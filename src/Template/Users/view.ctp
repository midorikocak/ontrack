<div class="users view large-9 medium-8 columns content">
    <h3><?= h($user->username) ?></h3>
    <div class="row">
        <div class="medium-4 columns">
            <img src="/img/<?=$user->image_filename?>" alt="profile image">
        </div>
        <br/>
        <br/>
    </div>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Username') ?></th>
            <td><?= h($user->username) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Email') ?></th>
            <td><?= h($user->email) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Working Hours') ?></th>
            <td><?= h($user->workingHours) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Events of the User') ?></h4>
        <?php if (!empty($user->events)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Note') ?></th>
                <th scope="col"><?= __('StartDate') ?></th>
                <th scope="col"><?= __('Hours') ?></th>
                <th scope="col"><?= __('Minutes') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($user->events as $events): ?>
            <tr>
                <td><?= h($events->id) ?></td>
                <td><?= h($events->note) ?></td>
                <td><?= h($events->startDate) ?></td>
                <td><?= h($events->hours) ?></td>
                <td><?= h($events->minutes) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Events', 'action' => 'view', $events->id], ['onclick'=>"viewEvent($events->id)"]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Events', 'action' => 'edit', $events->id], ['onclick'=>"editEvent($events->id)"]) ?>
                    <?= $this->Html->link(__('Delete'), ['controller' => 'Events', 'action' => 'delete', $events->id], ['onclick'=>"deleteEvent($events->id)"]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
