<?php

class Koneksi {
   protected static $_HOST = "localhost";
   protected static $_PORT = 3309;//3306
   protected static $_DB_NAME = "koding_web";
   protected static $_USERNAME = "root";
   protected static $_PASSWORD = "12345";
   protected static $_CONNECT_DB = null;

   public static function connect() {
      self::$_CONNECT_DB = @mysqli_connect(
         self::$_HOST,
         self::$_USERNAME,
         self::$_PASSWORD,
         self::$_DB_NAME,
         self::$_PORT
      );
      if(!self::$_CONNECT_DB){
         die("Gagal Konekesi ke database: ".mysqli_connect_error());
      }/*else{
         echo "Koneksi Sukses";
      }*/
      return self::$_CONNECT_DB;
   }

   public static function close(){
      if(self::$_CONNECT_DB !== null) mysqli_close(self::$_CONNECT_DB);
   }
}

// Test
//Koneksi::connect();
//Koneksi::close();