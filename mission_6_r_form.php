<?php
session_start();
 
header("Content-type: text/html; charset=utf-8");
 
 
//クロスサイトリクエストフォージェリ（CSRF）対策
$_SESSION['token'] = md5(uniqid(rand(), true));
$token = $_SESSION['token'];
 
 
//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');


//データベースへの接続
	$dsn = 'mysql:dbname=tt_282_99sv_coco_com;host=localhost';
	$user = 'tt-282.99sv-coco';
	$password = 'kE85t4N2';		//ユーザー名とパスワードをコンストラクタの2番目と3番目の引数

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
 
 
if(empty($_GET)) {//urltokenを受け取らなかった場合
	header("Location: mission_6_r-mail_form.php");//メール登録ページへ戻る
	exit();
}else{
	//GETデータを変数に入れる
	$urltoken = $_GET['urltoken'];
	//メール入力判定
	if (empty($urltoken)){//urlトークンが空白
		$errors = array('urltoken'=>'もう一度登録をやりなおして下さい。');
	}else{//urlトークンが空白でない場合受け取った場合
		try{
			//例外処理を投げる（スロー）ようにする
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			//flagが0の未登録者・仮登録日から24時間以内
			$statement = $dbh->prepare("SELECT mail FROM pre_student WHERE urltoken=(:urltoken) AND flag =0 AND date > now() - interval 24 hour");
			$statement->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
			$statement->execute();
			
			//レコード件数取得(上の検索に引っかかった行数)
			$row_count = $statement->rowCount();
			
			//24時間以内に仮登録され、本登録されていないトークンの場合
			if( $row_count ==1){//該当データが１つの場合
				$mail_array = $statement->fetch();
				$mail = $mail_array[mail];
				$_SESSION['mail'] = $mail;
			}else{
				$errors = array('urltoken_timeover'=>'このURLはご利用できません。有効期限が過ぎた等の問題があります。もう一度登録をやりなおして下さい。');
			};//24時間以内に仮登録され、本登録されていないトークンの場合終了
			
			//データベース接続切断
			$dbh = null;
			
		}catch (PDOException $e){
			print('Error:'.$e->getMessage());
			die();
		};
	};//urlトークンが空白でない場合終了
};//urlトークンを受け取った場合終了
 
?>
 
<!DOCTYPE html>
<html>
<head>
<title>会員登録画面</title>
<meta charset="utf-8">
</head>
<body bgcolor="#fffff0">

<img src="team orrange hs.jpg" width="500" >

<font face="arial" color="#006400">

<h3>会員登録画面</h3>
 
<?php if (count($errors) === 0): ?>
 
<form action="mission_6_r_check.php" method="post">
 
<p>名前：<input type="text" name="name"></p>
<p>学籍情報：<input type="text" name="class">組<input type="text" name="no">番</p>
<p>メールアドレス：<?=htmlspecialchars($mail, ENT_QUOTES, 'UTF-8')?></p>
<p>パスワード(半角英数字の5文字以上30文字以下)：<input type="text" name="password"></p>
 
<input type="hidden" name="token" value="<?=$token?>">
<input type="submit" value="確認する">
 
</form>
 
<?php else: ?><!-- エラーが発生した場合 -->
 
<?php foreach($errors as $value):?>
<?echo "<p>".$value."</p>";?>	<!-- 発生したエラーの表示 -->	
<?php endforeach;?>
<?php endif;?>
 
</body>
</html>