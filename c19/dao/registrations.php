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

  public function checkOutParty( dto\registrations $dto) {
    $this->Q(
      sprintf(
        'UPDATE `%s` SET `checkout` = "%s" WHERE `id` = %d OR `parent` = %d',
        $this->_db_name,
        \db::dbTimeStamp(),
        $dto->id,
        $dto->id

      )

    );

  }

  public function getByUID( $uid) {
    $sql = sprintf(
      'SELECT * FROM `registrations` WHERE `uid` = "%s"',
      $this->escape( $uid)

    );

    // \sys::logger( sprintf('<%s> %s', $sql, __METHOD__));


    if ( $res = $this->Result($sql)) {
      return $res->dto( $this->template);

    }

    return false;

  }

  public function getPartyByUID( $uid) : array {
    // \sys::logger( sprintf('<%s> %s', $uid, __METHOD__));

    if ( $dto = $this->getByUID( $uid)) {
      $sql = sprintf(
        'SELECT * FROM `%s` WHERE `id` = %d OR `parent` = %d',
        $this->_db_name,
        $dto->id,
        $dto->id

      );
      // \sys::logger( sprintf('<%s> %s', $sql, __METHOD__));

      if ( $res = $this->Result( $sql)) {

        return $this->dtoSet( $res);

      }

      return [ $dto];

    }

    return [];

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
