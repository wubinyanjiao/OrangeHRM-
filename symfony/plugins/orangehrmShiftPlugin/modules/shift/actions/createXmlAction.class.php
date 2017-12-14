<?php


class createXmlAction extends baseShiftAction {

    public function execute($request) {

        $scheduleID=$request->getParameter('schedule_id');
        $this->getShiftService()->createXml($scheduleID);
        // $this->getShiftService()->createRotaryXml($scheduleID);
        $this->schedule_id=$scheduleID;
        //执行java程序；

        $file_path="/Users/wubin/Documents/Github/www/OrangeHRM/symfony/plugins/orangehrmShiftPlugin/lib/service/files/TCM_xml_schedule.xml";
        $roaster_path="/Users/wubin/Documents/Github/www/OrangeHRM/symfony/plugins/orangehrmShiftPlugin/lib/service/Linux-NurseRostering/NurseRostering.jar";
        $java_path="/Library/Java/JavaVirtualMachines/jdk-9.0.1.jdk/Contents/Home/bin/java";

        exec("$java_path  -jar $roaster_path  $file_path 2>&1",$output, $return_val);

        $result=$this->getShiftService()->getRosterResult();



        $shiftTypes=$this->getShiftService()->getShiftTypeList($scheduleID);
		$shiftTypes = array_column($shiftTypes, NULL, 'id');

		$employeeList=$this->getShiftService()->getEmployeeList();
		$employeeList = array_column($employeeList, NULL, 'empNumber');
        


        $this->result=$result;
        $assignment_list=$result['Assignment'];

        // echo'<pre>';var_dump($assignment_list);exit;



        $employ_list=array_column($assignment_list,'Employee');
        $employ_list=array_unique($employ_list);

        $date_list=array_column($assignment_list,'Date');
        $date_list=array_unique($date_list);

      

        //列出每一个员工的所有天的排班
       // var_dump($result['Assignment']);exit;

        foreach ($employ_list as $key => $employee) {

        	foreach ($assignment_list as $k => $assignment) {

        		if($assignment['Employee']==$employee){
        			$employee_array[$employee][]=$assignment;
        		}
        	}
        }


        $emarray=array();

        foreach ($employee_array as $key => $employee) {

        	$employ_day=array_column($employee,'Date');
        	$employ_day=array_unique($employ_day);
        	

        	$diff=array_diff($date_list, $employ_day);

 			if(!empty($diff)){
 				foreach ($diff as $difkey=> $difday) {
 					$emarray[$key][$difday][]='休息';
 				}
 			}

        	foreach ($date_list as $ked => $date) {
        		foreach ($employee as $ks => $emday) {
        			if($date==$emday['Date']){
        				$emarray[$key][$date][]=$shiftTypes[$emday['ShiftType']]['name'];
        			}
        		}
        		
        	}
        	ksort($emarray[$key]);

        }


        $this->emarray=$emarray;
        $this->date_list=$date_list;
        $this->employeeList=$employeeList;

    }

}

