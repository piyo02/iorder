<div class="light-bg py-5" id="contact">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 text-center text-lg-left">
                <p class="mb-2"> <span class="ti-location-pin mr-2"></span><?= $store->address ?></p>
                <div class=" d-block d-sm-inline-block">
                    <p class="mb-2">
                        <span class="ti-email mr-2"></span> <a class="mr-4" href="mailto:<?= $store->email ?>"><?= $store->email ?></a>
                    </p>
                </div>
                <div class="d-block d-sm-inline-block">
                    <p class="mb-0">
                        <span class="ti-headphone-alt mr-2"></span> <a href="tel:<?= $store->phone ?>"><?= $store->phone ?></a>
                    </p>
                </div>

            </div>
            <div class="col-lg-6">
                <div class="social-icons">
                    <a href="<?= $store->facebook_url ?>"><span class="ti-facebook"></span></a>
                    <a href="<?= $store->instagram_url ?>"><span class="ti-instagram"></span></a>
                </div>
            </div>
        </div>
    </div>
</div>
<footer class="my-5 text-center">
    <p class="mb-2"><small>©<?= date('Y') ?>. FIXLabs</small></p>
</footer>
<!-- jQuery and Bootstrap -->
<script src="<?= base_url('assets-user/') ?>js/jquery-3.2.1.min.js"></script>
<script src="<?= base_url('assets-user/') ?>js/jquery-idle/jquery.idle.js"></script>
<script src="<?= base_url('assets-user/') ?>js/bootstrap.bundle.min.js"></script>
<!-- Plugins JS -->
<script src="<?= base_url('assets-user/') ?>js/owl.carousel.min.js"></script>
<!-- Custom JS -->
<script src="<?= base_url('assets-user/') ?>js/script.js"></script>
<script>
    $(document).idle({
        onIdle: function() {
            window.location = "<?= base_url() . 'auth/logout/1' ?>";
        },
        idle: 1000 * 60 * 30
    });
</script>
</body>

</html>