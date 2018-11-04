<?php

header('Content-Type: text/html; charset=UTF-8');//文字コードの指定

//データベースへの接続
$dsn = 'mysql:dbname=tt_282_99sv_coco_com;host=localhost';
$user = 'tt-282.99sv-coco';
$password = 'kE85t4N2';		//ユーザー名とパスワードをコンストラクタの2番目と3番目の引数
$pdo = new PDO($dsn,$user,$password);

//仮登録用のstudentテーブルを作成する

$sql_C ="CREATE TABLE student2018" 
."("
."id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
."name VARCHAR(50) NOT NULL,"
."class INT NOT NULL,"
."number INT NOT NULL,"
."mail VARCHAR(50) NOT NULL,"
."password VARCHAR(128) NOT NULL,"
."flag TINYINT(1) NOT NULL DEFAULT 1"
.");";
$stmt_C = $pdo->query($sql_C);	

//pre_studentテーブルが作成されたか確認
$sql_S ='SHOW TABLES';
$result_S = $pdo -> query($sql_S);	
echo "【データベース内のテーブル一覧】";
echo '<br>';
foreach ($result_S as $row_S){		//投稿を一つずつ変数$rowに入れ、投稿番号を表示
			echo $row_S[0];
			echo '<br>';
}
echo "<hr>";


//pre_studentテーブルの中身を確認
$sql_SC ='SHOW CREATE TABLE student2018';		//現在作成されているテーブルの一覧を取得する
$result_SC = $pdo -> query($sql_SC);
echo "【studentテーブルの中身】";
echo '<br>';
foreach ($result_SC as $row_SC){
			print_r($row_SC);
}
echo "<hr>";

?>