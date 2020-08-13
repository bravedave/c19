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

use application;
use config;
use dvc\service;

class postUpdate extends service {
    protected function _upgrade() {
        umask( 000);
        config::route_register( 'users', 'green\\users\\controller');

        \green\users\config::green_users_checkdatabase();

        echo( sprintf('%s : %s%s', 'green updated', __METHOD__, PHP_EOL));

        config::route_register( 'admin', 'c19\\controller');
        config::route_register( 'events', 'c19\\events');
        config::route_register( 'recover', 'c19\\recover');

    }

    static function upgrade() {
        $app = new self( application::startDir());
        $app->_upgrade();

    }

}
