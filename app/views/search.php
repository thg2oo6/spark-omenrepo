<?= View::make('containers.header', [
    "searchString" => $searchString
])->render(); ?>
    <section class="container omen-maxContainer omen-mainContent">
        <section class="row">
            <section class="col-lg-12 omen-searchInfo">
                Displaying packages for "<?= $searchString; ?>".
            </section>
        </section>
        <?php
        $i = 0;
        $n = $projects->count();
        foreach ($projects as $project): ?>
            <?php if ($i % 2 == 0): ?>
                <section class="row omen-packages">
            <?php endif; ?>
            <section class="col-lg-6 omen-package">
                <h3 class="omen-title">
                    <a href="<?= URL::to('/project/' . $project->name); ?>"><?= $project->name; ?></a>
                </h3>

                <p class="omen-provider">
                    by <a href="<?= URL::to('/profile/' . $project->user->username); ?>">
                        <?= $project->user->username ?></a>
                </p>

                <p class="omen-description">
                    <?= $project->description; ?>
                </p>

                <?php if (count($project->keywords) != 0): ?>
                    <p class="omen-tags">
                        <span class="glyphicon glyphicon-tags">&nbsp;</span>
                        <?php foreach ($project->keywords as $keyword): ?>
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