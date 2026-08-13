<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Registro de cumplimiento de un hábito
 * en una fecha específica.
 */
class RegistroHabito extends Entity
{
    protected array $_accessible = [
        'id_habito' => true,
        'fecha' => true,
        'completado' => true,
        'fecha_registro' => true,
    ];
}