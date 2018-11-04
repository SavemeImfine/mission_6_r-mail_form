<?php
session_start();
 
header("Content-type: text/html; charset=utf-8");
 
//クロスサイトリクエストフォージェリ（CSRF）対策のトークン判定
if ($_POST['token'] != $_SESSION['token']){
	echo "不正アクセスの可能性があります。";
	exit();
}
 
//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');
 
//データベースへの接続
	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';		//ユーザー名とパスワードをコンストラクタの2番目と3番目の引数
	$pdo = new PDO($dsn,$user,$password);

	//必ず例外処理（try,catch）を行う
	try{
		$dbh = new PDO($dsn, $user, $password);		//例外が発生する恐れがあるコード
		//new演算子を使用してPDOクラスのインスタンスを作成。作成したインスタンスを$dbhという変数に代入。
	}catch (PDOException $e){//例外発生時の処理
	    	print('Error:'.$e->getMessage());		//エラーメッセージの表示
	    	die();		//()のなかのメッセージを出力し、現在のスクリプトを終了する
	}

 
//エラーメッセージの初期化
$errors = array();
 
if(empty($_POST)) {
	header("Location: mission_6_r-mail_form.php");
	exit();
}else{
 	$name = $_SESSION['name'];
 	$class = $_SESSION['class'];
 	$number = $_SESSION['number'];
	$mail = $_SESSION['mail'];
	//パスワードのハッシュ化
	$pass=$_SESSION['password'];
 
	//ここでデータベースに登録する
	try{
		//例外処理を投げる（スロー）ようにする
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
		//トランザクション開始
		$dbh->beginTransaction();
	
		//studentテーブルに本登録する
		$statement = $dbh->prepare("INSERT INTO student2018 (name,class,number,mail,password) VALUES (:name,:class,:number,:mail,:password)");
		//プレースホルダへ実際の値を設定する
		$statement->bindValue(':name', $name, PDO::PARAM_STR);
		$statement->bindValue(':class', $class, PDO::PARAM_STR);
		$statement->bindValue(':number', $number, PDO::PARAM_STR);
		$statement->bindValue(':mail', $mail, PDO::PARAM_STR);
		$statement->bindValue(':password', $pass, PDO::PARAM_STR);
		$statement->execute();
		
		//pre_studentのflagを1にする
		$statement = $dbh->prepare("UPDATE pre_student SET flag=1 WHERE mail=(:mail)");
		//プレースホルダへ実際の値を設定する
		$statement->bindValue(':mail', $mail, PDO::PARAM_STR);
		$statement->execute();
	
		// トランザクション完了（コミット）
		$dbh->commit();
		
		//データベース接続切断
		$dbh = null;
	
		//セッション変数を全て解除
		$_SESSION = array();
	
 		//セッションを破棄する
 		session_destroy();
 	
 		/*
 		登録完了のメールを送信
 		*/
	
	}catch (PDOException $e){
		//トランザクション取り消し（ロールバック）
		$dbh->rollBack();
		$errors = array('error'=>'もう一度やりなおして下さい。');
		print('Error:'.$e->getMessage());
	};
};
 
?>
 
<!DOCTYPE html>
<html>
<head>
<title>会員登録完了画面</title>
<meta charset="utf-8">
</head>
<body bgcolor="#fffff0">

<img src="team orrange hs.jpg" width="500" >

<font face="arial" color="#006400">

<?php if (count($errors) === 0): ?>
<h3>会員登録完了画面</h3>
 
<p>登録完了しました！！ログイン画面からどうぞ。</p>
<p><a href="">ログイン画面（未リンク）</a></p>
 
<?php else: ?>
 
<?php foreach($errors as $value):?>
<?echo "<p>".$value."</p>";?>	<!-- 発生したエラーの表示 -->	
<?php endforeach;?>
<?php endif;?>
 
</body>
</html>
