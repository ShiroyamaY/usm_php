<?php

declare(strict_types=1);

/**
 * Класс для работы с галереей изображений.
 * Предоставляет методы для получения списка изображений из указанной директории.
 */
class ImageGallery {
    private string $directory;

    /**
     * Конструктор класса.
     *
     * @param string $directory Путь к директории с изображениями.
     */
    public function __construct(string $directory) {
        $this->directory = $directory;
    }

    /**
     * Возвращает массив изображений из директории.
     *
     * @return array Массив имен файлов изображений.
     */
    public function getImages(): array {
        $files = scandir($this->directory);
        $images = [];

        if ($files === false) {
            return $images;
        }

        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $fileInfo = pathinfo($file);
                if (isset($fileInfo['extension']) && strtolower($fileInfo['extension']) === 'jpg') {
                    $images[] = $file;
                }
            }
        }
        return $images;
    }
}

?>