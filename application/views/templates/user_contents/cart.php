<div class="section" id="pricing">
    <div class="container">
        <div class="section-title">
            <!-- <small>PRICING</small> -->
            <h3>Pesanan Anda</h3>
        </div>
        <div class="tab-content">
            <?= form_open($url) ?>
            <?= $contents; ?>
            <div class="mb-2 col-md-6 col-lg-6 col-sm-12">
                <label for="message">Keterangan</label>
                <textarea name="message" id="message" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Pesan</button>
            <?= form_close() ?>
        </div>
    </div>
</div>