<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Modelo de la tabla usuario.
 *
 * Representa todos los usuarios del sistema.
 */
class UsuariosTable extends Table
{

    /**
     * Configuración del modelo.
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        // Nombre de la tabla
        $this->setTable('usuario');

        // Llave primaria
        $this->setPrimaryKey('id_usuario');

        // Campo que representará al usuario
        $this->setDisplayField('nombre');

        /*
         * Un usuario puede tener muchas vinculaciones.
         *
         * Ejemplo:
         *
         * Juan
         * ↓
         * Coach Ana
         * Coach Pedro
         * Coach Luis
         */
        $this->hasMany('Vinculaciones', [
            'foreignKey' => 'id_usuario'
        ]);

        /*
         * Un usuario puede tener muchas tareas.
         */
        $this->hasMany('Tareas', [
            'foreignKey' => 'id_usuario'
        ]);

        /*
         * Un usuario puede tener muchos hábitos.
         */
        $this->hasMany('Habitos', [
            'foreignKey' => 'id_usuario'
        ]);

    }

    /**
     * Validaciones.
     */
    public function validationDefault(Validator $validator): Validator
    {
        return $validator;
    }

}