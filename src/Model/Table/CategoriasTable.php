<?php
declare(strict_types=1);

namespace App\Model\Table;

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
    }

    public function validationDefault(Validator $validator): Validator
    {
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
}