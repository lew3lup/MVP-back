<?php

namespace App\Service;

use App\Entity\Image;
use App\Exception\IncorrectFileFormatException;
use App\Exception\NotFoundException;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageService
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var ImageRepository */
    private $imageRepo;
    /** @var S3Service */
    private $s3Service;

    /**
     * ImageService constructor.
     * @param EntityManagerInterface $em
     * @param ImageRepository $imageRepo
     * @param ParameterBagInterface $parameterBag
     * @param LoggerInterface $logger
     */
    public function __construct(
        EntityManagerInterface $em,
        ImageRepository $imageRepo,
        ParameterBagInterface $parameterBag,
        LoggerInterface $logger
    ) {
        $this->em = $em;
        $this->imageRepo = $imageRepo;
        $this->s3Service = new S3Service(
            $parameterBag,
            $logger,
            $parameterBag->get('imagesBucketName'),
            $parameterBag->get('imagesRootDir')
        );
    }

    /**
     * @param int $id
     * @param int $adminId
     * @return Image
     */
    public function getByIdAndAdminId(int $id, int $adminId): Image
    {
        $image = $this->imageRepo->findOneByIdAndAdminId($id, $adminId);
        if (!$image) {
            throw new NotFoundException();
        }
        return $image;
    }

    /**
     * @param Image $image
     * @param string $path
     * @param UploadedFile $file
     * @return Image
     */
    public function uploadImage(Image $image, string $path, UploadedFile $file): Image
    {
        return $image->setPath($path)->setUrl($this->uploadImageFile($path, $file));
    }

    /**
     * @param Image $image
     * @return void
     */
    public function removeImage(Image $image): void
    {
        $this->removeImageFile($image->getPath());
        $this->em->remove($image);
    }

    /**
     * @param string $path
     * @param UploadedFile $file
     * @return string
     */
    public function uploadImageFile(string $path, UploadedFile $file): string
    {
        return $this->s3Service->uploadFile($path, $file->getContent());
    }

    /**
     * @param string $path
     */
    public function removeImageFile(string $path): void
    {
        $this->s3Service->removeFile($path);
    }

    /**
     * @param UploadedFile $file
     */
    public function validateImageFile(UploadedFile $file): void
    {
        if (!$file->getMimeType() || !in_array($file->getMimeType(), ['image/jpeg', 'image/png'])) {
            throw new IncorrectFileFormatException();
        }
    }
}