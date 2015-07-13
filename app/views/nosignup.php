<?= View::make('containers.head')->render(); ?>
    <section class="container omen-maxContainer omen-loginContainer">
        <section class="row">
            <section class="col-lg-4 col-lg-offset-4 omen-loginBox">
                <h1>signup</h1>

                <section class="alert alert-warning">
                    <span class="glyphicon glyphicon-info-sign"></span>
                    We are sorry, but the registration has been disabled!
                </section>

                <section class="row omen-linkBox">
                    <section class="col-md-6">
                        <a href="<?= URL::to('forgot_password'); ?>" class="btn btn-link">Forgot the password?</a>
                    </section>
                    <section class="col-md-6">
                        <a href="<?= URL::to('login'); ?>" class="btn btn-link pull-right">Login</a>
                    </section>
                </section>
            </section>
        </section>
    </section>
<?= View::make('containers.footer')->render(); ?>