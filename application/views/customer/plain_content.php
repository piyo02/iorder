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
        <?php if ($popular_product == NULL) : ?>
            <div class="card-deck">
                <h3>Tidak ada produk</h3>
            </div>
        <?php else : ?>
            <div class="card-deck">
                <?php
                    if (count($popular_product) > 3)
                        $limit = 3;
                    else
                        $limtt = count($popular_product);
                    for ($i = 0; $i < $limit; $i++) : ?>
                    <div class="card pricing popular">
                        <div class="card-head">
                            <small class="text-primary"><?= $popular_product[$i]->category_name ?></small>
                            <div>
                                <img src="<?= $popular_product[$i]->_image ?>" alt="<?= $popular_product[$i]->name ?>" width="50%">
                            </div>
                            <h3><?= $popular_product[$i]->name ?></h4>
                        </div>
                        <ul class="list-group list-group-flush">
                            <div class="list-group-item">Rp. <?= number_format($popular_product[$i]->price) ?></div>
                        </ul>
                        <div class="card-body">
                            <button onclick="order(<?= $popular_product[$i]->id ?>)" class="btn btn-primary btn-lg btn-block">Pesan</button>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
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
    function add_order() {
        var element = document.getElementById('qty_order');
        console.log(parseInt(element.innerHTML) + 1);
        element.innerHTML = parseInt(element.innerHTML) + 1;
    }
</script>
<script>
    function order(id) {
        var product_id = id;
        var user_id = $('#user_id').val();
        var qty = parseInt($('#qty_order').html());
        console.log($('#qty_order').html(qty + 1))
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