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
<table class="table table-sm">
	<thead class="small">
		<tr>
			<td class="h5 m-0">Event</td>
			<td>Duration</td>

		</tr>

	</thead>

	<tbody>
	<?php
		printf( '<td>%s</td>', $dto->description);
		if ( $dto->open) {
      printf( '<td>%s</td>', 'open event');

    }
    else {
      printf( '<td>%s - %s</td>',
        strings::asLocalDate( $dto->start, $time = true),
        strings::asLocalDate( $dto->end, $time = true));

    }

		print '</tr>';

  ?></tbody>

</table>

<h5 id="<?= $_registrations = strings::rand() ?>heading" class="d-flex">
  Attendees
  <div class="spinner-grow spinner-grow-sm ml-auto" role="status">
    <span class="sr-only">Loading...</span>
  </div>
</h5>
<div id="<?= $_registrations ?>"></div>

<script>
$(document).ready( () => {
  ( _ => {
    $('#<?= $_registrations ?>').on('get-attendees', function(e) {
      e.stopPropagation();

      $('.spinner-grow', '#<?= $_registrations ?>heading').removeClass('d-none');
      let url = _.url( '<?= $this->route ?>/attendees/<?= $dto->id ?>');
      // console.log( url);

      $(this).load( url, data => {
        setTimeout(() => {
          $('#<?= $_registrations ?>').trigger('get-attendees');
        }, 10000);

        $('.spinner-grow', '#<?= $_registrations ?>heading').addClass('d-none');

      });

    })
    .trigger('get-attendees');

    $(document).on( 'edit-attendee', ( e, id ) => {
      _.get.modal( _.url( '<?= $this->route ?>/editRegistration/' + id))
        .then( modal => modal.on('success', e => $('#<?= $_registrations ?>').trigger('get-attendees')));

    });

  }) (_brayworth_);

});
</script>
