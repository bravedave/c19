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

<nav class="nav flex-column">
    <div class="nav-item">
        <a class="nav-link" href="<?= strings::url( 'events') ?>">events</a>

    </div>

    <div class="nav-item">
        <a class="nav-link" href="<?= strings::url( 'users') ?>">users</a>

    </div>

    <div class="nav-item">
        <a class="nav-link" href="<?= strings::url( 'logout') ?>">logoff</a>

    </div>

</nav>
