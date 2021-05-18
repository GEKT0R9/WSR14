<?php

namespace app\repository;

use app\entity\Files;

class FileRepository
{
    /**
     * Получение контента по идентификатору файла
     * @param int $id идентификатор файла
     * @return mixed|null
     */
    public static function getContentFileById($id) {
        return Files::find()->where(['id' => $id])->one()->file_content;
    }

    public static function getFileById($id) {
        return Files::find()->where(['id' => $id])->one();
    }

    /**
     * Создание файла
     * @param string $name название файла
     * @param string $file_content содержимое файла
     * @param int $size размер файла
     * @param string $type тип файла
     * @return Files
     */
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