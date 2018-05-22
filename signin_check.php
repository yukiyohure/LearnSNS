<?php 
//ログイン済みかテェックし、未ログインであれば
if (!isset($_SESSION['id'])) {
    header("Location: signin.php");
    exit();
  }


  //ナビバーに表示するためログインユーザーの情報を取得
  $sql = 'SELECT * FROM `users` WHERE `id`='.$_SESSION['id'];
  $stmt = $dbh->prepare($sql);
  $stmt->execute();
  $login_user = $stmt->fetch(PDO::FETCH_ASSOC);
 ?>