<?php

namespace App\EventListener;

use App\Entity\Image;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;

class ImageUploadListener implements EventSubscriber
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
     * {@inheritDoc}
     */
    public function getSubscribedEvents(): array
    {
        return ['prePersist', 'preUpdate', 'preRemove'];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        if ($args->getEntity() instanceof Image) {
            $this->handleUpload($args->getEntity());
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args): void
    {
        if ($args->getEntity() instanceof Image) {
            $this->handleUpload($args->getEntity());
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args): void
    {
        if ($args->getEntity() instanceof Image) {
            $this->handleRemove($args->getEntity());
        }
    }

    /**
     * @param Image $image
     */
    public function handleUpload(Image $image): void
    {
        if (!$image || !$file = $image->getResource()) {
            return;
        }

        $fileName = uniqid() . '.' . $file->guessClientExtension();

        $image->setFilename($fileName);
        $image->setFilePath($this->uploadDir . DIRECTORY_SEPARATOR . $fileName);
        $image->setPublicPath($this->publicDir . "/$fileName");

        $file->move($this->uploadDir, $fileName);

        $image->setMimeType(mime_content_type($image->getFilePath()));
    }

    /**
     * @param Image $image
     */
    public function handleRemove(Image $image): void
    {
        if (!$this->fs->exists($image->getFilePath())) {
            throw new FileNotFoundException("File " . $image->getFilePath() . " does not exist or appears to be missing");
        }

        $this->fs->remove($image->getFilePath());
    }
}
