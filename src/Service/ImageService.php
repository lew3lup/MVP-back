<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageService
{
    /** @var S3Service */
    private $s3Service;

    /**
     * ImageService constructor.
     * @param ParameterBagInterface $parameterBag
     * @param LoggerInterface $logger
     */
    public function __construct(ParameterBagInterface $parameterBag, LoggerInterface $logger)
    {
        $this->s3Service = new S3Service(
            $parameterBag,
            $logger,
            $parameterBag->get('imagesBucketName'),
            $parameterBag->get('imagesRootDir')
        );
    }

    /**
     * @param string $path
     * @param UploadedFile $file
     * @return string
     */
    public function uploadImage(string $path, UploadedFile $file): string
    {
        return $this->s3Service->uploadFile($path, $file->getContent());
    }

    /**
     * @param string $path
     */
    public function removeImage(string $path): void
    {
        $this->s3Service->removeFile($path);
    }
}