<?php header("Content-Type:text/html;charset=utf-8"); ?>
<?php 
//error_reporting(E_ALL | E_STRICT);
##-----------------------------------------------------------------------------------------------------------------##
#
#  PHPメールプログラム【Mail05】　ファイル添付版　要PHP5以上
#　改造や改変は自己責任で行ってください。
#	
#  今のところ特に問題点はありませんが、不具合等がありましたら下記までご連絡ください。
#  MailAddress: info@php-factory.net
#  name: K.Numata
#  HP: http://www.php-factory.net/
#
#  重要！！サイトでチェックボックスを使用する場合のみですが。。。
#  チェックボックスを使用する場合はinputタグに記述するname属性の値を必ず配列の形にしてください。
#  例　name="当サイトをしったきっかけ[]"  として下さい。
#  nameの値の最後に[と]を付ける。じゃないと複数の値を取得できません！
#
##-----------------------------------------------------------------------------------------------------------------##
if (version_compare(PHP_VERSION, '5.1.0', '>=')) {//PHP5.1.0以上の場合のみタイムゾーンを定義
	date_default_timezone_set('Asia/Tokyo');//タイムゾーンの設定（日本以外の場合には適宜設定ください）
}

/*-------------------------------------------------------------------------------------------------------------------
* ★以下設定時の注意点　
* ・値（=の後）は数字以外の文字列（一部を除く）はダブルクオーテーション「"」、または「'」で囲んでいます。
* ・これをを外したり削除したりしないでください。後ろのセミコロン「;」も削除しないください。
* ・また先頭に「$」が付いた文字列は変更しないでください。数字の1または0で設定しているものは必ず半角数字で設定下さい。
* ・メールアドレスのname属性の値が「Email」ではない場合、以下必須設定箇所の「$Email」の値も変更下さい。
* ・name属性の値に半角スペースは使用できません。
*以上のことを間違えてしまうとプログラムが動作しなくなりますので注意下さい。
-------------------------------------------------------------------------------------------------------------------*/


//---------------------------　必須設定　必ず設定してください　-----------------------

//サイトのトップページのURL　※デフォルトでは送信完了後に「トップページへ戻る」ボタンが表示されますので
$site_top = "https://gh-check.com/v3_confirm/";

// 管理者メールアドレス ※メールを受け取るメールアドレス(複数指定する場合は「,」で区切ってください 例 $to = "aa@aa.aa,bb@bb.bb";)
$to = "register@g-hill.jp";

//フォームのメールアドレス入力箇所のname属性の値（name="○○"　の○○部分）
$Email = "メールアドレス";

/*------------------------------------------------------------------------------------------------
以下スパム防止のための設定　
※有効にするにはこのファイルとフォームページが同一ドメイン内にある必要があります
------------------------------------------------------------------------------------------------*/

//スパム防止のためのリファラチェック（フォームページが同一ドメインであるかどうかのチェック）(する=1, しない=0)
$Referer_check = 0;

//リファラチェックを「する」場合のドメイン ※以下例を参考に設置するサイトのドメインを指定して下さい。
$Referer_check_domain = "php-factory.net";

//---------------------------　必須設定　ここまで　------------------------------------


//---------------------- 任意設定　以下は必要に応じて設定してください ------------------------


// 管理者宛のメールで差出人を送信者のメールアドレスにする(する=1, しない=0)
// する場合は、メール入力欄のname属性の値を「$Email」で指定した値にしてください。
//メーラーなどで返信する場合に便利なので「する」がおすすめです。
$userMail = 1;

// Bccで送るメールアドレス(複数指定する場合は「,」で区切ってください 例 $BccMail = "aa@aa.aa,bb@bb.bb";)
$BccMail = "";

// 管理者宛に送信されるメールのタイトル（件名）
$subject = "【v3テストメール】管理者宛";

// 送信確認画面の表示(する=1, しない=0)
$confirmDsp = 1;

// 送信完了後に自動的に指定のページ(サンクスページなど)に移動する(する=1, しない=0)
// CV率を解析したい場合などはサンクスページを別途用意し、URLをこの下の項目で指定してください。
// 0にすると、デフォルトの送信完了画面が表示されます。
$jumpPage = 1;

// 送信完了後に表示するページURL（上記で1を設定した場合のみ）※httpから始まるURLで指定ください。
$thanksPage = "https://gh-check.com/v3_confirm/thanks.html";

// 必須入力項目を設定する(する=1, しない=0)
$requireCheck = 1;

/* 必須入力項目(入力フォームで指定したname属性の値を指定してください。（上記で1を設定した場合のみ）
値はシングルクォーテーションで囲み、複数の場合はカンマで区切ってください。フォーム側と順番を合わせると良いです。 
配列の形「name="○○[]"」の場合には必ず後ろの[]を取ったものを指定して下さい。*/
$require = array('お名前（カタカナ）','お電話番号','メールアドレス');


//----------------------------------------------------------------------
//  自動返信メール設定(START)
//----------------------------------------------------------------------

// 差出人に送信内容確認メール（自動返信メール）を送る(送る=1, 送らない=0)
// 送る場合は、フォーム側のメール入力欄のname属性の値が上記「$Email」で指定した値と同じである必要があります
$remail = 1;

//自動返信メールの送信者欄に表示される名前　※あなたの名前や会社名など（もし自動返信メールの送信者名が文字化けする場合ここは空にしてください）
$refrom_name = "v3のテスト";

// 差出人に送信確認メールを送る場合のメールのタイトル（上記で1を設定した場合のみ）
$re_subject = "【v3テストメール】送信者宛";

//フォーム側の「名前」箇所のname属性の値　※自動返信メールの「○○様」の表示で使用します。
//指定しない、または存在しない場合は、○○様と表示されないだけです。あえて無効にしてもOK
$dsp_name = 'お名前（カタカナ）';

//自動返信メールの冒頭の文言 ※日本語部分のみ変更可
$remail_text = <<< TEXT

お問い合わせいただきありがとうございました。
確認後、ご返信致しますので今しばらくお待ちください。

送信内容は以下になります。

TEXT;


//自動返信メールに署名（フッター）を表示(する=1, しない=0)※管理者宛にも表示されます。
$mailFooterDsp = 0;

//上記で「1」を選択時に表示する署名（フッター）（FOOTER～FOOTER;の間に記述してください）
$mailSignature = <<< FOOTER

＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
株式会社○○○○　佐藤太郎
〒150-XXXX 東京都○○区○○ 　○○ビル○F　
TEL：03- XXXX - XXXX 　FAX：03- XXXX - XXXX
携帯：090- XXXX - XXXX 　
E-mail:xxxx@xxxx.com
URL: http://www.php-factory.net/
＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝

FOOTER;


//----------------------------------------------------------------------
//  自動返信メール設定(END)
//----------------------------------------------------------------------

//メールアドレスの形式チェックを行うかどうか。(する=1, しない=0)
//※デフォルトは「する」。特に理由がなければ変更しないで下さい。メール入力欄のname属性の値が上記「$Email」で指定した値である必要があります。
$mail_check = 1;

//全角英数字→半角変換を行うかどうか。(する=1, しない=0)
$hankaku = 0;

//全角英数字→半角変換を行う項目のname属性の値（name="○○"の「○○」部分）
//※複数の場合にはカンマで区切って下さい。（上記で「1」を指定した場合のみ有効）
//配列の形「name="○○[]"」の場合には必ず後ろの[]を取ったものを指定して下さい。
$hankaku_array = array('電話番号');



//----------------------------------------------------------------------
//  添付ファイル処理用設定(BEGIN)
//----------------------------------------------------------------------
//ファイル添付機能を使用する場合は一時ファイルを保存する必要があるため確認画面の表示が必須になります。
$confirmDsp = 1;//確認画面を表示 ※変更不可

/* ----- 重要 ------*/
//ファイルアップ部分のnameの値は必ず配列の形　例　upfile[]　としてください。※添付ファイルが1つでも
//添付ファイルは複数も可能です。

//例1 添付ファイルが1つの場合　
//添付ファイル <input type="file" name="upfile[]" />

//例2 添付ファイルが複数の場合　
//添付ファイル1：<input type="file" name="upfile[]" /> 添付ファイル2：<input type="file" name="upfile[]" />



//添付ファイルのMAXファイルサイズ　※単位バイト　デフォルトは5MB（ただしサーバーのphp.iniの設定による）
$maxImgSize = 5024000;

//添付ファイル一時保存用ディレクトリ ※書き込み可能なパーミッション（777等※サーバによる）にしてください
$tmp_dir_name = './tmp/';

//添付許可ファイル（拡張子）
//※大文字、小文字は区別されません（同じ扱い）のでここには小文字だけでOKです（拡張子を大文字で送信してもマッチします）
$permission_file = array('jpg','jpeg','gif','png','pdf','txt','xls','xlsx','zip','lzh','doc');

//フォームのファイル添付箇所のname属性の値 <input type="file" name="upfile[]" />の「upfile」部
$upfile_key = 'upfile';

//サーバー上の一時ファイルを削除する(する=1, しない=0)　※バックアップ目的で保存させておきたい場合など
//添付ファイルは確認画面表示時にtmpディレクトリに一旦保存されますが、それを送信時に削除するかどうか。（残す場合サーバー容量に余裕がある場合のみ推奨）
//もちろん手動での削除も可能です。
$tempFileDel = 1;//デフォルトは削除する

//確認画面→戻る→確認画面のページ遷移では最初の一時ファイルはサーバ上に残りますが、1時間後以降の最初の送信時に自動で削除されます。


//メールソフトで添付ファイル名が文字化けする場合には「1」にしてみてください。（ThuderBirdで日本語ファイル名文字化け対策）
//「1」にすると添付ファイル名が0～の連番になります。
$rename = 0;//(0 or 1)


//サーバーのphp.iniの「mail.add_x_header」がONかOFFかチェックを行う(する=1, しない=0)　※PHP5.3以降
//「する」場合、mail.add_x_headerがONの場合確認画面でメッセージが表示されます。
//mail.add_x_headerがONの場合、添付ファイルが正常に添付できない可能性が非常に高いためのチェックです。
//mail.add_x_headerはデフォルトは「OFF」ですが、サーバーによっては稀に「ON」になっているためです。
//mail.add_x_headerがONの場合でも正常に添付できていればこちらは「0」として下さい。メッセージは非表示となります。
$iniAddX = 1;

//----------------------------------------------------------------------
//  添付ファイル処理用設定(END)
//----------------------------------------------------------------------


//------------------------------- 任意設定ここまで ---------------------------------------------


// 以下の変更は知識のある方のみ自己責任でお願いします。


//----------------------------------------------------------------------
//  関数実行、変数初期化
//----------------------------------------------------------------------
$encode = "UTF-8";//このファイルの文字コード定義（変更不可）

if(isset($_GET)) $_GET = sanitize($_GET);//NULLバイト除去//
if(isset($_POST)) $_POST = sanitize($_POST);//NULLバイト除去//
if(isset($_COOKIE)) $_COOKIE = sanitize($_COOKIE);//NULLバイト除去//
if($encode == 'SJIS') $_POST = sjisReplace($_POST,$encode);//Shift-JISの場合に誤変換文字の置換実行
$funcRefererCheck = refererCheck($Referer_check,$Referer_check_domain);//リファラチェック実行

//変数初期化
$sendmail = 0;
$empty_flag = 0;
$post_mail = '';
$errm ='';
$header ='';


//----------------------------------------------------------------------
//  添付ファイル処理(BEGIN)
//----------------------------------------------------------------------

if(isset($_FILES[$upfile_key])){
	$file_count = count($_FILES[$upfile_key]["tmp_name"]);
	for ($i=0;$i<$file_count;$i++) {
	
		if (@is_uploaded_file($_FILES[$upfile_key]["tmp_name"][$i])) {
			if ($_FILES[$upfile_key]["size"][$i] < $maxImgSize) {
				
				//許可拡張子チェック
				$upfile_name_check = '';
				$upfile_name_array[$i] = explode('.',$_FILES[$upfile_key]['name'][$i]);
				$upfile_name_array_extension[$i] = strtolower(end($upfile_name_array[$i]));
				foreach($permission_file as $permission_val){
				  if($upfile_name_array_extension[$i] == $permission_val){
					  $upfile_name_check = 'checkOK';
				  }
				}
				if($upfile_name_check != 'checkOK'){
				  $errm .= "<p class=\"error_messe\">許可されていない拡張子です。</p>\n";
				  $empty_flag = 1;
				}else{
				
					  $temp_file_name[$i] = $_FILES[$upfile_key]["name"][$i];
					  $temp_file_name_array[$i] =  explode('.',$temp_file_name[$i]);
					  
					  if(count($temp_file_name_array[$i]) < 2){
						$errm .= "<p class=\"error_messe\">ファイルに拡張子がありません。</p>\n";
						$empty_flag = 1;
					  }else{
						$extension = end($temp_file_name_array[$i]);
						
						  if(function_exists('uniqid')){
							  if(!file_exists($tmp_dir_name) || !is_writable($tmp_dir_name)){
							  exit("（重大なエラー）添付ファイル一時保存用のディレクトリが無いかパーミッションが正しくありません。{$tmp_dir_name}ディレクトリが存在するか、または{$tmp_dir_name}ディレクトリのパーミッションを書き込み可能（777等※サーバによる）にしてください");	
							  }
						  $upFileName[$i] = uniqid('temp_file_').mt_rand(10000,99999).'.'.$extension;
						  $upFilePath[$i] = $tmp_dir_name.$upFileName[$i];
						  
						  }else{
							  exit('（重大なエラー）添付ﾌｧｲﾙ一時ﾌｧｲﾙ用のﾕﾆｰｸIDを生成するuniqid関数が存在しません。<br>PHPのﾊﾞｰｼﾞｮﾝが極端に低い（PHP4未満）ようです。<br>PHPをﾊﾞｰｼﾞｮﾝｱｯﾌﾟするか配布元に相談ください');	
						  }
						  move_uploaded_file($_FILES[$upfile_key]['tmp_name'][$i],$upFilePath[$i]);
						  @chmod($upFilePath[$i], 0666);
					  }
				}
			}else{
				  $errm .= "<p class=\"error_messe\">ファイルサイズが大きすぎます。</p>\n";
				  $empty_flag = 1;
			}
		}
	}
}
//----------------------------------------------------------------------
//  添付ファイル処理(END)
//----------------------------------------------------------------------

if($requireCheck == 1) {
	$requireResArray = requireCheck($require);//必須チェック実行し返り値を受け取る
	$errm .= $requireResArray['errm'];
	if($requireResArray['empty_flag'] == 1) $empty_flag = $requireResArray['empty_flag'];
}
//メールアドレスチェック
if(empty($errm)){
	foreach($_POST as $key=>$val) {
		if($val == "confirm_submit") $sendmail = 1;
		if($key == $Email) $post_mail = h($val);
		if($key == $Email && $mail_check == 1 && !empty($val)){
			if(!checkMail($val)){
				$errm .= "<p class=\"error_messe\">【".$key."】はメールアドレスの形式が正しくありません。</p>\n";
				$empty_flag = 1;
			}
		}
	}
}
  
if(($confirmDsp == 0 || $sendmail == 1) && $empty_flag != 1){
	
	//差出人に届くメールをセット
	if($remail == 1) {
		$userBody = mailToUser($_POST,$dsp_name,$remail_text,$mailFooterDsp,$mailSignature,$encode);
		$reheader = userHeader($refrom_name,$to,$encode);
		$re_subject = "=?iso-2022-jp?B?".base64_encode(mb_convert_encoding($re_subject,"JIS",$encode))."?=";
	}
	//管理者宛に届くメールをセット
	$adminBody = mailToAdmin($_POST,$subject,$mailFooterDsp,$mailSignature,$encode,$confirmDsp);
	$header = adminHeader($userMail,$post_mail,$BccMail,$to);
	//$subject = "=?iso-2022-jp?B?".base64_encode(mb_convert_encoding($subject,"JIS",$encode))."?=";
	
	//トラバーサルチェック
	if(isset($_POST['upfilePath'])){
		traversalCheck($tmp_dir_name);
	}

	// if(ini_get('safe_mode')) {
	// 	$result = mb_send_mail($to,$subject,$adminBody,$header);
	// }else{
	// 	$result = mb_send_mail($to,$subject,$adminBody,$header,'-f'. $to);
	// }

  // reCAPTCHA 追記
  $secret_key = '6LflXFMqAAAAAFb4-jhcW3PDQaVIxTfZklKc8nPo'; //取得したシークレットキーを入れてください
  // フォームから送信されたトークンを取得
  $recaptcha_token = $_POST['recaptcha_token'];
  // reCAPTCHAをGoogleのAPIで検証
  $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret_key}&response={$recaptcha_token}");
  $responseKeys = json_decode($response, true);

  // reCAPTCHAの検証結果が成功し、スコアが0.5以上の場合はメール送信を続行
  if($responseKeys["success"] && $responseKeys["score"] >= 0.5) {
      //メール送信処理
      if(ini_get('safe_mode')) {
        $result = mb_send_mail($to,$subject,$adminBody,$header);
      }else{
        $result = mb_send_mail($to,$subject,$adminBody,$header,'-f'. $to);
      }

      // 送信者宛メールの送信処理を追加
      if($remail == 1) {
        mail($post_mail,$re_subject,$userBody,$reheader);
      }

      // reCAPTCHA結果とスコアをサンクスページに渡す
      header("Location: https://gh-check.com/v3_confirm/thanks.html?success=1&score=" . $responseKeys["score"]);
      exit;
  } else {
      // reCAPTCHAが失敗した場合、エラーメッセージを表示し、送信を中断
      die('reCAPTCHAの認証に失敗しました。もう一度やり直してください。');
  }
  // reCAPTCHA 追記ここまで
	
	//サーバ上の一時ファイルを削除
	$dir = rtrim($tmp_dir_name,'/');
	deleteFile($dir,$tempFileDel);

  // if($remail == 1) mail($post_mail,$re_subject,$userBody,$reheader);
}
else if($confirmDsp == 1){ 

/*　▼▼▼送信確認画面のレイアウト※編集可　オリジナルのデザインも適用可能▼▼▼　*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>確認画面</title>
<style type="text/css">
/* 自由に編集下さい */
#formWrap {
	width: 80%;
  margin: 0 auto;
	color:#555;
	line-height:120%;
	font-size:90%;
}
@media screen and (max-width: 1099px) {
  #formWrap {
    width: 90%;
  }
}
form{
	width: 70%;
  margin: 0 auto;
}
@media screen and (max-width: 1099px) {
  form{
    width: 100%;
  }
}
table.formTable{
	width:100%;
	margin:0 auto;
	margin-bottom:40px;
	border-collapse:collapse;
}
@media screen and (max-width: 1099px) {
  table.formTable {
    font-size: 16px;
  }
}
table.formTable td,table.formTable th{
	border:1px solid #ccc;
	padding:10px;
}
@media screen and (max-width: 1099px) {
  table.formTable td,table.formTable th{
    padding: 20px;
}
}
table.formTable th{
	width:30%;
	font-weight:normal;
	background:#efefef;
	text-align:left;
}
p.error_messe{
	margin:5px 0;
	color:red;
}
h1 {
  width: 25%;
  margin-bottom: 30px;
}
@media screen and (max-width: 1099px) {
  h1 {
    width: 45%;
    margin-bottom: 60px;
  }
}
h1 img {
width: 100%;
}
h3{
  text-align:center;
  font-size: 32px;
}
.confi_text{
	font-size: 20px;
    margin-bottom: 40px;
}
.img_area {
  width: 100%;
  margin-bottom: 50px
}
.img_area img {
  width: 100%;
  height: 200px;
  -o-object-fit: cover;
     object-fit: cover;
  -o-object-position: 50% 12%;
     object-position: 50% 12%;
}
.btn {
  position: relative;
  width: 24%;
  background-color: #000;
  font-size: 90%;
  letter-spacing: 2px;
  color: #fff;
  text-align: center;
  padding: 10px;
  margin: 0 20px;
  border-width: 0;
}
@media screen and (max-width: 1099px) {
  .btn {
    width: 40%;
    font-size: 20px;
  }
}
.btn::before {
  position: absolute;
  top: 7px;
  left: 7px;
  content: "";
  width: 100%;
  height: 100%;
  background-color: #d87616;
  z-index: -1;
}
</style>
</head>
<body>

<!-- ▲ Headerやその他コンテンツなど　※自由に編集可 ▲-->

<!-- ▼************ 送信内容表示部　※編集は自己責任で ************ ▼-->
<div id="formWrap">
<?php if($empty_flag == 1){ ?>
<div align="center">
<h4>入力にエラーがあります。下記をご確認の上「戻る」ボタンにて修正をお願い致します。</h4>
<?php echo $errm; ?><br /><br /><input type="button" value=" 前画面に戻る " onClick="history.back()">
</div>
<?php }else{ ?>
<h3>確認画面</h3>
<p align="center">以下の内容で間違いがなければ、「送信する」ボタンを押してください。</p>
<?php iniGetAddMailXHeader($iniAddX);//php.ini設定チェック?>
<form action="<?php echo h($_SERVER['SCRIPT_NAME']); ?>" method="POST">
<table class="formTable">
<?php echo confirmOutput($_POST);//入力内容を表示?>
</table>
<p align="center"><input type="hidden" name="mail_set" value="confirm_submit">
<input type="hidden" name="httpReferer" value="<?php echo h($_SERVER['HTTP_REFERER']) ;?>">
<?php
if(isset($_FILES[$upfile_key]["tmp_name"])){
	$file_count = count($_FILES[$upfile_key]["tmp_name"]);
	for ($i=0;$i<$file_count;$i++) {
		if(!empty($_FILES[$upfile_key]["tmp_name"][$i])){
?>
<input type="hidden" name="upfilePath[]" value="<?php echo h($upFilePath[$i]);?>">
<input type="hidden" name="upfileType[]" value="<?php echo h($_FILES[$upfile_key]['type'][$i]);?>">
<input type="hidden" name="upfileOriginName[]" value="<?php echo h($_FILES[$upfile_key]['name'][$i]);?>">
<?php 
		}
	}
}
?>
<!-- reCAPTCHA（確認画面あり）追記 -->
<!-- render=にサイトキーを入れてください -->
<script src="https://www.google.com/recaptcha/api.js?render=6LflXFMqAAAAADkDkadRRhOvSS4SJL6MDWxxf_KC"></script> 
<script>
    grecaptcha.ready(function() {
        document.querySelector('.confirmbtn').addEventListener('click', function(e) {
            e.preventDefault(); // フォーム送信を一旦止める
            grecaptcha.execute('6LflXFMqAAAAADkDkadRRhOvSS4SJL6MDWxxf_KC', {action: 'submit'}).then(function(token) { //サイトキーを入れます
                // reCAPTCHAのトークンをフォームに追加
                let recaptchaResponse = document.createElement('input');
                recaptchaResponse.type = 'hidden';
                recaptchaResponse.name = 'recaptcha_token';
                recaptchaResponse.value = token;
                document.forms[0].appendChild(recaptchaResponse);
                
                // トークンが取得できたらフォームを送信
                document.forms[0].submit();
            });
        });
    });
</script>
<!-- reCAPTCHA（確認画面あり）追記 ここまで-->
<input type="submit" value="送信する" class="btn confirmbtn">
<input type="button" value="前画面に戻る" onClick="history.back();" class="btn"></p>
</form>
<?php copyright();} ?>
</div><!-- /formWrap -->
<!-- ▲ *********** 送信内容確認部　※編集は自己責任で ************ ▲-->

<!-- ▼ Footerその他コンテンツなど　※編集可 ▼-->
</body>
</html>
<?php
/* ▲▲▲送信確認画面のレイアウト　※オリジナルのデザインも適用可能▲▲▲　*/
}

if(($jumpPage == 0 && $sendmail == 1) || ($jumpPage == 0 && ($confirmDsp == 0 && $sendmail == 0))) { 

/* ▼▼▼送信完了画面のレイアウト　編集可 ※送信完了後に指定のページに移動しない場合のみ表示▼▼▼　*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>完了画面</title>
</head>
<body>
<div align="center">
<?php if($empty_flag == 1){ ?>
<h4>入力にエラーがあります。下記をご確認の上「戻る」ボタンにて修正をお願い致します。</h4>
<div style="color:red"><?php echo $errm; ?></div>
<br /><br /><input type="button" value=" 前画面に戻る " onClick="history.back()">
</div>
</body>
</html>
<?php }else{ ?>
送信ありがとうございました。<br />
送信は正常に完了しました。<br /><br />
<a href="<?php echo $site_top ;?>">トップページへ戻る&raquo;</a>
</div>
<?php copyright(); ?>
<!--  CV率を計測する場合ここにAnalyticsコードを貼り付け -->
</body>
</html>
<?php 
/* ▲▲▲送信完了画面のレイアウト 編集可 ※送信完了後に指定のページに移動しない場合のみ表示▲▲▲　*/
  }
}
//確認画面無しの場合の表示、指定のページに移動する設定の場合、エラーチェックで問題が無ければ指定ページヘリダイレクト
else if(($jumpPage == 1 && $sendmail == 1) || $confirmDsp == 0) { 
	if($empty_flag == 1){ ?>
<div align="center"><h4>入力にエラーがあります。下記をご確認の上「戻る」ボタンにて修正をお願い致します。</h4><div style="color:red"><?php echo $errm; ?></div><br /><br /><input type="button" value=" 前画面に戻る " onClick="history.back()"></div>
<?php 
	}else{ header("Location: ".$thanksPage); }
}

// 以下の変更は知識のある方のみ自己責任でお願いします。

//----------------------------------------------------------------------
//  関数定義(START)
//----------------------------------------------------------------------
function checkMail($str){
	$mailaddress_array = explode('@',$str);
	if(preg_match("/^[\.!#%&\-_0-9a-zA-Z\?\/\+]+\@[!#%&\-_0-9a-z]+(\.[!#%&\-_0-9a-z]+)+$/", "$str") && count($mailaddress_array) ==2){
		return true;
	}else{
		return false;
	}
}
function h($string) {
	global $encode;
	return htmlspecialchars($string, ENT_QUOTES,$encode);
}
function sanitize($arr){
	if(is_array($arr)){
		return array_map('sanitize',$arr);
	}
	return str_replace("\0","",$arr);
}
//Shift-JISの場合に誤変換文字の置換関数
function sjisReplace($arr,$encode){
	foreach($arr as $key => $val){
		$key = str_replace('＼','ー',$key);
		$resArray[$key] = $val;
	}
	return $resArray;
}
//送信メールにPOSTデータをセットする関数
function postToMail($arr){
	global $hankaku,$hankaku_array;
	$resArray = '';
	foreach($arr as $key => $val){
		$out = '';
    //reCAPTCHA 追記
    // ここで recaptcha_token を無視
    if ($key === 'recaptcha_token') {
      continue;
    }
    //reCAPTCHA 追記 ここまで
		if(is_array($val)){
			foreach($val as $key02 => $item){ 
				//連結項目の処理
				if(is_array($item)){
					$out .= connect2val($item);
				}else{
					$out .= $item . ', ';
				}
			}
			$out = rtrim($out,', ');
			
		}else{ $out = $val; }//チェックボックス（配列）追記ここまで
		
		//全角→半角変換
		if($hankaku == 1){
			$out = zenkaku2hankaku($key,$out,$hankaku_array);
		}
		
		if($out != "confirm_submit" && $key != "httpReferer" && $key != "upfilePath" && $key != "upfileType") {
			
			if($key == "upfileOriginName" && $out !=''){
				$key = '添付ファイル';
			}elseif($key == "upfileOriginName" && $out ==''){
				continue;
			}
			$resArray .= "【 ".$key." 】 ".$out."\n";
		}
	}
	return $resArray;
}
//確認画面の入力内容出力用関数
function confirmOutput($arr){
	global $upFilePath,$upfile_key,$encode,$hankaku,$hankaku_array;
	$html = '';
	foreach($arr as $key => $val) {
		$out = '';
		if(is_array($val)){
			foreach($val as $key02 => $item){ 
				//連結項目の処理
				if(is_array($item)){
					$out .= connect2val($item);
				}else{
					$out .= $item . ', ';
				}
			}
			$out = rtrim($out,', ');
			
		}else{ $out = $val; }//チェックボックス（配列）追記ここまで
		$out = nl2br(h($out));//※追記 改行コードを<br>タグに変換
		$key = h($key);
		
		//全角→半角変換
		if($hankaku == 1){
			$out = zenkaku2hankaku($key,$out,$hankaku_array);
		}
		
		$html .= "<tr><th>".$key."</th><td>".mb_convert_kana($out,"K", $encode);
		$html .= '<input type="hidden" name="'.$key.'" value="'.str_replace(array("<br />","<br>"),"",mb_convert_kana($out,"K", $encode)).'" />';
		$html .= "</td></tr>\n";
		
	}
	
	//添付ファイル表示処理
	if(isset($_FILES[$upfile_key]["tmp_name"])){
		$file_count = count($_FILES[$upfile_key]["tmp_name"]);
		$j = 1;
		for($i=0;$i<$file_count;$i++,$j++) {
			//添付があったらファイル名表示
			if(!empty($upFilePath[$i])){
			  $html .= "<tr><th>添付ファイル名{$j}.</th><td>{$_FILES[$upfile_key]['name'][$i]}</td></tr>\n";
			}
		}
	}
	
	return $html;
}
//全角→半角変換
function zenkaku2hankaku($key,$out,$hankaku_array){
	global $encode;
	if(is_array($hankaku_array) && function_exists('mb_convert_kana')){
		foreach($hankaku_array as $hankaku_array_val){
			if($key == $hankaku_array_val){
				$out = mb_convert_kana($out,'a',$encode);
			}
		}
	}
	return $out;
}
//配列連結の処理
function connect2val($arr){
	$out = '';
	foreach($arr as $key => $val){
		if($key === 0 || $val == ''){//配列が未記入（0）、または内容が空のの場合には連結文字を付加しない（型まで調べる必要あり）
			$key = '';
		}elseif(strpos($key,"円") !== false && $val != '' && preg_match("/^[0-9]+$/",$val)){
			$val = number_format($val);//金額の場合には3桁ごとにカンマを追加
		}
		$out .= $val . $key;
	}
	return $out;
}
//管理者宛送信メールヘッダ
function adminHeader($userMail,$post_mail,$BccMail,$to){
	$header = '';
	
	//メールで日本語使用するための設定
	mb_language("Ja") ;
	mb_internal_encoding("utf-8");
	
	if($userMail == 1 && !empty($post_mail)) {
		$header="From: $post_mail\n";
		if($BccMail != '') {
		  $header.="Bcc: $BccMail\n";
		}
		$header.="Reply-To: ".$post_mail."\n";
	}else {
		if($BccMail != '') {
		  $header="Bcc: $BccMail\n";
		}
		$header.="Reply-To: ".$to."\n";
	}
	
	//----------------------------------------------------------------------
	//  添付ファイル処理(START)
	//----------------------------------------------------------------------
	if(isset($_POST['upfilePath'])){
		$header .= "MIME-Version: 1.0\n";
		$header .= "Content-Type: multipart/mixed; boundary=\"__PHPFACTORY__\"\n";
	}else{
		$header.="Content-Type:text/plain;charset=iso-2022-jp\nX-Mailer: PHP/".phpversion();
	}
	
	return $header;
}
//管理者宛送信メールボディ
function mailToAdmin($arr,$subject,$mailFooterDsp,$mailSignature,$encode,$confirmDsp){
	global $rename;
	$adminBody = '';
	//----------------------------------------------------------------------
	//  添付ファイル処理(START)
	//----------------------------------------------------------------------
	if(isset($_POST['upfilePath'])){
		$adminBody .= "--__PHPFACTORY__\n";
		$adminBody .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n";
		//$adminBody .= "\n";
	}
	//----------------------------------------------------------------------
	//  添付ファイル処理(END)
	//----------------------------------------------------------------------
	
	$adminBody .="「".$subject."」からメールが届きました\n\n";
	$adminBody .="＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n\n";
	$adminBody .= postToMail($arr);//POSTデータを関数からセット
	$adminBody .="\n＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n";
	$adminBody .="送信された日時：".date( "Y/m/d (D) H:i:s", time() )."\n";
	$adminBody .="送信者のIPアドレス：".@$_SERVER["REMOTE_ADDR"]."\n";
	$adminBody .="送信者のホスト名：".getHostByAddr(getenv('REMOTE_ADDR'))."\n";
	if($confirmDsp != 1){
		$adminBody.="問い合わせのページURL：".@h($_SERVER['HTTP_REFERER'])."\n";
	}else{
		$adminBody.="問い合わせのページURL：".@$arr['httpReferer']."\n";
	}
	if($mailFooterDsp == 1) $adminBody.= $mailSignature."\n";
	
//----------------------------------------------------------------------
//  添付ファイル処理(START)
//----------------------------------------------------------------------

if(isset($_POST['upfilePath'])){
	
	$default_internal_encode = mb_internal_encoding();
	if($default_internal_encode != $encode){
		mb_internal_encoding($encode);
	}
	
	$file_count = count($_POST['upfilePath']);
											 
	for ($i=0;$i<$file_count;$i++) {
	
		if(isset($_POST['upfilePath'][$i])){
		
		$adminBody .= "--__PHPFACTORY__\n";
		$filePath = h(@$_POST['upfilePath'][$i]);//ファイルパスを指定
		$fileName = h(mb_encode_mimeheader(@$_POST['upfileOriginName'][$i]));
		$imgType = h(@$_POST['upfileType'][$i]);
		
		//ファイル名が文字化けする場合には連番ファイル名とする
		if($rename == 1){
			$fileNameArray = explode(".",$fileName);
			$fileName = $i.'.'.end($fileNameArray);
		}
		
		
		# 添付ファイルへの処理をします。
		$handle = @fopen($filePath, 'r');
		$attachFile = @fread($handle, filesize($filePath));
		@fclose($handle);
		$attachEncode = base64_encode($attachFile);
		
		$adminBody .= "Content-Type: {$imgType}; name=\"$filePath\"\n";
		$adminBody .= "Content-Transfer-Encoding: base64\n";
		$adminBody .= "Content-Disposition: attachment; filename=\"$fileName\"\n";
		$adminBody .= "\n";
		$adminBody .= chunk_split($attachEncode) . "\n";
		}
	}
		$adminBody .= "--__PHPFACTORY__--\n";
}
//----------------------------------------------------------------------
//  添付ファイル処理(END)
//----------------------------------------------------------------------
	
	//return mb_convert_encoding($adminBody,"JIS",$encode);
	return $adminBody;
}

//ユーザ宛送信メールヘッダ
function userHeader($refrom_name,$to,$encode){
	$reheader = "From: ";
	if(!empty($refrom_name)){
		$default_internal_encode = mb_internal_encoding();
		if($default_internal_encode != $encode){
			mb_internal_encoding($encode);
		}
		$reheader .= mb_encode_mimeheader($refrom_name)." <".$to.">\nReply-To: ".$to;
	}else{
		$reheader .= "$to\nReply-To: ".$to;
	}
	$reheader .= "\nContent-Type: text/plain;charset=iso-2022-jp\nX-Mailer: PHP/".phpversion();
	return $reheader;
}
//ユーザ宛送信メールボディ
function mailToUser($arr,$dsp_name,$remail_text,$mailFooterDsp,$mailSignature,$encode){
	$userBody = '';
	if(isset($arr[$dsp_name])) $userBody = h($arr[$dsp_name]). " 様\n";
	$userBody.= $remail_text;
	$userBody.="\n＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n\n";
	$userBody.= postToMail($arr);//POSTデータを関数からセット
	$userBody.="\n＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n\n";
	$userBody.="送信日時：".date( "Y/m/d (D) H:i:s", time() )."\n";
	if($mailFooterDsp == 1) $userBody.= $mailSignature;
	return mb_convert_encoding($userBody,"JIS",$encode);
}
//必須チェック関数
function requireCheck($require){
	$res['errm'] = '';
	$res['empty_flag'] = 0;
	foreach($require as $requireVal){
		$existsFalg = '';
		foreach($_POST as $key => $val) {
			if($key == $requireVal) {
				
				//連結指定の項目（配列）のための必須チェック
				if(is_array($val)){
					$connectEmpty = 0;
					foreach($val as $kk => $vv){
						if(is_array($vv)){
							foreach($vv as $kk02 => $vv02){
								if($vv02 == ''){
									$connectEmpty++;
								}
							}
						}
						
					}
					if($connectEmpty > 0){
						$res['errm'] .= "<p class=\"error_messe\">【".h($key)."】は必須項目です。</p>\n";
						$res['empty_flag'] = 1;
					}
				}
				//デフォルト必須チェック
				elseif($val == ''){
					$res['errm'] .= "<p class=\"error_messe\">【".h($key)."】は必須項目です。</p>\n";
					$res['empty_flag'] = 1;
				}
				
				$existsFalg = 1;
				break;
			}
			
		}
		if($existsFalg != 1){
				$res['errm'] .= "<p class=\"error_messe\">【".$requireVal."】が未選択です。</p>\n";
				$res['empty_flag'] = 1;
		}
	}
	
	return $res;
}
//リファラチェック
function refererCheck($Referer_check,$Referer_check_domain){
	if($Referer_check == 1 && !empty($Referer_check_domain)){
		if(strpos(h($_SERVER['HTTP_REFERER']),$Referer_check_domain) === false){
			return exit('<p align="center">リファラチェックエラー。フォームページのドメインとこのファイルのドメインが一致しません</p>');
		}
	}
}
function copyright(){
	echo '<a style="display:none;text-align:center;margin:15px 0;font-size:11px;color:#aaa;text-decoration:none" href="http://www.php-factory.net/" target="_blank">- PHP工房 -</a>';
}
//ファイル添付用一時ファイルの削除
function deleteFile($dir,$tempFileDel){
	global $permission_file;
	
	if($tempFileDel == 1){
		if(isset($_POST['upfilePath'])){
			foreach($_POST['upfilePath'] as $key => $val){
				
				foreach($permission_file as $permission_file_val){
					if(strpos(strtolower($val),$permission_file_val) !== false && file_exists($val)){
						if(strpos($val,'htaccess') !== false) exit();
						unlink($val);
						break;
					}
				}
					
			}
		}
		
		//ゴミファイルの削除（1時間経過したもののみ）※確認画面→戻る→確認画面の場合、先の一時ファイルが残るため
		if(file_exists($dir) && !empty($dir)){
		$handle = opendir($dir);
		  while($temp_filename = readdir($handle)){
			if(strpos($temp_filename,'temp_file_') !== false ){
				if( strtotime(date("Y-m-d H:i:s",filemtime($dir."/".$temp_filename))) < strtotime(date("Y-m-d H:i:s",strtotime("-1 hour"))) ){
					@unlink("$dir/$temp_filename");
				}
			}
		  }
		}
	}
}	
//php.iniのmail.add_x_headerのチェック
function iniGetAddMailXHeader($iniAddX){
	if($iniAddX == 1){ 
		if(@ini_get('mail.add_x_header') == 1) echo '<p style="color:red">php.iniの「mail.add_x_header」がONになっています。添付がうまくいかない可能性が高いです。htaccessファイルかphp.iniファイルで設定を変更してOFFに設定下さい。サーバーにより設定方法は異なります。詳しくはサーバーマニュアル等、またはサーバー会社にお問い合わせ下さい。正常に添付できていればOKです。このメーッセージはmail.php内のオプションで非表示可能です</p>'; 
	}
}

//トラバーサル対策
function traversalCheck($tmp_dir_name){
	if(isset($_POST['upfilePath']) && is_array($_POST['upfilePath'])){
		foreach($_POST['upfilePath'] as $val){
			if(strpos($val,$tmp_dir_name) === false || strpos($val,'temp_file_') === false) exit('Warning!! you are wrong..1');//ルール違反は強制終了
			if(substr_count($tmp_dir_name,'/') != substr_count($val,'/') ) exit('Warning!! you are wrong..2');//ルール違反は強制終了
			if(strpos($val,'htaccess') !== false) exit('Warning!! you are wrong..3');
			if(!file_exists($val)) exit('Warning!! you are wrong..4');
			if(strpos(str_replace($tmp_dir_name,'',$val),'..') !== false)  exit('Warning!! you are wrong..5');
		}
	}
}


//----------------------------------------------------------------------
//  関数定義(END)
//----------------------------------------------------------------------
?>