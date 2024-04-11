<?php
// Custom Read Write DBクラス
class Custom_Read_Write_DB extends wpdb {
   public $read_connection;
   public $write_connection;

   // コンストラクタ
   function __construct() {
      // wp-config.phpから認証情報を読み取る
      $db_user = DB_USER;
      $db_password = DB_PASSWORD;
      $db_name = DB_NAME;

      // Initialize the master database (write connection)
      $this->write_connection = new wpdb($db_user, $db_password, $db_name, DB_HOST);

      // Initialize the read database connection
      $this->read_connection = new wpdb($db_user, $db_password, $db_name, DB_HOST_READ);
   }

   // クエリの実行
   function query($query) {
      // Determine if query is a read operation
      if ($this->is_read_query($query)) {
         return $this->read_connection->query($query);
      } else {
         // For write operations, use the master database
         return $this->write_connection->query($query);
      }
   }

   // 読み取りクエリかどうかの判定
   function is_read_query($query) {
      // 正規表現を使用してクエリが読み取りクエリかどうかを判定する
      return (bool) preg_match('/^\s*(SELECT|DESCRIBE|EXPLAIN)\s+/i', $query);
   }
}
