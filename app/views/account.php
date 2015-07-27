<?= View::make('containers.header')->render(); ?>
    <section class="container omen-maxContainer omen-loginContainer">
        <?php if (Session::has('omen_error')): ?>
            <section class="row">
                <section class="col-lg-6 col-lg-offset-3">
                    <section class="alert alert-danger"><span
                            class="glyphicon glyphicon-warning-sign"></span> <?= Session::get('omen_error') ?></section>
                </section>
            </section>
        <?php endif;
        if (Session::has('omen_notice')):
            ?>
            <section class="row">
                <section class="col-lg-6 col-lg-offset-3">
                    <section class="alert alert-warning"><span
                            class="glyphicon glyphicon-info-sign"></span> <?= Session::get('omen_notice') ?></section>
                </section>
            </section>
        <?php endif; ?>

        <section class="row">
            <section class="col-lg-4 omen-loginBox">
                <h1>account</h1>

                <form action="" method="POST">
                    <input type="hidden" name="section" value="account"/>
                    <section class="omen-inputBox input-group input-group-lg">
                        <span class="input-group-addon" id="username-label">
                            <span class="glyphicon glyphicon-user"></span>
                        </span>
                        <input type="text" readonly placeholder="username" class="form-control"
                               aria-describedby="username-label"
                               value="<?= $user->username; ?>"/>
                    </section>
                    <section class="omen-inputBox input-group input-group-lg">
                        <span class="input-group-addon" id="email-label">
                            <span class="glyphicon glyphicon-envelope"></span>
                        </span>
                        <input type="email" name="email" placeholder="email" class="form-control"
                               aria-describedby="email-label"
                               value="<?= $user->email; ?>"/>
                    </section>
                    <section class="omen-inputBox input-group input-group-lg">
                        <span class="input-group-addon" id="firstname-label">
                            <span class="glyphicon glyphicon-font"></span>
                        </span>
                        <input type="text" name="firstname" placeholder="firstname" class="form-control"
                               aria-describedby="firstname-label"
                               value="<?= $user->firstname; ?>"/>
                    </section>
                    <section class="omen-inputBox input-group input-group-lg">
                        <span class="input-group-addon" id="lastname-label">
                            <span class="glyphicon glyphicon-bold"></span>
                        </span>
                        <input type="text" name="lastname" placeholder="lastname" class="form-control"
                               aria-describedby="lastname-label"
                               value="<?= $user->lastname; ?>"/>
                    </section>

                    <button type="submit" class="btn btn-success btn-block btn-lg omen-button">
                        <span class="glyphicon glyphicon-save"></span> save
                    </button>
                </form>
            </section>
            <section class="col-lg-4 omen-loginBox">
                <h1>password</h1>

                <form action="" method="POST">
                    <input type="hidden" name="section" value="password"/>
                    <section class="omen-inputBox input-group input-group-lg">
                        <span class="input-group-addon" id="password-label">
                            <span class="glyphicon glyphicon-lock"></span>
                        </span>
                        <input type="password" name="oldpassword" placeholder="old password" class="form-control"
                               aria-describedby="password-label"/>
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

                    <button type="submit" class="btn btn-success btn-block btn-lg omen-button">
                        <span class="glyphicon glyphicon-save"></span> save
                    </button>
                </form>
            </section>
            <section class="col-lg-4 omen-loginBox">
                <h1>tokens</h1>
                <ul class="omen-tokens">
                    <?php foreach ($user->tokens as $token): ?>
                        <li>
                            <?= $token->uuid; ?>
                            <a href="<?= URL::to('/account/delete_token/' . $token->uuid) ?>">delete token</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        </section>
    </section>
<?= View::make('containers.footer')->render(); ?>