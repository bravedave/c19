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
    $sql = 'SELECT
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
      ORDER BY `id` DESC';

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
    // \sys::logSQL( $sql);

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

  public function otherPossibleEvents( dto\registrations $dto) : array {
    $sql = sprintf(
      'SELECT * FROM `events` WHERE `open` = 1 OR "%s" BETWEEN `start` AND `end`',
      $dto->created

    );
    // \sys::logger( sprintf('<%s> %s', $sql, __METHOD__));

    if ( $res = $this->Result( $sql)) {
      return $this->dtoSet( $res);

    }

    return [];

  }

  public function purge( $ttl) {
    $dead_date = date( 'Y-m-d H:i:s', time() - $ttl);
    $sql = sprintf( 'SELECT
        `events`.`id`,
        `events`.`created`,
        `events`.`end`,
        regs.tot
      FROM `events`
        LEFT JOIN
        (SELECT
          `event`,
          count(*) tot
        FROM `registrations`
        GROUP BY `event`) regs
          ON regs.`event` = `events`.`id`
      WHERE
        `events`.open = 0
        AND
        (`created` IS NULL OR `created` <= "%s")
        AND
        (`end` <= "%s")
        AND
        ( regs.tot IS NULL OR regs.tot = 0)',
      $dead_date,
      $dead_date

    );

    // \sys::logSQL( sprintf('<%s> %s', $sql, __METHOD__));
    if ( $res = $this->Result( $sql)) {
      $ids = [];
      while ( $dto = $res->dto()) {
        $ids[] = $dto->id;

      }

      if ( $ids) {
        $sql = sprintf( 'DELETE FROM `events` WHERE id IN (%s)', implode( ',', $ids));
        // \sys::logSQL( sprintf('<%s> %s', $sql, __METHOD__));
        $this->Q( $sql);

      }

    }

  }

}
