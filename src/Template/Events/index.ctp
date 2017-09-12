<div class="events index large-9 medium-8 columns content">
    <h3 class="<?php if ($accomplished) echo 'success-message'; ?>"><?= h($date) ?></h3>
    <div class="row">
        <div class="large-6 columns">
            <div class="row collapse postfix-radius">
                <div class="small-3 columns">
                    <span class="prefix">From</span>
                </div>
                <div class="small-9 columns">
                    <input id="from-data" type="datetime-local" value="<?=$fromValue ?>">
                </div>
            </div>
        </div>
        <div class="large-6 columns">
            <div class="row collapse postfix-radius">

                <div class="small-3 columns">
                    <span class="prefix">To</span>
                </div>
                <div class="small-9 columns">
                    <input id="to-data" type="datetime-local" value="<?=$toValue ?>">
                </div>
            </div>
        </div>
    </div>
    <div id="results">
        <table cellpadding="0" cellspacing="0">
            <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('startDate') ?></th>
                <th scope="col"><?= $this->Paginator->sort('note') ?></th>
                <th scope="col"><?= $this->Paginator->sort('hours') ?></th>
                <th scope="col"><?= $this->Paginator->sort('minutes') ?></th>
                <?php if (isset($allowAdminControls)) : ?>
                <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                <?php endif; ?>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($events as $event): ?>
                <tr>
                    <td><?= h($event->startDate) ?></td>
                    <td><?= h($event->note) ?></td>
                    <td><?= $this->Number->format($event->hours) ?></td>
                    <td><?= $this->Number->format($event->minutes) ?></td>
                    <?php if (isset($allowAdminControls)) : ?>
                    <td><?= $event->has('user') ? $this->Html->link($event->user->username, ['controller' => 'Users', 'action' => 'view', $event->user->id]) : '' ?></td>
                    <?php endif; ?>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $event->id], ['onclick'=>"viewEvent($event->id)"]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $event->id], ['onclick'=>"editEvent($event->id)"]) ?>
                        <?= $this->Html->link(__('Delete'), ['action' => 'delete', $event->id] , ['onclick'=>"deleteEvent($event->id)"]) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="paginator">
            <ul class="pagination">
                <?= $this->Paginator->first('<< ' . __('first')) ?>
                <?= $this->Paginator->prev('< ' . __('previous')) ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next(__('next') . ' >') ?>
                <?= $this->Paginator->last(__('last') . ' >>') ?>
            </ul>
            <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
        </div>
    </div>

</div>
