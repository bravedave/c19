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
      ORDER BY `id` DESC',
      \db::dbTimeStamp(),
      \db::dbTimeStamp()

    );

    // \sys::logSQL( $sql);

    return $this->Result( $sql);

  }

  public function getOpenEvents() {
    $sql = sprintf( 'SELECT * FROM `events`
      WHERE `open` = 1
        OR (`start` <= "%s" AND `end` >= "%s")
      ORDER BY `description`',
      \db::dbTimeStamp(),
      \db::dbTimeStamp()

    );
    \sys::logSQL( $sql);

    return $this->Result( $sql);

  }

  public function getRegistrationsForEvent( $id) {
    $sql = sprintf( 'SELECT
        r.*,
        p.name `parent_name`
      FROM
        `registrations` r
        LEFT JOIN `registrations` p ON p.`id` = r.`parent`
      WHERE
        r.`event` = %d
      ORDER BY
        r.`created` DESC',
      $id);

    return $this->Result( $sql);

  }

}
