<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Categoria extends Entity
{
    protected array $_accessible = [
        'id_usuario' => true,
        'nombre' => true,
        'color' => true,
        'usuario' => true,
        'tareas' => true,
    ];
}