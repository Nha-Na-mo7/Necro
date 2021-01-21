<?php
abstract class Item{
  protected $name;
  protected $comment;
  protected $battleOnlyFlg = true;
  public static function userd($obj){}

  // コンストラクタ
  public function __construct($name, $comment){
    $this->name = $name;
    $this->comment = $comment;
  }

  // nameのゲッター
  public function getName(){
    return $this->name;
  }

  // commentのゲッターセッター
  public function setComment($str){
    $this->comment = $str;
  }
  public function getComment(){
    return $this->comment;
  }

  // priceのゲッターセッター
  public function setBattleOnlyFlg($bool){
    $this->battleOnlyFlg = $bool;
  }
  public function getBattleOnlyFlg(){
    return $this->battleOnlyFlg;
  }

}

// 回復アイテム
class HealItem extends Item{
  protected $healPoint;

  public function __construct($name, $comment, $healPoint){
    parent::__construct($name, $comment);
    $this->healPoint = $healPoint;
    $this->battleOnlyFlg = false;
  }

  // 使った場合の処理
  public function used($obj){
    debug('Item::used($obj)..$healPointの数値分HPを回復');
    // 現在HPに回復値を加算
    $obj->setHpM($obj->getHpM() + $this->healPoint);
    debug('現在HP:'.$obj->getHpM());
    // 最大HPをオーバーしていた場合、再更新する
    if($obj->getHpM() > $obj->getMaxHp()){
      debug('Item::used($obj)..最大HPオーバーのため、再更新');
      $obj->setHpM($obj->getMaxHp());
      debug('現在HP:'.$obj->getHpM());
    }
    Message::set('体力が かいふくした！');
  }

  // healPointのゲッターセッター
  public function setHealPoint($num){
    $this->healPoint = $num;
  }
  public function getHealPoint(){
    return $this->healPoint;
  }
}
// レアヒールアイテム
class RareHealItem extends HealItem{
  protected $healPoint;

  public function __construct($name, $comment, $healPoint){
    $name = '★'.$name;
    parent::__construct($name, $comment, $healPoint);
    $this->battleOnlyFlg = true;
  }

  // 使った場合の処理
  // HP回復効果 + このターンのみプロテクトを付与
  public function used($obj){
    debug('$healPointの数値分HPを回復');
    parent::used($obj);
    Message::set('聖なる盾が身を包む！');
    $obj->setProtectFlg(true);
  }
}

// パワーアップアイテム
class PowerItem extends Item{
  protected $battleOnlyFlg = true;
  protected $upStatus;
  protected $upPoint;

  public function __construct($name, $comment, $upStatusNum, $upPoint){
    Parent::__construct($name, $comment);
    $this->upStatus = $upStatusNum;
    $this->upPoint = $upPoint;
  }

  public function used($obj){
    debug('特定のステータスに作用します');
    switch ($this->upStatus) {
      case 1:
        if($this->upPoint == 0){
          // upPoint 0の時は初期値の分上昇
          $obj->setAtkM($obj->getAtkM() + $obj->getMaxAtk());
        }else{
          $obj->setAtkM($obj->getAtkM() + $this->upPoint);
        }
        Message::set($obj->getName().'は 攻撃が 上がった！');
        break;

      case 2:
      if($this->upPoint == 0){
        // upPoint 0の時は初期値の分上昇
        $obj->setDefM($obj->getDefM() + $obj->getMaxDef());
      }else{
        $obj->setDefM($obj->getDefM() + $this->upPoint);
      }
      Message::set($obj->getName().'は 防御が 上がった！');
        break;

      case 3:
      if($this->upPoint == 0){
        // upPoint 0の時は初期値の分上昇
        $obj->setSpdM($obj->getSpdM() + $obj->getMaxSpd());
      }else{
        $obj->setSpdM($obj->getSpdM() + $this->upPoint);
      }
      Message::set($obj->getName().'は 素早さが 上がった！');
        break;

      case 4:
      if($this->upPoint == 0){
        // upPoint 0の時は初期値の分上昇
        $obj->setAtkM($obj->getAtkM() + $obj->getMaxAtk());
        $obj->setDefM($obj->getDefM() + $obj->getMaxDef());
      }else{
        $obj->setAtkM($obj->getAtkM() + $this->upPoint);
        $obj->setDefM($obj->getDefM() + $this->upPoint);
      }
      Message::set($obj->getName().'は 攻撃と防御が 上がった！');
        break;

      default:
        // code...
        break;
    }
  }

  // attackPointのゲッターセッター
  public function setAttackPoint($num){
    $this->attackPoint = $num;
  }
  public function getAttackPoint(){
    return $this->attackPoint;
  }

}
class RarePowerItem extends PowerItem{
  public function __construct($name, $comment, $upStatusNum, $upPoint){
    $name = '★'.$name;
    Parent::__construct($name, $comment, $upStatusNum, $upPoint);
    $this->upStatus = $upStatusNum;
    $this->upPoint = $upPoint;
  }

  // 使った場合の処理
  // パワーあっぷ効果 + このターンのみプロテクトを付与
  public function used($obj){
    debug('特定のステータスをパワーあっぷ！');
    parent::used($obj);
    Message::set('聖なる盾が身を包む！');
    $obj->setProtectFlg(true);
  }
}

// 使用済みアイテムをセッションから削除する
// 先にこれを使って、切り取ったオブジェクトを引数として活用すればよかったかも？
function itemDelete($num){
  array_splice($_SESSION['item'],$num,1);
  debug('itemDeleteしました');
  // debug('$_SESSION[item]::'.print_r($_SESSION['item']));
}

class PowerStatus{
  const ATK = 1;
  const DEF = 2;
  const SPD = 3;
  const ATKandDEF = 4;
  const ATKandSPD = 5;
  const DEFandSPD = 6;
  const ALL = 7;
}

 ?>
