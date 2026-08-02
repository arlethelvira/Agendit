<?php
declare(strict_types=1);

namespace App\Model\Table;

// Clase principal para trabajar con tablas
use Cake\ORM\Table;

// Se utiliza para validar los datos antes de guardarlos
use Cake\Validation\Validator;

// Se utiliza para validar reglas relacionadas con la base de datos
use Cake\ORM\RulesChecker;

/**
 * Modelo de la tabla codigo_invitacion.
 *
 * Este archivo representa TODA la tabla "codigo_invitacion"
 * de la base de datos.
 *
 * Aquí NO se hacen vistas ni HTML.
 * Aquí solamente se configura cómo CakePHP trabajará con la tabla.
 */
class CodigoInvitacionTable extends Table
{

    /**
     * Se ejecuta cuando CakePHP carga este modelo.
     *
     * Aquí se configura:
     * - Nombre de la tabla
     * - Llave primaria
     * - Campo representativo
     * - Relaciones con otras tablas
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        // Nombre real de la tabla en PostgreSQL
        $this->setTable('codigo_invitacion');

        // Llave primaria
        $this->setPrimaryKey('id_codigo');

        // Campo que CakePHP mostrará como representación del registro
        $this->setDisplayField('codigo');

        /*
         * RELACIONES
         *
         * Un código pertenece a un especialista.
         *
         * Esto significa que cada registro de codigo_invitacion
         * tiene un id_especialista.
         *
         * Gracias a esta relación podremos hacer consultas como:
         *
         * $codigo = $this->CodigoInvitacion
         *      ->get($id, ['contain' => ['Especialistas']]);
         *
         * sin escribir JOIN manualmente.
         */
        $this->belongsTo('Especialistas', [
            'foreignKey' => 'id_especialista',
            'joinType' => 'INNER'
        ]);
    }


    /**
     * VALIDACIONES
     *
     * Se ejecutan antes de guardar un registro.
     *
     * Sirven para validar que los datos enviados desde un formulario
     * sean correctos.
     */
    public function validationDefault(Validator $validator): Validator
    {

        // -----------------------------
        // Código de invitación
        // -----------------------------

        $validator
            ->scalar('codigo')                       // Debe ser texto
            ->maxLength('codigo', 20)                // Máximo 20 caracteres
            ->requirePresence('codigo', 'create')    // Obligatorio al crear
            ->notEmptyString('codigo');              // No puede venir vacío


        // -----------------------------
        // Fecha de expiración
        // -----------------------------

        $validator
            ->dateTime('fecha_expiracion')           // Debe ser una fecha válida
            ->requirePresence('fecha_expiracion', 'create')
            ->notEmptyDateTime('fecha_expiracion');


        // -----------------------------
        // Estado
        // -----------------------------

        $validator
            ->scalar('estado')
            ->maxLength('estado', 20)
            ->requirePresence('estado', 'create')
            ->notEmptyString('estado');


        return $validator;
    }


    /**
     * REGLAS DE INTEGRIDAD
     *
     * Aquí se validan reglas relacionadas con la base de datos.
     *
     * Ejemplo:
     * - Que el especialista exista.
     * - Que el código no esté repetido.
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {

        /*
         * Verifica que el id_especialista exista
         * en la tabla especialistas.
         */
        $rules->add(
            $rules->existsIn(
                ['id_especialista'],
                'Especialistas'
            )
        );

        /*
         * El código debe ser único.
         *
         * Aunque PostgreSQL ya tenga UNIQUE,
         * CakePHP detectará el problema antes
         * y mostrará un mensaje amigable.
         */
        $rules->add(
            $rules->isUnique(
                ['codigo'],
                'Este código ya existe.'
            )
        );

        return $rules;
    }

}