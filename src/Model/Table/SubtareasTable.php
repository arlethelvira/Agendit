<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\EventInterface;
use Cake\Datasource\EntityInterface;
use ArrayObject;

class SubtareasTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('subtarea');
        $this->setPrimaryKey('id_subtarea');

        $this->belongsTo('Tareas', [
            'foreignKey' => 'id_tarea',
            'joinType' => 'INNER',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id_tarea')
            ->requirePresence('id_tarea', 'create')
            ->notEmptyString('id_tarea');

        $validator
            ->scalar('titulo')
            ->maxLength('titulo', 50)
            ->requirePresence('titulo', 'create')
            ->notEmptyString('titulo');

        $validator
            ->boolean('completada');

        $validator
            ->dateTime('fecha_completada')
            ->allowEmptyDateTime('fecha_completada');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('id_tarea', 'Tareas'), ['errorField' => 'id_tarea']);
        return $rules;
    }

    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        if ($entity->isNew()) {
            if (empty($entity->fecha_creacion)) {
                $entity->fecha_creacion = date('Y-m-d H:i:s');
            }
            if (!isset($entity->completada)) {
                $entity->completada = false;
            }
        }
    }
}