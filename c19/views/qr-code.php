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
