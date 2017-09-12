<?php

namespace App\Test\TestCase\Controller;

use App\Controller\UsersController;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\UsersController Test Case
 */
class UsersControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users',
        'app.events',
        'app.password_resets'
    ];

    /**
     * Test login method
     *
     * @return void
     */
    public function testLogin()
    {
        $this->configRequest([
            'headers' => ['Accept' => 'application/json', 'X-Requested-With' => 'XMLHttpRequest']
        ]);

        $this->post('/users/login.json', ['username' => 'admin', 'password' => '12345']);
        $this->assertResponseOk();
        $this->assertResponseContains('logged in');
    }

    /**
     * Test logout method
     *
     * @return void
     */
    public function testLogout()
    {
        $this->get('/users/logout');

        $this->assertRedirect(['controller' => 'Pages', 'action' => 'display', 'home']);
    }

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->testLogin();

        $this->session(['Auth.User.id' => 1]);
        $this->session(['Auth.User.role' => 'admin']);
        $this->get('/users.json');
        $this->assertResponseOk();
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->session(['Auth.User.id' => 1]);
        $this->session(['Auth.User.role' => 'admin']);
        $this->get('/users/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('admin');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->session(['Auth.User.id' => 1]);
        $this->session(['Auth.User.role' => 'admin']);
        $this->post('/users/add', [
            'username' => 'tester',
            'password' => '1234',
            'email' => 'tester@tester.com',
            'role' => 'user',
            'status' => 'confirmed',
            'workingHours' => '8'
        ]);
        $this->get('users/view/5');
        $this->assertResponseOk();
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testUnauthenticatedAdd()
    {
        $this->session(['Auth.User.id' => 4]);
        $this->session(['Auth.User.role' => 'user']);

        $this->patch('/users.json', [
            'username' => 'tester',
            'password' => '1234',
            'email' => 'tester@tester.com',
            'role' => 'user',
            'status' => 'confirmed',
            'workingHours' => '8'
        ]);

        $this->assertResponseError();
        $this->assertResponseContains('not authorized');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->session(['Auth.User.id' => 1]);
        $this->session(['Auth.User.role' => 'admin']);
        $this->post('/users/edit/4', [
            'username' => 'edited',
            'password' => '1234',
            'password_repeat' => '1234'
        ]);
        $this->get('users/view/4');

        $this->assertResponseContains('edited');
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
        $this->session(['Auth.User.id' => 1]);
        $this->session(['Auth.User.role' => 'admin']);
        $this->delete('/users/4.json');
        $this->get('users/4.json');
        $this->assertResponseError();
    }

    public function testInvite()
    {
        $this->configRequest([
            'headers' => ['Accept' => 'application/json', 'X-Requested-With' => 'XMLHttpRequest']
        ]);
        $this->session(['Auth.User.id' => 1]);
        $this->session(['Auth.User.role' => 'admin']);
        $this->post('/users/invite.json', ['email' => 'newuser@ontrack.dev']);
        $this->assertResponseContains('invited');
    }


    public function testRegister()
    {
        $this->configRequest([
            'headers' => ['Accept' => 'application/json', 'X-Requested-With' => 'XMLHttpRequest']
        ]);

        $this->session(['Auth.User.id' => 1]);
        $this->session(['Auth.User.role' => 'admin']);
        $this->post('/users/invite.json', ['email' => 'newuser@ontrack.dev']);
        $this->get('users/logout');

        $users = TableRegistry::get('Users');
        $user = $users->findByEmail('newuser@ontrack.dev')->first()->toArray();
        $confirmationCode = $user['username'];

        $this->configRequest([
            'headers' => ['Accept' => 'application/json', 'X-Requested-With' => 'XMLHttpRequest']
        ]);
        $this->post('/users/register.json?invitation=' . $confirmationCode, [
            'username' => 'newUser',
            'password' => 'verysecret',
            'password_repeat' => 'verysecret'
        ]);
        $this->assertResponseContains('registered');
        unset($users);
    }

    public function testForgotNoUserFound()
    {
        $this->configRequest([
            'headers' => ['Accept' => 'application/json', 'X-Requested-With' => 'XMLHttpRequest']
        ]);

        $this->post('users/forgot.json', ['email' => 'nonexistinguser@ontrack.dev']);
        $this->assertResponseError();

    }

    public function testForgot()
    {
        $this->configRequest([
            'headers' => ['Accept' => 'application/json', 'X-Requested-With' => 'XMLHttpRequest']
        ]);

        $this->post('users/forgot.json', ['email' => 'user@ontrack.dev']);

        $this->assertResponseOk();
    }

    public function testReset()
    {
        $this->configRequest([
            'headers' => ['Accept' => 'application/json', 'X-Requested-With' => 'XMLHttpRequest']
        ]);

        $this->post('users/forgot.json', ['email' => 'user@ontrack.dev']);
        $passwordReset = TableRegistry::get('PasswordResets');
        $reset = $passwordReset->findByEmail('user@ontrack.dev')->first();

        $this->configRequest([
            'headers' => ['Accept' => 'application/json', 'X-Requested-With' => 'XMLHttpRequest']
        ]);
        $this->post('users/reset.json?code='.$reset->token,['password'=>'supersecret', 'password_repeat'=>'supersecret']);
        $this->assertResponseOk();
    }


}
