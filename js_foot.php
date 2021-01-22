<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script>
  $(function(){
    // バトル演出
    var battleEffect = (function(){
      return{
        // ノックアウト
        enemyKnockDown : function($that){
          $that.fadeToggle(600);
        },
        // プロテクトの盾
        protect : function($that){
          $that.fadeToggle('fast');
          setTimeout(function(){
            $that.fadeToggle('slow');
          }, 2000);
        },
        // 通常攻撃
        normalAttack : function($that){
          $that.fadeToggle('fast');
          setTimeout(function(){
            $that.fadeToggle('fast');
          }, 200);
        },
        // ルードバスター
        minamogiri : function($that){
          $that.fadeToggle('fast');
          setTimeout(function(){
            $that.fadeToggle('slow');
          }, 1000);
        },
        init : function(){
          var $jsEnemyKnockDown = $('.js-Enemy-KnockDown');
          var $jsProtectSuccess = $('.js-ProtectSuccess');
          var $jsnormalAttack = $('.js-normalAttack');
          var $jsminamogiri = $('.js-minamogiri');

          this.enemyKnockDown($jsEnemyKnockDown);
          this.protect($jsProtectSuccess);
          this.normalAttack($jsnormalAttack);
          this.minamogiri($jsminamogiri);
        }
      }
    })();
    battleEffect.init();
  })

</script>
