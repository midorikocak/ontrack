<div class="events form large-9 medium-8 columns content">
    <?= $this->Form->create($event) ?>
    <fieldset>
        <legend><?= __('Edit Event') ?></legend>
        <?php
            echo $this->Form->control('note');
            echo $this->Form->control('startDate');
            echo $this->Form->control('hours');
            echo $this->Form->control('minutes');
            if (isset($allowAdminControls)) {
                echo $this->Form->control('user_id', ['options' => $users]);
            }
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
