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

use strings;    ?>

<nav class="nav flex-column">
  <div class="nav-item">
    <a class="nav-link" href="<?= strings::url( 'events') ?>"><i class="bi bi-calendar-event"></i> events</a>

  </div>

  <?php if ( currentUser::isAdmin()) {  ?>
    <div class="nav-item">
      <a class="nav-link" href="<?= strings::url( 'users') ?>"><i class="bi bi-people"></i> users</a>

    </div>

    <div class="nav-item">
      <a class="nav-link" href="#" id="<?= $_settings = strings::rand() ?>"><i class="bi bi-gear"></i> settings</a>

    </div>
    <script>
    $(document).ready( () => {
      $('#<?= $_settings ?>').on( 'click', e => {
        e.stopPropagation();e.preventDefault();

        ( _ => {
          _.get.modal(_.url('<?= $this->route ?>/settings'));

        }) (_brayworth_);

      })

    });
    </script>

  <?php } ?>

  <?php if ( currentUser::isProgrammer()) {  ?>
    <div class="nav-item">
      <a class="nav-link" href="<?= strings::url( 'admin/dbdownload') ?>"><i class="bi bi-hdd-stack"></i> DB Download</a>

    </div>

  <?php } ?>

</nav>
