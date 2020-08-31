<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/    ?>

<div class="alert alert-info" role="alert">
  <h5>thanks for registering</h5>

  <?php
  if ( $this->data->dtoSet) {
    print '<ul>';
    foreach ($this->data->dtoSet as $dto) {
      printf( '<li>%s</li>', $dto->name);

    }
    print '</ul>';

  } ?>

  <p>Please keep this screen open on your phone during
  your stay at the Club so your registration can be
  verified if required.</p>

  <div><?= date('r') ?></div>

  <?php if ( config::$CHECKOUT) { ?>
  <div class="d-flex mt-3">
    <button type="button" class="btn btn-info ml-auto" id="<?= $_uid = strings::rand() ?>">checkout</button>

  </div>
  <script>
  $(document).ready( () => {
    $('#<?= $_uid ?>').on( 'click', function( e) {
      e.stopPropagation();e.preventDefault();

      $(document).trigger('checkout');

    });

  });
  </script>
  <?php } // if ( config::$CHECKOUT) ?>

</div>

