<?php
// 登場モンスターを格納する変数
$monsters = array();

// インスタンスたち

// human
$human = new Human('工藤新一', random_int(135, 150), random_int(115,125), random_int(85, 95), random_int(55,100), 50, 'img/ally/syujinkou.png');

// 登場モンスターのみなさん
// ※ [Monster] ($name, $hp, $atk, $def, $spd, $level, $img)
// Stage0
$monsters[] = new Monster('マネキン' ,200, 20, 20, 20, 20, 0, 'img/enemy/nendoru.png');
$monsters[] = new Monster('ゾンビニアの枝肉', 130, 10, 70, 10, random_int(25,30), 1, 'img/enemy/edaniku.png');
$monsters[] = new Monster('ラッタッタ', 65, 45, 50, 40, random_int(33,37), 2, 'img/enemy/mouse.png');
$monsters[] = new Monster('ミニワーム', 45, 60, 40, 25, random_int(33,37), 2, 'img/enemy/worm.png');
$monsters[] = new Monster('アカグッモ', 60, 65, 30, 110, random_int(34,39), 3, 'img/enemy/seaka.png');
$monsters[] = new Monster('モブ同期ゾンビニア', 50, 85, 60, 40, random_int(38,41), 3, 'img/enemy/zombie_man.png');
$monsters[] = new Monster('腐乱犬ティンダロス', 60, 75, 50, 114, random_int(38,41), 3, 'img/enemy/zombie_dog.png');
$monsters[] = new Monster('ゴルズィ', 95, 58, 85, 30, random_int(39,43), 4, 'img/enemy/goruji.png');
$monsters[] = new Monster('アカグッモヒトデ', 72, 72, 72, 72, random_int(37,44), 4, 'img/enemy/kumohitode.png');
$monsters[] = new Monster('ボーリアン', 220, 35, 50, 88, random_int(39,43), 5, 'img/enemy/ootarumawashi.png');
$monsters[] = new RareMonster('ゴルドラ', 75, 80, 95, 40, random_int(43,46), 7.5, 'img/enemy/Dramidoro.png');
$monsters[] = new RareMonster('魔法陣で召喚された人', 40, 90, 90, 120, random_int(43,46), 8, 'img/enemy/fantasy_mahoujin_syoukan.png');
$monsters[] = new RareMonster('ダーティモーサイ', 100, 80, 70, 75, random_int(43,47), 9, 'img/enemy/mousai.png');
$monsters[] = new Monster('サーモグラフィオーグル', random_int(40,90), 165, 65, 30, random_int(44,48), 13, 'img/enemy/thermograph.png');
$monsters[] = new RareMonster('■■■■アネﾞデパミﾞ', 55, 95, 70, 95, random_int(44,48), 10, 'img/enemy/Yatagarasu.png');
$monsters[] = new RareMonster('世界観を間違えたワイバーン', 100, 125, 75, 100, random_int(45,50), 30, 'img/enemy/wyvern.png');


//BOSSモンスター
$bossMonsters[] = new BossMonster('テストボス' , 200, 50, 50, 50, 50, 33, BOSSCRY[0], BOSSDEATH[0], 'img/enemy/boss_deepone2.png');
$bossMonsters[] = new BossMonster('人事ディープワン' , 400, 92, 60, 50, 50, 33, BOSSCRY[1], BOSSDEATH[1], 'img/enemy/boss_deepone3.png');
$bossMonsters[] = new BossMonster('合体魔獣 営業キメラ' , 420, 81, 66, 111, 50, 33, BOSSCRY[2], BOSSDEATH[2], 'img/enemy/kimera.png');
$bossMonsters[] = new RareBossMonster('邪悪の権化 シュブ=ニグラス' , 450, 90, 70, 45, 50, 33, BOSSCRY[3], BOSSDEATH[3], 'img/enemy/Yogsallon.png');
$bossMonsters[] = new RareBossMonster('外なる神 シュブ=ニグラス' , 500, 95, 75, 60, 52, 40, BOSSCRY[4], BOSSDEATH[4], 'img/enemy/ShubNiggurath.png');
$bossMonsters[] = new FinalBossMonster('地獄に堕ちたCEO' , 800, 75, 180, 70, 70, 50, BOSSCRY[5], BOSSDEATH[5], 'img/enemy/ceo.png');

 ?>
