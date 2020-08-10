<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace c19\dao;

use dao\_dao;

class events extends _dao {
	protected $_db_name = 'events';
	protected $template = '\c19\dao\dto\events';

  public function getOpenEvents() {
    $sql = sprintf( 'SELECT
        *
      FROM
        `events`
      WHERE
        `open` = 1
        OR (`start` <= "%s" AND `end` >= "%s")',
      date( "Y-m-d H:i:s", time()),
      date( "Y-m-d H:i:s", time())

    );

    return $this->Result( $sql);

  }

  public function getRegistrationsForEvent( $id) {
    $sql = sprintf( 'SELECT
        *
      FROM
        `registrations`
      WHERE
        `event` = %d',
      $id);

    return $this->Result( $sql);

  }

}
