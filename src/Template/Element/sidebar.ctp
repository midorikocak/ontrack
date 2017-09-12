<?php if(empty($noSidebar)) : ?>
<ul class="side-nav">
    <li class="heading"><?= __('Actions') ?></li>
    <li><?= $this->Html->link(__('Events'), ['controller' => 'Events', 'action' => 'index']) ?></li>
    <li><?= $this->Html->link(__('New Event'), ['controller' => 'Events','action' => 'add']) ?></li>
    <li><?= $this->Html->link(__('Dates Worked'), ['controller' => 'Events', 'action' => 'dates']) ?></li>
    <li><?= $this->Html->link(__('Report of Today'), ['controller' => 'Events', 'action' => 'report']) ?></li>
    <?php if (isset($allowAdminControls)): ?>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('Invite User'), ['controller' => 'Users', 'action' => 'invite']) ?></li>
    <?php endif; ?>
</ul>
<?php endif; ?>