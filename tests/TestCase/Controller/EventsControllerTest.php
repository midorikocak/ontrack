<?php

namespace App\Test\TestCase\Controller;

use App\Controller\EventsController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\EventsController Test Case
 */
class EventsControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.events',
        'app.users'
    ];

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Test index method
     *
     * @return void
     */
    public function testUserIndex()
    {
        $this->session(['Auth.User.id' => 3]);
        $this->session(['Auth.User.role' => 'user']);

        $today = (new \DateTime('tomorrow'))->format('Y-m-d 23:59:59');
        $this->get('/events.json?to=' . $today);
        $response = json_decode($this->_response->body(), true);
        $this->assertTrue($response['metadata']['resultset']['count'] > 0);
        $this->assertResponseOk();
    }

    /**
     * Test index method
     *
     * @return void
     */
    public function testAdminIndex()
    {
        $this->session(['Auth.User.id' => 1]);
        $this->session(['Auth.User.role' => 'admin']);

        $tomorrow = (new \DateTime('tomorrow'))->format('Y-m-d 23:59:59');
        $this->get('/events.json?to=' . $tomorrow);
        $response = json_decode($this->_response->body(), true);
        $this->assertTrue($response['metadata']['resultset']['count'] == 9);
        $this->assertResponseOk();
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->configRequest([
            'headers' => ['Accept' => 'application/json', 'X-Requested-With' => 'XMLHttpRequest']
        ]);
        $this->session(['Auth.User.id' => 4]);
        $this->session(['Auth.User.role' => 'user']);
        $this->get('/events/view/9.json');
        $this->assertResponseOk();
    }

    /**
     * Test unauthorized view method
     *
     * @return void
     */
    public function testUnauthorizedView()
    {
        $this->session(['Auth.User.id' => 4]);
        $this->session(['Auth.User.role' => 'user']);
        $this->get('/events/1.json');
        $this->assertResponseError();
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->session(['Auth.User.id' => 4]);
        $this->session(['Auth.User.role' => 'user']);

        $this->configRequest([
            'headers' => ['Accept' => 'application/json', 'X-Requested-With' => 'XMLHttpRequest']
        ]);

        $this->post('/events/add', [
            'startDate' => (new \DateTime())->format('Y-m-d h:i:s'),
            'note' => 'I worked very very hard',
            'hours' => 2,
            'minutes' => 20
        ]);
        $this->get('events/view/10');
        $this->assertResponseOk();
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->session(['Auth.User.id' => 4]);
        $this->session(['Auth.User.role' => 'user']);

        $this->configRequest([
            'headers' => ['Accept' => 'application/json', 'X-Requested-With' => 'XMLHttpRequest']
        ]);
        $unique = uniqid();
        $this->post('/events/edit/9', [
            'note' => $unique,
            'hours' => 2,
            'minutes' => 20
        ]);
        $this->get('events/view/9');
        $this->assertResponseOk();
        $this->assertResponseContains($unique);
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->configRequest([
            'headers' => ['Accept' => 'application/json', 'X-Requested-With' => 'XMLHttpRequest']
        ]);
        $this->session(['Auth.User.id' => 4]);
        $this->session(['Auth.User.role' => 'user']);
        $this->delete('/events/9.json');
        $this->get('users/9.json');
        $this->assertResponseError();
    }

    /**
     * Test generate report method
     *
     * @return void
     */
    public function testReport()
    {
        $this->session(['Auth.User.id' => 2]);
        $this->session(['Auth.User.role' => 'manager']);

        $this->configRequest([
            'headers' => ['Accept' => 'application/json', 'X-Requested-With' => 'XMLHttpRequest']
        ]);

        $this->get('/events/report.json?date=2017-08-17');
        $response = json_decode($this->_response->body(), true);
        $this->assertTrue($response['metadata']['resultset']['count'] == 3);
    }

    /**
     * Test worked dates method
     *
     * @return void
     */
    public function testDates()
    {
        $this->session(['Auth.User.id' => 2]);
        $this->session(['Auth.User.role' => 'manager']);

        $this->configRequest([
            'headers' => ['Accept' => 'application/json', 'X-Requested-With' => 'XMLHttpRequest']
        ]);

        $this->get('/events/dates.json');
        $response = json_decode($this->_response->body(), true);
        $this->assertTrue($response['days'][1]['notes'] == 3);
    }
}
