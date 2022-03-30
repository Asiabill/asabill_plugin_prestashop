<?php

class asiabillConfirmationModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    public $display_column_left = false; // 不显示左侧内容

    public function initContent(){
        parent::initContent();

        $this->context->smarty->assign(array(
            'id_order' => $_GET['id_order'],
            'state' => $_GET['state'],
            'orderAmount' => $_SESSION['orderAmount'],
            'orderInfo' => $_SESSION['orderInfo']
        ));


        $this->setTemplate('confirmation.tpl');

    }

}