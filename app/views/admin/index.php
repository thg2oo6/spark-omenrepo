<?= View::make('containers.header_admin', [
    'page' => 'dashboard'
])->render(); ?>
    <section class="container omen-maxContainer omen-mainContent omen-adminContent">
        <section class="row">
            <section class="col-lg-8 omen-dashboardPackage">
                <h1>Latest packages</h1>
                <table class="table table-responsive">
                    <thead>
                    <tr>
                        <th class="omen-tablePackage">Pacakge</th>
                        <th class="omen-tableVersion">Version</th>
                        <th class="omen-tableUser">User</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($packages as $package): ?>
                        <tr>
                            <td class="omen-tablePackage">
                                <a href="<?= URL::to('project/' . $package->name); ?>" target="_blank">
                                    <?= $package->name; ?>
                                </a>
                            </td>
                            <td class="omen-tableVersion"><?= $package->versions->last()->version; ?></td>
                            <td class="omen-tableUser">
                                <a href="<?= URL::to('profile/' . $package->user->username); ?>" target="_blank">
                                    <?= $package->user->username; ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
            <section class="col-lg-4 omen-dashboardKeywords">
                <h1>Most used keywords</h1>
                <table class="table table-responsive">
                    <thead>
                    <tr>
                        <th>Keyword</th>
                        <th>Usage</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($keywords->take(10) as $keyword): ?>
                        <tr>
                            <td class="omen-tableKeyword">
                                <a href="<?= URL::to('keyword/' . $keyword->name); ?>" target="_blank">
                                    <?= $keyword->name; ?>
                                </a>
                            </td>
                            <td class="omen-tableUsage"><?= $keyword->projects->count(); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </section>
    </section>
<?= View::make('containers.footer')->render(); ?>