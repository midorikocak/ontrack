<style>
    div.container3 {
        height: 100vh;
        position: relative }              /* 1 */
    div.container3 div {
        text-align: center;
        margin: 0 auto;
        position: absolute;               /* 2 */
        left: 50%;
        top: 35%;                         /* 3 */
        transform: translate(-50%, -35%) }   /* 4 */
</style>
<div class=container3>
    <div>
        <h2>Welcome to Ontrack Application</h2>
        <p>Best solution to manage your time.</p>
        <a href="<?= $this->Url->build(['controller'=>'users','action'=>'login'])?>" class="button" id="welcome-login"><?= __('Login Here') ?></a>

    </div>
</div>