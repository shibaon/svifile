<?php

namespace Svi\FileBundle;

use Svi\FileBundle\Service\FileService;
use Svi\FileBundle\Service\ImageService;

trait BundleTrait
{
    /**
     * @return FileService
     */
    public function getFileService()
    {
        return $this->app[FileService::class];
    }

    /**
     * @return ImageService
     */
    public function getImageService()
    {
        return $this->app[ImageService::class];
    }
}