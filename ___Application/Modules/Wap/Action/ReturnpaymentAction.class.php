<?php
/**
 * 支付回调
 *
 *
 */


class ReturnpaymentAction extends Action{

	public function __construct() {
		parent::__construct();
	}
   /**
 * 通用通知接口demo
 * ====================================================
 * 支付完成后，微信会把相关支付和用户信息发送到商户设定的通知URL，
 * 商户接收回调信息后，根据需要设定相应的处理流程。
 * 
 * 这里使用log文件形式记录回调信息。 以及更新订单状态等
*/
    public function index() {

     include_once("./api/payment/wpay/log_.php");
    include_once("./api/payment/wpay/WxPayPubHelper/WxPayPubHelper.php");

      //使用通用通知接口
    $notify = new Notify_pub();

    //存储微信的回调
    $xml = $GLOBALS['HTTP_RAW_POST_DATA'];  
    $notify->saveData($xml);
    
    //验证签名，并回应微信。
    //对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
    //微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
    //尽可能提高通知的成功率，但微信不保证通知最终能成功。
    if($notify->checkSign() == FALSE){
        $ret_data['return_code']="FAIL";
        $ret_data['return_code']="签名失败";
        $notify->setReturnParameter("return_code","FAIL");//返回状态码
        $notify->setReturnParameter("return_msg","签名失败");//返回信息
    }else{
         //商户订单号
            $out_trade_no = $_GET['out_trade_no'];
            //支付宝交易号
            $trade_no = $_GET['trade_no'];
            //支付接口代码
            $payment_code = 'wxpay';

            //验证成功      
            $model_order = D('Order');
            $model_payment = D('Payment');
            $pay_record_id = M("g_order_pay")->where(array('pay_sn'=>$out_trade_no))->getField('pay_id');
            $order_list = $model_order->getOrderList(array('pay_record_id'=>$pay_record_id,'order_state'=>'10'));

            $result = $model_payment->updateProductBuy($out_trade_no, $payment_code, $order_list, $trade_no);
            if(empty($result['error'])) {
                 $notify->setReturnParameter("return_code","SUCCESS");//设置返回码
            }else{
                 $ret_data['return_code']="FAIL";
                 $ret_data['return_code']="订单信息更新失败";
                 $notify->setReturnParameter("return_code","FAIL");//返回状态码
                 $notify->setReturnParameter("return_msg","订单信息更新失败");//返回信息
            }
        
    }
    $returnXml = $notify->returnXml();
    echo $returnXml;
    
    //==商户根据实际情况设置相应的处理流程，此处仅作举例=======
    
    //以log文件形式记录回调信息
    $log_ = new Log_();
    $log_name="./api/payment/wpay/notify_url.log";//log文件路径
    $log_->log_result($log_name,"【接收到的notify通知】:\n".$xml."\n");

    if($notify->checkSign() == TRUE)
    {
        if ($notify->data["return_code"] == "FAIL") {
            //此处应该更新一下订单状态，商户自行增删操作
            $log_->log_result($log_name,"【通信出错】:\n".$xml."\n");
        }
        elseif($notify->data["result_code"] == "FAIL"){
            //此处应该更新一下订单状态，商户自行增删操作
            $log_->log_result($log_name,"【业务出错】:\n".$xml."\n");
        }
        else{
            //此处应该更新一下订单状态，商户自行增删操作
            if($ret_data['return_code'] == "FAIL"){
                  $log_->log_result($log_name,"【支付成功】:\n".$xml."\n");
            }else{
                 $log_->log_result($log_name,"【支付成功】:\n".$xml."\n");
            }
           
        }
        
        //商户自行增加处理流程,
        //例如：更新订单状态
        //例如：数据库操作
        //例如：推送支付完成信息
    }

    }

     public function test() {

     include_once("./api/payment/wpay/log_.php");
    //include_once("./api/payment/wpay/WxPayPubHelper/WxPayPubHelper.php");

    
  //商户订单号
    $out_trade_no = $_GET['out_trade_no'];
    //支付宝交易号
    $trade_no = $_GET['trade_no']=time();
    //支付接口代码
    $payment_code = 'wxpay';
    //验证成功      
    $model_order = D('Order');
    $model_payment = D('Payment');

    $order_list = $model_order->getOrderList(array('pay_sn'=>$out_trade_no,'order_state'=>'10'));

    $result = $model_payment->updateProductBuy($out_trade_no, $payment_code, $order_list, $trade_no);
    if(empty($result['error'])) {
        echo "SUCCESS";
    }else{
        echo "FAIL";
    }
    
    //==商户根据实际情况设置相应的处理流程，此处仅作举例=======
    
    //以log文件形式记录回调信息
    $log_ = new Log_();
    $log_name="./api/payment/wpay/notify_url.log";//log文件路径
    $log_->log_result($log_name,"【接收到的notify通知】:$out_trade_no");

   if($ret_data['return_code'] == "FAIL"){
          $log_->log_result($log_name,"【支付失败】:$out_trade_no");
    }else{
         $log_->log_result($log_name,"【支付成功】:$out_trade_no");
    }

    }
  
}
