<?php
// 設定ファイル読み込み
require_once '../conf/const.php';
// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';

// ログインチェックを行うため、セッションを開始する
session_start();

// ログインしている場合は、index.php(商品一覧)にとばす
if(is_logined() === true){
  redirect_to(HOME_URL);
}

// トークン生成
$token = get_random_string($length = 20);
$_SESSION['token'] = $token;

// ユーザー登録テンプレートファイル読み込み
include_once VIEW_PATH . 'signup_view.php';



