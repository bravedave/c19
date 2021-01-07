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
        <td class="d-none d-xl-table-cell text-center" title="Manual Addition">M</td>

      </tr>

    </thead>

    <tbody>
    <?php
    $parties = 0;
    $manual = 0;
    $unchecked = 0;
    while ( $dto = $this->data->registrations->dto()) {
      if ( !$dto->parent) $parties += (int)$dto->party;
      if ( config::registrations_type_manual == $dto->type) $manual++;
      $checkedOut = strtotime( $dto->checkout) > 0 ? 'yes' : 'no';
      printf(
        '<tr data-checkout="%s" data-id="%d">',
        $checkedOut,
        $dto->id

      );

      if ( 'no' == $checkedOut) $unchecked++;
      print '<td class="small text-center" line-number>&nbsp;</td>';

      if ( config::$CHECKOUT) {
        printf( '<td>%s<div class="d-sm-none small">%s</div></td>', strings::asLocalDate( $dto->created, $time = true), strings::asLocalDate( $dto->checkout, $time = true));
        printf( '<td class="d-none d-sm-table-cell">%s</td>', strings::asLocalDate( $dto->checkout, $time = true));

      }
      else {
        printf( '<td>%s</td>', strings::asLocalDate( $dto->created, $time = true));

      }
      printf( '<td>%s<div class="d-md-none small">%s</div></td>', $dto->name, $dto->phone);
      printf( '<td>%s</td>', $dto->parent ? $dto->parent_name : '&nbsp;');
      printf( '<td class="text-center">%s</td>', $dto->parent ? '&nbsp;' : $dto->party);
      printf( '<td class="d-none d-md-table-cell">%s</td>', $dto->phone);
      printf( '<td class="d-none d-lg-table-cell">%s</td>', $dto->address);
      printf( '<td class="d-none d-xl-table-cell text-center">%s</td>', config::registrations_type_manual == $dto->type ? 'M' : '&nbsp;');

      print '</tr>';

    }
    ?></tbody>

    <tfoot>
      <tr>
        <td class="text-muted font-italic" colspan="4">
          <?php if ( config::$CHECKOUT) {
            $hide = 'd-none';
            if ( time() > strtotime( $this->data->event->end)) {
              if ( $unchecked && currentUser::isAdmin()) {
                $hide = '';

              }

            } ?>
            <button class="btn btn-sm btn-light <?= $hide ?>" id="<?= $_checkoutBtn = strings::rand()  ?>">checkout <?= $unchecked ?></button>

          <?php } // if ( config::$CHECKOUT) { ?>

        </td>
        <?php if ( config::$CHECKOUT) { ?>
        <td class="d-none d-sm-table-cell">&nbsp;</td>
        <?php } // if ( config::$CHECKOUT) { ?>
        <td class="text-center"><?= $parties ?></td>
        <td class="d-none d-md-table-cell">&nbsp;</td>
        <td class="d-none d-lg-table-cell">&nbsp;</td>
        <td class="d-none d-xl-table-cell text-center"><?= $manual ?></td>

      </tr>

      <tr>
        <td class="font-italic small" colspan="8"><?php
          if ( $manual) printf( '<div>note report contains %s manual addition/s</div>', $manual);
          printf( '<div class="text-muted">updated : %s</div>', date( config::$DATETIME_FORMAT));

        ?></td>
        <?php if ( config::$CHECKOUT) { ?>
        <td class="d-none d-sm-table-cell">&nbsp;</td>
        <?php } // if ( config::$CHECKOUT) { ?>

      </tr>

    </tfoot>

  </table>

</div>
<script>
( _ => $(document).ready( () => {
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

  <?php if ( currentUser::isAdmin()) {  ?>
    <?php if ( config::$CHECKOUT) { ?>
    $('#<?= $_checkoutBtn ?>').on( 'click', function( e) {
      e.stopPropagation();e.preventDefault();

      $('#<?= $_table ?>').trigger( 'checkout');

    });
    <?php } // if ( config::$CHECKOUT) ?>

    $('#<?= $_table ?>').on( 'checkout', function( e) {
      ids = [];
      $('> tbody > tr[data-checkout="no"]', '#<?= $_table ?>').each( ( i, tr) => {
        let _tr = $(tr);
        let _data = _tr.data();

        ids.push( _data.id);

      });

      // console.log( ids);
      $(document).trigger( 'checkout-attendees', ids.join(','));

    });

    $('> tbody > tr', '#<?= $_table ?>').each( ( i, tr) => {
      let _tr = $(tr);

      _tr
      .addClass( 'pointer')
      .on('edit', function(e) {
        let _tr = $(this);
        let _data = _tr.data();

        $(document).trigger( 'edit-attendee', _data.id);

      })
      .on( 'checkout', function( e) {
        let _me = $(this);
        let _data = _me.data();

        $(document).trigger( 'checkout-attendee', _data.id);

      })
      .on( 'click', function( e) {
        e.stopPropagation();e.preventDefault();

        $(this).trigger('edit');

      })
      .on( 'contextmenu', function( e) {
        if ( e.shiftKey)
          return;

        e.stopPropagation();e.preventDefault();

        let _me = $(this);
        let _data = _me.data();

        _.hideContexts();

        let _context = _.context();

        if ( 'no' == _data.checkout) {
          _context.append( $('<a href="#">checkout</a>').on( 'click', function( e) {
            e.stopPropagation();e.preventDefault();

            _me.trigger( 'checkout');
            _context.close();

          }));

        }

        if ( _context.length > 0) _context.open( e);

      });

    });

  <?php } // if ( currentUser::isAdmin()) ?>

}))( _brayworth_);
</script>
