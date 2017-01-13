<?php
/**
 * 商品
 *
 * 
 *
 */
class GoodsModel extends Model {


    public function getGoodsInfo($condition = array()) {
        return M('g_goods')->where($condition)->find();
    }

     /**
     * 商品分类列表
     * @param unknown $condition
     * @param string $pagesize
     * @param string $field
     * @param string $order
     * @param string $limit
     * @return Ambigous <multitype:boolean Ambigous <string, mixed> , unknown>
     */
    public function getGoodsList($condition, $pagesize = '', $field = '*', $order = 'sort_order asc', $limit = ''){
        $list = M('g_goods')->field($field)->where($condition)->order($order)->limit($limit)->select();
        if (empty($list)) return array();
        return $list;
    }

    /**
     * 所有商品
     * @param unknown $condition
     * @param string $pagesize
     * @param string $field
     * @param string $order
     * @param string $limit
     * @return Ambigous <multitype:boolean Ambigous <string, mixed> , unknown>
     */
    public function getGoodsAll($condition,$field = '*', $order = 'sort_order asc'){
        $list = M('g_goods')->field($field)->where($condition)->order($order)->select();
        if (empty($list)) return array();
        return $list;
    }
       /**
     * 添加商品
     *
     * @param unknown_type $data
     * @param unknown_type $condition
     */
    public function addGoods($data) {
        $res=M('g_goods')->add($data);
         $is_package=0;
        if($res!==false){
            //添加商品属性
            if($data['goods_type']){
                //$data['attr_id']
                //$data['attr_value']
                //$data['attr_price']
              if($data['attr_id']){
                  $add_data=array();
                  $add_data['goods_id']=$res;
                  foreach ($data['attr_id'] as $key => $value) {
                      if ($value) {
                           $add_data['attr_id']=$value;
                           $add_data['attr_value']=$data['attr_value'][$key];
                           $add_data['attr_price']=$data['attr_price'][$key];
                           $add_data['attr_vip_price']=$data['attr_vip_price'][$key];
                           $add_data['attr_share_money']=$data['attr_share_money'][$key];
                           $add_data['is_package']=$data['is_package'][$key];
                           if($data['is_package'][$key]  && $data['attr_value'][$key]){
                            $is_package=1;
                           }
                           $file=$this->addGoodsAttr($add_data);
                      }
                  }
              }
                
            }
            if($is_package){
              ///属性套餐修改
               M('g_goods')->where(array('goods_id'=>$res))->save(array('is_package'=>$is_package));
            }
             
            //添加商品图片
            if($data['good_image_url']){
                $add_data=array();
                $add_data['goods_id']=$res;
              foreach ($data['good_image_url'] as $key => $value) {
                  if ($value) {
                       $add_data['img_url']=$value;
                      $add_data['img_order']=$data['img_order'][$key];
                      $file=$this->addGoodsGallery($add_data);
                  }
              }
            }
        }

        return $res;
    }   
    /**
	 * 更改商品
	 *
	 * @param unknown_type $data
	 * @param unknown_type $condition
	 */
	public function editGoods($data,$condition) {
        $res=M('g_goods')->where($condition)->save($data);
        $is_package=0;//是否包含套餐
        if($res!==false){
            $goods_id=$condition['goods_id'];
            //修改商品属性
            if($data['goods_type']){
                //$data['goods_attr_id']
                //$data['attr_id']
                //$data['attr_value']
                //$data['attr_price']
              $goods_attr_id_count=count($data['goods_attr_id']);
              if($goods_attr_id_count>0){
                //判断 商品属性是否存在
                  $where_attr=array();
                  $where_attr['goods_id']=$goods_id;
                  $nodel_attr_str="";//不删除的
                  $add_attr_data=array();
                  foreach ($data['goods_attr_id'] as $key => $value) {
                      if ($value) {
                          $nodel_attr_str.=','.$value;
                           //存在的修改
                           $where_attr['goods_attr_id']=$data['goods_attr_id'][$key];//属性id
                           $save_data=array();
                           //$save_data['attr_id']=$data['attr_id'][$key];
                           $save_data['attr_value']=$data['attr_value'][$key];
                           $save_data['attr_price']=$data['attr_price'][$key];
                           $save_data['attr_vip_price']=$data['attr_vip_price'][$key];
                           $save_data['attr_share_money']=$data['attr_share_money'][$key];
                           $save_data['is_package']=$data['is_package'][$key];///套餐属性
                           if($data['is_package'][$key] && $data['attr_value'][$key]){
                            $is_package=1;
                           }
                           $file=$this->editGoodsAttr($save_data,$where_attr);
                           if($file>0){
                            $res=1;
                           }
                          
                      }else{
                         //不存在的 新增
                        $add_data=array();
                        $add_data['attr_id']=$data['attr_id'][$key];
                        $add_data['goods_id']=$goods_id;
                        $add_data['attr_value']=$data['attr_value'][$key];
                        $add_data['attr_price']=$data['attr_price'][$key];
                        $add_data['attr_vip_price']=$data['attr_vip_price'][$key];
                        $add_data['attr_share_money']=$data['attr_share_money'][$key];
                        $add_data['is_package']=$data['is_package'][$key];///套餐属性
                        $add_attr_data[]=$add_data;
                      }
                  }
                  //删除不存在的 
                  if($nodel_attr_str){
                      $nodel_attr_str=substr($nodel_attr_str, 1);
                      $del_where=array();
                      $del_where['goods_attr_id']=array('not in',$nodel_attr_str);
                      $del_where['goods_id']=$goods_id;
                      $del=$this->delGoodsAttr($del_where);
                  }
                  //不存在的 新增
                  if(!empty($add_attr_data)){
                     foreach ($add_attr_data as $key => $value) {
                          if($value['is_package'] && $value['attr_value']){
                            $is_package=1;
                           }
                         $file=$this->addGoodsAttr($value);
                         if($file>0){
                            $res=1;
                          }
                     }
                  }
              }
                
            }else{
                //未选择 则删除商品属性
                $this->delGoodsAttr(array('goods_id'=>$goods_id));
            }
            ///属性套餐修改
            M('g_goods')->where($condition)->save(array('is_package'=>$is_package));
            //添加商品图片
            $data_img=array();
            $data_img['goods_id']=$goods_id;
            $where_img['goods_id']=$goods_id;
            $nodel_img_str="";
            //判断 图片是否存在
            if($data['good_image_url']){
              foreach ($data['good_image_url'] as $key => $value) {
                  if ($value) {
                       $where_img['img_url']=$value;
                       $file=$this->getGoodsGalleryInfo($where_img);
                       if($file){
                        $save_data=array();
                        $save_data['img_order']=$data['img_order'][$key];
                        //修改排序
                        $this->editGoodsGallery($save_data,array('img_id'=>$file['img_id']));
                        $nodel_img_str=','.$file['img_id'];
                        //存在不做修改
                        unset($data['good_image_url'][$key]);
                       }
                  }
              }
            }
            //判断是否删除9.9大聚惠的商品，如果是则删除活动数据里的参数
            $activity99_id = M('g_goods')->where($condition)->getField("activity_id");
            if ($activity99_id == 2 && $data['is_delete'] == 1) {
                $activity99 = M('activity')->where('id=2')->find();
                $param_arr = json_decode($activity99['param']);
                foreach ($param_arr->activity_goods_msg as $k => $v) {
                    if ($k == $goods_id) {
                        unset($param_arr->activity_goods_msg->$k);
                    }
                }
                $activity_save_data['param'] = json_encode($param_arr);
                M("activity")->where("id=2")->save($activity_save_data);
            }
            //删除不存在的 并新增
            if($data['good_image_url']){
                $del_where=array();
                if($nodel_img_str){
                    $nodel_img_str=substr($nodel_img_str, 1);
                    $del_where['img_id']=array('not in',$nodel_img_str);
                }
                $del_where['goods_id']=$goods_id;
                $del=$this->delGoodsGallery($del_where);
                $add_data=array();
                $add_data['goods_id']=$goods_id;
              foreach ($data['good_image_url'] as $key => $value) {
                  if ($value) {
                       $add_data['img_url']=$value;
                       $add_data['img_order']=$data['img_order'][$key];
                       $file=$this->addGoodsGallery($add_data);
                  }
              }
            }
        }
		return $res;
	}
     /**
     * 查询商品属性
     *
     * @param unknown_type $data
     * @param unknown_type $condition
     */
    public function getGoodsAttrInfo($condition) {
        return M('g_goods_attr')->where($condition)->find();
    }
   /**
     * 新增商品属性
     *
     * @param unknown_type $data
     * @param unknown_type $condition
     */
    public function addGoodsAttr($data) {
        return M('g_goods_attr')->add($data);
    }
     /**
     * 更改商品属性
     *
     * @param unknown_type $data
     * @param unknown_type $condition
     */
    public function editGoodsAttr($data,$condition) {
        return M('g_goods_attr')->where($condition)->save($data);
    }
    /**
     * 获取商品属性
     *
     * @param unknown_type $data
     * @param unknown_type $condition
     */
    public function getGoodsAttrList($condition) {
        return M('g_goods_attr')->where($condition)->select();
    }
    /**
     * 删除商品属性
     *
     * @param unknown_type $data
     * @param unknown_type $condition
     */
    public function delGoodsAttr($condition) {
        return M('g_goods_attr')->where($condition)->delete();
    }
    /**
     * 新增商品图片
     *
     * @param unknown_type $data
     * @param unknown_type $condition
     */
    public function addGoodsGallery($data) {
        return M('g_goods_gallery')->add($data);
    }
     /**
     * 更改商品图片
     *
     * @param unknown_type $data
     * @param unknown_type $condition
     */
    public function editGoodsGallery($data,$condition) {
        return M('g_goods_gallery')->where($condition)->save($data);
    }
     /**
     * 获取商品图片
     *
     * @param unknown_type $data
     * @param unknown_type $condition
     */
    public function getGoodsGalleryInfo($condition) {
        return M('g_goods_gallery')->where($condition)->find();
    }
    /**
     * 获取商品图片
     *
     * @param unknown_type $data
     * @param unknown_type $condition
     */
    public function getGoodsGalleryList($condition) {
        return M('g_goods_gallery')->where($condition)->select();
    }
    /**
     * 删除商品图片
     *
     * @param unknown_type $data
     * @param unknown_type $condition
     */
    public function delGoodsGallery($condition) {
        return M('g_goods_gallery')->where($condition)->delete();
    }
   /**
       * 获取商品价格商品属性
       *
       * @param unknown_type $data
       * @param unknown_type $condition
       */
      public function getGoodsPrice($goods=array(),$activity_type=0) {
         if(empty($goods)){
          return $goods;
         }
          $goods['activity_id'] = $activity_type;
         ///已上架 已审核  是特价促销
          $activity_info = $this->getActivityinfo($goods['activity_id']);
         //$goods['hot_type']=0;//活动类型0 无 1 限时抢购
         if($goods['is_on_sale'] && $goods['is_auditing'] && $goods['activity_id'] && $activity_type ){

           $goods['activity_name']=$activity_info['activity_name'];
            $time=time();
            $activity_status=-1;//-1 已结束  0未开始 1进行中
            $start_time=$end_time=0;//start_time距离开始时间 end_time距离结束时间 
            $activity_msg="";
            $activity_tip="";
            if($goods['activity_start_date']>$time){
              $activity_status=0;
              //开始时间小于 当前时间 未开始
              $start_time=$goods['activity_start_date']-$time;
              $end_time=$goods['activity_end_date']-$goods['activity_start_date'];//结束时间减去开始时间
              $activity_msg="未开始";
              $activity_tip="距开始";//结束时间减去开始时间
                 $goods['is_buy_msg']="活动未开始";
            }else{
              if($goods['activity_end_date']>=$time){
                //结束时间小于 当前时间  进行中
                $activity_status=1;
                $start_time=0;
                $end_time=$goods['activity_end_date']-$time;//结束时间减去当前时间
                $activity_msg="进行中";
                $activity_tip="距结束";
              }else{
                $activity_status=-1;
                if( $goods['is_end_activity_sale']){//结束后继续销售
                  $goods['activity_status']=$activity_status;
                  return $goods;
                }
                $activity_msg="已结束";
                $activity_tip="";
                $goods['is_buy_msg']="活动已结束";
              }
            }
            $goods['market_price']=$goods['shop_price'];////价格
            $goods['shop_price']=$activity_info['activity_price'];////活动价格
            $goods['activity_status']=$activity_status;
            $goods['start_time']=$start_time;
            $goods['end_time']=$end_time;
            $goods['activity_msg']=$activity_msg;
            $goods['activity_tip']=$activity_tip;
            return $goods;
         }else{
          return $goods;
         }
      }

    //获取活动信息
    public function getActivityinfo($Activit_id) {
        $activity_info = M('activity')->where('id='.$Activit_id)->find();
        $para_arr= json_decode($activity_info['param'],true);
        if($Activit_id == 1){
            $activity_info['activity_price'] = $para_arr['activity_price'];
            $activity_info['sum_num'] = $para_arr['sum_num'];
        }elseif ($Activit_id == 2) {
            $activity_info['activity_price'] = $para_arr['activity_price'];
        }
        return $activity_info;
    }


}
