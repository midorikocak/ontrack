<?= $this->element('logout') ?>
<div class="row">
    <div class="large-5 large-centered columns" id="box">
        <h1><?= __('OnTrack') ?></h1>
        <p><?= __('Please enter your username and password') ?></p>
        <div class="users form">
            <?= $this->Flash->render('auth') ?>
            <?= $this->Form->create() ?>

            <?= $this->Form->input('username') ?>
            <?= $this->Form->input('password') ?>
            <br/>
            <div id="ontrack-capthca" class="g-recaptcha" data-sitekey="6LfVMS0UAAAAAMY4aIquS5dKE9NY3rUuizivCYSg"></div>
            <br/>

            <?= $this->Form->button(__('Login')); ?>
            <?= $this->Form->end() ?>
            <a href="<?= $this->Url->build('forgot') ?>"><?= __('Forgot password?') ?></a>
        </div>
    </div>
</div>