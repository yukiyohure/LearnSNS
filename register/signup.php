<?php 
//エラーの種類を保存しておくエラー変数を定義
$errors = array();

//POST送信されたデータがある場合
if (!empty($_POST)) {
  $name = $_POST["input_name"];
  $email = $_POST["input_email"];
  $password = $_POST["input_password"];

  $count = strlen($password);//変数の中に何文字保存されているか取得する

  //ユーザー名のテェック
  if ($name == '') {
    $errors["name"] = "blank";
  }

  //メールアドレスのテェック
  if ($email=='') {
    $errors["email"] = "blank";
  }

  //パスワードのテェック
  if ($password == '') {
    $errors["password"] = "blank";
  }else if($count < 4 || $count > 16){
    //長さの修正を促すためエラー変数にエラーの種類を保存
    $errors["password"] = "length";
  }

  //画像のテェック
  $file_name = $_FILES["input_img_name"]["name"];
  // var_dump($file_name);
  if (!empty($file_name)) {
    //拡張子テェックの処理
      $file_type = substr($file_name,-3);//substr:文字列の指定位置から何文字分か切り取る関数。＋は前から。ーは後ろから。
      //hogehoge.pngというファイル名であれば「png」が取得できる。
      $file_type = strtolower($file_type);//大文字が含まれていたら小文字に変換
      if (($file_type != 'png') &&($file_type != 'jpg') && ($file_type != 'gif') ) {
        $errors["img_name"]="type";
      }
    }else{
      //空の時
      $errors["img_name"] = "blank";
    }

}

// var_dump($errors);

 ?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>Learn SNS</title>
  <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="../assets/font-awesome/css/font-awesome.css">
   <link rel="stylesheet" type="text/css" href="../assets/css/style.css"> <!-- 追加 -->

</head>
<body style="margin-top: 60px">
  <div class="container">
    <div class="row">
         <!-- ここから -->
      <div class="col-xs-8 col-xs-offset-2 thumbnail">
        <h2 class="text-center content_header">アカウント作成</h2>
        <form method="POST" action="signup.php" enctype="multipart/form-data">
          <div class="form-group">
            <label for="name">ユーザー名</label>
            <input type="text" name="input_name" class="form-control" id="name" placeholder="山田 太郎">
            <?php if((isset($errors["name"])) && ($errors["name"]=='blank')){ ?>

              <p class="text-danger">ユーザー名を入力してください</p>

            <?php } ?>
          </div>
          <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" name="input_email" class="form-control" id="email" placeholder="example@gmail.com">
            <?php if((isset($errors["email"])) && ($errors["email"]=='blank')){ ?>
              <p class="text-danger">メールアドレスを入力してください</p>
            <?php } ?>
          </div>
          <div class="form-group">
            <label for="password">パスワード</label>
            <input type="password" name="input_password" class="form-control" id="password" placeholder="4 ~ 16文字のパスワード">
            <?php if((isset($errors["password"])) && ($errors["password"]=='blank')){ ?>
            <p class="text-danger">パスワードを入力してください</p>
            <?php } ?>
            <?php if((isset($errors["password"])) && ($errors["password"]=='length')){ ?>
            <p class="text-danger">パスワードは４〜16文字で入力してください</p>
            <?php }  ?>
          </div>
          <div class="form-group">
            <label for="img_name">プロフィール画像</label>
            <input type="file" name="input_img_name" id="img_name">
            <?php if((isset($errors["img_name"])) && ($errors["img_name"]=='blank')){ ?>
            <p class="text-danger">画像を選択してください</p>
            <?php } ?>
            <?php if((isset($errors["img_name"])) && ($errors["img_name"]=='type')){ ?>
            <p class="text-danger">画像の拡張子はpng,jpg,gifのいずれかです</p>
            <?php } ?>
          </div>
          <input type="submit" class="btn btn-default" value="確認">
          <a href="../signin.php" style="float: right; padding-top: 6px;" class="text-success">サインイン</a>
        </form>
      </div>
      <!-- ここまで -->

    </div>
  </div>
  <script src="../assets/js/jquery-3.1.1.js"></script>
  <script src="../assets/js/jquery-migrate-1.4.1.js"></script>
  <script src="../assets/js/bootstrap.js"></script>
</body>
</html>
