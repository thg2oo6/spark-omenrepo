<?= View::make('containers.header')->render(); ?>
<?php
$versions = $project->versions->sortByDesc('updated_at');
$latestVersion = $versions->first();
$jsonVersion = json_decode($latestVersion->omenFile);
?>
    <section class="container omen-maxContainer omen-mainContent">
        <section class="row omen-projectDetails">
            <section class="col-lg-8">
                <h1><?= $project->name; ?></h1>

                <h3><?= $project->description; ?></h3>

                <section class="omen-readme">
                    <?= Markdown::parse($latestVersion->readme); ?>
                </section>
            </section>
            <section class="col-lg-4">
                <ul class="omen-projectLinks">
                    <li class="omen-downloadElement">
                        <span class="glyphicon glyphicon-cloud-download"></span> omen install <?= $project->name; ?>
                    </li>
                    <li>
                        <a href="<?= URL::to('profile/' . $project->user->username); ?>">
                            <?= $project->user->username ?>
                        </a>
                        updated it <?= $project->updated_at->diffForHumans(); ?>
                    </li>
                    <li>
                        latest version published: <strong>v<?= $latestVersion->version ?></strong>
                    </li>
                    <?php if (!empty($project->license)): ?>
                        <li>
                            license: <?= $project->license; ?>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($project->homepage)): ?>
                        <li>
                            the <a href="<?= $project->homepage; ?>" target="_blank">homepage</a> is located here
                        </li>
                    <?php endif; ?>
                    <?php if ($project->keywords->count() != 0): ?>
                        <li class="omen-elementHeader">
                            <span class="glyphicon glyphicon-tags">&nbsp;</span> Keywords:
                        </li>
                        <li class="omen-tags">
                            <?php foreach ($project->keywords as $keyword): ?>
                                <a href="<?= URL::to('/keyword/' . $keyword->name); ?>"><?= $keyword->name; ?></a>
                            <?php endforeach; ?>
                        </li>
                    <?php endif; ?>
                    <li class="omen-elementHeader">
                        <span class="glyphicon glyphicon-tags">&nbsp;</span> Versions:
                    </li>
                    <li class="omen-versions">
                        <?php foreach ($versions as $version): ?>
                            <span class="omen-version"><?= $version->version; ?></span>
                        <?php endforeach; ?>
                    </li>
                    <?php if (isset($jsonVersion->repository)): ?>
                        <li class="omen-elementHeader">
                            <span class="glyphicon glyphicon-file">&nbsp;</span> Repository:
                        </li>
                        <li class="omen-repository">
                            <a href="<?= $jsonVersion->repository->url; ?>" target="_blank">
                                <?= $jsonVersion->repository->url; ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (isset($jsonVersion->contributors) && count($jsonVersion->contributors) != 0): ?>
                        <li class="omen-elementHeader">
                            <span class="glyphicon glyphicon-user">&nbsp;</span> Contributors:
                        </li>
                        <li class="omen-contributors">
                            <ul>
                                <?php foreach ($jsonVersion->contributors as $contributor):
                                    $user = User::where('email', $contributor->email)->first();
                                    ?>
                                    <li>
                                        <?php if (!is_null($user)): ?>
                                            <a href="<?= URL::to('/profile/' . $user->username); ?>" target="_blank">
                                                <?= $contributor->name; ?>
                                            </a>
                                        <?php else: ?>
                                            <?= $contributor->name; ?>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </section>
        </section>
    </section>
<?= View::make('containers.footer')->render(); ?>