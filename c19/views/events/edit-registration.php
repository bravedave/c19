<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
 * styleguide : https://codeguide.co/
*/

namespace c19;

use strings;

$dto = $this->data->dto;  ?>

<div id="<?= $_wrap = strings::rand() ?>">
  <form id="<?= $_form = strings::rand() ?>" autocomplete="off">
    <input type="hidden" name="action" value="update-registration">
    <input type="hidden" name="id" value="<?= $dto->id ?>">

    <div class="modal fade" tabindex="-1" role="dialog" id="<?= $_modal = strings::rand() ?>" aria-labelledby="<?= $_modal ?>Label" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header bg-secondary text-white py-2">
            <h5 class="modal-title" id="<?= $_modal ?>Label"><?= $this->title ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>

          </div>

          <div class="modal-body">
            <div class="form-group row">
              <div class="col">
                <input type="text" class="form-control" name="name" value="<?= $dto->name ?>">

              </div>

            </div>

            <?php if ( !( strtotime( $dto->checkout) > 0)) {  ?>
              <div class="form-group row">
                <div class="col">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="checkout" value="yes"
                      id="<?= $uid = strings::rand() ?>">

                    <label class="form-check-label" for="<?= $uid ?>">
                      Checkout

                    </label>

                  </div>

                </div>

              </div>

            <?php } // if ( strtotime( $dto->checkout))  ?>

          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save</button>

          </div>

        </div>

      </div>

    </div>

  </form>
  <script>
  $(document).ready( () => {

    $('#<?= $_modal ?>').on( 'hidden.bs.modal', e => { $('#<?= $_wrap ?>').remove(); });
    $('#<?= $_modal ?>').modal( 'show');

    $('#<?= $_form ?>')
    .on( 'submit', function( e) {
      let _form = $(this);
      let _data = _form.serializeFormJSON();

      ( _ => {
        _.post({
          url : _.url('<?= $this->route ?>'),
          data : _data,

        }).then( d => {
          if ( 'ack' == d.response) {
            $('#<?= $_modal ?>').trigger( 'success');
            $('#<?= $_modal ?>').modal( 'hide');

          }
          else {
            let alert = $('<div class="alert alert-danger mr-auto py-2"></div>').html( d.description);
            $('.modal-footer', '#<?= $_modal ?>').prepend( alert);

          }

        });

      }) (_brayworth_);

      // console.table( _data);

      return false;
    });
  });
  </script>
</div>

