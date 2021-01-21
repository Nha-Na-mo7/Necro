<?php
class Battle{

  // 工藤新一を1体登場させる
  public static function createHuman(){
    global $human;
    $_SESSION['human'] = $human;
  }

  // モンスターを1体登場させる
  public static function createMonster(){
    global $monsters;
    if($_SESSION['knockDownCount'] < 50){
      $plus = floor($_SESSION['knockDownCount'] / 5);
      $monster = $monsters[random_int((1 + $plus), (5 + $plus))];

    }else{
      $monster = $monsters[random_int(1, 15)];
    }
    Message::clear();
    Message::set($monster->getName().'が 現れた！');

    $_SESSION['monster'] = $monster;
  }
  // ボスモンスターを1体登場させる
  public static function createBossMonster(){
    global $bossMonsters;
    if($_SESSION['knockDownCount'] < 50){
      $monster = $bossMonsters[($_SESSION['knockDownCount'] + 1) / 10];

    }else{
      $monster = $bossMonsters[random_int(1, 15)];
    }
    Message::clear();
    Message::set('【BOSS BATTLE!】'.$monster->getName().'が 現れた！');
    $monster->battleCry();

    $_SESSION['monster'] = $monster;
  }

  // ダメージ量の計算
  public static function damageCal($userObj, $targetObj,$power = 50){
    $critical = 1;
    // 1/24の確率できゅうしょに当たった！のフラグ
    // 急所はモンスターなら「1.5倍」主人公なら「2倍」のダメージ
    if(!random_int(0,23)){
      debug('急所に当たった！');
      $critical *= (is_a($targetObj, 'Monster')) ? 1.5 : 2;
    }
    // ダメージ = ( ((レベル×2/5+2)×威力×A/D )/50+2)×急所補正×乱数補正
      $damage = (int)( ( (($userObj->getLevel() * 2 / 5 + 2) * $power * $userObj->getAtkM() / $targetObj->getDefM()) / 50 + 2 ) * $critical * ((random_int(85,100)/ 100)) );

      // ターゲットがプロテクト成功状態なら与ダメージ-3000
      if($targetObj->getProtectFlg()){
        debug($userObj->getName().'の攻撃対象はプロテクト成功状態だ！');
        $damage -= 3000;
        $damage = ($damage < 0) ? 0 : $damage;
      }else{
        // 急所メッセージはプロテクト判定の後に出す
        if($critical > 1){
          Message::set($targetObj->getName().'の きゅうしょに当たった！');
        }
      }
      debug('damageCal...与えるダメージ量は '.$damage);
      return $damage;
  }

  // オブジェクトどうしの素早さを比較する
  // obj1が速ければ、1を返す
  public static function speedComparison($obj1,$obj2){
    $spdObj1 = $obj1->getSpdM();
    $spdObj2 = $obj2->getSpdM();
    if($spdObj1 == $spdObj2){
      // 同速の場合、1/2
      return (random_int(0,1)) ? 1 : 0 ;
    }elseif($spdObj1 > $spdObj2){
      return 1;
    }else{
      return 0;
    }
  }
  // 工藤新一が攻撃する時
  public static function humanAttack(){
    debug('工藤新一の攻撃メソッドを使用します');
    $_SESSION['human']->attack($_SESSION['monster']);
  }

  // モンスターが攻撃する時
  public static function monsterAttack(){
    debug('モンスターの攻撃メソッドを使用します');
    $_SESSION['monster']->attack($_SESSION['human']);
    // モンスターの行動後にプロテクト状態を解除する
    self::unProtect($_SESSION['human']);
  }

  // 工藤新一の方が先制攻撃する時
  public static function humanSpeedy(){
    Message::clear();
    debug('工藤新一の方が早いです、攻撃です');
    self::humanAttack();
    // 攻撃後、HPを判定する
    if(self::hpZeroCheck($_SESSION['monster'])){
      debug('相手のHPは0になりました');
      self::KnockDownMonster();
    // 相手のHPが0以下になっていない時
    }else{
      debug('次は遅いモンスターの攻撃です');
      self::monsterAttack();
      self::checkHpAndGameover();
    }
  }
  // モンスターの方が早く攻撃する時
  public static function monsterSpeedy(){
    Message::clear();
    debug('モンスターの方が早く攻撃します');
    self::monsterAttack();
    if(self::hpZeroCheck($_SESSION['human'])){
      debug('工藤新一のHPは0になりました');
      self::gameover();
    // HPがまだ残っている場合
    }else{
      // 遅い方の攻撃
      debug('遅れて、工藤新一の攻撃です');
      self::humanAttack();
      if(self::hpZeroCheck($_SESSION['monster'])){
        debug('相手のHPは0になりました。');
        self::KnockDownMonster();
      }
    }
  }

  // 「プロテクト」の判定
  /* 「プロテクト」について
  ・回数制限はない。1度の戦闘中に何度でも使える。
  ・判定成功すると、このターン受けるダメージを-3000。
  ・ただし成功の度に、次以降の成功確率が減少していく。
  A = プロテクト成功回数($_SESSION['protectCount'])
  F = (3 / (A + 3)) * 100
  Fが成功 % になる  */
  public static function protectIsSuccess($obj){
    global $jsProtectSuccess;
    $a = $obj->getProtectCount();
    $f = (int)((3 / ($a + 3)) * 100);
    debug('Battle::protectIsSuccess プロテクト成功率は'.$f.'%');
    if(random_int(0,99) <= $f){
      // プロテクト成功したら、プロテクトカウントを増やす
      debug('Battle::protectIsSuccess :プロテクト成功!');
      $obj->plusOneProtectCount();
      $jsProtectSuccess = true;
      return true;
    }
    debug('Battle::protectIsSuccess :プロテクト失敗...');
    return false;
  }

  // 対象のプロテクト状態を解除する
  public static function unProtect($targetObj){
    $targetObj->setProtectFlg(false);
  }

  // 工藤新一がバトル中にアイテムを使う
  public static function humanUseItem($objItem){
    // 先にアイテムを使用
    $_SESSION['human']->useItem($objItem);
    // その後モンスターが行動する
    self::monsterAttack();
    // 追撃されてHPが残っている場合
    self::checkHpAndGameover();
  }

  // 工藤新一がバトルでプロテクトを使う
  public static function humanProtect(){
    $_SESSION['human']->protect();
    self::monsterAttack();
    // 追撃されてHPが残っている場合
    self::checkHpAndGameover();
  }

  // 「逃げる」の成功判定
    /* 「逃げる」をプレイヤーが選択する
        A = 自分の素早さ,B = 相手の素早さ
        F = (A * 128 / B) + 30
        逃走成功率 = ( F / 256 )
        これによりどんなに素早さに差があっても12%程度は逃走成功する */
  public static function runIsSuccess($obj1,$obj2){
    $a = $obj1->getSpdM();
    $b = $obj2->getSpdM();
    $f = (($a * 128 / $b) + 30);
    debug('Battle::runIsSuccess 逃走成功率は'.(int)($f / 256 * 100).'%');
    if(random_int(0,100) <= ($f / 256 * 100)){
      debug('Battle::runIsSuccess :逃走成功!');
      return true;
    }
  
    debug('Battle::runIsSuccess :逃走失敗...');
    return false;
  }

  // 「逃げる」に失敗してモンスターが行動する場合
  public static function runFalse(){
    Message::clear();
    Message::set($_SESSION['monster']->getName().'「逃すかボケエエエエエエエエエエエエ！！！！！！！！！！！！」');
    self::monsterAttack();
    // 追撃されてHPが残っている場合
    self::checkHpAndGameover();
  }

  // HPを判定する
  public static function hpZeroCheck($obj){
    $hp = $obj->getHpM();
    // HPが0以下だった場合
    if($hp <= 0){
      debug('Battle::hpZeroCheck : HP0以下です');
      return true;
    }
  
    debug('Battle::hpZeroCheck : HPは1以上あります!');
    return false;
  }

  // モンスターを倒した場合
  public static function KnockDownMonster(){
    // ※モンスターを倒してもすぐに画面遷移はさせないこと
    global $knockDownFlg, $finalBossKnockdownFlg, $item, $rareItem;
    debug('KnockDownMonster...モンスターを倒した！');
    Message::set($_SESSION['monster']->getName().'を完膚なきまでに倒した！');
    // 逃げるでも使うメソッドなので、逃げた場合には入らない処理
    if(empty($_POST['run'])){
      // ボスモンスターなら断末魔をつける
      // さらにアイテム・レアアイテムが1つずつ手に入る
      if(is_a($_SESSION['monster'], 'BossMonster')){
        debug('ボスモンスターなので断末魔が付きます');
        Message::set('');
        $_SESSION['monster']->deathRattle();

        // ラスボスを倒した場合、アイテム入手せずEDへのボタンを表示
        if(!(is_a($_SESSION['monster'], 'FinalBossMonster'))){
          debug('ラスボスではないのでアイテム入手です');
          $getItem = $item[random_int(0,(count($item) - 1))];
          $getRareItem = $rareItem[random_int(1,(count($rareItem) - 1))];
          $_SESSION['item'][] = $getItem;
          $_SESSION['item'][] = $getRareItem;
          Message::set('"'.$getItem->getName().'" と "'.$getRareItem->getName().'"を 手に入れた！');
        }else{
          debug('ラスボスを倒しました');
          Message::set('最後の敵を倒した！');
          $finalBossKnockdownFlg = true;
        }
      }else{
        // アイテムの三重取り防止のためにボスでない場合に設定
        // 30%で戦闘後にアイテムを1つ獲得
        if(random_int(0,9) < 3){
          debug('30%の確率でのアイテム入手です');
          $getItem = $item[random_int(0,(count($item) - 1))];
          $_SESSION['item'][] = $getItem;
          Message::set($getItem->getName().'を 手に入れた！');
        }
      }
    }

    // フラグがあることで、画像が素早く消えるアニメーションをつける
    $knockDownFlg = true;
    $_SESSION['score'] += $_SESSION['monster']->getScore();
    $_SESSION['knockDownCount'] += 1;
    $_SESSION['human']->setProtectCount(0);
    $_SESSION['human']->blackMist();
    // $_SESSION['battleTime'] = false;
    // $_SESSION['standbyTime'] = true;
  }

  // GAMEOVER時の対応
  public static function gameover(){
    debug('~~~ GAMEOVER ~~~');
    debug('スコアは'.$_SESSION['score']);
    // GAMEOVERフラグをつける
    global $gameoverFlg;
    $gameoverFlg = true;

    // メッセージ
    Message::set('');
    Message::set('<span class="message-red"> - GAME OVER - </span>');
    Message::set('これであなたも 一人前の ゾンビニアですね＾＾');
    Message::set('倒したゾンビニアの数 : '.$_SESSION['knockDownCount']);
    Message::set('獲得スコア : '.$_SESSION['score']);
  }

  // HPチェックしてGAMEOVERになるまでの一連の流れ
  public static function checkHpAndGameover(){
    // HP判定する、0以下ならGAMEOVERメソッドを呼ぶ
    if(self::hpZeroCheck($_SESSION['human'])){
      debug('工藤新一のHPは0になりました');
      self::gameover();
    }
  }

  // HPの最大実数値計算
  public static function statusCalHp($num, $level){
    $status = (int)( (($num * 2) + random_int(0,31)) * $level / 100 + $level + 10 );
    return $status;
  }
  // HP以外の最大実数値計算
  public static function statusCal($num, $level){
    $status = (int)( (($num * 2) + random_int(0,31)) * $level / 100 + 5 );
    return $status;
  }

}

// 戦闘中・待機画面セリフ集
define('BOSSMONSTNBY',array(
  ' に ゆくてを ふさがれた！',
  ' は めを あわせようとしない。',
  ' とは ひにくなことに  はなしあいでは かいけつ できそうにない。',
  ' は おおきく いきを ついた。',
  ' 「くろい かぜが ないている …」',
  ' は にがしてくれるようだ。',
  ' に こうげきを つづけろ。',
  ' の 地響きが とどろく。',
  ' から 闇が 降りそそぐ！',
  ' 「絶望の中で生きる屈辱を 味わわせてやる！」',
  ' 「理解できたか？我々の新たな目的を。」',
  ' 「失せろ！我々は新たな目的を手に入れた！」',
));
define('MONSTNBY',array(
  'くつろいでいる',
  'ねむそうだ',
  'あくびをしている',
  'ちょっと寝不足らしい',
  'ひるねをしたいようだ',
  'ソファで横になっている',
  'スマホをいじっている',
  'まとめサイトをみている',
  '退屈そうだ',
  '体が凝っているみたい',
  '体を伸ばしている',
  '肩を回した',
  'おもむろに考え事を始めた',
  '何かを思い出そうとしているようだ',
  '朝起きてからのことを思い出している',
  '家に鍵をかけてきたか不安になっている',
  '頭をかいた',
  '小腹が空いたみたい',
  'そろそろおやつの時間みたい',
  '何か食べようかと考えている',
  'ラーメンの気分らしい',
  'ラーメンのトッピングを考えている',
  '太ることを気にしているみたい',
  'ヤサイマシの気分だ',
  'シャキッとした',
  '時計をみた',
  '悩ましげな表情をしている',
  '定時後にラーメンを食べるとケツイした',
  'サイフを みている',
  '小銭を数えている',
  'ガッツポーズした',
  'のどがかわいてきたみたい',
  'デスクに向かって行った',
  'コーヒーを飲もうとしている',
  'コーヒーに砂糖を入れた',
  'ポットのお湯がないことに気づいた',
  'ちょっと ガッカリしている',
  'ポットに水を貯めている',
  'ポットをわかした',
  '小さくため息をついた',
  'リラックスしている',
  'スイーツもたべたいようだ',
  'あまいものが すきみたい',
  'テーブルのお菓子を眺めている',
  'マカロンを つまんだ',
  'マカロンを一口で ほおばった',
  'ほっぺたが落ちそう',
  'マシュマロも 食べたいみたい',
  'そろそろお仕事モードだ！',
  '気分転換できたみたい！',
  '元気メイっぱい！',
  '今日も頑張る'
));



 ?>
