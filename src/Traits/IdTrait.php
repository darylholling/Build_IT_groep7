<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait IdTrait
 */
trait IdTrait
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}