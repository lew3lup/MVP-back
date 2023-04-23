<?php

namespace App\Service;

use App\Entity\Descriptionable;
use App\Exception\BadRequestException;

class DescriptionService
{
    /**
     * @param Descriptionable $entity
     * @param array $data
     * @param int $maxNameLength
     * @param int $maxDescriptionLength
     * @return Descriptionable
     */
    public function setData(
        Descriptionable $entity,
        array $data,
        ?int $maxNameLength = null,
        ?int $maxDescriptionLength = null
    ): Descriptionable {
        if (!$this->checkMultiLangData($data['name'], $maxNameLength)) {
            throw new BadRequestException('INVALID_NAME');
        }
        if (!$this->checkMultiLangData($data['description'], $maxDescriptionLength)) {
            throw new BadRequestException('INVALID_DESCRIPTION');
        }
        return $entity->setName($data['name'])->setDescription($data['name']);
    }

    /**
     * @param $data
     * @param int|null $maxSymbols
     * @return bool
     */
    private function checkMultiLangData($data, ?int $maxSymbols): bool
    {
        if (empty($data) || !is_array($data) || empty($data[Descriptionable::DEFAULT_LANGUAGE])) {
            return false;
        }
        foreach ($data as $key => $value) {
            if (
                !in_array($key, Descriptionable::LANGUAGES) ||
                empty($value) || !is_string($value) || ($maxSymbols && iconv_strlen($value) > $maxSymbols)
            ) {
                return false;
            }
        }
        return true;
    }
}