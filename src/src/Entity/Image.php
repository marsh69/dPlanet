<?php

namespace App\Entity;

use Gedmo\Timestampable\Traits\TimestampableEntity;

class Image
{
    use IdTrait;
    use IsDeletedTrait;
    use TimestampableEntity;

    /** @var string|null $mimeType */
    protected $mimeType = '';
    /** @var string $filename */
    protected $filename = '';
    /** @var string $filePath */
    protected $filePath = '';
    /** @var string $publicPath */
    protected $publicPath = '';
    /** @var resource|null $resource */
    protected $resource;

    /**
     * @return string|null
     */
    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    /**
     * @param string|null $mimeType
     * @return Image
     */
    public function setMimeType(?string $mimeType): Image
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     * @return Image
     */
    public function setFilename(string $filename): Image
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @param string $filePath
     * @return Image
     */
    public function setFilePath(string $filePath): Image
    {
        $this->filePath = $filePath;
        return $this;
    }

    /**
     * @return string
     */
    public function getPublicPath(): string
    {
        return $this->publicPath;
    }

    /**
     * @param string $publicPath
     * @return Image
     */
    public function setPublicPath(string $publicPath): Image
    {
        $this->publicPath = $publicPath;
        return $this;
    }

    /**
     * @return resource|null
     */
    public function getResource(): ?resource
    {
        return $this->resource;
    }

    /**
     * @param resource|null $resource
     * @return Image
     */
    public function setResource(?resource $resource): Image
    {
        $this->resource = $resource;
        return $this;
    }
}