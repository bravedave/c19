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

class registrations extends _dao {
	protected $_db_name = 'registrations';
	protected $template = __NAMESPACE__ . '\dto\registrations';

  public function getByUID( $uid) {
    $sql = sprintf(
      'SELECT * FROM `registrations` WHERE `uid` = "%s"',
      $this->escape( $uid)

    );

    if ( $res = $this->Result($sql)) {
      return $res->dto();

    }

    return false;

  }

  public function purge( $ttl) {
    $dead_date = date( 'Y-m-d H:i:s', time() - $ttl);
    $sql = sprintf(
      'DELETE FROM `%s` WHERE `created` IS NULL OR `created` <= "%s"',
      $this->_db_name, $dead_date

    );

    // \sys::logSQL( sprintf('<%s> %s', $sql, __METHOD__));

    $this->Q( $sql);

  }

}
