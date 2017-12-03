<?php

class ShiftAllocateForm extends sfForm {
    
    private $employeeService;
    public $fullName;
    private $widgets = array();
    public $empSkillList;
    private $workShiftService;

    public function getWorkShiftService() {
        if (is_null($this->workShiftService)) {
            $this->workShiftService = new WorkShiftService();
            $this->workShiftService->setWorkShiftDao(new WorkShiftDao());
        }
        return $this->workShiftService;
    }
    /**
     * Get EmployeeService
     * @returns EmployeeService
     */
    public function getEmployeeService() {
        if(is_null($this->employeeService)) {
            $this->employeeService = new EmployeeService();
            $this->employeeService->setEmployeeDao(new EmployeeDao());
        }
        return $this->employeeService;
    }

    /**
     * Set EmployeeService
     * @param EmployeeService $employeeService
     */
    public function setEmployeeService(EmployeeService $employeeService) {
        $this->employeeService = $employeeService;
    }

    public function configure() {
        $shift_id = $this->getOption('shift_id');
        $scheduleID = $this->getOption('scheduleID');

        $shiftEmployeeWidgets = $this->getSkillsWidgets();
        $shiftEmployeeValidators = $this->getSkillsValidators();


        $widgets =$shiftEmployeeWidgets;
        $validators = $shiftEmployeeValidators;
     

        $this->setWidgets($widgets);
        $this->setValidators($validators);

        $this->setDefault('workShiftId', $shift_id);
        $this->setDefault('scheduleID', $scheduleID);

        $this->widgetSchema->setNameFormat('workShift[%s]');
    }
    
    
    /*
     * Tis fuction will return the widgets of the form
     */
    public function getSkillsWidgets() {
        $widgets = array();
        $shift_id = $this->getOption('shift_id');
        $shiftIdNew= $this->getOption('shiftIdNew');

        if(!empty($shiftIdNew)){
            $shiftID=$shiftIdNew;
        }else{
            $shiftID=$shift_id;
        }

        $scheduleID = $this->getOption('scheduleID');
        $employeeList = $this->getEmployeeList($shiftID);

        
        


        $exist_employeeList = $this->getExistEmployeeList($shiftID);

        $widgets['workShiftId'] = new sfWidgetFormInputHidden();
        $widgets['scheduleID'] = new sfWidgetFormInputHidden();
        $widgets['code'] = new sfWidgetFormSelect(array('choices' => $this->_getShiftList($scheduleID)));

        $widgets['availableEmp'] = new sfWidgetFormSelectMany(array('choices' => $employeeList));
        $widgets['assignedEmp'] = new sfWidgetFormSelectMany(array('choices' => $exist_employeeList));

        return $widgets;
    }

    /*
     * Tis fuction will return the form validators
     */
    public function getSkillsValidators() {
        
        $validators = array(
            'workShiftId' => new sfValidatorNumber(array('required' => false)),
            'scheduleID' => new sfValidatorNumber(array('required' => false)),
            'code' => new sfValidatorString(array('required' => false, 'max_length' => 13)),
           
            'availableEmp' => new sfValidatorPass(),
            'assignedEmp' => new sfValidatorPass(),
        );

        return $validators;
    }

     public function getExistEmployeeList($workShiftId){

        // var_dump($workShiftId);exit;

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

    /*
        给排班分配员工
        1，首先根据shift_id判断是否在数据库中这个ID有人员安排到这个班上
        2，如果有这个班已经有人员排班；如果这个班已经有雇员安排了班，则执行下面已有逻辑
        3，如果在数据库中并没有给这个班安排雇员，则显示所有雇员；
    */

    public function getEmployeeList($workShiftId) {

        $empNameList = array();


        //判断是否有员工为该班分配

        $existWorkShiftEmpList = $this->getWorkShiftService()->getWorkShiftEmployeeNameListById($workShiftId);
        

        $employeeList = $this->getEmployeeService()->getEmployeePropertyList($properties, 'lastName', 'ASC', true);

        $properties = array("empNumber", "firstName", "middleName", "lastName");
        $employeeList = $this->getEmployeeService()->getEmployeePropertyList($properties, 'lastName', 'ASC', true);


        if(empty($existWorkShiftEmpList)){

            foreach ($employeeList as $employee) {

                $empNumber = $employee['empNumber'];
                $name = trim(trim($employee['firstName'] . ' ' . $employee['middleName'], ' ') . ' ' . $employee['lastName']);
                $name = trim($employee['firstName']);
                $empNameList[$empNumber] = $name;
                
            }

        }else{

            $existWorkShiftEmpList = $this->getWorkShiftService()->getWorkShiftEmployeeIdList();

            foreach ($employeeList as $employee) {

                $empNumber = $employee['empNumber'];

                if (!in_array($empNumber, $existWorkShiftEmpList)) {

                    $name = trim(trim($employee['firstName'] . ' ' . $employee['middleName'], ' ') . ' ' . $employee['lastName']);
                    $name = trim($employee['firstName']);
                    $empNameList[$empNumber] = $name;
                }
            }
        }

        
        $this->employeeList = $empNameList;

        return $empNameList;
    }

    private function _getShiftList($scheduleID) {
        $shiftService = new ShiftService();
        $ShiftList = $shiftService->getShifts($scheduleID);
        $list = array("" => "-- " . __('Select') . " --");

        foreach($ShiftList as $shift) {
            $list[$shift->getId()] = $shift->getName();
        }
        
        // Clear already used skill items
        foreach ($this->empSkillList as $empSkill) {
            if (isset($list[$empSkill->skillId])) {
                unset($list[$empSkill->skillId]);
            }
        }
        return $list;
    }

    public function save() {

        $workShiftId = $this->getValue('workShiftId');
        
        if (empty($workShiftId)) {
            $workShift = new WorkShift();
            $empArray = $this->getValue('assignedEmp');            
        } else {
            $workShift = $this->getWorkShiftService()->getWorkShiftById($workShiftId);

            $employees = $this->getValue('assignedEmp');

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
        // var_dump($empArray);exit;
        
        $this->_saveEmployeeWorkShift($workShift->getId(), $empArray);
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

   
}
?>