video_contents：簡易動画公開システム
===========

## はじめに

簡易動画公開システムは、以下の技術の学習を目的として作成されました。

1. PHP
2. HTML
3. SQL

## ディレクトリ構成

```
css/               # 外部CSSファイル置き場
doc/               # プログラム以外の文書など置き場
img/               # 画像置き場
js/                # 外部javascriptファイル置き場
lib/               # 共通機能置き場
config.sample.php  # 設定ファイルのひな形
index.php          # WEBページ表示プログラム
play_count.php     # 再生回数カウントプログラム
README.md          # このファイル
```

## 使用設定

1. ソースコードをダウンロードします。
2. データベースを作成し、~/doc/video_contents.sqlを実行してテーブルを作成します。
3. ~/config.sample.phpをコピーして~/config.phpを作成し、設定を変更します。
 * $_db_user: データベース接続に使用するユーザ名
 * $_db_password: データベース接続に使用するパスワード
 * $_footer: フッターに表示するメッセージ


## 使用ライブラリ

- [jQuery](http://jquery.com/)
- [Twitter Bootstrap](http://getbootstrap.com/)
