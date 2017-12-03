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
        $jopDocuments=$this->getShiftService()->getJobDepartmentList();
       
       $jopdocument_list=array();
        foreach($jopDocuments as $key=>$jopDocument){
            $jopdocument_list[$jopDocument['id']]=$jopDocument['name'];
        }


        return $jopdocument_list;
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
        $shiftRotary->setFirstDocument($shift_rotary['firDocument']);
        $shiftRotary->setSecondDocument($shift_rotary['secDocument']);   
        $shiftRotary->setthirdDocument($shift_rotary['thirDocument']);      
   
        
        $this->shiftService->saveShiftRotary($shiftRotary); 

   
        return $message;    

    }

}

