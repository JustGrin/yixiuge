<?php

/*代理*/

class AgentAction extends AuthAction
{
    //代理列表
    public function index()
    {
        $where = '';
        if ($_GET['member_name']) {
            $where['m.member_name'] = $_GET['member_name'];
        }
        if ($_GET['mobile']) {
            $where['m.mobile'] = $_GET['mobile'];
        }
        if ($_GET['member_card']) {
            $where['m.member_card'] = $_GET['member_card'];
        }
        if ($_GET['status'] !== null) {
            $where['agent.status'] = $_GET['status'];
        }
        $pre = C('DB_PREFIX');
        $join = $pre . 'member m on m.id=agent.member_id';
        $field = 'agent.*,m.member_name,m.mobile,m.member_card';
        $order = 'add_time desc';
        import('ORG.Util.Page');// 导入分页类
        $count = M()->table($pre . 'regional_agent agent')->where($where)->count();// 查询满足要求的总记录数
        $Page = new Page($count, 25);// 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show();// 分页显示输出
        $list = M()->table($pre . 'regional_agent agent')
            ->field($field)
            ->where($where)
            ->join($join)
            ->order($order)
            ->limit($Page->firstRow, $Page->listRows)
            ->select();
//        echo M()->_sql();
        /*查询所有省市区*/
        $city = $this->get_all_city();
        foreach ($list as $k => $v) {
            $list[$k]['province'] = $city['province'][$v['provinceid']];
            $list[$k]['city'] = $city['city'][$v['cityid']];
            $list[$k]['area'] = $city['area'][$v['areaid']];
            $list[$k]['agent_region'] = $list[$k]['province'] . $list[$k]['city'] . $list[$k]['area'];
        }
//        var_dump($list);die;
        $this->msgtitle = '代理';
        $this->page = $show;
        $this->list = $list;
        $this->display();
    }

    //代理编辑新增
    public function agent_edit()
    {
        if ($_POST) {   //执行操作==>编辑/新增
//            var_dump($_POST);die;
            if ($_GET['id']) {  //编辑
                $where['id'] = $_GET['id'];
                $res = M('regional_agent')->where($where)->count();
                if (!$res) {
                    $this->error('代理不存在');
                }
                $data = $_POST;
                $data['rate'] = $_POST['rate'] / 100;
//                var_dump($data);die;
                $re = M('regional_agent')->where($where)->save($data);
            } else { //新增
                $data = $_POST;
                $data['rate'] = $_POST['rate'] / 100;
                $data['add_time'] = time();
                $re = M('regional_agent')->add($data);
            }
            if ($re !== false) {
                $this->success('操作成功', U('admin/agent/index'));
                die;
            } else {
                $this->error('操作失败');
            }
        } else { //显示页面
            if ($_GET['id']) { //编辑
                $this->msgtitle = '编辑代理';
                $where['id'] = $_GET['id'];
                $data = M('regional_agent')->where($where)->find();
                $data['member_card'] = M('member')->where(array('id' => $data['member_id']))->getField('member_card');
                $city = $this->get_all_city();
                $data['city'] = $city['city'][$data['cityid']];
                $data['area'] = $city['area'][$data['areaid']];
                $this->data = $data;
            } else {
                $this->msgtitle = '新增代理';
            }
            //所有省份
            $province = $this->get_city_return();
            $this->province = $province['province'];
            $this->display();
        }
    }

    //代理删除
    public function agent_del()
    {
        $where['id'] = $_GET['id'];
        $res = M('regional_agent')->where($where)->count();
        if (!$res) {
            $this->error('代理不存在');
        }
        $re = M('regional_agent')->where($where)->delete();
        if ($re !== false) {
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }

    //用户弹窗
    public function popup_user()
    {
        $where = '';
        if ($_GET['member_name']) {
            $where['member_name'] = $_GET['member_name'];
        }
        if ($_GET['member_card']) {
            $where['member_card'] = $_GET['member_card'];
        }
        if ($_GET['mobile']) {
            $where['mobile'] = $_GET['mobile'];
        }
        $field = 'id,member_card,mobile,member_name,member_status,member_freeze';
        $order = 'add_time desc';
        $data = D('Common')->getPageList('member', $where, $field, $order);
        $this->list = $data['list'];
        $this->page = $data['page'];
//        var_dump($list);die;
        $this->display();
    }

    //验证当前区域下是否已经存在代理
    public function check_exist_agent()
    {
        if ($_POST['id']) {
            $where['areaid']=$_POST['areaid'];
            $member_id=M('regional_agent')->where($where)->getField('member_id');
            if($member_id==$_POST['member_id'] || $member_id==false){
                echo 0;
            }else{
                echo 1;
            }
        } else {
            $where['areaid'] = $_POST['areaid'];
            $re = M('regional_agent')->where($where)->count();
            echo $re;
        }
    }

    //获取所有城市
    public function get_all_city()
    {
        $province_arr = M('city_province')->select();
        foreach ($province_arr as $k => $v) {
            $city['province'][$v['provinceid']] = $v['province'];
        }
        $city_arr = M('city_city')->select();
        foreach ($city_arr as $k => $v) {
            $city['city'][$v['cityid']] = $v['city'];
        }
        $area_arr = M('city_area')->select();
        foreach ($area_arr as $k => $v) {
            $city['area'][$v['areaid']] = $v['area'];
        }
        return $city;
    }
}

?>