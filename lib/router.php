<?php

  $ROUTES = [];

  class Router {
    public static  $routes = array();

    var $url;
    var $controller;
    var $action;
    var $method;
    var $path;

    public function init () {
      $current_route = Router::find_route();

      if($current_route){
        call_user_func($current_route->controller.'Controller::'.$current_route->action);
      }else{
        Controller::render('404');
      }
    }

    public function find_route () {
      $current_route = null;

      foreach(self::$routes as $route) {
          if (explode('?',$_SERVER['REQUEST_URI'])[0] == $route->url &&
              $_SERVER["REQUEST_METHOD"] == $route->method) {
              $current_route = $route;
              break;
          }
      }

      return $current_route;
    }

    public function redirect_to ($controller, $action, $params = null) {
      $url = null;

      foreach (self::$routes as $route) {
        if ($controller == $route->controller &&
            $action     == $route->action){
          $url = $route->url;
          break;
        }
      }

      if ($params){
        $params_formated = [];

        foreach ($params as $k => $v) {
          array_push($params_formated, $k.'='.$v);
        }

        $url = $url.'?'.implode('&', $params_formated);
      }

      header('Location: '.$url);
      die();
    }

    public function add_route (array $cfg) {
      $route = new stdClass();

      foreach($cfg as $k=>$v){
          $route->{$k}=$v;
      }

      array_push(self::$routes, $route);
    }

  }

?>