<?php
/**
 * 設定ファイル
 */

// データベース接続情報
$_dsn = 'mysql:dbname=video_contents;host=localhost;charset=utf8';

// データベース接続ユーザ
$_db_user = 'xxx';

// データベース接続パスワード
$_db_password = 'xxx';

// フッタ表示
$_footer = '';


// 画像ファイル格納ディレクトリのパス
define('DIR_PATH_IMAGE', './contents/thumbnail/');

// mp4ファイル格納ディレクトリのパス
define('DIR_PATH_MP4', './contents/movies/');

// ogvファイル格納ディレクトリのパス
define('DIR_PATH_OGV', './contents/movies/');
