<?php

namespace App\Service;

use App\Exception\ServiceUnavailableException;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class S3Service
{
    /** @var S3Client */
    private $s3;
    /** @var LoggerInterface */
    private $logger;
    /** @var string */
    private $bucketName;
    /** @var string */
    private $rootDir;

    /**
     * S3Service constructor.
     * @param ParameterBagInterface $parameterBag
     * @param LoggerInterface $logger
     * @param string $bucketName
     * @param string $rootDir
     */
    public function __construct(
        ParameterBagInterface $parameterBag,
        LoggerInterface $logger,
        string $bucketName,
        string $rootDir
    ) {
        $this->logger = $logger;
        $this->bucketName = $bucketName;
        $this->rootDir = $rootDir;
        $this->s3 = new S3Client([
            'version' => 'latest',
            'region' => $parameterBag->get('awsRegion'),
            'credentials' => [
                'key' => $parameterBag->get('awsAccessKeyId'),
                'secret' => $parameterBag->get('awsSecretAccessKey')
            ]
        ]);
    }

    /**
     * @param string $path
     * @param string $content
     * @return string
     */
    public function uploadFile(string $path, string $content): string
    {
        try {
            $result = $this->s3->putObject([
                'Bucket' => $this->bucketName,
                'Key' => $this->rootDir . '/' . $path,
                'Body' => $content,
                'ACL' => 'public-read'
            ]);
            return $result->get('ObjectURL');
        } catch (S3Exception $e) {
            $this->logger->error($e);
            throw new ServiceUnavailableException();
        }
    }

    public function removeFile(string $path): void
    {
        //ToDo
    }
}