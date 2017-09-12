<h1 style="font-family: Arial, Helvetica, sans-serif; font-size: 32px; color: #404040; margin-top: 0; margin-bottom: 20px; padding: 0; line-height: 135%"
    class="headline"><?= __('Hello, You are invited to an awesome app: OnTrack') ?></h1>
<p style="font-family: Arial, Helvetica, sans-serif; color: #555555; font-size: 14px; padding: 0 40px;">
    <?= __('You are invited to the ontrack application.') ?>
</p>

<p style="font-family: Arial, Helvetica, sans-serif; color: #555555; font-size: 14px; padding: 0 40px;">
    To register please click <a href="<?= $this->Url->build(['controller' => 'users', 'action' => 'register', 'invitation' => $confirmationLink], true) ?>"> here</a>
</p>