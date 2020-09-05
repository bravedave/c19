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
use dvc;
use dvc\session;
use dvc\Response;
use BaconQrCode as QrCode;

class controller extends dvc\Controller {
  protected function _authorize() {
    /**
     * curl -X POST -H "Accept: application/json" -d action="-system-logon-" -d u="john" -d p="" "http://localhost/"
     */

    $action = $this->getPost('action');

    if ('-system-logon-' == $action) {
      if ($u = $this->getPost('u')) {
        if ($p = $this->getPost('p')) {
          $dao = new dao\users;
          if ($dto = $dao->validate($u, $p)) {
            Json::ack($action);

          }
          else {
            Json::nak($action);

          }
          die;

        }

      }

    }
    elseif ('-send-password-' == $action) {
        /*
        * send a link to reset the password
        * curl -X POST -H "Accept: application/json" -d action="-send-password-" -d u="david@brayworth.com.au" -d "http://localhost/"
        */
        if ($u = $this->getPost('u')) {
            $dao = new dao\users;
            if ($dto = $dao->getByEmail($u)) {
                /*
                * this will only work for email addresses
                */
                if ($dao->sendResetLink($dto)) {
                    sys::logger('-send-password-link--');
                    // Json::ack('sent reset link');
                    Json::ack('sent reset link')
                        ->add('message', 'sent link, check your email and your junk mail');

                    sys::logger('-sent-password-link--');
                    die;

                } else { Json::nak($action); }

            } else { Json::nak($action); }

        } else { Json::nak($action); }

    } else { throw new InvalidPostAction; }

  }

  protected function _index() {
      $render = [
          'primary' => 'home',
          'secondary' => [
            'index',
            'qr-code',

          ]

      ];

      $this->render( $render);

  }

  protected function authorize() {
      if ($this->isPost()) {
          $this->_authorize();

      }
      else { parent::authorize(); }

  }

  protected function getView($viewName = 'index', $controller = null, $logMissingView = true) {
      $view = sprintf('%s/views/%s.php', __DIR__, $viewName);        // php
      if (file_exists($view))
          return ($view);

      return parent::getView($viewName, $controller, $logMissingView);

  }

  protected function render($params) {
    // dvc\pages::$contentClass = 'col pt-3 pb-4';
    \dvc\pages\bootstrap::$primaryClass = 'col-md-9 col-xl-10 pt-3 pb-4';
    \dvc\pages\bootstrap::$secondaryClass = 'd-none d-md-block col-md-3 col-xl-2 bg-secondary text-light pt-3 pb-4 d-print-none';

    $navbar = implode(
      DIRECTORY_SEPARATOR, [
        __DIR__,
        'views',
        'navbar'

      ]

    );

    $params = \array_merge([
      'css' => [],
      'scripts' => [],
      'data' => [],
      'left-interface' => true,
      'navbar' => $navbar

    ], $params);

    $params['data'] = \array_merge(['title' => config::$WEBNAME], (array)$params['data']);
    $params['css'][] = sprintf('<link type="text/css" rel="stylesheet" media="all" href="%s" />', strings::url('c19css'));
    $params['css'][] = sprintf('<link rel="icon" href="%s" sizes="32x32" />', strings::url('image/apple-touch-icon-32x32.png'));
    // $params['scripts'][] = sprintf('<script type="text/javascript" src="%s"></script>', strings::url( 'fatjs'));

    parent::render($params);

  }

  protected function postHandler() {
    $action = $this->getPost( 'action');

    if ( 'save-settings' == $action) {
      $registration_ttl = (int)$this->getPost( 'registration_ttl');

      if ( $registration_ttl > 0) {
        config::c19_registration_ttl( $registration_ttl);

      }

      config::c19_checkout( (int)$this->getPost( 'checkout'));

      Json::ack( $action);

    }
    else {
      parent::postHander();

    }
  }

  public function __construct($rootPath) {
      // \sys::logger( sprintf('<%s> %s', get_class($this), __METHOD__));
      if ( \in_array( get_class($this),[
          'assets',
          'home',
          'c19\recover'
      ])) {
          $this->RequireValidation = false;

      }
      else {
          $this->RequireValidation = config::$REQUIRE_AUTHORIZATION;

      }

      config::c19_checkdatabase();
      parent::__construct($rootPath);

      self::application()->addPath( __DIR__ );

      $this->title = config::$WEBNAME;

  }

  public function c19css() {
      $path = implode(DIRECTORY_SEPARATOR, [
          __DIR__,
          'css',
          'custom.css'

      ]);

      sys::serve($path);

  }

  public function image($img) {
    if ( \in_array( $img, [
      'apple-touch-icon-57x57.png',
      'apple-touch-icon-114x114.png',
      'apple-touch-icon-72x72.png',
      'apple-touch-icon-32x32.png',
      'splash-startup.png'
      ])) {

      $path = implode(DIRECTORY_SEPARATOR, [
          __DIR__,
          'images',
          $img

      ]);

      if (\file_exists($path)) sys::serve($path);

    }
    else {
      $img = preg_replace('@[^a-z0-9_]@', '', $img);
      if ($img) {
        $path = implode(DIRECTORY_SEPARATOR, [
            __DIR__,
            'images',
            $img

        ]);

        if (\file_exists($path . '.jpg')) sys::serve($path . '.jpg');
        elseif (\file_exists($path . '.png')) sys::serve($path . '.png');

      }

    }

  }

  public function qrcode( $v = '') {
    if ( 'v' == $v) {
      $this->render([ 'content' => 'qr-code' ]);

    }
    else {
      $qrCode = implode( DIRECTORY_SEPARATOR, [
        config::dataPath(),
        'qr-code.svg'

      ]);

      if ( !\file_exists( $qrCode)) {
        // $renderer = new \BaconQrCode\Renderer\ImageRenderer(
        //     new \BaconQrCode\Renderer\RendererStyle\RendererStyle(800),
        //     new \BaconQrCode\Renderer\Image\ImagickImageBackEnd()
        // );

        $renderer = new QrCode\Renderer\ImageRenderer(
            new QrCode\Renderer\RendererStyle\RendererStyle(800, $margin = 0),
            new QrCode\Renderer\Image\SvgImageBackEnd()
        );

        $writer = new QrCode\Writer( $renderer);
        $writer->writeFile( strings::url('', $protocol = true), $qrCode);

      }

      if ( \file_exists( $qrCode)) {
        sys::serve( $qrCode);

      }

    }

  }

  public function settings() {
    $this->load( 'settings');

  }

}
