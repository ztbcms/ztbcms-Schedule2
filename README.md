# ztbcms-Schedule  模块


###### 返回码

|返回码   | 说明  |
| ------------ | ------------ |
| 200  | 正常  |
| 400  | 添加失败  |


## 1.添加和编辑模板和规则
 
 #### 接口 ScheduleRulesService/scheduleRuleAddEdit 

 | 参数  |  类型 | 说明 |
 | ------------ | ------------ | ------------ |
 |  data | array |数组 |
 |  scheduleId | int | 模板id不为空的时候为编辑 ，为空的时候添加  |
 |  name | string |  模板名称 编辑的时候可以为空  |
 |  targetType| string | 关联类型 编辑的时候可以为空 |
 |  target | int |类型id  编辑的时候可以为空 |

#### 注释：

**1.当 data 的数据说明可查看ruleAddEdit接口 如：**
```        
$data = [
    '0' => [
        'timePeriod' => '2019/01/17 - 2019/01/19',
        'monthDay' => '20',
        'sort' => '2',   
    ],
    '1' => [
        'timePeriod' => '2019/01/17 - 2019/01/25',
        'weekDay' => '3',
        'sort' => ''  //为空的时候为0      
    ]
];
具体可看demo
```

 ##### 成功返回
 ```
 {
     "status": true,
     "code": 200,
     "data": {
         "schedule_id": "23",
         "rule_id": ["52"]
     },
     "msg": "操作成功",
     "url": "",
     "state": "success"
 }
 ```
##### 失败返回
```
{
    "status":false,
    "code":400,
    "msg":"操作失败",
    "data":[]
}
```


## 2.添加或者编辑模板信息
#### 接口 ScheduleRulesService/scheduleAddEdit

| 参数  | 类型 | 说明 |
| ------------ | ------------ |------------ |
|  scheduleId | int |添加的时候为空，编辑时传编辑的模板id  |
|  name | string|模板名称  |
|  targetType| string | 关联类型 |
|  target | int |类型id  |

#### return 

```
{
    "status":false,
    "code":400,
    "msg":"预约不存在",
    "data":[
        "id":1
    ]
}

```

## 3.删除模板
#### 接口 ScheduleRulesService/delSchedule

| 参数  |  注释 |
| ------------ | ------------ |
|  scheduleId | 模板id  |


## 4.为模板添加规则
#### 接口 ScheduleRulesService/ruleAddEdit

| 参数 | 类型 | 说明 |
| ------------ | ------------ | ------------ |
|  scheduleId | int |模板id  |
|  timePeriod | string | 时间限制的日期 2019/01/18 - 2019/01/18 |
|  loopType | string |时间的类型  month 每个月 week每周 daily每天  |
|  monthDay | array |每个月的多少号 例如1，2，3号 [1,2,3] |
|  weekDay | array | 每个星期几，如星期一，二，[1,2] |
|  sort | int | 权重，当有两个规则相同去权重大的 |

#### 注释：

**1.当 monthDay 字段和 weekDay 字段都为空的时候 为每天的类型**

**2.当 monthDay 为数组的时候会生成多条数据 如：**
```
$monthDay = [2,5,6];
        生成每月 2号 5号 6号的三条预约,weekDay 字段同理,具体可看demo
```
**3.当 monthDay 和 weekDay 同时存在 的时候 既生成星期的 也 生成每月的**  

#### return 

```
{
    "status":false,
    "code":400,
    "msg":"新增成功",
    "data":[
        "id":1
    ]
}

```

## 5.校验时间是否存在预约
#### 接口 ScheduleRulesService/checkTime

| 参数  | 类型 | 注释 |
| ------------ | ------------ | ------------ |
|  checkTime | string-date  |时间 2019/01/11 |
|  scheduleId | int |模板id  |


#####  成功返回

```json
{
    "status":true,
    "code":200,
    "msg":"预约存在; schedule_id为：9,schedule_rule_id为：19",//返回信息
    "data":{
        "schedule_id":9,
        "schedule_rule_id":19,
        "date":"2019/01/11"
    }
}
```
##### 失败返回
```
{
    "status":false,
    "code":400,
    "msg":"预约不存在",
    "data":false
}
```


 ## 6.校验时间是否存在预约
 
 #### 接口 ScheduleRulesService/queryRule 

 | 参数  |  类型 | 说明 |
 | ------------ | ------------ | ------------ |
 |  checkTime | string-dates |时间 2019/01/25 - 2019/01/27 |
 |  scheduleId | int |模板id  |

 ##### 成功返回
 ```
 {
    "status":true,
    "code":200,
    "data":[{
        "schedule_id":"9",
        "schedule_rule_id":"33",
        "date":"2019/01/25"
        },{
        "schedule_id":"9",
        "schedule_rule_id":"33",
        "date":"2019/01/25"
        }],
        "msg":"存在预约",
        "url":"",
        "state":"success"
}
 ```
##### 失败返回
```
{
    "status":false,
    "code":400,
    "msg":"预约不存在",
    "data":[]
}
```

## 7.查看模板详情
#### 接口 ScheduleRulesService/scheduleDetails

| 参数  |  注释 |
| ------------ | ------------ |
|  scheduleId | 模板id  |