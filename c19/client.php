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

use Json;
use strings;

class client extends controller {
  protected function _index() {
    $dao = new dao\events;
    $this->data = (object)[
      'events' => $dao->dtoSet( $dao->getOpenEvents()),

    ];

    if ( $this->data->events) {
      $this->render([
        'data' => [
          'title' => $this->title = config::$CLIENT_TITLE,
        ],
        'content' => [
          'client'
        ]

      ]);

    }
    else {
      $this->render([
        'data' => [
          'title' => $this->title = config::$CLIENT_TITLE,
        ],
        'content' => 'no-events-found'

      ]);

    }

  }

  protected function postHandler() {
    $action = $this->getPost( 'action');

    if( 'checkout' == $action) {
      if ( $uid = $this->getPost( 'uid')) {
        $dao = new dao\registrations;
        if ( $dto = $dao->getByUID( $uid)) {
          $dao->checkOutParty( $dto);
          Json::ack( $action)
            ->add( 'data', [
              'uid' => $uid

            ]);

        } else { Json::nak( $action); }

      } else { Json::nak( $action); }

    }
    elseif ( 'register' == $action) {
      $a = [
        'event' => $this->getPost( 'event'),
        'name' => $this->getPost('name'),
        'party' => $this->getPost( 'party'),
        'phone' => $this->getPost( 'phone'),
        'address' => $this->getPost( 'address'),
        'uid' => bin2hex( random_bytes( 11)),
        'created' => \db::dbTimeStamp()

      ];

      $dao = new dao\registrations;
      $id = $dao->Insert( $a);

      if ( $family = $this->getPost('family')) {
        $a['parent'] = $id;
        $family = (array)$family;
        foreach ($family as $kid) {
          $a['name'] = (string)$kid;
          $dao->Insert( $a);

        }

      }

      Json::ack( $action)
        ->add( 'data', [
          'uid' => $a['uid'],
          'valid' => date('Y-m-d')

        ]);

    }
    elseif( 'get-checkout-url' == $action) {
      if ( $uid = $this->getPost( 'uid')) {
        $dao = new dao\registrations;
        if ( $dto = $dao->getByUID( $uid)) {
          Json::ack( $action);

        } else { Json::nak( $action); }

      } else { Json::nak( $action); }

    }
    else {
      parent::postHandler();

    }

  }

  protected function render($params) {
    $params = \array_merge([
      'footer' => implode( DIRECTORY_SEPARATOR, [
        __DIR__,
        'views',
        'blank'
      ]),
      'navbar' => implode( DIRECTORY_SEPARATOR, [
        __DIR__,
        'views',
        'navbar-client'
      ]),

    ], $params);

    // $params['meta'][] = '<meta name="apple-mobile-web-app-capable" content="yes">';
    $params['css'][] = sprintf('<link type="text/css" rel="stylesheet" media="all" href="%s">', strings::url('/c19css'));
    $params['css'][] = sprintf('<link rel="apple-touch-icon" sizes="72x72" href="%s">', strings::url('/image/apple-touch-icon-72x72.png'));
    $params['css'][] = sprintf('<link rel="apple-touch-icon" sizes="114x114" href="%s">', strings::url('/image/apple-touch-icon-114x114.png'));
    $params['css'][] = sprintf('<link rel="apple-touch-icon" href="%s">', strings::url('/image/apple-touch-icon-57x57.png'));
    $params['css'][] = sprintf('<link rel="apple-touch-startup-image" href="%s">', strings::url('/image/splash-startup.png'));





    parent::render($params);

  }

  public function checkedout() {
    $this->data = (object)[
      'dtoSet' => false

    ];

    if ( $uid = $this->getParam( 'uid')) {
      // \sys::logger( sprintf('<%s> %s', $uid, __METHOD__));

      $dao = new dao\registrations;
      $this->data->dtoSet = $dao->getPartyByUID( $uid);
      // \sys::dump( $this->data->dtoSet);

    }

    $this->load('thanks-for-checkout');

  }

  public function thanks() {
    $this->data = (object)[
      'dtoSet' => false

    ];

    if ( $uid = $this->getParam( 'uid')) {
      // \sys::logger( sprintf('<%s> %s', $uid, __METHOD__));

      $dao = new dao\registrations;
      $this->data->dtoSet = $dao->getPartyByUID( $uid);
      // \sys::dump( $this->data->dtoSet);

    }

    $this->load('thanks-for-registering');

  }

}
