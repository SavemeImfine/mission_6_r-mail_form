<?php
session_start();
 
header("Content-type: text/html; charset=utf-8");
 
//クロスサイトリクエストフォージェリ（CSRF）対策のトークン判定
if ($_POST['token'] != $_SESSION['token']){
	echo "不正アクセスの可能性あり";
	exit();
}
 
//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');
 
//前後にある半角全角スペースを削除する関数
function spaceTrim ($str) {
	// 行頭
	$str = preg_replace('/^[ 　]+/u', '', $str);
	// 末尾
	$str = preg_replace('/[ 　]+$/u', '', $str);
	return $str;
}
 
//エラーメッセージの初期化
$errors = array();
 
if(empty($_POST)) {
	header("Location: mission_6_r-mail_form.php");
	exit();
}else{
	//POSTされたデータを各変数に入れる
	$name = $_POST['name'];
 	$class = $_POST['class'];
 	$number = $_POST['no'];
	$password =$_POST['password'];
	//echo $name;
	//echo "<br>";
	//echo $class;
	//echo "<br>";
	//echo $number;
	//echo "<br>";
	//echo $password;
	//echo "<br>";
	
	//前後にある半角全角スペースを削除
	$account = spaceTrim($account);
	$password = spaceTrim($password);
 
	//アカウント入力判定
	if (empty($name)){
		$errors = array('name'=>'名前が入力されていません。');
	};	
	if (empty($class)){
		$errors = array('class'=>'クラスが入力されていません。');
	};
	if (empty($number)){
		$errors = array('number'=>'番号が入力されていません。');
	};
	//パスワード入力判定
	if (empty($password)){
		$errors = array('password'=>'パスワードが入力されていません。');
	}elseif(!preg_match('/^[0-9a-zA-Z]{5,30}$/', $_POST['password'])){
				$errors = array('password_length'=>'パスワードは半角英数字の5文字以上30文字以下で入力して下さい。');
	}else{
		$password_hide = str_repeat('*', strlen($password));
	}
	
}
 
//エラーが無ければセッションに登録
if(count($errors) === 0){
	$_SESSION['name']=$name;
 	$_SESSION['class']=$class;
 	$_SESSION['number']=$number;
	$_SESSION['password']=$password;
}
 
?>
 
<!DOCTYPE html>
<html>
<head>
<title>会員登録確認画面</title>
<meta charset="utf-8">
</head>
<body bgcolor="#fffff0">

<img src="team orrange hs.jpg" width="500" >

<font face="arial" color="#006400">

<h3>会員登録確認画面</h3>
 
<?php if (count($errors) === 0): ?>
 
<form action="mission_6_insert.php" method="post">
 
 <p>名前：<?echo htmlspecialchars($_SESSION['name'], ENT_QUOTES)?></p>
 <p>学籍情報：<?echo htmlspecialchars($_SESSION['class'], ENT_QUOTES)?>組<?echo htmlspecialchars($_SESSION['number'], ENT_QUOTES)?>番</p>
<p>メールアドレス：<?echo htmlspecialchars($_SESSION['mail'], ENT_QUOTES)?></p>
<p>パスワード：<?=$password_hide?></p>
 
<input type="button" value="戻る" onClick="history.back()">
<input type="hidden" name="token" value="<?=$_POST['token']?>">
<input type="submit" value="登録する">
 
</form>
 
<?php else: ?>
 
<?php foreach($errors as $value):?>
<?echo "<p>".$value."</p>";?>	<!-- 発生したエラーの表示 -->	
<?php endforeach;?>
 
<input type="button" value="戻る" onClick="history.back()">
 
<?php endif;?>
 
</body>
</html>