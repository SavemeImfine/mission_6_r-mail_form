<?php
session_start();
 
header('Content-type: text/html; charset=utf-8');
 
//クロスサイトリクエストフォージェリ（CSRF）対策のトークン判定
if ($_POST['token'] != $_SESSION['token']){		//送信されたトークンと生成されたトークンが一致した場合
	echo "不正アクセスの可能性があります。";			//警告文の表示
	exit();		//セッションを終える
}
 
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
 	//$errorsという配列を作っている。この先、エラーが起きた場合、この配列にエラー内容を要素として入れていく。
$errors = array();
 
if(empty($_POST)) { //何も受信されなかった時
	header('Location: mission_6_r-mail_form.php');//mission_6_r-mail_form.php(メールフォームのページ)へ飛ぶ。
	exit();	//処理を終了させてそれ以後の処理を行わない
}else{
	//echo "メールチェックページへ移動成功";
	//echo "<br>";
	if(empty($_POST['mail'])){	//メールアドレスが空欄の場合
		$errors = array('mail'=>'メールアドレスが入力されていません。');//エラーメッセージの定義と配列へ要素として追加。
	}else{		//メールアドレスが入力されている場合
		$mail=$_POST['mail'];
		if(filter_var($mail,FILTER_VALIDATE_EMAIL)==TRUE){	//preg_match — 正規表現によるマッチングを行う
				//echo "正しいメールアドレスです。";
				//echo "<br>";
		}else{
				$errors = array('mail_check'=>'メールアドレスの形式が正しくありません。');
		};//マッチングの終了
	};//メールフォームに入力されていた場合の操作終了
	
	//var_dump($errors);//エラー内容の表示
	//echo "<br>";
		
	$miss=count($errors);
	//echo $miss."<br>";
	
	//エラーが一つもない場合にpre_studentテーブルに書き込みと、メールの送信
	if ($miss == 0){		//エラーが存在しないとき
	
		//URLに含めるトークンの作成
		$urltoken = hash('sha256',uniqid(rand(),1));//uniqid(rand())...マイクロ秒単位の現在時刻ベースの乱数。１は、$more_entropyをtrueにした.ユニーク性高くなる
		
		//会員登録ページのフォームのURL
		$url = "http://tt-282.99sv-coco.com/mission_6_r_form.php"."?urltoken=".$urltoken;
		//echo $url;//URLを表示して確認
		//echo "<br>";
		//echo $mail."<br>";
				
		//テーブルへの書き込み
		try{//テーブルへの書き込み開始
			//例外処理を投げるようにする
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
			$statement = $dbh->prepare("INSERT INTO pre_student (urltoken,mail,date) VALUES (:urltoken,:mail,now() )");
		
			//プレースホルダへ実際の値を設定する
			$statement->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
			$statement->bindValue(':mail', $mail, PDO::PARAM_STR);
			$statement->execute();
		
			//データベース接続切断
			$dbh = null;	
	
		}catch (PDOException $e){
			print('Error:'.$e->getMessage());
			die();
		}//テーブルへの書き込み終了
		
		//メールの内容の作成
		//$mail=宛先（フォームに入力されたメアド）
		$subject = "＊重要＊ 【TEAM ORRANGE HIGH SCHOOL長期休暇学習管理システム】本登録のご案内";	//件名
		$message = "この度は、長期休暇学習管理システムへのメール登録をしていただき誠にありがとうございます。\r\n現在は仮登録の状態です。\r\n以下のURLに24時間以内にアクセスし、本登録を行ってください。↓\r\n".$url;	//メッセージ内容
		$returnmail = 'outro_wings@yahoo.co.jp';	//送信側のメアド(エラーなどによりメールが送信されなかった時に通知が行くメアド)
		$return_path= "-f $returnmail";
		$name = "TEAM ORRANGE HIGH SCHOOL";	//送信側のなまえ
		//Fromヘッダーを作成
		$header = 'From: '. mb_encode_mimeheader($name). '<'. $returnmail. '>';
		
		//文字コードの指定
		mb_language('ja');		//言語を取得し、e-mailメッセージのエンコー ディングとして使用されます。今回は日本語
		mb_internal_encoding('UTF-8');//内部エンコーディングの設定

 		//メールの送信
		if(mb_send_mail($mail, $subject, $message, $header, $return_path)){//メールを送信したら
			//echo "<hr>"."メールが送信されました";
			
			//セッション変数を全て解除.セッションを利用すれば、複数ページに渡ってデータを受け渡すことができます
			$_SESSION = array();

 			//セッションを破棄する
 			session_destroy();

		}else{
			echo "<hr>"."メールの送信に失敗しました"."<hr>";
			$errors = array('mail_error'=>'メールの送信に失敗しました。');
		};
	
	};//エラーが存在しない場合の操作終了

	
};//何かしら(トークン・アドレス)送られてきた場合の操作終了
?>

<!DOCTYPE html>
<html>
<head>
<title>メール確認画面</title>
<meta charset="utf-8">
</head>
<body bgcolor="#fffff0">

<img src="team orrange hs.jpg" width="500" >

<font face="arial" color="#006400">
<h3>メール確認画面</h3>

 
<?php if (count($errors) === 0):?><!-- エラーが発生しなかった場合 -->

<font face="arial"> 
<p>仮登録が完了しました。</p><!--送信されたというメッセージを表示  -->
 <p><?=$mail?>宛てに、本登録のご案内メールを送信しました。</p>
<p>２４時間以内に本登録を完了してください。</p>
 
<?php else: ?><!-- エラーが発生した場合 -->
 
<?php foreach($errors as $value):?>
<?echo "<p>".$value."</p>";?>	<!-- 発生したエラーの表示 -->	
<?php endforeach;?>
<?php endif;?>

<hr size="2" width="700" align="left" color="#8b4513" noshade   >

<input type="button" value="戻る" style="WIDTH: 200px; HEIGHT: 50px" onClick="history.back()"><!-- それまでにブラウザで表示した履歴の一つ前のページへ戻ります。 -->
</font>
</body>
</html> 
