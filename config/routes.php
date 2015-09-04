<?php

  $routes =  array(
      array(
          'method'     => 'GET',
          'url'        => '/',
          'controller' => 'website',
          'action'     => 'index',
          'path'       => 'root_path'
        ),
      array(
          'method'     => 'GET',
          'url'        => '/profile',
          'controller' => 'user',
          'action'     => 'profile',
          'path'       => 'profile_path'
        ),
      array(
          'method'     => 'GET',
          'url'        => '/new_user',
          'controller' => 'user',
          'action'     => 'new_user',
          'path'       => 'new_user_path'
        ),
      array(
          'method'     => 'POST',
          'url'        => '/user/create',
          'controller' => 'user',
          'action'     => 'create',
          'path'       => 'create_user_path'
        )
    );

  foreach ($routes as $route) {
    Router::add_route($route);
  }

  Router::init();

?>