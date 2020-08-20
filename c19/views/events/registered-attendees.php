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

<div class="table-responsive">
  <table class="table table-sm" id="<?= $_table = strings::rand() ?>">
    <thead class="small">
      <tr>
        <td class="text-center" line-number>#</td>
        <td>Time</td>
        <?php if ( config::$CHECKOUT) { ?>
          <td class="d-none d-sm-table-cell">Out</td>
        <?php } // if ( config::$CHECKOUT) { ?>
        <td>Name</td>
        <td>Parent</td>
        <td class="text-center">Family</td>
        <td class="d-none d-md-table-cell">Mobile</td>
        <td class="d-none d-lg-table-cell">Address</td>

      </tr>

    </thead>

    <tbody>
    <?php
    $parties = 0;
    while ( $dto = $this->data->registrations->dto()) {
      if ( !$dto->parent) $parties += (int)$dto->party;
      printf( '<tr data-id="%d">', $dto->id);

      print '<td class="small text-center" line-number>&nbsp;</td>';

      if ( config::$CHECKOUT) {
        printf( '<td>%s<div class="d-sm-none small">%s</div></td>', strings::asShortDate( $dto->created, $time = true), strings::asShortDate( $dto->checkout, $time = true));
        printf( '<td class="d-none d-sm-table-cell">%s</td>', strings::asShortDate( $dto->checkout, $time = true));

      }
      else {
        printf( '<td>%s</td>', strings::asShortDate( $dto->created, $time = true));

      }
      printf( '<td>%s<div class="d-md-none small">%s</div></td>', $dto->name, $dto->phone);
      printf( '<td>%s</td>', $dto->parent ? $dto->parent_name : '&nbsp;');
      printf( '<td class="text-center">%s</td>', $dto->parent ? '&nbsp;' : $dto->party);
      printf( '<td class="d-none d-md-table-cell">%s</td>', $dto->phone);
      printf( '<td class="d-none d-lg-table-cell">%s</td>', $dto->address);

      print '</tr>';

    }
    ?></tbody>

    <tfoot>
      <tr>
        <td class="text-muted font-italic small" colspan="4"><?= sprintf( 'updated : %s', date( 'c')) ?></td>
        <?php if ( config::$CHECKOUT) { ?>
        <td class="d-none d-sm-table-cell">&nbsp;</td>
        <?php } // if ( config::$CHECKOUT) { ?>
        <td class="text-center"><?= $parties ?></td>
        <td class="d-none d-md-table-cell">&nbsp;</td>
        <td class="d-none d-lg-table-cell">&nbsp;</td>

      </tr>

    </tfoot>

  </table>

</div>

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
