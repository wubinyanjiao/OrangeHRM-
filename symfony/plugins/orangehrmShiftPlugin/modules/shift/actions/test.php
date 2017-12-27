<?php

/*
    ／循环


*/

class rollXmlAction extends baseShiftAction {

    public function execute($request) {
  

        $scheduleID=$request->getParameter('schedule_id');
  
        $this->schedule_id=$scheduleID;


      
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
                $shiftResult->setScheduleID($scheduleID);
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

