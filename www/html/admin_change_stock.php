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
// stockを取得
$stock = get_post('stock');

// セッションのトークンとPOSTのトークンの照合
if($_SESSION['token'] === $_POST['token']){
  // 在庫数を更新したら、メッセージを表示する
  if(update_item_stock($db, $item_id, $stock)){
    set_message('在庫数を変更しました。');
    // 上記以外はエラーメッセージを表示する
  } else {
    set_error('在庫数の変更に失敗しました。');
  }
}
// admin.phpにとばす
redirect_to(ADMIN_URL);