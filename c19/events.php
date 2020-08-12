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
use sys;

class events extends controller {
	protected $label = 'Events';

	protected function postHandler() {
		$action = $this->getPost( 'action');

		if ( 'delete' == $action) {
			if ( ( $id = (int)$this->getPost('id')) > 0 ) {
				$dao = new dao\people;
				$dao->delete( $id);

				Json::ack( $action);

			} else { Json::nak( $action); }

		}
		elseif ( 'save-events' == $action) {
			$a = [
				'updated' => \db::dbTimeStamp(),
				'description' => $this->getPost('description'),
				'open' => (int)$this->getPost('open'),
				'start' => $this->getPost('start'),
				'end' => $this->getPost('end'),

			];

			if ( ( $id = (int)$this->getPost('id')) > 0 ) {
				$dao = new dao\events;
				$dao->UpdateByID( $a, $id);
				Json::ack( $action)
					->add( 'id', $id);

			}
			else {
				if ( $a['description']) {

					$dao = new dao\events;
					$a['created'] = $a['updated'];
					$id = $dao->Insert( $a);
					Json::ack( $action)
						->add( 'id', $id);

				} else { Json::nak( $action); }

			}

		}
		elseif ( 'update-registration' == $action) {
			$a = [
				'name' => $this->getPost('name'),

			];

			if ( ( $id = (int)$this->getPost('id')) > 0 ) {
				$dao = new dao\registrations;
				$dao->UpdateByID( $a, $id);
				Json::ack( $action)
					->add( 'id', $id);

      } else { Json::nak( $action); }

		}
		else {
			parent::postHandler();

		}

	}

	protected function _index() {
		$dao = new dao\events;
		$this->data = (object)[
			'dataset' => $dao->getOpenEventsWithAttendance()

    ];

    $secondary = [
      'events/index-title',
      'events/index-up',

    ];

		$this->render(
			[
				'title' => $this->title = $this->label,
				'primary' => 'events/report',
				'secondary' => $secondary,

			]

		);

	}

	public function attendees( $id = 0) {
    if ( $id = (int)$id) {
      $dao = new dao\events;
      $this->data = (object)[
        'registrations' => $dao->getRegistrationsForEvent( $id)

      ];

      $this->load('events/registered-attendees');

		} else { $this->load('events/not-found'); }

  }

	public function edit( $id = 0) {
		$this->data = (object)[
			'title' => $this->title = 'Add Event',
			'dto' => new dao\dto\events

		];

		if ( $id = (int)$id) {
			$dao = new dao\events;
			if ( $dto = $dao->getByID( $id)) {

				$this->data->title = $this->title = 'Edit Event';
				$this->data->dto = $dto;
				$this->load('events/edit');

			}
			else {
				$this->load('events/not-found');

			}

		}
		else {
      $this->load('events/edit');

		}

  }

  public function editRegistration( $id) {
    if ( $id = (int)$id) {
      $dao = new dao\registrations;
      if ( $dto = $dao->getByID( $id)) {
        $this->title = 'Edit Registration';

        $this->data = (object)[
          'dto' => $dto

        ];

        $this->load('events/edit-registration');

      }

    }

  }

	public function registrations( $id = 0) {

    if ( $id = (int)$id) {
      $dao = new dao\events;
      $this->data = (object)[
        'title' => $this->title = 'Registrations',
        'event' => $dao->getByID( $id)

      ];

      $secondary = [
        'events/index-title',
        'events/index-up',

      ];

      $this->render([
        'title' => $this->title = $this->label,
        'primary' => 'events/registrations',
        'secondary' => $secondary,

      ]);

		}
		else {
      $this->render([
        'title' => $this->title = $this->label,
        'primary' => 'events/not-found',
        'secondary' => $secondary,

      ]);

		}

  }

}