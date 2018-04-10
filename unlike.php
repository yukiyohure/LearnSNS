<?php 
session_start();
require('dbconnect.php');
require('signin_check.php');
//いいね！機能

//誰がどの記事のいいねを取り消したいか認識し、likesテーブルに保存
// var_dump($_REQUEST["feed_id"]);

$sql = 'DELETE FROM `likes` WHERE `user_id`=? AND `feed_id`=?';
//sql文の中に?を使うときは$dataに配列を作ってexecuteの()の中に入れる。

//DELETE文実行
 $data = array($_SESSION["id"],$_REQUEST["feed_id"]);
 $stmt = $dbh -> prepare($sql);
 $stmt -> execute($data);

//いいねされた記事のlikes_count を再計算する

//いいねされた数を取得
	$sql = 'SELECT COUNT(*) as `cnt` FROM `likes` WHERE `feed_id` = ?';
    $data = array($_REQUEST["feed_id"]);
    $stmt = $dbh -> prepare($sql);
    $stmt -> execute($data);

    $rec = $stmt->fetch(PDO::FETCH_ASSOC);

    // var_dump($rec);
//いいねされた記事のlike_count をUpdate
	$sql = 'UPDATE `feeds` SET `like_count` = ? WHERE `id` = ?';

	//UPDATE文実行
	$data = array($rec["cnt"],$_REQUEST["feed_id"]);
	$stmt = $dbh -> prepare($sql);
	$stmt -> execute($data);
//timeline.phpに戻る
header("Location:timeline.php");



 ?>