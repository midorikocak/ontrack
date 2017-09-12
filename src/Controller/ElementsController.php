<?php

namespace App\Controller;

use Cake\Event\Event;

class ElementsController extends AppController
{
    /**
     * Initialization hook method.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        $this->viewBuilder()->setLayout('ajax');
    }

    public function display($element = null)
    {
        if ($element == null) {
            throw new ForbiddenException();
        }

        $this->set(compact('element', $element));

        try {
            $this->render('/Element/' . $element);
        } catch (MissingTemplateException $e) {
            if (Configure::read('debug')) {
                throw $e;
            }
            throw new NotFoundException();
        }
    }
}