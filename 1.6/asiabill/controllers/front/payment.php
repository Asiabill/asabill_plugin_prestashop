<?php

class asiabillPaymentModuleFrontController extends ModuleFrontController
{

    public $ssl = true;
    public $display_column_left = false; // 不显示左侧内容

    public function initContent(){
        parent::initContent();
        // 购物车类
        $cart = $this->context->cart;
        // 支付程序
        $form_htn = '';
        if($cart->nbProducts() > 0){
            $parameter = $this->module->execPayment($cart);
        }else{
            $parameter = array();
        }

        $this->context->smarty->assign(array(
            'parameter' => $parameter,
            'pay_display' => Configuration :: get('ASIABILL_DISPLAY'),
            'iframe_height' => Configuration :: get('ASIABILL_IFRAME_HEIGHT'),
            'nbProducts' => $cart->nbProducts(),
            'loading_box' => '/modules/asiabill/views/images/loading_box.gif'
        ));

        $this->setTemplate('payment_execution.tpl');

    }

}
