<?php
/*
    设置约束类型
*/
class ContranctTypeForm extends sfForm {
    
    private $shiftService;
    public $fullName;
    private $widgets = array();
    public $contranctTypeList;

     private function getShiftService() {

        if(is_null($this->ShiftService)) {
            $this->shiftService = new ShiftService();
        }
        return $this->shiftService;
    }

    public function configure() {
       
        $shift_id = $this->getOption('shift_id');
        $scheduleID = $this->getOption('scheduleID');

        $this->contranctTypeList = $this->getShiftService()->getContranctTypes($scheduleID);
   
        $languageWidgets = $this->getMaxStaffWidgets();
        $languageValidators = $this->getMaxStaffValidators();

        $widgets = $languageWidgets;
        $validators = $languageValidators;
 
        $this->setWidgets($widgets);
        $this->setValidators($validators);

        $this->setDefault('shiftId', $shift_id);
        $this->setDefault('scheduleID', $scheduleID);
        
        $this->widgetSchema->setNameFormat('contrancttype[%s]');
        $this->getWidgetSchema()->setLabels($this->getFormLabels());
       
    }
    
    /**
     *
     * @return \sfWidgetFormInputHidden 
     */
    private function getMaxStaffWidgets() {
        $availableShiftTypeList = $this->_getShiftTypeList();
        $status = array('1' => __('Enabled'), '0' => __('Disabled'));
        $widgets = array();
        $widgets['id'] = new sfWidgetFormInputHidden();
        $widgets['scheduleID'] = new sfWidgetFormInputHidden();
        $widgets['name'] = new sfWidgetFormInputText();//最大值
        $widgets['status']= new sfWidgetFormSelect(array('choices' => $status));//状态
        return $widgets;
    }
    
    private function getMaxStaffValidators() {
        $availableShiftTypeList = $this->_getShiftTypeList();

        $validators = array(
            'id' => new sfValidatorString(array('required' => false)),
            'scheduleID' => new sfValidatorNumber(array('required' => false, 'min'=> 0)),
            'name' => new sfValidatorString(array('required' => true)),
            'status' => new sfValidatorString(array('required' => true)),
        );
        return $validators;
    }

    public function getLangTypeDesc($langType) {
        $langTypeDesc = "";
        if (isset($this->langTypeList[$langType])) {
            $langTypeDesc = $this->langTypeList[$langType];
        }    
        return $langTypeDesc;
    }
    
    public function getCompetencyDesc($competency) {
        $competencyDesc = "";
        if (isset($this->competencyList[$competency])) {
            $competencyDesc = $this->competencyList[$competency];
        }
        return $competencyDesc;
    }

    private function _getShiftTypeList() {

        $scheduleID = $this->getOption('scheduleID');

        $shiftTypeService = new ShiftTypeService();
        $shiftTypeList = $shiftTypeService->getShiftTypes($scheduleID);

        $list = array("" => "-- " . __('Select') . " --");

        foreach($shiftTypeList as $shiftType) {
            $list[$shiftType->getId()] = $shiftType->getName();
        }


        return $list;
    }
    
    /**
     *
     * @return array
     */
    protected function getFormLabels() {
        $required = '<em> *</em>';
        $labels = array(
            'minximum' => __('最小员工个数') . $required,
            'applyTo' => __('应用到') . $required,
        );
        return $labels;
    }
}
?>