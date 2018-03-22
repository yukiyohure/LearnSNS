<?php 
session_start();
// var_dump($_SESSION);
echo $_SESSION["register"]["name"];
echo "<br>";
echo $_SESSION["register"]["email"];
echo "<br>";
echo $_SESSION["register"]["password"];
echo "<br>";
echo $_SESSION["register"]["img_name"];
echo "<br>";
 ?>
 <!DOCTYPE html>
 <html lang="ja">
 <head>
 	<meta charset="utf-8">
 	<title></title>
 </head>
 <body>
 <img src="../user_profile_img/<?php echo $_SESSION['register']['img_name']; ?>" width="60px">
 </body>
 </html>