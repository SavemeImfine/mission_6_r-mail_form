<?php
session_start();
header("Content-type: text/html; charset=utf-8");
 
//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

//クロスサイトリクエストフォージェリ（CSRF）対策のトークン判定
if ($_POST['token'] != $_SESSION['token']){		//送信されたトークンと生成されたトークンが一致した場合
	echo "不正アクセスの可能性があります。";			//警告文の表示
	exit();		//セッションを終える
};

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
	header("Location: mission_6_login_form.php");
	exit();
}else{
	
	
	//POSTされたデータを各変数に入れる
 	$login_mail = $_POST['login_mail'];
	$login_pass =$_POST['login_pass'];
	
	if(empty($login_mail)){
		$errors = array('login_mail'=>'メールアドレスが入力されていません。');
	}elseif(empty($login_pass)){
		$errors = array('login_pass'=>'パスワードが入力されていません。');
	}else{
		//テーブルからメールアドレスとパスワードを取り出す
		$login_sql= 'SELECT * FROM student2018';//テーブルの中身を取り出す
		$login_results = $pdo -> query($login_sql);//sql文を実行
				foreach ($login_results as $login_row){
							if(!($login_row['mail']==$login_mail) or !($login_row['password']==$login_pass)){//もしメールアドレスとパスワードが一致しなかったら
								$errors = array('login'=>'メールアドレスまたはパスワードが違います');
							};
				};
	};
	
};//何かしら送られた時終了


 
?>
