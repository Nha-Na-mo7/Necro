<?php
/*
=========================
 STAGE 1 入社
=========================
*/

function (){

};

// OPを流す
// 上画面
function chapterOver($chapNum){
  $click = (!empty($_SESSION['MsgClickCount'])) ? $_SESSION['MsgClickCount'] : 0 ;
  switch ($chapNum) {
    case 1:
      if(empty(CHA1[$click])){
        echo
        '
        <img src="img/ally/guide.png" alt="" class="big-picture">
        ';
      }elseif($click <= 4){
        echo
        '
        <img src="img/office/center_building.jpg" alt="" class="big-picture">
        ';
      }elseif($click <= 13 || $click >= 57){
        echo
        '
        <div class="relative">
        <img src="img/office/entrance.png" alt="" class="big-picture-background">'
        ;
        // 表示モンスター
        if($click >= 11){
          if($click >= 57){
            echo
            '
            <div class="width-monster">
            <img src="img/enemy/boss_deepone3.png" alt="" class="enemy-monster">
            </div>
            ';
          }else{
            echo
            '
            <div class="width-monster">
            <img src="img/enemy/boss_deepone2.png" alt="" class="enemy-monster">
            </div>
            ';
          }
        }
        echo
        '
        </div>
        ';
      }elseif(($click <= 48 && !($click == 34)) || ($click >= 53 && $click <= 56)){
        echo
        '
        <div class="relative">
        <img src="img/office/openspace2.png" alt="" class="big-picture-background">'
        ;
        // 表示モンスター
        if(($click <= 23) || ($click >= 41 && $click <= 48)){
          echo
          '
          <div class="width-monster">
          <img src="img/enemy/boss_deepone2.png" alt="" class="enemy-monster">
          </div>
          ';
        }elseif(($click >= 35 && $click <= 40)){
          echo
          '
          <div class="width-monster">
          <img src="img/enemy/zombie_man3.png" alt="" class="enemy-monster">
          </div>
          ';
        }elseif(($click >= 53 && $click <= 56)){
          echo
          '
          <div class="width-monster">
          <img src="img/enemy/boss_deepone3.png" alt="" class="enemy-monster">
          </div>
          ';
        }
        echo
        '
        </div>
        ';
      }
      break;

    case 2:
      // 工藤新一のステータス
      $humanLife = '9999';
      $humanMaxLife = '9999';
      if(!empty($_SESSION['human'])){
        $humanLife = $_SESSION['human']->getHpM();
        $humanMaxLife = $_SESSION['human']->getmaxHp();
      }
      // バトル中かスタンバイかで分岐
      // バトル中
      if($_SESSION['battleTime']){
        $backimg;
        if($_SESSION['knockDownCount'] < 10){
          $backimg = 'entrance';
        }elseif($_SESSION['knockDownCount'] < 20){
          $backimg = 'openspace2';
        }elseif($_SESSION['knockDownCount'] < 30){
          $backimg = 'openspace1';
        }elseif($_SESSION['knockDownCount'] < 40){
          $backimg = 'roomKaigi';
        }else{
          $backimg = 'bossroom';
        }
        echo '
        <div class="relative">
        <img src="img/office/'.$backimg.'.png" alt="" class="big-picture-background">'
        ;

        // モンスターが作られている場合
        if(!empty($_SESSION['monster'])){
          $path = $_SESSION['monster']->getImg();
          debug('$path::' .$path);
          // モンスター
          // HPの残りパーセント
          $lifePercent = ( ($_SESSION['monster']->getHpM() / $_SESSION['monster']->getmaxHp()) );
          // HPバーの色(割合によって変動)
          $lifebarColor = '';
          if($lifePercent < 0.25){
            $lifebarColor = '#c03';
          }elseif($lifePercent < 0.5){
            $lifebarColor = '#FF4';
          }else{
            $lifebarColor = '#4dba4b';
          }

          // JS関連
          // モンスター($HPバー)が倒れる演出のフラグ用
          $class_KnockDown = '';
          global $knockDownFlg;
          if($knockDownFlg){
            debug('$knockDownFlgがあり');
            $class_KnockDown = 'js-Enemy-KnockDown';
          }
          // プロテクト成功時、盾を表示
          $class_ProtectSuccess = '';
          global $jsProtectSuccess;
          if($jsProtectSuccess){
            debug('$jsProtectSuccessがあり');
            $class_ProtectSuccess = 'js-ProtectSuccess';
          }

          // 攻撃成功時、エフェクトを表示
          $class_humanAttack = '';
          $atkPath = '';
          global $humanAtkMove;
          if($humanAtkMove){
            debug('$humanAtkMoveあり');
            $class_humanAttack = ($humanAtkMove == 1) ? 'js-minamogiri' : 'js-normalAttack';
            $atkPath = ($humanAtkMove == 1) ? 'minamogiri' : 'attack';
          }

          echo
          '
          <div class="width-monster">
          <ul class="myself-status">
          <li class="myself-status-name">工藤新一</li>
          <li>HP '.$humanLife.' / '.$humanMaxLife.'</li>
          <li>攻撃 '.$_SESSION['human']->getAtkM().'</li>
          <li>防御 '.$_SESSION['human']->getDefM().'</li>
          <li>素早さ '.$_SESSION['human']->getSpdM().'</li>
          </ul>
          <div class="protectShield '.$class_ProtectSuccess.'">
            <img src="img/ally/jsProtectL.png" alt="" class="">
          </div>
          <div class="protectShield '.$class_humanAttack.'">
            <img src="img/ally/'.$atkPath.'.png" alt="" class="">
          </div>
          <img src="'.$path.'" alt="" class="enemy-monster '.$class_KnockDown.'">
          </div>
          <div class="hp-bar '.$class_KnockDown.'">
          <div class="lifebar" style="width:'.((int)($lifePercent * 500)).'px;background:'.$lifebarColor.';">
          </div>
        </div>
        ';
        }
        // else{
        //   // モンスターが作られていない場合(休憩中など)
        //
        // }
      // スタンバイ
      }elseif($_SESSION['standbyTime']){
        $backimg;
        if($_SESSION['knockDownCount'] < 10){
          $backimg = 'entrance';
        }elseif($_SESSION['knockDownCount'] < 20){
          $backimg = 'openspace2';
        }elseif($_SESSION['knockDownCount'] < 30){
          $backimg = 'openspace1';
        }elseif($_SESSION['knockDownCount'] < 40){
          $backimg = 'roomKaigi';
        }else{
          $backimg = 'bossroom';
        }
        echo
        '
        <div class="relative">
          <img src="img/office/'.$backimg.'.png" alt="" class="big-picture-background">
          <div class="width-monster">
            <ul class="myself-status">
            <li class="myself-status-name">工藤新一</li>
            <li>HP '.$humanLife.' / '.$humanMaxLife.'</li>
            <li>攻撃 '.$_SESSION['human']->getAtkM().'</li>
            <li>防御 '.$_SESSION['human']->getDefM().'</li>
            <li>素早さ '.$_SESSION['human']->getSpdM().'</li>
            </ul>
          </div>
        </div>
      ';
      }
      break;

    // エピローグ
    case 3:
      if(empty(EPILOGUE[$click])){
        echo
        '
        <div class="relative">
        <img src="img/office/titleLogo.png" alt="" class="titleLogo">
        </div>'
        ;
      }elseif($click <= 39){
        echo
        '
        <div class="relative">
        <img src="img/office/bossroom.png" alt="" class="big-picture-background">'
        ;
        // 表示モンスター
        if($click >= 0){
          // シュブ=ニグラス
          if(($click >= 8 && $click <= 9) || $click == 14){
            echo
            '
            <div class="width-monster">
            <img src="img/enemy/Yogsallon.png" alt="" class="enemy-monster">
            </div>
            ';
          // CEO
          }else{
            // 該当箇所だけ必殺技演出
            if($click == 23){
              echo'
              <div class="protectShield js-minamogiri">
                <img src="img/ally/minamogiri.png" alt="" class="">
              </div>
              ';
            }
            echo
            '
            <div class="width-monster">
            <img src="img/enemy/ceo.png" alt="" class="enemy-monster">
            </div>
            ';
          }
        }
        echo
        '
        </div>
        ';
      }elseif($click == 41){
        echo
        '
        <img src="img/office/center_building.jpg" alt="" class="big-picture">
        ';
      }elseif($click >= 42){
        echo
        '
        <div class="relative">
        <img src="img/office/explosion.png" alt="" class="big-picture">
        </div>
        ';
      }elseif(!empty(OP[$click])){
        echo
        '
        <img src="img/office/titleLogo.png" alt="" class="titleLogo">
        ';
      }
      break;

    // 最後の画面
    case 4:
      echo
      '
      <img src="img/office/titleLogo.png" alt="" class="titleLogo">
      <p class="theend">THE END</p>
      '
      ;
    break;

    default:
      // チャプターがない時はOP画面を表示
      echo
      '<h1> ☠️ ネクロビジョン ☠️ </h1>
      <img src="img/enemy/company_black.png" alt="" class="img-Enemy">
      <h2>キミは都内にあるIT企業 "株式会社ネクロビジョン"に入社したばかりの新人だ！</h2>
      <h2>ところが、ネクロビジョンはゾンビが彷徨う呪われた会社だった！</h2>
      <p>この作品は全てフィクションです。
      <br>また登場するキャラクター・人物・団体・施設・名称等は全て架空のものであり、実在のものとは一切関係ありません。
      <br>予めご了承の上、お楽しみください。</p>'
      ;
      break;
  }
}
//下画面
function chaptersMsgUnder($chapNum){
  // チャプターごとにシナリオが分岐する
  switch ($chapNum) {
    // Stage1-Chapter1 OP/出社・戦闘前まで
    case 1:
      echo '<div class="opmessage-area">';
      if(!empty(CHA1[$_SESSION['MsgClickCount']])){
        echo '<p>'.CHA1[$_SESSION['MsgClickCount']].'</p>
        </div>
        <form method="post">
          <input type="submit" name="nextChapterBattle" value="OPをスキップ">
          <input type="submit" name="nextMsg" value="次へ →">
        </form>';
      }else{
        echo
        '</div>
        <form method="post">
          <input type="submit" name="nextChapterBattle" value="バトルのやり方を理解した！">
        </form>';
      }
      break;

    // Stage1-Chapter2 戦闘画面
    case 2:
      // モンスターがいる時
      global $knockDownFlg, $itemFlg, $gameoverFlg, $finalBossKnockdownFlg;
      if(!empty($_SESSION['monster'])){
        // 倒れていない場合の画面
        if(!$knockDownFlg){
          debug('monster:::'.print_r($_SESSION['monster'],true));
          debug('モンスターがいます！ : '.$_SESSION['monster']->getName());
          // GAMEOVER時は余計な選択肢が出ない
          if($gameoverFlg){
            echo
            '<div class="opmessage-area">
            <p>'.$_SESSION['message'].'</p></div>'.
            '
            <form method="post">
              <input type="submit" name="restart" value="転生する">
            </form>';
          // GAMEOVERでない時
          }else{
            // アイテム選択中画面
            if($itemFlg){
              echo
              '<p>使用するアイテムを選んでね</p>
              <form method="post">
              <ul class="itemList">
              ';
              $i = 0;
              foreach ($_SESSION['item'] as $items => $val) {
                echo
                '
                <li>
                <input type="submit" class="submit-item" name="item'.$i.'" value="'.$val->getName().'">
                <p class="itemMessage">'.$val->getComment().'</p>
                </li>
                ';
                $i++;
              }
              echo
              '
              </ul>
              <input type="submit" name="back" value="戻る">
              </form>';
            }else{
              // 通常の下画面
              echo
              '<div class="opmessage-area">
              <p>'.$_SESSION['message'].'</p>
              </div>'.
              '
              <form method="post">
                <input type="submit" name="attack" value="たたかう">
                <input type="submit" name="protect" value="プロテクト">
                <input type="submit" name="item" value="アイテム">
                <input type="submit" name="run" value="みのがす">
              </form>';
            }
          }

        // ノックアウトした時
        }else{
          $submitName = ($finalBossKnockdownFlg) ? 'nextChapterStory' : 'nextStandby';
          $submitValue = ($finalBossKnockdownFlg) ? 'エンディングへ' : '次へ';
          echo '<div class="opmessage-area"><p>'.$_SESSION['message'].'</p></div>';
          Message::clear();
          echo '
          <form method="post">
          <input type="submit" name="'.$submitName.'" value="'.$submitValue.'">
          <input type="submit" name="restart" value="退職願">
          </form>';
        }
      }else{
        debug('モンスターはいません');
        if($itemFlg){
          echo
          '<p>使用するアイテムを選んでね</p>
          <form method="post">
          <ul class="itemList">
          ';
          $i = 0;
          foreach ($_SESSION['item'] as $items=> $val) {
          // バトル中オンリーアイテムは選べない
            if($val->getBattleOnlyFlg()){
              echo
              '
              <li>
              <div>
              <span class="submitNG">'.$val->getName().'</span>
              <p class="itemMessage itemMessageNg">'.$val->getComment().'</p>
              </div>
              </li>
              ';
            }else{
              echo
              '
              <li>
              <input type="submit" class="submit-item" name="item'.$i.'" value="'.$val->getName().'">
              <p class="itemMessage">'.$val->getComment().'</p>
              </li>
              ';
            }
            $i++;
          }
          echo
          '
          <input type="submit" name="back" value="戻る">
          </form>
          ';

        }else{
          if(empty($_SESSION['message'])){
            if(($_SESSION['knockDownCount'] % 10) == 9){
              Message::set('この先から 異様な雰囲気を感じる... 何か いるようだ。');
            }else{
              Message::set('どうしますか？');
            }
          }
          echo '
          <div class="opmessage-area"><p>'.$_SESSION['message'].'</p>
          <p>倒した数 :'.$_SESSION['knockDownCount'].'匹<br>
          スコア : '.$_SESSION['score'].'</p></div>
          <form method="post">
          <input type="submit" name="nextBattle" value="進む">
          <input type="submit" name="item" value="アイテム">
          <input type="submit" name="restart" value="退職願">
          </form>';
        }
      }
    break;

      case 3:
        if(!empty(EPILOGUE[$_SESSION['MsgClickCount']])){
          echo '<div class="opmessage-area"><p>'.EPILOGUE[$_SESSION['MsgClickCount']].'</p></div>
          <form method="post">
            <input type="submit" name="nextMsg" value="次へ →">
          </form>';
        }else{
          echo
          '<form method="post">
            <input type="submit" name="nextChapterSame" value=" トータルスコアを確認する ">
          </form>';
        }
        break;

      case 4:
          echo
          '
          <h1>- THANK YOU FOR PLAYING! -</h1>
          <div class="opmessage-area">
          <p>倒したゾンビニアの数 :' .$_SESSION['knockDownCount'].'<br>獲得スコア :'. $_SESSION['score'].'</p>
          </div>
          <form method="post">
            <input type="submit" name="restart" value="はじめから">
          </form>
          ';
        break;

    default:
      // code...
      break;
  }
}
// セリフ集
// チャプター1
  define('CHA1',array(
    '俺は、工藤新一！！！',
    '大学を卒業して、今日からネクロビジョンに新卒入社することになった新人だ！！！',
    '社会人としての第一歩、楽しみだぜ。',
    'エンジニアは未経験だけど、まあなんとかなるだろ！！！',
    'とりあえず出社するぜ！！！',
    ' - 株式会社ネクロビジョン エントランス - ',
    '入社1日目だからやはり緊張するな。<br>よし、入るぞおおおおおおおおおおおおおおおおお！！！',
    '「たのもー！<br>今日から入社の“エンジニア"工藤新一だ！！！！！！！！！！！！！！！！！！」',
    '...',
    '... ... はーい',
    '人事「あっ キミは！」',
    'こいつは！ 俺を面接してくれた人事のねーちゃんだ',
    '人事 「よく来たね！入社おめでとう！<br>さ、入って入って！」',
    'お、おう！',
    ' - 株式会社ネクロビジョン 応接間 -',
    '人事「キミは研修生だから、最初の1ヶ月はここで学習することになるよ！」',
    '人事「大丈夫！最初は私たちから言われたことだけやってればいいからね！<br>1ヶ月研修したら現場にアサインされるよ！」',
    '人事「研修は1ヶ月だけど、<span class="op-red">"現場経験3年"として営業する</span>からプロ意識を持ってね！」',
    '1ヶ月で現場経験3年とか跳躍しすぎな気もするけど、この業界では当たり前なのかな？',
    'まあなんとかなるだろ。',
    'あ、ところで人事さん、1つ聞きたいんですけど',
    '同期っていたりしますか？もしかして俺一人とか？',
    '人事「ああ、同期ならもう全員先に来てるよ？」',
    '人事「おーい！出てきて！」',
    '(同期がいるなら心強いぜ<br>まあ俺が一番優秀だけどな)',
    '... ゥ ... ゥ ...',
    '(ん？ なんか呻き声が聞こえるような)',
    'ゥ... ニ ... ゲロ ... !',
    '(なんだ？)',
    '人事「ちょっと早く早く！<br>一緒に研修を受ける <span class="op-red">ゾンビニア</span> 仲間の工藤さんだよ！ 挨拶挨拶！」',
    '... ん？ エンジニアじゃないのか？',
    '... ん？ エンジニアじゃないのか？<br> <span class="op-red">ゾンビニア</span> ...?',
    '人事「工藤さん！」',
    '人事「工藤さん！<br>お待たせ！ こちらがキミの同期になる... ...」',
    'ゾ ン ビ ニ ア の み ん な だ よ',
    '＊ 「 ゥ... ゥ... ガッ ... ごぼごぼごぼ 」',
    '！！！？！？！？！？！',
    '＊ 「 ォ... マェ... ェサ ... ごぼごぼごぼ 」',
    '(なんだこいつら！？<br>とても同じ人間とは思えねええ！)',
    '＊ 「 ごぼごぼごぼ... ナカマ... ォ ... マェ ... 」',
    '人事「工藤さん？ どうした？」',
    'どうした？じゃねえええええええええええええええええ！！！<br>なんだこいつらはあああああああああああぁぁぁぁぁ！？！？！',
    '人事「何って...」',
    '人事「ごぼっ...」',
    '人事「何って... キミもすぐ」',
    '人事「ごぼっ... ごぼっ...」',
    '人事「何って... キミもすぐ ゾンビニアになってもらうんだよ？」',
    'お前は一体何者だああああああああああ！！！！！！！！！！！！！',
    '人事「...」',
    '人事「クックック...!」',
    '人事「クックック...!<br>そろそろこの姿のままというのも辛くなってきたな」',
    '人事「グォオォオオオ！！！ごぼごぼごぼごぼごぼごぼ」',
    ' 人事の皮膚にヒビが入っていく...!',
    ' そして人事は正体を表した...!',
    '人事ディープワン「クックック... やはりこっちの方が動きやすいごぼごぼごぼ... 」',
    'うわあああ',
    '逃げるぞ！',
    ' 株式会社ネクロビジョン エントランス',
    '俺の殺人キックを持ってしてもドアが開かねえええええええええ！？',
    '閉じ込められただとおおおおおおおおおおおおおおおおお！！！？？？',
    ' 人事ディープワン「どうしたのかな？工藤さん？<br>君はゾンビニアになるんだよ？グオオオオオオオオオオオオオ」',
    '... くっ<br>どうやら、ここにいるゾンビニアたちと戦うのは避けられねえみたいだなあああああぁぁぁぁぁぁぁぁぁぁぁぁ！？',
    'それならば！！！！！！！！！！！<br> ここにいる全員をやっつけて！！！！！！！！！！！！<br>会社を脱出してやるよおおおおおおおおおおおお！！！！！！！！！',
    '俺はああああああああああ！<br>ゾンビニアなんかにはああああああああああ<br>ならねえんだよおおおおおおおおおおおおおおお！！！！！！！！',
    ' 人事ディープワン「それなら、力ずくでゾンビニアにしてあげるよ...!」',
    ' 人事ディープワン「飛びかかれ！ゾンビニアども！」',
    'そうはいかねえ！ ゾンビニアども！逆に覚悟するんだな！<br>1匹ずつぶっ倒してやるぜええええええええええええええええええええええええええええ！！！！！！！！',
  ));




// エンディング
  define('EPILOGUE',array(
    'CEO「 具おおオオオオオオオオォオォオォォォ 」',
    'どうだああああああああ！俺はゾンビニアになどならねえええ！」',
    'CEO「 ...  」',
    'CEO「 ... ...  」',
    'CEO「 ... ... ...! 」',
    'CEO「 具おおおおおオオオオオオオオオオオオオオオ 」',
    '何いいいいいいいいいいい！？<br>まだ、仕留め切れねえのかあああああああああああああ！！！！！！！! ',
    '？？？ 「 ... 当然！ 」',
    'シュブ=ニグラス 「 ゾンビニアは死なぬ。何度でも蘇るさ」',
    'シュブ=ニグラス 「 さあ、再びゾンビニアのために力を取り戻すんだ、CEO!!! 」',
    'CEO「 グオオオオオオオオオオ！<br> グアおおおおおおあろおおおおおおおおおおおおおお  」',
    '(やつの身体は装甲が剥がれてボロボロだ...!)',
    '(もし、最大火力の一撃を叩き込むことができれば...!)',
    'CEO「 ... グォアア ... 」',
    'シュブ=ニグラス「 さあ俺の魂を生贄に、今こそ復活せよ！ 」',
    'CEO「 ... 」',
    'CEO「 ... ... 」',
    'CEO「 ... ... グルルルルルルルルルル」',
    '！？ CEOは今こちらに注意を払っていない...',
    '決めるなら、ここだ！',
    'うおおおおおおおおおおおおおおおおお！！！！！！',
    'くらえええええええええ！！！！！！！！！！！！！',
    '<h1>! 魂 の 一 撃 !</h1>',
    '<br>',
    '...',
    'シュブ=ニグラス 「...」',
    'CEO 「...」',
    'CEO 「... ...」',
    'CEO 「... ... ...」',
    'CEO 「... ... ... ガッ ...」',
    'CEO 「ガボガボごぼが母語簿GBおGBおgbbbrrr....」',
    'シュブ=ニグラス「な、な、な、な、何ィいいいいいいいいいい！？」',
    ' - CEOの身体から光が放たれる！！！ - ',
    ' - さらに、会社の壁が崩れ始めた。 外に出られそうだ！ -',
    '逃げるぞ！',
    'シュブ=ニグラス「まて、逃すか！ あ... CEO!」',
    'CEO 「ガボガボごぼが母語簿GBおGBおgbbbbbbrrrrrrrrr....」',
    'CEO 「母語簿GBおGBおgb馬具具紗愚愚愚汚求gggb碁戊母ごぼ<br>bぐgるあググgるがううあううvfひrぬytぶうyふうrhるtg.」',
    'CEO 「ヲヲヲヲヲヲヲヲヲヲヲヲヲヲヲx痛rぐるあうぐぐぐぐ<br>ぐrg愚愚ぐ愚GGGGGGGGぐ愚GGGGGGggggggggggggggggggggggggggggぐ愚Gggggggggg」',
    'CEO 「あああああ」',
    'CEO 「 」',
    '<br>',
    '<br>',
    'こうして、ゾンビニアの復活を目論む企業、<br>株式会社ネクロビジョンは消滅した。',
    '激しい戦いだった。だが俺は勝利したのだ。',
    '... 俺はこの激しい戦いを通して学んだことがある。',
    '「自らの道は、自分で切り拓かなければならない」<br>ということだ。',
    '俺はあのまま戦わない選択をしていたら、ゾンビニアとして一生、さらには死後まで永遠に過ごさなければならなかっただろう。',
    'エンジニアとしてのキャリアを言われるがままにしてしまって、家電量販店で販売員として働く物足りないゾンビニアライフを過ごしていただろう。',
    'だが、俺は目の前の困難に立ち向かうことを選択したんだ。',
    'その結果、俺はニンゲンとしての道が再び開かれたのだ。',
    'そうだ。「言われたことだけやってりゃなんとかなる」なんてことはあり得ないんだ。',
    '自分の人生、後悔のないように。常に挑戦していくことが大事なんだ！',
    'うおおおおおおおおおおおおおおおおおおおおおおおおおおおおおお',
    'うおおおおおおおおおおおおおおおおおおおおおおおおおおおお！！<br>俺は今やる気に満ち溢れているぞおおおおおおおおおおおおおおおおおおお！！',
    'うおおおおおおおおおおおおおおおおおおおおおおおおおおおお！！<br>俺は今やる気に満ち溢れているぞおおおおおおおおおおおおおおおおおおお！！<br>早速帰ったらエンジニアの学習を始めるぞおおおおおおおおおおおおおおお！！',
    '<br>',
    '今日も都心は 平和だった。',
    'おしまい',
  ));




 ?>
