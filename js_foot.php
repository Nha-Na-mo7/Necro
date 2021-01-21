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
    
    // パスワードチェック(非活性→活性)
    var passchecker = (function(){
      var PASSWORD = '1234';
      return{
        validPass: function($that){
          var password = $that.val();
          // パスワードが合致した時のみ活性化させる
          if(password === PASSWORD){
            console.log('パスワードが一致');
            $that.prop('disabled', true);
            $('.js-pass-disable').prop('disabled', false);
          }else{
            $that.prop('disabled', false);
          }
        },
        init: function(){
          var that = this;
          $('.js-passcheck').on('keyup', function(){
            var $that = $(this);
            // console.log($that.val());
            that.validPass($that);
          });
        }
      }
    })();
    battleEffect.init();
    passchecker.init();
  })

</script>
