<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Categoria extends Entity
{
    protected array $_accessible = [
        'nombre' => true,
        'color' => true,
        'tareas' => true,
    ];
}