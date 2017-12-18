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


        //获取约束条件
    /*    $data=$this->getShiftService()->saveFile('rotaryContranct');
        echo '<pre>';var_dump($data);exit;*/



        $firDoc=$shift_rotary['firDocument'];
        $secDoc=$shift_rotary['secDocument'];
        $thirDoc=$shift_rotary['thirDocument'];


        $date_from=$shift_rotary['date_from'];
        $date_to=$shift_rotary['date_to'];

      
        //获取期间月个数
        $date_from = strtotime($date_from); // 自动为00:00:00 时分秒 两个时间之间的年和月份  
        $date_to = strtotime($date_to);  
   
        $monarr = array();  
        $monarr[] = date('Y-m-d',$date_from); // 当前月;  
        while( ($date_from = strtotime('+1 month', $date_from)) <= $date_to){  
              $monarr[] = date('Y-m-d',$date_from); // 取得递增月;   
        }  

      
        //获取部门员工信息；
        $document_employee=$this->getShiftService()->getEmoloyeeLocation();

        //获得每个部门下的员工
        foreach ($document_employee as $key => $employee) {
            $empLocation[$employee['locationId']][$key]['empNumber']=$employee['Employee']['empNumber'];
            $empLocation[$employee['locationId']][$key]['firstName']=$employee['Employee']['firstName'];
            $empLocation[$employee['locationId']][$key]['working_years']=$employee['Employee']['working_years'];
            $empLocation[$employee['locationId']][$key]['orange_department']=$employee['locationId'];
        }

        //第一个部门所有员工的按照工作年限排序
        $firsDocEmp=$empLocation[$firDoc];

        
        foreach ($firsDocEmp as $key => $val) {  
          $tmp[$key] = $val['working_years'];  
        }  
        array_multisort($tmp,SORT_ASC,$firsDocEmp);

        //第二个部门员工
        $secDocEmp=$empLocation[$secDoc];
        foreach ($secDocEmp as $key => $val) {  
          $tmp_sec[$key] = $val['working_years'];  
        }  
        array_multisort($tmp_sec,SORT_ASC,$secDocEmp);

        //第三个部门员工
        $thirDocEmp=$empLocation[$thirDoc];
        foreach ($thirDocEmp as $key => $val) {  
          $tmp_thir[$key] = $val['working_years'];  
        }  
        array_multisort($tmp_thir,SORT_ASC,$thirDocEmp);
    
        //循环每个月
        $moth_count=count($monarr);

        $tmp_fir=array();
        $tmp_sec=array();
        $tmp_thir=array();

        $rotary_emp=array();

         
        for($i=0;$i<$moth_count;$i++){

            //循环，如果第一个部门存在部门满三年的员工，则把其中年限最长的加入到第二个部门
            if(null !== $firsDocEmp[$i]){
                // var_dump($thirDocEmp[$i]['orange_department']);exit;
                if(null == $secDocEmp[$i] && null !== $thirDocEmp[$i]){//第一个部门和第三个部门有复合轮转的员工
                    $tmp_thir[$i]=$firsDocEmp[$i];
                    $tmp_thir[$i]['date_from']=$monarr[$i];
                    $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                    

                    $tmp_fir[$i]=$thirDocEmp[$i];
                    $tmp_fir[$i]['date_from']=$monarr[$i];
                    $tmp_fir[$i]['rotary_department']=$firsDocEmp[$i]['orange_department'];

                    unset($firsDocEmp[$i]);
                    unset($thirDocEmp[$i]);

                    break;

                }else if(null !== $secDocEmp[$i] && null == $thirDocEmp[$i]){//第一个部门和第二个部门有复合轮转的员工
                    
                    $tmp_sec[$i]=$firsDocEmp[$i];
                    $tmp_sec[$i]['date_from']=$monarr[$i];
                    $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                    

                    $tmp_fir[$i]=$secDocEmp[$i];
                    $tmp_fir[$i]['date_from']=$monarr[$i];
                    $tmp_fir[$i]['rotary_department']=$firsDocEmp[$i]['orange_department'];


                    unset($firsDocEmp[$i]);
                    unset($secDocEmp[$i]);

                    break;
                } else if(null !== $secDocEmp[$i] && null !== $thirDocEmp[$i]){// 如果三个部门都有符合轮转条件的员工
                    $tmp_sec[$i]=$firsDocEmp[$i];
                    $tmp_sec[$i]['date_from']=$monarr[$i];
                    $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                    

                    $tmp_thir[$i]=$secDocEmp[$i];
                    $tmp_thir[$i]['date_from']=$monarr[$i];

                    $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                    

                    $tmp_fir[$i]=$thirDocEmp[$i];
                    $tmp_fir[$i]['date_from']=$monarr[$i];
                    $tmp_fir[$i]['rotary_department']=$firsDocEmp[$i]['orange_department'];

                  
                    unset($firsDocEmp[$i]);
                    unset($secDocEmp[$i]);
                    unset($thirDocEmp[$i]);

                    // break;

                }

            }else{//如果第一个部门没有符合轮转条件的员工，第二和第三个部门参与轮转
                if(null == $secDocEmp[$i] || null == $thirDocEmp[$i]){//如果第二个部门也没有复合条件的轮转的人
                    break;
                }else{//第二个部门和第三个部门有复合轮转的员工

                    $tmp_thir[$i]=$secDocEmp[$i];

                    $tmp_thir[$i]['date_from']=$monarr[$i];
                    $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                    

                    $tmp_sec[$i]=$thirDocEmp[$i];
                    $tmp_sec[$i]['date_from']=$monarr[$i];
                    $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];

                    unset($secDocEmp[$i]);
                    unset($thirDocEmp[$i]);

                }
            }
        }
    

        $rotary_employees=array_merge($tmp_fir,$tmp_sec,$tmp_thir);
        
        foreach ($rotary_employees as $key => $value) {
            $rotaryEmp['rotary_id']=$shift_rotary['id'];
            $rotaryEmp['empNumber']=$value['empNumber'];
            $rotaryEmp['dateFrom']=$value['date_from'];
            $rotaryEmp['dateTo']=$value['date_from'];
            $rotaryEmp['orangeDepartment']=$value['orange_department'];
            $rotaryEmp['rotaryDepartment']=$value['rotary_department'];
            $rotary_emp=new WorkRotaryEmplayee();
            $this->saveRotaryEmployee($rotaryEmp);
        }

    }

    public function saveRotaryEmployee($rotaryEmp){

        $rotary_emp=new WorkRotaryEmplayee();

      
        $rotary_emp->setRotaryId($rotaryEmp['rotary_id']);

        $rotary_emp->setEmpNumber($rotaryEmp['empNumber']);

        $rotary_emp->setDateFrom($rotaryEmp['dateFrom']);
        $rotary_emp->setDateTo($rotaryEmp['dateTo']);

        $rotary_emp->setOrangeDepartment($rotaryEmp['orangeDepartment']);
        $rotary_emp->setRotaryDepartment($rotaryEmp['rotaryDepartment']);
  
        $this->getShiftService()->saveRotaryResult($rotary_emp);
    

    }

}

