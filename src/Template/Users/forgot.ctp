<?= $this->element('logout') ?>
<div class="row">
    <div class="large-5 large-centered columns" id="box">
        <h1><?= __('OnTrack') ?></h1>
        <p><?= __('Please enter your email address, and you will get an email') ?></p>
        <div class="users form ">
            <?= $this->Flash->render('auth') ?>
            <?= $this->Form->create() ?>
            <?= $this->Form->input('email', ['type' => 'email', 'required' => true]) ?>
            <?= $this->Form->button(__('Get Password')); ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>