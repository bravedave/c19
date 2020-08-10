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

    if ( 'register' == $action) {
      $a = [
        'event' => $this->getPost( 'event'),
        'name' => $this->getPost('name'),
        'party' => $this->getPost( 'party'),
        'phone' => $this->getPost( 'phone'),
        'address' => $this->getPost( 'address'),
        'created' => \db::dbTimeStamp()

      ];

      $dao = new dao\registrations;
      $dao->Insert( $a);
      Json::ack( $action);

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
      ])

    ], $params);

    $params['css'][] = sprintf('<link type="text/css" rel="stylesheet" media="all" href="%s" />', strings::url('/c19css'));

    parent::render($params);

  }

  function thanks() {
    $this->load('thanks-for-registering');

  }

}
