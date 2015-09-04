<?php
  class DBTable {
    public $table_name;
    static $fields;
    static $file_fields;
    static $validates;

    function validate($params){
      $errors = '';

      foreach (static::$validates as $field => $rules) {
        foreach ($rules as $key => $value) {
          switch ($key) {
            case 'required':
              if($params[$field] == ''){
                $errors = $errors.$field.' is required<br/>';
              }
              break;
            case 'format':
              if(!$params[$field] == '' && !preg_match($value, $params[$field])){
                $errors = $errors.$field.' have wrong format!<br/>';
              };
              break;
            default:
              die("wrong argument");
              break;
          }
        }
      }

      if(!empty($errors)){
        header('Location: '.explode('?', $_SERVER["HTTP_REFERER"])[0].'?errors='.$errors);
        die();
      }
    }

    function clear_params($params, $link){
      foreach ($params as $key => $value) {
        $params[$key] = mysql_real_escape_string(
          preg_replace('/[^\d\w-@.,()]/', '', $value), $link);
      }
      return $params;
    }

    function db_connect () {
      $database   = 'php_test_app';
      $host       = 'localhost';
      $dbuser     = 'root';
      $dbpassword = 'root';

      $link = mysql_connect($host, $dbuser, $dbpassword)
        or die('Could not connect: ' . mysql_error());

      mysql_set_charset('utf8',$link);
      mysql_select_db($database) or die('Could not select database');

      return $link;
    }

    function create ($params, $files) {
      self::validate($params);
      $link   = self::db_connect();
      $params = self::clear_params($params,$link);

      $table      = get_called_class().'s';

      $val_fields = array_map(function ($f) { return "`".$f."`"; }, static::$fields);

      $values = [];

      foreach (static::$fields as $field) {
        array_push($values, '"'.$params[$field].'"');
      }

      foreach (static::$file_fields as $field) {
        if($files[$field]['name']){
          $dir_path  = 'uploads/'.get_called_class().'/';
          mkdir($dir_path, 0777);
          $file_path = $dir_path.$files[$field]['name'];
          move_uploaded_file($files[$field]['tmp_name'], $file_path);


          array_push($val_fields, '`'.$field.'`');
          array_push($values, '"'.$file_path.'"');
        }
      }

      $request_fields = implode(', ', $val_fields);
      $request_values = implode(', ', $values);


      $query = "INSERT INTO .`".$table.
        "` (".$request_fields.") VALUES (".$request_values.")";

      $result = mysql_query($query, $link) or die('MySQL error: '.mysql_error());
      $id     = (mysql_insert_id($link));

      mysql_close($link);
      return $id;
    }

    function find ($id) {
      $table  = get_called_class().'s';

      $link   = self::db_connect();

      $query  = 'SELECT * FROM '.$table.' WHERE id = '.(int)$id;
      $result = mysql_query($query, $link) or die('MySQL error: '.mysql_error());


      $record = mysql_fetch_assoc($result);
      mysql_close($link);

      return new static($record);
    }

    function where ($rules, $columns = false) {
      $table      = get_called_class().'s';
      $link       = self::db_connect();
      $query_cols = '*';
      if ($columns) {
        $query_cols = implode(', ', array_map(function($c) { return '`'.$c.'`'; }, $columns));
      }

      $query = 'SELECT '.$query_cols.' FROM '.$table.' WHERE '.$rules;
      $result = mysql_query($query, $link) or die('MySQL error: '.mysql_error());

      $response = [];

      while ($record = mysql_fetch_object($result)) {
        array_push($response, $record);
      }

      mysql_close($link);
      return $response;
    }

    public function __construct($params){
      foreach(static::$fields as $key){
        $this->{$key} = $params[$key];
      }

      foreach(static::$file_fields as $key){
        $this->{$key} = $params[$key];
      }
    }
  }

?>