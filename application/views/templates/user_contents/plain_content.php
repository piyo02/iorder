<?php
//id, name, price, _image where stock > 0
?>
<input type="hidden" value="<?= $user_id ?>" id="user_id" name="user_id">
<div class="section" id="pricing">
    <div class="container">
        <div class="section-title">
            <!-- <small>PRICING</small> -->
            <h3>Produk Terlaris</h3>
        </div>
        <div class="card-deck">
            <?php for ($i = 0; $i < 3; $i++) : ?>
                <div class="card pricing popular">
                    <div class="card-head">
                        <small class="text-primary"><?= $products[$i]->category_name ?></small>
                        <div>
                            <img src="<?= $products[$i]->_image ?>" alt="<?= $products[$i]->name ?>" width="50%">
                        </div>
                        <h3><?= $products[$i]->name ?></h4>
                    </div>
                    <ul class="list-group list-group-flush">
                        <div class="list-group-item">Rp. <?= number_format($products[$i]->price) ?></div>
                    </ul>
                    <div class="card-body">
                        <button onclick="order(<?= $products[$i]->id ?>)" class="btn btn-primary btn-lg btn-block">Pesan</button>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</div>
<!-- // end .section -->
<div class="section light-bg">
    <div class="container">
        <div class="section-title">
            <!-- <small></small> -->
            <h3>Produk Kami</h3>
        </div>

        <ul class="nav nav-tabs nav-justified" role="tablist">
            <?php foreach ($categories as $key => $category) : ?>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#<?= $category->id ?>"><?= $category->name ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="tab-content">
            <?php foreach ($categories as $key => $category) : ?>
                <div class="tab-pane fade show" id="<?= $category->id ?>">
                    <div class="d-flex flex-column flex-lg-row">
                        <div class="img-gallery owl-carousel owl-theme">
                            <?php foreach ($products as $key => $product) :
                                    if ($product->category_name == $category->name) :
                                        ?>
                                    <div class="card pricing popular">
                                        <div class="card-head">
                                            <small class="text-primary"><?= $product->category_name ?></small>
                                            <div>
                                                <img src="<?= $product->_image ?>" alt="<?= $product->name ?>" width="50%">
                                            </div>
                                            <h3><?= $product->name ?></h4>
                                        </div>
                                        <ul class="list-group list-group-flush">
                                            <div class="list-group-item">Rp. <?= number_format($product->price) ?></div>
                                        </ul>
                                        <div class="card-body">
                                            <button onclick="order(<?= $products[$i]->id ?>)" class="btn btn-primary btn-lg btn-block">Pesan</>
                                        </div>
                                    </div>
                            <?php
                                    endif;
                                endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>


<script>
    function order(id) {
        var product_id = id;
        var user_id = $('#user_id').val();
        $.ajax({
            type: 'POST', //method
            url: '<?= base_url('product/add_hold_order') ?>', //action
            data: {
                user_id: user_id,
                product_id: product_id,
                quantity: 1
            }, //data yang dikrim ke action $_POST['id']
            dataType: 'json',
            async: false,
            success: function(data) {
                console.log(data);
            }
        });
    }
</script>