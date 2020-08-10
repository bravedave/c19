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

use Json;
use strings;

class recover extends controller {
	protected function postHandler() {

        $action = $this->getPost('action');
        if ( $action == 'reset password') {

            if ( $key = $this->getParam('guid')) {

                $pwd = $this->getPost('password');
                if ( strlen($pwd) < 6) {
                    Json::nak( sprintf( '%s: Password too short', $action));

                }
                elseif (!preg_match("#[0-9]+#", $pwd)) {
                    Json::nak( sprintf( '%s: Password must include at least one number', $action));

                }
                elseif (!preg_match("#[a-zA-Z]+#", $pwd)) {
                    Json::nak( sprintf( '%s: Password must include at least one letter', $action));

                }
                else {
                    $a = [
                        'password' => password_hash( $pwd, PASSWORD_DEFAULT)
                    ];

                    $dao = new dao\users;
                    if ( $dto = $dao->getByResetKey( $key)) {
                        $dao->UpdateByID( $a, $dto->id);
                        Json::ack( $action);

                    } else { Json::nak( $action); }

                }

            } else { Json::nak( $action); }

        } else { parent::postHandler(); }

    }

    protected function _index() {
      if ($key = $this->getParam('k')) {
        $dao = new dao\users;
        if ($dto = $dao->getByResetKey($key)) {
            $this->data = (object)[
                'guid' => $key,
                'dto' => $dto
            ];

            $this->render([
                'data' => [
                    'title' => 'Reset Password',
                ],
                'content' => [
                    'password-reset'

                ],
                'navbar' => 'blank'
            ]);

        }
        else {
          Response::redirect(strings::url(), 'invalid recovery key');

        }

      } else {
          Response::redirect();

      }

    }

}
