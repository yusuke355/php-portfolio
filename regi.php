<?php

require('function.php');

define("MSG01","入力必須です");
define("MSG02","メールアドレスの形式ではありません");
define("MSG03","再入力が合っていません");
define("MSG04","パスワードの形式が正しくありません");
define("MSG05","6文字以上で入力して下さい");
define("MSG06","");

$err_msg = array();

if(!empty($_POST)){

  if(empty($_POST['name'])){
    $err_msg['name'] = MSG01;
  }

  if(empty($_POST['mail'])){
    $err_msg['mail'] = MSG01;
  }

  if(empty($_POST['pass'])){
    $err_msg['pass'] = MSG01;
  }

  if(empty($_POST['re-pass'])){
    $err_msg['re-pass'] = MSG01;
  }

  if(empty($err_msg)){
    $name = $_POST['name'];
    $mail = $_POST['mail'];
    $pass = $_POST['pass'];
    $re_pass = $_POST['re-pass'];


    if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $mail)) {
      $err_msg['mail'] = MSG02;
    }

    if($pass !== $re_pass){
    $err_msg['pass'] = MSG03;
  }

  if(empty($err_msg)){

    if(!preg_match("/^[a-zA-Z0-9]+$/", $pass)){
      $err_msg['pass'] = MSG04;

    }elseif(mb_strlen($pass) < 6 ){
      $err_msg['pass'] = MSG05;
    }

    if(empty($err_msg)){

      try {
          // DBへ接続
          $dbh = dbConnect();
          // SQL文作成
          $sql = 'INSERT INTO users (name,email,pass,create_date) VALUES(:name,:email,:pass,:create_date)';
          $data = array(':name' => $name, ':email' => $mail, ':pass' => password_hash($pass, PASSWORD_DEFAULT),
                        ':create_date' => date('Y-m-d H:i:s'));
          // クエリ実行
          queryPost($dbh, $sql, $data);

          header("Location:mypage.php"); //マイページへ

        } catch (Exception $e) {
          error_log('エラー発生:' . $e->getMessage());
          $err_msg['common'] = MSG07;
        }
    }

  }


}
}



?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Do+Hyeon&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Do+Hyeon&family=M+PLUS+Rounded+1c:wght@500&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/5dbef0193a.js" crossorigin="anonymous"></script>
  <title>Document</title>
</head>
<body>
  <header>
    <h1><a href="index.php">Shoe-Market</a></h1>
    <nav class="top_nav">
      <ul>
        <li><a href="">Shoe-Marketとは</a></li>
        <li><a href="">サービス</a></li>
        <li><a href="">アクセス</a></li>
        <li class="list3"><a class="btn" href="regi.php">新規登録</a></li>
        <li><a class="btn" href="login.php">ログイン</a></li>
      </ul>
    </nav>
  </header>
  <div class="form-wrap">
    <h1>新規登録</h1>
    <form action="" method="post">
      <label for="">名前:
        <div class="err-msg"><?php if(!empty($err_msg['name'])) echo $err_msg['name']; ?></div>
        <input class="form form1" type="text" name="name" value="<?php if(!empty($_POST['name'])) echo $_POST['name']; ?>"><br>
      </label>
      <label for="">メールアドレス:
        <div class="err-msg"><?php if(!empty($err_msg['mail'])) echo $err_msg['mail']; ?></div>
        <input class="form form2" type="text" name="mail" value="<?php if(!empty($_POST['mail'])) echo $_POST['mail']; ?>"><br>
      </label>
      <label for="">パスワード:
        <div class="err-msg"><?php if(!empty($err_msg['pass'])) echo $err_msg['pass']; ?></div>
        <input class="form form3" type="password" name="pass" value="<?php if(!empty($_POST['pass'])) echo $_POST['pass']; ?>"><br>
      </label>
      <label for="">パスワード（再入力）:
        <div class="err-msg"><?php if(!empty($err_msg['re-pass'])) echo $err_msg['re-pass']; ?></div>
        <input class="form form4" type="password" name="re-pass" value="<?php if(!empty($_POST['re-pass'])) echo $_POST['re-pass']; ?>"><br>
      </label>
      <input type="submit" value="送信">
    </form>
  </div>
  <footer>
    ©Shoe-Market.2020.
  </footer>
</body>
</html>
