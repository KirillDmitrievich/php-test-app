<?php
  class User extends DBTable {
    const SALT = 'asdh@21#$@!$@!W';
    static $fields = ['first_name', 'last_name', 'phone', 'encrypted_password',
    'email', 'country', 'city', 'street', 'house', 'flat', 'id'];
    static $file_fields = ['avatar'];
    static $validates = array(
      'first_name' => array('required' => true),
      'last_name' => array('required' => true),
      'encrypted_password' => array('required' => true),
      'email' => array('required' => true, 'format' => '/[\w\d]+@[\w\d]+\.\w+/'),
      'phone' => array('required' => true, 'format' => '/\d{8,8}/'),
      );
  }
?>