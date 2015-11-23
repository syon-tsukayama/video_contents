<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>水産・海洋実習</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le styles -->
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css" integrity="sha384-aUGj/X2zp5rLCbBxumKTCw2Z50WgIr1vs/PFN4praOTvYXWlVyh2UtNUU0KAUhAX" crossorigin="anonymous">

        <style type="text/css">
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }
        .sidebar-nav {
            padding: 9px 0;
        }

        @media (max-width: 980px) {
            /* Enable use of floated navbar text */
            .navbar-text.pull-right {
                float: none;
                padding-left: 5px;
                padding-right: 5px;
            }
        }
        </style>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->
        <script src="./js/jquery-1.11.3.min.js"></script>
    </head>

    <body>
    <?php

// 共通機能読み込み
require_once('./lib/common.php');

// データベース接続
$conn = cmn_connect_db();

// テスト出力
if(!$conn)
{
    echo '接続エラー';
    exit;
}

// メニューIDの取得
if(!empty($_GET['menu_id']))
{
    $menu_id = $_GET['menu_id'];
}
else
{
  // トップページのIDを指定
    $menu_id = 1;
}

// 記事ID取得
if(!empty($_GET['page_id']))
{
    $page_id = $_GET['page_id'];
}
else
{
  // 初期表示の内容ID指定
    $page_id = 1;
}

    ?>
    <div class="container-fluid">

        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-inner">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">水産・海洋実習</a>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
<?php
// メニュー情報を検索するSQL実行
$sql =<<<EOS
SELECT `id`, `name`, `page_id`, `order_no`
FROM `menus_table`
WHERE `publish_status` = 1
ORDER BY `order_no` ASC;
EOS;

$stmt = $conn->prepare($sql);

$stmt->execute();

// 検索結果取得
while($row = $stmt->fetch())
{
  $menu_url = basename(__FILE__).'?menu_id='.$row['id'].'&page_id='.$row['page_id'];

  if($row['id'] == $menu_id)
  {
?>
              <li class="active">
                  <a href="<?php echo $menu_url; ?>"><?php echo $row['name']; ?></a>
              </li>
<?php
  }
  else
  {
?>
              <li>
                  <a href="<?php echo $menu_url; ?>"><?php echo $row['name']; ?></a>
              </li>
<?php
}
}
?>
                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>

        <div class="container-fluid">
<?php
// ページIDからページデータ検索
$sql =<<<EOS
SELECT `id`, `name`, `template_name`
FROM `pages_table`
WHERE `publish_status` = 1 AND `id` = :page_id;
EOS;

$stmt = $conn->prepare($sql);
$stmt->bindParam(':page_id', $page_id);
$stmt->execute();

$row = $stmt->fetch();

if(empty($row['template_name']) || !is_file('./tpl/'.$row['template_name']))
{
?>
            <div class="row">
                <div class="col-md-12">

                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Warning!</strong> このページは現在、開発中につき閲覧できません。
                    </div>

                </div>
            </div>
<?php
}
else
{
    // テンプレート読み込み
    require_once('./tpl/'.$row['template_name']);
}
?>
            <hr />

            <footer>
                <?php echo $_footer; ?>
            </footer>

        </div><!--/.fluid-container-->
    </div><!--/.fluid-container-->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>

        <script type="text/javascript">
        $(function()
        {
            // play_countクラスの付加されたHTMLタグをクリックしたときの処理設定
            $('.play_count').on('click', function(event)
                {
                    $.post(
                        // 再生回数カウントプログラムの指定
                        'play_count.php',

                        // play_count.php へ送信するPOSTデータ
                        {
                            'content_id': $(this).attr('data-content_id'),
                            'file_path': $(this).attr('href')
                        },

                        // play_count.php の処理結果
                        function(data)
                        {
                        // ajax戻り値のテスト表示
//                        alert(data);
                        }
                        );
                });
        });
        </script>

    </body>

</html>