<?php
// 設定ファイル読み込み
require_once '../conf/const.php';
// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// ユーザーに関するファイル読み込み
require_once MODEL_PATH . 'user.php';
// 商品に関するファイル読み込み
require_once MODEL_PATH . 'item.php';
// カートに関するファイル読み込み
require_once MODEL_PATH . 'cart.php';

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

// 該当するログインユーザーのカートにある商品の取得
$carts = get_user_carts($db, $user['user_id']);

// カートの合計金額
$total_price = sum_carts($carts);

// カートテンプレートファイルの読み込み
include_once VIEW_PATH . 'cart_view.php';