<?= View::make('containers.head')->render(); ?>
    <section class="container omen-maxContainer omen-loginContainer">
        <section class="row">
            <section class="col-lg-4 col-lg-offset-4 omen-loginBox">
                <h1>forgot password</h1>

                <?php if (Session::has('omen_error')): ?>
                    <section class="alert alert-danger"><span
                            class="glyphicon glyphicon-warning-sign"></span> <?= Session::get('omen_error') ?></section>
                <?php endif;
                if (Session::has('omen_notice')):
                    ?>
                    <section class="alert alert-warning"><span
                            class="glyphicon glyphicon-info-sign"></span> <?= Session::get('omen_notice') ?></section>
                <?php endif; ?>
                <form action="" method="POST">
                    <input type="hidden" name="_token" value="<?= csrf_token() ?>"/>
                    <section class="omen-inputBox input-group input-group-lg">
                        <span class="input-group-addon" id="username-label">
                            <span class="glyphicon glyphicon-user"></span>
                        </span>
                        <input type="text" name="username" placeholder="username" class="form-control"
                               aria-describedby="username-label"/>
                    </section>

                    <button type="submit" class="btn btn-success btn-block btn-lg omen-button">
                        <span class="glyphicon glyphicon-lock"></span> Recover
                    </button>

                    <section class="row omen-linkBox">
                        <section class="col-md-6">
                            <a href="<?= URL::to('login'); ?>" class="btn btn-link pull-left">Login</a>
                        </section>
                        <section class="col-md-6">
                            <a href="<?= URL::to('signup'); ?>" class="btn btn-link">Create a new account</a>
                        </section>
                    </section>
                </form>
            </section>
        </section>
    </section>
<?= View::make('containers.footer')->render(); ?>