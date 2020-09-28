<?php
// iframe禁止のためのheader関数
header('X-FRAME-OPTIONS: DENY');

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
// データベースから商品一覧を取得
$items = get_all_items($db);

// ランダムなパスワードを一行で生成する。
$token = substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, 30);
$_SESSION['token'] = $token;

// 管理画面テンプレートファイルの読み込み
include_once VIEW_PATH . '/admin_view.php';
