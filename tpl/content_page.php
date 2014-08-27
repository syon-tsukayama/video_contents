<?php
/**
 * 動画コンテンツページテンプレート
 */

?>


    <div class="row-fluid">
        <div class="span3">
            <div class="well sidebar-nav">

                <ul class="nav nav-list">
                    <li class="nav-header">Sidebar</li>
<?php
// サブメニュー作成のための記事情報を検索するSQL実行
$sql =<<<EOS
SELECT `id`, `name`, `order_no`
FROM `pages_table`
WHERE `menu_id` = :menu_id AND `publish_status` = 1
ORDER BY `order_no` ASC;
EOS;

$stmt = $conn->prepare($sql);
$stmt->bindParam(':menu_id', $menu_id);
$stmt->execute();

// 検索結果取得
while($row = $stmt->fetch())
{
    $url = 'index.php?menu_id='.$menu_id.'&page_id='.$row['id'];

    if(!empty($row['name']))
    {
?>
                    <li><a href="<?php echo $url; ?>"><?php echo $row['name']; ?></a></li>
<?php
    }
}
?>
                </ul>
            </div><!--/.well -->
        </div><!--/span-->

        <div class="span9">

<?php
$sql =<<<EOS
SELECT `id`, `name`, `remark`
FROM `pages_table`
WHERE `id` = :page_id AND `publish_status` = 1;
EOS;

$stmt_select_pages = $conn->prepare($sql);
$stmt_select_pages->bindParam(':page_id', $page_id);
$stmt_select_pages->execute();

$row = $stmt_select_pages->fetch();
?>

            <div class="page-header">
                <h1><?php echo $row['name']; ?></h1>
            </div>
<?php
if(!empty($row['remark']))
{
?>
            <h4><?php echo nl2br($row['remark']); ?></h4>
            <br>
<?php
}
?>

            <br>

<?php

// 記事情報の件数を取得するSQL実行
$sql =<<<EOS
SELECT COUNT(*)
FROM `contents_table`
WHERE `page_id` = :page_id;
EOS;

$stmt_count_contents = $conn->prepare($sql);
$stmt_count_contents->bindParam(':page_id', $page_id);
$stmt_count_contents->execute();

$total_count = $stmt_count_contents->fetchColumn();


if($total_count > 0)
{
    // 記事情報を検索するSQL実行
    $sql =<<<EOS
SELECT `id`, `title`, `content`, `image_name`, `mp4_file_name`, `ogv_file_name`
FROM `contents_table`
WHERE `page_id` = :page_id;
EOS;

    $stmt_select_contents = $conn->prepare($sql);
    $stmt_select_contents->bindParam(':page_id', $page_id);
    $stmt_select_contents->execute();

    // 検索結果取得
    $row_count = 0;

    while($row = $stmt_select_contents->fetch())
    {
        $row_count++;

        if(($row_count % 3) === 1)
        {
?>
            <div class="row-fluid">
                <ul class="thumbnails">
<?php
        }

        $content_id = $row['id'];

        $modal_id = 'movie_modal_'.$row_count;

        $image_path = './contents/thumbnail/'.$row['image_name'];

        $mp4_file_path = './contents/movies/'.$row['mp4_file_name'];

        $ogv_file_path = './contents/movies/'.$row['ogv_file_name'];
?>

                    <li class="span4">
                        <div class="thumbnail">
                            <a data-target="#<?php echo $modal_id; ?>" data-toggle="modal" href="#" class="play_count" data-content_id="<?php echo $content_id; ?>">
                                <img alt="<?php echo $row['title']; ?>" style="width: 300px; height: 200px;" src="<?php echo $image_path; ?>">
                            </a>
                            <div class="caption">
                                <h3><?php echo $row['title']; ?></h3>
                                <p><?php echo $row['content']; ?></p>
                                <p>
                                    <a class="btn btn-primary play_count" data-target="#<?php echo $modal_id; ?>" data-toggle="modal" href="#" data-content_id="<?php echo $content_id; ?>">再生</a>
                                    <a class="btn play_count" href="<?php echo $mp4_file_path; ?>" data-content_id="<?php echo $content_id; ?>">mp4</a>
                                    <a class="btn play_count" href="<?php echo $ogv_file_path; ?>" data-content_id="<?php echo $content_id; ?>">ogv</a>
                                </p>
                            </div>
                        </div>

                        <div id="<?php echo $modal_id; ?>" class="modal hide fade" tabindex="-1">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h3><?php echo $row['title']; ?></h3>
                            </div>
                            <div class="modal-body">
                                <p>
                                    <video poster="<?php echo $image_path; ?>" width="320" height="180" controls loop preload="none">
                                        <source src="<?php echo $mp4_file_path; ?>">
                                        <source src="<?php echo $ogv_file_path; ?>">
                                        <p>動画を再生するには、videoタグをサポートしたブラウザが必要です。</p>
                                    </video>
                                </p>
                            </div>
                        </div>

                    </li>

<?php
        if(($row_count % 3) === 0 || $row_count == $total_count)
        {
?>
                </ul>
            </div>
<?php
        }
    }
}
?>

        </div><!--/span-->
    </div><!--/row-->
