<?php
// コメントアウト　command+/
// デバッグ処理
function dd($var){
  var_dump($var);
  exit();
}

// urlにとばす
function redirect_to($url){
  header('Location: ' . $url);
  exit;
}

// 名前取得
function get_get($name){
  // 変数がセットされているか確認
  if(isset($_GET[$name]) === true){
    // 名前を返す
    return $_GET[$name];
  };
  // 空の文字列を返す
  return '';
}

// 名前取得
function get_post($name){
  // 変数がセットされているか確認
  if(isset($_POST[$name]) === true){
    // 名前を返す
    return $_POST[$name];
  };
  return '';
}

// ファイル名取得
function get_file($name){
  if(isset($_FILES[$name]) === true){
    return $_FILES[$name];
  };
  return array();
}

// セッション変数取得
function get_session($name){
  // セッション変数が取得された場合
  if(isset($_SESSION[$name]) === true){

    return $_SESSION[$name];
  };
  return '';
}

// セッション変数を$valueに代入する
function set_session($name, $value){
  $_SESSION[$name] = $value;
}
// セッション変数を$errorに代入する
function set_error($error){
  $_SESSION['__errors'][] = $error;
}

// エラー取得
function get_errors(){
  // セッションを取得
  $errors = get_session('__errors');
  // エラーがない場合
  if($errors === ''){
    // 空の配列を返す
    return array();
  }
  set_session('__errors',  array());
  return $errors;
}

// エラーがあるかチェック
function has_error(){
  return isset($_SESSION['__errors']) && count($_SESSION['__errors']) !== 0;
}

// セッション変数に$messageを代入
function set_message($message){
  $_SESSION['__messages'][] = $message;
}

// メッセージ取得
function get_messages(){
  $messages = get_session('__messages');
  // メッセージがからの場合
  if($messages === ''){
    return array();
  }
  set_session('__messages',  array());
  // $messagesに返す
  return $messages;
}

// ログインチェック
function is_logined(){
  return get_session('user_id') !== '';
}

// ファイルネーム取得
function get_upload_filename($file){
  // 画像が無効の場合
  if(is_valid_upload_image($file) === false){
    return '';
  }
  // 画像形式ファイル
  $mimetype = exif_imagetype($file['tmp_name']);
  $ext = PERMITTED_IMAGE_TYPES[$mimetype];
  return get_random_string() . '.' . $ext;
}

function get_random_string($length = 20){
  return substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, $length);
}

function save_image($image, $filename){
  return move_uploaded_file($image['tmp_name'], IMAGE_DIR . $filename);
}

function delete_image($filename){
  if(file_exists(IMAGE_DIR . $filename) === true){
    unlink(IMAGE_DIR . $filename);
    return true;
  }
  return false;
  
}



function is_valid_length($string, $minimum_length, $maximum_length = PHP_INT_MAX){
  $length = mb_strlen($string);
  return ($minimum_length <= $length) && ($length <= $maximum_length);
}

function is_alphanumeric($string){
  return is_valid_format($string, REGEXP_ALPHANUMERIC);
}

function is_positive_integer($string){
  return is_valid_format($string, REGEXP_POSITIVE_INTEGER);
}

function is_valid_format($string, $format){
  return preg_match($format, $string) === 1;
}


function is_valid_upload_image($image){
  if(is_uploaded_file($image['tmp_name']) === false){
    set_error('ファイル形式が不正です。');
    return false;
  }
  $mimetype = exif_imagetype($image['tmp_name']);
  if( isset(PERMITTED_IMAGE_TYPES[$mimetype]) === false ){
    set_error('ファイル形式は' . implode('、', PERMITTED_IMAGE_TYPES) . 'のみ利用可能です。');
    return false;
  }
  return true;
}

function h($str){
  return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
}