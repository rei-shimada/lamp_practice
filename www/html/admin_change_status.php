<?php
// 設定ファイル読み込み
require_once '../conf/const.php';
// 関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// ユーザーファイル読み込み
require_once MODEL_PATH . 'user.php';
// 商品ファイル読み込み
require_once MODEL_PATH . 'item.php';

session_start();
// ログインしていないユーザーがadmin_change_status.phpを直接開こうとした場合、ログインページにとばす。
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
// changes_toを取得
$changes_to = get_post('changes_to');

// changes_toがopenならば商品のステータスを更新し、メッセージを表示する
if($changes_to === 'open'){
  update_item_status($db, $item_id, ITEM_STATUS_OPEN);
  set_message('ステータスを変更しました。');
  // changes_toがcloseならば商品のステータスを更新し、メッセージを表示する
}else if($changes_to === 'close'){
  update_item_status($db, $item_id, ITEM_STATUS_CLOSE);
  set_message('ステータスを変更しました。');
  // 上記以外はエラーメッセージを表示する
}else {
  set_error('不正なリクエストです。');
}

// admin.phpにとばす
redirect_to(ADMIN_URL);