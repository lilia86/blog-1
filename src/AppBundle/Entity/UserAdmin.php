<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * Admin.
 *
 * @ORM\Entity
 */
class UserAdmin extends User
{
    use Timestampable;
}
