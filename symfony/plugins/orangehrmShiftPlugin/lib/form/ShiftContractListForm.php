<?php

class ShiftContractListForm extends sfForm {
    
    private $scheduleService;
    private $shiftContranctList;
    private $langTypeList;
    private $widgets = array();
    public $empLanguageList;


    private function getScheduleService() {

        if(is_null($this->scheduleService)) {
            $this->scheduleService = new ScheduleService();
        }
        return $this->scheduleService;
    }
     private function getShiftService() {

        if(is_null($this->shiftService)) {
            $this->shiftService = new ShiftService();
        }
        return $this->shiftService;
    }


    public function configure() {
       
        $shift_id = $this->getOption('shift_id');
        $scheduleID = $this->getOption('scheduleID');
        $this->scheduleID=$scheduleID;
        
        $this->empContractList = $this->getScheduleService()->getShiftContranctsBySchedule(WorkSchedule::STATE_ACTIVE);

    


        
        $languageWidgets = $this->getMaxStaffWidgets();
        $languageValidators = $this->getMaxStaffValidators();

        $widgets = $languageWidgets;
        $validators = $languageValidators;

        $this->setWidgets($widgets);
        $this->setValidators($validators);

        $this->setDefault('shiftId', $shift_id);
        $this->setDefault('scheduleID', $scheduleID);
        
        $this->widgetSchema->setNameFormat('maxstaff[%s]');
        $this->getWidgetSchema()->setLabels($this->getFormLabels());

    }
    
    /**
     *
     * @return \sfWidgetFormInputHidden 
     */
    private function getMaxStaffWidgets() {

        $i18nHelper = sfContext::getInstance()->getI18N();

        $availableShiftTypeList = $this->_getShiftTypeList();

        $availableContranctList = $this->_getContractTypeList();

        
        $status = array('1' => __('Enabled'), '0' => __('Disabled'));
        $widgets = array();
        $widgets['shiftId'] = new sfWidgetFormInputHidden();
        $widgets['scheduleID'] = new sfWidgetFormInputHidden();
        $widgets['contranct_list'] = new sfWidgetFormSelect(array('choices' => $availableContranctList));
       
        $widgets['maximum'] = new sfWidgetFormInputText();//最大值
        // $widgets['applyTo'] = new sfWidgetFormInputText();//最大值
        $widgets['applyTo'] = new sfWidgetFormSelect(array('choices' => $availableShiftTypeList));
        $widgets['status']= new sfWidgetFormSelect(array('choices' => $status));//状态
        return $widgets;
    }
    
    private function getMaxStaffValidators() {
        $availableShiftTypeList = $this->_getShiftTypeList();
        $availableContranctList = $this->_getContractTypeList();
        $i18nHelper = sfContext::getInstance()->getI18N();


        $validators = array(
            'shiftId' => new sfValidatorString(array('required' => false)),
            'scheduleID' => new sfValidatorNumber(array('required' => false, 'min'=> 0)),
            'contranct_list' => new sfValidatorChoice(array('choices' => array_keys($availableContranctList))),
            'maximum' => new sfValidatorString(array('required' => true)),
            'applyTo' => new sfValidatorString(array('required' => true)),
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

        $list = array("0" => "仅作用当前班");

        foreach($shiftTypeList as $shiftType) {
            $list[$shiftType->getId()] = '计划中所有的'.$shiftType->getName();
        }


        return $list;
    }

    private function _getContractTypeList() {


        $contranctTypeList = $this->getShiftService()->getContranctTypes($this->scheduleID,1);
     
        $list = array("" => "-- " . __('Select') . " --");

        foreach($contranctTypeList as $contranctType) {
            $list[$contranctType->getId()] = $contranctType->getName();
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
            'contranct_list' => __('约束条件') . $required,
            'maximum' => __('值') . $required,
            'applyTo' => __('应用到') . $required,
        );
        return $labels;
    }
}
?>