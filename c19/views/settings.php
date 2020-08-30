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

use strings;  ?>

<div id="<?= $_wrap = strings::rand() ?>">
  <form id="<?= $_form = strings::rand() ?>" autocomplete="off">
    <input type="hidden" name="action" value="save-settings">
    <div class="modal fade" tabindex="-1" role="dialog" id="<?= $_modal = strings::rand() ?>" aria-labelledby="<?= $_modal ?>Label" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header bg-secondary text-white py-2">
            <h5 class="modal-title" id="<?= $_modal ?>Label">Settings</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <div class="form-group row">
              <div class="col">
                <label for="<?= $_uid = strings::rand() ?>">Registration TTL</label>
                <div class="input-group">
                  <input type="text" name="registration_ttl" class="form-control" required
                    id="<?= $_uid ?>"
                    value="<?= config::c19_registration_ttl() ?>">

                  <div class="input-group-append">
                    <div class="input-group-text" id="<?= $_ttl_calc = strings::rand() ?>"></div>
                  </div>

                </div>

                <div class="form-text text-muted">
                  The <strong>Registration TTL</strong> is the number of seconds before
                  Registrants are automatically purged (i.e. days * 24 * 60 * 60, 1 day = 86400)
                </div>

              </div>

            </div>

            <div class="row">
              <div class="col">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" name="checkout" value="1"
                    <?php if ( config::$CHECKOUT) print 'checked'; ?>
                    id="<?= $uid = strings::rand() ?>">

                  <label class="form-check-label" for="<?= $uid ?>">
                    Enable Checkout

                  </label>

                </div>

                <div class="form-text text-muted">
                  <strong>Checkout</strong> will succeed if
                  <ul>
                    <li>The browser is <strong>NOT</strong> in private
                      <sup><a href="https://support.apple.com/en-us/HT203036" target="_blank" rel="noreferrer"><i class="fa fa-external-link"></i></a></sup>
                      mode</li>
                    <li><strong>AND</strong> the user returns to the app on the <strong>same date</strong></li>

                  </ul>

                </div>

              </div>

            </div>

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
    $('input[name="registration_ttl"]').on( 'change', function(e) {
      let _me = $(this);
      let v = Number( _me.val());

      $('#<?= $_ttl_calc ?>').html( (Math.round(v / 24 / 60 / 60 * 100) / 100) + ' days');

    })
    .trigger( 'change');

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
            $('#<?= $_modal ?>')
            .trigger( 'success')
            .modal('hide');

          }
          else {
            _.growl( d);

          }

        });

      }) (_brayworth_);


      // console.table( _data);

      return false;
    });
  });
  </script>
</div>
