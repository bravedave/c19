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

<nav class="navbar navbar-expand-md navbar-dark bg-dark">
  <div class="navbar-brand" href="#"><?= $this->data->title ?></div>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="<?= strings::url('admin') ?>">
          <?= dvc\icon::get( dvc\icon::house ) ?>

        </a>

      </li>

      <li class="nav-item">
        <a class="nav-link" href="<?= strings::url() ?>" target="_blank" rel="noopener">
          <?= dvc\icon::get( dvc\icon::app ) ?>
          <span class="d-md-none"> register</span>

        </a>

      </li>

      <li class="nav-item d-md-none">
        <a class="nav-link" href="<?= strings::url( 'events') ?>">
          <?= dvc\icon::get( dvc\icon::calendar_event ) ?>
          events

        </a>

      </li>

      <?php if ( currentUser::isAdmin()) {  ?>
        <li class="nav-item d-md-none">
          <a class="nav-link" href="<?= strings::url( 'users') ?>">
            <?= dvc\icon::get( dvc\icon::people ) ?>
            users

          </a>

        </li>

        <div class="nav-item d-md-none">
          <a class="nav-link" href="#" id="<?= $_settings = strings::rand() ?>">
            <?= dvc\icon::get( dvc\icon::sliders ) ?>
            settings

          </a>

        </div>

      <?php } // if ( currentUser::isAdmin())  ?>

      <div class="nav-item d-md-none">
        <a class="nav-link" href="<?= strings::url( 'logout') ?>">
          <?= dvc\icon::get( dvc\icon::person_dash ) ?>
          logoff

        </a>

      </div>

      <li class="nav-item d-none d-md-list-item">
        <a class="nav-link" href="https://github.com/bravedave/c19">
          <?= dvc\icon::get( dvc\icon::github ) ?>

        </a>

      </li>

      <?php if ( config::$REQUIRE_AUTHORIZATION) {  ?>
      <li class="nav-item d-none d-md-list-item">
        <a class="nav-link" title="logout" href="<?= strings::url( 'logout') ?>"><i class="bi bi-x bi-2x"></i></a>

      </li>

      <?php } ?>

    </ul>

  </div>

</nav>
<?php if ( currentUser::isAdmin()) {  ?>
  <script>
  $(document).ready( () => {
    $('#<?= $_settings ?>').on( 'click', function( e) {
      ( _ => {
        _.get.modal(_.url('admin/settings'));

      }) (_brayworth_);

    })

  });
  </script>

<?php } // if ( currentUser::isAdmin())  ?>
