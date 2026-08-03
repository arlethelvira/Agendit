<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Habito Entity
 *
 * @property int $id_habito
 * @property int $id_usuario
 * @property int|null $id_especialista
 * @property string $titulo
 * @property string|null $notas
 * @property string $frecuencia
 * @property string|null $color
 * @property \Cake\I18n\FrozenTime $fecha_creacion
 */
class Habito extends Entity
{
    protected array $_accessible = [
        'id_usuario' => true,
        'id_especialista' => true,
        'titulo' => true,
        'notas' => true,
        'frecuencia' => true,
        'color' => true,
        'fecha_creacion' => true,
    ];
}