<?php
// ini_set('log_errors', 'on');
// ini_set('error_log', 'php.log');


require('C_Message.php');
require('C_Battle.php');
require('C_Creature.php');
require('C_Item.php');
require('Obj_Monster.php');
require('Obj_item.php');

// ======================
// セッション関連
// ======================
// // セッションの保存場所
// session_save_path('/var/tmp');
// ガベージコレクションに対するセッションの有効期限 2時間
ini_set('session.gc_maxlifetime',60*60*2);
// cookieに対するセッション有効期限 2時間
ini_set('session.cookie_lifetime',60*60*2);
// 1/100→1/1の確率でGCが発動する
ini_set('session.gc_divisor', 1);
// セッションスタート
session_start();
// セッションIDの置き換え
session_regenerate_id();

// session_destroy();
// デバッグ
$debugFlg = false;
function debug($str){
  global $debugFlg;
  if($debugFlg){
    error_log('ﾃﾞﾊﾞｯｸﾞ:'.$str);
  }
}

// ステージかいし
function stageStart($num){
  $path = 'stage'.$num.'.php';
  debug('$path :::'.$path);
  require($path);
}

// 次のチャプターへ移動するときのセッション変数変更
function nextChapterLink(){
  $_SESSION['MsgClickCount'] = 0;
  $_SESSION['chapter'] += 1;
}

// POST送信されたとき！
if(!empty($_POST)){
  debug(print_r($_POST,true));
  // フラグを立てる
  $restartFlg = (!empty($_POST['restart'])) ? true : false;
  $startFlg = (!empty($_POST['start'])) ? true : false;
    // ストーリー進行系フラグ
  $nextChapterSameFlg = (!empty($_POST['nextChapterSame'])) ? true : false;
  $nextChapterBattleFlg = (!empty($_POST['nextChapterBattle'])) ? true : false;
  $nextBattleFlg = (!empty($_POST['nextBattle'])) ? true : false;
  $nextChapterStoryFlg = (!empty($_POST['nextChapterStory'])) ? true : false;
  $nextChapterStandbyFlg = (!empty($_POST['nextChapterStandby'])) ? true : false;
  $nextStandbyFlg = (!empty($_POST['nextStandby'])) ? true : false;
    // バトル系フラグ
  $attackFlg = (!empty($_POST['attack'])) ? true : false;
  $attackMove = (!empty($_POST['attackMove'])) ? $_POST['attackMove'] : 0; //技番号
  $protectFlg = (!empty($_POST['protect'])) ? true : false;
  $itemFlg = (!empty($_POST['item'])) ? true : false;
  $runFlg = (!empty($_POST['run'])) ? true : false;
  $knockDownFlg = false; //ノックダウン用、諸々の処理に使う
  $humanAtkMove = 0;
  $jsProtectSuccess = false;
  $gameoverFlg = false;


  // リセット判定
  if($restartFlg){
    debug('リセット要求あり、セッションをクリアします');
    debug(print_r($_POST,true));
    $_SESSION = array();
  }else{
    if($startFlg){

      $_SESSION['storyTime'] = true;
      $_SESSION['MsgClickCount'] = 0;
      $_SESSION['knockDownCount'] = 0;
      $_SESSION['score'] = 0;
      $_SESSION['stageNum'] = 1;
      $_SESSION['chapter'] = 1;
      $_SESSION['battleTime'] = true;
      $_SESSION['item'] = array();

      // ステージ1を読みこむ
      Battle::createHuman();
      makenewItem();
      stageStart(1);
    // startFlgがない（リセットではない）とき
    }else{
      // 次のチャプターに移行するとき(フェーズは変わらない)
      if($nextChapterSameFlg){
        nextChapterLink();
      }
      // 次のチャプターに移行するとき(バトルへ)
      if($nextChapterBattleFlg){
        nextChapterLink();
        $_SESSION['battleTime'] = true;
        $_SESSION['storyTime'] = false;
        $_SESSION['standbyTime'] = false;
      }
      // 次のバトルへ
      if($nextBattleFlg){
        $_SESSION['battleTime'] = true;
        $_SESSION['storyTime'] = false;
        $_SESSION['standbyTime'] = false;
      }
      // 次のチャプターに移行するとき(ストーリーへ)
      if($nextChapterStoryFlg){
        nextChapterLink();
        $_SESSION['battleTime'] = false;
        $_SESSION['storyTime'] = true;
        $_SESSION['standbyTime'] = false;
      }
      // 次のチャプターに移行するとき(スタンバイフェイズへ)
      if($nextChapterStandbyFlg){
        nextChapterLink();
        $_SESSION['battleTime'] = false;
        $_SESSION['storyTime'] = false;
        $_SESSION['standbyTime'] = true;
      }
      // (主に)戦闘後のスタンバイフェイズへ
      if($nextStandbyFlg){
        $_SESSION['battleTime'] = false;
        $_SESSION['storyTime'] = false;
        $_SESSION['standbyTime'] = true;

        // 暫定でここにおきますが、ふさわしい位置があればもっと後ろへ
        $_SESSION['monster'] = array();
      }
      // 同一チャプター内の時
      if(!empty($_POST['nextMsg'])){
        $_SESSION['MsgClickCount'] += 1;
      }

      // バトル中のみの分岐判定を読み込み
      if(!empty($_SESSION['battleTime']) && $_SESSION['battleTime']){
        require('battlePostCheck.php');
      }

      // スタンバイ中の分岐
      if(!empty($_SESSION['standbyTime']) && $_SESSION['standbyTime']){
        require('standbyPostCheck.php');
      }

      stageStart($_SESSION['stageNum']);
    }
  }
  // フラグごとに読みこむファイルの内容が変わる
  if(!empty($_SESSION['battleTime'])){
    // 出現モンスターがいない場合はモンスターを出現させる
    if(empty($_SESSION['monster'])){
      // 10の倍数の戦闘(=ノックダウンが9)はボスが出てくる
      if(($_SESSION['knockDownCount'] % 10) == 9){
        Battle::createBossMonster();
      }else{
        Battle::createMonster();
      }
    }
  }
// POST送信がないとき (=最初の画面)
}else{
  $passwordFlg = true;
}

 ?>

<?php include_once("index.html"); ?>