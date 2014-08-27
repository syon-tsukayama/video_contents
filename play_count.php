<?php

/**
 * 再生数のカウントプログラム
 * 厳密にはクリック数ですが。。。
 */

// POSTデータチェック
if(!empty($_POST['content_id']) && !empty($_POST['file_path']))
{
    // 共通機能読み込み
    require_once('./lib/common.php');

    // データベース接続
    $conn = cmn_connect_db();

    // データ登録するSQL作成
    $sql =<<<EOS
INSERT INTO play_logs_table (
    `content_id`, `file_name`, `created`, `updated`
    ) VALUES (
    :content_id, :file_name, NOW(), NOW()
    );
EOS;

    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':content_id', $_POST['content_id'], PDO::PARAM_INT);
    $stmt->bindValue(':file_name', $_POST['file_path'], PDO::PARAM_STR);
    $stmt->execute();

    echo 'ok. '.$_POST['content_id'];
}
else
{
    echo '.';
}

