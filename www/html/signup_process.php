<?php
// 定数ファイル読み込み
require_once '../conf/const.php';
// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// ユーザーデータに関する関数ファイル読み込み
require_once MODEL_PATH . 'user.php';

// ログインチェックを行うため、セッションを開始する
session_start();

// ログインしている場合は、index.php(商品一覧)にとばす
if(is_logined() === true){
  redirect_to(HOME_URL);
}

// 名前取得
$name = get_post('name');
// パスワード取得
$password = get_post('password');
// パスワード(確認用)取得
$password_confirmation = get_post('password_confirmation');

// データベースに接続
$db = get_db_connect();

try{
// データーベースから、名前、パスワード、パスワード（確認用）を取得
  $result = regist_user($db, $name, $password, $password_confirmation);
// データが存在したらエラーメッセージ表示し、signup.phpにとばす
  if( $result === false){
    set_error('ユーザー登録に失敗しました。');
    redirect_to(SIGNUP_URL);
  }
}catch(PDOException $e){
  // データベースに接続出来なかった場合、エラーメッセージ表示
  set_error('ユーザー登録に失敗しました。');
  // signup.phpにとばす
  redirect_to(SIGNUP_URL);
}

// データが存在していなかった場合、メッセージ表示しindex.phpにとばす
set_message('ユーザー登録が完了しました。');
login_as($db, $name, $password);
redirect_to(HOME_URL);