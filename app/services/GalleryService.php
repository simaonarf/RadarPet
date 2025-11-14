<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostPhoto;
use App\Models\User;
use Lib\Validations;

class GalleryService
{
    private const UPLOAD_DIR = '/assets/uploads/';
    private const MAX_SIZE = 5 * 1024 * 1024; // 5MB
    private const ALLOWED_MIMES = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    private const MAX_PHOTOS = 10;

    public function createPostWithPhotos(array $postData, array $files, User $user): Post
    {
        $post = new Post([
            'title' => $postData['title'] ?? '',
            'description' => $postData['description'] ?? '',
            'user_id' => $user->id
        ]);

        $post->validates();

        if ($post->hasErrors() || !$this->validateFiles($files, $post) || !$post->save()) {
            return $post;
        }

        if (!$this->processPhotos($files, $post)) {
            $this->rollback($post, []);
        }

        return $post;
    }

    public function addPhotos(Post $post, array $files): bool
    {
        $normalizedFiles = $this->normalizeFilesArray($files);

        if (empty($normalizedFiles)) {
            return true;
        }

        $currentCount = count($post->photos()->get());
        $newCount = count($normalizedFiles);

        if (!Validations::photosLimit($currentCount, $newCount, $post, self::MAX_PHOTOS)) {
            return false;
        }

        if (!$this->validateFiles($normalizedFiles, $post)) {
            return false;
        }

        return $this->processPhotos($normalizedFiles, $post, false);
    }

    public function deletePhoto(int $photoId, Post $post): bool
    {
        $photo = PostPhoto::findById($photoId);

        if (!$photo || $photo->post_id !== $post->id) {
            $post->addError('photo', 'foto não encontrada.');
            return false;
        }

        $this->deletePhysicalFile($photo->path);

        return $photo->destroy();
    }

    public function deleteAllPhotos(Post $post): bool
    {
        foreach ($post->photos()->get() as $photo) {
            $this->deletePhysicalFile($photo->path);
        }

        return true;
    }

    public function normalizeFilesArray(array $files): array
    {
        if (empty($files['name']) || !is_array($files['name'])) {
            return empty($files['name']) ? [] : [$files];
        }

        $normalized = [];
        $fileCount = count($files['name']);

        for ($i = 0; $i < $fileCount; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            $normalized[] = [
                'name' => $files['name'][$i],
                'type' => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error' => $files['error'][$i],
                'size' => $files['size'][$i]
            ];
        }

        return $normalized;
    }

    private function validateFiles(array $files, Post $post): bool
    {
        if (!Validations::filesNotEmpty($files, $post, self::MAX_PHOTOS)) {
            return false;
        }

        foreach ($files as $index => $file) {
            if (!Validations::fileValid($file, $post, $index + 1, self::MAX_SIZE, self::ALLOWED_MIMES)) {
                return false;
            }
        }

        return true;
    }

    private function processPhotos(array $files, Post $post, bool $rollbackPost = true): bool
    {
        $uploadedPhotos = [];

        foreach ($files as $index => $file) {
            $photoPath = $this->uploadPhoto($file, $post->id);

            if (!$photoPath || !$this->savePhoto($photoPath, $post->id)) {
                if ($rollbackPost) {
                    $this->rollback($post, $uploadedPhotos);
                } else {
                    $this->deleteUploadedPhotos($uploadedPhotos);
                }
                $post->addError('photos', 'erro ao processar foto ' . ($index + 1));
                return false;
            }

            $uploadedPhotos[] = $photoPath;
        }

        return true;
    }

    private function uploadPhoto(array $file, int $postId): ?string
    {
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = "post_{$postId}_" . uniqid() . '.' . $extension;
        $relativePath = self::UPLOAD_DIR . $fileName;
        $absolutePath = $this->getAbsolutePath($relativePath);

        $dir = dirname($absolutePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        if (!move_uploaded_file($file['tmp_name'], $absolutePath)) {
            return null;
        }

        chmod($absolutePath, 0644);

        return $relativePath;
    }

    private function savePhoto(string $path, int $postId): bool
    {
        $postPhoto = new PostPhoto([
            'post_id' => $postId,
            'path' => $path
        ]);

        if (!$postPhoto->save()) {
            $this->deletePhysicalFile($path);
            return false;
        }

        return true;
    }

    private function rollback(Post $post, array $uploadedPhotos): void
    {
        $this->deleteUploadedPhotos($uploadedPhotos);
        $post->destroy();
    }

    private function deleteUploadedPhotos(array $uploadedPhotos): void
    {
        foreach ($uploadedPhotos as $photoPath) {
            $this->deletePhysicalFile($photoPath);
        }
    }

    private function deletePhysicalFile(string $relativePath): void
    {
        $absolutePath = $this->getAbsolutePath($relativePath);
        if (file_exists($absolutePath)) {
            @unlink($absolutePath);
        }
    }
    
    private function getAbsolutePath(string $relativePath): string
    {
        return __DIR__ . '/../../public' . $relativePath;
    }
}