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

                $data=date("Y-m-d",strtotime("+7 day",strtotime($v['shift_date'])));
                $shiftResult=$this->getShiftService()->getShiftResutById($v['id']);
                $shiftResult->setScheduleID($scheduleID);
                $shiftResult->setShiftDate($data);
                $shiftResult->setEmpNumber($key);
                $shiftResult->setShiftTypeId($v['shift_type_id']);
                $this->getShiftService()->saveShiftResult($shiftResult); 
               
            }
        }



         $this->redirect("shift/createXML?schedule_id=$scheduleID");

    }

}

