<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class CategoriasTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('categoria');
        $this->setPrimaryKey('id_categoria');

        $this->hasMany('Tareas', [
            'foreignKey' => 'id_categoria',
        ]);

        $this->belongsTo('Usuarios', [
        'foreignKey' => 'id_usuario',
        'joinType' => 'INNER',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id_usuario')
            ->requirePresence('id_usuario', 'create')
            ->notEmptyString('id_usuario');

        $validator
            ->scalar('nombre')
            ->maxLength('nombre', 50)
            ->requirePresence('nombre', 'create')
            ->notEmptyString('nombre');

        $validator
            ->scalar('color')
            ->maxLength('color', 30)
            ->requirePresence('color', 'create')
            ->notEmptyString('color');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
{
    $rules->add($rules->existsIn('id_usuario', 'Usuarios'), ['errorField' => 'id_usuario']);

    $rules->add($rules->isUnique(['id_usuario', 'nombre']), [
        'errorField' => 'nombre',
        'message' => 'Ya tienes una categoría con ese nombre',
    ]);

    return $rules;
}
// Categorías del usuario en sesión
public function findDeUsuario(SelectQuery $query, int $idUsuario): SelectQuery
{
    return $query
        ->where(['Categorias.id_usuario' => $idUsuario])
        ->orderBy(['Categorias.nombre' => 'ASC']);
}

}