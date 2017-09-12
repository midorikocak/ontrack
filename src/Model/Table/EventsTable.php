<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Events Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Event get($primaryKey, $options = [])
 * @method \App\Model\Entity\Event newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Event[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Event|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Event patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Event[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Event findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EventsTable extends Table
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

        $this->setTable('events');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
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
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('note', 'create')
            ->notEmpty('note');

        $validator
            ->dateTime('startDate')
            ->requirePresence('startDate', 'create')
            ->notEmpty('startDate');

        $validator
            ->integer('hours')
            ->requirePresence('hours', 'create')
            ->greaterThanOrEqual('hours', 0)
            ->notEmpty('hours');

        $validator
            ->integer('minutes')
            ->greaterThanOrEqual('minutes', 0)
            ->requirePresence('minutes', 'create')
            ->notEmpty('minutes');

        return $validator;
    }

    public function firstDate($userId = null)
    {
        $query = $this->find('all', [
            'order' => ['Events.startDate' => 'ASC']
        ]);

        if ($userId != null) {
            $query->where(['user_id' => $userId]);
        }

        $query->select('startDate');

        return $query->first()->get('startDate');
    }

    public function totalTime(\DateTime $time, $userId = null)
    {
        $query = $this->find();

        $startDate = $time->format('Y-m-d 00:00:00');
        $endDate = $time->format('Y-m-d 23:59:59');

        $query
            ->select(['hours' => $query->func()->sum('Events.hours')])
            ->select(['minutes' => $query->func()->sum('Events.minutes')])
            ->where(function ($exp) use ($startDate, $endDate) {
                return $exp->between('startDate', $startDate, $endDate);
            });

        if ($userId != null) {
            $query->where(['user_id' => $userId]);
        }

        $totalTime = $query->first()->toArray();


        if ($totalTime['hours'] == null) {
            $totalTime['hours'] = 0;
        }
        if ($totalTime['minutes'] == null) {
            $totalTime['minutes'] = 0;
        }

        if ($totalTime['minutes'] >= 60) {
            $totalTime['hours'] += floor($totalTime['minutes'] / 60);
            $totalTime['minutes'] %= 60;
        }

        return $totalTime;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    public function isOwnedBy($eventId, $userId)
    {
        return $this->exists(['id' => $eventId, 'user_id' => $userId]);
    }
}
