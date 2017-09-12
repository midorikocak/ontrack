<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;

/**
 * Events Controller
 *
 * @property \App\Model\Table\EventsTable $Events
 *
 * @method \App\Model\Entity\Event[] paginate($object = null, array $settings = [])
 */
class EventsController extends AppController
{

    public $paginate = [
        'limit' => 20
    ];

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|mixed
     */
    public function index()
    {
        $events = $this->Events;
        $accomplished = false;

        if ($this->Auth->user('role') == 'admin') {
            $this->paginate = [
                'limit' => 20,
                'contain' => ['Users']
            ];
        }

        $from = $this->request->getQuery('from');
        $to = $this->request->getQuery('to');

        if ($from == null && $to != null) {
            /**
             * @var $from FrozenTime
             */
            if ($this->Auth->user('role') !== 'admin') {
                $from = $events->firstDate($this->Auth->user('id'))->format('Y-m-d 00:00:00');
            } else {
                $from = $events->firstDate()->format('Y-m-d 00:00:00');
            }
        } elseif ($from != null && $to == null) {
            $to = (new \DateTime())->format('Y-m-d 23:59:59');
        } elseif ($from == null && $to == null) {
            $from = (new \DateTime())->format('Y-m-d 00:00:00');
            $to = (new \DateTime())->format('Y-m-d 23:59:59');
        }

        $query = $events->find('all');

        if ($this->Auth->user('role') != 'admin') {
            $query->select(['id', 'note', 'startDate', 'hours', 'minutes']);
        }


        /* TODO: Going to need in the future
        $endDate = $query
            ->newExpr()
            ->add('DATE_ADD(DATE_ADD(startDate, INTERVAL hours HOUR), INTERVAL minutes MINUTE)');
        $query->select(['id', 'note', 'startDate', 'endDate' => $endDate, 'hours', 'minutes', 'created', 'modified']);

        if ($to != null) {
            $query->having(['endDate <' => $to]);
        }
        */

        if ($from != null) {
            $query->where(['startDate >' => $from]);
        }
        if ($to != null) {
            $query->where(['startDate <' => $to]);
        }

        if ($this->Auth->user('role') !== 'admin') {
            $query->where(['user_id' => $this->Auth->user('id')]);
        }

        $fromDate = new \DateTime($from);
        $toDate = new \DateTime($to);

        if ($fromDate->format('Y-m-d') === $toDate->format('Y-m-d')) {
            $date = $fromDate->format('F j, Y');

            if ($this->Auth->user('role') !== 'admin') {
                $query->where(['user_id' => $this->Auth->user('id')]);
                $totalTime = $events->totalTime($fromDate, $this->Auth->user('id'));
            } else {
                $totalTime = $events->totalTime($fromDate);
            }

            $users = TableRegistry::get('Users');
            $user = $users->get($this->Auth->user('id'));
            $workingHours = $user->workingHours;

            if ($totalTime['hours'] >= $workingHours) {
                $accomplished = true;
            }

        } else {
            $date = $fromDate->format('F j, Y') . " - " . $toDate->format('F j, Y');
        }

        $events = $this->paginate($query);

        $fromValue = $fromDate->format('Y-m-d\TH:i');
        $toValue = $toDate->format('Y-m-d\TH:i');

        $page = $this->request->getQuery('page') ?? 0;
        if ($page === 0) {
            $offset = 0;
        } else {
            $offset = ($page - 1) * $this->paginate['limit'];
        }

        $metadata = ["resultset" => [
            'count' => $query->count(),
            'offset' => $offset,
            'limit' => $this->paginate['limit']
        ]];

        $this->set(compact('events', 'date', 'fromValue', 'toValue', 'metadata', 'accomplished'));

        $this->set('_serialize', ['metadata', 'events', 'from' => 'fromValue', 'to' => 'toValue']);
    }

    /**
     * View method
     *
     * @param string|null $id Event id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $event = $this->Events->get($id, [
            'contain' => ['Users']
        ]);

        $this->set('event', $event);
        $this->set('_serialize', ['event']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $event = $this->Events->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            if (!isset($data['minutes']) && !isset($data['hours'])) {
                if ($this->request->is('ajax')) {
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

            if ($data['minutes'] >= 60) {
                $data['hours'] += floor($data['minutes'] / 60);
                $data['minutes'] %= 60;
            }
            $event = $this->Events->patchEntity($event, $this->request->getData());
            $event->user_id = $this->Auth->user('id');
            if ($this->Events->save($event)) {

                if ($this->request->is('ajax')) {
                    $code = 200;
                    $data = [
                        'status' => $code,
                        'message' => 'The event has been saved'
                    ];

                    return $this->response
                        ->withStatus($code)
                        ->withType('application/json')
                        ->withStringBody(json_encode($data));
                }
                return $this->redirect(['action' => 'index']);
            } else {
                if ($this->request->is('ajax')) {
                    $code = 500;
                    $data = [
                        'status' => $code,
                        'message' => __('The event could not be saved. Please, try again.')
                    ];

                    return $this->response
                        ->withStatus($code)
                        ->withType('application/json')
                        ->withStringBody(json_encode($data));
                }
            }
        }
        $users = $this->Events->Users->find('list', ['limit' => 200]);
        $this->set(compact('event', 'users'));
        $this->set('_serialize', ['event']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Event id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $event = $this->Events->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $event = $this->Events->patchEntity($event, $this->request->getData());
            if ($this->Events->save($event)) {
                if ($this->request->is('ajax')) {
                    $code = 200;
                    $data = [
                        'status' => $code,
                        'message' => __('The event has been saved.')
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
                    'message' => __('The event could not be saved. Please, try again.')
                ];

                return $this->response
                    ->withStatus($code)
                    ->withType('application/json')
                    ->withStringBody(json_encode($data));
            }
        }
        $users = $this->Events->Users->find('list', ['limit' => 200]);
        $this->set(compact('event', 'users'));
        $this->set('_serialize', ['event']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|mixed
     */
    public function report()
    {
        $events = $this->Events;


        $date = new \DateTime($this->request->getQuery('date')) ?? (new \DateTime())->format('Y-m-d');

        $startDate = $date->format('Y-m-d 00:00:00');
        $endDate = $date->format('Y-m-d 23:59:59');
        $accomplished = false;
        $query = $events->find('all');

        $users = TableRegistry::get('Users');
        $user = $users->get($this->Auth->user('id'));
        $workingHours = $user->workingHours;

        if ($this->Auth->user('role') !== 'admin') {
            $query->where(['user_id' => $this->Auth->user('id')]);
            $totalTime = $events->totalTime($date, $this->Auth->user('id'));
        } else {
            $totalTime = $events->totalTime($date);
        }

        if ($totalTime['hours'] >= $workingHours) {
            $accomplished = true;
        }

        $query->where(function ($exp) use ($startDate, $endDate) {
            return $exp->between('startDate', $startDate, $endDate);
        });

        $query->select(['id', 'note']);
        $events = $query->toArray();


        // TODO: Get Today's events from Model
        $metadata = ["resultset" => [
            'count' => $query->count()
        ]];

        $date = $date->format('F j, Y');

        $results = ['date' => $date, 'totalTime' => $totalTime, 'notes' => $events];
        $this->set(compact('events', 'date', 'totalTime', 'results', 'metadata', 'accomplished'));
        $this->set('_serialize', ['metadata', 'results']);

        /*
        if ($this->request->is('ajax')) {
            $this->render('events', 'ajax');
        }
        */
    }

    public function dates()
    {
        $query = $this->Events->find();

        $activeDate = $query->func()->date_format([
            'startDate' => 'identifier',
            "'%M %d, %Y'" => 'literal'
        ]);

        $users = TableRegistry::get('Users');
        $user = $users->get($this->Auth->user('id'));
        $workingHours = $user->workingHours;

        if ($this->Auth->user('role') !== 'admin') {
            $query->where(['user_id' => $this->Auth->user('id')]);
        }

        $query->select([
            'activeDate' => $activeDate,
            'notes' => $query->func()->count('*'),
            'totalHours' => $query->newExpr()->add('sum(hours)+floor(sum(minutes)/60)'),
            'totalMinutes' => $query->newExpr()->add('mod(sum(minutes),60)'),
            'accomplished' => $query->newExpr()->add("IF((sum(hours)+floor(sum(minutes)/60))>=$workingHours, 1, 0 )")
        ])->group('activeDate');

        $days = $query->toArray();
        $this->set(compact('days'));
        $this->set('_serialize', ['days']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Event id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $event = $this->Events->get($id);
        if ($this->Events->delete($event)) {
            if ($this->request->is('ajax')) {
                $code = 200;
                $data = [
                    'status' => $code,
                    'message' => __('The event has been deleted.')
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
                    'message' => __('The event could not be deleted. Please, try again.')
                ];

                return $this->response
                    ->withStatus($code)
                    ->withType('application/json')
                    ->withStringBody(json_encode($data));
            }
        }

        return $this->redirect(['action' => 'index']);
    }

    public function isAuthorized($user): bool
    {
        if (in_array($this->request->getParam('action'), ['add', 'index', 'report', 'dates'])) {
            return true;
        }

        // The owner of an event can edit and delete it
        if (in_array($this->request->getParam('action'), ['edit', 'delete', 'view'])) {
            $eventId = (int)$this->request->getParam('pass.0');
            if ($this->Events->isOwnedBy($eventId, $user['id'])) {
                return true;
            }
        }

        return parent::isAuthorized($user);
    }
}
