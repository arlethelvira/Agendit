<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\EventInterface;
use Cake\I18n\DateTime;
use Cake\Datasource\EntityInterface;
use ArrayObject;

/**
 * Habitos Model
 *
 * Tabla real: habito
 * Columnas: id_habito, id_usuario, id_especialista, titulo, notas,
 *           frecuencia, color, fecha_creacion
 */
class HabitosTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('habito');
        $this->setPrimaryKey('id_habito');
        $this->setDisplayField('titulo');
    }

    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        if ($entity->isNew() && empty($entity->fecha_creacion)) {
            $entity->fecha_creacion = DateTime::now();
        }
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id_usuario')
            ->requirePresence('id_usuario', 'create')
            ->notEmptyString('id_usuario');

        $validator
            ->scalar('titulo')
            ->maxLength('titulo', 30)
            ->requirePresence('titulo', 'create')
            ->notEmptyString('titulo');

        $validator
            ->scalar('notas')
            ->maxLength('notas', 60)
            ->allowEmptyString('notas');

        $validator
            ->scalar('frecuencia')
            ->maxLength('frecuencia', 60)
            ->requirePresence('frecuencia', 'create')
            ->notEmptyString('frecuencia');

        $validator
            ->scalar('color')
            ->maxLength('color', 30)
            ->requirePresence('color', 'create')
            ->notEmptyString('color');

        $validator
            ->integer('id_especialista')
            ->allowEmptyString('id_especialista');

        return $validator;
    }
}