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
      <label for="<?= $_uid = strings::rand() ?>">Name</label>
      <input type="text" class="form-control" name="name" placeholder="name" required pattern="[a-zA-Z0-9].*" id="<?= $_uid ?>">

    </div>

  </div>

  <div class="form-group row">
    <div class="offset-lg-4 col-lg-4">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text"><i class="fa fa-phone"></i></div>
        </div>

        <input type="tel" class="form-control" name="phone" placeholder="0417 000 000" required pattern="[0-9].*">

      </div>

    </div>

  </div>

  <div class="form-group row">
    <div class="offset-lg-4 col-lg-4">
      <label for="<?= $_uid = strings::rand() ?>">Address</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text"><i class="fa fa-at"></i></div>
        </div>

        <input type="text" class="form-control" name="address" placeholder="@" required pattern="[a-zA-Z0-9].*" id="<?= $_uid ?>">

      </div>

      <div class="form-text text-muted font-italic">address can be either street address or email</div>

    </div>

  </div>

  <div class="form-group row">
    <div class="offset-lg-4 col-lg-4">
      <div class="row">
        <label class="col col-md-5 col-lg-7" for="<?php $_uid = strings::rand() ?>">Is this a family group ?</label>
        <div class="col-5 col-md-7 col-lg-5">
          <div class="form-check form-check-inline">
            <input type="radio" class="form-check-input" name="family-group" value="no" checked>
            <label class="form-check-label">no</label>
          </div>

          <div class="form-check form-check-inline">
            <input type="radio" class="form-check-input" name="family-group" value="yes">
            <label class="form-check-label">yes</label>
          </div>

        </div>

      </div>

    </div>

  </div>

  <input type="hidden" name="party" value="1" id="<?= $_party = strings::rand() ?>">

  <div class="row">
    <div class="offset-lg-4 col-lg-4">
      <label class="d-none" id="<?= $_party ?>label">Your Family</label>
      <div id="<?= $_party ?>family"></div>

      <div class="row">
        <div class="col text-right">
          <button type="button" class="btn btn-light" id="<?= $_party ?>btn"><i class="fa fa-plus"></i></button>

        </div>

      </div>

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

  <div class="form-group row">
    <div class="offset-lg-4 col-lg-4 text-muted small">
      <h6>Privacy Polciy</h6>

      <p>Data is kept in accordance with Government Requirements, namely
        <ul>
          <li>for 28 days, after which it is automatically deleted</li>

        </ul>

      </p>

      <p>This site does not use cookies, if you elect to <em>remember me</em>,
        the browsers localStorage is used to populate the form.
        The webserver has logs which include your ip address
        and the time of access</p>

      <p>The data is only used for the legal
        requirements in these difficult times.</p>

    </div>

  </div>

</form>
<script>
$(document).ready( () => {
  $('input[name="family-group"]', '#<?= $_form ?>').on( 'change', function( e) {
    let _me = $(this);
    // console.log( _me.val());
    if ( 'yes' == _me.val()) {
      $('#<?= $_party ?>').val(2).trigger( 'change');

    }
    else {
      $('#<?= $_party ?>').val(1).trigger( 'change');

    }

  });

  $('#<?= $_party ?>').on( 'change', function( e) {
    let i = Number( $('#<?= $_party ?>').val());

    if ( i < 1) return; // that's invalid anyway

    let familyContainer = $('#<?= $_party ?>family');

    let _kids = $('.row', familyContainer);
    let kids = _kids.length;
    // console.log('have I got too many ?', kids, i-1);
    if ( kids > i-1) {
      for (let index = i-1; index < kids; index++) {
        $(_kids[index]).remove();

      }

    }

    // console.log('have I got enough ?', kids, i-1);
    while ( kids < i-1) {
      let row = $('<div class="form-group row"></div>').appendTo(familyContainer);
      let col = $('<div class="col"></div>').appendTo( row);
      let ig = $('<div class="input-group"></div>').appendTo( col);
      let del = $('<button type="button" class="btn btn-light input-group-text"><i class="fa fa-minus text-danger"></i></button>')
      ig.append( '<div class="input-group-prepend"><div class="input-group-text">' + ++kids + '</div></div>')
      ig.append( '<input type="text" class="form-control" name="family[]" placeholder="full name" required pattern="[a-zA-Z0-9].*" />');
      ig.append( $('<div class="input-group-append"></div>').append(del))

      del.on( 'click', function( e) {
        e.stopPropagation();e.preventDefault();
        $(this).closest('.row').remove();

        let i = Number( $('#<?= $_party ?>').val());
        $('#<?= $_party ?>').val(i-1);
        $('#<?= $_party ?>').trigger('change');

      })

    }

    if ( 1 == i) {
      $('#<?= $_party ?>label').addClass('d-none');
      $('#<?= $_party ?>btn').addClass('d-none');
    }
    else {
      $('#<?= $_party ?>label').removeClass('d-none');
      $('#<?= $_party ?>btn').removeClass('d-none');
    }

  });

  // $('#<?= $_party ?>').trigger('change');
  $('input[name="family-group"]:checked', '#<?= $_form ?>').trigger( 'change');

  $('#<?= $_party ?>btn').on( 'click', function( e) {
    e.stopPropagation();e.preventDefault();

    let i = Number( $('#<?= $_party ?>').val());
    $('#<?= $_party ?>').val(i+1);
    $('#<?= $_party ?>').trigger('change');

  });

  $('#<?= $_form ?>')
  .on( 'submit', function( e) {
    let _form = $(this);
    let _data = _form.serializeFormJSON();

    if ( '1' == String( _data.remember)) {
      let me = {
        'name' : _data.name,
        'party' : _data.party,
        'phone' : _data.phone,
        'address' : _data.address,

      };

      if (!!_data['family[]']) {
        me.family = _data['family[]'];

      }

      localStorage.setItem('c19', JSON.stringify(me));
      // console.log( '----------[me]------------');
      // console.log( JSON.stringify(me));
      // console.log( me);
      // console.log( _data);
      // console.log( '----------[me]------------');

    }
    else {
      localStorage.removeItem('c19');

    }

    // console.log( _data);
    // return false;

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

  ( _ => {
    let me = localStorage.getItem('c19');
    if ( !!me) {
      let _me = JSON.parse( me);

      $('input[name="name"]', '#<?= $_form ?>').val( _me.name);
      $('input[name="party"]', '#<?= $_form ?>').val( _me.party);
      $('input[name="phone"]', '#<?= $_form ?>').val( _me.phone);
      $('input[name="address"]', '#<?= $_form ?>').val( _me.address);
      $('input[name="remember"]', '#<?= $_form ?>').prop( 'checked', true);

      $('input[name="party"]', '#<?= $_form ?>').trigger('change');
      if ( Number( _me.party) > 1) {
        $('input[name="family-group"][value="yes"]', '#<?= $_form ?>')
        .prop( 'checked', true)
        .trigger( 'change');

      }

      if ( !!_me.family) {
        $('input[name="family\[\]"]').each( (i, el) => {
          if ( !!_me.family[i]) {
            $(el).val( _me.family[i]);

          }

        });

      }

    }

  }) (_brayworth_);

});
</script>
