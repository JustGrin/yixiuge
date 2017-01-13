<?php
/**
 * 支付回调
 *
 *
 */

define('DS','/');
define('BASE_PATH',__ROOT__);

class AlipayreturnAction extends Action{

	public function __construct() {
		parent::__construct();
        //初始化日志

        $logHandler= new CLogFileHandler("./api/payment/alipay/logs/".date('Y-m-d').'.log');
        $log = Logs::Init($logHandler, 15);
	}

    /**
     * 支付回调
     */
    public function return_url() {
        $time=date("Y-m-d H:i:s");
        $verify_result = $this->_verify_result('return');
        Logs::DEBUG("\n 【支付回调】 begin return_url". $time);
        Logs::DEBUG("\n 【支付回调】 get call back:" . json_encode($_GET));
        Logs::DEBUG("\n 【支付回调】 return_url 签名".json_encode($verify_result));
        $r_data['status']=0;
        //商户订单号
        $out_trade_no = $_GET['out_trade_no'];
        //支付宝交易号
        $trade_no = $_GET['trade_no'];
        //支付接口代码
        $payment_code = 'alipay';
        //验证成功
        $model_order = M('g_order_info');

        //pay_status 支付状态；0，未付款；1，付款中 ；2，已付款
        $order_msg=$model_order->where(array('order_sn'=>$out_trade_no))->find();
        $r_data['order_sn']=$order_msg['order_sn'];
        $r_data['id']=$order_msg['order_id'];
        if($order_msg){
            if($order_msg['pay_status']==2){
                $r_data['msg']="支付成功";
                $r_data['status']=1;
                upgrade_level($order_msg['order_sn']);
            }else if($order_msg['pay_status']==1){
                $r_data['msg']= "支付成功，待对账";
                $r_data['status']=1;
            }else{
                if($verify_result) {
                    //pay_status 支付状态；0，未付款；1，付款中 ；2，已付款
                    $order_list[] = $order_msg;

                    $result = $model_order->where(array('order_sn'=>$out_trade_no,'pay_status'=>0))->save(array('pay_status'=>1));
                    if($result!==false){
                        //记录订单日志
                        $data = array();
                        $data['order_id'] = $order_msg['order_id'];
                        $data['log_role'] = 'buyer';
                        $data['log_msg'] ='订单支付( 支付宝平台交易号 : '.$trade_no.' )'; //L('order_log_pay').'( 支付平台交易号 : '.$trade_no.' ) ';
                        $data['log_orderstate'] = '20';
                        $morder = D('Order');
                        $morder->addOrderLog($data);
                        $r_data['status']=1;
                        $r_data['msg']= "支付成功，待对账";
                    }else{
                        $r_data['msg']= "支付失败";
                    }
                }else {
                    //验证失败
                    $r_data['msg']= "支付验证失败";
                }
            }
        }else{
            $r_data['msg']="订单错误";
        }
        $this->data=$r_data;
       // Tpl::showpage('payment_message');
        $this->display();
    }

    /**
     * 支付提醒
     */
    public function notify_url() {
        $time=date("Y-m-d H:i:s");
        $verify_result = $this->_verify_result('notify');
        Logs::DEBUG("\n 【支付回调】 begin notify_url". $time);
        Logs::DEBUG("\n 【支付回调】 post call back:" . json_encode($_POST));
        Logs::DEBUG("\n 【支付回调】  notify_url 签名  ".json_encode($verify_result));
		if($verify_result){  //验证成功
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
            //商户订单号
            $out_trade_no = $_POST['out_trade_no'];
            //支付宝交易号
            $trade_no = $_POST['trade_no'];
            //交易状态
            $trade_status = $_POST['trade_status'];
            //收到TRADE_FINISHED请求后，这笔订单就结束了，支付宝不会再主动请求商户网站了；收到TRADE_SUCCESS请求后，后续一定还有至少一条通知记录，即TRADE_FINISHED
            if($_POST['trade_status'] == 'TRADE_FINISHED') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
                //如果有做过处理，不执行商户的业务程序
                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
                $res=$this->notify_url_update_order($_POST);
            }else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
                //如果有做过处理，不执行商户的业务程序

                //注意：
                //付款完成后，支付宝系统发送该交易状态通知

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
                $res=$this->notify_url_update_order($_POST);
            }
            if($res!==false){
                echo "success";		//验证订单更新成功
            }else{
                //验证订单更新失败
                echo "fail";
            }
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            //echo "success";		//请不要修改或删除
        }else {
		    //验证失败
		    echo "fail";
		}

    }
    //重写回调处理函数
    public function notify_url_update_order($data=array())
    {
        if(empty($data)){
            return false;
        }
        //商户订单号
        $out_trade_no = $data['out_trade_no'];
        //支付宝交易号
        $trade_no = $data['trade_no'];
        //交易状态

        //支付接口代码
        $payment_code = 'alipay';
        //验证成功
        $model_order = D('Mallorder');
        $model_payment = D('Mallpayment');
        //pay_status 支付状态；0，未付款；1，付款中 ；2，已付款
        $order_list = $model_order->getOrderList(array('order_sn'=>$out_trade_no,'pay_status'=>array('neq','2')));

        $result = $model_payment->updateProductBuy($out_trade_no, $payment_code, $order_list, $trade_no);
        if(empty($result['error'])) {
            $money=$data["total_fee"];
            //购买成功  查询是否是会员 不是会员则升级为会员
            $log=array();
            $member=M('member')->where(array('id'=>$order_list[0]['user_id']))->field('id,member_vip_type')->find();
            if($order_list[0]['is_upgrade']){//升级订单
                if($member && empty($member['member_vip_type'])) {
                    M('member')->where(array('id' => $order_list[0]['user_id']))->save(array('member_vip_type' => 1,'vip_time'=>time()));
                    Logs::DEBUG("\n 会员" . $member['member_card'] . "升级VIP成功");
                    $log['des'] = '用户使用支付宝支付￥' . $money . '升级会员成功';
                }else{
                    $log['des'] = '用户使用支付宝支付￥'.$money;
                }
            }else{
                $log['des'] = '用户使用支付宝支付￥'.$money;
            }
            //记录订单日志
            $data = array();
            $data['order_id'] = $order_list[0]['order_id'];
            $data['log_role'] = 'buyer';
            $data['log_msg'] = $log['des'];
            $data['log_orderstate'] = '20';
            $insert = $model_order->addOrderLog($data);

            Logs::DEBUG("\n 【支付回调】 notify_url update success:订单更新成功");
            $this->sendSMS($out_trade_no,$data['total_fee']);
            return true;
        }else{
            Logs::DEBUG("\n 【支付回调】 notify_url update error:" . json_encode($result));
            return false;
        }
        return true;
    }
    private function _verify_result($type) {
        unset($_GET['_URL_']);//将系统的控制参数置空，防止因为加密验证出错
        //读取接口配置信息
        $condition = array();
        $condition['payment_code'] = 'alipay';

        require_once(BASE_PATH.DS.'api/payment/alipay/lib/alipay_notify.class.php');
        require_once(BASE_PATH.DS.'api/payment/alipay/alipay.config.php'); //读取接口配置信息

        //var_dump($alipay_config);
		//计算得出通知验证结果
		$alipayNotify = new AlipayNotify($alipay_config);

        switch ($type) {
            case 'notify':
                $verify_result = $alipayNotify->verifyNotify();
                break;
            case 'return':
                $verify_result = $alipayNotify->verifyReturn();
                break;
            default:
                $verify_result = false;
                break;
        }

        return $verify_result;
    }

//定时发送
    /*
    $time = '2010-05-27 12:11';
    $res = sendSMS($uid,$pwd,$mobile,$content,$time);
    echo $res;
    */
    function sendSMS($order_sn=null,$money=null)
    {
        if(empty($order_sn) || empty($money)){
            return false;
        }
        $mobile=C('SENDMOBILE');
        if(empty($mobile)){
            return false;
        }
        $http = 'http://http.yunsms.cn/tx/';//接口地址 'http://接口地址/tx/';
        $content="分销系统产生一条新订单，订单号：".$order_sn."，金额,￥".$money."，时间".date("Y-m-d H:i")."支付宝支付";
        $data = array
        (
            'uid'=>C('SENDUID'),          //用户账号
            'pwd'=>strtolower(md5(C('SENDPWD'))), //MD5位32密码
            'mobile'=>$mobile,        //号码
            'content'=>$content,      //内容 如果对方是utf-8编码，则需转码iconv('gbk','utf-8',$content); 如果是gbk则无需转码
            'time'=>$time,    //定时发送
            'mid'=>$mid,           //子扩展号
            'encode'=>'utf8'
        );
        $re= $this->postSMS($http,$data);      //POST方式提交
        $return_data['status']=0;
        if( trim($re) == '100' )
        {
            $datas=json_encode($data);
            //$return_data['status']=1;
            //return $return_data;
            Logs::DEBUG("\n 【分销系统】短信发送成功:".$datas);
        }
        else
        {
            $datas=json_encode($data);
            //return "发送失败! 状态：".$re;
            $return_data['error']="";
            Logs::DEBUG("\n 【分销系统】短信发送失败:".$datas);
        }
    }
    public function postSMS($url,$data='')
    {
        $post='';
        $row = parse_url($url);
        $host = $row['host'];
        $port = $row['port'] ? $row['port']:80;
        $file = $row['path'];
        while (list($k,$v) = each($data))
        {
            $post .= rawurlencode($k)."=".rawurlencode($v)."&"; //转URL标准码
        }
        $post = substr( $post , 0 , -1 );
        $len = strlen($post);
        $fp = @fsockopen( $host ,$port, $errno, $errstr, 10);
        if (!$fp) {
            return "$errstr ($errno)\n";
        } else {
            $receive = '';
            $out = "POST $file HTTP/1.0\r\n";
            $out .= "Host: $host\r\n";
            $out .= "Content-type: application/x-www-form-urlencoded\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Content-Length: $len\r\n\r\n";
            $out .= $post;
            fwrite($fp, $out);
            while (!feof($fp)) {
                $receive .= fgets($fp, 128);
            }
            fclose($fp);
            $receive = explode("\r\n\r\n",$receive);
            unset($receive[0]);
            return implode("",$receive);
        }
    }
}


//以下为日志

interface ILogHandler
{
    public function write($msg);

}

class CLogFileHandler implements ILogHandler
{
    private $handle = null;

    public function __construct($file = '')
    {
        $this->handle = fopen($file,'a');
    }

    public function write($msg)
    {
        fwrite($this->handle, $msg, 4096);
    }

    public function __destruct()
    {
        fclose($this->handle);
    }
}

class Logs
{
    private $handler = null;
    private $level = 15;

    private static $instance = null;

    private function __construct(){}

    private function __clone(){}

    public static function Init($handler = null,$level = 15)
    {
        if(!self::$instance instanceof self)
        {
            self::$instance = new self();
            self::$instance->__setHandle($handler);
            self::$instance->__setLevel($level);
        }
        return self::$instance;
    }


    private function __setHandle($handler){
        $this->handler = $handler;
    }

    private function __setLevel($level)
    {
        $this->level = $level;
    }

    public static function DEBUG($msg)
    {
        self::$instance->write(1, $msg);
    }

    public static function WARN($msg)
    {
        self::$instance->write(4, $msg);
    }

    public static function ERROR($msg)
    {
        $debugInfo = debug_backtrace();
        $stack = "[";
        foreach($debugInfo as $key => $val){
            if(array_key_exists("file", $val)){
                $stack .= ",file:" . $val["file"];
            }
            if(array_key_exists("line", $val)){
                $stack .= ",line:" . $val["line"];
            }
            if(array_key_exists("function", $val)){
                $stack .= ",function:" . $val["function"];
            }
        }
        $stack .= "]";
        self::$instance->write(8, $stack . $msg);
    }

    public static function INFO($msg)
    {
        self::$instance->write(2, $msg);
    }

    private function getLevelStr($level)
    {
        switch ($level)
        {
            case 1:
                return 'debug';
                break;
            case 2:
                return 'info';
                break;
            case 4:
                return 'warn';
                break;
            case 8:
                return 'error';
                break;
            default:

        }
    }

    protected function write($level,$msg)
    {
        if(($level & $this->level) == $level )
        {
            $msg = "\r\n ".'['.date('Y-m-d H:i:s').']['.$this->getLevelStr($level).'] '.$msg."\n";
            $this->handler->write($msg);
        }
    }
    
}
