<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Sistemas Model
 *
 * @property \App\Model\Table\AcessosTable&\Cake\ORM\Association\HasMany $Acessos
 *
 * @method \App\Model\Entity\Sistema get($primaryKey, $options = [])
 * @method \App\Model\Entity\Sistema newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Sistema[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Sistema|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Sistema saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Sistema patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Sistema[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Sistema findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SistemasTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('sistemas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Search.Search');


        $this->searchManager()
        ->add('q', 'Search.Like', [
            'before' => true,
            'after' => true,
            'mode' => 'or',
            'comparison' => 'LIKE',
            'wildcardAny' => '*',
            'wildcardOne' => '?',
            'field' => ['adicionar', 'campos', 'string']
        ]);

        $this->addBehavior('DateFormat');
        $this->addBehavior('Timestamp');

        $this->hasMany('Acessos', [
            'foreignKey' => 'sistema_id'
        ]);
    }


    /**
     * Default search Configuration.
     *
     * @return search query component
     */
    public function searchConfiguration()
    {
        $search = new Manager($this);

        $search->like('title');

        return $search;
    }
    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('sigla')
            ->maxLength('sigla', 10)
            ->allowEmptyString('sigla');

        $validator
            ->scalar('nome')
            ->maxLength('nome', 100)
            ->allowEmptyString('nome');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 200)
            ->allowEmptyString('descricao');

        $validator
            ->scalar('icone')
            ->maxLength('icone', 30)
            ->allowEmptyString('icone');

        $validator
            ->scalar('controller')
            ->maxLength('controller', 50)
            ->allowEmptyString('controller');

        return $validator;
    }
}
