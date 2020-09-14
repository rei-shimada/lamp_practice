<?php 
// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// データーベースに関する関数ファイル読み込み
require_once MODEL_PATH . 'db.php';

// ユーザーのカート一覧取得
function get_user_carts($db, $user_id){
  // carts,itemテーブルからログインユーザーのitem_id
  // name,price,stock,status,image,cart_id,user_id,
  // amountを取得する
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = {$user_id}
  ";
  // fetch_all_queryに返す
  return fetch_all_query($db, $sql);
}

// ログインユーザーが選んだ商品の取得
function get_user_cart($db, $user_id, $item_id){
  // cartsテーブルからログインユーザーが選んだ商品の取得
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = {$user_id}
    AND
      items.item_id = {$item_id}
  ";
// fetch_queryに返す
  return fetch_query($db, $sql);

}

// カートに追加する
function add_cart($db, $user_id, $item_id ) {
  // ログインユーザーのカート情報取得
  $cart = get_user_cart($db, $user_id, $item_id);
  // ログインユーザーのカート情報がなかったら
  if($cart === false){
    // insert_cartに返す(カート情報を追加する)
    return insert_cart($db, $user_id, $item_id);
  }
  // update_cart_amountに返す(カートの数量を更新する)
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

// カート情報を追加する
function insert_cart($db, $user_id, $item_id, $amount = 1){
  // テーブルにitem_id,user_id,amountを追加する
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES({$item_id}, {$user_id}, {$amount})
  ";
// execute_queryに返す
  return execute_query($db, $sql);
}

// // カートの数量を更新する
// function update_cart_amount($db, $cart_id, $amount){
//   // cartsテーブルのamountを更新する
//   $sql = "
//     UPDATE
//       carts
//     SET
//       amount = {$amount}
//     WHERE
//       cart_id = {$cart_id}
//     LIMIT 1      //レコードの選択
//   ";
//   // execute_queryに返す
//   return execute_query($db, $sql);
// }

// カートの数量を更新する(修正版)
function update_cart_amount($db, $cart_id, $amount){
  // cartsテーブルのamountを更新する
  $sql = "
    UPDATE
      carts
    SET
      amount = ?
    WHERE
      cart_id = ?
  ";
  // SQL文を実行する準備
  $stmt = $db->prepare($sql);
  // SQL文のプレースホルダに値をバインド
  $stmt->bindValue(1, $amount,   PDO::PARAM_INT);
  $stmt->bindValue(2, $cart_id,  PDO::PARAM_STR);
  
  // SQLを実行
  $stmt->execute();
  // execute_queryに返す
  return execute_query($db, $sql);
}


// カート情報を削除する
function delete_cart($db, $cart_id){
  // cartsテーブルの情報を削除する
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = {$cart_id}
    LIMIT 1
  ";

   // execute_queryに返す
  return execute_query($db, $sql);
}

// カート一覧から商品を購入する
function purchase_carts($db, $carts){
// カートから商品を購入出来なかった場合、
  if(validate_cart_purchase($carts) === false){
    // falseを返す
    return false;
  }
  // 繰り返し処理
  foreach($carts as $cart){
    // 商品の在庫が更新されなかった場合
    if(update_item_stock(
        $db, 
        $cart['item_id'], 
        $cart['stock'] - $cart['amount']
      ) === false){
      // エラーメッセージ表示
      set_error($cart['name'] . 'の購入に失敗しました。');
    }
  }
  
  // ログインユーザーのカート情報を削除する
  delete_user_carts($db, $carts[0]['user_id']);
}

// ログインユーザーのカート情報を削除する
function delete_user_carts($db, $user_id){
  // cartsテーブルからログインユーザーのカート情報を削除する
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = {$user_id}
  ";
  // クエリ実行
  execute_query($db, $sql);
}

// カートの合計
function sum_carts($carts){
  // 初期化
  $total_price = 0;
  // 繰り返し処理
  foreach($carts as $cart){
    // 合計金額は金額と数量をかけた物を足していく
    $total_price += $cart['price'] * $cart['amount'];
  }
  // total_priceに返す
  return $total_price;
}

// カートから商品を購入する
function validate_cart_purchase($carts){
  // カートがからの場合
  if(count($carts) === 0){
    // エラーメッセージ表示
    set_error('カートに商品が入っていません。');
    // falseを返す
    return false;
  }
  // 繰り返し処理
  foreach($carts as $cart){
    // オープンされていなかった場合
    if(is_open($cart) === false){
      // エラーメッセージ表示
      set_error($cart['name'] . 'は現在購入できません。');
    }
    // 在庫数が数量よりも少ない場合
    if($cart['stock'] - $cart['amount'] < 0){
      // エラーメッセージ表示
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  // エラーがあった場合
  if(has_error() === true){
    // falseを返す
    return false;
  }
  // trueを返す
  return true;
}

