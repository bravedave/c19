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

use sys;
use dvc;

class config extends dvc\config {
  const c19_db_version = 0.48;

	const allow_password_recovery = true;
  const use_inline_logon = true;

  const registrations_type_auto = 0;
  const registrations_type_manual = 1;

	static $CHECKOUT = false;
  static $CLIENT_TITLE = 'Bilinga Check In';
	static $REQUIRE_AUTHORIZATION = false;

	static $WEBNAME = 'Attendance Checking';

  static protected $_QR_FOOTER = "COVID-19\nAttendance Register\n\nScan QR Code with your smartphone camera and register your attendance at this venue\n\nScan again to checkout";
  static protected $_REGISTRATION_TTL = 2419200; // 28 * 24 * 60 * 60
  static protected $_REGISTRATION_PURGE = 0;   // the last time the client info was purged
  static protected $_C19_VERSION = 0;

	static function c19_checkdatabase() {
		if ( self::c19_version() < self::c19_db_version) {
      $dao = new dao\dbinfo;
			$dao->dump( $verbose = false);

			self::c19_version( self::c19_db_version);

    }

    if ( time() - self::c19_purged() > 3600) {
    // if ( time() - self::c19_purged() > 30) {
      $dao = new dao\registrations();
      $dao->purge( self::$_REGISTRATION_TTL);

      $dao = new dao\events();
      $dao->purge( self::$_REGISTRATION_TTL);

      self::c19_purged( time());

    }

		// sys::logger( 'bro!');

	}

	static protected function c19_config() {
		return implode( DIRECTORY_SEPARATOR, [
            rtrim( self::dataPath(), '/ '),
            'c19.json'

        ]);

	}

	static protected function c19_purged( $set = null) {
		$ret = self::$_REGISTRATION_PURGE;

		if ( (float)$set) {
			$config = self::c19_config();

			$j = file_exists( $config) ?
				json_decode( file_get_contents( $config)):
				(object)[];

			self::$_REGISTRATION_PURGE = $j->registration_purge = $set;

			file_put_contents( $config, json_encode( $j, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

		}

		return $ret;

  }

	static protected function c19_version( $set = null) {
		$ret = self::$_C19_VERSION;

		if ( (float)$set) {
			$config = self::c19_config();

			$j = file_exists( $config) ?
				json_decode( file_get_contents( $config)):
				(object)[];

			self::$_C19_VERSION = $j->c19_version = $set;

			file_put_contents( $config, json_encode( $j, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

		}

		return $ret;

  }

  static function c19_registration_ttl_days() {
    $days = self::c19_registration_ttl() / 86400;
    if ( $days == (int)$days) {
      return (int)$days;

    }
    else {
      return round( $days, 2);

    }

  }

  static function c19_registration_ttl( $set = null) {
    $ret = self::$_REGISTRATION_TTL;

		if ( (float)$set) {
			$config = self::c19_config();

			$j = file_exists( $config) ?
				json_decode( file_get_contents( $config)):
				(object)[];

			if ( !isset($j->registration_ttl) || $j->registration_ttl != $set) {
        self::$_REGISTRATION_PURGE = $j->registration_ttl = $set;
        file_put_contents( $config, json_encode( $j, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        \sys::logger( sprintf('<updated registration ttl> %s', __METHOD__));

      }

		}

    return $ret;

  }

  static function c19_checkout( $set = null) {
    $ret = self::$CHECKOUT;

    $config = self::c19_config();

    $j = file_exists( $config) ?
      json_decode( file_get_contents( $config)):
      (object)[];

    if ( !isset($j->checkout) || $j->checkout != $set) {
      self::$CHECKOUT = $j->checkout = (bool)$set;
      file_put_contents( $config, json_encode( $j, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
      \sys::logger( sprintf('<updated checkout> %s', __METHOD__));

    }

    return $ret;

  }

  static function c19_init() {
		$_a = [
			'dbCachePrefix' => self::$DB_CACHE_PREFIX,
			'c19_version' => self::$_C19_VERSION,
			'registration_ttl' => self::$_REGISTRATION_TTL,
			'registration_purge' => self::$_REGISTRATION_PURGE,
      'require_authorization' => false,
      'checkout' => self::$CHECKOUT,
      'qr_footer' => self::$_QR_FOOTER

		];

		if ( file_exists( $config = self::c19_config())) {
			$a = (object)array_merge( $_a, (array)json_decode( file_get_contents( $config)));

			self::$DB_CACHE_PREFIX = (bool)$a->dbCachePrefix;
			self::$_C19_VERSION = $a->c19_version;
			self::$_REGISTRATION_TTL = $a->registration_ttl;
			self::$_REGISTRATION_PURGE = $a->registration_purge;
			self::$REQUIRE_AUTHORIZATION = (bool)$a->require_authorization;
			self::$CHECKOUT = (bool)$a->checkout;
			self::$_QR_FOOTER = (string)$a->qr_footer;

		}

		if( extension_loaded('apcu') && ini_get('apc.enabled') && ( php_sapi_name() != 'cli' || ini_get('apc.enable_cli'))) {
      if ( \class_exists('MatthiasMullie\Scrapbook\Adapters\Apc')) {
        self::$DB_CACHE = 'APC';	// values = 'APC'
        self::$DB_CACHE_DEBUG_FLUSH = true;

      }
      else {
        \sys::logger( sprintf('<%s> %s', 'please install cache', __METHOD__));
        \sys::logger( sprintf('<%s> %s', 'composer require matthiasMullie/scrapbook', __METHOD__));

      }

		}

  }

  static function qrFooter( $set = null) {
    $ret = self::$_QR_FOOTER;

    if ( !\is_null( $set)) {
      $config = self::c19_config();

      $j = file_exists( $config) ?
        json_decode( file_get_contents( $config)):
        (object)[];

      if ( !isset($j->qr_footer) || $j->qr_footer != $set) {
        self::$_QR_FOOTER = $j->qr_footer = (string)$set;
        file_put_contents( $config, json_encode( $j, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        \sys::logger( sprintf('<updated qrFooter> %s', __METHOD__));

      }

    }

    return $ret;

  }

}

config::c19_init();
