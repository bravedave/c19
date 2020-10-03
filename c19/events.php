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
				$dao = new dao\events;
				$dao->delete( $id);

				Json::ack( $action);

			} else { Json::nak( $action); }

		}
		elseif ( 'checkout-attendee' == $action) {
      if ( $id = $this->getPost( 'id')) {
        $dao = new dao\registrations;
        if ( $dto = $dao->getByID( $id)) {
          $dao->checkOutParty( $dto);
          Json::ack( $action);

        } else { Json::nak( sprintf( 'registrant not found (%s) : %s', $id, $action)); }

      } else { Json::nak( sprintf( 'invalid id : %s', $action)); }

		}
		elseif ( 'checkout-attendees' == $action) {
      if ( $ids = $this->getPost( 'ids')) {
        if ( $ids = explode( ',', $ids)) {
          $dao = new dao\registrations;
          foreach ($ids as $id) {
            if ( $dto = $dao->getByID( $id)) {
              $dao->checkOutParty( $dto);
              # code...
            }

          }
          Json::ack( $action);

        } else { Json::nak( sprintf( 'invalid ids : %s', $action)); }

      } else { Json::nak( sprintf( 'invalid ids : %s', $action)); }

		}
		elseif ( 'save-events' == $action) {
			$a = [
				'updated' => \db::dbTimeStamp(),
				'description' => $this->getPost('description'),
        'open' => (int)$this->getPost('open'),
        'start' => '',
        'end' => '',

      ];

      if ( !$a['open']) {
        $a['start'] = date( 'Y-m-d H:i:s', strtotime( $this->getPost('start')));
        $a['end'] = date( 'Y-m-d H:i:s', strtotime($this->getPost('end')));

      }

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

      if ( $event = (int)$this->getPost( 'event')) {
        $a['event'] = $event;

      }

			if ( ( $id = (int)$this->getPost('id')) > 0 ) {
        $dao = new dao\registrations;
        if ( $dto = $dao->getByID( $id)) {
          $dao->UpdateByID( $a, $id);
          Json::ack( $action)
            ->add( 'id', $id);

          if ( 'yes' == $this->getPost( 'checkout')) {
            $dao->checkOutParty( $dto);

          }

        } else { Json::nak( $action); }

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

    ];

    if ( currentUser::isAdmin()) {
      $secondary[] = 'events/index-up';

    }

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
        'event' => $dao->getByID( $id),
        'registrations' => $dao->getRegistrationsForEvent( $id)

      ];

      $this->load('events/registered-attendees');

		} else { $this->load('events/not-found'); }

  }

	public function edit( $id = 0, $mode = '') {
		$this->data = (object)[
			'title' => $this->title = 'Add Event',
			'dto' => new dao\dto\events

		];

		if ( $id = (int)$id) {
			$dao = new dao\events;
			if ( $dto = $dao->getByID( $id)) {

        if ( 'copy' == $mode) {
          $dto->id = 0;

        }
        else {
          $this->data->title = $this->title = 'Edit Event';

        }
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
        'event' => $dao->getByID( $id),
        'openevents' => $dao->getOpenEvents()

      ];

      $secondary = [
        'events/index-title',
        'events/index-open-events',

      ];

      if ( currentUser::isAdmin()) {
        $secondary[] = 'events/index-up';

      }

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
