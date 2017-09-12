<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\Http\Client;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[] paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

    /**
     * Before filter for User Requests
     *
     * @param Event $event Event to send to parent
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['register', 'logout', 'reset', 'forgot', 'isLogged']);
    }

    public function isLogged()
    {
        if ($this->Auth->user('id')) {
            $code = 200;
            $data = [
                'status' => $code,
                'message' => 'You are logged in'
            ];

            return $this->response
                ->withStatus($code)
                ->withType('application/json')
                ->withStringBody(json_encode($data));
        } else {
            $code = 400;
            $data = [
                'status' => $code,
                'message' => 'You are not logged in'
            ];

            return $this->response
                ->withStatus($code)
                ->withType('application/json')
                ->withStringBody(json_encode($data));
        }

    }

    /**
     * Checks if request is authorized
     *
     * @param null $user User to check if authorized
     * @return bool
     */
    public function isAuthorized($user = null): bool
    {
        if ($user['role'] == 'admin' || $user['role'] == 'manager') {
            return true;
        }
        if ($user['role'] == 'user') {
            if (in_array($this->request->action, ['logout'])) {
                return true;
            }
            if (in_array($this->request->action, ['edit', 'view'])) {
                $userId = (int)$this->request->getParam('pass.0');
                if ($this->Auth->user('id') == $userId) {
                    return true;
                }
            }
        }

        return parent::isAuthorized($user);
    }

    /** Login Method
     *
     * @return \Cake\Http\Response|null
     */
    public function login()
    {
        $this->set('noHeader', true);
        $this->set('noSidebar', true);
        if ($this->Auth->user()) {
            if ($this->request->is('ajax')) {
                $code = 400;
                $data = [
                    'status' => $code,
                    'message' => 'You are already logged in. Please logout first.'
                ];

                return $this->response
                    ->withStatus($code)
                    ->withType('application/json')
                    ->withStringBody(json_encode($data));
            }
            return $this->redirect($this->Auth->redirectUrl());
        }
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            if (isset($data['g-recaptcha-response'])) {
                $recaptcha = $data['g-recaptcha-response'];
                $http = new Client();
                $response = $http->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => '6LfVMS0UAAAAAG0MBUZiCb5nVOBgjp392l9CqSwU',
                    'response' => $recaptcha,
                    'remoteip' => $this->request->clientIp()
                ]);
                $responseBody = json_decode($response->body(), true);
                if ($responseBody['success'] && $response->getStatusCode() == 200) {
                    $user = $this->Auth->identify();
                    if ($user && $user['status'] == 'confirmed') {
                        $this->Auth->setUser($user);
                        if (!$this->request->is('ajax')) {
                            return $this->redirect($this->Auth->redirectUrl());
                        } else {
                            $this->Auth->setUser($user);
                            $code = 200;
                            $data = [
                                'status' => $code,
                                'message' => 'You are logged in'
                            ];

                            return $this->response
                                ->withStatus($code)
                                ->withType('application/json')
                                ->withStringBody(json_encode($data));
                        }
                    }
                    if ($user['status'] == 'invited') {
                        if ($this->request->is('ajax')) {
                            $code = 200;
                            $data = [
                                'status' => $code,
                                'message' => 'Please follow confirmation link sent to your email address'
                            ];

                            return $this->response
                                ->withStatus($code)
                                ->withType('application/json')
                                ->withStringBody(json_encode($data));
                        }
                        if ($this->request->is('ajax')) {
                            $code = 400;
                            $data = [
                                'status' => $code,
                                'message' => __('Please follow confirmation link sent to your email address')
                            ];

                            return $this->response
                                ->withStatus($code)
                                ->withType('application/json')
                                ->withStringBody(json_encode($data));
                        }
                    } else {
                        if ($this->request->is('ajax')) {
                            $code = 400;
                            $data = [
                                'status' => $code,
                                'message' => 'Invalid username or password, try again'
                            ];

                            return $this->response
                                ->withStatus($code)
                                ->withType('application/json')
                                ->withStringBody(json_encode($data));
                        }
                        $this->Flash->error(__('Invalid username or password, try again'));
                    }
                } else {
                    if ($this->request->is('ajax')) {
                        $code = 400;
                        $data = [
                            'status' => $code,
                            'message' => 'Please check ReCaptcha'
                        ];

                        return $this->response
                            ->withStatus($code)
                            ->withType('application/json')
                            ->withStringBody(json_encode($data));
                    }
                }
            } else {
                $user = $this->Auth->identify();
                if ($user && $user['status'] == 'confirmed') {
                    $this->Auth->setUser($user);
                    $code = 200;
                    $data = [
                        'status' => $code,
                        'message' => 'You are logged in'
                    ];

                    return $this->response
                        ->withStatus($code)
                        ->withType('application/json')
                        ->withStringBody(json_encode($data));
                } else {
                    $code = 400;
                    $data = [
                        'status' => $code,
                        'message' => 'Invalid username or password, try again'
                    ];

                    return $this->response
                        ->withStatus($code)
                        ->withType('application/json')
                        ->withStringBody(json_encode($data));
                }
            }
        }
    }

    /**
     * Logout Method
     *
     * @return \Cake\Http\Response|null
     */
    public function logout()
    {
        if (!$this->request->is('ajax')) {
            return $this->redirect($this->Auth->logout());
        } else {
            $this->Auth->logout();
            $code = 200;
            $data = [
                'status' => $code,
                'message' => 'You are logged out'
            ];

            return $this->response
                ->withStatus($code)
                ->withType('application/json')
                ->withStringBody(json_encode($data));
        }
    }

    /**
     * Pasword Forgotten Method
     *
     * @return \Cake\Http\Response|null
     */
    public function forgot()
    {
        //$this->viewBuilder()->setLayout('logout');
        $this->set('noHeader', true);
        $this->set('noSidebar', true);
        $message = __('If there is an email registered, you will receive a confirmation email, to reset your password');

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            if ($this->request->is('ajax')) {
                if (!isset($data['email']) || empty($data['email'])) {
                    $code = 400;
                    $data = [
                        'status' => $code,
                        'message' => 'Bad request'
                    ];

                    return $this->response
                        ->withStatus($code)
                        ->withType('application/json')
                        ->withStringBody(json_encode($data));
                }
            }
            $query = $this->Users->findByEmail($data['email'])->first();

            if (empty($query)) {

                if ($this->request->is('ajax')) {
                    $code = 400;
                    $data = [
                        'status' => 400,
                        'message' => $message
                    ];

                    return $this->response
                        ->withStatus($code)
                        ->withType('application/json')
                        ->withStringBody(json_encode($data));
                }
                return $this->redirect(['action' => 'login']);
            }

            $user = $query->toArray();
            $email = $user['email'];

            $randomHash = md5(uniqid(rand(), true));

            $passwordReset = TableRegistry::get('PasswordResets');
            try {
                $reset = $passwordReset->get($email, [
                    'contain' => []
                ]);
            } catch (RecordNotFoundException $e) {
                $reset = $passwordReset->newEntity();
            }

            $resetCode = $passwordReset->patchEntity($reset, ['email' => $email, 'token' => $randomHash]);
            if (!$passwordReset->save($resetCode)) {
                if ($this->request->is('ajax')) {
                    $code = 500;
                    $data = [
                        'status' => __('Internal Server Error'),
                        'message' => $message
                    ];

                    return $this->response
                        ->withStatus($code)
                        ->withType('application/json')
                        ->withStringBody(json_encode($data));
                }
                return $this->redirect(['action' => 'login']);
            }

            $email = new Email('default');
            $email->setFrom(['noreply@ontrack.dev' => 'OnTrack Application'])
                ->setViewVars(['code' => $randomHash])
                ->setTemplate('reset')
                ->setEmailFormat('html')
                ->setTo($user['email'])
                ->setSubject(__('Someone asked to reset your password at OnTrack'))
                ->send();

            if ($this->request->is('ajax')) {
                $code = 200;
                $data = [
                    'status' => 200,
                    'message' => $message
                ];

                return $this->response
                    ->withStatus($code)
                    ->withType('application/json')
                    ->withStringBody(json_encode($data));
            }
            return $this->redirect(['action' => 'login']);
        }
    }

    /**
     * @return \Cake\Http\Response|null
     */
    public function reset()
    {
        //$this->viewBuilder()->setLayout('logout');
        $this->set('noHeader', true);
        $this->set('noSidebar', true);
        $code = $this->request->getQuery('code');
        $passwordReset = TableRegistry::get('PasswordResets');

        $reset = $passwordReset->findByToken($code);

        $checkIfCodeExists = $reset->count();

        if ($code == null || empty($checkIfCodeExists)) {
            if ($this->request->is('ajax')) {
                $code = 400;
                $data = [
                    'status' => $code,
                    'message' => __('You cannot reset your password')
                ];

                return $this->response
                    ->withStatus($code)
                    ->withType('application/json')
                    ->withStringBody(json_encode($data));
            }
            if (!$this->Auth->user()) {
                return $this->redirect(['action' => 'login']);
            } else {
                return $this->redirect($this->Auth->redirectUrl());
            }
        }

        $resetData = $reset->first()->toArray();
        $email = $resetData['email'];

        $query = $this->Users->findByEmail($email);
        if (empty($query)) {
            if ($this->request->is('ajax')) {
                $code = 400;
                $data = [
                    'status' => $code,
                    'message' => __('You cannot reset your password')
                ];

                return $this->response
                    ->withStatus($code)
                    ->withType('application/json')
                    ->withStringBody(json_encode($data));
            }
            return $this->redirect(['action' => 'login']);
        }

        $user = $query->first();

        //$this->viewBuilder()->setLayout('logout');
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $user = $this->Users->patchEntity($user, $data);

            if (($data['password_repeat'] == $data['password']) && $this->Users->save($user)) {
                $passwordReset->delete($reset->first());
                if ($this->request->is('ajax')) {
                    $code = 200;
                    $data = [
                        'status' => $code,
                        'message' => __('You successfully changed your password. Now you can login')
                    ];

                    return $this->response
                        ->withStatus($code)
                        ->withType('application/json')
                        ->withStringBody(json_encode($data));
                }
                return $this->redirect(['action' => 'login']);
            }
        }
    }

    /**
     * @return \Cake\Http\Response|null
     */
    public function register()
    {
        $this->set('noHeader', true);
        $this->set('noSidebar', true);
        $invitation = $this->request->getQuery('invitation');

        if ($invitation == null) {
            if ($this->request->is('ajax')) {
                $code = 400;
                $data = [
                    'status' => $code,
                    'message' => __('You cannot register without an invitation code.')
                ];

                return $this->response
                    ->withStatus($code)
                    ->withType('application/json')
                    ->withStringBody(json_encode($data));
            }
            return $this->redirect(['action' => 'login']);
        }

        $query = $this->Users->findByUsername($invitation);

        $user = $query->first();

        if (empty($user)) {
            if ($this->request->is('ajax')) {
                $code = 400;
                $data = [
                    'status' => $code,
                    'message' => __('You cannot register without an invitation code.')
                ];

                return $this->response
                    ->withStatus($code)
                    ->withType('application/json')
                    ->withStringBody(json_encode($data));
            }
            return $this->redirect(['action' => 'login']);
        }

        $user->password = "";
        $user->username = "";
        $user->status = "confirmed";

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $user = $this->Users->patchEntity($user, $data);

            if ($this->request->is('ajax')) {
                if (!isset($data['password_repeat']) || empty($data['password'])) {
                    $code = 400;
                    $data = [
                        'status' => $code,
                        'message' => 'Bad request'
                    ];

                    return $this->response
                        ->withStatus($code)
                        ->withType('application/json')
                        ->withStringBody(json_encode($data));
                }
            }

            if (($data['password_repeat'] == $data['password']) && $this->Users->save($user)) {
                if ($this->request->is('ajax')) {
                    $code = 200;
                    $data = [
                        'status' => $code,
                        'message' => __('You successfully registered to our app. Now you can login')
                    ];

                    return $this->response
                        ->withStatus($code)
                        ->withType('application/json')
                        ->withStringBody(json_encode($data));
                }
                return $this->redirect(['action' => 'login']);
            }

            if ($this->request->is('ajax')) {
                $code = 400;
                $data = [
                    'status' => $code,
                    'message' => 'The user could not be saved. Please, try again.'
                ];

                return $this->response
                    ->withStatus($code)
                    ->withType('application/json')
                    ->withStringBody(json_encode($data));
            }
        }

        //$this->viewBuilder()->setLayout('logout');
        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
        $this->set('_serialize', ['users']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Events']
        ]);

        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                if ($this->request->is('ajax')) {
                    $code = 200;
                    $data = [
                        'status' => $code,
                        'message' => __('The user has been saved.')
                    ];

                    return $this->response
                        ->withStatus($code)
                        ->withType('application/json')
                        ->withStringBody(json_encode($data));
                }
                return $this->redirect(['action' => 'index']);
            }
            if ($this->request->is('ajax')) {
                $code = 400;
                $data = [
                    'status' => $code,
                    'message' => __('The user could not be saved. Please, try again.')
                ];

                return $this->response
                    ->withStatus($code)
                    ->withType('application/json')
                    ->withStringBody(json_encode($data));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * @return \Cake\Http\Response|null
     */
    public function invite()
    {
        $user = $this->Users->newEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            // Admin can invite only non confirmed users

            $randomHash = md5(uniqid(rand(), true));
            $data['username'] = $randomHash;
            $data['password'] = 'waiting_invitation';
            $data['status'] = 'invited';
            $data['role'] = 'user';

            if ($this->request->is('ajax')) {
                if (!isset($data['email']) || empty($data['email'])) {
                    $code = 400;
                    $data = [
                        'status' => $code,
                        'message' => 'Bad request'
                    ];

                    return $this->response
                        ->withStatus($code)
                        ->withType('application/json')
                        ->withStringBody(json_encode($data));
                }
            }
            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                $email = new Email('default');
                $email->setFrom(['noreply@ontrack.dev' => 'OnTrack Application'])
                    ->setViewVars(['confirmationLink' => $randomHash])
                    ->setTemplate('invitation')
                    ->setEmailFormat('html')
                    ->setTo($user->email)
                    ->setSubject(__('You are invited to OnTrack'))
                    ->send();

                if ($this->request->is('ajax')) {
                    $code = 200;
                    $data = [
                        'status' => $code,
                        'message' => __('The user has been invited.')
                    ];

                    return $this->response
                        ->withStatus($code)
                        ->withType('application/json')
                        ->withStringBody(json_encode($data));
                }
                return $this->redirect(['action' => 'index']);
            }
            if ($this->request->is('ajax')) {
                $code = 400;
                $data = [
                    'status' => $code,
                    'message' => __('The user could not be saved. Please, try again.')
                ];

                return $this->response
                    ->withStatus($code)
                    ->withType('application/json')
                    ->withStringBody(json_encode($data));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $oldPicture = $user['image_filename'];

            if (!empty($data['image_filename']['tmp_name'])) {
                $picture = $data['image_filename'];
                $filename = $picture['name'];
                $ran = rand();
                if ($filename != null) {
                    if (move_uploaded_file($picture['tmp_name'], WWW_ROOT . 'img/' . $ran . "." . $filename)) {
                        if ($oldPicture != '') {
                            unlink(WWW_ROOT . 'img/' . $oldPicture);
                        }
                        $data['image_filename'] = $ran . "." . $picture['name'];
                    } else {
                        $data['image_filename'] = null;
                    }
                }
            } else {
                unset($data['image_filename']);
            }

            if ($this->Auth->user('role') == 'user') {
                unset($data['status']);
                unset($data['role']);
            }

            $user = $this->Users->patchEntity($user, $data);
            if (($data['password_repeat'] == $data['password']) && $this->Users->save($user)) {
                if ($this->request->is('ajax')) {
                    $code = 200;
                    $data = [
                        'status' => $code,
                        'message' => __('The user has been saved.')
                    ];

                    return $this->response
                        ->withStatus($code)
                        ->withType('application/json')
                        ->withStringBody(json_encode($data));
                }
                return $this->redirect(['action' => 'edit', $id]);
            }
            if ($this->request->is('ajax')) {
                $code = 400;
                $data = [
                    'status' => $code,
                    'message' => __('The user could not be saved. Please, try again.')
                ];

                return $this->response
                    ->withStatus($code)
                    ->withType('application/json')
                    ->withStringBody(json_encode($data));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            if ($this->request->is('ajax')) {
                $code = 200;
                $data = [
                    'status' => $code,
                    'message' => __('The user has been deleted.')
                ];

                return $this->response
                    ->withStatus($code)
                    ->withType('application/json')
                    ->withStringBody(json_encode($data));
            }
        } else {
            if ($this->request->is('ajax')) {
                $code = 500;
                $data = [
                    'status' => $code,
                    'message' => __('The user could not be deleted. Please, try again.')
                ];

                return $this->response
                    ->withStatus($code)
                    ->withType('application/json')
                    ->withStringBody(json_encode($data));
            }
        }
        return $this->redirect(['action' => 'index']);
    }
}
