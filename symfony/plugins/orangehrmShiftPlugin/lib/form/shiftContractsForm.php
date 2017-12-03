<?php

class shiftContractsForm extends sfForm {

    private $employeeService;
    public $shift_dates;
    public $shiftsByDate;

     //获取排班类型实例
    public function getShiftTypeService() {
        if (is_null($this->shiftTypeService)) {
            $this->shiftTypeService = new ShiftTypeService();
            $this->shiftTypeService->setShiftTypeDao(new ShiftTypeDao());
        }
        return $this->shiftTypeService;
    }

    public function getShiftService(){
       
        if(is_null($this->shifService)) {
            $this->shifService = new ShiftService();
            $this->shifService->setShiftDao(new ShiftDao());
        }
        return $this->shifService;
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


        $shift = $this->getOption('shift');
        $scheduleID = $this->getOption('scheduleID');
        $shift_date = $this->getOption('shift_date');
        $shift_type = $this->getOption('shift_type');


        $this->shiftTypes = $this->getShiftTypeService()->getShiftTypes($scheduleID);
        $this->shiftDates = $this->getShiftDateService()->getShiftDates($scheduleID);

        $workExperienceWidgets = $this->getWorkExperienceWidgets();
        $workExperienceValidators = $this->getWorkExperienceValidators();

 
        $widgets = $workExperienceWidgets;
        $validators = $workExperienceValidators;
        

        $this->setWidgets($widgets);
        $this->setValidators($validators);
        
        $start_time=substr($shift->start_time,0,5);
        $end_time=substr($shift->end_time,0,5);


        $this->setDefault('shiftId', $shift->id);
        $this->setDefault('scheduleID', $scheduleID);
        $this->setDefault('name',$shift->name);
        $this->setDefault('shiftType', $shift->shift_type_id);
        $this->setDefault('shiftDays', $shift->shiftdate_id);
        $this->setDefault('start_time', $start_time);
        $this->setDefault('end_time',$end_time);
        $this->setDefault('status',$shift->status);

        $this->setDefault('required_employee',$shift->required_employee);
       

        //获取列表集合
        $this->shiftsByDate = $this->getShiftService()->getShiftList($scheduleID);
        $shift_dates =$this->getShiftService()->getShiftDateList($scheduleID);
        $this->shift_dates=array_column($shift_dates, NULL, 'id');

        $shift_types =$this->getShiftService()->getShiftTypeList($scheduleID);
        $this->shift_types=array_column($shift_types, NULL, 'id');

        $this->widgetSchema->setNameFormat('workShift[%s]');
    }


    public function getWorkExperienceWidgets() {

        
        $status = array('1' => __('Enabled'), '0' => __('Disabled'));

        $widgets = array();

        //creating widgets
        $widgets['shiftId'] = new sfWidgetFormInputHidden();
        $widgets['scheduleID'] = new sfWidgetFormInputHidden();
        $widgets['name'] = new sfWidgetFormInputText();
        $widgets['shiftDays']= new sfWidgetFormSelect(array('choices' => $this->_getShiftDateList()));
        $widgets['shiftType']= new sfWidgetFormSelect(array('choices' => $this->_getShiftTypeList()));
        $widgets['start_time'] = new ohrmWidgetTimeDropDown();
        $widgets['end_time'] = new ohrmWidgetTimeDropDown();
        $widgets['required_employee'] = new sfWidgetFormInputText();
        $widgets['status']= new sfWidgetFormSelect(array('choices' => $status));
       
        $widgets['comments'] = new sfWidgetFormTextarea();



        return $widgets;
    }

    /*
     * Tis fuction will return the form validators
     */

    public function getWorkExperienceValidators() {
        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();

        $validators = array(
            'shiftId' => new sfValidatorString(array('required' => false)),
            'scheduleID' => new sfValidatorNumber(array('required' => false, 'min'=> 0)),
            'name' => new sfValidatorString(array('required' => true, 'max_length' => 100)),
            'shiftDays' => new sfValidatorString(array('required' => true, 'max_length' => 13)),
            'shiftType' => new sfValidatorString(array('required' => true, 'max_length' => 13)),
            'start_time' => new sfValidatorString(array('required' => true, 'max_length' => 120)),
            'end_time' => new sfValidatorString(array('required' => true, 'max_length' => 120)),

            'required_employee' => new sfValidatorString(array('required' => true, 'trim'=>true, 'max_length'=>100)),
            'status' => new sfValidatorString(array('required' => false)),

           
            'comments' => new sfValidatorString(array('required' => false, 'max_length' => 200)),
        );

        return $validators;
    }

    private function _getShiftDateList() {

        $list = array("" => "-- " . __('Select') . " --");
        foreach($this->shiftDates as $shiftDate) {
            
            $list[$shiftDate->getId()] = $shiftDate->getShiftDate();
        }
        return $list;
    }
    private function _getShiftTypeList() {
        $list = array("" => "-- " . __('Select') . " --");

        //获取排班类型列表
        foreach($this->shiftTypes as $shiftType) {
            $list[$shiftType->getId()] = $shiftType->getName();
        }
        return $list;
    }


}

?>