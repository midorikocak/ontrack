<h1 style="font-family: Arial, Helvetica, sans-serif; font-size: 32px; color: #404040; margin-top: 0; margin-bottom: 20px; padding: 0; line-height: 135%"
    class="headline"><?= __('Hello, Someone requested to change your password at OnTrack') ?></h1>
<p style="font-family: Arial, Helvetica, sans-serif; color: #555555; font-size: 14px; padding: 0 40px;">
    <?= __('If you asked to change your password, it\'s ok. If you did not, please ignore this message.') ?>
</p>

<p style="font-family: Arial, Helvetica, sans-serif; color: #555555; font-size: 14px; padding: 0 40px;">
    To reset your password now please click <a href="<?= $this->Url->build(['controller' => 'users', 'action' => 'reset', 'code' => $code], true) ?>"> here</a>
</p>