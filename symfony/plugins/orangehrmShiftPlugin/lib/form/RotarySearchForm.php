
<?php

class RotarySearchForm extends BaseForm {

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

        $this->widgetSchema->setNameFormat('rotartContract[%s]');
    }

    public function getReadOnlyWidgetNames() {
        return $this->readOnlyWidgetNames;
    }



     public function _getShiftTypeList(){
       $locationList = array('' => '-- ' . __('选择部门') . ' --');

        $locationService = new LocationService();
        $locations = $locationService->getLocationList();        

        $accessibleLocations = UserRoleManagerFactory::getUserRoleManager()->getAccessibleEntityIds('Location');

    
        foreach ($locations as $location) {
            if (in_array($location->id, $accessibleLocations)) {
                $locationList[$location->id] = $location->name;
            }
        }
      
        return($locationList);
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
            
             //中级职称平均分配

            'averge_mid_level_count' => new sfWidgetFormInputText(),
            'averge_mid_level_weight' => new sfWidgetFormInputText(),
            'averge_mid_level_status' => new sfWidgetFormSelect(array('choices' => $status)),


            //男士平均分配

            'averge_man_count' => new sfWidgetFormInputText(),
            'averge_man_weight' => new sfWidgetFormInputText(),
            'averge_man_status' => new sfWidgetFormSelect(array('choices' => $status)),


             //研究生平均分配

            'averge_graduate_count' => new sfWidgetFormInputText(),
            'averge_graduate_weight' => new sfWidgetFormInputText(),
            'averge_graduate_status' => new sfWidgetFormSelect(array('choices' => $status)),


            //中级职称满多长时间轮转

            'midlevel_year_count' => new sfWidgetFormInputText(),
            'midlevel_year_weight' => new sfWidgetFormInputText(),
            'midlevel_year_status' => new sfWidgetFormSelect(array('choices' => $status)),



             //轮转部门顺序

            'firt_rotary_document' => new sfWidgetFormSelect(array('choices' => $this->_getShiftTypeList())),
            'sec_rotary_document' => new sfWidgetFormSelect(array('choices' => $this->_getShiftTypeList())),
            'third_rotary_document' => new sfWidgetFormSelect(array('choices' => $this->_getShiftTypeList())),
            'document_rotary_Weight' => new sfWidgetFormInputText(),
            'document_rotary_status' => new sfWidgetFormSelect(array('choices' => $status)),


            //门诊满几年轮转

            'rotary_limit_year' => new sfWidgetFormInputText(),
            'rotary_limit_time_weight' => new sfWidgetFormInputText(),
            'rotary_limit_time_status' => new sfWidgetFormSelect(array('choices' => $status)),


            //年龄满足至少多少不轮转到门诊

            'min_age_rotary' => new sfWidgetFormInputText(),
            'min_age_rotary_weight' => new sfWidgetFormInputText(),
            'min_age_rotary_status' => new sfWidgetFormSelect(array('choices' => $status)),



            //组长不参与轮转

            // 'leader_no_rotary_year' => new sfWidgetFormInputText(),
            'leader_no_rotary_weight' => new sfWidgetFormInputText(),
            'leader_no_rotary_status' => new sfWidgetFormSelect(array('choices' => $status)),
      

        );

   		//中级职称平均分配

        $widgets['averge_mid_level_count']->setAttribute('value', '');
        $widgets['averge_mid_level_weight']->setAttribute('value', '');
        $widgets['averge_mid_level_status']->setAttribute('value', '');


         //男士平均分配

        $widgets['averge_man_count']->setAttribute('value', '');
        $widgets['averge_man_weight']->setAttribute('value', '');
        $widgets['averge_man_status']->setAttribute('value', '');

          //研究生平均分配

        $widgets['averge_graduate_count']->setAttribute('value', '');
        $widgets['averge_graduate_weight']->setAttribute('value', '');
        $widgets['averge_graduate_status']->setAttribute('value', '');


         //中级职称满多长时间轮转

        $widgets['midlevel_year_count']->setAttribute('value', '');
        $widgets['midlevel_year_weight']->setAttribute('value', '');
        $widgets['midlevel_year_status']->setAttribute('value', '');

		 //轮转部门顺序

        $widgets['firt_rotary_document']->setAttribute('value', '');
        $widgets['sec_rotary_document']->setAttribute('value','');
        $widgets['third_rotary_document']->setAttribute('value', '');
        $widgets['document_rotary_status']->setAttribute('value', '');


         //门诊满几年轮转

        $widgets['rotary_limit_year']->setAttribute('value', '');
        $widgets['rotary_limit_time_weight']->setAttribute('value', '');
        $widgets['rotary_limit_time_status']->setAttribute('value', '');


        //年龄满足至少多少不轮转到门诊

        $widgets['min_age_rotary']->setAttribute('value', '');
        $widgets['min_age_rotary_weight']->setAttribute('value', '');
        $widgets['min_age_rotary_status']->setAttribute('value', '');

         //组长不参与轮转

        // $widgets['leader_no_rotary_year']->setAttribute('value', '');
        $widgets['leader_no_rotary_weight']->setAttribute('value', '');
        $widgets['leader_no_rotary_status']->setAttribute('value', '');







        
        return $widgets;
    }

    private function getPersonalInfoValidators() {
        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();
        $validators = array(
          



          	//中级职称平均分配
      
            'averge_mid_level_count' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),

            'averge_mid_level_weight' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),

            'averge_mid_level_status' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true),
                    array('required' => 'Last Name Empty!', 'max_length' => 'Last Name Length exceeded 30 characters')),


             //男士平均分配

            'averge_man_count' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),

            'averge_man_weight' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),
            
            'averge_man_status' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true),
                    array('required' => 'Last Name Empty!', 'max_length' => 'Last Name Length exceeded 30 characters')),



              //研究生平均分配
	     
	        'averge_graduate_count' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),

            'averge_graduate_weight' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),
            
            'averge_graduate_status' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true),
                    array('required' => 'Last Name Empty!', 'max_length' => 'Last Name Length exceeded 30 characters')),


             //中级职称满多长时间轮转
	     
	        'midlevel_year_count' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),

            'midlevel_year_weight' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),
            
            'midlevel_year_status' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true),
                    array('required' => 'Last Name Empty!', 'max_length' => 'Last Name Length exceeded 30 characters')),


             //轮转部门顺序
   
	        'firt_rotary_document' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->_getShiftTypeList()))),
	        'sec_rotary_document' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->_getShiftTypeList()))),

	        'third_rotary_document' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),

	        'document_rotary_status' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true),
	                    array('required' => 'Last Name Empty!', 'max_length' => 'Last Name Length exceeded 30 characters')),


	          //门诊满几年轮转

        	 'rotary_limit_year' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),

            'rotary_limit_time_weight' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),
            
            'rotary_limit_time_status' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true),
                    array('required' => 'Last Name Empty!', 'max_length' => 'Last Name Length exceeded 30 characters')),



        	//年龄满足至少多少不轮转到门诊

        	 'min_age_rotary' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),

            'min_age_rotary_weight' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),
            
            'min_age_rotary_status' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true),
                    array('required' => 'Last Name Empty!', 'max_length' => 'Last Name Length exceeded 30 characters')),

         	//组长不参与轮转

        	 // 'leader_no_rotary_year' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),

            'leader_no_rotary_weight' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),
            
            'leader_no_rotary_status' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true),
                    array('required' => 'Last Name Empty!', 'max_length' => 'Last Name Length exceeded 30 characters')),




        );

        return $validators;
    }

 

}

