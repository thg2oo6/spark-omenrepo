<?= View::make('containers.header')->render(); ?>
    <section class="container omen-maxContainer omen-mainContent">
        <section class="row omen-profile">
            <section class="col-lg-9 omen-projects">
                <h1><?= $user->username; ?></h1>
                <?php if ($projects->count() == 0): ?>
                    No projects published yet :(...
                <?php else: ?>
                    <section class="omen-projectsCount">
                        <span class="omen-count"><?= $projects->count(); ?></span>
                        published project(s)
                    </section>
                    <ul class="omen-projectsList">
                        <?php foreach ($projects as $project): ?>
                            <li>
                                <a href="<?= URL::to('project/' . $project->name); ?>"><?= $project->name; ?></a> -
                                <strong class="omen-projectVersion">v<?= $project->versions->max('version'); ?></strong>
                                -
                                <?= $project->description; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </section>
            <section class="col-lg-3 omen-userProfile">
                <img src="http://www.gravatar.com/avatar/<?= md5($user->email); ?>?s=250"
                     alt="profileImg"
                     class="omen-userImage"/>

                <ul class="omen-userInformation">
                    <li class="omen-elementHeader">
                        <?= $user->firstname . ' ' . $user->lastname; ?>
                    </li>

                    <?php if (Auth::user() != null && Auth::user()->id == $user->id): ?>
                        <li class="omen-element">
                            <a href="<?= URL::to('account'); ?>">Edit profile</a>
                        </li>
                        <li class="omen-element">
                            <a href="<?= URL::to('logout'); ?>">Logout</a>
                        </li>
                    <?php endif; ?>

                </ul>
            </section>
        </section>
    </section>
<?= View::make('containers.footer')->render(); ?>