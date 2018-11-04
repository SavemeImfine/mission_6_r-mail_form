<?php
header('Content-Type: text/html; charset=UTF-8');//文字コードの指定

//データベースへの接続
	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';		//ユーザー名とパスワードをコンストラクタの2番目と3番目の引数
	$pdo = new PDO($dsn,$user,$password);

echo "【student2018】";
echo "<br>";
//ブラウザにコメントを表示(4-1-e)
			$sql = 'SELECT * FROM student2018 ORDER BY id ASC';//指定したテーブル内の中身を投稿番号順に並べ替えて取り出す
			$results = $pdo -> query($sql);//sql5を実行
			foreach ($results as $row){//$rowの中にはテーブルのカラム名が入る
			echo $row['id'].'&emsp;'."<br>";
			echo '&emsp;'."名前: ".$row['name'].'&emsp;'."<br>";
			echo '&emsp;'."学籍情報: ".$row['class']."組".$row['number']."番".'&emsp;'."<br>";
			echo '&emsp;'."メール: ".$row['mail'].'&emsp;'."<br>";
			echo '&emsp;'."パスワード: ".$row['password'].'&emsp;'."<br>";
			echo '&emsp;'."フラッグ: ".$row['flag']."<br>";	
			};
?>
