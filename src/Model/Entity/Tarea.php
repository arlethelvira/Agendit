<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Tarea extends Entity
{
    protected array $_accessible = [
        'id_usuario' => true,
        'id_categoria' => true,
        'id_especialista' => true,
        'titulo' => true,
        'notas' => true,
        'fecha_creacion' => true,
        'fecha_limite' => true,
        'hora_limite' => true,
        'fecha_completada' => true,
        'hora_recordatorio' => true,
        'estado' => true,
        'usuario' => true,
        'categoria' => true,
        'especialista' => true,
        'subtareas' => true,
    ];

    // Getter virtual: true/false en vez de manejar fecha_completada directo en las vistas/JS
    protected function _getCompletada(): bool
    {
        return !empty($this->fecha_completada);
    }

    protected function _getAsignadaPorEspecialista(): bool
    {
        return !empty($this->id_especialista);
    }
}