<?php

namespace App\Test\TestCase;

use Cake\TestSuite\TestCase;
use GuzzleHttp;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SessionCookieJar;

class EventAPITest extends TestCase
{
    private $http;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users',
        'app.events'
    ];


    public function setUp()
    {
        parent::setUp();
        $cookieJar = new SessionCookieJar('SESSION_STORAGE', true);
        $this->http = new GuzzleHttp\Client([
            'base_uri' => 'http://ontrack.dev/api/v1/',
            'cookies' => $cookieJar
        ]);

        $this->http->request('GET', 'users/logout.json', [
            'headers' => [
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest'
            ]
        ]);
        $this->http->request('POST', 'users/login.json', [
            'headers' => [
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest'
            ],
            'form_params' => [
                'username' => 'admin',
                'password' => 'turgut',
            ]
        ]);
    }

    public function tearDown()
    {
        $this->http = null;
    }

    /**
     * Test view event
     *
     * Uses default database, not the test.
     * @return void
     */
    public function testView()
    {
        $response = $this->http->request('GET', 'events/25.json', [
            'headers' => [
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest'
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTextContains('startDate', $response->getBody());
    }

    /**
     * Test index event
     *
     * Uses default database, not the test.
     * @return void
     */
    public function testIndex()
    {
        $response = $this->http->request('GET', 'events.json', [
            'headers' => [
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest'
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTextContains('resultset', $response->getBody());
    }

    /**
     * Test dates event
     *
     * Uses default database, not the test.
     * @return void
     */
    public function testDates()
    {
        $response = $this->http->request('GET', 'events/dates.json', [
            'headers' => [
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest'
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTextContains('days', $response->getBody());
    }

    /**
     * Test dates event
     *
     * Uses default database, not the test.
     * @return void
     */
    public function testReport()
    {
        $response = $this->http->request('GET', 'events/report.json', [
            'headers' => [
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest'
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTextContains('results', $response->getBody());
    }

}