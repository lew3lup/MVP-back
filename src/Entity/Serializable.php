<?php

namespace App\Entity;

use JsonSerializable;

abstract class Serializable implements JsonSerializable
{
    /**
     * @return array
     */
    abstract public function jsonSerialize(): array;

    /**
     * @return array
     */
    public function jsonSerializeDetailed(): array
    {
        return $this->jsonSerialize();
    }
}