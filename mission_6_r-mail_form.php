<?php
session_start();
header("Content-type: text/html; charset=utf-8");
 
//CSRF対策①ユーザがフォーム画面にアクセスしてきたらサーバ側でトークンを発行。トークンはセッションIDをハッシュ関数に通してハッシュ値として生成します。
	//uniqid関数は、マイクロ秒単位の現在時刻にもとづいたユニークなIDを生成する関数です。
	//uniqid関数の第2引数に「true」を指定すると、線形合同法を使用して生成された文字列が付加される
	//uniqid関数の第1引数にrand関数を指定すると、さらにランダムな文字列を生成できます。
	//つまり下の行で作成されたのは、rand関数で生成した文字列＋マイクロ秒単位にもとづいた文字列＋エントロピー文字列
	//さらにmd5ハッシュ変換を行っている	
$_SESSION['token'] = md5(uniqid(rand(), true));
$token = $_SESSION['token'];
//ここで作ったトークンはhtmlのhiddenに埋め込まれる
 
//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');
 
?>

<!DOCTYPE html>
<html>
<head>
<title>メール登録画面</title>
<meta charset="utf-8">
</head>
<body bgcolor="#fffff0">

<img src="team orrange hs.jpg" width="500" >

<font face="arial" color="#006400">
<h3>生徒用メール登録画面</h3>
<form action="mission_6_r-mail_check.php" method="post">
 【メールアドレスを入力してください。】<br>
<input type="text" placeholder="メールアドレス" name="mail" size="50"> <br> 
 <!--CSRF対策②トークンをhiddenに埋め込みます。-->
<input type="hidden" name="token" value="<?=$token?>">
<input type="submit" value="登録"> 
</form>
</font>
</body>
</html>