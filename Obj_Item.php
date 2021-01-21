<?php

// 説明文一覧
define('I_COM',array(
  '00' => 'HP + 200 会社近くで買った弁当。高カロリー栄養満点。',
  '01' => 'HP + 105 ゆきだるまから 3個もぎ取ってきた。 せかいの はてまで つれてって。',
  '02' => 'HP + 84 アカグッモのしるだけでなく 丸ごとしようした ジュース。',
  '03' => 'HP + 67 カップラーメンに よく入っている あいつ。',
  '04' => 'ATK + 60 三日月の形をした刀。 ペルシャ原産。',
  '05' => 'DEF + 75 宇宙の力で敵の攻撃を防ぐ。',
  '10' => 'HPぜんかいふく。 バタースコッチシナモンパイ。(1スライス)',
  '11' => 'HP + 998 オトナになると わかってくる ちょっとクセのある あじ。',
  '12' => 'HP + 400 倒れた仲間に使うと、復活させた上にHPを全回復できるぞ。',
  '13' => 'HP + 160 ゾンビニアの枝肉を 産地直送して作った、できたて ホヤホヤの カレー。',
  '14' => 'ATK & DEF × 2 この戦闘中、自分の限界を超える。',
  '15' => 'DEF + 180 プロテクトの新しい形。耐久力が大きく上がる。',
));

$item[] = new HealItem('ステーキ弁当', I_COM['00'], 200);
$item[] = new HealItem('ゆきだるまのかけら', I_COM['01'], 105);
$item[] = new HealItem('スパイダードーナツ', I_COM['02'], 84);
$item[] = new HealItem('ミニワーム', I_COM['03'], 67);
$item[] = new PowerItem('シミター', I_COM['04'], 1, 60);
$item[] = new PowerItem('宇宙バリア', I_COM['05'], 2, 75);


$rareItem[] = new RareHealItem('バタースコッチパイ', I_COM['10'], 999);
$rareItem[] = new RareHealItem('カタツムリパイ', I_COM['11'], 998);
$rareItem[] = new RareHealItem('げんきのかたまり', I_COM['12'], 400);
$rareItem[] = new RareHealItem('ゾンビーフカレー', I_COM['13'], 160);
$rareItem[] = new RarePowerItem('リミッター解除', I_COM['14'], 4, 0);
$rareItem[] = new RarePowerItem('プロテクト Ⅱ', I_COM['15'], 2, 180);

// セッションにアイテムを入れ込む
function makenewItem(){
  // 通常アイテムobj4個、レアアイテムobj1個が初期装備
  // ただし20%の確率でレアアイテムを2個持てる
  global $item, $rareItem;
  if(!mt_rand(0,4)){
    for($i = 0; $i < 3; $i++){
      $_SESSION['item'][] = $item[mt_rand(0,2)];
    }
    for($i = 0; $i < 2; $i++){
      $_SESSION['item'][] = $rareItem[mt_rand(0,2)];
    }
  }else{
    for($i = 0; $i < 4; $i++){
      $_SESSION['item'][] = $item[mt_rand(0,2)];
    }
    $_SESSION['item'][] = $rareItem[mt_rand(0,2)];
  }
  debug('$_SESSION[item] ::'.print_r($_SESSION['item'], true));
}

 ?>
