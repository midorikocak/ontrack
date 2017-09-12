<!DOCTYPE html>
<html>
<head>
<?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->fetch('title') ?></title>
<?= $this->Html->meta('icon') ?>
<?= $this->Html->css('base.css') ?>
<?= $this->Html->css('cake.css') ?>
<?= $this->Html->css('logout.css') ?>
<?= $this->Html->script('modernizr'); ?>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

<?= $this->fetch('meta') ?>
<?= $this->fetch('css') ?>
<?= $this->fetch('script') ?>
</head>
<body>
<div id="content">
    <div class="row">
        <div class="large-5 large-centered columns" id="box">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </div>
</div>
<?= $this->Html->script('vendor/jquery'); ?>
<?= $this->Html->script('foundation.min'); ?>
<script type="text/javascript">
    $(document).foundation();
</script>
<?= $this->fetch('scriptBottom') ?>
</body>
</html>