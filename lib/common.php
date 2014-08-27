<?php

/**
 * 複数のプログラムで利用される共通処理のまとめ
 */

/**
 * データベース接続処理
 */
function cmn_connect_db()
{
    // データベース接続
    // 接続設定
    $dbtype = 'mysql';
    $sv = 'localhost';
    $dbname = 'video_contents';
    $user = 'xxx';
    $pass = 'xxx';

    // データベースに接続
    $dsn = "$dbtype:dbname=$dbname;host=$sv;charset=utf8";
    $conn = new PDO($dsn, $user, $pass);

    return $conn;
}


