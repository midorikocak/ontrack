<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EventsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EventsTable Test Case
 */
class EventsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\EventsTable
     */
    public $EventsTable;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.events',
        'app.users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Events') ? [] : ['className' => EventsTable::class];
        $this->EventsTable = TableRegistry::get('Events', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EventsTable);

        parent::tearDown();
    }





    /**
     * Test firstDate method
     *
     * @return void
     */
    public function testFirstDate()
    {
        $this->assertEquals($this->EventsTable->firstDate()->format('Y-m-d h:i:s'), '2017-08-10 10:14:32');
        $event = $this->EventsTable->newEntity();
        $this->EventsTable->patchEntity($event, [
            'startDate'=>'1900-08-10 10:14:32',
            'note'=>'very very old',
            'hours'=>'3',
            'minutes'=>'5',
            'user_id'=>1
        ]);
        $this->EventsTable->save($event);
        $this->assertEquals($this->EventsTable->firstDate()->format('Y-m-d h:i:s'), '1900-08-10 10:14:32');

    }

    /**
     * Test totalTime method
     *
     * @return void
     */
    public function testTotalTimeAdmin()
    {

        $this->assertEquals($this->EventsTable->totalTime(new \DateTime('2017-08-17')), ['hours'=>'17','minutes'=>'55']);
        $event = $this->EventsTable->newEntity();
        $this->EventsTable->patchEntity($event, [
            'startDate'=>'2017-08-17 10:14:32',
            'note'=>'very very old',
            'hours'=>'3',
            'minutes'=>'5',
            'user_id'=> 1
        ]);

        $this->EventsTable->save($event);

        $this->assertEquals($this->EventsTable->totalTime(new \DateTime('2017-08-17')), ['hours'=>'21','minutes'=>'0']);
    }

    /**
     * Test totalTime method
     *
     * @return void
     */
    public function testTotalTimeUser()
    {
        $this->assertEquals($this->EventsTable->totalTime(new \DateTime('2017-08-17'), 2), ['hours'=>'12','minutes'=>'20']);
    }
}
