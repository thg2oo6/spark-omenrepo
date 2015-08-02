<?= View::make('containers.header_admin', [
    'page' => 'keywords'
])->render(); ?>
    <section class="container omen-maxContainer omen-mainContent omen-adminContent">
        <section class="row">
            <section class="col-lg-12 omen-keywords">
                <h1>Keywords</h1>
                <table class="table table-responsive">
                    <thead>
                    <tr>
                        <th class="omen-keyword">Keyword</th>
                        <th class="omen-projects">Projects</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($keywords as $keyword): ?>
                        <tr>
                            <td class="omen-keyword">
                                <a href="<?= URL::to('keyword/' . $keyword->name); ?>" target="_blank">
                                    <?= $keyword->name; ?>
                                </a>
                            </td>
                            <td class="omen-projects"><?= $keyword->projects->transform(function ($e) {
                                    $e->urlName = '<a href="' . URL::to('project/' . $e->name) . '" target="_blank">'
                                        . $e->name . '</a>';
                                    return $e;
                                })->implode('urlName', ', '); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </section>
    </section>
<?= View::make('containers.footer')->render(); ?>