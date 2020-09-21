<?php
// 設定ファイル読み込み
require_once '../conf/const.php';
// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// ユーザーに関する関数ファイル読み込み
require_once MODEL_PATH . 'user.php';
// 商品に関する関数ファイル読み込み
require_once MODEL_PATH . 'item.php';

// ログインチェックを行うため、セッションを開始する
session_start();

// ログインしていないユーザーがadmin.phpを直接開こうとした場合、ログインページにとばす。
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// データベースに接続
$db = get_db_connect();
// データベースからログインユーザーを取得
$user = get_login_user($db);

// ログインしたユーザーが管理者でなかった場合、ログインページにとばす。
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

// 名前の取得
$name = get_post('name');
// 金額の取得
$price = get_post('price');
// ステータスの取得
$status = get_post('status');
// 在庫数の取得
$stock = get_post('stock');
// 商品画像の取得
$image = get_file('image');

// 商品が登録出来たら、メッセージを表示する
if(regist_item($db, $name, $price, $stock, $status, $image)){
  set_message('商品を登録しました。');
  // 上記以外はエラーメッセージを表示する
}else {
  set_error('商品の登録に失敗しました。');
}

// admin.phpにとばす
redirect_to(ADMIN_URL);