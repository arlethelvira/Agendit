<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Entity de un código de invitación.
 *
 * Representa un solo registro de la tabla
 * codigo_invitacion.
 */
class CodigoInvitacion extends Entity
{

    /**
     * Campos que pueden asignarse automáticamente.
     */
    protected array $_accessible = [

        // Permitir asignación de estos campos
        'id_especialista' => true,
        'codigo' => true,
        'fecha_generacion' => true,
        'fecha_expiracion' => true,
        'usado' => true,
        'estado' => true,

        /*
         * Permite acceder al especialista relacionado.
         */
        'especialista' => true,
    ];

}