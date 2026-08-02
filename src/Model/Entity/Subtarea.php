<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Subtarea extends Entity
{
    protected array $_accessible = [
        'id_tarea' => true,
        'titulo' => true,
        'fecha_creacion' => true,
        'completada' => true,
        'fecha_completada' => true,
        'tarea' => true,
    ];
}