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

  public function getOpenEventsWithAttendance() {
    $sql = sprintf( 'SELECT
        `events`.*,
        regs.tot
      FROM `events`
        LEFT JOIN
        (SELECT
          `event`,
          count(*) tot
        FROM `registrations`
        GROUP BY `event`) regs
          ON regs.`event` = `events`.`id`
      WHERE `open` = 1
        OR (`start` <= "%s" AND `end` >= "%s")',
      \db::dbTimeStamp(),
      \db::dbTimeStamp()

    );

    return $this->Result( $sql);

  }

  public function getOpenEvents() {
    $sql = sprintf( 'SELECT * FROM `events`
      WHERE `open` = 1
        OR (`start` <= "%s" AND `end` >= "%s")',
      \db::dbTimeStamp(),
      \db::dbTimeStamp()

    );

    return $this->Result( $sql);

  }

  public function getRegistrationsForEvent( $id) {
    $sql = sprintf( 'SELECT
        *
      FROM
        `registrations`
      WHERE
        `event` = %d
      ORDER BY
        `created` DESC',
      $id);

    return $this->Result( $sql);

  }

}
