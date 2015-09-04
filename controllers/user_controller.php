<?php
  class UserController extends Controller {
    function new_user () {
      $locs      = array('errors' => $_GET['errors']);
      $locs = self::addLocalizeToLocs($locs, 'registration');

      self::render("new_user", $locs);
    }

    function create () {
      $params = $_POST;
      if($params['password'] == $params['password_confirmation']){
        $params['encrypted_password'] = crypt($params['password'], User::SALT);
      }
      $id = User::create($params, $_FILES);
      Router::redirect_to('user','profile', array('id' => $id));
    }

    function profile () {
      $user = User::find($_GET['id']);
      $locs = (array)$user;
      $locs = self::addLocalizeToLocs($locs, 'profile');

      self::render("profile", $locs);
    }

  }
?>