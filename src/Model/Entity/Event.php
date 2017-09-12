<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Event Entity
 *
 * @property int $id
 * @property string $note
 * @property \Cake\I18n\FrozenTime $startDate
 * @property int $hours
 * @property int $minutes
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $updated
 * @property int $user_id
 *
 * @property \App\Model\Entity\User $user
 */
class Event extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];

    /*
    protected $_hidden = ['endDate'];

    protected function _getEndDate()
    {
        $startDate = new \DateTime($this->_properties['startDate']);

        $hours = $this->_properties['hours'] ?? 0;
        $minutes = $this->_properties['minutes'] ?? 0;
        $startDate->add(\DateInterval::createFromDateString("$hours hours + $minutes minutes"));

        return $startDate->format('Y-m-d H:i:s');
    }
    */
}
