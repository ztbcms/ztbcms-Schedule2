<?php
/**
 * Created by PhpStorm.
 * User: cycle_3
 * Email: 953006367@qq.com
 * Date: 2018/12/29
 * Time: 14:48
 */

namespace Schedule\Service;

use System\Service\BaseService;
use Schedule\Model\ScheduleModel;
use Schedule\Model\ScheduleRuleModel;

class ScheduleRulesService extends BaseService
{

    const SCHEDULE = ScheduleModel::TABLE;            //默认兼职介绍
    const SCHEDULE_RULE = ScheduleRuleModel::TABLE;  //默认兼职具体规则


    //规则列表
    static function scheduleList($name = null, $order = 'id desc', $page = 1, $limit = 20)
    {
        $where = [];
        if($name)$where['name'] = ['like',"%{$name}%"];
        $res = self::select(self::SCHEDULE, $where, $order, $page, $limit);
        foreach ($res['data']['items'] as &$v){
            $v['add_time_name'] = date("Y-m-d H:i",$v['add_time']);
        }
        return $res;
    }

    //规则详情
    static function scheduleDetails($id)
    {
        $where['id'] = $id;
        $res_find = self::find(self::SCHEDULE, $where);
        $res_find['data']['rule'] = D(self::SCHEDULE_RULE)->where(['schedule_id' => $where['id']])->select();
        foreach ($res_find['data']['rule'] as &$v){
            $ScheduleRuleModel = new ScheduleRuleModel();
            $rule_data = $ScheduleRuleModel->translateView($v);
            $v['time_name'] = $rule_data['time_name'];
            $v['loop_type_name'] = $rule_data['loop_type_name'];
        }
        return $res_find;
    }

    //添加或者编辑模板和规则
    static function scheduleAddEdit($id,$name,$target_type,$target){
        $table = D(self::SCHEDULE);
        if(!$target_type) return self::createReturn(false, '', '此处不太建议target_type为空');
        if(!$target) return self::createReturn(false, '', '此处不太建议target为空');
        if($id){
            $save['name'] = $name;
            $save['target_type'] = $target_type;
            $save['target'] = $target;
            $res = $table->where(['id'=>$id])->save($save);
            $res_data['schedule_id'] = $id;
        } else {
            $data['name'] = $name;
            $data['target_type'] = $target_type;
            $data['target'] = $target;
            $data['add_time'] = time();
            $res = $table->add($data);
            $res_data['schedule_id'] = $res;
        }
        if($res){
            return self::createReturn(true, $res_data, '操作成功');
        } else {
            return self::createReturn(false, '', '保存失败');
        }
    }

    //为模板添加规则
    static function ruleAddEdit($id,$timePeriod,$month_day,$week_day){
        $RuleModel = new ScheduleRuleModel;
        //校验并对数据进行处理
        $check_res = $RuleModel->checkRule($id,$timePeriod,$month_day,$week_day);
        if($check_res['status'] == false) return self::createReturn(false, '', $check_res['msg']);

        $data = $check_res['data']['items'];
        $schedule_rule_table = D(self::SCHEDULE_RULE);

        M()->startTrans();
        $Rollback = true;
        $rule_id = [];
        foreach ($data as $v){
            $schedule_rule_add['schedule_id'] = $v['schedule_id'];
            $schedule_rule_add['start_time'] = $v['start_time'];
            $schedule_rule_add['end_time'] = $v['end_time'];
            $schedule_rule_add['year'] = $v['year'];
            $schedule_rule_add['month'] = $v['month'];
            $schedule_rule_add['day'] = $v['day'];
            $schedule_rule_add['week'] = $v['week'];
            $schedule_rule_add['loop_type'] = $v['loop_type'];
            $schedule_rule_add['add_time'] = time();
            $rule_res = $schedule_rule_table->add($schedule_rule_add);
            if(!$rule_res) $Rollback = false;  else $rule_id[] = $rule_res;
        }
        if($Rollback == false){
            M()->rollback();
            return BaseService::createReturn(false, '', '添加失败');
        } else {
            M()->commit();
            $data['rule_id'] = $rule_id;
            return self::createReturn(true, $data, '添加规则成功');
        }
    }

    //删除规则
    static function rulesDel($rule_id){
        if(!$rule_id) return self::createReturn(false, '', 'id不能为空');
        $res = D(self::SCHEDULE_RULE)->where(['id'=>$rule_id])->delete();
        if($res){
            return self::createReturn(true, $res, '删除成功');
        } else {
            return self::createReturn(false, '', '删除失败');
        }
    }

    //校验时间是否被预约
    static function checkTime($checkTime,$id){
        $time = strtotime($checkTime);
        if(!$time) return self::createReturn(false, '', '时间不能为空');
        if(!$id) return self::createReturn(false, '', 'id不能为空');
        $ScheduleRuleModel = new ScheduleRuleModel();
        $res = $ScheduleRuleModel->checkTime($time,$id);
        if($res['code'] != '200'){
            return self::createReturn(false, '', $res['msg']);
        } else {
            return self::createReturn(true, $res['data'], $res['msg']);
        }
    }

    //编辑内容
    static function editContent($id,$name = null,$about = null){
        if(!$id) return self::createReturn(false, '', 'id不能为空');
        if($name) $save['name'] = $name;
        if($about) $save['about'] = $about;
        if(!$save) return self::createReturn(false, '', '内容不能为空');
        $res = D(self::SCHEDULE)->where(['id'=>$id])->save($save);
        if($res){
            return self::createReturn(true, '', '编辑成功');
        } else {
            return self::createReturn(false, '', '内容不能为空');
        }
    }

    //刪除模板
    static function delSchedule($id){
        if(!$id) return self::createReturn(false, '', '模板id不能为空');
        $res = D(self::SCHEDULE)->where(['id'=>$id])->delete();
        D(self::SCHEDULE_RULE)->where(['schedule_id'=>$id])->delete();
        if($res){
            return self::createReturn(true, '', '删除成功');
        } else {
            return self::createReturn(false, '', '删除失败');
        }
    }

    //查询某时间段内的所有预约规则
    static function queryRule($checkTime,$id){
        $time = explode('-',$checkTime);
        $start_time = strtotime($time[0]);
        $end_time = strtotime($time[1]);
        if(!$id) return self::createReturn(false, '', 'id不能为空');
        $rule_where['start_time|end_time'] = ['BETWEEN', [$start_time, $end_time]];;
        $rule_where['schedule_id'] = $id;
        $res = D(self::SCHEDULE_RULE)->where($rule_where)->select();
        if($res){
            return self::createReturn(true, $res, '存在预约');
        } else {
            return self::createReturn(false, $res, '不存在预约');
        }
    }
}