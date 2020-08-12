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

<table class="table table-sm" id="<?= $_table = strings::rand() ?>">
	<thead class="small">
		<tr>
			<td class="text-center" line-number>#</td>
			<td>Time</td>
			<td>Name</td>
			<td>Parent</td>
			<td class="text-center">Family</td>
			<td>Mobile</td>
			<td>Address</td>

		</tr>

	</thead>

	<tbody>
	<?php
  $parties = 0;
	while ( $dto = $this->data->registrations->dto()) {
    $parties += (int)$dto->party;
		printf( '<tr data-id="%d">', $dto->id);

		print '<td class="small text-center" line-number>&nbsp;</td>';

		printf( '<td>%s</td>', strings::asLocalDate( $dto->created, $time = true));
		printf( '<td>%s</td>', $dto->name);
		printf( '<td>%s</td>', $dto->parent ? $dto->parent_name : '&nbsp;');
		printf( '<td class="text-center">%s</td>', $dto->parent ? '&nbsp;' : $dto->party);
		printf( '<td>%s</td>', $dto->phone);
		printf( '<td>%s</td>', $dto->address);

    print '</tr>';

	}
	?></tbody>

  <tfoot>
    <tr>
      <td class="text-muted font-italic small" colspan="4"><?= sprintf( 'updated : %s', date( 'c')) ?></td>
      <td class="text-center"><?= $parties ?></td>
      <td colspan="2">&nbsp;</td>

    </tr>

  </tfoot>

</table>

<script>
$(document).ready( () => {
	$('#<?= $_table ?>')
	.on('update-row-numbers', function(e) {
    let tot = 0;
		$('> tbody > tr:not(.d-none) >td[line-number]', this).each( ( i, e) => {
			$(e).html( i+1);
      tot ++;

		});

		$('> thead > tr >td[line-number]', this).html( tot);

	})
	.trigger('update-row-numbers');

  $('> tbody > tr', '#<?= $_table ?>').each( ( i, tr) => {
    let _tr = $(tr);

    _tr
    .on('edit', function(e) {
      let _tr = $(this);
      let _data = _tr.data();

      $(document).trigger( 'edit-attendee', _data.id);

    })
    .on( 'click', function( e) {
      e.stopPropagation();e.preventDefault();

      $(this).trigger('edit');

    });

  });

});
</script>
