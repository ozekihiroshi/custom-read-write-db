<?php
/*
Plugin Name: Custom Read Write DB
Description: WordPress plugin to separate read and write database connections.
Version: 1.0
Author: Hiroshi Ozeki
*/

// プラグインのメインクラス
class Custom_Read_Write_DB_Plugin {
    // コンストラクタ
    public function __construct() {
        // フックを設定
        register_activation_hook(__FILE__, array($this, 'activate'));
    }

    // プラグインが有効化されたときに実行される関数
    public function activate() {
        // 初期化処理を実行
        $this->init();
    }

    // プラグインの初期化
    public function init() {
        // Custom_Read_Write_DBクラスをロードし、wpdbオブジェクトを置き換える
        require_once(plugin_dir_path(__FILE__) . 'custom-read-write-db-class.php');
        global $wpdb;
        $wpdb = new Custom_Read_Write_DB();

        // ここでデータベース接続を設定する
        $wpdb->add_database(array(
            'host'     => DB_HOST_WRITE,
            'user'     => DB_USER,
            'password' => DB_PASSWORD,
            'name'     => DB_NAME,
        ));
        $wpdb->add_database(array(
            'host'     => DB_HOST_READ,
            'user'     => DB_USER,
            'password' => DB_PASSWORD,
            'name'     => DB_NAME,
        ));
    }
}

// インスタンス化してプラグインを開始
new Custom_Read_Write_DB_Plugin();
