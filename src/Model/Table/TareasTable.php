<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\EventInterface;
use Cake\Datasource\EntityInterface;
use ArrayObject;

class TareasTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('tarea');
        $this->setPrimaryKey('id_tarea');

        $this->belongsTo('Usuarios', [
            'foreignKey' => 'id_usuario',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('Categorias', [
            'foreignKey' => 'id_categoria',
            'joinType' => 'LEFT',
        ]);

        $this->belongsTo('Especialistas', [
            'foreignKey' => 'id_especialista',
            'joinType' => 'LEFT',
        ]);

        $this->hasMany('Subtareas', [
            'foreignKey' => 'id_tarea',
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id_usuario')
            ->requirePresence('id_usuario', 'create')
            ->notEmptyString('id_usuario');

        $validator
            ->integer('id_categoria')
            ->allowEmptyString('id_categoria');

        $validator
            ->integer('id_especialista')
            ->allowEmptyString('id_especialista');

        $validator
            ->scalar('titulo')
            ->maxLength('titulo', 30)
            ->requirePresence('titulo', 'create')
            ->notEmptyString('titulo', 'El título es obligatorio');

        $validator
            ->scalar('notas')
            ->maxLength('notas', 60)
            ->allowEmptyString('notas');

        $validator
            ->date('fecha_limite')
            ->allowEmptyDate('fecha_limite');

        $validator
            ->time('hora_limite')
            ->allowEmptyTime('hora_limite');

        $validator
            ->time('hora_recordatorio')
            ->allowEmptyTime('hora_recordatorio');

        $validator
            ->dateTime('fecha_completada')
            ->allowEmptyDateTime('fecha_completada');

        $validator
            ->scalar('estado')
            ->maxLength('estado', 20)
            ->inList('estado', ['activa', 'inactiva'])
            ->notEmptyString('estado');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('id_usuario', 'Usuarios'), ['errorField' => 'id_usuario']);
        $rules->add($rules->existsIn('id_categoria', 'Categorias'), ['errorField' => 'id_categoria']);
        $rules->add($rules->existsIn('id_especialista', 'Especialistas'), ['errorField' => 'id_especialista']);

        return $rules;
    }

    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        if ($entity->isNew()) {
            if (empty($entity->fecha_creacion)) {
                $entity->fecha_creacion = date('Y-m-d H:i:s');
            }
            if (empty($entity->estado)) {
                $entity->estado = 'activa';
            }
        }
    }

    // Tareas activas de un usuario, con categoría y subtareas cargadas
    public function findActivasDeUsuario(SelectQuery $query, int $idUsuario): SelectQuery
    {
        return $query
            ->where(['Tareas.id_usuario' => $idUsuario, 'Tareas.estado' => 'activa'])
            ->contain(['Categorias', 'Subtareas'])
            ->orderBy(['Tareas.fecha_limite' => 'ASC', 'Tareas.hora_limite' => 'ASC']);
    }

    // Tareas activas de un usuario dentro de un rango de fechas (para el calendario)
    public function findActivasEnRango(SelectQuery $query, int $idUsuario, string $desde, string $hasta): SelectQuery
    {
        return $query
            ->where([
                'Tareas.id_usuario' => $idUsuario,
                'Tareas.estado' => 'activa',
                'Tareas.fecha_limite >=' => $desde,
                'Tareas.fecha_limite <=' => $hasta,
            ])
            ->contain(['Categorias'])
            ->orderBy(['Tareas.fecha_limite' => 'ASC', 'Tareas.hora_limite' => 'ASC']);
    }
}