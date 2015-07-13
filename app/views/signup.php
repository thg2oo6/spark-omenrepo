<?= View::make('containers.head')->render(); ?>
    <section class="container omen-maxContainer omen-loginContainer">
        <section class="row">
            <section class="col-lg-4 col-lg-offset-4 omen-loginBox">
                <h1>signup</h1>

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
                               aria-describedby="username-label"
                               value="<?= Input::old('username'); ?>"/>
                    </section>
                    <section class="omen-inputBox input-group input-group-lg">
                        <span class="input-group-addon" id="email-label">
                            <span class="glyphicon glyphicon-envelope"></span>
                        </span>
                        <input type="email" name="email" placeholder="email" class="form-control"
                               aria-describedby="email-label"
                               value="<?= Input::old('email'); ?>"/>
                    </section>
                    <section class="omen-inputBox input-group input-group-lg">
                        <span class="input-group-addon" id="password-label">
                            <span class="glyphicon glyphicon-lock"></span>
                        </span>
                        <input type="password" name="password" placeholder="password" class="form-control"
                               aria-describedby="password-label"/>
                    </section>
                    <section class="omen-inputBox input-group input-group-lg">
                        <span class="input-group-addon" id="cpassword-label">
                            <span class="glyphicon glyphicon-exclamation-sign"></span>
                        </span>
                        <input type="password" name="cpassword" placeholder="password confirmation" class="form-control"
                               aria-describedby="cpassword-label"/>
                    </section>
                    <section class="omen-inputBox input-group input-group-lg">
                        <span class="input-group-addon" id="firstname-label">
                            <span class="glyphicon glyphicon-font"></span>
                        </span>
                        <input type="text" name="firstname" placeholder="firstname" class="form-control"
                               aria-describedby="firstname-label"
                               value="<?= Input::old('firstname'); ?>"/>
                    </section>
                    <section class="omen-inputBox input-group input-group-lg">
                        <span class="input-group-addon" id="lastname-label">
                            <span class="glyphicon glyphicon-bold"></span>
                        </span>
                        <input type="text" name="lastname" placeholder="lastname" class="form-control"
                               aria-describedby="lastname-label"
                               value="<?= Input::old('lastname'); ?>"/>
                    </section>

                    <button type="submit" class="btn btn-success btn-block btn-lg omen-button">
                        <span class="glyphicon glyphicon-user"></span> signup
                    </button>

                    <section class="row omen-linkBox">
                        <section class="col-md-6">
                            <a href="<?= URL::to('forgot_password'); ?>" class="btn btn-link">Forgot the password?</a>
                        </section>
                        <section class="col-md-6">
                            <a href="<?= URL::to('login'); ?>" class="btn btn-link pull-right">Login</a>
                        </section>
                    </section>
                </form>
            </section>
        </section>
    </section>
<?= View::make('containers.footer')->render(); ?>