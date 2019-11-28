<div class="nav-menu fixed-top">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <nav class="navbar navbar-dark navbar-expand-lg">
                    <a class="navbar-brand" href="index.html">iorder</a>
                    <!-- <a class="navbar-brand" href="index.html"><img src="<?= base_url('assets-user/') ?>images/logo.png" class="img-fluid" alt="logo"></a> -->
                    <a class="navbar-brand" href="<?= base_url('product/detail_order') ?>"><span class="ti-shopping-cart"></span></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
                    <div class="collapse navbar-collapse" id="navbar">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item"> <a class="nav-link active" href="<?= base_url('product') ?>">Beranda <span class="sr-only">(current)</span></a> </li>
                            <!-- <li class="nav-item"> <a class="nav-link" href="#features">FEATURES</a> </li>
                                <li class="nav-item"> <a class="nav-link" href="#gallery">GALLERY</a> </li>
                                <li class="nav-item"> <a class="nav-link" href="#pricing">PRICING</a> </li>
                                <li class="nav-item"> <a class="nav-link" href="#contact">CONTACT</a> </li> -->
                            <li class="nav-item"><a href="#" class="btn btn-outline-light my-3 my-sm-0 ml-lg-3">Pesanan</a></li>
                            <li class="nav-item"> <a class="nav-link" href="<?= base_url('auth/logout/1') ?>">Logout</a> </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>
<header class="bg-gradient" id="home">
    <div class="container mt-5">
        <h1><?= $store->name; ?></h1>
        <p class="tagline"><?= $store->slogan; ?></p>
    </div>
    <div class="img-holder mt-3"><img src="<?= base_url('assets-user/') ?>images/iphonex.png" alt="phone" class="img-fluid"></div>
</header>