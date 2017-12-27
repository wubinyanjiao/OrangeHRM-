<?php

class UpdateShiftResultForm extends BaseForm {
    private $shiftService;
    public $shiftTypeService;

    private function getShiftService() {

        if(is_null($this->ShiftService)) {
            $this->ShiftService = new ShiftService();
        }
        return $this->ShiftService;
    }
  

    public function configure() {

        $scheduleID = $this->getOption('scheduleID');

        // var_dump($scheduleID );exit;
    
        $widgets = array('scheduleID' => new sfWidgetFormInputHidden(array(), array('value' => $scheduleID)));
        $validators = array('scheduleID' => new sfValidatorString(array('required' => false)));
        

        $emergencyContactWidgets = $this->getEmergencyContactWidgets();
        $emergencyContactValidators = $this->getEmergencyContactValidators();

        $widgets = array_merge($widgets, $emergencyContactWidgets);
        $validators = array_merge($validators, $emergencyContactValidators);
    

        $this->setWidgets($widgets);
        $this->setValidators($validators);

        $this->widgetSchema->setNameFormat('emgcontacts[%s]');
    }

    /*
     * Tis fuction will return the widgets of the form
     */
    public function getEmergencyContactWidgets(){

   

       
        $widgets = array();

        $employ_list= $this->getEmployList();

        // var_dump( $employ_list);exit;


        $widgets['shiftResultID'] = new sfWidgetFormInputHidden();
       
      
        $widgets['employee'] = new sfWidgetFormSelect(array('choices' => $employ_list), array("class" => "formInputText"));
     
   

        return $widgets;
    }
    
    /*
     * Tis fuction will return the form validators
     */
    public function getEmergencyContactValidators(){

        $skills= $this->getEmployList();
        $validators = array(
            'shiftResultID' => new sfValidatorNumber(array('required' => false, 'min' => 1)),

            'employee' => new sfValidatorString(array('required' => false)),
  
        );
        
        return $validators;
    }

    public function getEmployList(){
        $scheduleID = $this->getOption('scheduleID');

        $assignment_list=$this->getShiftService()->getRosterResult($scheduleID);
        $employ_list=array_column($assignment_list,'emp_number');
        $employ_list=array_unique($employ_list);


        $employeeList=$this->getShiftService()->getEmployeeList();
        $employeeList = array_column($employeeList, NULL, 'empNumber');

        foreach ($employeeList as $key => $value) {
            
            foreach ($employ_list as $k => $v) {
                if($key==$v){
                    $employNewArr[$v]=$value['firstName'];
                }
            }
        }
        return $employNewArr;
        
        
    }

    public function save() {

        

        $scheduleID=$this->getValue('scheduleID');

        $shiftTypeID=(int)$this->getValue('shiftTypeID');
        $shift_type['shiftTypeName']=$this->getValue('shiftTypeName');
        $shift_type['start_time']=$this->getValue('start_time');
        $shift_type['end_time']=$this->getValue('end_time');
        $shift_type['status']=$this->getValue('status');
        $shift_type['scheduleID']=$scheduleID;

       
        if (empty($shiftTypeID)) {
            $shiftType = new WorkShiftType();
            $message = array('scheduleID'=>$scheduleID,'messageType' => 'success', 'message' => __(TopLevelMessages::SAVE_SUCCESS));
        } else {
            
            $shiftType = $this->getShiftTypeService()->getShiftTypeById($shiftTypeID);
            $message = array('scheduleID'=>$scheduleID,'messageType' => 'success', 'message' => __(TopLevelMessages::UPDATE_SUCCESS));
        }

        $create_at=date("Y-m-d",time());
        $shiftType->setName($shift_type['shiftTypeName']);
        $shiftType->setStartTime($shift_type['start_time']);    
        $shiftType->setEndTime($shift_type['end_time']);  
        $shiftType->setScheduleId($shift_type['scheduleID']);   
        $shiftType->setCreateAt($create_at); 

        $shiftType->setStatus($shift_type['status']);

        $this->getShiftTypeService()->saveShiftType($shiftType); 

        $shiftTypeID=$shiftType->getId();
       
        $skill_List=$this->getSkillList();


        //保存所需职位；首先判断数据库中是否存在该职位；
        //1,从数据库中取出该类型下的所有岗位；
        $newList = array();
        $require_skill=$skill_List;

        $skills=$this->getShiftTypeService()->getSkillsByType($shiftTypeID);
        $skillsArray=$this->getShiftTypeService()->getSkillsArrayByType($shiftTypeID);


       
        if(empty($skillsArray)){
            $newList=$require_skill;
        }else{
            //列举出前台页面提交的数据；
            //循环从数据库中所取的排班类型对应的岗位；
            foreach ($skills as $key => $skill) {
                $id=$skill->getSkillId();

                //若有数据库中的某一个岗位不再前台页面提交的数据中，则从数据库中删除这个岗位；如果存在的话，则保留；
                if(!in_array($id, $require_skill)){
                    $skill->delete();
                }else{
                    $idList[] = $id;
                }
            }
            
            //最后对比前天页面和数据库中更新的数量，如果是新增加的，则调用下面方法；
            $skillList = array_diff($idList,$require_skill);

            
            foreach ($skillList as $skill) {
                    $newList[] = $skill;
            }
        }


        $this->_saveShiftTypeToSkill($shiftType->getId(),$newList,$scheduleID);
   
        return $message;    

    }

    public function _saveShiftTypeToSkill($shiftTypeID,$skills,$scheduleID){
        // echo'<pre>';
        if(!empty($shiftTypeID)&&!empty($skills)){
            foreach ($skills as $key => $skill) {
                $workTypeSkill=new WorkTypeSkill();


                $workTypeSkill->setShiftTypeId($shiftTypeID);
                $workTypeSkill->setSkillId($skill);
            
                $workTypeSkill->setScheduleId($scheduleID);
                $workTypeSkill->save();
            }
        }
    }
}

