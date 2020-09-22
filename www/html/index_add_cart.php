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

// ログインしていないユーザーがindex_add_cart.phpを直接開こうとした場合、ログインページにとばす。
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// データベースに接続
$db = get_db_connect();
// データベースからログインユーザーを取得
$user = get_login_user($db);

// アイテムid取得
$item_id = get_post('item_id');

// セッションのトークンとPOSTのトークンの照合
if($_SESSION['token'] === $_POST['token']){
  // カートに商品を追加出来たらメッセージ表示
  if(add_cart($db,$user['user_id'], $item_id)){
    set_message('カートに商品を追加しました。');
    // 上記以外はエラーメッセージを表示する
  } else {
    set_error('カートの更新に失敗しました。');
  }
}
// index_add_cart.phpにとばす
redirect_to(HOME_URL);