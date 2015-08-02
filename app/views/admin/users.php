<?= View::make('containers.header_admin', [
    'page' => 'users'
])->render(); ?>
    <section class="container omen-maxContainer omen-mainContent omen-adminContent">
        <section class="row">
            <section class="col-lg-12 omen-users">
                <h1>Users</h1>
                <table class="table table-responsive">
                    <thead>
                    <tr>
                        <th class="omen-username">Username</th>
                        <th class="omen-fullname">Full Name</th>
                        <th class="omen-email">Email</th>
                        <th class="omen-admin">Admin?</th>
                        <th class="omen-active">Active?</th>
                        <th class="omen-projects">Projects</th>
                        <th class="omen-actions">&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="omen-username">
                                <a href="<?= URL::to('profile/' . $user->username); ?>" target="_blank">
                                    <?= $user->username; ?>
                                </a>
                            </td>
                            <td class="omen-fullname">
                                <?= $user->firstname . ' ' . $user->lastname; ?>
                            </td>
                            <td class="omen-email">
                                <?= $user->email; ?>
                            </td>
                            <td class="omen-admin">
                                <?= $user->isAdmin ? '&check;' : '&times;'; ?>
                                <?php if ($user->id != Auth::user()->id): ?>
                                    <a href="<?= URL::to('admin/users/' . $user->id . '/makeAdmin'); ?>">
                                        <?= !$user->isAdmin ? '&check;' : '&times;'; ?>
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td class="omen-active">
                                <?= $user->isActive ? '&check;' : '&times;'; ?>
                                <?php if ($user->id != Auth::user()->id): ?>
                                    <a href="<?= URL::to('admin/users/' . $user->id . '/makeActive'); ?>">
                                        <?= !$user->isActive ? '&check;' : '&times;'; ?>
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td class="omen-projects">
                                <?= $user->projects->count(); ?>
                            </td>
                            <td class="omen-actions">
                                <?php if ($user->id != Auth::user()->id): ?>
                                    <ul>
                                        <li>
                                            <a href="<?= URL::to('admin/users/' . $user->id . '/reset'); ?>">Reset
                                                Password</a>
                                        </li>
                                        <li>
                                            <a href="<?= URL::to('admin/users/' . $user->id . '/delete'); ?>">Delete</a>
                                        </li>
                                    </ul>
                                <?php else: ?>
                                    &nbsp;
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </section>
    </section>
<?= View::make('containers.footer')->render(); ?>