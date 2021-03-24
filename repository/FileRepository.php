<?php

namespace app\repository;

use app\entity\Files;

class FileRepository
{
    public static function getContentFileById($id) {
        return Files::find()->where(['id' => $id])->one()->file_content;
    }

    public static function createFile(
        $name,
        $file_content,
        $size,
        $type
    ) {
        $img = new Files;
        $img->name = $name;
        $img->file_content = $file_content;
        $img->size = $size;
        $img->permission = $type;
        $img->save();
        return $img;
    }
}