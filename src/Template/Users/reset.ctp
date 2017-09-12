<div class="row">
    <div class="large-5 large-centered columns" id="box">
        <?= $this->element('logout') ?>
        <h1><?= __('OnTrack') ?></h1>
        <p><?= __('Please reset your password') ?></p>
        <div class="users form">
            <?= $this->Flash->render('auth') ?>
            <?= $this->Form->create() ?>
            <?= $this->Form->input('password', ['type' => 'password', 'required' => true, 'placeholder' => 'Enter a strong password']) ?>
            <?= $this->Form->input('password_repeat', ['type' => 'password', 'required' => true, 'placeholder' => 'Now repeat the password']) ?>
            <?= $this->Form->button(__('Reset Password')); ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>