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

use strings;  ?>

<h1 class="d-none d-print-block"><?= $this->title ?></h1>
<h5>Event</h5>
<table class="table table-sm">
	<thead class="small">
		<tr>
			<td>Description</td>
			<td>Duration</td>

		</tr>

	</thead>

	<tbody>
	<?php
    $dto = $this->data->event;
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

<h5>Attendees</h5>
<table class="table table-sm" id="<?= $_table = strings::rand() ?>">
	<thead class="small">
		<tr>
			<td class="text-center">#</td>
			<td>Time</td>
			<td>Name</td>
			<td class="text-center">Party</td>
			<td>Mobile</td>
			<td>Address</td>

		</tr>

	</thead>

	<tbody>
	<?php
	while ( $dto = $this->data->registrations->dto()) {
		printf( '<tr data-id="%d">', $dto->id);

		print '<td class="small text-center" line-number>&nbsp;</td>';

		printf( '<td>%s</td>', strings::asLocalDate( $dto->created, $time = true));
		printf( '<td>%s</td>', $dto->name);
		printf( '<td class="text-center">%s</td>', $dto->party);
		printf( '<td>%s</td>', $dto->phone);
		printf( '<td>%s</td>', $dto->address);

    print '</tr>';

	}
	?></tbody>

</table>

<script>
$(document).ready( () => {
	$('#<?= $_table ?>')
	.on('update-row-numbers', function(e) {
		$('> tbody > tr:not(.d-none) >td[line-number]', this).each( ( i, e) => {
			$(e).html( i+1);

		});

	})
	.trigger('update-row-numbers');

});
</script>
