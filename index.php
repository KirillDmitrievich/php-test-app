<?php
  $app_directories = array('lib', 'controllers','models', 'config', 'views');

  foreach ($app_directories as $dir) {
    foreach (glob($dir."/*.php") as $filename)
    {
        include $filename;
    }
  }

 ?>