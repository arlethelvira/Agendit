<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Entity de una vinculación.
 *
 * Representa la relación entre
 * un socio y un especialista.
 */
class Vinculacion extends Entity
{

    protected array $_accessible = [

        'id_usuario' => true,
        'id_especialista' => true,
        'fecha_inicio' => true,
        'fecha_fin' => true,
        'estado' => true,

        /*
         * Relaciones
         */
        'usuario' => true,
        'especialista' => true,
    ];

}