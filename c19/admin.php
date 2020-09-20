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

use sys;

class admin extends controller {
  protected function postHandler() {
    $action = $this->getPost( 'action');

    if ( 'save-settings' == $action) {
      $registration_ttl = (int)$this->getPost( 'registration_ttl');

      if ( $registration_ttl > 0) {
        config::c19_registration_ttl( $registration_ttl);

      }

      config::c19_checkout( (int)$this->getPost( 'checkout'));
      config::qrFooter( (string)$this->getPost( 'qr_footer'));

      Json::ack( $action);

    }
    else {
      parent::postHander();

    }
  }

	public function dbdownload() {
		if ( config::$DB_TYPE == 'sqlite' ) {
			if ( currentUser::isProgrammer()) {
				$zipfile = $this->db->zip();
				if ( file_exists( $zipfile)) {
					sys::serve( $zipfile);

				}

			} else { $this->_index(); }

		} else { $this->_index(); }

	}

  public function settings() {
    $this->load( 'settings');

  }

}
