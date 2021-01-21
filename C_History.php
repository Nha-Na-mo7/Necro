<?php
// 履歴の管理

class His{
  public static function set($str){
    // セッションhistoryが作られてなければ作る
    if(empty($_SESSION['history'])) $_SESSION['history'] = '';

    $_SESSION['history'] .= $str.'<br>';
  }

  public static function clear(){
    unset($_SESSION['history']);
  }


}
 ?>
