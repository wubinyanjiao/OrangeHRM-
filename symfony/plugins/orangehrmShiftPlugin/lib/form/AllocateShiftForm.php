<?php


class AllocateShiftForm extends BaseForm {

    private $workShiftService;
    private $employeeService;
    private $employeeList;
    private $shiftService;

    public function getWorkShiftService() {
        if (is_null($this->workShiftService)) {
            $this->workShiftService = new WorkShiftService();
            $this->workShiftService->setWorkShiftDao(new WorkShiftDao());
        }
        return $this->workShiftService;
    }
    private function getShiftService() {

        if(is_null($this->shiftService)) {
            $this->shiftService = new ShiftService();
        }
        return $this->shiftService;
    }
    
    public function getEmployeeService() {
        if (is_null($this->employeeService)) {
            $this->employeeService = new EmployeeService();
        }
        return $this->employeeService;
    }

    public function setEmployeeService(EmployeeService $employeeService) {
        $this->employeeService = $employeeService;
    }


    public function configure() {
        $shift = $this->getOption('shift');
        $scheduleID = $this->getOption('scheduleID');
        $shift_date = $this->getOption('shift_date');
        $shift_type = $this->getOption('shift_type');
        $employeeList = $this->getEmployeeList();

        

        $exist_employeeList = $this->getExistEmployeeList($shift['id']);

    
        $this->setWidgets(array(
            'workShiftId' => new sfWidgetFormInputHidden(),
            'scheduleID' => new sfWidgetFormInputHidden(),
            'shiftDate'=>new ohrmWidgetDatePicker(array(), array('id' => 'dependent_dateOfBirth')),
            'name' => new sfWidgetFormInputText(array(), array("maxlength" => 52)),
            'shiftType' => new sfWidgetFormInputText(array(), array("maxlength" => 52)),
            'from_time' => new sfWidgetFormInputText(array(), array("maxlength" => 52)),
            'to_time' => new sfWidgetFormInputText(array(), array("maxlength" => 52)),
           
            'availableEmp' => new sfWidgetFormSelectMany(array('choices' => $employeeList)),
            'assignedEmp' => new sfWidgetFormSelectMany(array('choices' => $exist_employeeList)),
        ));

        $this->setValidators(array(
            'workShiftId' => new sfValidatorNumber(array('required' => false)),
            'scheduleID' => new sfValidatorNumber(array('required' => false)),
            'name' =>new sfValidatorString(array('required' => true, 'max_length' => 52)),
            'from_time' =>new sfValidatorString(array('required' => false, 'max_length' => 52)),
            'to_time' =>new sfValidatorString(array('required' => false, 'max_length' => 52)),
            'shiftType' =>new sfValidatorString(array('required' => true, 'max_length' => 52)),
            'shiftDate' => new sfValidatorString(array('required' => false, 'max_length' => 52)),
       
            'availableEmp' => new sfValidatorPass(),
            'assignedEmp' => new sfValidatorPass(),
        ));

        $start_time=substr($shift->start_time,0,5);
        $end_time=substr($shift->end_time,0,5);
    
        $this->setDefault('workShiftId', $shift->id);
        $this->setDefault('scheduleID', $scheduleID);
        $this->setDefault('name',$shift->name);
        $this->setDefault('shiftType', $shift_type->name);
        $this->setDefault('shiftDate', $shift_date->shiftDate);
        $this->setDefault('from_time', $start_time);
        $this->setDefault('to_time',$end_time);
     

        $requiredMarker = '&nbsp;<em>*</em>';

        $labels = array(
            'name' => __('Shift Name') . $requiredMarker,
            'workHours' => __('Work Hours') . $requiredMarker,
            'shiftDate'=>__('日期'),
            'shiftType'=>__('排班类型')
        );

        $this->getWidgetSchema()->setNameFormat('workShift[%s]');
        $this->getWidgetSchema()->setLabels($labels);
       // $this->getShiftService()->createXml($scheduleID);
    }


    public function save() {

        $schedule_id=$this->getValue('scheduleID');

        $workShiftId = $this->getValue('workShiftId');

        
        if (empty($workShiftId)) {
            //创建排班信息
            // $workShift = new WorkShift();
            $empArray = $this->getValue('assignedEmp');            
        } else {

            //修改排班信息
            $workShift = $this->getWorkShiftService()->getWorkShiftById($workShiftId);
            //获取雇员表中雇员编号
            $employees = $this->getValue('assignedEmp');
        //获取数据库中雇员信息
            $existingEmployees = $workShift->getEmployeeWorkShift();
            $idList = array();
            if ($existingEmployees[0]->getEmpNumber() != "") {
                foreach ($existingEmployees as $existingEmployee) {
                    $id = $existingEmployee->getEmpNumber();
                    if (!in_array($id, $employees)) {
                        $existingEmployee->delete();
                    } else {
                        $idList[] = $id;
                    }
                }
            }

            $employeeList = array_diff($employees, $idList);
            $newList = array();
            foreach ($employeeList as $employee) {
                $newList[] = $employee;
            }
            $empArray = $newList;
        }
        
        $params['shift_id']= (int)$workShiftId;
        $params['schedule_id']= (int)$schedule_id;
        
        $this->_saveEmployeeWorkShift($workShiftId, $empArray);
        return $params;

    }

    private function _saveEmployeeWorkShift($workShiftId, $empArray) {
        $empWorkShiftCollection = new Doctrine_Collection('EmployeeWorkShift');
        
        for ($i = 0; $i < count($empArray); $i++) {
            $empWorkShift = new EmployeeWorkShift();
            $empWorkShift->setWorkShiftId($workShiftId);
            $empWorkShift->setEmpNumber($empArray[$i]);
            $empWorkShiftCollection->add($empWorkShift);
        }
        $this->getWorkShiftService()->saveEmployeeWorkShiftCollection($empWorkShiftCollection);
    }

    public function getExistEmployeeList($workShiftId){

         $existWorkShiftEmpList = $this->getWorkShiftService()->getWorkShiftEmployeeNameListById($workShiftId);
       
        $existEmployeeList = array();
         foreach ($existWorkShiftEmpList as $key => $employee) {
            $empNumber = $employee['empNumber'];
            $name=$employee['firstName'];
            $existEmployeeList[$empNumber] = $name;
         }
         $this->exist_employeeList = $existEmployeeList;
        return $existEmployeeList;

    }

    public function getEmployeeList() {

        $empNameList = array();

        $properties = array("empNumber", "firstName", "middleName", "lastName");
        $employeeList = $this->getEmployeeService()->getEmployeePropertyList($properties, 'lastName', 'ASC', true);

        $existWorkShiftEmpList = $this->getWorkShiftService()->getWorkShiftEmployeeIdList();

        foreach ($employeeList as $employee) {

            $empNumber = $employee['empNumber'];

            if (!in_array($empNumber, $existWorkShiftEmpList)) {

                $name = trim(trim($employee['firstName'] . ' ' . $employee['middleName'], ' ') . ' ' . $employee['lastName']);
                $name = trim($employee['firstName']);
                $empNameList[$empNumber] = $name;
            }
        }
        $this->employeeList = $empNameList;
        return $empNameList;
    }

    public function getEmployeeListAsJson() {

        $jsonArray = array();
        foreach ($this->employeeList as $key => $value) {
            $jsonArray[] = array('name' => $value, 'id' => $key);
        }
        
        return json_encode($jsonArray);
    }

    public function getWorkShiftListAsJson() {

        $jsonArray = array();        
        $workShiftList = $this->getWorkShiftService()->getWorkShiftList();

        foreach ($workShiftList as $workShift) {
            $jsonArray[] = array('name' => $workShift->getName(), 'id' => $workShift->getId());
        }

        return json_encode($jsonArray);
    }

    protected function getDuration($fromTime, $toTime) {
        list($startHour, $startMin) = explode(':', $fromTime);
        list($endHour, $endMin) = explode(':', $toTime);

        $durationMinutes = (intVal($endHour) - intVal($startHour)) * 60 + (intVal($endMin) - intVal($startMin));
        $hours = $durationMinutes / 60;

        return number_format($hours, 2);
    }

}

