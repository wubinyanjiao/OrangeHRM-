<?php

class ContranctaSearchForm extends BaseForm {

    private $nationalityService;
    private $employeeService;
    private $readOnlyWidgetNames = array();
    private $gender;
    private $employee;
    public $fullName;

    public function configure() {

         $scheduleID = $this->getOption('scheduleID');

         $this->schedule_id=$scheduleID;


        $widgets = array('txtEmpID' => new sfWidgetFormInputHidden(array(), array('value' => $this->employee->empNumber)));
        $validators = array('txtEmpID' => new sfValidatorString(array('required' => false)));

       
        $personalInfoWidgets = $this->getPersonalInfoWidgets();
        $personalInfoValidators = $this->getPersonalInfoValidators();

           
        $widgets = array_merge($widgets, $personalInfoWidgets);
        $validators = array_merge($validators, $personalInfoValidators);

        $this->setWidgets($widgets);
        $this->setValidators($validators);

        $this->widgetSchema->setNameFormat('personal[%s]');
    }

    public function getReadOnlyWidgetNames() {
        return $this->readOnlyWidgetNames;
    }


    private function _getShiftTypeList() {

        $scheduleID = $this->getOption('scheduleID');

        $shiftTypeService = new ShiftTypeService();
        $shiftTypeList = $shiftTypeService->getShiftTypes($scheduleID);

        $list = array("" => "选择班次");

        foreach($shiftTypeList as $shiftType) {
            $list[$shiftType->getId()] = $shiftType->getName();
        }


        return $list;
    }

    private function _getEmployeeList() {

        $shiftService = new ShiftService();
        $empoyeeList = $shiftService->getEmployeeList();

        $list = array("" => "选择员工");

        foreach($empoyeeList as $employee) {
            $list[$employee['empNumber']] = $employee['firstName'];
        }


        return $list;
    }

    private function getPersonalInfoWidgets() {

        $status = array('1' => __('Enabled'), '0' => __('Disabled'));
        $onOff = array('1' => __('是'), '0' => __('否'));
        $freeDays = array('1' => __('一天'), '2' => __('两天'),'3' => __('三天'),'4' => __('四天'),'5' => __('五天'),'6' => __('六天'));
        $widgets = array(
            // 夜班后夜休
            'nightAfterNightLeisureShiftSelect' => new sfWidgetFormSelect(array('choices' => $this->_getShiftTypeList())),
            'nightAfterNightLeisureWeight' => new sfWidgetFormInputText(),
            'nightAfterNightLeisureStatus' => new sfWidgetFormSelect(array('choices' => $status)),

            // 班次尽量平均分配
            'averageAssignment' => new sfWidgetFormInputText(),
            'averageAssignmentShiftSelect' => new sfWidgetFormSelect(array('choices' => $this->_getShiftTypeList())),
            'averageAssignmentWeight' => new sfWidgetFormInputText(),
            'averageAssignmentStatus' => new sfWidgetFormSelect(array('choices' => $status)),

            // 班次只分配给男性
            'shiftdOnlyforManShiftSelect' => new sfWidgetFormSelect(array('choices' => $this->_getShiftTypeList())),
            'shiftdOnlyforManWeight' => new sfWidgetFormInputText(),
            'shiftdOnlyforManStatus' => new sfWidgetFormSelect(array('choices' => $status)),

            // 每周公休分配
            'freeTwoDaysSelect' => new sfWidgetFormSelect(array('choices' =>  $freeDays)),
            'freeTwoDaysWeight' => new sfWidgetFormInputText(),
            'freeTwoDaysStatus' => new sfWidgetFormSelect(array('choices' => $status)),

            //该班次分配后间隔后再分配
            'assignmentAfterIntervalShiftSelect' => new sfWidgetFormSelect(array('choices' => $this->_getShiftTypeList())),
            'assignmentAfterIntervalEmployee' => new sfWidgetFormInputText(),
            'assignmentAfterIntervalWeight' => new sfWidgetFormInputText(),
            'assignmentAfterIntervalStatus' => new sfWidgetFormSelect(array('choices' => $status)),

            //该班次分配后持续分配

            'assignmentAfterShiftSelect' => new sfWidgetFormSelect(array('choices' => $this->_getShiftTypeList())),
            'assignmentAfterShiftDays' => new sfWidgetFormSelect(array('choices' => $freeDays)),
            'assignmentAfterShiftWeight' => new sfWidgetFormInputText(),
            'assignmentAfterShiftStatus' => new sfWidgetFormSelect(array('choices' => $status)),


            //最多允许连续工作几个周末

            'allowWeekendShift' => new sfWidgetFormInputText(),
            'maxWeekendShiftWeight' => new sfWidgetFormInputText(),
            'maxWeekendShiftStatus' => new sfWidgetFormSelect(array('choices' => $status)),



            //周六日连休

            'restOnStaAndSunWeight' => new sfWidgetFormInputText(),
            'restOnStaAndSunOn' => new sfWidgetFormSelect(array('choices' => $onOff)),
            'restOnStaAndSunStatus' => new sfWidgetFormSelect(array('choices' => $status)),


            // 该班次不分配给某员工
            'shiftNotForEmployeeShiftSelect' => new sfWidgetFormSelect(array('choices' => $this->_getShiftTypeList())),
            'shiftNotForEmployee' => new sfWidgetFormSelect(array('choices' => $this->_getEmployeeList())),
            'shiftNotForEmployeeWeight' => new sfWidgetFormInputText(),
            'shiftNotForEmployeeStatus' => new sfWidgetFormSelect(array('choices' => $status)),


            // 该班次分配给某员工
            'shiftForEmployeeShiftSelect' => new sfWidgetFormSelect(array('choices' => $this->_getShiftTypeList())),
            'shiftForEmployee' => new sfWidgetFormSelect(array('choices' => $this->_getEmployeeList())),
            'shiftForEmployeeWeight' => new sfWidgetFormInputText(),
            'shiftForEmployeeStatus' => new sfWidgetFormSelect(array('choices' => $status)),


             //不希望此班次后继续班次

            'startShiftSelect' => new sfWidgetFormSelect(array('choices' => $this->_getShiftTypeList())),
            'nextShiftSelect' => new sfWidgetFormSelect(array('choices' => $this->_getShiftTypeList())),
            'restAfterOneShiftWeight' => new sfWidgetFormInputText(),
            'restAfterOneShiftStatus' => new sfWidgetFormSelect(array('choices' => $status)),

            // 周六工作在周二或周四安排调休
            'restOnTuOrTuesWeight' => new sfWidgetFormInputText(),
            'restOnTuOrTuesStatus' => new sfWidgetFormSelect(array('choices' => $status)),


            //连续周末分配同一班次
            'continuWeekOneShiftWeight' => new sfWidgetFormInputText(),
            'continuWeekOneShiftStatus' => new sfWidgetFormSelect(array('choices' => $status)),


            //最少任务天数分配

            'minWorkDayCount' => new sfWidgetFormInputText(),
            'minWorkDayWeight' => new sfWidgetFormInputText(),
            'minWorkDayStatus' => new sfWidgetFormSelect(array('choices' => $status)),


             //最多任务天数分配

            'maxWorkDayCount' => new sfWidgetFormInputText(),
            'maxWorkDayWeight' => new sfWidgetFormInputText(),
            'maxWorkDayStatus' => new sfWidgetFormSelect(array('choices' => $status)),



            // 最少允许连续工作几个周末
            'minWorkWeekendCount' => new sfWidgetFormInputText(),
            'minWorkWeekendStatus' => new sfWidgetFormSelect(array('choices' => $status)),
      

            


        );

        //setting default values
        $widgets['nightAfterNightLeisureStatus']->setAttribute('value', '');
        $widgets['nightAfterNightLeisureWeight']->setAttribute('value', '');
        $widgets['nightAfterNightLeisureShiftSelect']->setAttribute('value','');

         //setting default values
        $widgets['averageAssignment']->setAttribute('value', '');
        $widgets['averageAssignmentShiftSelect']->setAttribute('value','');
        $widgets['averageAssignmentWeight']->setAttribute('value', '');
        $widgets['averageAssignmentStatus']->setAttribute('value', '');

        // 班次只分配给男性
        $widgets['shiftdOnlyforManShiftSelect']->setAttribute('value','');
        $widgets['shiftdOnlyforManWeight']->setAttribute('value', '');
        $widgets['shiftdOnlyforManStatus']->setAttribute('value', '');

        // 每周公休分配
        $widgets['freeTwoDaysSelect']->setAttribute('value','');
        $widgets['freeTwoDaysWeight']->setAttribute('value', '');
        $widgets['freeTwoDaysStatus']->setAttribute('value', '');

        //该班次分配后间隔后再分配
        $widgets['assignmentAfterIntervalShiftSelect']->setAttribute('value', '');
        $widgets['assignmentAfterIntervalEmployee']->setAttribute('value','');
        $widgets['assignmentAfterIntervalWeight']->setAttribute('value', '');
        $widgets['assignmentAfterIntervalStatus']->setAttribute('value', '');


        //该班次分配后持续分配

        $widgets['assignmentAfterShiftSelect']->setAttribute('value', '');
        $widgets['assignmentAfterShiftDays']->setAttribute('value','');
        $widgets['assignmentAfterShiftWeight']->setAttribute('value', '');
        $widgets['assignmentAfterShiftStatus']->setAttribute('value', '');


        //最多允许连续工作几个周末

        $widgets['allowWeekendShift']->setAttribute('value','');
        $widgets['maxWeekendShiftWeight']->setAttribute('value', '');
        $widgets['maxWeekendShiftStatus']->setAttribute('value', '');


        //周六日连休

        $widgets['restOnStaAndSunOn']->setAttribute('value','');
        $widgets['restOnStaAndSunWeight']->setAttribute('value', '');
        $widgets['restOnStaAndSunStatus']->setAttribute('value', '');

         
         // 该班次不分配给某员工

        $widgets['shiftNotForEmployeeShiftSelect']->setAttribute('value', '');
        $widgets['shiftNotForEmployee']->setAttribute('value','');
        $widgets['shiftNotForEmployeeWeight']->setAttribute('value', '');
        $widgets['shiftNotForEmployeeStatus']->setAttribute('value', '');


        // 该班次分配给某员工

        $widgets['shiftForEmployeeShiftSelect']->setAttribute('value', '');
        $widgets['shiftForEmployee']->setAttribute('value','');
        $widgets['shiftForEmployeeWeight']->setAttribute('value', '');
        $widgets['shiftForEmployeeStatus']->setAttribute('value', '');


        //不希望此班次后继续班次

        $widgets['startShiftSelect']->setAttribute('value', '');
        $widgets['nextShiftSelect']->setAttribute('value','');
        $widgets['restAfterOneShiftWeight']->setAttribute('value', '');
        $widgets['restAfterOneShiftStatus']->setAttribute('value', '');


        // 周六工作在周二或周四安排调休

        $widgets['restOnTuOrTuesWeight']->setAttribute('value', '');
        $widgets['restOnTuOrTuesStatus']->setAttribute('value', '');

        //连续周末分配同一班次

        $widgets['continuWeekOneShiftWeight']->setAttribute('value', '');
        $widgets['continuWeekOneShiftStatus']->setAttribute('value', '');

        //最少任务天数分配

        $widgets['minWorkDayCount']->setAttribute('value', '');
        $widgets['minWorkDayWeight']->setAttribute('value', '');
        $widgets['minWorkDayStatus']->setAttribute('value', '');

        //最多任务天数分配

        $widgets['maxWorkDayCount']->setAttribute('value', '');
        $widgets['maxWorkDayWeight']->setAttribute('value', '');
        $widgets['maxWorkDayStatus']->setAttribute('value', '');


        // 最少允许连续工作几个周末

         $widgets['minWorkWeekendCount']->setAttribute('value', '');
        $widgets['minWorkWeekendStatus']->setAttribute('value', '');




        
        return $widgets;
    }

    private function getPersonalInfoValidators() {
        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();
        $validators = array(
            'txtEmployeeId' => new sfValidatorString(array('required' => false)),
            // 夜班后夜休

            
            'nightAfterNightLeisureWeight' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),
            
            'nightAfterNightLeisureStatus' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true),
                    array('required' => 'Last Name Empty!', 'max_length' => 'Last Name Length exceeded 30 characters')),
       
            
            'nightAfterNightLeisureShiftSelect' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->_getShiftTypeList()))),

 
            // 排班尽量平均分配
            'averageAssignment' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true),
                    array('required' => 'First Name Empty!', 'max_length' => 'First Name Length exceeded 30 characters')),
       
            'averageAssignmentShiftSelect' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->_getShiftTypeList()))),
            
            'averageAssignmentWeight' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),
            
            'averageAssignmentStatus' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true),
                    array('required' => 'Last Name Empty!', 'max_length' => 'Last Name Length exceeded 30 characters')),



            // 班次只分配给男性

            'shiftdOnlyforManShiftSelect' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->_getShiftTypeList()))),

            'shiftdOnlyforManWeight' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),

            'shiftdOnlyforManStatus' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true),
                    array('required' => 'Last Name Empty!', 'max_length' => 'Last Name Length exceeded 30 characters')),


            // 每周公休分配

            'freeTwoDaysSelect' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true),
                    array('required' => 'Last Name Empty!', 'max_length' => 'Last Name Length exceeded 30 characters')),

            'freeTwoDaysWeight' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),

            'freeTwoDaysStatus' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true),
                    array('required' => 'Last Name Empty!', 'max_length' => 'Last Name Length exceeded 30 characters')),

            //该班次分配后间隔后再分配
            
            'assignmentAfterIntervalShiftSelect' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->_getShiftTypeList()))),

            'assignmentAfterIntervalEmployee' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),

            'assignmentAfterIntervalWeight' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),

            'assignmentAfterIntervalStatus' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true),
                    array('required' => 'Last Name Empty!', 'max_length' => 'Last Name Length exceeded 30 characters')),

            //该班次分配后持续分配
            'assignmentAfterShiftSelect' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->_getShiftTypeList()))),

            'assignmentAfterShiftDays' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true),
                    array('required' => 'Last Name Empty!', 'max_length' => 'Last Name Length exceeded 30 characters')),

            'assignmentAfterShiftWeight' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),

            'assignmentAfterShiftStatus' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true),
                    array('required' => 'Last Name Empty!', 'max_length' => 'Last Name Length exceeded 30 characters')),

            //最多允许连续工作几个周末
             'maxWeekendShiftWeight' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),

              'allowWeekendShift' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),
              'maxWeekendShiftStatus' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true),
                    array('required' => 'Last Name Empty!', 'max_length' => 'Last Name Length exceeded 30 characters')),

        //周六日连休
        'restOnStaAndSunWeight' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),
        'restOnStaAndSunOn' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true),
                    array('required' => 'Last Name Empty!', 'max_length' => 'Last Name Length exceeded 30 characters')),
        'restOnStaAndSunStatus' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true),
                    array('required' => 'Last Name Empty!', 'max_length' => 'Last Name Length exceeded 30 characters')),

        // 该班次不分配给某员工

        'shiftNotForEmployeeShiftSelect' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->_getShiftTypeList()))),

        'shiftNotForEmployee' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->_getEmployeeList()))),

        'shiftNotForEmployeeWeight' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),

        'shiftNotForEmployeeStatus' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true),
                    array('required' => 'Last Name Empty!', 'max_length' => 'Last Name Length exceeded 30 characters')),

        // 该班次分配给某员工

        'shiftForEmployeeShiftSelect' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->_getShiftTypeList()))),

        'shiftForEmployee' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->_getEmployeeList()))),

        'shiftForEmployeeWeight' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),

        'shiftForEmployeeStatus' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true),
                    array('required' => 'Last Name Empty!', 'max_length' => 'Last Name Length exceeded 30 characters')),



        // 不希望此班次后继续班次

        'startShiftSelect' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->_getShiftTypeList()))),
        'nextShiftSelect' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->_getShiftTypeList()))),

        'restAfterOneShiftWeight' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),

        'restAfterOneShiftStatus' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true),
                    array('required' => 'Last Name Empty!', 'max_length' => 'Last Name Length exceeded 30 characters')),

      

        // 周六工作在周二或周四安排调休
        'restOnTuOrTuesWeight' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),

        'restOnTuOrTuesStatus' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true),
                    array('required' => 'Last Name Empty!', 'max_length' => 'Last Name Length exceeded 30 characters')),

        //连续周末分配同一班次

        'continuWeekOneShiftWeight' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),

        'continuWeekOneShiftStatus' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true),
                    array('required' => 'Last Name Empty!', 'max_length' => 'Last Name Length exceeded 30 characters')),


        //最少任务天数分配

      
            'minWorkDayCount' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),

            'minWorkDayWeight' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),

              'minWorkDayStatus' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true),
                    array('required' => 'Last Name Empty!', 'max_length' => 'Last Name Length exceeded 30 characters')),


              //最多任务天数分配

      
            'maxWorkDayCount' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),

            'maxWorkDayWeight' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),
            
            'maxWorkDayStatus' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true),
                    array('required' => 'Last Name Empty!', 'max_length' => 'Last Name Length exceeded 30 characters')),



        // 最少允许连续工作几个周末

            'minWorkWeekendCount' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),
            
            'minWorkWeekendStatus' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true),
                    array('required' => 'Last Name Empty!', 'max_length' => 'Last Name Length exceeded 30 characters')),


        );

        return $validators;
    }

 

}

