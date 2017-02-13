<?php

/**
 *公告
 */
class UsernoticeAction extends AuthAction{
    public function index(){
       $where = array();
        isset($_GET['title']) ?   $where['title']=array('like','%'.$_GET['title'].'%') : $_GET['title'] = '';
        $where['status'] = 1; //状态1,代表未删除,0代表已删除
        $data = D('Common')->getPageList('user_notice', $where, '', 'add_time desc', 20);
//        var_dump($data);die;
        $this->assign('data', $data);
        $this->display();
    }
	
    public function notice_edit(){
        if ($_POST) {  //有值时执行添加或者编辑操作
            if (isset($_GET['id'])) {    //get['id']有值代表更新,无值代表新增
                $where['id'] = $_GET['id'];
                $data = $_POST;
                $re = M('user_notice')->where($where)->save($data);
            } else {
                $data = $_POST;
                $data['add_time'] = time();
                $data['auid']=session("auid");
                $data['author']=session("name");
                $re = M('user_notice')->add($data);
            }
            if ($re !== false) {
                $this->success('操作成功',U('admin/usernotice/index'));
                die;
            } else {
                $this->error('操作失败');
            }
        } else {   //无值时显示该页面
            $id = isset($_GET['id']) ? $_GET['id'] : 0 ;
            if ($id) {    //get['id']有值代表新增,无值代表新增
                $where['id'] = $id;
                $data = M('user_notice')->where($where)->find();
                $this->assign('data', $data);
                $msgtitle = '编辑文章';
            } else {
                $msgtitle = '发布文章';
            }
            $this->id =$id;

            $this->assign('msgtitle', $msgtitle);
            $this->display();
        }
    }
    //删除通知
    public function notice_del(){
        if($_GET['id']){
            $where['id']=$_GET['id'];
            $re=M('user_notice')->where($where)->delete();
            if($re){
                $this->success('操作成功');die;
            }else{
                $this->error('操作失败');
            }
        }else{
            $this->error('文章不存在');
        }
    }
    //推文列表
    public function tweet_list(){
       $where = array();
        isset($_GET['title']) ?  $where['title'] = array('like', '%' . $_GET['title'] . '%') : $_GET['title'] = '';
        $where['type'] = 2;//0普通文章 1 活动文章 2微信SEO
        $data = D('Common')->getPageList('article_tweet', $where, '', 'add_time desc', 20);
        $this->assign('data', $data);
        $this->display();
    }
	
    //推文编辑
    public function tweet_edit(){
        if ($_POST) {  //有值时执行添加或者编辑操作
			$imgs = array();
			$imgs[] = $_POST['img'];
			$is_imgs = false;
			for($i=2; $i<=9; $i++){
				if(isset($_POST['img'.$i])){
					$imgs[] = $_POST['img'.$i];
					unset($_POST['img'.$i]);
					$is_imgs = true;
				}else{
					break;
				}
			}
            if (!empty($_GET['id'])) {    //get['id']有值代表更新,无值代表新增
                $where['id'] = $_GET['id'];
                $data = $_POST;
				if($is_imgs){
					$data['imgs_a'] = serialize($imgs);
				}else{
					$data['imgs_a'] = '';
				}
                $re = M('article_tweet')->where($where)->save($data);
            } else {
                $data = $_POST;
				if($is_imgs){
					$data['imgs_a'] = serialize($imgs);
				}else{
					$data['imgs_a'] = '';
				}
                $data['add_time'] = time();
                $data['auid'] = session("auid");
                $data['author'] = session("name");
                $re = M('article_tweet')->add($data);
            }
            if ($re !== false) {
                $this->success('操作成功', U('admin/usernotice/tweet_list'));
                die;
            } else {
                $this->error('操作失败');
            }
        } else {   //无值时显示该页面
            $auid=session('auid');
			$data['img_num'] = 1;
            $id  = isset($_GET['id']) ? $_GET['id'] : 0;
            if ($id) {    //get['id']有值代表新增,无值代表新增
                $where['id'] = $id;
                $data = M('article_tweet')->where($where)->find();
				$data['img_num'] = 1;
				if(isset($data['imgs_a']) && $data['imgs_a']){
					$data['imgs'] = unserialize($data['imgs_a']);
					$data['img_num'] = count($data['imgs']);
				}
                $goods=M('g_goods')->field('goods_img')->where(array('goods_id'=>$data['goods_id']))->find();
                $data['goods_img']=$goods['goods_img'];
                $msgtitle = '编辑文章';
            } else {
                $msgtitle = '发布文章';
            }
            $this->assign('msgtitle', $msgtitle);
            $this->id =$id;
            $this->assign('data', $data);
            $this->auid=$auid;
            $this->display();
        }
    }
    //推文编辑 审核
    public function tweet_edit_sh(){
        $auid=session('auid');
        if ($_POST) {  //有值时执行添加或者编辑操作
//            var_dump($_POST);die;
            if (!empty($_GET['id'])) {    //get['id']有值代表更新,无值代表新增
                $where['id'] = $_GET['id'];
                $data=$_POST;
                $re = M('article_tweet')->where($where)->save($data);
            } else {
                $data = $_POST;
                $data['add_time'] = time();
                $data['auid'] = session("auid");
                $data['author'] = session("name");
//                var_dump($data);die;
                $re = M('article_tweet')->add($data);
            }
            if ($re !== false) {
                $this->success('操作成功', U('admin/usernotice/tweet_list'));
                die;
            } else {
                $this->error('操作失败');
            }
        } else {   //无值时显示该页面
            if (!empty($_GET['id'])) {    //get['id']有值代表新增,无值代表新增
                $where['id'] = $_GET['id'];
                $data = M('article_tweet')->where($where)->find();
                $goods=M('g_goods')->field('goods_img')->where(array('goods_id'=>$data['goods_id']))->find();
                $data['goods_img']=$goods['goods_img'];
                $msgtitle = '编辑文章';
            } else {
                $msgtitle = '发布文章';
            }
        }
        $this->auid=$auid;
        $this->assign('data', $data);
        $this->assign('msgtitle', $msgtitle);
        $this->display();
    }
    //删除推文
    public function tweet_del(){
        if ($_GET['id']) {
            $where['id'] = $_GET['id'];
            $re = M('article_tweet')->where($where)->delete();
            if ($re) {
                $this->success('操作成功');
                die;
            } else {
                $this->error('操作失败');
            }
        }else {
            $this->error('文章不存在');
        }
    }
     //推文列表
    public function tweet_list_fx(){

        isset($_GET['title']) ?  $where['title'] = array('like', '%' . $_GET['title'] . '%') : $_GET['title'] = '';
         $where['type']=1;//0普通文章 1 活动文章
        $data = D('Common')->getPageList('article_tweet', $where, '', 'add_time desc', 20);
//        var_dump($data);die;
        $this->assign('data', $data);
        $this->display();
    }
    //推文编辑
    public function tweet_edit_fx(){
        $auid=session('auid');
        if ($_POST) {  //有值时执行添加或者编辑操作
//            var_dump($_POST);die;
            if (!empty($_GET['id'])) {    //get['id']有值代表更新,无值代表新增
                $where['id'] = $_GET['id'];
                $data=$_POST;
                $re = M('article_tweet')->where($where)->save($data);
            } else {
                $data = $_POST;
                $data['add_time'] = time();
                $data['auid'] = session("auid");
                $data['author'] = session("name");
//                var_dump($data);die;
                $re = M('article_tweet')->add($data);
            }
            if ($re !== false) {
                $this->success('操作成功', U('admin/usernotice/tweet_list_fx'));
                die;
            } else {
                $this->error('操作失败');
            }
        } else {   //无值时显示该页面
            if (!empty($_GET['id'])) {    //get['id']有值代表新增,无值代表新增
                $where['id'] = $_GET['id'];
                $data = M('article_tweet')->where($where)->find();
                $goods=M('g_goods')->field('goods_img')->where(array('goods_id'=>$data['goods_id']))->find();
                $data['goods_img']=$goods['goods_img'];
                $msgtitle = '编辑文章';
            } else {
                $msgtitle = '发布文章';

            }
        }
        $data['type']=1;
        $this->auid=$auid;
        $this->assign('data', $data);
        $this->assign('msgtitle', $msgtitle);
        $this->display();
    }
    //删除推文
    public function tweet_del_fx(){
        if ($_GET['id']) {
            $where['id'] = $_GET['id'];
            $re = M('article_tweet')->where($where)->delete();
            if ($re) {
                $this->success('操作成功');
                die;
            } else {
                $this->error('操作失败');
            }
        }else {
            $this->error('文章不存在');
        }
    }
     //分销detail
     public function tweet_detail_fx(){
         $article_id = $_GET['id'];
          $pre=C('DB_PREFIX');  //表前缀
          $field='art.*,g.goods_id,g.goods_name,g.goods_brief,g.shop_price,g.goods_img'
          .',g.purchase_price,g.goods_desc,g.goods_img,g.shop_price';
          //获取商品信息
          $re=M()->table($pre.'article_tweet art')
          ->field($field)
          ->where(array('art.id'=>$article_id))
          ->join($pre.'g_goods g on art.goods_id=g.goods_id')
          ->find();

         if($re){
                $host_url=$url='HTTP://'.$_SERVER['HTTP_HOST'];
                $data=$host_url.U("wap/usernotice/tweet_detail",array('id'=>$re['id']));
                $re['qrcode']=$this->get_qrcode($data);
         }
         $this->data=$re;
         $this->display();
     }
    //关联推文弹窗
    public function popup_tweet_list(){
        if ($_GET['title']) {
            $where['title'] = array('like', '%' . $_GET['title'] . '%');
        }
        if($_GET['id']){
            $where['id']=array('neq',$_GET['id']);
        }
        $p = $_REQUEST['p'];
        $pagesize = 10;
        $p = !empty($p) ? $p : 1;
        $start = ($p - 1) * $pagesize;
        $data=M('article_tweet')->where($where)->order('add_time desc')->limit($start,$pagesize)->select();
//        echo M()->_sql();die;
//        var_dump($data);die;
        $this->count = M('g_goods')->where($where)->count();
        $this->assign('data', $data);
        $this->display();
    }
    //奖品领取列表
    public function activity_receive_list(){
        $pre=C('DB_PREFIX');  //获取表前缀
        import('ORG.Util.Page');// 导入分页类
        $count = M('activity_receive')->count();// 查询满足要求的总记录数
        $Page = new Page($count, 20);// 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show();// 分页显示输出
        $field='act.*,mem.member_name,mem.member_card';
        $join=$pre.'member mem on act.member_id=mem.id';
        //进行分页数据查询
        $data=M()->table($pre.'activity_receive act')
            ->field($field)
            ->join($join)
            ->order('act.add_time desc')
            ->limit($Page->firstRow.','.$Page->listRows)
            ->select();
        $this->data=$data;
        $this->page=$show;
//        echo M()->_sql();
//        var_dump($data);die;
        $this->display();
    }
    //奖品领取列表
    public function activity_receive_detail(){
        if(IS_POST){
            if($_GET['id']){
                $where['activity_id']=$_GET['id'];
                $data['invoice_no']=$_POST['invoice_no'];
                $data['status']=1;
                $data['invoice_time']=time();
                $re=M('activity_receive')->where($where)->save($data);
            }
            if($re != false){
                $this->success('操作成功');die;
            }else{
                $this->error('操作失败');
            }
        }
        if($_GET['id']){
            $where['activity_id']=$_GET['id'];
        }
        $pre = C('DB_PREFIX');  //获取表前缀
        $field='act.*,mem.member_card,mem.member_name';
        $join=$pre.'member mem on act.member_id=mem.id';
        $data=M()->table($pre.'activity_receive act')
            ->field($field)
            ->where($where)
            ->join($join)
            ->find();
        $this->data=$data;
        $this->display();
    }
    //浏览量统计
    public function browse_stat(){
        $where['id']=$_POST['id'];
        M('article_tweet')->where($where)->setInc('page_view');
    }
}