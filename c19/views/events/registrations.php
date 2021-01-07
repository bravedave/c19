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

$dto = $this->data->event;  ?>
<h1 class="d-none d-print-block"><?= $this->title ?></h1>
<div class="row">
  <div class="col text-truncate h5 m-0 pt-2"><?= $dto->description ?></div>
  <div class="col-2 text-right pl-0">
    <?php if ( currentUser::isAdmin()) {  ?>
      <button class="btn btn-light"
        id="<?= $_uid = strings::rand() ?>">
        <i class="bi bi-pencil"></i>

      </button>
      <script>
      ( _ => {
        $(document).ready( () => {
          $('#<?= $_uid ?>').on( 'click', function( e) {
            e.stopPropagation();e.preventDefault();

            _.get.modal( _.url('<?= $this->route ?>/edit/<?= $dto->id ?>'))
            .then( m => m.on( 'success', e => window.location.reload()));

          });

        });

      }) (_brayworth_);
      </script>

    <?php } // if ( currentUser::isAdmin())  ?>

    <button class="btn btn-light"
      id="<?= $_refresh = strings::rand() ?>">
      <i class="bi bi-arrow-repeat"></i>

    </button>

  </div>

</div>

<div class="row mb-2">
  <div class="col border-bottom">
  <?php
  if ( $dto->open) {
    print 'open event';

  }
  else {
    printf( '%s - %s',
      strings::asLocalDate( $dto->start, $time = true),
      strings::asLocalDate( $dto->end, $time = true));

  } ?>

  </div>

</div>

<h5 id="<?= $_registrations = strings::rand() ?>heading" class="d-flex">
  <div class="mr-auto pt-2">Attendees</div>
  <div class="spinner-grow mx-2" role="status">
    <span class="sr-only">Loading...</span>
  </div>
  <button class="btn btn-light" id="<?= $_registrations ?>-add"><i class="bi bi-person-plus"></i></button>

</h5>

<div class="row">
  <div class="col px-0 px-md-3" id="<?= $_registrations ?>">&nbsp;</div>

</div>

<script>
( _ => {
  let iterant = 0;
  $('#<?= $_registrations ?>').on('get-attendees', function(e) {
    e.stopPropagation();

    let _iterant = ++iterant;

    $('.spinner-grow', '#<?= $_registrations ?>heading').removeClass('d-none');

    let url = _.url( '<?= $this->route ?>/attendees/<?= $dto->id ?>');
    let icon = $('#<?= $_refresh ?> > .bi');
    if ( icon.length > 0) icon.spinner().prop( 'disabled', true);

    // console.log( url);

    $(this).load( url, data => {
      if ( _iterant == iterant) {
        setTimeout(() => {
          $('#<?= $_registrations ?>').trigger('get-attendees');

        }, 10000);

      }

      $('.spinner-grow', '#<?= $_registrations ?>heading').addClass('d-none');
      if ( icon.length > 0) icon.spinner('off');

    });

  });

  $('#<?= $_refresh ?>').on( 'click', function( e) {
    e.stopPropagation();
    $('#<?= $_registrations ?>').trigger('get-attendees');

  });

  $('#<?= $_registrations ?>-add').on( 'click', function( e) {
    e.stopPropagation();

    $(document).trigger( 'add-attendee');

  });

  $(document).on( 'add-attendee', ( e, id ) => {
    _.get.modal( _.url( '<?= $this->route ?>/addRegistration/<?= $dto->id ?>'))
      .then( modal => modal.on('success', e => $('#<?= $_registrations ?>').trigger('get-attendees')));

  });

  $(document).on( 'edit-attendee', ( e, id ) => {
    _.get.modal( _.url( '<?= $this->route ?>/editRegistration/' + id))
      .then( modal => modal.on('success', e => $('#<?= $_registrations ?>').trigger('get-attendees')));

  });

  $(document).on( 'checkout-attendee', ( e, id ) => {
    // console.log( '<?= $this->route ?>', id);
    _.post({
      url : _.url('<?= $this->route ?>'),
      data : {
        action : 'checkout-attendee',
        id : id

      },

    }).then( d => {
      if ( 'ack' == d.response) {
        $('#<?= $_registrations ?>').trigger('get-attendees');

      }
      else {
        _.growl( d);

      }

    });

  });

  $(document).on( 'checkout-attendees', ( e, ids ) => {
    // console.log( '<?= $this->route ?>', ids);
    // return;

    if ( !ids) return;

    _.post({
      url : _.url('<?= $this->route ?>'),
      data : {
        action : 'checkout-attendees',
        ids : ids

      },

    }).then( d => {
      if ( 'ack' == d.response) {
        $('#<?= $_registrations ?>').trigger('get-attendees');

      }
      else {
        _.growl( d);

      }

    });

  });

  $(document).ready( () => $('#<?= $_registrations ?>').trigger('get-attendees'));

})( _brayworth_);
</script>
