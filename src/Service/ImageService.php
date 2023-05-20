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
    public const RESIZE_TYPE_CONTAIN = 1;
    public const RESIZE_TYPE_COVER = 2;

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
     * @param int $newWidth
     * @param int $newHeight
     * @param int $resizeType
     * @param int $quality
     * @return Image
     */
    public function uploadImage(
        Image $image,
        string $path,
        UploadedFile $file,
        int $newWidth = 0,
        int $newHeight = 0,
        int $resizeType = self::RESIZE_TYPE_CONTAIN,
        int $quality = 98
    ): Image {
        return $image->setPath($path)->setUrl($this->uploadImageFile(
            $path,
            $file,
            $newWidth,
            $newHeight,
            $resizeType,
            $quality
        ));
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
     * @param int $newWidth
     * @param int $newHeight
     * @param int $resizeType
     * @param int $quality
     * @return string
     */
    public function uploadImageFile(
        string $path,
        UploadedFile $file,
        int $newWidth = 0,
        int $newHeight = 0,
        int $resizeType = self::RESIZE_TYPE_CONTAIN,
        int $quality = 98
    ): string {
        $this->validateImageFile($file);
        if ($newWidth || $newHeight || $quality !== 100) {
            $this->compressImage($file, $newWidth, $newHeight, $resizeType, $quality);
        }
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
    private function validateImageFile(UploadedFile $file): void
    {
        if (!$file->getMimeType() || !in_array($file->getMimeType(), ['image/jpeg', 'image/png'])) {
            throw new IncorrectFileFormatException();
        }
    }

    /**
     * @param UploadedFile $file
     * @param int $newWidth
     * @param int $newHeight
     * @param int $resizeType
     * @param int $quality
     */
    private function compressImage(
        UploadedFile $file,
        int $newWidth,
        int $newHeight,
        int $resizeType,
        int $quality = 100
    ): void {
        list($width, $height) = getimagesize($file->getRealPath());
        if ($newWidth || $newHeight) {
            $ratio = $width / $height;
            if ($resizeType === self::RESIZE_TYPE_COVER) {
                //Cover
                if ($newWidth && $newWidth < $width) {
                    $newHeight1 = $newWidth / $ratio;
                    if ($newHeight && $newHeight > $newHeight1) {
                        $newWidth = (int)($newHeight * $ratio);
                    } else {
                        $newHeight = (int)$newHeight1;
                    }
                } elseif ($newHeight && $newHeight < $height) {
                    $newWidth1 = $newHeight * $ratio;
                    if ($newWidth && $newWidth > $newWidth1) {
                        $newHeight = (int)($newWidth / $ratio);
                    } else {
                        $newWidth = (int)$newWidth1;
                    }
                }
            } else {
                //Contain
                if ($newWidth && $newWidth < $width) {
                    $newHeight1 = $newWidth / $ratio;
                    if ($newHeight && $newHeight < $newHeight1) {
                        $newWidth = (int)($newHeight * $ratio);
                    } else {
                        $newHeight = (int)$newHeight1;
                    }
                } elseif ($newHeight && $newHeight < $height) {
                    $newWidth1 = $newHeight * $ratio;
                    if ($newWidth && $newWidth < $newWidth1) {
                        $newHeight = (int)($newWidth / $ratio);
                    } else {
                        $newWidth = (int)$newWidth1;
                    }
                }
            }
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }
        $imageTrueColor = imagecreatetruecolor($newWidth, $newHeight);
        $image = imagecreatefromstring($file->getContent());
        imagecopyresampled(
            $imageTrueColor,
            $image,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight,
            $width,
            $height
        );
        imagejpeg($imageTrueColor, $file->getRealPath(), $quality);
    }
}