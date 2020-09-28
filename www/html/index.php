<?php
// 設定ファイル読み込み
require_once '../conf/const.php';
// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// ユーザーに関する関数ファイル読み込み
require_once MODEL_PATH . 'user.php';
// 商品に関する関数ファイル読み込み
require_once MODEL_PATH . 'item.php';

// ログインチェックを行うためセッション開始
session_start();

// ログインしていないユーザーがindex.phpを直接開こうとした場合、ログインページにとばす。
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// データベースに接続
$db = get_db_connect();
// データベースからログインユーザーを取得
$user = get_login_user($db);

// 商品一覧の表示
$items = get_open_items($db);

// トークン生成
$token = get_random_string($length = 20);
$_SESSION['token'] = $token;

// 商品一覧テンプレートファイル読み込み
include_once VIEW_PATH . 'index_view.php';