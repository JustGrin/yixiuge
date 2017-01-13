<?php

/*会员升级*/

class UpgradeAction extends AuthAction
{
    //商品列表	10-12,gqh新增获取货号,通过货号进行模糊查询
    public function index()
    {
        /*if(empty($_SESSION['topadmin'])){
            $where['store_id']=session('afid');
        }*/
        if ($_GET['goods_name']) {
            $where['goods_name'] = array('like', $_GET['goods_name'] . '%');
        }
        if ($_GET['goods_sn'] && $_GET['goodsNum'] != 0) {
            $where['goods_sn'] = array('like', array('%' . $_GET['goodsNum'] . '%', '%' . $_GET['goods_sn'] . '%'), 'AND');
            //$where['_sting'] = " goods_sn like '%".$_GET['goodsNum']."%' AND goods_sn like '%".$_GET['goods_sn']."%'";
        } else {
            if ($_GET['goods_sn']) {
                $where['goods_sn'] = array('like', '%' . $_GET['goods_sn'] . '%');
            }
            if ($_GET['goodsNum']) {
                $where['goods_sn'] = array('like', '%' . $_GET['goodsNum'] . '%');
            }

        }
        if ($_GET['cat_id']) {
            $where['cat_id'] = array('eq', $_GET['cat_id']);
        }
        if ($_GET['brand_id']) {
            $where['brand_id'] = array('eq', $_GET['brand_id']);
        }
        if ($_GET['goods_type']) {
            $where['goods_type'] = array('eq', $_GET['goods_type']);
        }
        if (isset($_GET['order'])) {
            $order = $_GET['order'] . " desc";
        }
        if (isset($_GET['is_on_sale'])) {
            if ($_GET['is_on_sale'] != 'all') {
                $where['is_on_sale'] = $_GET['is_on_sale'];
            }
        } else {
            $_GET['is_on_sale'] = 'all';
        }
        if (isset($_GET['offline'])) {
            if ($_GET['offline'] != 'all') {
                $where['offline'] = $_GET['offline'];
            }
        } else {
            $_GET['offline'] = 'all';
        }

        if (isset($_GET['is_auditing'])) {
            if ($_GET['is_auditing'] != 'all') {
                $where['is_auditing'] = $_GET['is_auditing'];
            }
        } else {
            $_GET['is_auditing'] = 'all';
        }

        $where['is_delete'] = 0;
        $where['is_upgrade']=array('neq',0);
        $goodsNumList = M('g_category')->where(array('parent_id' => 0))->field('goods_sn')->select();
        $this->assign('goodsNumList', $goodsNumList);

        $REQUEST_URI = $_SERVER['REQUEST_URI'];
        cookie('now_url', $REQUEST_URI);
        $menulist = D("Common")->getPageList('g_goods', $where, "*", "goods_id desc", 10);
//		echo M()->_sql();die;
//        var_dump($menulist);die;
        $this->assign("list", $menulist);
//订单状态：0(已取消)10(默认):未付款;20:已付款;30:已发货;40:已收货;
        $order_state = array('0' => '已取消', '10' => '待付款', '20' => '已付款', '30' => '已完成');
        $this->order_state = $order_state;
        $this->display();
        //$this->display();
    }

    //获取顶级分类
    public function get_top_cat($cat_id = 0)
    {
        if (empty($cat_id)) {
            return array();
        }
        $c_where['cat_id'] = $cat_id;
        $goods_sn = M('g_category')->field('cat_id,goods_sn,parent_id')->where($c_where)->find();
        if ($goods_sn['parent_id'] == 0) {
            return $goods_sn;
        } else {
            return $this->get_top_cat($goods_sn['parent_id']);//
        }

    }

    //获取分类的下级分类 cat_id 组合 hkj
    public function get_category_child($pid = 0)
    {
        $list = M('g_category')->where(array('parent_id' => $pid, 'is_show' => 1))->field('cat_id')->select();
        if ($pid) {
            $type_str = ',' . $pid;
        } else {
            $type_str = ',';
        }
        if ($list) {//查下级
            $type_str1 = "";
            foreach ($list as $key => $value) {
                $type_str1 .= ',' . $value['cat_id'];
            }
            if ($type_str1) {
                $type_str .= $type_str1;
                $type_str1 = substr($type_str1, 1);
                if ($type_str1) {//查下级
                    $type_str2 = "";
                    $list = M('g_category')->where(array('parent_id' => array('in', $type_str1), 'is_show' => 1))->field('cat_id')->select();
                    foreach ($list as $key => $value) {
                        $type_str2 .= ',' . $value['cat_id'];
                    }
                    if ($type_str2) {
                        $type_str .= $type_str2;
                        unset($list);
                    }
                }
            }
        }
        if ($type_str) {
            $type_str = substr($type_str, 1);
        }
        return $type_str;
    }

    //编辑用户信息 update hkj 分类修改后 货号也修改  gqh:增加商品促销设置
    public function goods_edit()
    {
//		var_dump($_POST);die;
        if ($_POST) {
            if ($_POST['promote_start_date'] && $_POST['promote_end_date']) {   //将活动时间转换成时间戳
                $_POST['promote_start_date'] = strtotime($_POST['promote_start_date']);
                $_POST['promote_end_date'] = strtotime($_POST['promote_end_date']);
            }
            if ($_POST['goods_id']) {
                $goods_type = M('g_goods')->where(array('goods_id' => $_POST["goods_id"]))->getField('cat_id');
                if ($goods_type != $_POST['cat_id']) {
                    //$c_where['cat_id']=$_POST['cat_id'];
                    //$goods_sn=M('g_category')->field('goods_sn')->where($c_where)->find();
                    $goods_sn_old = $this->get_top_cat($goods_type);
                    $goods_sn = $this->get_top_cat($_POST['cat_id']);
                    if ($goods_sn_old['cat_id'] != $goods_sn['cat_id']) {//顶级分类不一样
                        if ($goods_sn) {
                            $car_str = $this->get_category_child($goods_sn['cat_id']);
                            $c_where['goods_sn'] = array('like', $goods_sn['goods_sn'] . '%');
                            $c_where['cat_id'] = array('in', $car_str);
                        } else {
                            $c_where['cat_id'] = $_POST['cat_id'];
                        }
                        $re = M('g_goods')->field('goods_sn')->where($c_where)->count();
                        if (empty($re)) {
                            $goods_sn = $goods_sn['goods_sn'] . '001';
                            $_POST['goods_sn'] = $goods_sn;
                        } else {
                            $last = M('g_goods')->where($c_where)->order('goods_sn desc')->getField('goods_sn'); //获取该分类下最后一条记录的货号
                            $last = str_replace($goods_sn['goods_sn'], '', $last);  //替换前缀
                            $last++;   //自增加1
                            $strlen = strlen($last);   //计算长度
                            if ($strlen < 3) {
                                $rep = 3 - $strlen;
                                $goods_sn = $goods_sn['goods_sn'] . str_repeat('0', $rep) . $last;
                            }
                            $_POST['goods_sn'] = $goods_sn;
                        }
                    }
                }
             
                $g_where["goods_id"] = $_POST["goods_id"];
                $topadmin = session('topadmin');
                if ($topadmin != 1) {
                    unset($_POST['is_auditing']);//去除 审核状态
                }
                $res = D("Goods")->editGoods($_POST, $g_where);
            } else {
                //$goods_sn=M('g_category')->field('goods_sn')->where($g_where)->find();
                $goods_sn = $this->get_top_cat($_POST['cat_id']); //获取顶级分类
                if ($goods_sn) {
                    $car_str = $this->get_category_child($goods_sn['cat_id']);
                    $c_where['goods_sn'] = array('like', $goods_sn['goods_sn'] . '%');
                    $c_where['cat_id'] = array('in', $car_str);
                } else {
                    $c_where['cat_id'] = $_POST['cat_id'];
                }
                $re = M('g_goods')->field('goods_sn')->where($c_where)->count();
                if (empty($re)) {
                    $goods_sn = $goods_sn['goods_sn'] . '001';
                    $_POST['goods_sn'] = $goods_sn;
                } else {
                    $last = M('g_goods')->where($c_where)->order('goods_sn desc')->getField('goods_sn'); //获取该分类下最后一条记录的货号
                    $last = str_replace($goods_sn['goods_sn'], '', $last);  //替换前缀
                    $last++;   //自增加1
                    $strlen = strlen($last);   //计算长度
                    if ($strlen < 3) {
                        $rep = 3 - $strlen;
                        $goods_sn = $goods_sn['goods_sn'] . str_repeat('0', $rep) . $last;
                    }
                    $_POST['goods_sn'] = $goods_sn;
                }
                if ($_POST["is_refund"]) {
                    $_POST["is_refund"] = 1;
                } else {
                    $_POST["is_refund"] = 0;
                }
                $_POST['add_time'] = time();
                //12.2 随机生成浏览量===>销量读取浏览量
                $_POST['goods_browse'] = rand(500, 1000);
                $_POST['is_upgrade'] =1;
                $res = D("Goods")->addGoods($_POST);
            }
            $cookie = $_COOKIE['url'];
            if ($res !== false) {
                if (empty($_POST['goods_id'])) {///新增
                    $this->success("操作成功", U('admin/upgrade/index'));
                } else {

                    $topadmin = session('topadmin');
                    if ($topadmin != 1 && $res > 0) {//不是超级 管理员 修改商品 商品待审核
                        M("g_goods")->where(array('goods_id' => $_POST['goods_id']))->save(array('is_auditing' => 0));
                    }
                    $this->success("操作成功", cookie('now_url'));
                }
            } else {
                $this->error("操作失败");
            }
        } else {
            if ($_GET['id']) {
                $g_where["goods_id"] = array("eq", $_GET["id"]);
                $data = D("Goods")->getGoodsInfo($g_where);
                if ($data) {
                    $pre = C('DB_PREFIX');//表前缀
                    $this->good_image_url = M('g_goods_gallery')->where(array('goods_id' => $data['goods_id']))->order('img_order desc')->select();
                }
                if ($data['promote_start_date'] == false) {
                    $data['promote_start_date'] = time();
                }
                if ($data['promote_end_date'] == false) {
                    $data['promote_end_date'] = strtotime("+1 month");
                }
//				var_dump($data);die;
                $this->assign("data", $data);
            } else {
                $this->share_money = M('sys_param')->where(array('param_code' => 'share_money'))->getField('param_value');
                $data['promote_start_date'] = time();
                $data['promote_end_date'] = strtotime("+1 month");
                $this->assign("data", $data);
            }
            //商品分类
            //$Category=D('Common')->getAllList('g_category',array('is_show'=>1));
            $Category = D('Category')->getCategoryAll(array('is_show' => 1));
            $Category = $this->gettree($Category, 0, 0, 'parent_id', 'cat_id');
            $this->assign("category", $Category);
            //商品品牌
            $brand = D('Brand')->getBrandAll(array('is_show' => 1));
            $this->assign("brand", $brand);
            //商品类型
            $this->goods_type = M('g_goods_type')->where(array('enabled' => 1))->select();
            $title = "商品信息";
            $this->assign("msgtitle", $title);
            //获取配送方式
            $field = 'shipping_id,shipping_name';
            $s_where['enabled'] = 1;
            $shipping = M('g_shipping')->field($field)->where($s_where)->select();
            $this->assign('shipping', $shipping);
            $this->display();
        }
    }

    ///删除商品
    public function Goods_del()
    {
        $where['goods_id'] = $_GET['id'];
        $data['is_delete'] = 1;
        $res = D('Goods')->editGoods($data, $where);
        if ($res !== false) {
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }

    ///获取商品属性
    public function get_goods_attribute()
    {
        $where['cat_id'] = $_GET['cat_id'];
        $goods_id = $_GET['goods_id'];
        $goods_attr = array();
        if ($goods_id) {
            $goods_type = M('g_goods')->where(array('goods_id' => $goods_id))->getField('goods_type');
            if (!empty($goods_type) && $goods_type == $_GET['cat_id']) {
                $pre = C('DB_PREFIX');//表前缀
                $goods_attr = M()->table($pre . 'g_goods_attr goods_attr')//
                ->join($pre . 'g_attribute attr on attr.attr_id=goods_attr.attr_id')//
                ->where(array('goods_attr.goods_id' => $goods_id))//
                ->order('attr.sort_order desc')->select();
            }
        }
        $list = M('g_attribute')->where($where)->order('sort_order desc')->select();
        $arr1 = $arr2 = array();
        $count1 = count($goods_attr);
        $count2 = count($list);
        if ($count1 > $count2) {
            $arr1 = $goods_attr;
            $arr2 = $list;
            $type = 1;
            $min_count = $count2;
        } else {
            $arr1 = $list;
            $arr2 = $goods_attr;
            $type = 0;
            $min_count = $count1;
        }

        $res_arr = array();
        foreach ($arr1 as $keys => $val) {
            //var_dump($keys);
            $infor = 0;
            if ($arr2) {
                foreach ($arr2 as $keyss => $vals) {
                    if (!empty($val['attr_id']) && $val['attr_id'] == $vals['attr_id']) {
                        $arr2[$keyss]['is_shows'] = 1;
                        $infor = 1;
                        if ($type == 1) {
                            //arr1 是商品属性
                            $val['attr_name'] = $vals['attr_name'];
                            $val['attr_input_type'] = $vals['attr_input_type'];
                            $val['attr_type'] = $vals['attr_type'];
                            $val['attr_values'] = $vals['attr_values'];
                            $val['is_package'] = $vals['is_package'];
                            $res_arr[] = $val;
                        } else {
                            //arr1 是 类型属性
                            $val['attr_value'] = $vals['attr_value'];
                            $val['attr_price'] = $vals['attr_price'];
                            $val['goods_attr_id'] = $vals['goods_attr_id'];
                            $res_arr[] = $val;
                        }
                        //unset($arr2[$keyss]);
                    }
                }
            }
            /*if(($keys+1)>$min_count){
                $res_arr[]=$val;
            }*/
            if (empty($type)) {
                //非商品属性
                if (($keys + 1) > $min_count && empty($infor)) {

                    $res_arr[] = $val;
                }
            } else {
                if ($min_count == 0) {
                    $res_arr[] = $val;
                }
            }

        }
        foreach ($arr2 as $keya => $value) {
            if (empty($value['is_shows'])) {
                $res_arr[] = $value;
            }
        }
        $str = "";
        if ($res_arr) {
            foreach ($res_arr as $key => $value) {
                $attr_type = "";//多选
                if ($value['attr_type']) {
                    $add_data_attr = 'add_data_attr';
                    $add_data_attr_type = '+';
                    if ($value['attr_id'] == $old_attr_id) {
                        $add_data_attr = 'remove_data_attr';
                        $add_data_attr_type = '-';
                    }
                    $attr_type = '<div class="input-group">
						<span class="input-group-addon ' . $add_data_attr . '" data="' . $value['attr_id'] . '">' . $add_data_attr_type . '</span></div>';
                }
                $attr_price = "";
                $attr_input_type = '<input type="text" name="attr_value[]" value="' . $value['attr_value'] . '" placeholder="' . $value['attr_name'] . '" class="form-control">';
                //类别；0，为手工输入；1，为选择输入；2，为多行文本输入
                if ($value['attr_input_type'] == 1) {
                    $option = '';
                    if ($value['attr_values']) {
                        $value['attr_values'] = str_replace(array("\r\n", "\r", "\n"), ",", $value['attr_values']);//回车替换都号
                        $attr_values = explode(',', $value['attr_values']);
                        foreach ($attr_values as $keys => $val) {
                            if ($val) {
                                if ($value['attr_value'] == $val) {
                                    $option .= ' <option value="' . $val . '" selected >' . $val . '</option>';
                                } else {
                                    $option .= ' <option value="' . $val . '">' . $val . '</option>';
                                }

                            }
                        }
                    }
                    $attr_input_type = '<select class="form-control" name="attr_value[]">
			                    <option value="">请选择</option>' . $option . '</select>';
                } elseif ($value['attr_input_type'] == 2) {
                    $option = '';
                    $attr_input_type = '<textarea name="attr_value[]"  class=" autosize" data-autosize-on="true" style="overflow: hidden; resize: horizontal; word-wrap: break-word;  width: 100%;  height: 100px;">' . $value['attr_value'] . '</textarea>';
                }
                //如果可以多选，则可以自定义属性，并且可以根据值的不同定不同的价
                if ($value['attr_type']) {
                    $attr_price = '<div class="col-xs-2">
						 <div class="form-group">
							<label class="control-label">
								属性价格
							</label>
							<input type="text" name="attr_price[]" value="' . $value['attr_price'] . '"  placeholder="属性价格" class="form-control">
						</div>
					</div>';
                } else {
                    $attr_price = '<div class="col-xs-2">
						 <div class="form-group">
							<label class="control-label">

							</label>
							<input type="hidden" name="attr_price[]" value="' . $value['attr_price'] . '"  placeholder="属性价格" class="form-control">
						</div>
					</div>';
                }
                //如果可以多选，则可以自定义不同的属性分享佣金
                if ($value['attr_type']) {
                    $attr_share_money = '<div class="col-xs-2">
                         <div class="form-group">
                            <label class="control-label">
                                属性分享佣金
                            </label>
                            <input type="text" name="attr_share_money[]" value="' . $value['attr_share_money'] . '"  placeholder="属性分享佣金" class="form-control">
                        </div>
                    </div>';
                } else {
                    $attr_share_money = '<div class="col-xs-2">
                         <div class="form-group">
                            <label class="control-label">

                            </label>
                            <input type="hidden" name="attr_share_money[]" value="' . $value['attr_share_money'] . '"  placeholder="属性分享佣金" class="form-control">
                        </div>
                    </div>';
                }
                $str .= '<div class="col-md-10 data_attrs attr_id_' . $value['attr_id'] . '">

				<div class="col-xs-1">
					 <div class="form-group">
						<label class="control-label">
						<input type="hidden" name="attr_id[]" value="' . $value['attr_id'] . '">
						<input type="hidden" name="goods_attr_id[]" value="' . $value['goods_attr_id'] . '" >
                        <input type="hidden" name="is_package[]" value="' . $value['is_package'] . '" >
						</label>
						' . $attr_type . '
					</div>
				</div>
				<div class="col-xs-4">
				 <div class="form-group">
						<label class="control-label">
							' . $value['attr_name'] . '
						</label>
						' . $attr_input_type . '

					</div>
				</div>

					' . $attr_price . $attr_share_money . '

				</div>';
                $old_attr_id = $value['attr_id'];
            }
        }
        echo $str;
    }
    //修改上下架
    public function is_on_sale()
    {
        $data['status'] = 0;
        if ($_GET['value'] != null) {
            $goods_id = $_GET["id"];
            $is_on_sale = $_GET["value"];
            $where['goods_id'] = $goods_id;
            $save_date['is_on_sale'] = $is_on_sale;
            $res = M('g_goods')->where($where)->save($save_date);
            if ($res !== false) {
                //$this->redirect("Admin/Goods/index");
                $data['status'] = 1;
                echo json_encode($data);
            } else {
                $data['error'] = '操作失败,请稍候再试';
                echo json_encode($data);
            }
            //$sql="update db_g_goods set is_on_sale =" .$is_on_sale . " where goods_id= " . $goods_id;
            //$re=mysql_query($sql);
            ///$this->redirect("Admin/Goods/index");
        }
    }


}