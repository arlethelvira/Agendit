<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Entity del catálogo de tipos
 * de especialistas.
 */
class TipoEspecialista extends Entity
{

    protected array $_accessible = [

        '*' => true,

        'id_tipo' => false,
    ];

}