<div class="users view large-9 medium-8 columns content">
    <h3 class="<?php if ($accomplished) echo 'success-message'; ?>"><?= h('Report of ') . h($date) ?>    </small>

        <?php if ($accomplished): ?>
            <small class='success-message'><?= __('You reached your goal.') ?></small>
        <?php else: ?>
            <small class='error-message'><?= __('You did not reach your goal.') ?></small>
        <?php endif; ?>
    </h3>

    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Date') ?></th>
            <td><?= h($date) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Total Time') ?></th>
            <td><?= h($totalTime['hours']) . ' ' . __('hours') . ' ' . h($totalTime['minutes']) . ' ' . __('minutes') ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Notes') ?></h4>
        <?php if (!empty($events)): ?>
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <th scope="col"><?= __('Note') ?></th>
                </tr>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?= h($event->note) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p><?= __('You have no work logged for this date'); ?></p>
        <?php endif; ?>

    </div>
</div>
