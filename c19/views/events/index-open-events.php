<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/  ?>

<nav class="nav flex-column mt-2">
  <?php
	while ( $dto = $this->data->openevents->dto()) {  ?>
  <div class="nav-item">
    <a class="nav-link" href="<?= strings::url('events/registrations/' . $dto->id) ?>"><i class="bi bi-calendar-event"></i> <?= $dto->description ?></a>

  </div>
  <?php
  } ?>

</nav>
