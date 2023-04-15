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
        //ToDo: детальные проверки
        if (
            empty($data['name']) || !is_array($data['name']) ||
            empty($data['description']) || !is_array($data['description'])
        ) {
            throw new BadRequestException();
        }
        return $entity->setName($data['name'])->setDescription($data['name']);
    }
}