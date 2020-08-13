<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace c19;

use strings;
use dvc;    ?>

<nav class="navbar navbar-dark bg-bilinga">
  <a class="navbar-brand" href="#">
    <img class="d-inline-block align-top"
      alt="" loading="lazy" height="50" width="50" src="<?= strings::url( 'image/logo' ) ?>"
      style="margin: -10px 0;" />
    <?= $this->data->title ?>
  </a>
  <button tyle="button" class="btn btn-bilinga" name="refresh" id="<?= $_uid = strings::rand() ?>"><i class="fa fa-refresh"></i></button>
</nav>
<script>
$(document).ready( () => {
  $('#<?= $_uid ?>').on( 'click', function( e) {
    e.stopPropagation();e.preventDefault();

    window.location.reload();

  })

});
</script>
