<?= View::make('containers.head')->render(); ?>

<header class="omen-headerContainer">
    <section class="container">
        <section class="row omen-topPart">
            <section class="col-lg-2 col-md-4">
                <a href="<?= URL::to('/'); ?>" title="Omen | Spark" class="omen-logo">Omen</a>
            </section>
            <section class="col-lg-4 col-lg-offset-6 omen-userProfile col-md-8">
                <?php
                if (Auth::guest())
                    echo View::make('profile.guest')->render();
                else
                    echo View::make('profile.user')->render();
                ?>
            </section>
        </section>
        <section class="row omen-searchField">
            <section class="col-lg-12">
                <form action="<?= URL::to('/search'); ?>" method="GET">
                    <section class="input-group input-group-lg">
                        <input type="text" class="form-control" name="package"
                               placeholder="The package you are searching for ..."
                            <?php if (isset($searchString)): ?>
                                value="<?= $searchString; ?>"
                            <?php endif; ?>
                            />
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button">
                                <span class="glyphicon glyphicon-search"></span>
                            </button>
                        </span>
                    </section>
                </form>
                <!-- /input-group -->
            </section>
        </section>
    </section>
</header>