<?php

/**
 * Author: gqh
 * Date: 2015/10/31
 * Time: 14:35
 */
class DrawAction extends AuthAction{
    //抽奖活动列表 gqh
    public function index(){
        $data=D('Common')->getPageList('draw_progressive',$where = array(),'*','add_time desc', $pagesize = 20);
//        var_dump($data);die;
        $where=array('draw_status'=>1,'is_shenghe'=>1);
        $odds = M('draw_progressive')->field('sum(draw_chances_value) as odds')->where($where)->find();  //计算总中奖基数
        if($data['list'] ){
            foreach($data['list'] as $k => $v){
                $data['list'][$k]['odds']= sprintf("%.1f",($v['draw_chances_value']/$odds['odds'])*100);  //获得中奖率
                if($v['draw_type']==5){ //当奖品为商品时，获取商品信息
                    $field='goods_name';
                    $where['goods_id']= $v['draw_value'];
                    $goods_msg=M('g_goods')->field($field)->where($where)->find();
                    $data['list'][$k]['goods_msg']=$goods_msg['goods_name'];
                }
            }
        }

//        var_dump($data);die;
        $data['list']=$this->get_draw_type($data['list']);  //获取奖品类型
        $data['page'] = isset($data['page']) ?  $data['page'] : '';
//        var_dump($data);die;
        $this->assign('data',$data);
        $this->display();
    }
    //抽奖活动编辑 gqh
    public function draw_edit(){
//        var_dump($_POST);die;
        if($_POST){  //有值代表 执行 新增或者编辑操作
            if($_GET['id']){  //代表执行编辑操作
                $where['id']=$_GET['id'];
                $re=M('draw_progressive')->where($where)->save($_POST);
                if ($re) {
                    $this->success('操作成功', U('admin/Draw/index'), 3);
                    die;
                } else {
                    $this->error('操作失败');
                }
            }else{  //代表执行新增操作
                $_POST['add_time'] =time();
                $re = M('draw_progressive')->add($_POST);
                if ($re) {
                    $this->success('操作成功', U('admin/Draw/index'), 3);
                    die;
                } else {
                    $this->error('操作失败');
                }
            }
        }else{   //无值代表显示当前页面
            $id = isset($_GET['id']) ? $_GET['id'] : 0 ;
            if($id){   //有值代表显示编辑页面
                $this->msgtitle='编辑抽奖';
                $where['id']=$id;
                $data=M('draw_progressive')->where($where)->find();
                $this->data=$data;
            }else{ //无值代表显示新增页面
                $this->msgtitle='新增抽奖';
            }
        }
        $this->id = $id;
        $this->display();
    }
    //删除抽奖活动 gqh
    public function draw_del(){
        $where['id']=$_GET['id'];
        $re=M('draw_progressive')->where($where)->delete();
//        echo M()->_sql();die;
        if($re){
            $this->error('操作成功',U('admin/Draw/index'));
        }else{
            $this->error('操作失败');
        }
    }
    //抽奖记录 gqh
    public function  draw_log(){
        $where = array();
        $cwhere = array();
        if(!empty($_GET['log_status']) && $_GET['log_status']!=10){
            $cwhere['log.log_status']= $_GET['log_status'];
            $where['log_status']= $_GET['log_status'];
        }else{
            $_GET['log_status'] = '';
        }
        if(!empty($_GET['draw_type']) && $_GET['draw_type'] !=0){
            $cwhere['log.draw_type'] = $_GET['draw_type'];
            $where['draw_type'] = $_GET['draw_type'];
        }else{
            $_GET['draw_type'] = '';
        }
//        var_dump($cwhere);
        $pre=C('DB_PREFIX'); //表前缀
        import('ORG.Util.Page');// 导入分页类
        $count=M('draw_log')->where($where)->count();   //查询满足记录的总数
        $page=new Page($count,20);//传入记录总数及每页显示数量
        $show=$page->show();
        //进行分页记录查询
        $pre = C('DB_PREFIX'); //表前缀
        $field = 'log.*,user.member_id,user.member_name,draw.draw_chances_value';
        $join_O='join '.$pre.'member user on user.id=log.member_id';
        $join_T=' join '.$pre.'draw_progressive draw on draw.id=log.draw_id';
        $order='log.add_time desc';
        $list=M()->table($pre.'draw_log log')->field($field)->where($cwhere)->join($join_O)->join($join_T)->limit($page->firstRow.','.$page->listRows)->order($order)->select();
//        echo M()->_sql();die;
//        var_dump($list);die;
        $list=$this->get_draw_type($list);   //获取奖品类型名
//        var_dump($list);die;
        $where = array('draw_status' => 1, 'is_shenghe' => 1);
        $odds = M('draw_progressive')->field('sum(draw_chances_value) as odds')->where($where)->find();  //计算总中奖基数
        $status = array(0 => '无效', 1 => '未发放', 2 => '处理中', 5 => '已发放');  //中奖状态0无效 1未到账 2处理中 5已到帐
        foreach($list as $k=>$v){
            $list[$k]['odds'] = sprintf("%.1f", ($v['draw_chances_value'] / $odds['odds'])*100);  //获得中奖率
            if($v['draw_type']=5){  //奖品为商品时获取商品信息
                $field = 'goods_name';
                $where['goods_id'] = $v['draw_value'];
                $goods_msg = M('g_goods')->field($field)->where($where)->find();
                $list[$k]['goods_msg'] = $goods_msg['goods_name'];
            }
            $list[$k]['status']=$status[$v['log_status']];
        }
        $data['list']=$list;
        $data['page']=$show;
//        var_dump($data);
//        die;
        $this->assign('data',$data);
        $this->display();
    }
    //中奖详细信息 gqh
    public function draw_detail(){
        $where['draw_log_id'] = $_GET['id'];
        //获取奖池信息
        $draw = M('draw_progressive')->where($where)->find();
        //奖品类型 1积分 2金币 3话费4折扣5商品
        if($_GET['type']==5){  //奖品类型为商品
            $data=M('draw_address')->where($where)->find();
            $data['draw_type']=$_GET['type'];
            //获取商品信息
            $field = 'goods_id,goods_name';
            $where['goods_id'] = $draw['draw_value'];
            $goods_msg = M('g_goods')->field($field)->where($where)->find();
            $data['goods_name'] = $goods_msg['goods_name'];
            $data['goods_id']= $goods_msg['goods_id'];
        }
        if($_GET['type']==3){ //奖品类型为话费
            $data=M('draw_recharge')->where($where)->find();
            $data['draw_type'] = $_GET['type'];
            $member_msg=M('member')->field('member_name')->where(array('id'=>$data['member_id']))->find();
            $data['member_name']=$member_msg['member_name'];
            $status = array(0 => '无效', 1 => '未发放', 2 => '处理中', 3 => '已发放');  //中奖状态0无效 1未到账 2处理中 5已到帐
            $data['status']=$status[$data['status']];
        }
        if (in_array($_GET['type'],array(1,2,4))) { //奖品类型为其它类型
            $where['id']=$_GET['id'];
            $data=M('draw_log')->where($where)->find();
//            echo M()->_sql();
            $member_msg = M('member')->field('member_name')->where(array('id' => $data['member_id']))->find();
            $data['member_name'] = $member_msg['member_name'];
            $status = array(0 => '无效', 1 => '未发放', 2 => '处理中', 3 => '已发放');  //中奖状态0无效 1未到账 2处理中 5已到帐
            $data['log_status'] = $status[$data['log_status']];

        }
        $data=$this->get_draw_type($data);
//        var_dump($data);die;
        $this->assign('data',$data);
        $this->display();
    }
    //ajax新增抽奖键盘为商品时弹出商品  gqh
    public function popup(){
        if ($_GET['goods_name']) {
            $where['goods_name'] = array('like', '%' .$_GET['goods_name'] . '%');
        }
        if ($_GET['goods_sn']) {
            $where['goods_sn'] = array('like','%'.$_GET['goods_sn'].'%');
        }
        $field='goods_id,goods_name,goods_img,goods_sn,shop_price';
        $where['is_delete'] = 0;
        $where['is_on_sale']=1;
        $where['is_auditing']=1;
//		echo M()->_sql();die;
        $p = $_REQUEST['p'];
        $pagesize = 10;
        $p = !empty($p) ? $p : 1;
        $start = ($p - 1) * $pagesize;
        $list=M('g_goods')->field($field)->where($where)->order('goods_id desc')->limit($start, $pagesize)->select();

        if(IS_AJAX){
            echo json_encode($list);die;
        }
        $this->assign("list", $list);
        $this->display();
    }
    //ajax操作奖品发放 gqh
    public function draw_dispose(){
        if($_POST){  //执行奖品发放操作
            if($_POST['type']==5){  //奖品为商品
                $where['draw_log_id']=$_POST['id'];
                $data['status']=1;
                $data['invoice_time']=time();
                $data['invoice_no']=$_POST['invoice'];
                $lwhere['id']= $_POST['id'];
                $ldata['log_status']=5;
                $add=M('draw_address');
                $log=M('draw_log');
                $add->startTrans();// 事务开启
                    $re_log = $log->where($lwhere)->save($ldata);
                    $re_add=$add->where($where)->save($data);
                if($re_log && $re_add){
                    $add->commit();   //成功则提交
                    echo json_encode($re_add);
                }else{
                    $add->rollback();  //不成功，则回滚
                    echo json_encode($re_add);
                }
            }
            if ($_POST['type'] == 3) {   //奖品为话费
                $where['draw_log_id']=$_POST['id'];
                $data['status'] = 5;
                $data['recharge_img']=$_POST['recharge_img'];
                $data['recharge_no']=$_POST['recharge_no'];
                if(!empty($_POST['recharge_remark'])){
                    $data['recharge_remark']=$_POST['recharge_remark'];
                }
                $lwhere['id']= $_POST['id'];
                $ldata['log_status'] = 5;
                $log=M('draw_log');
                $rec=M('draw_recharge');
                $rec->startTrans();// 事务开启
                $re_log=$log->where($lwhere)->save($ldata);
                echo M()->_sql();
                $re_rec=$rec->where($where)->save($data);
//                echo M()->_sql();
//                var_dump($re_log);
//                var_dump($re_rec);
                if($re_log && $re_rec){
                    $rec->commit();   //成功则提交
                    echo json_encode($re_rec);
                }else{
                    $rec->rollback();  //不成功，则回滚
                    echo json_encode($re_rec);
                }
            }
        }else{   //显示奖品方法页面
            $where['draw_log_id'] = $_GET['id'];
            if ($_GET['type'] == 5) {  //商品发放
                $data = M('draw_address')->where($where)->find();
                $data['type']=$_GET['type'];
            }
            if ($_GET['type'] == 3) {  //话费充值
                $data=M('draw_recharge')->where($where)->find();  //查询话费充值信息
                $member_msg=M('member')->field('member_name')->where(array('id'=>$data['member_id']))->find();//查询用户信息
                $data['member_name']=$member_msg['member_name'];
                $data['type'] = $_GET['type'];
            }
            $this->assign('data', $data);
        }
//        echo M()->_sql();
//        var_dump($data);die;
        $this->display();
    }
    //抽奖活动快速审核,AJAX请求 gqh
    public function setVerify(){
        if($_GET['id']){
            $where['id']=$_GET['id'];
            $data['is_shenghe']=$_POST['is_shenghe'];
            $re=M('draw_progressive')->where($where)->save($data);
            if($re){
                echo json_encode($re);die;
            }
            echo json_encode($re);
        }
    }
    //取得奖品类型 gqh
    public function get_draw_type($data=array()){
        if(empty($data)){
            return array();
        }
        $type=array(1=>'积分',2=>'金币',3=>'话费',4=>'商品折扣',5=>'商品');    //'奖品类型 1积分 2金币 3话费4折扣5商品',
        foreach($data as $k=>$v){
            if(is_array($v)){
            $data[$k]['type_name']=$type[$data[$k]['draw_type']];
            }else {
                $data['type_name'] = $type[$data['draw_type']];
            }
        }
        return $data;
    }
}