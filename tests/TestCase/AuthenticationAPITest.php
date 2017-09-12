<?php

namespace App\Test\TestCase;

use Cake\TestSuite\TestCase;
use GuzzleHttp;
use GuzzleHttp\Cookie\SessionCookieJar;

class AuthenticationAPITest extends TestCase
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

    private $Users;


    public function setUp()
    {
        parent::setUp();
        $cookieJar = new SessionCookieJar('SESSION_STORAGE', true);
        $this->http = new GuzzleHttp\Client([
            'base_uri' => 'http://ontrack.dev/api/v1/users/',
            'cookies' => $cookieJar
        ]);
    }

    public function tearDown()
    {
        $this->http = null;
    }

    /**
     * Test view using API
     *
     * Uses default database, not the test.
     * @return void
     */
    public function testUnauthorizedView()
    {

        try {
            $response = $this->http->request('GET', 'logout.json', [
                'headers' => [
                    'Accept' => 'application/json',
                    'X-Requested-With' => 'XMLHttpRequest'
                ]
            ]);
            $response = $this->http->request('GET', '1.json', [
                'headers' => [
                    'Accept' => 'application/json',
                    'X-Requested-With' => 'XMLHttpRequest'
                ]
            ]);
        } catch (GuzzleHttp\Exception\ClientException $e) {
            $this->assertEquals(403, $e->getResponse()->getStatusCode());
        }

    }

    /**
     * Test Login using API regular user
     *
     * Uses default database, not the test.
     * @return void
     */
    public function testLoginUser()
    {
        $response = $this->http->request('POST', 'login.json', [
            'headers' => [
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest'
            ],
            'form_params' => [
                'username' => 'user',
                'password' => 'turgut',
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTextContains('logged in', $response->getBody());
    }

    /**
     * Test Logout using API
     *
     * Uses default database, not the test.
     * @return void
     */
    public function testLogout()
    {
        $response = $this->http->request('GET', 'logout.json', [
            'headers' => [
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest'
            ]
        ]);

        $this->assertTextContains('logged out', $response->getBody());
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Test Login using API
     *
     * Uses default database, not the test.
     * @return void
     */
    public function testLoginAdmin()
    {
        $response = $this->http->request('POST', 'login.json', [
            'headers' => [
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest'
            ],
            'form_params' => [
                'username' => 'admin',
                'password' => 'turgut',
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTextContains('logged in', $response->getBody());
    }

    /**
     * Test view using API
     *
     * Uses default database, not the test.
     * @return void
     */
    public function testView()
    {
        $response = $this->http->request('GET', '1.json', [
            'headers' => [
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest'
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTextContains('admin', $response->getBody());
    }

}