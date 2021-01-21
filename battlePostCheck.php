<?php
// 通常攻撃をした時
if(!empty($_POST['attack'])){
  debug('バトル...攻撃/attackが押されました！素早さ比較します');
  // 速さを比較して
  if(Battle::speedComparison($_SESSION['human'],$_SESSION['monster'])){
    // 工藤新一の方が早い時
    debug('工藤新一の方が早いです');
    Battle::humanSpeedy();
    // 遅かった時
  }else{
    debug('モンスターの方が早いです');
    Battle::monsterSpeedy();
    $message1 = Message::set('');
  }
}
// プロテクトを押した時
if(!empty($_POST['protect'])){
  debug('バトル...プロテクト/protectが押されました！');
  Battle::humanProtect();
}

// アイテムを押した時
if(!empty($_POST['item'])){
  debug('バトル...アイテム/itemが押されました！');
}
// アイテム表示中の時...
$i = 0;
foreach ($_SESSION['item'] as $items => $val) {
  if(!empty($_POST['item'.$i])){
    Message::clear();
    debug('バトル...アイテム'.$i.'が押されました');
    Battle::humanUseItem($_SESSION['item'][$i]);
    itemDelete($i);
  }
  $i++;
}

// 逃げるを押した時
if(!empty($_POST['run'])){
  debug('バトル...逃げる/runが押されました！');
  // 逃走判定
  if(Battle::runIsSuccess($_SESSION['human'],$_SESSION['monster'])){
    // 逃走成功時
    debug('逃走成功の処理します');
      //消える演出のためにノックダウンを使い回す
    Battle::KnockDownMonster();
      // 増えたスコアとカウントを減らす
    $_SESSION['score'] -= $_SESSION['monster']->getScore();
    $_SESSION['knockDownCount'] -= 1;
      // メッセージを更新
    Message::clear();
    Message::set($_SESSION['human']->getName().'は逃げ出した！だっさ');
  }else{
    // 逃走失敗時
    debug('逃走失敗の処理します');
    Battle::runFalse();
  };
}

// 戻るを押した時
if(!empty($_POST['back'])){
  // ボスモンスターの時の待機セリフ
  if(is_a($_SESSION['monster'], 'BossMonster')){
    Message::clear();
    Message::set('＊ '.$_SESSION['monster']->getName().BOSSMONSTNBY[mt_rand(0,11)]);
  // 一般モンスターの時の待機セリフ
  }else{
    Message::clear();
    Message::set($_SESSION['monster']->getName().'は '.MONSTNBY[mt_rand(0,50)]);
  }

}
 ?>
