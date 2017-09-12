<?= $this->element('logout') ?>
<div class="row">
    <div class="large-5 large-centered columns" id="box">
        <h1><?= __('OnTrack') ?></h1>
        <p><?= __('Please register') ?></p>
        <div class="users form">
            <?= $this->Flash->render('auth') ?>
            <?= $this->Form->create($user) ?>

            <?php
            echo $this->Form->control('username');
            echo $this->Form->control('password');
            echo $this->Form->control('password_repeat', ['required' => true, 'type' => 'password', 'value' => $user['password']]);
            ?>
            <?= $this->Form->button(__('Register')); ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>