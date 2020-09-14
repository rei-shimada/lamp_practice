<?php
// 設定ファイル読み込み
require_once '../conf/const.php';
// 関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// ユーザーファイル読み込み
require_once MODEL_PATH . 'user.php';
// 商品ファイル読み込み
require_once MODEL_PATH . 'item.php';
// カートファイル読み込み
require_once MODEL_PATH . 'cart.php';

// ログインチェックを行うため、セッションを開始する
session_start();

// ログインしていないユーザーがadmin.phpを直接開こうとした場合、ログインページにとばす。
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// データベース接続
$db = get_db_connect();
// データベースからログインユーザーを取得
$user = get_login_user($db);

// カートid取得
$cart_id = get_post('cart_id');
// 数量取得
$amount = get_post('amount');

// カート数量を更新したら、メッセージを表示する
if(update_cart_amount($db, $cart_id, $amount)){
  set_message('購入数を更新しました。');
  // 上記以外はエラーメッセージを表示する
} else {
  set_error('購入数の更新に失敗しました。');
}

// cart.phpにとばす
redirect_to(CART_URL);