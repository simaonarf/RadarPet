<?php

namespace Lib;

use Core\Database\Database;

class Validations
{
    public static function notEmpty($attribute, $obj)
    {
        if ($obj->$attribute === null || $obj->$attribute === '') {
            $obj->addError($attribute, 'não pode ser vazio!');
            return false;
        }

        return true;
    }

    public static function passwordConfirmation($obj)
    {
        if ($obj->password !== $obj->password_confirmation) {
            $obj->addError('password', 'as senhas devem ser idênticas!');
            return false;
        }

        return true;
    }

    public static function uniqueness($fields, $object)
    {
        $dbFieldsValues = [];
        $objFieldValues = [];

        if (!is_array($fields)) {
            $fields = [$fields];
        }

        if (!$object->newRecord()) {
            $dbObject = $object::findById($object->id);

            foreach ($fields as $field) {
                $dbFieldsValues[] = $dbObject->$field;
                $objFieldValues[] = $object->$field;
            }

            if (
                !empty($dbFieldsValues) &&
                !empty($objFieldValues) &&
                $dbFieldsValues === $objFieldValues
            ) {
                return true;
            }
        }

        $table = $object::table();
        $conditions = implode(' AND ', array_map(fn($field) => "{$field} = :{$field}", $fields));

        $sql = <<<SQL
            SELECT id FROM {$table} WHERE {$conditions};
        SQL;

        $pdo = Database::getDatabaseConn();
        $stmt = $pdo->prepare($sql);

        foreach ($fields as $field) {
            $stmt->bindValue($field, $object->$field);
        }

        $stmt->execute();

        if ($stmt->rowCount() !== 0) {
            foreach ($fields as $field) {
                $object->addError($field, 'já existe um registro com esse dado');
            }
            return false;
        }

        return true;
    }

    public static function filesNotEmpty(array $files, $obj, int $maxPhotos = 10): bool
    {
        // Fotos opcionais -> se não mandar nenhuma, está OK
        if (empty($files)) {
            return true;
        }

        if (count($files) > $maxPhotos) {
            $obj->addError('photos', "máximo de {$maxPhotos} fotos permitidas.");
            return false;
        }

        return true;
    }

    public static function fileValid(array $file, $obj, int $photoNumber = 1, int $maxSize = 5242880, array $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp']): bool
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $obj->addError('photos', "erro no upload da foto {$photoNumber}.");
            return false;
        }

        if ($file['size'] > $maxSize) {
            $maxMB = round($maxSize / 1048576, 1);
            $obj->addError('photos', "foto {$photoNumber} muito grande (Max {$maxMB}MB).");
            return false;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedMimes)) {
            $obj->addError('photos', "tipo de arquivo {$photoNumber} não permitido.");
            return false;
        }

        return true;
    }

    public static function photosLimit(int $currentCount, int $newCount, $obj, int $maxPhotos = 10): bool
    {
        if (($currentCount + $newCount) > $maxPhotos) {
            $obj->addError('photos', "limite de {$maxPhotos} fotos excedido.");
            return false;
        }

        return true;
    }
}
