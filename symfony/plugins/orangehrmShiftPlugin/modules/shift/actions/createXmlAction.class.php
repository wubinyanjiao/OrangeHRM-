<?php


class createXmlAction extends baseShiftAction {

    public function execute($request) {
  

        $scheduleID=$request->getParameter('schedule_id');
  
        $this->schedule_id=$scheduleID;
      
        $assignment_list=$this->getShiftService()->getRosterResult($scheduleID);

        $shiftTypes=$this->getShiftService()->getShiftTypeList($scheduleID);
		$shiftTypes = array_column($shiftTypes, NULL, 'id');

		$employeeList=$this->getShiftService()->getEmployeeList();
		$employeeList = array_column($employeeList, NULL, 'empNumber');

        $param=array('scheduleID' => $scheduleID);
        
        $this->updateShiftResultForm = new UpdateShiftResultForm(array(),$param,true);

        $this->result=$result;
       

        $employ_list=array_column($assignment_list,'emp_number');
        $employ_list=array_unique($employ_list);

        $date_list=array_column($assignment_list,'shift_date');
        $date_list=array_unique($date_list);

 

        //列出每一个员工的所有天的排班
       // var_dump($result['Assignment']);exit;
 
        foreach ($employ_list as $key => $employee) {

        	foreach ($assignment_list as $k => $assignment) {

        		if($assignment['emp_number']==$employee){
        			$employee_array[$employee][]=$assignment;
        		}
        	}
        }


        $emarray=array();

        foreach ($employee_array as $key => $employee) {

        	$employ_day=array_column($employee,'shift_date');
        	$employ_day=array_unique($employ_day);
        	

        	$diff=array_diff($date_list, $employ_day);


 			if(!empty($diff)){
 				foreach ($diff as $difkey=> $difday) {


 					$emarray[$key][$difday][]='休息';
 				}

              
 			}

        	foreach ($date_list as $ked => $date) {
        		foreach ($employee as $ks => $emday) {
                 
        			if($date==$emday['shift_date']){
                   
        				// $emarray[$key][$date][$emday['id']]=$shiftTypes[$emday['shift_type_id']]['name'];

                        if(is_numeric($emday['shift_type_id'])){
                            $emarray[$key][$date][$emday['id']]=$shiftTypes[$emday['shift_type_id']]['name'];
                        }else{
                            $emarray[$key][$date][$emday['id']]=$emday['shift_type_id'];
                        }
                        

        			}
        		}
        		
        	}
        	ksort($emarray[$key]);

        }


        $this->emarray=isset($new_emarray)?$new_emarray:$emarray;
        $this->date_list=$date_list;
        $this->employeeList=$employeeList;

    }

}

