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

</div>

