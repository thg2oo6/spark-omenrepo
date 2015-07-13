<a href="<?= URL::to('/profile/' . Auth::user()->username); ?>" class="omen-userImage">
    <img src="http://www.gravatar.com/avatar/<?= md5(Auth::user()->email); ?>?s=75"
         alt="profileImg"/>
</a>
<ul>
    <li><a href="<?= URL::to('/profile/' . Auth::user()->username); ?>"
           class="omen-mainlink"><?= Auth::user()->username; ?></a></li>
    <li>
        <a href="<?= URL::to('/account'); ?>" class="omen-sidelink">my account</a>
        <a href="<?= URL::to('/logout'); ?>" class="omen-sidelink">logout</a>
    </li>
</ul>