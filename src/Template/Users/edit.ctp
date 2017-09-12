<div class="users form large-9 medium-8 columns content">
    <?= $this->Form->create($user, ['type' => 'file']) ?>
    <fieldset>
        <legend><?= __('Edit User') ?></legend>
        <?php
        echo $this->Form->control('username');
        echo $this->Form->control('email');
        echo $this->Form->control('password');
        echo $this->Form->control('password_repeat',['required' => true, 'type'=>'password', 'value'=>$user['password']]);
        echo $this->Form->control('workingHours');
        echo $this->Form->control('image_filename', ['type' => 'file']);
        if (isset($allowAdminControls)) {
            echo $this->Form->control('role', [
                'options' => ['admin' => 'Admin', 'manager' => 'Manager', 'user' => 'User']
            ]);
            echo $this->Form->control('status', [
                'options' => ['confirmed' => 'Confirmed', 'invited' => 'Invited']
            ]);
        }
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
