<?php
// 設定ファイル読み込み
require_once '../conf/const.php';
// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// ユーザーに関する関数ファイル読み込み
require_once MODEL_PATH . 'user.php';
// 商品に関する関数ファイル読み込み
require_once MODEL_PATH . 'item.php';

// ログインチェックを行うためにセッション開始
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

// item_idを取得
$item_id = get_post('item_id');

// 該当商品が削除された場合、メッセージを表示
if(destroy_item($db, $item_id) === true){
  set_message('商品を削除しました。');
  // 上記以外はエラーメッセージを表示する
} else {
  set_error('商品削除に失敗しました。');
}


// admin.phpにとばす
redirect_to(ADMIN_URL);