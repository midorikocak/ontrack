<?php if(empty($noHeader)) : ?>
<ul class="title-area large-3 medium-4 columns">
    <li class="name">
        <h1><a href=""><?= 'OnTrack' ?></a></h1>
    </li>
</ul>
<div class="top-bar-section">
    <ul class="right">
        <?php if(isset($userId)) : ?>
        <li><?= $this->Html->link($username, ['controller'=>'users', 'action' => 'view', $userId] , ['onclick'=>"viewUser($userId)"]) ?></li>
        <li><?= $this->Html->link(__('Settings'), ['controller'=>'users', 'action' => 'edit', $userId] , ['onclick'=>"editUser($userId)"]) ?></li>
        <li><a href="<?= $this->Url->build(['controller'=>'users','action'=>'logout'])?>"><?= __('Logout') ?></a></li>
        <?php else: ?>
        <li><a href="<?= $this->Url->build(['controller'=>'users','action'=>'login'])?>"><?= __('Login') ?></a></li>
        <?php endif; ?>
    </ul>
</div>
<?php endif; ?>