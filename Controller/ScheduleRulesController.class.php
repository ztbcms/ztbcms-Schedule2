<?php
/**
 * Created by PhpStorm.
 * User: cycle_3
 * Email: 953006367@qq.com
 * Date: 2018/12/29
 * Time: 14:13
 */

namespace Schedule\Controller;

use Common\Controller\AdminBase;
use Schedule\Service\ScheduleRulesService;

class ScheduleRulesController extends AdminBase {

    /*
     *  默认预约规则列表
     */
    public function scheduleList(){
        if(IS_AJAX){
            $page = I('page','1','trim');
            $limit = I('limit','20','trim');
            $order = I('order','id desc','trim');
            $name = I('name','','trim');
            $res = ScheduleRulesService::scheduleList($name,$order,$page,$limit);
            $this->ajaxReturn($res);
        } else {
            $this->display();
        }
    }

    /*
     * 预约规则详情
     */
    public function scheduleDetails(){
        if(IS_AJAX){
           $id = I('id','','trim');
           $res = ScheduleRulesService::scheduleDetails($id);
           $this->ajaxReturn($res);
        } else {
            $this->display();
        }
    }

    /*
     * 添加编辑模板信息
     */
    public function scheduAddEdit(){
       $post = I('post.');
       $id = $post['id'];
       $name = $post['detail']['name'];
       $targetType = $post['detail']['target_type'];
       $target = $post['detail']['target'];
       $res = ScheduleRulesService::scheduleAddEdit($id,$name,$targetType,$target);
       $this->ajaxReturn($res);
    }

    /*
     * 添加新的规则
     */
    public function rulesAddEdit(){
        $post = I('post.','','trim');
        $id = $post['id'];
        $timePeriod = $post['timePeriod'];
        $loop_type = $post['loop_type'];
        $monthDay = $post['month_day'];
        $weekDay = $post['week_day'];
        $sort = $post['sort'] ? $post['sort'] : 0;
        if($loop_type == 'month'){
            $weekDay = '0';
        }
        if($loop_type == 'week'){
            $monthDay = '0';
        }
        if($loop_type == 'daily'){
            $monthDay = '0';
            $weekDay = '0';
        }
//  模拟数据demo
//        $weekDay = [
//            '0' => '2',
//            '1' => '5',
//            '2' => '6'
//        ];
//        $monthDay = [
//            '0' => '5',
//            '1' => '8',
//            '2' => '9'
//        ];
        //添加规则
        $res = ScheduleRulesService::ruleAddEdit($id,$timePeriod,$monthDay,$weekDay,$sort);
        $this->ajaxReturn($res);
    }

    /*
     * 删除模板规则
     */
    public function rulesDel(){
       $ruleId = I('id','','trim');
       $res = ScheduleRulesService::rulesDel($ruleId);
       $this->ajaxReturn($res);
    }

    /*
     * 校验时间是否在预约内
     */
    public function checkTime(){
       $checkTime = I('checkTime','','trim');
       $id = I('id','','trim');
       $res = ScheduleRulesService::checkTime($checkTime,$id);
       $this->ajaxReturn($res);
    }

    /*
     * 编辑规则简介和名称
     */
    public function editContent(){
        $data = I('post.','','trim');
        $id = $data['id'];
        $name = $data['name'];
        $about = $data['about'];
        $res = ScheduleRulesService::editContent($id,$name,$about);
        $this->ajaxReturn($res);
    }

    /*
     * 删除模板预约
     */
    public function delScheduleAcquiescent(){
        $id = I('id','','trim');
        $res = ScheduleRulesService::delSchedule($id);
        $this->ajaxReturn($res);
    }

    /*
     * 查询模板时间段内的所有规则
     */
    public function queryRule(){
        $checkTime = I('checkTime','','trim');
        $id = I('id','','trim');
        $res = ScheduleRulesService::queryRule($checkTime,$id);
        $this->ajaxReturn($res);
    }

    /*
     * 直接添加模板和規則
     */
    public function scheduleRuleAddEdit(){
        $data = [];

//        $data = [
//            '0' => [
//                'timePeriod' => '2019/01/12 - 2019/01/25',
//                'monthDay' => '14',
//                'sort' => '2',
//            ],
//            '1' => [
//                'timePeriod' => '2019/01/17 - 2019/01/25',
//                'weekDay' => '3',
//                'sort' => ''
//            ]
//        ];
        $res = ScheduleRulesService::scheduleRuleAddEdit($data,'20','模板三','goods_id','489');
        $this->ajaxReturn($res);
    }

}