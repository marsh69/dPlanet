<?php

namespace App\Service;

use App\Entity\Image;
use Symfony\Component\Filesystem\Filesystem;

class UploadService
{
    /** @var Filesystem $fs */
    protected $fs;
    /** @var string $uploadDir */
    protected $uploadDir;
    /** @var string $publicDir */
    protected $publicDir;

    /**
     * UploadService constructor.
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
     * TODO: Webp/webm/mp4 conversion
     *
     * @param Image $image
     * @return Image|null
     */
    public function uploadImage(Image $image): ?Image
    {
        $uploadedFile = $image->getResource();

        if (!$uploadedFile) {
            return null;
        }

        $fileName = md5(uniqid()) . '.' . $uploadedFile->guessClientExtension();
        $filePath = $this->uploadDir . DIRECTORY_SEPARATOR . $fileName;
        $publicPath = $this->publicDir . "/$fileName";
        $mimeType = mime_content_type($uploadedFile->getPathname());

        $uploadedFile->move($this->uploadDir, $fileName);

        $image->setFilename($fileName);
        $image->setFilePath($filePath);
        $image->setMimeType($mimeType);
        $image->setPublicPath($publicPath);

        return $image;
    }
}
