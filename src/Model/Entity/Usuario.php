<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Entity de Usuario.
 */
class Usuario extends Entity
{

    protected array $_accessible = [

        '*' => true,

        /*
         * Nunca permitir que el id
         * sea modificado automáticamente.
         */
        'id_usuario' => false,
    ];

}