<?php
// 設定ファイル読み込み
require_once '../conf/const.php';
// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// ユーザーに関する関数ファイル読み込み
require_once MODEL_PATH . 'user.php';
// 商品に関する関数ファイル読み込み
require_once MODEL_PATH . 'item.php';
// カートに関する関数ファイル読み込み
require_once MODEL_PATH . 'cart.php';

// ログインチェックを行うためセッション開始
session_start();

// ログインしていないユーザーがadmin.phpを直接開こうとした場合、ログインページにとばす。
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// データベースに接続
$db = get_db_connect();
// データベースからログインユーザーを取得
$user = get_login_user($db);

// カートid取得
$cart_id = get_post('cart_id');

// カートの商品を削除したら、メッセージを表示する。
if(delete_cart($db, $cart_id)){
  set_message('カートを削除しました。');
  // 上記以外はエラーメッセージを表示する
} else {
  set_error('カートの削除に失敗しました。');
}

// cart.phpにとばす
redirect_to(CART_URL);