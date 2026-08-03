<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * ============================================================
 * Modelo de la tabla usuario
 * ============================================================
 *
 * Este archivo representa la tabla "usuario".
 *
 * Aquí NO se hacen consultas.
 *
 * Aquí solamente definimos:
 *
 * • Cómo se llama la tabla.
 * • Cuál es su llave primaria.
 * • Sus relaciones.
 * • Sus validaciones.
 * • Sus reglas de negocio.
 *
 */

class UsuariosTable extends Table
{

    /**
     * ============================================================
     * Configuración del modelo
     * ============================================================
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        /*
         * Nombre real de la tabla en PostgreSQL.
         */
        $this->setTable('usuario');

        /*
         * Llave primaria.
         */
        $this->setPrimaryKey('id_usuario');

        /*
         * Campo representativo.
         *
         * CakePHP lo usa cuando necesita
         * mostrar el usuario como texto.
         */
        $this->setDisplayField('nombre');



        /*
         * ==========================================
         * RELACIONES
         * ==========================================
         */



        /*
         * Un usuario puede tener
         * muchas vinculaciones.
         *
         * usuario
         *      ↓
         * vinculacion
         */
        $this->hasMany('Vinculaciones', [
            'foreignKey' => 'id_usuario'
        ]);


        /*
         * Un usuario puede tener
         * muchas tareas.
         */
        $this->hasMany('Tareas', [
            'foreignKey' => 'id_usuario'
        ]);


        /*
         * Un usuario puede tener
         * muchos hábitos.
         */
        $this->hasMany('Habitos', [
            'foreignKey' => 'id_usuario'
        ]);


        /*
         * Un usuario puede ser especialista.
         *
         * (Solo algunos usuarios tendrán
         * un registro en la tabla especialista.)
         */
        $this->hasOne('Especialistas', [
            'foreignKey' => 'id_usuario'
        ]);

    }



    /**
     * ============================================================
     * VALIDACIONES
     * ============================================================
     *
     * Estas validaciones se ejecutan
     * ANTES de guardar.
     *
     * Si alguna falla,
     * save() devolverá false.
     */
    public function validationDefault(
        Validator $validator
    ): Validator
    {

        /*
         * -----------------------
         * Nombre
         * -----------------------
         */
        $validator
            ->scalar('nombre')
            ->maxLength('nombre', 100)
            ->requirePresence('nombre', 'create')
            ->notEmptyString('nombre', 'Ingrese su nombre');


        /*
         * -----------------------
         * Apellido paterno
         * -----------------------
         */
        $validator
            ->scalar('apellido_paterno')
            ->maxLength('apellido_paterno', 100)
            ->requirePresence('apellido_paterno', 'create')
            ->notEmptyString(
                'apellido_paterno',
                'Ingrese su apellido paterno'
            );


        /*
         * -----------------------
         * Apellido materno
         * -----------------------
         */
        $validator
            ->scalar('apellido_materno')
            ->maxLength('apellido_materno', 100)
            ->requirePresence('apellido_materno', 'create')
            ->notEmptyString(
                'apellido_materno',
                'Ingrese su apellido materno'
            );


        /*
         * -----------------------
         * Correo electrónico
         * -----------------------
         */
        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString(
                'email',
                'Ingrese un correo electrónico'
            );


        /*
         * -----------------------
         * Contraseña
         * -----------------------
         */
        $validator
            ->scalar('contrasena')
            ->minLength(
                'contrasena',
                8,
                'Debe contener mínimo 8 caracteres'
            )
            ->requirePresence('contrasena', 'create')
            ->notEmptyString(
                'contrasena',
                'Ingrese una contraseña'
            );


        return $validator;
    }



    /**
     * ============================================================
     * REGLAS DE NEGOCIO
     * ============================================================
     *
     * Aquí van reglas que consultan
     * la base de datos.
     *
     * Ejemplo:
     *
     * • correo único
     * • CURP única
     * • RFC único
     */
    public function buildRules(
        RulesChecker $rules
    ): RulesChecker
    {

        /*
         * El correo no puede repetirse.
         */
        $rules->add(
            $rules->isUnique(
                ['email'],
                'Ese correo ya está registrado.'
            )
        );

        return $rules;
    }

}