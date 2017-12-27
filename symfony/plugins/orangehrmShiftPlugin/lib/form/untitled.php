<?php

/*
    排班,
    第一，新创建一个schedule，排版日期为当前时间＋7天
    第二，新建排班类型，排班类型与原来一样;
    第三，新建排班日期，日期为新创建的日期
    第四，新建shift，同时将shiftskill和员工分配创建；
    第五，复制xml文件


*/

class rollXmlAction extends baseShiftAction {

    public function execute($request) {
  

        $scheduleID=$request->getParameter('schedule_id');
  
        $this->schedule_id=$scheduleID;
/*
        //获取要复制的计划信息
        $schedule_before=$this->getScheduleService()->getScheduleById($scheduleID);

        if($schedule_before->copy_type=='one'){

            $data_from=date("Y-m-d",strtotime("+7 day",strtotime($schedule_before->shiftDate)));
            $data_to=date("Y-m-d",strtotime("+6 day",strtotime($data_from)));

        }else if($schedule_before->copy_type=='two') {

            $data_from=date("Y-m-d",strtotime("+14 day",strtotime($schedule_before->shiftDate)));
            $data_to=date("Y-m-d",strtotime("+12 day",strtotime($data_from)));

        }else if($schedule_before->copy_type=='three') {

            $data_from=date("Y-m-d",strtotime("+21 day",strtotime($schedule_before->shiftDate)));
            $data_to=date("Y-m-d",strtotime("+18 day",strtotime($data_from)));

        }else{
             $data_from=date("Y-m-d",strtotime("+28 day",strtotime($schedule_before->shiftDate)));
             $data_to=date("Y-m-d",strtotime("+24 day",strtotime($data_from)));
        }




        $workShift = new WorkSchedule(); 
        $create_at=date('Y-m-d', time());
        $workShift->setName($schedule_before->name);
        $workShift->setCopyType($schedule_before->copy_type);
        $workShift->setStatus($schedule_before->status);
        $workShift->setShiftDate($data_from);
        $workShift->setCreateAt($create_at);


        $this->getScheduleService()->saveSchedule($workShift);


        $schedule_id_new=$workShift->getId();


        //复制创建日期

        $date_durn=$this->prDates($data_from,$data_to);

        foreach ($date_durn as $key => $day) {
            $workShiftDate = new WorkShiftDate;
            $workShiftDate->setShiftDate($day);
            $workShiftDate->setScheduleId($schedule_id_new);
            $this->getShiftDateService()->saveShiftDate($workShiftDate);
        }
       
       $shift_type_before=$this->getShiftTypeService()->getShiftTypes($scheduleID);

       //创建类型,同时创建班

       $shift_date_new=$this->getShiftService()->getShiftDateList($schedule_id_new);


       foreach ($shift_type_before as $typekey => $typeval) {

  
            $shiftType = new WorkShiftType();
     
            $shiftType->setName($typeval->name);
            $shiftType->setStartTime($typeval->start_time);    
            $shiftType->setEndTime($typeval->end_time);  
            $shiftType->setScheduleId($schedule_id_new);   
            $shiftType->setCreateAt($create_at); 
            $shiftType->setRequireEmployee($typeval->require_employee);  
            $shiftType->setCopyType($typeval->copy_type);   
            $shiftType->setStatus($typeval->status);
            $this->getShiftTypeService()->saveShiftType($shiftType); 

            $shift_copy_type=$shiftType->getCopyType();
            $shift_list['shiftName']=$shiftType->getName();
            $shift_list['start_time']=$shiftType->getStartTime();
            $shift_list['end_time']=$shiftType->getEndTime();
            $shift_list['scheduleID']=$shiftType->getScheduleId();
            $shift_list['shiftType']=$shiftType->getId();
            $shift_list['required_employee']=$shiftType->getRequireEmployee();

             $shift_list['status']=$shiftType->getStatus();

            if($shift_copy_type==2){
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
                    $shift_id=$shift->getId();
                    $shift_date=$shift->getShiftdateId();
                    
                    $this->saveShiftAssignment($shift_id,$shift_list['required_employee'],$shift_list['scheduleID'],$shift_date);
                    $this->_saveEmployeeWorkShift($shift_id);
            }

            //若果该排班对计划中所有日期生效； 
            if($shift_copy_type==3){
                // var_dump($shift_date);exit;
                
                foreach ($shift_date_new as $key => $shiftDate) {
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
                    $shift->setShiftdateId($shiftDate['id']); 

                    $this->getShiftService()->saveShift($shift); 
                    $shift_id=$shift->getId();

                    $shift_date=$shift->getShiftdateId();

                    $this->saveShiftAssignment($shift_id,$shift_list['required_employee'],$shift_list['scheduleID'],$shift_date);
                    $this->_saveEmployeeWorkShift($shift_id);
                }
                   
            }
            //如果排班特定日期生效；
            if($shift_copy_type==4){

                //获取日期对应的星期ID,日期ID对应星期ID；
                foreach ($shift_date_new as $key => $date) {
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
                    $shift->setShiftdateId($shiftDate['id']); 

                    $this->getShiftService()->saveShift($shift); 
                    $shift_id=$shift->getId();
                    $shift_date=$shift->getShiftdateId();

                    
                    $this->saveShiftAssignment($shift_id,$shift_list['required_employee'],$shift_list['scheduleID'],$shift_date);
                    $this->_saveEmployeeWorkShift($shift_id);
   

                }
            }
       }*/



        $assignment_list=$this->getShiftService()->getRosterResult($scheduleID);


        $employ_list=array_column($assignment_list,'emp_number');
        $employ_list=array_unique($employ_list);

        $date_list=array_column($assignment_list,'shift_date');
        $date_list=array_unique($date_list);

      

        //列出每一个员工的所有天的排班
       // var_dump($result['Assignment']);exit;
         $employee_array=array();

        foreach ($employ_list as $key => $employee) {

            foreach ($assignment_list as $k => $assignment) {

                if($assignment['emp_number']==$employee){
                    $employee_array[$employee][]=$assignment;
                }
            }

        }

        //获取数组键
        $arr_keys=array_keys($employee_array);


        array_unshift($arr_keys, array_pop($arr_keys));


        $array_ab=array_combine($arr_keys,$employee_array);

        foreach ($array_ab as $key => $new_result) {
            foreach ($new_result as $k => $v) {


                $shiftResult=$this->getShiftService()->getShiftResutById($v['id']);
               $shiftResult = new WorkShiftResult;
                $shiftResult->setScheduleID($schedule_id_new);
                $shiftResult->setShiftDate($v['shift_date']);
                $shiftResult->setEmpNumber($key);
                $shiftResult->setShiftTypeId($v['shift_type_id']);
                $this->getShiftService()->saveShiftResult($shiftResult); 
               
            }
        }



         $this->redirect("shift/createXML?schedule_id=$scheduleID");

    }



    //计算两个日期中的所有日期；
    public function prDates($start,$end){
        $dt_start = strtotime($start);
        $dt_end = strtotime($end);
        while ($dt_start<=$dt_end){
            $data[]= date('Y-m-d',$dt_start);
            $dt_start = strtotime('+1 day',$dt_start);
        }
        return $data;
    }

    public function saveShiftAssignment($shiftId,$required_employee,$schedule_id,$shift_date){
        for($i=0;$i<$required_employee;$i++){
            $shiftAssignment = new WorkShiftAssignment;
            $shiftAssignment->setShiftId($shiftId);
            $shiftAssignment->setScheduleId($schedule_id);
            $shiftAssignment->setShiftIndex($i);
            $shiftAssignment->setShiftDate($shift_date);
            $this->getShiftService()->saveShiftAssignments($shiftAssignment);
        }
        
    }

    //默认如果创建班，也就给全部员工分配上这个班
    private function _saveEmployeeWorkShift($workShiftId) {

       //获取所有员工编号。然后喂每个员工分配这个班
       $empArray = $this->getShiftService()->getEmployeeList();

        $empWorkShiftCollection = new Doctrine_Collection('EmployeeWorkShift');
        for ($i = 0; $i < count($empArray); $i++) {
            $empWorkShift = new EmployeeWorkShift();
            $empWorkShift->setWorkShiftId($workShiftId);
            
            $empWorkShift->setEmpNumber($empArray[$i]['empNumber']);

            $empWorkShiftCollection->add($empWorkShift);

        }
        $this->getShiftService()->saveEmployeeWorkShiftCollection($empWorkShiftCollection);
    }

    protected function getDuration($fromTime, $toTime) {
        list($startHour, $startMin) = explode(':', $fromTime);
        list($endHour, $endMin) = explode(':', $toTime);

        $durationMinutes = (intVal($endHour) - intVal($startHour)) * 60 + (intVal($endMin) - intVal($startMin));
        $hours = $durationMinutes / 60;

        return number_format($hours, 2);
    }

}

