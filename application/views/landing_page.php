<!-- Promo Block -->
<div class="col-md-12" id="bg-landing" style="height:100vh; background-image: url(<?= base_url('assets-user/') . 'images/bg-landing-page.jpg' ?>); background-size : cover; background-repeat: no-repeat;">
  <div class="content" style="color:white;padding-top:200px; text-align:center">
    <h1>Welcome To iorder!</h1>
  </div>
</div>
<!-- End Promo Block -->
<script>
  var lebar = window.innerWidth;

  if (lebar <= 600) {
    document.getElementById('bg-landing').style.backgroundPosition = "center";
  } else {
    document.getElementById('bg-landing').style.backgroundPosition = "center top";
  }
</script>