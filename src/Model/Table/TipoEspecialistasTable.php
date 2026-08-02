<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Modelo de la tabla tipo_especialista.
 *
 * Esta tabla funciona como un catálogo de los tipos
 * de especialistas permitidos en Agendit.
 *
 * Ejemplo:
 * 1 - Nutriólogo
 * 2 - Coach
 * 3 - Psicólogo
 *
 * En el futuro pueden agregarse más especialidades
 * sin modificar la estructura de la base de datos.
 */
class TipoEspecialistasTable extends Table
{

    /**
     * Configuración del modelo.
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        // Nombre real de la tabla
        $this->setTable('tipo_especialista');

        // Llave primaria
        $this->setPrimaryKey('id_tipo');

        // Campo representativo
        $this->setDisplayField('nombre');

        /*
         * Un tipo de especialista puede pertenecer
         * a muchos especialistas.
         *
         * Coach
         * │
         * ├── Juan
         * ├── Ana
         * └── Pedro
         */
        $this->hasMany('Especialistas', [
            'foreignKey' => 'id_tipo'
        ]);
    }

    /**
     * Validaciones.
     */
    public function validationDefault(Validator $validator): Validator
    {

        // Nombre
        $validator
            ->scalar('nombre')
            ->maxLength('nombre', 30)
            ->requirePresence('nombre', 'create')
            ->notEmptyString('nombre');

        // Descripción
        $validator
            ->scalar('descripcion')
            ->maxLength('descripcion', 100)
            ->requirePresence('descripcion', 'create')
            ->notEmptyString('descripcion');

        // Color
        $validator
            ->scalar('color')
            ->maxLength('color', 20)
            ->requirePresence('color', 'create')
            ->notEmptyString('color');

        return $validator;
    }

}