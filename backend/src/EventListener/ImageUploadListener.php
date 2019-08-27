<?php

namespace App\EventListener;

use App\Entity\Image;
use Symfony\Component\Filesystem\Filesystem;

class ImageUploadListener
{
    /** @var Filesystem $fs */
    protected $fs;
    /** @var string $uploadDir */
    protected $uploadDir;
    /** @var string $publicDir */
    protected $publicDir;

    /**
     * ImageUploadListener constructor.
     * @param Filesystem $fs
     * @param string $uploadDir
     * @param string $publicDir
     */
    public function __construct(Filesystem $fs, string $uploadDir, string $publicDir)
    {
        $this->fs = $fs;
        $this->uploadDir = $uploadDir;
        $this->publicDir = $publicDir;
    }

    /**
     * TODO: Add webp, webm and mp4 conversion
     *
     * @param Image $image
     */
    public function handleUpload(Image $image): void
    {
        if (!$image || !$file = $image->getResource()) {
            return;
        }

        $fileName = uniqid() . ".{$file->guessExtension()}";

        $image->setFilename($fileName);
        $image->setFilePath($this->uploadDir . DIRECTORY_SEPARATOR . $fileName);
        $image->setPublicPath("{$this->publicDir}/$fileName");

        $file->move($this->uploadDir, $fileName);

        $image->setMimeType(
            mime_content_type($image->getFilePath())
        );
    }

    /**
     * @param Image $image
     */
    public function handleRemove(Image $image): void
    {
        $this->fs->remove($image->getFilePath());
    }
}
