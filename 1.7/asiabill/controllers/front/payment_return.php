<?php
session_start();
class asiabillPayment_returnModuleFrontController extends ModuleFrontController
{
    public $ssl = true;

    public function initContent(){

        parent::initContent();

        if( isset($_POST) && !empty($_POST['merNo']) && !empty($_POST['tradeNo']) ){

            foreach ($_POST as $name => $value){
                $$name = $value;
            }

            //signkey私钥
            $signkey = Configuration :: get('ASIABILL_SIGNKEY');

            //校验源加密
            $signsrc = $merNo.$gatewayNo.$tradeNo.$orderNo.$orderCurrency.$orderAmount.$orderStatus.$orderInfo.$signkey;
            $signInfocheck =strtoupper(hash("sha256",$signsrc));

            $new_history = new OrderHistory();
            $new_history->id_order = $orderNo;

            $order = new Order($orderNo);
            $current_status = $order->getCurrentState();

            if($isPush == '1'){      //检测是否推送 1为推送  空为正常POST
                $logtype = '[PUSH]';
            }else{
                $logtype = '[RETURN]';
            }

            if($signInfo == $signInfocheck){

                if(substr($orderInfo,0,5) == 'I0061'){	 //排除订单号重复(I0061)的交易
                    // 不做处理
                    $order_status = $current_status;
                }else{

                    switch ($orderStatus){
                        case 1:
                            //支付成功
                            $order_status = (int)Configuration :: get('ASIABILL_SUCCEED_STATE');
                            break;
                        case 0:
                            //支付失败
                            $order_status = (int)Configuration :: get('ASIABILL_FAIL_STATE');
                            break;
                        case -1:
                            //预授权
                            $order_status = (int)Configuration :: get('ASIABILL_WAIT_STATE');
                            break;
                        default:
                            //错误订单状态
                            $order_status = (int)Configuration :: get('ASIABILL_FAIL_STATE');
                            break;
                    }

                }

            }
            else{
                // 签名失败
                if($isPush == '1'){
                    echo 'Encryption error!';
                    exit();
                }
            }

            // 日志
            $this->module->parameterLog($logtype,$_POST);
            file_put_contents("./modules/asiabill/data.txt",json_encode($_POST),LOCK_EX);

            $_SESSION['asiabill_order'] = array(
                $orderNo => array(
                    'status' => $orderStatus,
                    'info' => $orderInfo
                )
            );

            if( $current_status != (int)Configuration :: get('ASIABILL_SUCCEED_STATE') ){
                $new_history->changeIdOrderState($order_status, $orderNo);
                $new_history->addWithemail(true);
            }

            if($isPush == '1'){
                echo 'SUCCESS';
            }
            else{
                $cart = $this->context->cart;
                $customer = new Customer($cart->id_customer);

                $url = 'index.php?controller=order-confirmation&id_cart='.$remark.'&id_module='.$this->module->id.'&id_order='.$orderNo.'&key='.$customer->secure_key;

                //echo $url;

                echo '<script>parent.location.replace("'.$url.'")</script>';
            }

            exit();

        }


    }

}
