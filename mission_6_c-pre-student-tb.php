<?php

header('Content-Type: text/html; charset=UTF-8');//文字コードの指定

//データベースへの接続
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';		//ユーザー名とパスワードをコンストラクタの2番目と3番目の引数
$pdo = new PDO($dsn,$user,$password);

//仮登録用のpre_studentテーブルを作成する
$sql_C ="CREATE TABLE pre_student"
." ("
."id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
."urltoken VARCHAR(128) NOT NULL,"//urltokineカラムにはURLに含めるトークンが入力されます。
."mail VARCHAR(50) NOT NULL,"
."date DATETIME NOT NULL,"
."flag TINYINT(1) NOT NULL DEFAULT 0"//flagカラムはデフォルトが0の状態で自動入力され、会員登録が完了した時に、値を1に置き換えます。
.");";
$stmt_C = $pdo->query($sql_C);	

//pre_studentテーブルが作成されたか確認
$sql_S ='SHOW TABLES';
$result_S = $pdo -> query($sql_S);		//3つのカラムを持ったテーブル「tbtest」を変数として取り出す(投稿ごとの配列)
echo "【データベース内のテーブル一覧】";
echo '<br>';
foreach ($result_S as $row_S){		//投稿を一つずつ変数$rowに入れ、投稿番号を表示
			echo $row_S[0];
			echo '<br>';
}
echo "<hr>";


//pre_studentテーブルの中身を確認
$sql_SC ='SHOW CREATE TABLE pre_student';		//現在作成されているテーブルの一覧を取得する
$result_SC = $pdo -> query($sql_SC);
echo "【pre_studentテーブルの中身】";
echo '<br>';
foreach ($result_SC as $row_SC){
			print_r($row_SC);
}
echo "<hr>";

?>
