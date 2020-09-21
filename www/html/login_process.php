<?php
// 設定ファイル読み込み
require_once '../conf/const.php';
// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// ユーザーに関する関数ファイル読み込み
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

// データベースに接続
$db = get_db_connect();

// データーベースからログインユーザーの名前とパスワードを取得
$user = login_as($db, $name, $password);
// データが存在していない場合、エラーメッセージ表示
if( $user === false){
  set_error('ログインに失敗しました。');
  // login.phpにとばす
  redirect_to(LOGIN_URL);
}

// データが存在していた場合、メッセージ表示
set_message('ログインしました。');
// ログインユーザーが管理者だった場合、admin.phpにとばす
if ($user['type'] === USER_TYPE_ADMIN){
  redirect_to(ADMIN_URL);
}
// index.phpにとばす
redirect_to(HOME_URL);