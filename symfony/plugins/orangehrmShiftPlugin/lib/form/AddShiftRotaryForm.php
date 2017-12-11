<?php

class AddShiftRotaryForm extends BaseForm {
    private $shiftService;
    public $shiftTypeService;

    private function getShiftService() {

        if(is_null($this->shiftService)) {
            $this->shiftService = new ShiftService();
        }
        return $this->shiftService;
    }
  

    public function configure() {


        $widgets = $this->getRotaryWidgets();
        $validators = $this->getRotaryValidators();
   
        $this->setWidgets($widgets);
        $this->setValidators($validators);


        $this->widgetSchema->setNameFormat('rotary[%s]');
    }

    /*
     * Tis fuction will return the widgets of the form
     */
    public function getRotaryWidgets(){
        $widgets = array();

        $status = array('1' => __('Enabled'), '0' => __('Disabled'));
        $skills= $this->getJobDocument();

        //creating widgets
        $widgets['RotaryID'] = new sfWidgetFormInputHidden();

       
        $widgets['name'] =new sfWidgetFormInputText();

        $widgets['calFromDate'] = new ohrmWidgetDatePicker(array(), array('id' => 'calFromDate'));

        $widgets['calToDate'] = new ohrmWidgetDatePicker(array(), array('id' => 'dependent_dateOfBirth'));

        //第一个部门
        $widgets['firDocument'] = new sfWidgetFormSelect(array('choices' => $skills));
        $widgets['secDocument'] = new sfWidgetFormSelect(array('choices' => $skills));
        $widgets['thirDocument'] = new sfWidgetFormSelect(array('choices' => $skills));


        return $widgets;
    }
    
    /*
     * Tis fuction will return the form validators
     */
    public function getRotaryValidators(){

        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();

        $skills= $this->getJobDocument();
        $validators = array(

        'RotaryID' => new sfValidatorNumber(array('required' => false, 'min' => 1)),
      
        'name' => new sfValidatorString(array('required' => true)),


        'calFromDate' => new ohrmDateValidator(       
            array('date_format' => $inputDatePattern, 'required' => false),
            array('invalid'=>'Date format should be'. $inputDatePattern)),
        
        'calToDate' =>  new ohrmDateValidator(       
            array('date_format' => $inputDatePattern, 'required' => false),
            array('invalid'=>'Date format should be'. $inputDatePattern)),

        'firDocument' => new sfValidatorChoice(array('choices' => array_keys($skills))),
        'secDocument' => new sfValidatorChoice(array('choices' => array_keys($skills))),
        'thirDocument' => new sfValidatorChoice(array('choices' => array_keys($skills)))
    );
        return $validators;
    }

    public function getJobDocument(){
       $locationList = array('' => '-- ' . __('Select') . ' --');

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

    public function save() {

        $rotaryID=(int)$this->getValue('RotaryID');
        $shift_rotary['name']=$this->getValue('name');
        $shift_rotary['date_from']=$this->getValue('calFromDate');
        $shift_rotary['date_to']=$this->getValue('calToDate');
        $shift_rotary['firDocument']=$this->getValue('firDocument');
        $shift_rotary['secDocument']=$this->getValue('secDocument');
        $shift_rotary['thirDocument']=$this->getValue('thirDocument');
      

        if (empty($rotaryID)) {
            $shiftRotary = new WorkShiftRotary();
            $message = array('messageType' => 'success', 'message' => __(TopLevelMessages::SAVE_SUCCESS));
        } else {
            
            $shiftRotary = $this->getShiftTypeService()->getShiftTypeById($RotaryID);
            $message = array('messageType' => 'success', 'message' => __(TopLevelMessages::UPDATE_SUCCESS));
        }


        $shiftRotary->setName($shift_rotary['name']);

        $shiftRotary->setDateFrom($shift_rotary['date_from']);    
      
        $shiftRotary->setDateTo($shift_rotary['date_to']);  
        $shiftRotary->setFirstDepartment($shift_rotary['firDocument']);
        $shiftRotary->setSecondDepartment($shift_rotary['secDocument']);   
        $shiftRotary->setthirdDepartment($shift_rotary['thirDocument']);      
   
        $this->getShiftService()->saveShiftRotary($shiftRotary); 

        $id=$shiftRotary->getId();
        if(!empty($id)){
            $shift_rotary['id']=$id;
        }
        $this->setRoatary($shift_rotary);

        return $message;    

    }

     /*
        具体操作步骤
        第一，分别罗列出三个部门所有员工；
        第二，获取部门顺序，例如，部门顺序是A部门－>B部门－>C部门
        第三，foreachA部门，取出A部门经验最大的一个员工，加入到B部门；
        第四，foreachB部门，取出B部门经验最大的一个员工（不包涵新加入的A部门员工），加入到C部门；
        第五，foreachC部门，取出C部门经验最大的一个员工（不包涵新加入的B部门员工），加入到A部门；
    */

    public function setRoatary($shift_rotary){


        $firDoc=$shift_rotary['firDocument'];
        $secDoc=$shift_rotary['secDocument'];
        $thirDoc=$shift_rotary['thirDocument'];
      
        //获取部门员工信息；
        $document_employee=$this->getShiftService()->getEmoloyeeLocation();

        //获得每个部门下的员工
        foreach ($document_employee as $key => $employee) {
            $empLocation[$employee['locationId']][]=$employee['Employee'];
        }
       
  

        //第一个部门所有员工的详细信息
        $firsDocEmp=$empLocation[$firDoc];

        // var_dump($firsDocEmp);exit; 
        
        //第一个部门中工龄最大的员工empNumber
        $firsDocEmp = array_column($firsDocEmp, 'working_years', 'empNumber');
        $firMax= array_search(max($firsDocEmp), $firsDocEmp);  

         
        //第二个部门员工
        $secDocEmp=$empLocation[$secDoc];
        //第三个部门员工
        $thirDocEmp=$empLocation[$thirDoc];

        foreach ($firsDocEmp as $key_fir => $fir_emp) {
            //获取工龄最大的员工；
            //获取轮换时间：一个月轮换
            //原始部门ID；
            //轮转部门ID：
            $rotaryEmp['rotary_id']="";
            $this->saveRotaryEmployee()


           
        }

        
    }

    public function saveRotaryEmployee($rotaryEmp){
        $rotary_emp=new WorkRotaryEmplayee();

        $rotary_emp->setRotaryId($rotaryEmp['rotary_id']);
        $rotary_emp->setRotaryId($rotaryEmp['emp_number']);
        $rotary_emp->setDateFrom($rotaryEmp['date_from']);
        $rotary_emp->setDateTo($rotaryEmp['date_to']);
        $rotary_emp->setOrangeDepartment($rotaryEmp['orange_department_id']);
        $rotary_emp->settRotaryDepartment($rotaryEmp['rotary_department_id']);

    }

}

