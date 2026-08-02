<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;

/**
 * Modelo de la tabla vinculacion.
 *
 * Representa todas las vinculaciones entre un
 * usuario (socio) y un especialista.
 */
class VinculacionesTable extends Table
{

    /**
     * Configuración del modelo.
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        // Nombre de la tabla en PostgreSQL
        $this->setTable('vinculacion');

        // Llave primaria
        $this->setPrimaryKey('id_vinculacion');

        // Campo representativo
        $this->setDisplayField('estado');

        /*
         * Una vinculación pertenece a un usuario.
         */
        $this->belongsTo('Usuarios', [
            'foreignKey' => 'id_usuario',
            'joinType' => 'INNER'
        ]);

        /*
         * Una vinculación pertenece a un especialista.
         */
        $this->belongsTo('Especialistas', [
            'foreignKey' => 'id_especialista',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Validaciones de formulario.
     */
    public function validationDefault(Validator $validator): Validator
    {

        // Fecha de inicio
        $validator
            ->date('fecha_inicio')
            ->requirePresence('fecha_inicio', 'create')
            ->notEmptyDate('fecha_inicio');

        // Fecha de fin (opcional)
        $validator
            ->allowEmptyDate('fecha_fin');

        // Estado de la vinculación
        $validator
            ->scalar('estado')
            ->maxLength('estado', 20)
            ->requirePresence('estado', 'create')
            ->notEmptyString('estado');

        return $validator;
    }

    /**
     * Reglas de integridad de la base de datos.
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {

        // Verifica que el usuario exista
        $rules->add(
            $rules->existsIn(
                ['id_usuario'],
                'Usuarios'
            )
        );

        // Verifica que el especialista exista
        $rules->add(
            $rules->existsIn(
                ['id_especialista'],
                'Especialistas'
            )
        );

        // Evita que un usuario se vincule dos veces
        // con el mismo especialista.
        $rules->add(
            $rules->isUnique(
                ['id_usuario', 'id_especialista'],
                'Este usuario ya está vinculado con este especialista.'
            )
        );

        return $rules;
    }

}