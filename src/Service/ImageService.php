<?php

namespace App\Service;

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class ImageService
{
    /** @var S3Client */
    private $s3;

    public function __construct()
    {
        $this->s3 = new S3Client([
            //ToDo
        ]);
    }

    public function saveImage(string $path, $content): void
    {
        try {
            $this->s3->putObject([
                //ToDo
            ]);
        } catch (S3Exception $e) {
            //ToDo
        }
    }
}