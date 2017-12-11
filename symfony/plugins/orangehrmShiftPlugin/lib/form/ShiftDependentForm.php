<?php

class ShiftDependentForm extends BaseForm {
    public $shiftTypes;
    public $shiftDates;
    private $employeeService;
    private $shiftService;
    private $shiftTypeService;
    private $shiftDateService;

    private function getShiftService() {

        if(is_null($this->ShiftService)) {
            $this->ShiftService = new ShiftService();
        }
        return $this->ShiftService;
    }

    //获取排班类型实例
    public function getShiftTypeService() {
        if (is_null($this->shiftTypeService)) {
            $this->shiftTypeService = new ShiftTypeService();
            $this->shiftTypeService->setShiftTypeDao(new ShiftTypeDao());
        }
        return $this->shiftTypeService;
    }

    //获取排班类型实例
    public function getShiftDateService() {
        if (is_null($this->shiftDateService)) {
            $this->shiftDateService = new ShiftDateService();
            $this->shiftDateService->setShiftDateDao(new ShiftDateDao());
        }
        return $this->shiftDateService;
    }


    public function configure() {
   
            $scheduleID = $this->getOption('scheduleID');

            $this->shiftTypes = $this->getShiftTypeService()->getShiftTypes($scheduleID);
            $this->shiftDates = $this->getShiftDateService()->getShiftDates($scheduleID);
            
            // $this->schedule = $this->getShiftService()->getSchedule();
            // $data = array_column($this->schedule, NULL, 'schedule_id');

            $widgets = array('scheduleID' => new sfWidgetFormInputHidden(array(), array('value' => $scheduleID)));
            $validators = array('scheduleID' => new sfValidatorString(array('required' => false)));
            $dependentWidgets = $this->getDependentWidgets($scheduleID);
            $dependentValidators = $this->getDependentValidators($scheduleID);

        
            $widgets = array_merge($widgets, $dependentWidgets);
            $validators = array_merge($validators, $dependentValidators);
     
            $this->setWidgets($widgets);

            $this->setValidators($validators);


            $this->widgetSchema->setNameFormat('dependent[%s]');
            
    }

    private function _getShiftTypeList() {
        $list = array("" => "-- " . __('Select') . " --");

        //获取排班类型列表
        foreach($this->shiftTypes as $shiftType) {
            $list[$shiftType->getId()] = $shiftType->getName();
        }
        return $list;
    }

    private function _getShiftDateList() {

        $list = array("" => "-- " . __('Select') . " --");
        foreach($this->shiftDates as $shiftDate) {
            
            $list[$shiftDate->getId()] = $shiftDate->getShiftDate();
        }
        return $list;
    }

    
    //获取计划中的日期
    public function getShifDays($scheduleID){

        $shiftDates=$this->getShiftService()->saveFile('shiftDate');
       // echo'<pre>'; var_dump($shiftDates);exit;
        $shiftDates = array_column($shiftDates, NULL, 'id');

        foreach ($shiftDates as $key => $shiftDate) {
            if($shiftDate['schedule_id']==$scheduleID){
                $dateList[$key]=$shiftDate['date'];
            }
        }

        return $dateList;
    }
    
    /*
     * Tis fuction will return the widgets of the form
     */
    public function getDependentWidgets($scheduleID){
         
        $widgets = array();

        $status = array('1' => __('Enabled'), '0' => __('Disabled'));
  
        $leaveStatusChoices =array('1'=> '周一', '2'=> '周二', '3'=>'周三','4'=>'周四', '5'=> '周五', '6'=>'周六','7'=>'周日');
        $setStatusChoices =array('1' => "-- ". 'Select' . " --", '2'=> '一天', '3'=>'该轮班中所有天','4'=> '自定义');
       
        $i18nHelper = sfContext::getInstance()->getI18N();
        
        $widgets['shiftId'] = new sfWidgetFormInputHidden();
        $widgets['name'] = new sfWidgetFormInputText();
     
         $widgets['shiftType'] = new sfWidgetFormSelect(array('choices' => $this->_getShiftTypeList()));
         $widgets['shiftDays'] = new sfWidgetFormSelect(array('choices' => $this->_getShiftDateList()));
        $widgets['start_time'] = new ohrmWidgetTimeDropDown();

        $widgets['end_time'] = new ohrmWidgetTimeDropDown();
        $widgets['relationshipType'] = new sfWidgetFormSelect(array('choices' => $setStatusChoices));
        $widgets['relationship'] = new ohrmWidgetCheckboxGroup(
                array('choices' => $leaveStatusChoices,
                      'show_all_option' => true));

        $widgets['required_employee'] = new sfWidgetFormInputText();

        $widgets['status'] = new sfWidgetFormSelect(array('choices' => $status), array("class" => "formInputText"));
       
        unset($setStatusChoices['']);
        
        return $widgets;
    }
    
    /*
     * Tis fuction will return the form validators
     */
    public function getDependentValidators(){
        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();
        $i18nHelper = sfContext::getInstance()->getI18N();
        $relationshipChoices =array('1' => "-- ", '2'=> '一天', '3'=>'该轮班中所有天','4'=> '自定义');
        $leaveStatusChoices =array('1'=> '周一', '2'=> '周二', '3'=>'周三','4'=>'周四', '5'=> '周五', '6'=>'周六','7'=>'周日');


        $validators = array(
            'shiftId' => new sfValidatorNumber(array('required' => false, 'min'=> 0)),
            'name' => new sfValidatorString(array('required' => true, 'trim'=>true, 'max_length'=>100)),
            'required_employee' => new sfValidatorString(array('required' => true, 'trim'=>true, 'max_length'=>100)),
            'start_time' => new sfValidatorString(array('required' => true, 'trim'=>true, 'max_length'=>100)),
            'end_time' => new sfValidatorString(array('required' => true, 'trim'=>true, 'max_length'=>100)),
           
            'shiftType' => new sfValidatorString(array('required' => true, 'max_length' => 13)),
            'shiftDays' => new sfValidatorString(array('required' => true, 'max_length' => 13)),
            'relationshipType' => new sfValidatorChoice(array('choices' => array_keys($relationshipChoices))),
            'relationship' => new sfValidatorChoice(
                array('choices' => array_keys($leaveStatusChoices), 
                      'required' => false, 'multiple' => true)),
            'status' => new sfValidatorString(array('required' => false)),
        );
        
        return $validators;
    }



    //获取星期方法
    function  get_week($date){

        //强制转换日期格式
        $date_str=date('Y-m-d',strtotime($date));
   
        //封装成数组
        $arr=explode("-", $date_str);
        
        //参数赋值
        //年
        $year=$arr[0];
        
        //月，输出2位整型，不够2位右对齐
        $month=sprintf('%02d',$arr[1]);
        
        //日，输出2位整型，不够2位右对齐
        $day=sprintf('%02d',$arr[2]);
        
        //时分秒默认赋值为0；
        $hour = $minute = $second = 0;   
        
        //转换成时间戳
        $strap = mktime($hour,$minute,$second,$month,$day,$year);
        
        //获取数字型星期几
        $number_wk=date("w",$strap);
        
        //自定义星期数组
        $weekArr=array('1'=> '周一', '2'=> '周二', '3'=>'周三','4'=>'周四', '5'=> '周五', '6'=>'周六','0'=>'周日');
         // echo'<pre>';var_dump($number_wk);exit;
        //获取数字对应的星期
        return $number_wk;
        // return $weekArr[$number_wk];
    }

    protected function getDuration($fromTime, $toTime) {
        list($startHour, $startMin) = explode(':', $fromTime);
        list($endHour, $endMin) = explode(':', $toTime);

        $durationMinutes = (intVal($endHour) - intVal($startHour)) * 60 + (intVal($endMin) - intVal($startMin));
        $hours = $durationMinutes / 60;

        return number_format($hours, 2);
    }
    /**
     * Save employee contract
     创建排班；是在指定计划中的指定某一天的一个排班；
     首先判断轮班类型：通过时间来判断是否发生了改变，如果发生了改变，保存修改，如果没有变，则不需要修改；
     其次，判断该排班复制情况：
      1, 仅仅对当天有效：则只保存在该scheduldid 下的shiftDayID 下面
      2，如果是复制到所有天：foreach计划中的所有天，然后将排班复制进去；
      3，如果只是应用到指定天（例如，周六，周日）：则循环所有天，然后将排班输入
     */
    public function save() { 

        $shift_list['shiftName']=$this->getValue('name');//轮班名称
        $shift_list['shift_id']=$this->getValue('shiftId');//轮班名称
        $shift_list['shiftType']=$this->getValue('shiftType');//选择轮班类型
        $shift_list['shiftDate']=$this->getValue('shiftDays');//选择轮班日期ID;
        $shift_list['relationshipType']=$this->getValue('relationshipType');//选择复制范围
        $shift_list['relationship']=$this->getValue('relationship');//自定义设置时间
        $shift_list['start_time']=$this->getValue('start_time');//轮班开始时间
        $shift_list['end_time']=$this->getValue('end_time');//轮班结束时
        $shift_list['required_employee']=$this->getValue('required_employee');//轮班结束时
        $shift_list['status']=$this->getValue('status');//轮班结束时
        $shift_list['scheduleID']=$this->getValue('scheduleID');//轮班结束时
        $shiftDates=$this->getShifDays($shift_list['scheduleID']);

        $this->shiftDates = $this->getShiftDateService()->getShiftDates($shift_list['scheduleID']);
        $shift_date=$this->_getShiftDateList();
        unset($shift_date[""]);
     
        //新建排班
        if (empty($shift_list['shift_id'])) {
             //如果排班仅对当天生效；
            if($shift_list['relationshipType']==2){
                    $shift = new WorkShiftNew;
                    
                    $create_at=date("Y-m-d",time());
                    $shift->setName($shift_list['shiftName']);
                    $shift->setStartTime($shift_list['start_time']);    
                    $shift->setEndTime($shift_list['end_time']);  
                    $shift->setScheduleId($shift_list['scheduleID']);
                    $shift->setShiftTypeId($shift_list['shiftType']);
                    $shift->setShiftdateId($shift_list['shiftDate']); 
                    $shift->setCreateAt($create_at); 
                    $shift->setHoursPerDay($this->getDuration($shift_list['start_time'], $shift_list['end_time']));
                    $shift->setStatus($shift_list['status']); 
                    $shift->setRequiredEmployee($shift_list['required_employee']);
                    $shift->setCreateAt($create_at); 
                    $this->getShiftService()->saveShift($shift);
                    // $shift_id=$shift->getId();
                    // $this->saveShiftAssignment($shift_id,$shift_list['required_employee'],$shift_list['scheduleID']);
            }

            //若果该排班对计划中所有日期生效； 
            if($shift_list['relationshipType']==3){
                // var_dump($shift_date);exit;
                
                foreach ($shift_date as $key => $shiftDate) {
                    $shift = new WorkShiftNew;
                    $create_at=date("Y-m-d",time());
                    $shift->setName($shift_list['shiftName']);
                    $shift->setStartTime($shift_list['start_time']);    
                    $shift->setEndTime($shift_list['end_time']);  
                    $shift->setScheduleId($shift_list['scheduleID']);
                    $shift->setShiftTypeId($shift_list['shiftType']);
                    $shift->setCreateAt($create_at); 
                    $shift->setHoursPerDay($this->getDuration($shift_list['start_time'], $shift_list['end_time']));
                    $shift->setStatus($shift_list['status']); 
                    $shift->setRequiredEmployee($shift_list['required_employee']);
                    $shift->setCreateAt($create_at); 
                    $shift->setShiftdateId($key); 
                    $this->getShiftService()->saveShift($shift); 
                    $shift_id=$shift->getId();
                    $this->saveShiftAssignment($shift_id,$shift_list['required_employee'],$shift_list['scheduleID']);
                }
                   
            }
            //如果排班特定日期生效；
            if($shift_list['relationshipType']==4){

                //获取日期对应的星期ID,日期ID对应星期ID；
                foreach ($shift_date as $key => $date) {
                    $week=$this->get_week($date);
                    $dates[$key]=$week;
                }
                
                foreach ($shift_list['relationship'] as $key => $value) {
                   foreach ($dates as $k => $val) {
                    if($val==$value)
                        {
                            $date_arr[]=$k;
                        }
                   }
                }

               foreach ($date_arr as $key => $index) {
                    $shift = new WorkShiftNew;
                    $create_at=date("Y-m-d",time());
                    $shift->setName($shift_list['shiftName']);
                    $shift->setStartTime($shift_list['start_time']);    
                    $shift->setEndTime($shift_list['end_time']);  
                    $shift->setScheduleId($shift_list['scheduleID']);
                    $shift->setShiftTypeId($shift_list['shiftType']);
                    $shift->setCreateAt($create_at); 
                    $shift->setHoursPerDay($this->getDuration($shift_list['start_time'], $shift_list['end_time']));
                    $shift->setStatus($shift_list['status']); 
                    $shift->setRequiredEmployee($shift_list['required_employee']);
                    $shift->setCreateAt($create_at); 
                    $shift->setShiftdateId($index); 
                    $this->getShiftService()->saveShift($shift); 
                    $shift_id=$shift->getId();
                    $this->saveShiftAssignment($shift_id,$shift_list['required_employee'],$shift_list['scheduleID']);
                }
            }

            $message = array('messageType' => 'success', 'message' => __(TopLevelMessages::SAVE_SUCCESS));
        } else {//更新
            $shift = $this->getShiftService()->getShiftById($id);
            $this->getShiftService()->saveShift($shift); 
            $message = array('messageType' => 'success', 'message' => __(TopLevelMessages::UPDATE_SUCCESS));
        }
    }

     public function saveShiftAssignment($shiftId,$required_employee,$schedule_id){
        for($i=0;$i<$required_employee;$i++){
            $shiftAssignment = new WorkShiftAssignment;
            $shiftAssignment->setShiftId($shiftId);
            $shiftAssignment->setScheduleId($schedule_id);
            $shiftAssignment->setShiftIndex($i);
            $this->getShiftService()->saveShiftAssignments($shiftAssignment);
        }
        
    }

    //默认如果创建班，也就给全部员工分配上这个班
    private function _saveEmployeeWorkShift($workShiftId) {
        $empWorkShiftCollection = new Doctrine_Collection('EmployeeWorkShift');
        
        for ($i = 0; $i < count($empArray); $i++) {
            $empWorkShift = new EmployeeWorkShift();
            $empWorkShift->setWorkShiftId($workShiftId);
            $empWorkShift->setEmpNumber($empArray[$i]);
            $empWorkShiftCollection->add($empWorkShift);
        }
        $this->getWorkShiftService()->saveEmployeeWorkShiftCollection($empWorkShiftCollection);
    }


}

