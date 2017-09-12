<?php
$this->layout = false;
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= __('OnTrack') ?>
    </title>

    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('cake.css') ?>

    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

    <link href="https://fonts.googleapis.com/css?family=Raleway:500i|Roboto:300,400,700|Roboto+Mono" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>

<body>
<nav class="top-bar expanded no-print" data-topbar role="navigation">

</nav>
<?= $this->Flash->render() ?>
<div class="container clearfix">
    <nav class="large-3 medium-4 columns no-print" id="actions-sidebar">

    </nav>
    <div id="main">

    </div>
</div>
<footer>
</footer>
<?= $this->Html->script('vendor/jquery'); ?>
<?= $this->Html->script('app'); ?>
<script>
    $(document).ready(function () {
        home();
    });

    $(window).on("popstate", function() {
        back(history.state);
    });
</script>
</body>
</html>
