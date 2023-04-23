<?php

namespace App\Service;

use App\Entity\Descriptionable;
use App\Exception\BadRequestException;

class DescriptionService
{
    /**
     * @param Descriptionable $entity
     * @param array $data
     * @return Descriptionable
     */
    public function setData(Descriptionable $entity, array $data): Descriptionable
    {
        if (!$this->checkMultiLangData($data['name'])) {
            throw new BadRequestException('INVALID_NAME');
        }
        if (!$this->checkMultiLangData($data['description'])) {
            throw new BadRequestException('INVALID_DESCRIPTION');
        }
        return $entity->setName($data['name'])->setDescription($data['name']);
    }

    /**
     * @param $data
     * @return bool
     */
    private function checkMultiLangData($data): bool
    {
        if (empty($data) || !is_array($data) || empty($data[Descriptionable::DEFAULT_LANGUAGE])) {
            return false;
        }
        foreach ($data as $key => $value) {
            if (!in_array($key, Descriptionable::LANGUAGES) || empty($value) || !is_string($value)) {
                return false;
            }
        }
        return true;
    }
}