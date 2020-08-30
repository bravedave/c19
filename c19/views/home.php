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

use strings;    ?>

<div class="d-md-none py-5">
    <img class="img-fluid d-block m-auto" src="<?= strings::url( $this->route . '/image/attendance' ) ?>" />
</div>
<div class="d-none d-md-block h-100" style="background: url('<?= strings::url( $this->route . '/image/attendance' ) ?>') no-repeat center;"></div>
