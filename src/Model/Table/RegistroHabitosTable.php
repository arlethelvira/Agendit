<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\EventInterface;
use Cake\Datasource\EntityInterface;
use ArrayObject;
use Cake\I18n\FrozenTime;

/**
 * Modelo de la tabla registro_habito.
 *
 * Guarda si un hábito fue marcado como
 * cumplido por el socio en una fecha específica.
 */
class RegistroHabitosTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('registro_habito');
        $this->setPrimaryKey('id_registro');

        /*
         * Cada registro pertenece a un hábito.
         */
        $this->belongsTo('Habitos', [
            'foreignKey' => 'id_habito'
        ]);
    }

    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        if ($entity->isNew() && empty($entity->fecha_registro)) {
            $entity->fecha_registro = FrozenTime::now();
        }
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence('id_habito', 'create')
            ->notEmptyString('id_habito');

        $validator
            ->requirePresence('fecha', 'create')
            ->date('fecha')
            ->notEmptyDate('fecha');

        $validator
            ->boolean('completado')
            ->notEmptyString('completado');

        return $validator;
    }
}