<?php
// 定数ファイル読み込み
require_once '../conf/const.php';
// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// ユーザーデータに関する関数ファイル読み込み
require_once MODEL_PATH . 'user.php';
// 商品データに関する関数ファイル読み込み
require_once MODEL_PATH . 'item.php';
// カートデータに関する関数ファイル読み込み
require_once MODEL_PATH . 'cart.php';

// ログインチェックを行うため、セッションを開始する
session_start();

// ログインしていないユーザーがfinish.phpを直接開こうとした場合、ログインページにとばす。
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// データベースに接続
$db = get_db_connect();
// データベースからログインユーザーのデータを取得
$user = get_login_user($db);

// ログインユーザーのカート情報を取得
$carts = get_user_carts($db, $user['user_id']);

// カートの商品が購入出来なかったら、メッセージを表示する。
if(purchase_carts($db, $carts) === false){
  set_error('商品が購入できませんでした。');
  // cart.phpにとばす
  redirect_to(CART_URL);
} 

// 合計金額
$total_price = sum_carts($carts);

// 購入完了テンプレートファイル読み込み
include_once '../view/finish_view.php';