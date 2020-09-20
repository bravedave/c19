<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
 * styleguide : https://codeguide.co/
*/

namespace c19;

class currentUser extends \currentUser {
	static public function isProgrammer() {
		if ( isset( self::user()->programmer)) {
			return ( self::user()->programmer == 1 );

		}

		return ( self::isAdmin());

	}

}
