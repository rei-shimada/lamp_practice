<?php
// 定数ファイル読み込み
require_once '../conf/const.php';
// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';

// ログインチェックを行うため、セッションを開始する
session_start();

// ログインしている場合は、ホームにとばす
if(is_logined() === true){
  redirect_to(HOME_URL);
}

// ログイン画面テンプレートファイル読み込み
include_once VIEW_PATH . 'login_view.php';