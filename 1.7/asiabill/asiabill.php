<?php
//ini_set('display_errors',1);            //错误信息
//error_reporting(E_ALL);                    //打印出所有的 错误信息
session_start();
use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

if (!defined('_PS_VERSION_')) {
    exit;
}

class Asiabill extends PaymentModule
{
    const DOMAIN = 'https://safepay.asiabill.com';
    const SANDBOX = 'https://testpay.asiabill.com';

    protected $_html = ''; //后台输出显示代码
    protected $_postErrors = array();

    public function __construct()
    {
        $this->name = 'asiabill'; //模块名称
        $this->tab = 'payments_gateways'; //模块组
        $this->version = 1.0;   //版本号

        $this->bootstrap = true; // 设置bootstrap样式

        $this->currencies     = true;
        $this->currencies_mode = 'checkbox'; // 设置插件货币可以用性选择 radio||checkbox
        parent :: __construct();

        $this->page = basename(__FILE__, '.php');   //返回
        $this->displayName = $this->l('Creditcard Pay');   // 模块名称
        $this->description = $this->l('Creditcard payments by Asiabill'); // 模块描述
        $this->author = $this->l('Asiabill'); // 模块作者
        $this->confirmUninstall = $this->l('Are you sure you want to delete your details ?'); // 模块删除提示
    }

    // 安装函数
    public function install() {


//        $action_URL="https://payment.secure-checkoutserver.com/Interface";
//        $return_url='http://' . htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8') . __PS_BASE_URI__.'modules/CreditCard/payment_result.php';

        return parent::install()
            && $this->registerHook('paymentOptions')
            && $this->registerHook('paymentReturn');
    }

    // 卸载函数
    public function uninstall() {
        if (!Configuration :: deleteByName('ASIABILL_SUCCEED_STATES') ||
            !Configuration :: deleteByName('ASIABILL_FAIL_STATES') ||
            !Configuration :: deleteByName('ASIABILL_MERNO') ||
            !Configuration :: deleteByName('ASIABILL_SIGNKEY') ||
            !Configuration :: deleteByName('ASIABILL_GATEWAYNO') ||
            !Configuration :: deleteByName('ASIABILL_HANDLER') ||
            !Configuration :: deleteByName('ASIABILL_RETURN_URL') ||
            !parent :: uninstall())
        {
            return false;
        }

        return true;
    }

    // 获取配置信息
    public function getContent() {

        // 有post提交信息，执行修改操作
        if (isset ($_POST['submitAsiabill'])) {



            if (!sizeof($this->_postErrors)) {
                //执行修改操作
                Configuration :: updateValue('ASIABILL_MODE', strval($_POST['mode']));
                Configuration :: updateValue('ASIABILL_MERNO', strval($_POST['merNo']));
                Configuration :: updateValue('ASIABILL_SIGNKEY', strval($_POST['signkey']));
                Configuration :: updateValue('ASIABILL_GATEWAYNO', strval($_POST['gatewayNo']));
                Configuration :: updateValue('ASIABILL_TEST_MERNO', strval($_POST['test_merNo']));
                Configuration :: updateValue('ASIABILL_TEST_SIGNKEY', strval($_POST['test_signkey']));
                Configuration :: updateValue('ASIABILL_TEST_GATEWAYNO', strval($_POST['test_gatewayNo']));
                Configuration :: updateValue('ASIABILL_ORDER_STATE', strval($_POST['order_state']));
                Configuration :: updateValue('ASIABILL_SUCCEED_STATE', strval($_POST['succeed_state']));
                Configuration :: updateValue('ASIABILL_FAIL_STATE', strval($_POST['fail_state']));
                Configuration :: updateValue('ASIABILL_WAIT_STATE', strval($_POST['wait_state']));
                Configuration :: updateValue('ASIABILL_DISPLAY', strval($_POST['display']));
                Configuration :: updateValue('ASIABILL_IFRAME_HEIGHT', strval($_POST['iframe_height']));
                $this->displayConf();
            } else{
                $this->displayErrors();
            }

        }

        $this->_html .= $this->displayAsiabill();
        $this->_html .= $this->displayForm();
        return $this->_html;
    }

    //设置显示logo及描述信息
    public function displayAsiabill() {
       //return '<br/>123123';
    }

    //后台显示设置表单
    public function displayForm() {
        global $cookie;

        // 设置项
        $conf = Configuration :: getMultiple(array (
            'ASIABILL_MODE',
            'ASIABILL_MERNO',
            'ASIABILL_SIGNKEY',
            'ASIABILL_GATEWAYNO',
            'ASIABILL_TEST_MERNO',
            'ASIABILL_TEST_GATEWAYNO',
            'ASIABILL_TEST_SIGNKEY',
            'ASIABILL_ORDER_STATE',
            'ASIABILL_SUCCEED_STATE',
            'ASIABILL_FAIL_STATE',
            'ASIABILL_WAIT_STATE',
            'ASIABILL_HANDLER',
            'ASIABILL_DISPLAY',
            'ASIABILL_IFRAME_HEIGHT',
        ));

        $mode = $conf['ASIABILL_MODE'];
        $merNo = $conf['ASIABILL_MERNO'];
        $signkey = $conf['ASIABILL_SIGNKEY'];
        $gatewayNo = $conf['ASIABILL_GATEWAYNO'];
        $test_merNo = $conf['ASIABILL_TEST_MERNO'];
        $test_gatewayNo = $conf['ASIABILL_TEST_GATEWAYNO'];
        $test_signkey = $conf['ASIABILL_TEST_SIGNKEY'];
        $order_state = $conf['ASIABILL_ORDER_STATE'];
        $succeed_state = $conf['ASIABILL_SUCCEED_STATE'];
        $fail_state = $conf['ASIABILL_FAIL_STATE'];
        $wait_state = $conf['ASIABILL_WAIT_STATE'];
        $display = $conf['ASIABILL_DISPLAY'];
        $iframe_height = $conf['ASIABILL_IFRAME_HEIGHT']?$conf['ASIABILL_IFRAME_HEIGHT']:500;


        // 获取系统的所有订单状态
        $states = OrderState::getOrderStates((int)($cookie->id_lang));


        global $smarty;
        $smarty->assign(array (
            'action_url' => $_SERVER['REQUEST_URI'],
            'mode' => $mode,
            'merNo' => $merNo,
            'gatewayNo' => $gatewayNo,
            'signkey' => $signkey,
            'test_merNo' => $test_merNo?$test_merNo:'12246',
            'test_gatewayNo' => $test_gatewayNo?$test_gatewayNo:'12246002',
            'test_signkey' => $test_signkey?$test_signkey:'12H4567r',
            'order_state' => $order_state,
            'succeed_state' => $succeed_state,
            'fail_state' => $fail_state,
            'wait_state' => $wait_state,
            'display' => $display?$display:'REDIRECT',
            'iframe_height' => $iframe_height,
            'order_states' => $states
        ));
        return $this->display(dirname(__FILE__), 'views/templates/admin/settings.tpl');

    }

    //支付程序
    public function execPayment($cart) {
        if (!$this->active) return;

        $id_card = $cart->id;

        // 商品信息
        $goods_items = [];
        foreach ($cart->getProducts() as $product) {
            $goods_items[] = [
                'productName' => substr($product['name'],0,130),
                'quantity' => $product['cart_quantity'],
                'price' => sprintf('%.2f',$product['price_wt'])
            ];
        }
        $goods_items = array_slice($goods_items,0,10);

        $customer = new Customer($cart->id_customer);
        $currency = $this->context->currency;

        // 生成订单
        $this->validateOrder($cart->id, Configuration::get('ASIABILL_ORDER_STATE'), $cart->getOrderTotal(),$this->displayName,NULL,NULL,(int)$currency->id,false,$customer->secure_key);

        // 模式
        $mode = Configuration :: get('ASIABILL_MODE');
        $test = $mode == '1'? '': 'TEST_';
        //提交地址
        $handler = ($mode == '1'? self::DOMAIN: self::SANDBOX) . '/Interface/V2';
        //商户号
        $merNo = Configuration :: get('ASIABILL_'.$test.'MERNO');
        //网关接入号
        $gatewayNo = Configuration :: get('ASIABILL_'.$test.'GATEWAYNO');
        //signkey密匙
        $signkey = Configuration :: get('ASIABILL_'.$test.'SIGNKEY');
        //交易金额
        $orderAmount = $cart->getOrderTotal();
        //商户订单号
        $orderNo = $this->currentOrder;
        //交易币种
        $currency = new CurrencyCore($cart->id_currency);
        $orderCurrency = $currency->iso_code;

        //交易返回地址
        global $link;
        $returnUrl = $link->getModuleLink('asiabill', 'payment_return');

        //组合加密项
        $signsrc = $merNo.$gatewayNo.$orderNo.$orderCurrency.$orderAmount.$returnUrl.$signkey;
        $signsrc = str_replace(array("&","<",">","\"","'"),array('&amp;','&lt;','&gt;','&quot;',''),trim($signsrc));

        $invoiceAddress=new Address(intval($cart->id_address_invoice));
        $customer = new Customer(intval($cart->id_customer));

        // 支付参数
        $parameter = array(
            'handler' => $handler,
            'merNo' => $merNo,//merNo
            'gatewayNo' => $gatewayNo,//gatewayNo
            'orderNo' => $orderNo,//orderNo
            'orderCurrency' => $orderCurrency,//orderCurrency
            'orderAmount' => $orderAmount,//orderAmount
            'returnUrl' => $returnUrl,//returnUrl
            'signInfo'=> hash('sha256',$signsrc),//signInfo
            'paymentMethod' => 'Credit Card',//paymentMethod
            'email' => $customer->email,//email
            'firstName' => $invoiceAddress->firstname,//firstname
            'lastName' => $invoiceAddress->lastname,//lastName
            'phone' => empty ($invoiceAddress->phone_mobile) ? $invoiceAddress->phone : $invoiceAddress->phone_mobile,//phone
            'country' => self::getIsoByName($invoiceAddress->country),//country
            'state' => State::getNameById($invoiceAddress->id_state),//state
            'city' => $invoiceAddress->city,//city
            'address' => $invoiceAddress->address1.$invoiceAddress->address2,//address
            'zip' => $invoiceAddress->postcode,//zip
            'remark' => $id_card,//remark 购物车id，显示完成页面url需要用到
            'interfaceInfo' => 'prestashop',//interfaceInfo
            'interfaceVersion' => '1.6',//interfaceVersion
            'isMobile' => $this->isMobile(),//isMobile
            'goods_detail' => json_encode($goods_items)
        );

        $this->parameterLog('[POST]',$parameter);

        return $parameter;
    }

    // 判断移动设备
    public function isMobile(){
        //check mobile or computer
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $is_pc = (strpos($agent, 'windows nt')) ? true : false;
        $is_iphone = (strpos($agent, 'iphone')) ? true : false;
        $is_ipad = (strpos($agent, 'ipad')) ? true : false;
        $is_android = (strpos($agent, 'android')) ? true : false;


        if($is_pc){
            $isMobile='0';
        }
        if($is_iphone){
            $isMobile='1';
        }
        if($is_ipad){
            $isMobile='1';
        }
        if($is_android){
            $isMobile='1';
        }

        return $isMobile;

    }

    // 参数日志
    public function parameterLog($header,$parameter){

        $filedate = date('Y-m-d');
        $postdate = date('Y-m-d H:i:s');
        $filename = dirname(__FILE__)."/asiabill_log/" . $filedate . ".log";

        $newfile  = fopen( $filename, "a+" );
        $post_log = $postdate.' '.$header." \r\n";
        foreach ($parameter as $key => $value){
            $post_log .= $key .' => '. $value."\r\n";
        }
        $post_log = $post_log . "*************************************\r\n";
        $post_log = $post_log.file_get_contents( $filename);

        $filename = fopen( $filename, "r+" );
        fwrite($filename,$post_log);
        fclose($filename);
        fclose($newfile);
    }

    //前台支付方式列表界面

    public function hookPaymentOptions($params)
    {
        if (!$this->active) {
            return;
        }
        if (!$this->checkCurrency($params['cart'])) {
            return;
        }

        $newOption = new PaymentOption();

        $newOption->setModuleName($this->name)
            ->setCallToActionText($this->trans('Pay Credit Card', array(), 'Modules.Asiabill.Admin'))
            ->setAction($this->context->link->getModuleLink($this->name, 'payment', array(), true))
            ->setAdditionalInformation($this->fetch('module:asiabill/views/templates/hook/pay.tpl'));

        return [$newOption];
    }

    public function hookPaymentReturn($params)
    {
        if (!$this->active) {
            return;
        }

        $id_order = $_GET['id_order'];
        $this->smarty->assign(array(
            'status' => $_SESSION['asiabill_order'][$id_order]['status'],
            'message' => $_SESSION['asiabill_order'][$id_order]['info']
        ));

        return $this->fetch('module:asiabill/views/templates/hook/return.tpl');
        exit();
    }

    // 显示错误
    public function displayErrors() {
        $nbErrors = sizeof($this->_postErrors);
        $this->_html .= '<div class="alert error"><h3>'
            . ($nbErrors > 1 ? $this->l('There are') : $this->l('There is')) . ' ' . $nbErrors . ' ' . ($nbErrors > 1 ? $this->l('errors') : $this->l('error')) . '</h3><ol>';
        foreach ($this->_postErrors AS $error){
            $this->_html .= '<li>' . $error . '</li>';
        }
        $this->_html .= '</ol></div>';
    }

    // 显示成功
    public function displayConf() {
        $this->_html .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="'
            . $this->l('Confirmation')
            . '" />'
            . $this->l('Settings updated') . '</div>';
    }


    public function checkCurrency($cart)
    {
        $currency_order = new Currency((int)($cart->id_currency));
        $currencies_module = $this->getCurrency((int)$cart->id_currency);

        if (is_array($currencies_module)) {
            foreach ($currencies_module as $currency_module) {
                if ($currency_order->id == $currency_module['id_currency']) {
                    return true;
                }
            }
        }
        return false;
    }

    static public function getIsoByName($country_name)
    {
        $sql='
    SELECT `id_country`
    FROM `'._DB_PREFIX_.'country_lang`
    WHERE `name` = "'.$country_name.'"';
        $result = Db::getInstance()->getRow($sql);
        $iso=Country::getIsoById($result['id_country']);
        return $iso;
    }

}