<?php
require_once('funcs.php');

//1.  DB接続します
$pdo = db_conn();
// try {
//     //ID:'root', Password: xamppは 空白 ''
//     $pdo = new PDO('mysql:dbname=gs_db_class;charset=utf8;host=localhost','root','');
//   } catch (PDOException $e) {
//     exit('DBConnectError:'.$e->getMessage());
//   }

//２．データ取得SQL作成
$stmt = $pdo->prepare("SELECT * FROM flashcard;");
$status = $stmt->execute();

//３．データ表示
$view ="";
if ($status === false) {
    //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);

} else {
      // 表のヘッダー
      $view .= "<table class='listTable'>";
      $view .= "<tr>";
      $view .= "<th>ID</th>";
      $view .= "<th>単語</th>";
      $view .= "<th>品詞</th>";
      $view .= "<th>意味</th>";
      $view .= "<th>例文</th>";
      $view .= "</tr>";

  //Selectデータの数だけ自動でループしてくれる
  //FETCH_ASSOC=http://php.net/manual/ja/pdostatement.fetch.php
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
        $view .= "<tr>";
        $view .= "<td>" . h($result['id']) . "</td>";
        $view .= "<td>" . h($result['word']) . "</td>";
        $view .= "<td>" . h($result['type']) . "</td>";
        $view .= "<td>" . h($result['meaning']) . "</td>";
        $view .= "<td>" . h($result['phrase']) . "</td>";
        $view .= "</tr>";
  }
  $view .= "</table>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  
  
<div id="register-screen" class="screen">
<div id="background"></div>
<div><h1>登録した単語の一覧です！</h1></div>
    <div class="list-container">
        <?= $view ?>
    </div>

    <div class="btn_03container">
<a href="flashcard.php" class="btn_03">単語帳を開く！</a>
<a href="index.php" class="btn_03">単語の登録画面に戻る！</a>
</div>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="module" src="js/list.js"></script>
</body>
</html>