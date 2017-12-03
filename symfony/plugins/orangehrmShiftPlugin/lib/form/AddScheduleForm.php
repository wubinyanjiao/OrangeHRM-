<?php


class AddScheduleForm extends sfForm {

    private $workShiftService;
    private $shiftDateService;


    public function getScheduleService() {
        if (is_null($this->workShiftService)) {
            $this->workShiftService = new ScheduleService();
            $this->workShiftService->setScheduleDao(new ScheduleDao());
        }
        return $this->workShiftService;
    }

    public function getShiftDateService() {
        if (is_null($this->shiftDateService)) {
            $this->shiftDateService = new ShiftDateService();
            $this->shiftDateService->setShiftDateDao(new ShiftDateDao());
        }
        return $this->shiftDateService;
    }
    
 
   

    public function configure() {
         

        $status = array('0' => __('Enabled'), '1' => __('Disabled'));

        $setStatusChoices =array( 'no'=> '不复制','one'=> '一周', 'two'=> '两周','three'=> '三周','four'=> '四周');
        $this->setWidgets(array(
            'id' => new sfWidgetFormInputHidden(),
            'name' => new sfWidgetFormInputText(array(), array("maxlength" => 52)),
            
            'dateOfBirth' =>new ohrmWidgetDatePicker(array(), array('id' => 'dependent_dateOfBirth')),//设置初始日期
            'copy' =>new sfWidgetFormChoice(array('expanded' => true, 'choices'  => $setStatusChoices)),
            'copydays'=> new sfWidgetFormInputHidden(),
             'status' => new sfWidgetFormSelect(array('choices' => $status), array("class" => "formInputText")), 
        ));

        $this->setValidators(array(
            'id' => new sfValidatorNumber(array('required' => false)),
            'name' => new sfValidatorString(array('required' => true, 'max_length' => 52)),
            'copydays' => new sfValidatorString(array('required' => false, 'trim'=>true)), 
            'copy' => new sfValidatorString(array('required' => false, 'trim'=>true, 'max_length'=>100)),
            'status' => new sfValidatorString(array('required' => false)),
          
            'dateOfBirth' =>  new ohrmDateValidator(array('date_format'=>$inputDatePattern, 'required'=>false),
                              array('invalid'=>'Date format should be '. $inputDatePattern)),
         
        ));

        $requiredMarker = '&nbsp;<em>*</em>';

        $labels = array(
            'name' => __('Shift Name') . $requiredMarker,
           
        );

        $this->getWidgetSchema()->setNameFormat('workShift[%s]');
        $this->getWidgetSchema()->setLabels($labels);
    }

    public function save() {

        try{

            $id = $this->getValue('id');
        
            if (empty($id)) {    
                $workShift = new WorkSchedule();   
            } else {//修改计划，id不为空
                $workSchedule = $this->getScheduleService()->getScheduleById($id);
            }

            $copydays=$this->getValue('copydays');
          
            $create_at=date('Y-m-d', time());
          
            $workShift->setName($this->getValue('name'));
            $workShift->setCopyType($this->getValue('copy'));
            $workShift->setStatus($this->getValue('status'));
            $workShift->setShiftDate($this->getValue('dateOfBirth'));
            $workShift->setCreateAt($create_at);
      
            $this->getScheduleService()->saveSchedule($workShift);

            $schedule_id=$workShift->getId();
         
            if(!empty($copydays)){
                $this->_saveShifDates($schedule_id, $copydays);
                return $schedule_id;
            }
            
        }catch(ErrorException $e) {
            echo $e->getMessage();
        }
    }

    private function _saveShifDates($id,$copydays){
           
        $copydays=explode(',', $copydays);
        foreach($copydays as $key=>$value){ 
            $temp=explode(':', $value);
            $copyday_format=$temp[0];

            $workShiftDate = new WorkShiftDate;
            $workShiftDate->setShiftDate($copyday_format);
            $workShiftDate->setScheduleId($id);

            $this->getShiftDateService()->saveShiftDate($workShiftDate);
        }
        
        return $id;
    }

    public function getScheduleListAsJson() {

        $jsonArray = array();        
        $workShiftList = $this->getScheduleService()->getScheduleList();
        return json_encode($jsonArray);
    }

}


?>