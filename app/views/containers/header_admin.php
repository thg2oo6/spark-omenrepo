<?= View::make('containers.head')->render(); ?>

<header class="omen-headerContainer">
    <section class="container">
        <section class="row omen-topPart">
            <section class="col-lg-2 col-md-4">
                <a href="<?= URL::to('/'); ?>" title="Omen | Spark" class="omen-logo">Omen</a>
            </section>
            <section class="col-lg-4 col-lg-offset-6 omen-userProfile col-md-8">
                <?php
                if (Auth::guest())
                    echo View::make('profile.guest')->render();
                else
                    echo View::make('profile.user')->render();
                ?>
            </section>
        </section>
        <section class="row omen-adminpanel">
            <ul class="nav nav-pills nav-justified">
                <li role="presentation" <?= $page == 'dashboard' ? 'class="active"' : ''; ?>>
                    <a href="<?= URL::to('admin'); ?>"><span class="glyphicon glyphicon-dashboard"></span> Dashboard</a>
                </li>
                <li role="presentation" <?= $page == 'projects' ? 'class="active"' : ''; ?>>
                    <a href="<?= URL::to('admin/projects'); ?>"><span class="glyphicon glyphicon-folder-close"></span>
                        Projects</a>
                </li>
                <li role="presentation" <?= $page == 'keywords' ? 'class="active"' : ''; ?>>
                    <a href="<?= URL::to('admin/keywords'); ?>"><span class="glyphicon glyphicon-tags"></span> Keywords</a>
                </li>
                <li role="presentation" <?= $page == 'users' ? 'class="active"' : ''; ?>>
                    <a href="<?= URL::to('admin/users'); ?>"><span class="glyphicon glyphicon-user"></span> Users</a>
                </li>
            </ul>
        </section>
    </section>
</header>