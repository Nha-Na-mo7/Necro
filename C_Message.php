<?php
// メッセージの表示履歴を担うクラスです。
interface MessageInter{
  // 履歴を記載する
  public static function set($str);
}

// 履歴管理クラス・インスタンス化して無限に増殖することはないので static
class Message implements MessageInter{
  public static function set($str){
    //
    if(empty($_SESSION['message'])){
      $_SESSION['message'] = '';
    }

    // 文字列をセッションに格納
    $_SESSION['message'] .= $str.'<br>';
  }


  public static function setHistory($str){
    // $_SESSION['history']がなければ初期化
    if(empty($_SESSION['history'])){
      $_SESSION['history'] = '';
    }

    // 文字列をセッションに格納
    $_SESSION['history'] .= $str.'<br>';
  }

  // 履歴をクリアする
    // 各ページの一番初めに表示される文章がある場合、その前につける
  public static function clear(){
    unset($_SESSION['message']);
  }
  public static function clearHistory(){
    unset($_SESSION['history']);
  }
}

 ?>
