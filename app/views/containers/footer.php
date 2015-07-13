<footer class="omen-footerContainer">
    <section class="container">
        <section class="row">
            <section class="col-lg-12 omen-poweredBy">
                powered by <a href="http://duricu.ro" class="spark">spark</a> - v<?= \Config::get('omen.version'); ?>
            </section>
        </section>
    </section>
</footer>
<?= View::make('containers.foot')->render(); ?>