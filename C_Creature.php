<?php
abstract class Creature{
  // 名前
  protected $name;
  // 画像
  protected $img;
  // 種族値
  protected $hp;
  protected $atk;
  protected $def;
  protected $spd;
  // 戦闘中の実数値
  protected $level;
  protected $hpM;
  protected $atkM;
  protected $defM;
  protected $spdM;

  // 算出された最大値
  protected $maxHp;
  protected $maxAtk;
  protected $maxDef;
  protected $maxSpd;

  protected $protectCount = 0;
  protected $protectFlg = false;

  // コンストラクタ
  public function __construct($name, $hp, $atk, $def, $spd, $level){
    $this->name = $name;
    $this->hp = $hp;
    $this->atk = $atk;
    $this->def = $def;
    $this->spd = $spd;
    $this->level = $level;

    $this->hpM = Battle::statusCalHp((int)$hp, (int)$level);
    $this->maxHp = $this->hpM;
    $this->atkM = Battle::statusCal((int)$atk, (int)$level);
    $this->maxAtk = $this->atkM;
    $this->defM = Battle::statusCal((int)$def, (int)$level);
    $this->maxDef = $this->defM;
    $this->spdM = Battle::statusCal((int)$spd, (int)$level);
    $this->maxSpd = $this->spdM;

  }
  // ゲッター&セッター
    // NAMEのセッターゲッター
    public function setName($str){
      $this->name = $str;
    }
    public function getName(){
      return $this->name;
    }

    // HPのゲッターセッター
    public function setHp($num){
      $this->hp = $num;
    }
    public function getHp(){
      return $this->hp;
    }

    // Atkのゲッターセッター
    public function setAtk($num){
      $this->atk = $num;
    }
    public function getAtk(){
      return $this->atk;
    }

    // Defのゲッターセッター
    public function setDef($num){
      $this->def = $num;
    }
    public function getDef(){
      return $this->def;
    }

    // Spdのゲッターセッター
    public function setSpd($num){
      $this->spd = $num;
    }
    public function getSpd(){
      return $this->spd;
    }

    // HPMのゲッターセッター
    public function setHpM($num){
      $this->hpM = $num;
    }
    public function getHpM(){
      return $this->hpM;
    }

    // maxHpのゲッターセッター
    public function setMaxHp($num){
      $this->maxHp = $num;
    }
    public function getMaxHp(){
      return $this->maxHp;
    }

    // AtkMのゲッターセッター
    public function setAtkM($num){
      $this->atkM = $num;
    }
    public function getAtkM(){
      return $this->atkM;
    }
    // maxAtkのゲッターセッター
    public function setMaxAtk($num){
      $this->maxAtk = $num;
    }
    public function getMaxAtk(){
      return $this->maxAtk;
    }

    // Defのゲッターセッター
    public function setDefM($num){
      $this->defM = $num;
    }
    public function getDefM(){
      return $this->defM;
    }
    // maxDefのゲッターセッター
    public function setMaxDef($num){
      $this->maxDef = $num;
    }
    public function getMaxDef(){
      return $this->maxDef;
    }

    // Spdのゲッターセッター
    public function setSpdM($num){
      $this->spdM = $num;
    }
    public function getSpdM(){
      return $this->spdM;
    }
    // maxSpdのゲッターセッター
    public function setMaxSpd($num){
      $this->maxSpd = $num;
    }
    public function getMaxSpd(){
      return $this->maxSpd;
    }

    // levelのゲッターセッター
    public function setLevel($num){
      $this->level = $num;
    }
    public function getLevel(){
      return $this->level;
    }

    // imgのセッターゲッター
    public function setImg($path){
      $this->img = $path;
    }
    public function getImg(){
      return $this->img;
    }

    // $protectCountのゲッターセッターと、+1するメソッド
    public function setProtectCount($num){
      $this->protectCount = $num;
    }
    public function plusOneProtectCount(){
      $this->protectCount += 1;
    }
    public function getProtectCount(){
      return $this->protectCount;
    }

    // $protectFlgのゲッターセッター
    public function setProtectFlg($bool){
      $this->protectFlg = $bool;
    }
    public function getProtectFlg(){
      return $this->protectFlg;
    }
}

// モンスタークラス
class Monster extends Creature{

  // 画像
  protected $img;
  protected $score;

  // コンストラクタ
  public function __construct($name, $hp, $atk, $def, $spd, $level,$score, $img){
    parent::__construct($name, $hp, $atk, $def, $spd, $level);
    // レベルはノックアウト5回に付き1上がっていく
    if(!empty($_SESSION['knockDownCount'])){
      $this->level += floor($_SESSION['knockDownCount'] / 5);
    }
    $this->img = $img;
    $this->score = (int)(($this->getHpM() + $this->getAtkM() + $this->getDefM() + $this->getSpdM()) * $score / 5);
  }

  // 攻撃技をランダムに使うメソッド(サブクラスで活かす)
  public function attack($targetObj){
    $this->normalAttack($targetObj);
  }

  // 通常攻撃
  public function normalAttack($targetObj){
    $damage = Battle::damageCal($this, $targetObj);
    Message::set($this->getName().'の攻撃！'.$damage.'ダメージを受けた！');
    debug('attackメソッド:::与えるダメージは '.$damage);
    $targetObj->setHpM($targetObj->getHpM() - $damage);
  }

  // scoreのゲッターセッター
  public function setScore($score){
    $this->score = $score;
  }
  public function getScore(){
    return $this->score;
  }
}

// 多彩な技を覚えた、上級モンスター
class RareMonster extends Monster{
  protected $noMoveFlg = false;
  public function attack($targetObj){
    // 2ターン攻撃の待機中かどうか判定
    if(!$this->noMoveFlg){
      // 使用技の乱数
      $randAttack = random_int(0,9);
      // 50%/通常攻撃
      if($randAttack < 5){
        $this->normalAttack($targetObj);
      // 10%/ビルドアップ
      }elseif($randAttack < 6){
        $this->buildup();
      // 20%/フォーカスブラスト
      }elseif($randAttack < 8){
        $this->focusAttack($targetObj);
      // 20%/コールドフレア
      }else{
        $this->coldFlareT1();
      }
    // コールドフレアの2ターン目の時
    }else{
      $this->coldFlareT2($targetObj);
    }
  }

  // フォーカスブラスト 命中率70/威力120/30%で防御-15
  public function focusAttack($targetObj){
    $randHit = random_int(0,9);
    // 命中判定
    if($randHit < 7){
      // 命中時
      $damage = Battle::damageCal($this, $targetObj,120);
      Message::set($this->name.'は フォーカスブラスト を放った！ '.$damage.'ダメージ！');
      debug('focusAttack:::与えるダメージは '.$damage);
      $targetObj->setHpM($targetObj->getHpM() - $damage);
      // 30%で防御を下げる、相手がプロテクト中なら×
      $rand_B_down = random_int(0,9);
      if($rand_B_down < 3 && !($targetObj->getProtectFlg())){
        $targetObj->setDefM($targetObj->getDefM() - 15);
        if($targetObj->getDefM() < 0){
          $targetObj->setDefM(0);
        }
        Message::set($targetObj->getName().'の防御が下がった！');
      }
    }else{
      Message::set($this->name.'は フォーカスブラスト を放った！ しかし 攻撃は外れた。だっさ');
    }
  }
  // コールドフレア 命中率100/威力190/2ターン攻撃
  public function coldFlareT1(){
    $this->noMoveFlg = true;
    Message::set('冷気が渦を巻く...'.$this->name.'は力を溜め込んでいる。 強烈な攻撃が来そうな予感だ！');
  }
  public function coldFlareT2($targetObj){
    $damage = Battle::damageCal($this, $targetObj,190);
    Message::set($this->name.'が 強烈な力を 解き放つ！！ '.$damage.'ダメージ！');
    debug('coldFlareT2:::与えるダメージは '.$damage);
    $targetObj->setHpM($targetObj->getHpM() - $damage);
    // 攻撃後、ノームーヴフラグは切る
    $this->noMoveFlg = false;
  }

  // ビルドアップ 命中率--/威力--/攻撃と防御を1.5倍にする
  public function buildup(){
    debug('Boss::buildupメソッド');
    // 最大実数値の半分の数値を加算する
    $plusAtk = floor($this->maxAtk / 2);
    $plusDef = floor($this->maxDef / 2);

    $this->atkM += $plusAtk;
    $this->defM += $plusDef;
    Message::set($this->name.'は体を鍛えて攻撃と防御が上昇した！');
  }
}

// ボスモンスタークラス
class BossMonster extends RareMonster{
  private $battleCryComment;   //雄叫び
  private $deathComment; //断末魔

  // コンストラクタ
  public function __construct($name, $hp, $atk, $def, $spd, $level, $score, $battleCryComment, $deathComment, $img){
    Parent::__construct($name, $hp, $atk, $def, $spd, $level, $score, $img);
    // 雄叫び&断末魔をセット
    $this->battleCryComment = $battleCryComment;
    $this->deathComment = $deathComment;
  }

  public function battleCry(){
    Message::set('＊ '.$this->battleCryComment);
  }
  public function deathRattle(){
    Message::set('＊ '.$this->deathComment);
  }

  // ボスのアタックパターン
  public function attack($targetObj){
    // 2ターン攻撃の待機中かどうか判定
    if(!$this->noMoveFlg){
      // 使用技の乱数
      $randAttack = random_int(0,9);
      // 30%/通常攻撃
      if($randAttack < 3){
        $this->normalAttack($targetObj);
      // 20%/めいそう
      }elseif($randAttack < 5){
        $this->buildup();
      // 20%/フォーカスブラスト
      }elseif($randAttack < 7){
        $this->focusAttack($targetObj);
      // 30%/コールドフレア
      }else{
        $this->coldFlareT1();
      }
    // コールドフレアの2ターン目の時
    }else{
      $this->coldFlareT2($targetObj);
    }
  }
}

// 3面・4面ボス
class RareBossMonster extends BossMonster{

  protected $recoverUsedFlg = false;
  // レアボスのアタックパターン
  public function attack($targetObj){
    // 2ターン攻撃の待機中かどうか判定
    if(!$this->noMoveFlg){
      // HPが30%未満かつLv51以上の時、1度だけじこさいせいを使用
      if( ($this->level >= 51) && ($this->hpM < ($this->maxHp / 100 * 30)) && !($this->recoverUsedFlg) ){
        debug('じこさいせい');
        $this->recover();
      }else{
        // 使用技の乱数
        $randAttack = random_int(0,9);
        // 20%/クロノアクセルor通常攻撃
        if($randAttack < 2){
          $this->chronoAccel($targetObj);
          // 10%/めいそう
        }elseif($randAttack < 3){
          $this->buildup();
          // 20%/ワイドブレイカー
        }elseif($randAttack < 5){
          $this->snarl($targetObj);
          // 30%/フォーカスブラスト
        }elseif($randAttack < 8){
          $this->focusAttack($targetObj);
          // 20%/コールドフレア
        }else{
          $this->coldFlareT1();
        }
      }
    // コールドフレアの2ターン目の時
    }else{
      $this->coldFlareT2($targetObj);
    }
  }

  // クロノアクセル
  // 素早さを上昇させる・既に相手より素早い場合、通常攻撃になる
  public function chronoAccel($targetObj){
    //相手の素早さが高い時、防御を1/4下げて素早さを2倍
    if($targetObj->getSpdM() > $this->spdM){
      debug('RareBoss.chronoAccelメソッド ::S++/B-');
      // 素早さの最大実数値を足す
      // 防御の最大実数値の半分の数値を引く
      $plusSpd = $this->maxSpd;
      $minusDef = floor($this->maxDef / 4);

      $this->spdM += $plusSpd;
      $this->defM -= $minusDef;

      Message::set($this->name.'は クロノアクセルを 使った！<br>守りを捨てて 高速で動けるようになった！');
    }else{
      $this->normalAttack($targetObj);
    }
  }
  // ワイドブレイカー/威力20/命中100/相手のランダムなステータスを100%の確率で下げる
  public function snarl($targetObj){
    debug('RareBoss::ワイドブレイカーを使います');
    $damage = Battle::damageCal($this, $targetObj,20);
    $targetObj->setHpM($targetObj->getHpM() - $damage);
    Message::set($this->getName().'は ワイドブレイカーを 使った！'.$damage.'ダメージ！');
    // 対象がプロテクトを使っていない場合
    if( !($targetObj->getProtectFlg()) ){
      $downStatus = random_int(0,2);
      switch ($downStatus) {
        case 0:
        debug('Atkが下がります');
          $minusAtk = floor($targetObj->getMaxAtk() / 4);
          $targetObj->setAtkM($targetObj->getAtkM() - $minusAtk);
          if($targetObj->getAtkM() < 0){
            $targetObj->setAtkM(0);
          }
          Message::set('さらに '.$targetObj->getName().'の 攻撃が下がった！');
          break;

        case 1:
        debug('Defが下がります');
          $minusDef = floor($targetObj->getMaxDef() / 4);
          $targetObj->setDefM($targetObj->getDefM() - $minusDef);
          if($targetObj->getDefM() < 0){
            $targetObj->setDefM(0);
          }
          Message::set('さらに '.$targetObj->getName().'の 防御が下がった！');
          break;

        case 2:
        debug('Spdが下がります');
          $minusSpd = floor($targetObj->getMaxSpd() / 4);
          $targetObj->setSpdM($targetObj->getSpdM() - $minusSpd);
          if($targetObj->getSpdM() < 0){
            $targetObj->setSpdM(0);
          }
          Message::set('さらに '.$targetObj->getName().'の 素早さが下がった！');
          break;

        default:
          // code...
          break;
      }
    }
  }

  // じこさいせい/1度だけHPを最大HPの3/4回復する
  public function Recover(){
    debug('じこさいせいを使いました');
    $mHp= floor(($this->maxHp / 4) * 3);
    $this->hpM += $mHp;
    if($this->hpM > $this->maxHp){
      $this->hpM = $this->maxHp;
    }
    $this->recoverUsedFlg = true;
    Message::set($this->name.' 「見るがいい、真の絶望を...!」');
    Message::set($this->name.'は 細胞を活性化させて 体力をかいふくした！');
  }
}

class FinalBossMonster extends RareBossMonster{
  public function attack($targetObj){
    // 2ターン攻撃の待機中かどうか判定
    if(!$this->noMoveFlg){
      // HPが30%未満かつLv51以上の時、1度だけじこさいせいを使用
      if( ($this->level >= 51) && ($this->hpM < ($this->maxHp / 100 * 30)) && !($this->recoverUsedFlg) ){
        $this->recover();
      }else{
        // 使用技の乱数
        $randAttack = random_int(0,9);
        // 20%/クロノアクセルor通常攻撃
        if($randAttack < 2){
          $this->chronoAccel($targetObj);
        //   // 10%/めいそう
        // }elseif($randAttack < 2){
        //   self::buildup();
          // 20%/ワイドブレイカー
        }elseif($randAttack < 4){
          $this->snarl($targetObj);
          // 40%/フォーカスブラスト
        }elseif($randAttack < 8){
          $this->focusAttack($targetObj);
          // 20%/コールドフレア
        }else{
          $this->coldFlareT1();
        }
      }
    // コールドフレアの2ターン目の時
    }else{
      $this->coldFlareT2($targetObj);
    }
  // 行動の終わりに防御が10下がり攻撃が2上昇
    $this->flakeArmer();
    $this->appAtk();
  }
  // 行動の終わりに防御が少しずつ下がる
  private function flakeArmer(){
    if($this->defM > 10){
      $this->defM -= 6;
      debug('装甲が剥がれ落ちる！実数防御値::'.$this->defM);
    }
  }
  private function appAtk(){
    $this->atkM += 2;
    debug('力を取り戻しつつある！実数攻撃値::'.$this->atkM);
  }

}

// 人クラス
class Human extends Creature{
  // 画像
  protected $img = '';

  // コンストラクタ
  public function __construct($name, $hp, $atk, $def, $spd, $level, $img){
    parent::__construct($name, $hp, $atk, $def, $spd, $level);
    $this->img = $img;
  }
  // 攻撃
  public function attack($targetObj){
    global $humanAtkMove;
    // ATK210以上なら確定水の呼吸壱ノ型 水面斬り
    if($this->atkM >= 210){
      $this->minamogiri($targetObj);
      $humanAtkMove = 1;
    // ATK150以上なら 1/2で水の呼吸壱ノ型 水面斬り
    }elseif($this->atkM >= 150 && random_int(0,1)){
      $this->minamogiri($targetObj);
      $humanAtkMove = 1;
    }else{
      $this->normalAttack($targetObj);
      $humanAtkMove = 2;
    }
  }

  // 通常攻撃
  public function normalAttack($targetObj){
    Message::set($this->name.'のビンタ！相手にダメージ！');
    $damage = Battle::damageCal($this, $targetObj);
    debug('normalAttackメソッド:::与えるダメージは '.$damage);
    $targetObj->setHpM($targetObj->getHpM() - $damage);

    // 攻撃後、25%で攻撃力が1.5倍になる。
    if(!random_int(0,3)){
      $upAtk = floor($this->maxAtk / 2);
      $this->atkM += $upAtk;
      Message::set('この戦闘中の間、 '.$this->name.'の 攻撃 が上がった！');
    }
  }

  // 水の呼吸壱ノ型 水面斬り
  // 自分の現在攻撃力が200以上の場合、技の威力が100になり、さらに攻撃力に応じて追加ダメージ。
  // この追加ダメージ分は、相手の防御力を考慮しない
  public function minamogiri($targetObj){
    Message::set($this->name.'「 水の呼吸壱ノ型 水面斬り！ 」');
    $damage = Battle::damageCal($this, $targetObj,100);
    $damage += floor(random_int(0,$this->atkM) / 5);
    debug('minamogiri:::さらに追加ダメージを加えると'.$damage);
    $targetObj->setHpM($targetObj->getHpM() - $damage);
  }

  // アイテムを使用する
  public function useItem($objItem){
    debug($objItem->getName().'を使います');
    Message::clear();
    Message::set($objItem->getName().'を使った！');
    $objItem->used($this);
  }
  // プロテクト
  public function protect(){
    debug('工藤新一のprotect()!!!');
    Message::clear();
    Message::set($this->getName().' 「プロテクトで 完全ガード！」');
    // プロテクト判定
    if(Battle::protectIsSuccess($this)){
      //成功時
      debug('プロテクト成功時の処理をします');
      $this->protectFlg = true;
      //失敗時
    }else{
      debug('プロテクト失敗です');
      $this->protectFlg = false;
      Message::set('しかし うまく決まらなかった。 だっさ');
    };
  }
  // HP以外のステータスを元に戻す
  public function blackMist(){
    debug('Battle::blackMist()...HP以外のステータスを元に戻します');
    $this->atkM = $this->maxAtk;
    $this->defM = $this->maxDef;
    $this->spdM = $this->maxSpd;
  }
}
// ボスモンスターの雄叫び
define('BOSSCRY',array(
  'テストです',
  '採用面接の時と違ってずいぶん威勢がいいのね！ 今日の晩飯はお前の肉にするわ！',
  'ゾンビニアは俺たち営業の言うことを黙って聞いていればいいんだよ！<br>まずは家電量販店で販売員をやれ！！！',
  '地下に封印されたゾンビニアが復活する...今日がその記念すべき日だ！<br>さあ貴様も一人前のゾンビニアになるんだ！',
  '調子に乗りやがって...俺の本当の姿を魅せてやる！！！',
  'ネクロビジョンは、ゾンビニアが溢れる世界の実現を目指す...<br>それが唯一無二にして揺るがない経営理念だ。'
));
// ボスモンスターの断末魔
define('BOSSDEATH',array(
  'テスト断末魔です',
  'ごぼごぼごぼごぼごぼ...<br>ゾンビニアに栄光あれ...！！',
  'バーカバーカ！<br>俺ら営業様が仕事見つけてこなきゃお前みたいなのは何にもできないくせに！',
  'なかなかやるな、<br>だが覚悟しろ お前を必ずゾンビニアにしてやる！',
  'グッ だが残念だったな、CEOは既に洗脳済みだ！<br>HAHAHAHAHAHAHAHAHAHAHAHAHAHAHAHAHAHA',
  'FATAL ERROR FATAL ERROR FATAL ERROR'
));


 ?>
