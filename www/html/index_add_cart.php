<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();

// ログインしていないユーザーがindex_add_cart.phpを直接開こうとした場合、ログインページにとばす。
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
// データベースからログインユーザーを取得
$user = get_login_user($db);

// アイテムid取得
$item_id = get_post('item_id');

// カートに商品を追加出来たらメッセージ表示
if(add_cart($db,$user['user_id'], $item_id)){
  set_message('カートに商品を追加しました。');
   // 上記以外はエラーメッセージを表示する
} else {
  set_error('カートの更新に失敗しました。');
}

// index_add_cart.phpにとばす
redirect_to(HOME_URL);