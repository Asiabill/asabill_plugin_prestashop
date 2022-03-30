<?php

use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

class asiabillPaymentModuleFrontController extends ModuleFrontController
{

    public $ssl = true;
    public $display_column_left = false; // 不显示左侧内容

    public function initContent(){
        parent::initContent();
        // 购物车类
        $cart = $this->context->cart;
        // 支付程序
        if($cart->nbProducts() > 0){
            $parameter = $this->module->execPayment($cart);

            $thml = '<form name="checkout_creditcard" id="checkout_creditcard" action="'. $parameter['handler'] .'" method="post">';
            foreach ($parameter as $key => $val){
                if( $key != 'handler' ){
                    $thml .= '<input type="hidden" name="'.$key.'" value=\''.$val.'\'>';
                }
            }
            $thml .= '</form>';

            $thml .= '<iframe style="display: none;border:none; margin: 0 auto; overflow:auto;" width="100%" height="100%" scrolling="auto" name="ifrm_creditcard_checkout" id="ifrm_creditcard_checkout" ></iframe>';

            if( Configuration :: get('ASIABILL_DISPLAY') == 'IFRAME' ){
                $thml .= '
            <script type="text/javascript">
            document.checkout_creditcard.target = "ifrm_creditcard_checkout";
            document.checkout_creditcard.submit();

            var ifrm_cc  = document.getElementById("ifrm_creditcard_checkout");
          
            ifrm_cc.onload = function(){
                ifrm_cc.style.display = "";
            }
        </script>
            ';
            }else{
                $thml .= '
            <script type="text/javascript">
                document.checkout_creditcard.submit();
            </script>
            ';
            }



            echo $thml;

        }else{
            Tools::redirect('index.php?controller=order&step=1');
        }
        exit();

    }

}
