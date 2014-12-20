<?php
/**
 * トップページテンプレート
 */

$displayed_content_ids = array();

// 再生件数を取得する
$sql =<<<EOS
SELECT `content_id`, COUNT(`id`) AS `play_count`
FROM `play_logs_table`
WHERE `content_id` IN (SELECT `id` FROM `contents_table` WHERE `publish_status` = 1)
GROUP BY `content_id` HAVING COUNT(`id`) > 0
ORDER BY `play_count` DESC;
EOS;

$stmt_select_play_logs = $conn->prepare($sql);
$stmt_select_play_logs->execute();

$play_counts = array();

// 検索結果取得
while($row = $stmt_select_play_logs->fetch())
{
    $play_counts[] = array(
        'content_id' => $row['content_id'],
        'play_count' => $row['play_count']
        );
}
?>


    <div class="row-fluid">
        <div class="span3">
            <div class="alert alert-info">

                <ul class="nav nav-list">
                    <li class="nav-header"><h4>人気動画ランキング３</h4></li>
<?php

$cnt_play_counts = count($play_counts);

if($cnt_play_counts > 0)
{
    // 動画情報表示のためのコンテンツテーブル検索SQL
    $sql =<<<EOS
SELECT `id`, `title`, `content`, `image_name`, `mp4_file_name`, `ogv_file_name`
FROM `contents_table`
WHERE `publish_status` = 1 AND `id` = :content_id;
EOS;

    $stmt_select_contents = $conn->prepare($sql);

    // 検索結果取得
    $row_count = 0;
$rank = 0;
    foreach($play_counts as $count_data)
    {
        $rank++;
        $content_id = $count_data['content_id'];
        $play_count = $count_data['play_count'];

        // ランキング表示したcontent_idを格納
        $displayed_content_ids[] = $content_id;

        $stmt_select_contents->bindValue(':content_id', $content_id, PDO::PARAM_INT);
        $stmt_select_contents->execute();

        $row = $stmt_select_contents->fetch();

        $row_count++;

        if(($row_count % 3) === 1)
        {
?>

                <ul class="thumbnails">
<?php
        }


        $content_id = $row['id'];

        $modal_id = 'movie_modal_'.$row_count;

        $image_path = DIR_PATH_IMAGE.$row['image_name'];

        $mp4_file_path = DIR_PATH_MP4.$row['mp4_file_name'];

        $ogv_file_path = DIR_PATH_OGV.$row['ogv_file_name'];
?>

                   <li class="span12">
                        <div class="thumbnail">
                            <a data-target="#<?php echo $modal_id; ?>" data-toggle="modal" href="#" class="play_count" data-content_id="<?php echo $content_id; ?>">
                                <img alt="<?php echo $row['title']; ?>" style="width: 240px; height: 150px;" src="<?php echo $image_path; ?>">
                            </a>
                            <div class="caption">
                                <h5><?php echo $row['title']; ?></h5>

                                <p>
                                    <a class="btn btn-primary play_count" data-target="#<?php echo $modal_id; ?>" data-toggle="modal" href="#" data-content_id="<?php echo $content_id; ?>">再生</a>

                                    <i class="icon-play"></i>再生回数：<?php echo $play_count; ?>
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
        if(($row_count % 3) === 0 || $row_count == $cnt_play_counts)
        {
?>
                </ul>

<?php
        }
        if($rank == 3 ){
        break;
        }
    }
}

?>
                </ul>
            </div><!--/.well -->

<?php
// 新着動画検索
$sql =<<<EOS
SELECT `id`, `title`, `content`, `image_name`, `mp4_file_name`, `ogv_file_name`
FROM `contents_table`
WHERE `publish_status` = 1
ORDER BY `updated` DESC
LIMIT 2;
EOS;

$stmt_select_contents_new = $conn->prepare($sql);
$stmt_select_contents_new->execute();

$new_contents = array();

// 検索結果取得
while($row = $stmt_select_contents_new->fetch())
{
    // 新着堂が表示するcontent_idを格納
    $displayed_content_ids[] = $row['id'];

    $new_contents[] = $row;
}

$displayed_content_ids = array_unique($displayed_content_ids);
$count = 0;
$$bind_param = '';
foreach($displayed_content_ids as $key => $content_id)
{
    $bind_param .= ':content_id_'.$key;

    $count++;

    if($count < count($displayed_content_ids))
    {
        $bind_param .= ', ';
    }
}
?>
            <div class="alert alert-success">

                <h4>新着動画</h4>
                <div class="row-fluid">

                    <?php
                    if(!empty($new_contents)):
                        foreach($new_contents as $row):

                            $row_count++;

                            $content_id = $row['id'];

                            $modal_id = 'movie_modal_'.$row_count;

                            $image_path = DIR_PATH_IMAGE.$row['image_name'];

                            $mp4_file_path = DIR_PATH_MP4.$row['mp4_file_name'];

                            $ogv_file_path = DIR_PATH_OGV.$row['ogv_file_name'];
                    ?>
                    <ul class="thumbnails">

                        <li class="span12">

                            <div class="thumbnail">
                                <a data-target="#<?php echo $modal_id; ?>" data-toggle="modal" href="#" class="play_count" data-content_id="<?php echo $content_id; ?>">
                                    <img alt="<?php echo $row['title']; ?>" style="width: 240px; height: 150px;" src="<?php echo $image_path; ?>">
                                </a>
                                <div class="caption">
                                    <h5><?php echo $row['title']; ?></h5>

                                    <p>
                                        <a class="btn btn-primary play_count" data-target="#<?php echo $modal_id; ?>" data-toggle="modal" href="#" data-content_id="<?php echo $content_id; ?>">再生</a>

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

                    </ul>

                    <?php
                        endforeach;
                    endif;
                    ?>

                </div>
            </div>

        </div><!--/span-->


        <div class="span9">

            <div class="hero-unit">

                <h2>Welcome!　Fishery and Ocean Technologies!!</h2>
                <h3>目指せ、水産マイスター！　Just do it！</h3>

                <div class="carousel slide" id="myCarousel">

                    <ol class="carousel-indicators">
                        <li class="active" data-slide-to="0" data-target="#myCarousel"></li>
                        <li data-slide-to="1" data-target="#myCarousel" class=""></li>
                        <li data-slide-to="2" data-target="#myCarousel" class=""></li>
                        <li data-slide-to="3" data-target="#myCarousel" class=""></li>
                        <li data-slide-to="4" data-target="#myCarousel" class=""></li>
                        <li data-slide-to="5" data-target="#myCarousel" class=""></li>
                        <li data-slide-to="6" data-target="#myCarousel" class=""></li>
                    </ol>

                    <div class="carousel-inner">

                        <div class="item active">
                            <div class="row-fluid">
                                <img src="<?php echo DIR_PATH_IMAGE.'slider/title.jpg'; ?>" alt="">
                            </div>
                        </div>

                        <div class="item">
                            <div class="row-fluid">
                                <img src="<?php echo DIR_PATH_IMAGE.'slider/yacht.jpg'; ?>" alt="">
                                <div class="carousel-caption">
                                    <h4>YACHT</h4>
                                    <p>風を帆に受け、大海原を走ろう。海を、自然を体で感じ、冒険しよう。</p>
                                </div>
                            </div>
                        </div>

                        <div class="item">
                            <div class="row-fluid">
                                <img src="<?php echo DIR_PATH_IMAGE.'slider/yuhi.jpg'; ?>" alt="">
                                <div class="carousel-caption">
                                    <h4>SPIRIT</h4>
                                    <p>The greatest pleasure in life is doing what people say you cannot do.</p>
                                </div>
                            </div>
                        </div>

                        <div class="item">
                            <div class="row-fluid">
                                <img src="<?php echo DIR_PATH_IMAGE.'slider/bi-ti.jpg'; ?>" alt="">
                                <div class="carousel-caption">
                                    <h4>CONTENT</h4>
                                    <p>水産の技術は多種多様、コンテンツを活用して技術を身につけよう！</p>
                                </div>
                            </div>
                        </div>

                        <div class="item">
                            <div class="row-fluid">
                                <img src="<?php echo DIR_PATH_IMAGE.'slider/cutter.jpg'; ?>" alt="">
                                <div class="carousel-caption">
                                    <h4>CUTTER BOAT</h4>
                                    <p>力漕！櫂を握り、力強く、体重を乗せ、水を掴む。１４名が息を合わせてGo ahead！</p>
                                </div>
                            </div>
                        </div>

                        <div class="item">
                            <div class="row-fluid">
                                <img src="<?php echo DIR_PATH_IMAGE.'slider/maguro.jpg'; ?>" alt="">
                                <div class="carousel-caption">
                                    <h4>FOOD PROCESSING</h4>
                                    <p>食は生活の基本。マグロやカツオを捌いて加工しよう。</p>
                                </div>
                            </div>
                        </div>

                        <div class="item">
                            <div class="row-fluid">
                                <img src="<?php echo DIR_PATH_IMAGE.'slider/eyesplice.jpg'; ?>" alt="">
                                <div class="carousel-caption">
                                    <h4>SPLICE</h4>
                                    <p>ロープを解いて、編み込む。スプライスをしてみよう。</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <a data-slide="prev" href="#myCarousel" class="left carousel-control">&lsaquo;</a>
                    <a data-slide="next" href="#myCarousel" class="right carousel-control">&rsaquo;</a>

                </div>

            </div>




       <div class="alert alert-block">
            <div class="row-fluid">
                <ul class="thumbnails">

                    <li class="span4">
                        <div class="thumbnail">
                            <a href="index.php?menu_id=2&page_id=2">
                                <img alt="300x200" style="width: 300px; height: 200px;" src="<?php echo DIR_PATH_IMAGE.'1_ro-pu/hitoe.jpg'; ?>">
                            </a>
                            <div class="caption">
                                <h3>結索</h3>
                                <p>　結索とはロープワークことです。結びの用途は結節、結合、結着などに分類できます。代表的な結索にもやい結び、巻き結び、８の字結び、本結び、錨結び等があります。</p>
                                <p><a class="btn btn-primary" href="index.php?menu_id=2&page_id=2">動画一覧 &raquo;</a></p>
                            </div>
                        </div>
                    </li>

                    <li class="span4">
                        <div class="thumbnail">
                            <a href="index.php?menu_id=3&page_id=9">
                                <img alt="300x200" style="width: 300px; height: 200px;" src="<?php echo DIR_PATH_IMAGE.'2_splices/eyesplices.jpg'; ?>">
                            </a>
                            <div class="caption">
                                <h3>アイスプライス</h3>
                                <p>　スプライスとは、ロープの端に輪（アイ）を作ったり、端止めなどの端末処理の技術です。 ロープを構成しているストランドを、互いに挟み込んだり組み合わせたりして編み込みます。  </p>
                                <p><a class="btn btn-primary" href="index.php?menu_id=3&page_id=9">動画一覧  &raquo;</a></p>
                            </div>
                        </div>
                    </li>

                    <li class="span4">
                        <div class="thumbnail">
                            <a href="index.php?menu_id=4&page_id=14">
                                <img alt="300x200" style="width: 300px; height: 200px;"  src="<?php echo DIR_PATH_IMAGE.'3_henmou/kaeru.jpg'; ?>" >
                            </a>
                            <div class="caption">
                                <h3>編網</h3>
                                <p>　漁網の編み方を学習します。アバリと目板を使って、本目結びと蛙又結びの２つの方法によって網を編みます。タモ網やハンモックの製作にも応用できます。水産科高校生必須の技術です。 </p>
                                <p><a class="btn btn-primary" href="index.php?menu_id=4&page_id=14">動画一覧 &raquo;</a></p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
       </div>

       <div class="alert alert-block">
            <div class="row-fluid">
                <ul class="thumbnails">
                    <li class="span4">
                        <div class="thumbnail">
                            <a href="index.php?menu_id=8&page_id=25">
                                <img alt="300x200" style="width: 300px; height: 200px;" src="<?php echo DIR_PATH_IMAGE.'7_shokuhin/magurokaitai.jpg'; ?>">
                            </a>
                            <div class="caption">
                                <h3>食品加工</h3>
                                <p>　食品は加工、調味、保存の知識や技術によって成り立っています。原料の特性を理解し、加工し、製品化します。生活を支え、豊かにしてくれる食品の製造技術を学びます。</p>
                                <p><a class="btn btn-primary" href="index.php?menu_id=8&page_id=25">動画一覧 &raquo;</a></p>
                            </div>
                        </div>
                    </li>

                    <li class="span4">
                        <div class="thumbnail">
                            <a href="index.php?menu_id=5&page_id=18">
                                <img alt="300x200" style="width: 300px; height: 200px;" src="<?php echo DIR_PATH_IMAGE.'4_mokei/sabani.jpg'; ?>">
                            </a>
                            <div class="caption">
                                <h3>模型製作</h3>
                                <p>　サバニ模型製作、ミニチュアサーフボード製作を通して船体や機材の構造を知り、製作技術を学びます。とくにサバニ模型製作では、木造船における木工技術を学びます。</p>
                                <p><a class="btn btn-primary" href="index.php?menu_id=5&page_id=18">動画一覧  &raquo;</a></p>
                            </div>
                        </div>
                    </li>

                    <li class="span4">
                        <div class="thumbnail">
                            <a href="index.php?menu_id=6&page_id=21">
                                <img alt="300x200" style="width: 300px; height: 200px;"  src="<?php echo DIR_PATH_IMAGE.'5_gyogu/hariegi.jpg'; ?>">
                            </a>
                            <div class="caption">
                                <h3>漁具製作</h3>
                                <p>　漁具は網漁具・釣り漁具・雑漁具に大別されます。実習では餌木製作、ルアー製作、仕掛け作りなどの技術を学びます。製作後は海で使ってみましょう。オリジナル漁具を製作してみましょう。 </p>
                                <p><a class="btn btn-primary" href="index.php?menu_id=6&page_id=21">動画一覧 &raquo;</a></p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>


        </div>
    </div>
 </div>