<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Modelo de la tabla especialista.
 *
 * Representa todos los especialistas registrados
 * en Agendit.
 */
class EspecialistasTable extends Table
{

    /**
     * Configuración del modelo.
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        // Nombre de la tabla
        $this->setTable('especialista');

        // Llave primaria
        $this->setPrimaryKey('id_especialista');

        // Campo representativo
        $this->setDisplayField('cedula_profesional');

        /*
         * Cada especialista pertenece a un usuario.
         *
         * Porque primero existe un usuario
         * y después se convierte en especialista.
         */
        $this->belongsTo('Usuarios', [
            'foreignKey'=>'id_usuario'
        ]);

        /*
         * Cada especialista pertenece a un tipo.
         *
         * Coach
         * Nutriólogo
         * Psicólogo
         */
        $this->belongsTo('TipoEspecialistas', [
            'foreignKey'=>'id_tipo'
        ]);

        /*
         * Un especialista puede generar muchos códigos.
         */
        $this->hasMany('CodigoInvitacion', [
            'foreignKey'=>'id_especialista'
        ]);

        /*
         * Un especialista puede tener muchos socios.
         */
        $this->hasMany('Vinculaciones', [
            'foreignKey'=>'id_especialista'
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