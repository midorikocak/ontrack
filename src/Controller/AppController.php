<?php

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Auth', [
            'ajaxLogin' => 'AjaxElement',
            'authorize' => ['Controller'],
            'loginRedirect' => [
                'controller' => 'Events',
                'action' => 'index'
            ],
            'logoutRedirect' => [
                'controller' => 'Pages',
                'action' => 'display',
                'home'
            ],
            'unauthorizedRedirect' => false
        ]);

        /*
        if ($this->Auth->user('id')) {
            $this->viewBuilder()->setLayout('default');
        } else {
            $this->viewBuilder()->setLayout('logout');
        }
        */
        if ($this->request->is('ajax')) {
            $this->viewBuilder()->setLayout('ajax');
        }
        elseif ($this->Auth->user('id')) {
            $this->viewBuilder()->setLayout('default');
        }

        /*
         * Enable the following components for recommended CakePHP security settings.
         * see http://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
        //$this->loadComponent('Csrf');
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return \Cake\Network\Response|null|void
     */
    public function beforeRender(Event $event)
    {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json'])
        ) {
            $this->set('_serialize', true);
        }
        if ($this->Auth !== false) {
            $id = $this->Auth->user('id');
            $role = $this->Auth->user('role');
            if ($id !== null) {
                $this->set('username', $this->Auth->user('username'));
                $this->set('userId', $this->Auth->user('id'));
            }

            if ($role != 'user') {
                $this->set('allowAdminControls', true);
            }
            if ($this->RequestHandler->prefers('json')) {
                $this->Auth->setConfig('loginRedirect', false);
                $this->Auth->setConfig('logoutRedirect', false);
                $this->Auth->setConfig('unauthorizedRedirect', false);
            }
        }
    }

    public function beforeFilter(Event $event)
    {
        $this->Auth->setConfig('authError', __("You are not authorized to access this area."));
        $this->Auth->allow(['login', 'display']);
        if (!$this->Auth->authCheck($event)) {
            $this->set([
                'status' => 403,
                'message' => "You are not authorized to access this area."
            ]);
        }
    }

    public function isAuthorized($user): bool
    {
        // Admin can access every action
        if (isset($user['role']) && $user['role'] === 'admin') {
            return true;
        }

        // Default deny
        return false;
    }
}
