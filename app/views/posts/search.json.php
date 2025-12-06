<?php
foreach ($posts as $post) {
    $thumbnailPath = $post->firstPhotoUrl();

    $json[] = [
        'id'          => $post->id,
        'title'       => $post->title,
        'description' => $post->description,
        'thumbnail'   => ['path' => $thumbnailPath]
    ];
}
