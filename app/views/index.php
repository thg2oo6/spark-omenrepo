<?= View::make('containers.header')->render(); ?>
    <section class="container omen-maxContainer omen-mainContent">
        <section class="row">
            <section class="col-lg-12 omen-searchInfo">
                Displaying latest created/modified packages.
            </section>
        </section>
        <?php
        $i = 0;
        $n = $versions->count();
        foreach ($versions as $version): ?>
            <?php if ($i % 2 == 0): ?>
                <section class="row omen-packages">
            <?php endif; ?>
            <section class="col-lg-6 omen-package">
                <h3 class="omen-title">
                    <a href="<?= URL::to('/project/' . $version->project->name); ?>"><?= $version->project->name; ?></a>
                    <span class="omen-version">v<?= $version->version; ?></span>
                </h3>

                <p class="omen-provider">
                    by <a href="<?= URL::to('/profile/' . $version->project->user->username); ?>">
                        <?= $version->project->user->username ?></a>
                </p>

                <p class="omen-description">
                    <?= $version->project->description; ?>
                </p>

                <?php if (count($version->project->keywords) != 0): ?>
                    <p class="omen-tags">
                        <span class="glyphicon glyphicon-tags">&nbsp;</span>
                        <?php foreach ($version->project->keywords as $keyword): ?>
                            <a href="<?= URL::to('/keyword/' . $keyword->name); ?>"><?= $keyword->name; ?></a>
                        <?php endforeach; ?>
                    </p>
                <?php endif; ?>
            </section>
            <?php
            $i++;
            if ($i % 2 == 0 || $i == $n): ?>
                </section>
            <?php endif;
        endforeach; ?>
    </section>
<?= View::make('containers.footer')->render(); ?>