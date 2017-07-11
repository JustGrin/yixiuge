<?php
/**
 *
 * 推文活动  hkj
 *
 */
class ActivityModel extends Model {

    ///推文活动 奖品领取记录 
    public function getActivityReceiveOne($condition = array()) {
         $one=M('activity_receive')->where($condition)->find();
        return $one;
    }

    ///推文活动 奖品领取记录 列表 
    public function getActivityReceiveList($condition = array(),$p=1,$order='activity_id desc',$pagesize=10) {
//        $pagesize = $pagesize;
        $p = !empty($p) ? $p : 1;
        $start = ($p - 1) * $pagesize;
        $list = M('activity_receive')->where($condition)//
        ->field($field)->limit($start, $pagesize)->order($order)->select();
        return $list;
    }
    ///推文活动 奖品领取记录 新增
    public function activityReceiveAdd($data = array()) {
        if(empty($data['add_time'])){
          $data['add_time']=time();
        }
        $result = M('activity_receive')->add($data);
        return $result;
    }
    //获取分享记录
    public function getArticleShareLog($condition=array()){
        $one=M('article_share_log')->where($condition)->find();
        return $one;
    }
     //记录分享记录 如果已经有了 则加一
    public function setArticleShareLog($data=array()){
        $one=M('article_share_log')->where($data)->find();
        if($one){
            $result=M('article_share_log')->where(array('id'=>$one['id']))->setInc('count');
            return $result;
        }else{
           if(empty($data['add_time'])){
              $data['add_time']=time();
            }
            $result=M('article_share_log')->add($data);
            return $result;
        }
       
    }
    
}
