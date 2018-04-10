<?php
  session_start();
  // var_dump($_SESSION['id']);
  //requireの名前はわかりやすくつける！
  require('dbconnect.php');
  require('signin_check.php');

  //
  if (!isset($_SESSION['id'])) {
    header("Location: signin.php");
    exit();
  }


  //ナビバーに表示するためログインユーザーの情報を取得
  $sql = 'SELECT * FROM `users` WHERE `id`='.$_SESSION['id'];
  $stmt = $dbh->prepare($sql);
  $stmt->execute();
  $login_user = $stmt->fetch(PDO::FETCH_ASSOC);
  //つぶやきを保存
  if (isset($_POST) && !empty($_POST)){
    $sql = 'INSERT INTO `feeds` SET `feed`=?, `user_id`=?, `created`=NOW()';
    $data = array($_POST["feed"],$_SESSION['id']);
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
  }
  //timelineの情報を取得
  $sql = 'SELECT `feeds`.*,`users`.`name`,`users`.`img_name` as `profile_image` FROM `feeds` INNER JOIN `users` ON `feeds`.`user_id` = `users`.`id` ORDER BY `feeds`.`updated` DESC';
  $stmt = $dbh->prepare($sql);
  $stmt->execute();
  //表示部分で使用できるようにタイムラインの情報を格納する配列を用意
  $timeline = array();
  while (1) {
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    // テーブル結合以前
    // $rec = array("id"=>1,"feed"=>"つぶやいた内容",,,,"created"=>"2018-03-03","updated"=>"2018-03-03")
    // テーブル結合後
    // $rec = array("id"=>1,"feed"=>"つぶやいた内容",,,,"created"=>"2018-03-03","updated"=>"2018-03-03","name"=>"demotarou","profile_image"=>"20180303010101test.png")

    //テーブル結合後-ログインユーザーのライク状況を表す情報を追加
    // $rec = array("id"=>1,"feed"=>"つぶやいた内容",,,,"created"=>"2018-03-03","updated"=>"2018-03-03","name"=>"demotarou","profile_image"=>"20180303010101test.png","like_flag"=>0)Likeしてない時

    // $rec = array("id"=>1,"feed"=>"つぶやいた内容",,,,"created"=>"2018-03-03","updated"=>"2018-03-03","name"=>"demotarou","profile_image"=>"20180303010101test.png","like_flag"=>1)Likeした時

    if ($rec == false){
      break;
    }

    //ログインユーザーが現在取得feedにlikeしているかどうかを取得
    //$_SESSION["id"]:ログインしているユーザーID
    //$rec["id"]:現在取得したfeedのID
    $like_sql = "SELECT COUNT(*) as `cnt` FROM `likes` WHERE `feed_id` = ? AND `user_id` = ?";
    $like_data = array($rec["id"],$_SESSION["id"]);
    $like_stmt = $dbh->prepare($like_sql);
    $like_stmt->execute($like_data);

    $like_rec = $like_stmt->fetch(PDO::FETCH_ASSOC);

    if($like_rec["cnt"] == 0){
      //Likeしてない
      $rec["like_flag"] = 0;
    }else{
      //Lke済み
      $rec["like_flag"] = 1;
    }

    $timeline[] = $rec;
  }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>Learn SNS</title>
  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="assets/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body style="margin-top: 60px; background: #E4E6EB;">
  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse1" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/">Learn SNS</a>
      </div>
      <div class="collapse navbar-collapse" id="navbar-collapse1">
        <ul class="nav navbar-nav">
          <li class="active"><a href="#">タイムライン</a></li>
          <li><a href="#">ユーザー一覧</a></li>
        </ul>
        <form method="GET" action="" class="navbar-form navbar-left" role="search">
          <div class="form-group">
            <input type="text" name="search_word" class="form-control" placeholder="投稿を検索">
          </div>
          <button type="submit" class="btn btn-default">検索</button>
        </form>
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><img src="user_profile_img/<?php echo $login_user["img_name"]; ?>" width="18" class="img-circle"><?php echo $login_user['name']; ?> <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="#">マイページ</a></li>
              <li><a href="signout.php">サインアウト</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container">
    <div class="row">
      <div class="col-xs-3">
        <ul class="nav nav-pills nav-stacked">
          <li class="active"><a href="timeline.php?feed_select=news">新着順</a></li>
          <li><a href="timeline.php?feed_select=likes">いいね！済み</a></li>
          <!-- <li><a href="timeline.php?feed_select=follows">フォロー</a></li> -->
        </ul>
      </div>
      <div class="col-xs-9">
        <div class="feed_form thumbnail">
          <form method="POST" action="">
            <div class="form-group">
              <textarea name="feed" class="form-control" rows="3" placeholder="Happy Hacking!" style="font-size: 24px;"></textarea><br>
            </div>
            <input type="submit" value="投稿する" class="btn btn-primary">
          </form>
        </div>
          <?php foreach ($timeline as $timeline_each) {
              include("timeline_oneline.php");
          } ?>
        <nav aria-label="Page navigation">
          <ul class="pager">
            <li class="previous disabled"><a href="#"><span aria-hidden="true">&larr;</span> Older</a></li>
            <li class="next"><a href="#">Newer <span aria-hidden="true">&rarr;</span></a></li>
          </ul>
        </nav>
      </div>
    </div>
  </div>
  <script src="assets/js/jquery-3.1.1.js"></script>
  <script src="assets/js/jquery-migrate-1.4.1.js"></script>
  <script src="assets/js/bootstrap.js"></script>
</body>
</html>