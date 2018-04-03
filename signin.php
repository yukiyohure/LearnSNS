<?php
    session_start();
    // サインイン処理
//エラーの種類を保存しておくエラー変数を定義
  $errors = array();
  $email = '';
  $password = '';
  //クッキー情報の存在をチェックし、あれば、POST送信されてきたように$_POST変数へ代入
  if (isset($_COOKIE['email']) && !empty($_COOKIE['email'])){
    $_POST["input_email"] = $_COOKIE['email'];
    $_POST["input_password"] = $_COOKIE['password'];
    $_POST["save"] = "on";
  }
  //POST送信されたデータがある場合
  if (!empty($_POST)){
    $email = $_POST["input_email"];
    $password = $_POST["input_password"];
    //Emailのチェック
    if ($email == ''){
      $errors["email"] = "blank";
    }
    //パスワードのチェック
    if ($password == ''){
      $errors["password"] = "blank";
    }
    if (empty($errors)){
      // emailでデータベースからデータを取得
      //DBに接続
      require("dbconnect.php");
      //入力されたemailと合致するデータの件数を取得
      $sql = 'SELECT * FROM `users` WHERE `email` = ?';
      $data = array($email);
      $stmt = $dbh->prepare($sql);
      $stmt->execute($data);
      $rec = $stmt->fetch(PDO::FETCH_ASSOC);
      // var_dump($rec);
      if ($rec == false){
        $errors['siginin'] = 'failed';
      }else{
        // var_dump(password_verify($password,$rec['password']));
        
        if (password_verify($password,$rec['password'])){
          //認証成功
          //SESSION変数にIDを保存
          $_SESSION['id'] = $rec['id'];
          //自動ログインが指示されていたら、クッキーにログイン情報を保存
          if ($_POST["save"] == "on"){
            //time() 現在時間を1970/01/01 0:00:00から秒数で表した数字
            //2週間後を有効期限に設定している
            setcookie('email',$email,time() + 60*60*24*14);
            setcookie('password',$password,time() + 60*60*24*14);
          }
          //timeline.phpに移動
          header("Location: timeline.php");
          exit();
        }else{
          $errors['siginin'] = 'failed';
        }
      }
    }
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
<body style="margin-top: 60px">
  <div class="container">
    <div class="row">
      <div class="col-xs-8 col-xs-offset-2 thumbnail">
        <h2 class="text-center content_header">サインイン</h2>
        <form method="POST" action="" enctype="multipart/form-data">
          <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" name="input_email" class="form-control" id="email" placeholder="example@gmail.com">
            <?php if ((isset($errors["email"])) && ($errors["email"] == 'blank')) { ?>
              <p class="text-danger">メールアドレスを入力してください</p>
            <?php } ?>
          </div>
          <div class="form-group">
            <label for="password">パスワード</label>
            <input type="password" name="input_password" class="form-control" id="password" placeholder="4 ~ 16文字のパスワード">
            <?php if ((isset($errors["password"])) && ($errors["password"] == 'blank')) { ?>
              <p class="text-danger">パスワードを入力してください</p>
            <?php } ?>
            <?php if ((isset($errors["siginin"])) && ($errors["siginin"] == 'failed')) { ?>
              <p class="text-danger">ログインに失敗しました。入力情報を確認してください</p>
            <?php } ?>
          </div>
          <div class="form-group">
            <label for="save">自動サインイン</label>
            <input type="checkbox" name="save" value="on" checked>
          </div>
          <input type="submit" class="btn btn-info" value="サインイン">
        </form>
      </div>
    </div>
  </div>
  <script src="assets/js/jquery-3.1.1.js"></script>
  <script src="assets/js/jquery-migrate-1.4.1.js"></script>
  <script src="assets/js/bootstrap.js"></script>
</body>
</html>