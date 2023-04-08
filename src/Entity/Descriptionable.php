<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

abstract class Descriptionable extends Serializable
{
    /**
     * @return Collection|Description[]
     */
    public function getDescriptions(): Collection
    {
        return $this->descriptions;
    }
}