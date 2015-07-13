Hello, <?= $user->username ?>!

To activate your account please follow the next link:
<?= URL::to('activate/' . $user->activationCode); ?>

Kind regards,
Omen Child
(Automatic mail bot)