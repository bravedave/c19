<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace fat;

use strings;    ?>

<div id="<?= $_wrap = strings::rand() ?>">
  <form id="<?= $_form = strings::rand() ?>" autocomplete="off">
    <input type="hidden" name="guid" value="<?= $this->data->guid ?>" />
    <input type="hidden" name="action" value="reset password" />
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
            <label for="<?= $_uid = strings::rand()  ?>">New Password</label>

            <input class="form-control" type="password"
              name="password" placeholder="new-password"
              autocomplete="new-password" />

          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Reset Password</button>

          </div>

        </div>

      </div>

    </div>

  </form>
  <script>
  $(document).ready( () => {
    ( _ => {
      $('#<?= $_modal ?>').on( 'hidden.bs.modal', e => { window.location.href = _.url(); });
      $('#<?= $_modal ?>').modal( 'show');

      $('#<?= $_form ?>')
      .on( 'submit', function( e) {
        let _form = $(this);
        let _data = _form.serializeFormJSON();
        let _modalBody = $('.modal-body', _form);

        // console.table( _data);
        _.post({
            url : _.url('<?= $this->route ?>'),
            data : _data,

        }).then( d => {
            if ( 'ack' == d.response) {
                $('#<?= $_modal ?>').modal( 'hide');

            }
            else {
                $('<div class="alert alert-danger mt-1"></div>').html(d.description).appendTo( _modalBody);

            }

        });

        return false;

      });

    }) (_brayworth_);

  });
  </script>

</div>
