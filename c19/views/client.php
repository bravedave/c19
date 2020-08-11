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

use strings; ?>

<form id="<?= $_form = strings::rand() ?>">
  <input type="hidden" name="action" value="register">
  <?php if ( count( $this->data->events) > 1) { ?>
    <div class="form-group row">
      <div class="offset-lg-4 col-lg-4">
        <select class="form-control" name="event" required>
          <option value="">select event</option>
        <?php
        foreach ($this->data->events as $event) {
          printf( '<option value="%s">%s</option>', $event->id, $event->description);

        }
        ?>
        </select>

      </div>

    </div>

  <?php } else {
    $event = $this->data->events[0];  ?>
    <input type="hidden" name="event" value="<?= $event->id ?>">
    <div class="form-group row">
      <div class="offset-lg-4 col-lg-4">
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text">event</div>
          </div>

          <input type="text" class="form-control" value="<?= $event->description ?>" readonly>

        </div>

      </div>

    </div>

  <?php } ?>

  <div class="form-group row">
    <div class="offset-lg-4 col-lg-4">
      <label for="<?php $_uid = strings::rand() ?>">Name</label>
      <input type="text" class="form-control" name="name" placeholder="name" required
        id="<?= $_uid ?>">

    </div>

  </div>

  <div class="form-group row">
    <div class="offset-lg-4 col-lg-4">
      <label for="<?php $_uid = strings::rand() ?>">How many in your group</label>
      <input type="number" class="form-control" name="party" placeholder="name" value="1" required
        id="<?= $_uid ?>">

    </div>

  </div>

  <div class="form-group row">
    <div class="offset-lg-4 col-lg-4">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text"><i class="fa fa-phone"></i></div>
        </div>

        <input type="tel" class="form-control" name="phone" placeholder="0417 000 000" required>

      </div>

    </div>

  </div>

  <div class="form-group row">
    <div class="offset-lg-4 col-lg-4">
      <label form="<?= $_uid = strings::rand() ?>">Address</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text"><i class="fa fa-at"></i></div>
        </div>

        <input type="text" class="form-control" name="address" placeholder="@" required
          id="<?= $_uid ?>">

      </div>

      <div class="form-text text-muted">address can be either street address or email</div>

    </div>

  </div>

  <div class="form-group row">
    <div class="offset-lg-4 col-lg-4">
      <div class="form-check">
        <input type="checkbox" class="form-check-input" name="remember" value="1" id="<?= $_uid = strings::rand()  ?>">
        <label class="form-check-label" for="<?= $_uid ?>">Remember Me</label>

      </div>

    </div>

  </div>

  <div class="form-group row">
    <div class="offset-lg-4 col-lg-4 text-right">
      <button class="btn btn-primary" style="background-color: #3F3F95" type="submit">Check In</button>

    </div>

  </div>

</form>
<script>
$(document).ready( () => {
  ( _ => {
    let me = localStorage.getItem('c19');
    if ( !!me) {
      let _me = JSON.parse( me);

      $('input[name="name"]', '#<?= $_form ?>').val( _me.name);
      $('input[name="party"]', '#<?= $_form ?>').val( _me.party);
      $('input[name="phone"]', '#<?= $_form ?>').val( _me.phone);
      $('input[name="address"]', '#<?= $_form ?>').val( _me.address);
      $('input[name="remember"]', '#<?= $_form ?>').prop( 'checked', true);

    }

  }) (_brayworth_);

  $('#<?= $_form ?>')
  .on( 'submit', function( e) {
    let _form = $(this);
    let _data = _form.serializeFormJSON();

    if ( '1' == String( _data.remember)) {
      let me = {
        'name' : _data.name,
        'party' : _data.party,
        'phone' : _data.phone,
        'address' : _data.address

      };

      localStorage.setItem('c19', JSON.stringify(me));

    }
    else {
      localStorage.removeItem('c19');

    }

    ( _ => {
      _.post({
        url : _.url('<?= $this->route ?>'),
        data : _data,

      }).then( d => {
        if ( 'ack' == d.response) {
          let p = _form.parent();
          p.load( _.url('<?= $this->route ?>/thanks'));

        }
        else {
          _.growl( d);

        }

      });

    }) (_brayworth_);

    return false;

  });

});
</script>
