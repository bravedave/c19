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
  thanks for checking out

  <?php
  if ( $this->data->dtoSet) {
    print '<ul>';
    foreach ($this->data->dtoSet as $dto) {
      printf( '<li>%s</li>', $dto->name);

    }
    print '</ul>';

  } ?>

  <div><?= date('r') ?></div>

</div>

