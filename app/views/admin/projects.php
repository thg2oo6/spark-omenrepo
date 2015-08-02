<?= View::make('containers.header_admin', [
    'page' => 'projects'
])->render(); ?>
    <section class="container omen-maxContainer omen-mainContent omen-adminContent">
        <section class="row">
            <section class="col-lg-12 omen-projects">
                <h1>Projects</h1>
                <table class="table table-responsive">
                    <thead>
                    <tr>
                        <th class="omen-project">Project</th>
                        <th class="omen-publisher">Publisher</th>
                        <th class="omen-version">Version</th>
                        <th class="omen-actions">&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($projects as $project): ?>
                        <tr>
                            <td class="omen-project">
                                <a href="<?= URL::to('project/' . $project->name); ?>">
                                    <?= $project->name; ?>
                                </a>
                            </td>
                            <td class="omen-publisher">
                                <a href="<?= URL::to('profile/' . $project->user->username); ?>">
                                    <?= $project->user->username; ?>
                                </a>
                            </td>
                            <td class="omen-version">
                                <?= $project->versions->last()->version; ?>
                            </td>
                            <td class="omen-actions">
                                <a href="<?= URL::to('admin/projects/' . $project->id . '/delete'); ?>">
                                    Delete project
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </section>
    </section>
<?= View::make('containers.footer')->render(); ?>