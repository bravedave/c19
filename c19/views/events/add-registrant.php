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
<form id="<?= $_form = strings::rand() ?>" autocomplete="off">
  <input type="hidden" name="action" value="add-registrant">
  <input type="hidden" name="event" value="<?= $this->data->event->id ?>">

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
          <div class="form-row mb-2">
            <div class="col">
              <div class="form-control-text"><?= $this->data->event->description ?></div>

            </div>

          </div>

          <div class="form-row mb-2">
            <div class="col">
              <label class="mb-0" for="<?= $_uid = strings::rand() ?>">Name</label>
              <input type="text" class="form-control" name="name" placeholder="name" id="<?= $_uid ?>" autofocus>

            </div>

          </div>

          <div class="form-row mb-2">
            <div class="col">
              <label class="mb-0" for="<?= $_uid = strings::rand() ?>">Phone</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="bi bi-telephone"></i></div>

                </div>

                <input type="text" class="form-control" name="phone" placeholder="0418 .." id="<?= $_uid ?>">

              </div>

            </div>

          </div>

          <div class="form-row mb-2">
            <div class="col">
              <label class="mb-0" for="<?= $_uid = strings::rand() ?>">Address</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="bi bi-at"></i></div>
                </div>

                <input type="text" class="form-control" name="address" placeholder="either street or email" id="<?= $_uid ?>">

              </div>
              <div class="form-text text-muted font-italic">address can be either street address or email</div>

            </div>

          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal" tabindex="-1">close</button>
          <button type="submit" class="btn btn-primary">Save</button>

        </div>

      </div>

    </div>

  </div>
  <script>
  ( _ => $(document).ready( () => {
    $('#<?= $_modal ?>').on( 'shown.bs.modal', e => $('[autofocus]', '#<?= $_modal ?>').focus());

    $('#<?= $_form ?>')
    .on( 'submit', function( e) {
      let _form = $(this);
      let _data = _form.serializeFormJSON();

      _.post({
        url : _.url('<?= $this->route ?>'),
        data : _data,

      }).then( d => {
        if ( 'ack' == d.response) {
          $('#<?= $_modal ?>').trigger( 'success');

        }
        else {
          _.growl( d);

        }

        $('#<?= $_modal ?>').modal( 'hide');

      });

      return false;

    });

  }))( _brayworth_);
  </script>

</form>
