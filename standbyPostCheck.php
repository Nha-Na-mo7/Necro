<?php
// アイテムが使われた時
$i = 0;
foreach ($_SESSION['item'] as $items => $val) {
  if(!empty($_POST['item'.$i])){
    Message::clear();
    debug('スタンバイ...アイテム'.$i.'が押されました');
    $_SESSION['human']->useItem($_SESSION['item'][$i]);
    itemDelete($i);
  }
  $i++;
}

// 戻るを押した時
if(!empty($_POST['back'])){
  Message::clear();
  if(($_SESSION['knockDownCount'] % 10) == 9){
    Message::set('この先から 異様な雰囲気を感じる... 何か いるようだ。');
    Message::set('');
  }else{
    Message::set('どうしますか？');
  }

}
 ?>
