# ztbcms-Schedule  模块


###### 返回码

|返回码   | 说明  |
| ------------ | ------------ |
| 200  | 正常  |
| 400  | 添加失败  |

> step1
## 添加或者编辑模板信息
#### 接口 ScheduleRulesService/scheduleAddEdit
| 参数  |  注释 |
| ------------ | ------------ |
|  id | 添加的时候为空，编辑时传编辑的模板id  |
|  name | 模板名称  |
|  target_type | 关联類型 |
|  target | 类型id  |


> step2
## 为模板添加规则
#### 接口 ScheduleRulesService/ruleAddEdit
| 参数  |  注释 |
| ------------ | ------------ |
|  id | 模板id  |
|  timePeriod | 时间限制的日期 2019/01/18 - 2019/01/18 |
|  loop_type | 时间的类型  month 每个月 week每周 daily每天  |
|  month_day | 每个月的多少号  （当类型为每个月的多少号的时候 只传month_day字段 week_day字段为空） |
|  week_day | 星期几 （当类型为每个周的周几的时候 只传该字段 month_day为空 ）  |

**注释： **
** 1.当 month_day 字段和 week_day 字段都为空的时候 为每天的类型 **
** 2.当 month_day 为数组的时候会生成多条数据 如： **
**$month_day = [
            '0' => '2',
            '1' => '5',
            '2' => '6'
        ];
		生成每月 2号 5号 6号的三条预约,week_day 字段同理
		具体可看demo
**
** 3.当 month_day 和 week_day 同时存在 的时候 既生成星期的 也 生成每月的  **


> step3
## 查看模板列表
#### 接口 ScheduleRulesService/scheduleList

| 参数  |  注释 |
| ------------ | ------------ |
|  name | 模糊查询列表名称  |
|  order | 排序 默认id倒叙  |
|  page | 默认1  |
|  limit | 默认20  |


> step4
## 查看模板详情
#### 接口 ScheduleRulesService/scheduleDetails

| 参数  |  注释 |
| ------------ | ------------ |
|  id | 模板id  |

> step5
## 删除模板的规则
#### 接口 ScheduleRulesService/scheduleDetails

| 参数  |  注释 |
| ------------ | ------------ |
|  rule_id | 规则id  |

> step6
## 删除模板
#### 接口 ScheduleRulesService/delSchedule

| 参数  |  注释 |
| ------------ | ------------ |
|  id | 模板id  |


> step7
## 校验时间是否存在预约
#### 接口 ScheduleRulesService/checkTime

| 参数  |  注释 |
| ------------ | ------------ |
|  checkTime | 时间 2019/01/11 |
|  id | 模板id  |


#####  step7成功返回

```json
{
	"status":true,
	"code":200,
	"msg":"预约存在; schedule_id为：9,schedule_rule_id为：19",//返回信息
	"data":{
	    "schedule_id":'9',
	    "schedule_rule_id":'19'
	}
}
```
##### step7失败返回
```
{
	"status":false,
	"code":400,
	"msg":"预约不存在",
	"data":false
}
```

 > step8
 ## 校验时间是否存在预约
 #### 接口 ScheduleRulesService/checkTime 

 | 参数  |  注释 |
 | ------------ | ------------ |
 |  checkTime | 时间 2019/01/25 - 2019/01/27 |
 |  id | 模板id  |

 ##### step8成功返回
 ```
 {
    "status":true,
    "code":200,
    "data":[{
        "id":"33",
        "schedule_id":"9",
        "start_time":"1548432000",
        \"end_time":"1548432000",
        "year":"0",
        "month":"0",
        "day":"0",
        "week":"0",
        "loop_type":"daily",
        "add_time":"1546510515"
        }],
		"msg":"存在预约",
        "url":"",
        "state":"success"
}
 ```
##### step8失败返回
```
{
	"status":false,
	"code":400,
	"msg":"预约不存在",
	"data":[]
}
```