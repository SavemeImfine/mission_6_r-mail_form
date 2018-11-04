<?php
header('Content-Type: text/html; charset=UTF-8');//文字コードの指定

//データベースへの接続
	$dsn = 'mysql:dbname=tt_282_99sv_coco_com;host=localhost';
	$user = 'tt-282.99sv-coco';
	$password = 'kE85t4N2';		//ユーザー名とパスワードをコンストラクタの2番目と3番目の引数
	$pdo = new PDO($dsn,$user,$password);

echo "【pre_student】";
echo "<br>";
//ブラウザにコメントを表示(4-1-e)
			$sql = 'SELECT * FROM pre_student ORDER BY id ASC';//指定したテーブル内の中身を投稿番号順に並べ替えて取り出す
			$results = $pdo -> query($sql);//sql5を実行
			foreach ($results as $row){//$rowの中にはテーブルのカラム名が入る
			echo $row['id'].'&emsp;'."<br>";
			echo '&emsp;'."トークン: ".$row['urltoken'].'&emsp;'."<br>";
			echo '&emsp;'."メールアドレス: ".$row['mail'].'&emsp;'."<br>";
			echo '&emsp;'."日時: ".$row['date'].'&emsp;'."<br>";
			echo '&emsp;'."フラッグ: ".$row['flag']."<br>";	
			};
?>