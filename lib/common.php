<?php

/**
 * 複数のプログラムで利用される共通処理のまとめ
 */

// 設定ファイル読み込み
require_once('./config.php');

/**
 * データベース接続処理
 */
function cmn_connect_db()
{
    global $_dsn;
    global $_db_user;
    global $_db_password;

    // データベース接続
    $conn = new PDO($_dsn, $_db_user, $_db_password);

    return $conn;
}


