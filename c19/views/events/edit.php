<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\beds_list;

use strings;

$dto = $this->data->dto;    ?>

<div id="<?= $_wrap = strings::rand() ?>">
    <form id="<?= $_form = strings::rand() ?>" autocomplete="off">
        <input type="hidden" name="action" value="save-events">
        <input type="hidden" name="id" value="<?= $dto->id ?>">

        <div class="modal fade" tabindex="-1" role="dialog" id="<?= $_modal = strings::rand() ?>"
            aria-labelledby="<?= $_modal ?>Label" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
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
                            <label class="my-0" for="<?= $uid = strings::rand() ?>">Description</label>
                            <input class="form-control" type="text" name="description" required
                                value="<?= $dto->description ?>"
                                id="<?= $uid ?>" />

                          </div>

                        </div>

                        <div class="form-group row">
                          <div class="col">
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="open" value="1"
                                <?php if($dto->open) print 'checked'; ?>
                                id="<?= $uid = strings::rand() ?>" />
                              <label class="form-check-label" for="<?= $uid ?>">Open Event</label>

                            </div>

                          </div>

                        </div>

                        <div class="form-group row">
                          <div class="col-md-6">
                            <label for="<?= $uid = strings::rand() ?>" class="my-0">Start</label>
                            <input class="form-control" type="datetime-local" name="start" required
                                value="<?php
                                  if ( ( $_t = strtotime( $dto->start)) > 0) {
                                    print date( 'Y-m-d\TH:i', $_t);

                                  } ?>"
                                id="<?= $uid ?>" />

                          </div>

                          <div class="col-md-6 mt-3 mt-md-0">
                            <label for="<?= $uid = strings::rand() ?>" class="my-0">End</label>
                            <input class="form-control" type="datetime-local" name="end" required
                                value="<?php
                                  if ( ( $_t = strtotime( $dto->end)) > 0) {
                                    print date( 'Y-m-d\TH:i', $_t);

                                  } ?>"
                                id="<?= $uid ?>" />

                          </div>

                        </div>

                    </div>

                    <div class="modal-footer py-1">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-outline-danger d-none" id="<?= $deleteBtn = strings::rand() ?>">delete</button>
                        <button type="submit" class="btn btn-primary">save</button>

                    </div>

                </div>

            </div>

        </div>

    </form>

    <script>
    ( _ => {
        $('#<?= $_modal ?>').on( 'shown.bs.modal', e => { $('#<?= $_wrap ?> input[name="description"]').focus(); });

        $('input[name="open"]', '#<?= $_form ?>').on( 'change', function( e) {
          let _me = $(this);
          if ( _me.prop('checked')) {
            $('input[name="start"]', '#<?= $_form ?>').prop( 'required', false);
            $('input[name="end"]', '#<?= $_form ?>').prop( 'required', false);

            $('input[name="start"]', '#<?= $_form ?>').closest( '.row').addClass( 'd-none');

          }
          else {
            $('input[name="start"]', '#<?= $_form ?>').prop( 'required', true);
            $('input[name="end"]', '#<?= $_form ?>').prop( 'required', true);

            $('input[name="start"]', '#<?= $_form ?>').closest( '.row').removeClass( 'd-none');

          }

        });

        $('input[name="start"], input[name="end"]', '#<?= $_form ?>').on( 'change', function( e) {
          e.target.value = e.target.value.substr( 0, 16);

        });

        $('input[name="open"]', '#<?= $_form ?>').trigger( 'change');

        $('#<?= $_form ?>').on( 'submit', function( e) {
            let _form = $(this);
            let _data = _form.serializeFormJSON();

            _.post({
                url : _.url('<?= $this->route ?>'),
                data : _data,

            }).then( (d) => {
                if ( 'ack' == d.response) {
                    $('#<?= $_modal ?>').trigger('success');

                }
                else {
                    _.growl( d);

                }

                $('#<?= $_modal ?>').modal('hide');

            });

            return false;

        });

    }) (_brayworth_);
    </script>

</div>
