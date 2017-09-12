<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;
use Cake\Auth\DefaultPasswordHasher;

/**
 * UsersFixture
 *
 */
class UsersFixture extends TestFixture
{
    public $connection = 'test';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'username' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => '', 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'email' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'password' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'workingHours' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'image_filename' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'role' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => 'user', 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'status' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => 'invited', 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'users_email_unique' => ['type' => 'unique', 'columns' => ['email'], 'length' => []],
            'username' => ['type' => 'unique', 'columns' => ['username'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci'
        ],
    ];

    // @codingStandardsIgnoreEnd

    public function init()
    {
        $records = [
            [
                'id' => 1,
                'username' => 'admin',
                'email' => 'admin@ontrack.dev',
                'password' => '12345',
                'workingHours' => 10,
                'image_filename' => '',
                'role' => 'admin',
                'status' => 'confirmed',
                'created' => 1502964894,
                'modified' => 1502964894
            ],
            [
                'id' => 2,
                'username' => 'manager',
                'email' => 'manager@ontrack.dev',
                'password' => '1234',
                'workingHours' => 15,
                'image_filename' => '',
                'role' => 'manager',
                'status' => 'confirmed',
                'created' => 1502964894,
                'modified' => 1502964894
            ],
            [
                'id' => 3,
                'username' => 'user',
                'email' => 'user@ontrack.dev',
                'password' => '1234',
                'workingHours' => 5,
                'image_filename' => '',
                'role' => 'user',
                'status' => 'confirmed',
                'created' => 1502964894,
                'modified' => 1502964894
            ],
            [
                'id' => 4,
                'username' => 'invited',
                'email' => 'invited@ontrack.dev',
                'password' => '1234',
                'workingHours' => 5,
                'image_filename' => '',
                'role' => 'user',
                'status' => 'invited',
                'created' => 1502964894,
                'modified' => 1502964894
            ],
        ];

        array_walk($records, function(&$item, $key){
            $item['password'] = (new DefaultPasswordHasher)->hash($item['password']);
        });

        $this->records = $records;
        parent::init();
    }
}
