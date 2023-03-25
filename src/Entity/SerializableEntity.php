<?php

namespace App\Entity;

use JsonSerializable;

abstract class SerializableEntity implements JsonSerializable
{
    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function jsonSerializeDetailed(): array
    {
        return $this->jsonSerialize();
    }
}