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
<table class="table" id="<?= $_table = strings::rand() ?>">
	<thead class="small">
		<tr>
			<td class="text-center">#</td>
			<td>Description</td>
			<td>Duration</td>
			<td class="text-center">Attendees</td>

		</tr>

	</thead>

	<tbody>
	<?php
  $iEvents = 0;
	while ( $dto = $this->data->dataset->dto()) {
    $iEvents++;
    printf( '<tr data-id="%d" data-tot="%d">',
      $dto->id,
      $dto->tot

    );

		print '<td class="small text-center" line-number>&nbsp;</td>';

		printf( '<td>%s</td>', $dto->description);
		if ( $dto->open) {
      printf( '<td>%s</td>', 'open event');

    }
    else {
      printf( '<td>%s - %s</td>',
        strings::asLocalDate( $dto->start, $time = true),
        strings::asLocalDate( $dto->end, $time = true));

    }
		printf( '<td class="text-center">%s</td>', $dto->tot);

    print '</tr>';

	}
	?></tbody>

  <?php if ( currentUser::isAdmin()) {  ?>
    <tfoot class="d-print-none">
      <tr>
        <td colspan="4" class="text-right">
          <button type="button" class="btn btn-outline-secondary" id="<?= $addBtn = strings::rand() ?>"><i class="fa fa-plus"></i></a>

        </td>

      </tr>

    </tfoot>

  <?php }
  elseif ( !$iEvents) { ?>
    <tfoot class="d-print-none">
      <tr>
        <td colspan="4">
          <div class="alert alert-warning">no events found</div>

        </td>

      </tr>

    </tfoot>

  <?php } ?>

</table>

<script>
( _ => {
  <?php if ( currentUser::isAdmin()) {  ?>
  $(document).on( 'add-events', e => {
    _.get.modal( _.url('<?= $this->route ?>/edit'))
    .then( m => m.on( 'success', e => window.location.reload()));

  });
  <?php } // if ( currentUser::isAdmin()) ?>

  $(document).ready( () => {
    $('#<?= $_table ?>')
    .on('update-row-numbers', function(e) {
      $('> tbody > tr:not(.d-none) >td[line-number]', this).each( ( i, e) => {
        $(e).html( i+1);

      });

    })
    .trigger('update-row-numbers');

    <?php if ( currentUser::isAdmin()) {  ?>
    $('#<?= $addBtn ?>').on( 'click', e => { $(document).trigger( 'add-events'); });
    <?php } // if ( currentUser::isAdmin()) ?>

    $('#<?= $_table ?> > tbody > tr').each( ( i, tr) => {

      $(tr)
      .addClass( 'pointer' )
      .on( 'view-registrations', function(e) {
        let _tr = $(this);
        let _data = _tr.data();

        _brayworth_.nav( '<?= $this->route ?>/registrations/' + _data.id);

      })
      .on( 'click', function(e) {
        e.stopPropagation(); e.preventDefault();

        $(this).trigger( 'view-registrations');

      });

      <?php if ( currentUser::isAdmin()) {  ?>
        $(tr)
        .on( 'delete', function( e) {
          let _tr = $(this);

          _brayworth_.ask({
            headClass: 'text-white bg-danger',
            text: 'Are you sure ?',
            title: 'Confirm Delete',
            buttons : {
              yes : function(e) {
                $(this).modal('hide');
                _tr.trigger( 'delete-confirmed');

              }

            }

          });

        })
        .on( 'delete-confirmed', function(e) {
          let _tr = $(this);
          let _data = _tr.data();

          ( _ => {
            _.post({
              url : _.url('<?= $this->route ?>'),
              data : {
                action : 'delete',
                id : _data.id

              },

            }).then( d => {
              if ( 'ack' == d.response) {
                _tr.remove();
                $('#<?= $_table ?>').trigger('update-line-numbers');

              }
              else {
                _.growl( d);

              }

            });

          }) (_brayworth_);

        })
        .on( 'edit', function(e) {
          let _tr = $(this);
          let _data = _tr.data();

          ( _ => {
            _.get.modal( _.url('<?= $this->route ?>/edit/' + _data.id))
            .then( m => m.on( 'success', e => window.location.reload()));

          })( _brayworth_);

        })
        .on( 'contextmenu', function( e) {
          if ( e.shiftKey)
            return;

          e.stopPropagation();e.preventDefault();

          let _tr = $(this);
          let _data = _tr.data();

          _.hideContexts();

          let _context = _.context();

          _context.append( $('<a href="#"><b>view registrations</b></a>').on( 'click', function( e) {
            e.stopPropagation();e.preventDefault();

            _context.close();

            _tr.trigger( 'view-registrations');

          }));

          _context.append( $('<a href="#">edit</a>').on( 'click', function( e) {
            e.stopPropagation();e.preventDefault();

            _context.close();

            _tr.trigger( 'edit');

          }));

          // console.log( _data.tot);
          if ( Number( _data.tot) == 0) {
            _context.append( $('<a href="#"><i class="fa fa-trash"></i>delete</a>').on( 'click', function( e) {
              e.stopPropagation();e.preventDefault();

              _context.close();

              _tr.trigger( 'delete');

            }));

          }

          _context.open( e);

        });

      <?php } // if ( currentUser::isAdmin()) ?>

    });

  });

})( _brayworth_);
</script>
