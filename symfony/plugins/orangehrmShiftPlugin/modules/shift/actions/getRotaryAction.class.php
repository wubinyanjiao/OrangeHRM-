<?php


class getRotaryAction extends baseShiftAction {

    public function execute($request) {



        $rotaryID=$request->getParameter('rotaryId');

        $rotary_list=$this->getShiftService()->getRotaryEmpListById($rotaryID);
    
        $rotary_list=array_column($rotary_list,null,'emp_number');
  
        $date_list=array_column($rotary_list,'dateFrom','dateFrom');

        $employeeList=$this->getShiftService()->getEmployeeList();
        $employeeList = array_column($employeeList, NULL, 'empNumber');

     
        //罗列出每一个员工从起始月到最后一个月的所轮转部门；
        foreach ($rotary_list as $key => $employee) {
            //循环所有月份。如果这个月有该员工，则显示该员工；，如果没有，则显示空
            foreach ($date_list as $k => $day) {
                if($employee['dateFrom']==$day){
                    $date_employ_list[$key][$k]['rotaryDepartment']=$employee['rotaryDepartment'];
                    $date_employ_list[$key][$k]['orangeDepartment']=$employee['orangeDepartment'];

                }else{
                    $date_employ_list[$key][$k]['rotaryDepartment']='';
                    $date_employ_list[$key][$k]['orangeDepartment']=$employee['orangeDepartment'];

                }
            }
           
            ksort($date_employ_list[$key]);
        }

        
        $locationList=$this->getJobDocument();

        $this->locationList=$locationList;
        $this->date_list=$date_list;

        $this->date_employ_list=$date_employ_list;
        $this->employeeList=$employeeList;

    }

    public function getJobDocument(){
       $locationList = array('' => '-- ' . __('') . ' --');

        $locationService = new LocationService();
        $locations = $locationService->getLocationList();        

 
        foreach ($locations as $location) {
          
                $locationList[$location->id] = $location->name;
           
        }
      
        return($locationList);
    }

}

