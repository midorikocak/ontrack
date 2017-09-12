<div class="events view large-9 medium-8 columns content">
    <h3><?= __('Event') ?></h3>
    <table class="vertical-table">
        <?php if (isset($allowAdminControls)) : ?>
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $event->has('user') ? $this->Html->link($event->user->username, ['controller' => 'Users', 'action' => 'view', $event->user->id]) : '' ?></td>
        </tr>
        <?php endif; ?>
        <tr>
            <th scope="row"><?= __('StartDate') ?></th>
            <td><?= h($event->startDate) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($event->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Hours') ?></th>
            <td><?= $this->Number->format($event->hours) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Minutes') ?></th>
            <td><?= $this->Number->format($event->minutes) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Note') ?></h4>
        <?= $this->Text->autoParagraph(h($event->note)); ?>
    </div>
</div>
