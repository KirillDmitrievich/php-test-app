<?php
  class Controller {
    function render ($path, $locals = false, $layout = 'layout') {
      $layoutfilepath = $_SERVER['DOCUMENT_ROOT'].'/views/'.$layout.'.html';
      $pagefilepath   = $_SERVER['DOCUMENT_ROOT'].'/views/'.$path.'.html';

      $layout_content   = file_get_contents($layoutfilepath);
      $page_content     = file_get_contents($pagefilepath);

      if($locals){
        $arr1 = array_map(function($k){ return '%'.$k.'%'; },array_keys($locals));
        $arr2 = array_values($locals);

        $page_content = str_replace($arr1, $arr2, $page_content);
      }

      $content = str_replace('%YIELD%', $page_content, $layout_content);

      echo $content;
    }

    function get_loc (){
      $loc = 'en';
      $locales_list = ['en','ru','ua'];

      if($_GET['lang'] && in_array($_GET['lang'], $locales_list)){ $loc = $_GET['lang']; }
      return $loc;
    }

    function addLocalizeToLocs ($locs, $page){
      $localizes = Localize::where('`page` = "'.$page.'"', ['title', self::get_loc()]);
      foreach ($localizes as $loc) {
        $locs['localize_'.$loc->title] = $loc->{self::get_loc()};
      }
      return $locs;
    }
  }
?>