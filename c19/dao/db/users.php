<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace dao;

$dbc = \sys::dbCheck( 'users');

$dbc->defineField( 'username', 'varchar');
$dbc->defineField( 'programmer', 'tinyint');
$dbc->defineField( 'reset_guid', 'varchar');
$dbc->defineField( 'reset_guid_date', 'datetime');
$dbc->check();

if ( $res = $this->db->Result( 'SELECT count(*) count FROM users' )) {
	if ( $dto = $res->dto()) {
		if ( $dto->count < 1 ) {
			$a = [
				'username' => 'admin',
				'name' => 'Administrator',
				'password' => password_hash( 'admin', PASSWORD_DEFAULT),
				'active' => 1,
				'admin' => 1,
				'created' => \db::dbTimeStamp(),
				'updated' => \db::dbTimeStamp()

			];
			$this->db->Insert( 'users', $a );
			\sys::logger( 'wrote users defaults');

		}
		else {
			\sys::logger( sprintf( 'there are %d user(s)', $dto->count));

		}

	}

}

if ( $res = $this->db->Result( 'SELECT count(*) count FROM users WHERE username = "nippers"' )) {
	if ( $dto = $res->dto()) {
		if ( $dto->count < 1 ) {
			$a = [
				'username' => 'nippers',
				'name' => 'Nippers',
				'password' => password_hash( 'nippers', PASSWORD_DEFAULT),
				'active' => 1,
				'created' => \db::dbTimeStamp(),
				'updated' => \db::dbTimeStamp()

			];
			$this->db->Insert( 'users', $a );
			\sys::logger( 'wrote nipper users');

		}

	}

}
