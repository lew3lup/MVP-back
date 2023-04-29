<?php

namespace App\Service;

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageService
{
    private $s3Service;
    /** @var S3Client */
    private $s3;
    /** @var string */
    private $bucketName;
    /** @var string */
    private $rootDir;

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