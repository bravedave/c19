<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
 * styleguide : https://codeguide.co/
*/  ?>

<style>
[data-role="content-secondary"] .qr-about { display: none; }
</style>

<style media="print">
footer { display: none; }
</style>

<div class="row mt-4">
  <div class="col text-center">
    <a href="<?= strings::url( 'admin/qrcode/v') ?>" target="_blank" rel="noopener">
      <img class="img-fluid" src="<?= strings::url( $this->route . '/qrcode') ?>" >

    </a>

  </div>

</div>

<div class="row">
  <div class="col text-center text-truncate h2"><?= strings::url('', $protocol = true) ?></div>

</div>

<div class="row mt-4 qr-about">
  <div class="offset-2 col-8 text-center h2"><?= strings::text2html( config::qrFooter()) ?></div>

</div>
