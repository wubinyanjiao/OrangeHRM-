<?php

class AddShiftRotary2Form extends BaseForm {
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


    public function getRotaryManWomen($emptyList,$contranct=null,$con_val){
        
        foreach ($emptyList as $key => $value) {
            if($value[$contranct]==$con_val){
                $manList[]=$value;
            }
        }
        return $manList;


    }


    public function getRotaryNoGraduate($emptyList,$contranct=null,$con_val){
        // var_dump($contranct);exit;
        foreach ($emptyList as $key => $value) {
            if($value[$contranct]!=$con_val){
                $noGraduList[]=$value;
            }
        }

        // var_dump($noGraduList);exit;

        return $noGraduList;


    }

    public function getMaxEmpNum($empList,$empIndex){
        asort($empList);
        $rel=array();
        foreach($empList as $k=>$v){
            $rel[]=$k;
         }

         $max_num=end($rel);
         foreach($empIndex as $k=>$val){
            if($val['empNumber']==$max_num){
                $em_key=$k;
            }
            
        }
        return $em_key;
    }


    /**
     * @author injection(injection.mail@gmail.com)
    * @var date1日期1
    * @var date2 日期2
    * @var tags 年月日之间的分隔符标记,默认为'-' 
    * @return 相差的月份数量
    * @example:
    $date1 = "2003-08-11";
    $date2 = "2008-11-06";
    $monthNum = getMonthNum( $date1 , $date2 );
    echo $monthNum;
    */
    public function getMonthNum( $date1, $date2, $tags='-' ){
     $date1 = explode($tags,$date1);
     $date2 = explode($tags,$date2);
     return abs($date1[0] - $date2[0]) * 12 + abs($date1[1] - $date2[1]);
    }

    /* 
    *function：计算两个日期相隔多少年，多少月，多少天 
    *param string $date1[格式如：2011-11-5] 
    *param string $date2[格式如：2012-12-01] 
    *return array array('年','月','日'); 
    */  
    public function diffDate($date1,$date2){  
        if(strtotime($date1)>strtotime($date2)){  
            $tmp=$date2;  
            $date2=$date1;  
            $date1=$tmp;  
        }  
        list($Y1,$m1,$d1)=explode('-',$date1);  
        list($Y2,$m2,$d2)=explode('-',$date2);  
        $Y=$Y2-$Y1;  
        $m=$m2-$m1;  
        $d=$d2-$d1;  
        if($d<0){  
            $d+=(int)date('t',strtotime("-1 month $date2"));  
            $m--;  
        }  
        if($m<0){  
            $m+=12;  
            $y--;  
        }  
        return array('year'=>$Y,'month'=>$m,'day'=>$d);  
    } 
    /**
     * 求两个日期之间相差的天数
     * (针对1970年1月1日之后，求之前可以采用泰勒公式)
     * @param string $day1
     * @param string $day2
     * @return number
     */
    public function diffBetweenTwoDays ($day1, $day2)
    {
      $second1 = strtotime($day1);
      $second2 = strtotime($day2);
        
      if ($second1 < $second2) {
        $tmp = $second2;
        $second2 = $second1;
        $second1 = $tmp;
      }
      return ($second1 - $second2) / 86400;
    }


    public function birthday($birthday){ 

        $age = strtotime($birthday); 
        if($age === false){ 
          return false; 
         } 

        list($y1,$m1,$d1) = explode("-",date("Y-m-d",$age)); 

        $now = strtotime("now"); 

        list($y2,$m2,$d2) = explode("-",date("Y-m-d",$now)); 

        $age = $y2 - $y1; 

        if((int)($m2.$d2) < (int)($m1.$d1)) 

        $age -= 1; 

        return $age; 
    } 



    public function setRoatary($shift_rotary){

        

        $firDoc=$shift_rotary['firDocument'];
        $secDoc=$shift_rotary['secDocument'];
        $thirDoc=$shift_rotary['thirDocument'];

  
        if($data['document_rotary']['status']==1&&$data['document_rotary']['status']!==0){
            $firDoc=$data['document_rotary']['firt_document'];
            $secDoc=$data['document_rotary']['sec_document'];
            $thirDoc=$data['document_rotary']['third_document'];
        }else{
            $firDoc=$shift_rotary['firDocument'];
            $secDoc=$shift_rotary['secDocument'];
            $thirDoc=$shift_rotary['thirDocument'];
        }

      

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

        $data=$this->getShiftService()->saveFile('rotaryContranct');
      
        //获取部门员工信息；
        $document_employee=$this->getShiftService()->getEmoloyeeLocation();

        //获取组长列表
        $leader_list=$this->getShiftService()->getLeaderList();
        $leader_list=array_column($leader_list, 'leader_id');

        //获得每个部门下的员工
        foreach ($document_employee as $key => $employee) {
       
            $date_now=date('Y-m-d',time());

            //在部门已经呆了多三个月
            $location_moth=$this->getMonthNum($date_now,$employee['location_time']);

             // $location_moth=11;

            $location_days=$this->diffBetweenTwoDays($date_now,$employee['location_time']);

            $age=$this->birthday($employee['emp_birthday']);

            
            //如果设置了组长不参与轮转
            if($data['leader_no_rotary']['status'] == 1){
                if(in_array($employee['empNumber'], $leader_list)){
                    unset($document_employee[$key]);
                }else{
                    $empLocation[$employee['EmpLocations'][0]['locationId']][$key]['empNumber']=$employee['empNumber'];
                    $empLocation[$employee['EmpLocations'][0]['locationId']][$key]['firstName']=$employee['firstName'];
                    $empLocation[$employee['EmpLocations'][0]['locationId']][$key]['location_days']=$location_days;
                    $empLocation[$employee['EmpLocations'][0]['locationId']][$key]['location_moth']=$location_moth;
                    $empLocation[$employee['EmpLocations'][0]['locationId']][$key]['orange_department']=$employee['EmpLocations'][0]['locationId'];
                    $empLocation[$employee['EmpLocations'][0]['locationId']][$key]['gender']=$employee['emp_gender'];
                    $empLocation[$employee['EmpLocations'][0]['locationId']][$key]['graduate']=$employee['EmployeeEducation'][0]['educationId'];
                    $empLocation[$employee['EmpLocations'][0]['locationId']][$key]['job_title']=$employee['job_title_code'];
                    $empLocation[$employee['EmpLocations'][0]['locationId']][$key]['age']=$age;
                }
            }else{
                $empLocation[$employee['EmpLocations'][0]['locationId']][$key]['empNumber']=$employee['empNumber'];
                $empLocation[$employee['EmpLocations'][0]['locationId']][$key]['firstName']=$employee['firstName'];
                $empLocation[$employee['EmpLocations'][0]['locationId']][$key]['location_days']=$location_days;
                $empLocation[$employee['EmpLocations'][0]['locationId']][$key]['location_moth']=$location_moth;
                $empLocation[$employee['EmpLocations'][0]['locationId']][$key]['orange_department']=$employee['EmpLocations'][0]['locationId'];
                $empLocation[$employee['EmpLocations'][0]['locationId']][$key]['gender']=$employee['emp_gender'];
                $empLocation[$employee['EmpLocations'][0]['locationId']][$key]['graduate']=$employee['EmployeeEducation'][0]['educationId'];
                $empLocation[$employee['EmpLocations'][0]['locationId']][$key]['job_title']=$employee['job_title_code'];
                $empLocation[$employee['EmpLocations'][0]['locationId']][$key]['age']=$age;
            }

        }

    


        $deparment_order['firDoc']=$empLocation[$firDoc];
         
        $deparment_order['secDoc']=$empLocation[$secDoc];
        $deparment_order['thirDoc']=$empLocation[$thirDoc];

        //第一个部门所有员工的按照部门工作年限排序
        

        foreach ($deparment_order['firDoc'] as $key => $val) { 
          $tmp[$key] = $val['location_days'];

        }  
        array_multisort($tmp,SORT_DESC,$deparment_order['firDoc']);




        //第二个部门员工
 
        foreach ($deparment_order['secDoc'] as $key => $val) {  
          $tmp_sec[$key] = $val['location_days'];  
        }  
        array_multisort($tmp_sec,SORT_ASC,$deparment_order['secDoc']);

        //第三个部门员工
      
        foreach ($deparment_order['thirDoc'] as $key => $val) {  
          $tmp_thir[$key] = $val['location_days'];  
        }  
        array_multisort($tmp_thir,SORT_ASC,$deparment_order['thirDoc']);



        $dep_ord['firDoc']=$firDoc;
        $dep_ord['secDoc']=$secDoc;
        $dep_ord['thirDoc']=$thirDoc;



        //中级职称满多少年参与轮转
        if($data['midlevel_year']['status']==1){

            foreach ($deparment_order as $key_ord => $val_ord) {
                foreach ($val_ord as $k_ord => $v_ord) {
                    if($v_ord['job_title']==3 && $v_ord['location_moth']<$data['midlevel_year']['count']){
                        unset($deparment_order[$key_ord][$k_ord]);
                    }
                }
            }
        }

        // var_dump();exit;
        // 门诊满几年轮转,首先判断第几个部门是门诊
        if($data['rotary_limit']['status']==1){

                foreach ($dep_ord as $kor => $valor) {
                 
                    if($valor==1){//如果是门诊
                        
                        foreach ($deparment_order[$kor] as $ko=> $valo) {
                            if($valo['location_moth']<$data['rotary_limit']['count']){
                                unset($deparment_order[$kor][$ko]);
                            }
                        }
                    }        
                }

        }

        //未满几年不能进轮转到门诊部门,
        //首先，将部门顺序罗列出来，首先判断门诊排第几，然后判断门诊前面的部门，如果门诊排第一个，则获取最后一个部门的部门员工ID
        //如果门诊排第二个，则获取第一个部门ID
        //如果门诊排第三个，则获取第二个部门ID

        if($data['min_age_rotary']['status']==1){

                foreach ($dep_ord as $kor => $valor) {
                 
                    if($valor==1){//如果是门诊

                        if($kor=='firDoc'){//如果门诊是第一个部门，则最后一个部门的年龄不满足的删除;
                            foreach ($deparment_order['thirDoc'] as $ko=> $valo) {
                                if($valo['age']<$data['min_age_rotary']['count']){
                                    unset($deparment_order['thirDoc'][$ko]);
                                }
                            }
                        }else if($kor=='secDoc'){
                            foreach ($deparment_order['firDoc'] as $ko=> $valo) {
                                if($valo['age']<$data['min_age_rotary']['count']){
                                    unset($deparment_order['firDoc'][$ko]);
                                }
                            }
                        }else{
                            foreach ($deparment_order['secDoc'] as $ko=> $valo) {
                                if($valo['age']<$data['min_age_rotary']['count']){
                                    unset($deparment_order['secDoc'][$ko]);
                                }
                            }
                        }
                        
                    }        
                }
        }




   
        $firsDocEmp=$deparment_order['firDoc'];
        $secDocEmp=$deparment_order['secDoc'];
        $thirDocEmp=$deparment_order['thirDoc'];




 
    
        //循环每个月
        $moth_count=count($monarr);

        $tmp_fir=array();
        $tmp_sec=array();
        $tmp_thir=array();

        $rotary_emp=array();

        //三个部门轮转，当一个部门不满足条件时，终止轮转
        /*for($i=0;$i<$moth_count;$i++){
            if(null !== $firsDocEmp[$i]){

                if(null == $secDocEmp[$i] && null !== $thirDocEmp[$i]){//第一个部门和第三个部门有符合轮转的员工

                    //第一个部门，如果这个员工是男性，执行下面判断
                    if($firsDocEmp[$i]['gender']==1){
                        //该部门员工中男士个数＝新加入的男士个数＋剩余的员工个数
                         $man_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',1);
                         $man_temp_fir=$this->getRotaryManWomen($tmp_fir,'gender',1);
                         $total_man_fir=count($man_orang_fir)+count($man_temp_fir)-1;
                        //如果轮班后男士数量小于平均值，则选择其中经验最多的女性参与轮转
                        if($total_man_fir<$data['averge_man']['count']){
                            //获取部门中经验最多的女员工
                            $women_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',2);
                            $women_orang_key_fir=array_column($women_orang_fir,'location_days','empNumber');
                             //获取经验最多的女员工emnumber
                            $em_key_fir=$this->getMaxEmpNum($women_orang_key_fir,$firsDocEmp);

                            $tmp_thir[$i]=$firsDocEmp[$em_key_fir];
                            $tmp_thir[$i]['date_from']=$monarr[$i];
                            $tmp_thir[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                            $rotary_department=$firsDocEmp[$em_key_fir]['orange_department'];

                            unset($firsDocEmp[$em_key_fir]);
                            
                        }else{//如果轮班后男士数大于或者等于平均值，将该员工轮转到第二个部门
                            $tmp_thir[$i]=$firsDocEmp[$i];
                            $tmp_thir[$i]['date_from']=$monarr[$i];
                            $tmp_thir[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                            $rotary_department=$firsDocEmp[$i]['orange_department'];
                            unset($firsDocEmp[$i]);
                        }
                       
                    }else{//第一个部门，如果这个员工是不是男性，不需要执行判断，直接讲该员工复制到第二个部门
                        $tmp_thir[$i]=$firsDocEmp[$i];
                        $tmp_thir[$i]['date_from']=$monarr[$i];
                        $tmp_thir[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                        $rotary_department=$firsDocEmp[$i]['orange_department'];
                        unset($firsDocEmp[$i]);
                    }


                    //判断第三个部门该员工是否是男性
                    if($thirDocEmp[$i]['gender']==1){

                        $man_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',1);
                        $man_temp_thir=$this->getRotaryManWomen($tmp_thir,'gender',1);
                        $total_man_thir=count($man_orang_thir)+count($man_temp_thir)-1;

                          //第三个部门轮转
                        if($total_man_thir<$data['averge_man']['count']){
                
                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                            $women_orang_key_thir=array_column($women_orang_thir,'location_days','empNumber');
                           
                            $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);

                            $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                            $tmp_fir[$i]['date_from']=$monarr[$i];
                            $tmp_fir[$i]['rotary_department']=$rotary_department;
                            unset($thirDocEmp[$em_key_thir]);
                        }else{
                            $tmp_fir[$i]=$thirDocEmp[$i];
                            $tmp_fir[$i]['date_from']=$monarr[$i];
                            $tmp_fir[$i]['rotary_department']=$rotary_department;
                            unset($thirDocEmp[$i]);
                        }

                    }else{

                        $tmp_fir[$i]=$thirDocEmp[$i];
                        $tmp_fir[$i]['date_from']=$monarr[$i];
                        $tmp_fir[$i]['rotary_department']=$rotary_department;
                        unset($thirDocEmp[$i]);
                    }

                    break;

                }else if(null !== $secDocEmp[$i] && null == $thirDocEmp[$i]){//第一个部门和第二个部门有复合轮转的员工                                                    
                    
                    //第一个部门，如果这个员工是男性，执行下面判断
                    if($firsDocEmp[$i]['gender']==1){
                        //该部门员工中男士个数＝新加入的男士个数＋剩余的员工个数
                         $man_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',1);
                         $man_temp_fir=$this->getRotaryManWomen($tmp_fir,'gender',1);
                         $total_man_fir=count($man_orang_fir)+count($man_temp_fir)-1;
                        //如果轮班后男士数量小于平均值，则选择其中经验最多的女性参与轮转
                        if($total_man_fir<$data['averge_man']['count']){
                            //获取部门中经验最多的女员工
                            $women_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',2);
                            $women_orang_key_fir=array_column($women_orang_fir,'location_days','empNumber');
                             //获取经验最多的女员工emnumber
                            $em_key_fir=$this->getMaxEmpNum($women_orang_key_fir,$firsDocEmp);

                            $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                            $tmp_sec[$i]['date_from']=$monarr[$i];
                            $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                            $rotary_department=$firsDocEmp[$em_key_fir]['orange_department'];

                            unset($firsDocEmp[$em_key_fir]);
                            
                        }else{//如果轮班后男士数大于或者等于平均值，将该员工轮转到第二个部门
                            $tmp_sec[$i]=$firsDocEmp[$i];
                            $tmp_sec[$i]['date_from']=$monarr[$i];
                            $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                            $rotary_department=$firsDocEmp[$i]['orange_department'];
                            unset($firsDocEmp[$i]);
                        }
                       
                    }else{//第一个部门，如果这个员工是不是男性，不需要执行判断，直接讲该员工复制到第二个部门
                        $tmp_sec[$i]=$firsDocEmp[$i];
                        $tmp_sec[$i]['date_from']=$monarr[$i];
                        $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                        $rotary_department=$firsDocEmp[$i]['orange_department'];
                        unset($firsDocEmp[$i]);

                    }


                    //判断第二个部门该员工是否是男性
                    if($secDocEmp[$i]['gender']==1){

                        $man_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',1);
                        $man_temp_sec=$this->getRotaryManWomen($tmp_sec,'gender',1);
                        $total_man_sec=count($man_orang_sec)+count($man_temp_sec)-1;

                         //第二个部门轮转
                        if($total_man_sec<$data['averge_man']['count']){
                            //先判断男生是否平均，如果不平均

                            $women_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',2);
                            $women_orang_key_sec=array_column($women_orang_sec,'location_days','empNumber');
                           
                            $em_key_sec=$this->getMaxEmpNum($women_orang_key_sec,$secDocEmp);

                            $tmp_fir[$i]=$secDocEmp[$em_key_sec];
                            $tmp_fir[$i]['date_from']=$monarr[$i];
                            $tmp_fir[$i]['rotary_department']=$rotary_department;
                            unset($secDocEmp[$em_key_sec]);
                        }else{
                            $tmp_fir[$i]=$secDocEmp[$i];
                            $tmp_fir[$i]['date_from']=$monarr[$i];
                            $tmp_fir[$i]['rotary_department']=$rotary_department;
                            unset($secDocEmp[$i]);
                        }

                    }else{

                        $tmp_fir[$i]=$secDocEmp[$i];
                        $tmp_fir[$i]['date_from']=$monarr[$i];
                        $tmp_fir[$i]['rotary_department']=$rotary_department;
                        unset($secDocEmp[$i]);

                    }

                    break;
                } else if(null !== $secDocEmp[$i] && null !== $thirDocEmp[$i]){
                //如果三个部门都有符合轮转条件的员工
                    
                        //第一个部门，如果这个员工是男性，执行下面判断
                        if($firsDocEmp[$i]['gender']==1){
                            //该部门员工中男士个数＝新加入的男士个数＋剩余的员工个数
                             $man_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',1);
                             $man_temp_fir=$this->getRotaryManWomen($tmp_fir,'gender',1);
                             $total_man_fir=count($man_orang_fir)+count($man_temp_fir)-1;
                            //如果轮班后男士数量小于平均值，则选择其中经验最多的女性参与轮转
                            if($total_man_fir<$data['averge_man']['count']){
                                //获取部门中经验最多的女员工
                                $women_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',2);
                                $women_orang_key_fir=array_column($women_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($women_orang_key_fir,$firsDocEmp);

                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                                
                            }else{//如果轮班后男士数大于或者等于平均值，将该员工轮转到第二个部门
                                $tmp_sec[$i]=$firsDocEmp[$i];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                                $rotary_department=$firsDocEmp[$i]['orange_department'];
                                unset($firsDocEmp[$i]);
                            }
                           
                        }else{//第一个部门，如果这个员工是不是男性，不需要执行判断，直接讲该员工复制到第二个部门
                            $tmp_sec[$i]=$firsDocEmp[$i];
                            $tmp_sec[$i]['date_from']=$monarr[$i];
                            $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                            $rotary_department_one=$firsDocEmp[$i]['orange_department'];
                            unset($firsDocEmp[$i]);

                        }
                        

                        //判断第二个部门该员工是否是男性
                        if($secDocEmp[$i]['gender']==1){

                            $man_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',1);
                            $man_temp_sec=$this->getRotaryManWomen($tmp_sec,'gender',1);
                            $total_man_sec=count($man_orang_sec)+count($man_temp_sec)-1;

                             //第二个部门轮转
                            if($total_man_sec<$data['averge_man']['count']){
                                //先判断男生是否平均，如果不平均

                                $women_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',2);
                                $women_orang_key_sec=array_column($women_orang_sec,'location_days','empNumber');
                               
                                $em_key_sec=$this->getMaxEmpNum($women_orang_key_sec,$secDocEmp);

                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']= $rotary_department_one;
                                $rotary_department_sec=$secDocEmp[$em_key_sec]['orange_department'];
                                unset($secDocEmp[$em_key_sec]);
                            }else{
                                $tmp_thir[$i]=$secDocEmp[$i];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']= $rotary_department_one;
                                $rotary_department_sec=$secDocEmp[$i]['orange_department'];
                                unset($secDocEmp[$i]);
                            }

                        }else{

                            $tmp_thir[$i]=$secDocEmp[$i];
                            $tmp_thir[$i]['date_from']=$monarr[$i];
                            $tmp_thir[$i]['rotary_department']= $rotary_department_one;
                            $rotary_department_sec=$secDocEmp[$i]['orange_department'];
                            unset($secDocEmp[$i]);

                        }

                      
                        //判断第三个部门该员工是否是男性
                        if($thirDocEmp[$i]['gender']==1){

                            $man_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',1);
                            $man_temp_thir=$this->getRotaryManWomen($tmp_thir,'gender',1);
                            $total_man_thir=count($man_orang_thir)+count($man_temp_thir)-1;

                              //第三个部门轮转
                            if($total_man_thir<$data['averge_man']['count']){
                    
                                $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                                $women_orang_key_thir=array_column($women_orang_thir,'location_days','empNumber');
                               
                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);

                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_sec;
                                unset($thirDocEmp[$em_key_thir]);
                            }else{
                                $tmp_fir[$i]=$thirDocEmp[$i];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_sec;
                                unset($thirDocEmp[$i]);
                            }

                        }else{

                            $tmp_fir[$i]=$thirDocEmp[$i];
                            $tmp_fir[$i]['date_from']=$monarr[$i];
                            $tmp_fir[$i]['rotary_department']=$rotary_department_sec;
                            unset($thirDocEmp[$i]);

                        }
                        

                }

            }else{//如果第一个部门没有符合轮转条件的员工，第二和第三个部门参与轮转

                if(null == $secDocEmp[$i] || null == $thirDocEmp[$i]){//如果第二个部门也没有复合条件的轮转的人
                    break;
                }else{//第二个部门和第三个部门有复合轮转的员工


                    //判断第二个部门该员工是否是男性
                        if($secDocEmp[$i]['gender']==1){

                            $man_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',1);
                            $man_temp_sec=$this->getRotaryManWomen($tmp_sec,'gender',1);
                            $total_man_sec=count($man_orang_sec)+count($man_temp_sec)-1;

                             //第二个部门轮转
                            if($total_man_sec<$data['averge_man']['count']){
                                //先判断男生是否平均，如果不平均

                                $women_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',2);
                                $women_orang_key_sec=array_column($women_orang_sec,'location_days','empNumber');
                               
                                $em_key_sec=$this->getMaxEmpNum($women_orang_key_sec,$secDocEmp);

                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                                $rotary_department=$secDocEmp[$em_key_sec]['orange_department'];
                                unset($secDocEmp[$em_key_sec]);
                            }else{
                                $tmp_thir[$i]=$secDocEmp[$i];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                                $rotary_department=$secDocEmp[$i]['orange_department'];
                                unset($secDocEmp[$i]);
                            }

                        }else{

                            $tmp_thir[$i]=$secDocEmp[$i];
                            $tmp_thir[$i]['date_from']=$monarr[$i];
                            $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                            $rotary_department=$secDocEmp[$i]['orange_department'];
                            unset($secDocEmp[$i]);

                        }

                        //判断第三个部门该员工是否是男性
                        if($thirDocEmp[$i]['gender']==1){

                            $man_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',1);
                            $man_temp_thir=$this->getRotaryManWomen($tmp_thir,'gender',1);
                            $total_man_thir=count($man_orang_thir)+count($man_temp_thir)-1;

                              //第三个部门轮转
                            if($total_man_thir<$data['averge_man']['count']){
                    
                                $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                                $women_orang_key_thir=array_column($women_orang_thir,'location_days','empNumber');
                               
                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);

                                $tmp_sec[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']= $rotary_department;
                                unset($thirDocEmp[$em_key_thir]);
                            }else{
                                $tmp_sec[$i]=$thirDocEmp[$i];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']= $rotary_department;
                                unset($thirDocEmp[$i]);
                            }

                        }else{

                            $tmp_sec[$i]=$thirDocEmp[$i];
                            $tmp_sec[$i]['date_from']=$monarr[$i];
                            $tmp_sec[$i]['rotary_department']= $rotary_department;
                            unset($thirDocEmp[$i]);

                        }

                }
            }



            exit;

            /*if($data['document_rotary']['status']==1){//设置部门轮转顺序

                if($data['averge_man']['status']==1){//男士平均分配
                    if($data['averge_graduate']['status']==1){//研究生平均分配
                        if($data['averge_graduate']['status']==1){//中级职称满多长时间轮转
                            if($data['rotary_limit']['status']==1){//门诊满几年轮转
                                if($data['min_age_rotary']['status']==1){//年龄满足至少多少不轮转到门诊
                                    if($data['leader_no_rotary']['status']==1){//组长不参与轮转
                                
                                        
                                            





                                    }else{//no组长不参与轮转

                                    }
                                }else{//no年龄满足至少多少不轮转到门诊

                                }

                            }else{//no门诊满几年轮转

                            }


                        }else{//no中级职称满多长时间轮转

                        }

                    }else{//研究生不平均分配

                    }
                }else{//no男士平均分配

                }
            }else{//no设置部门轮转顺序
               
                if($data['averge_man']['status']==1){//男士平均分配

                    if($data['averge_graduate']['status']==1){//男士平均分配,研究生平均分配
                        if($data['averge_graduate']['status']==1){//男士平均分配,研究生平均分配,中级职称满多长时间轮转
                            if($data['rotary_limit']['status']==1){//男士平均分配,研究生平均分配,中级职称满多长时间轮转,门诊满几年轮转
                                if($data['min_age_rotary']['status']==1){//年龄满足至少多少不轮转到门诊
                                    if($data['leader_no_rotary']['status']==1){
                                    //男士平均分配,研究生平均分配,中级职称满多长时间轮转,组长不参与轮转

                                        

                                        for($i=0;$i<$moth_count;$i++){
                                            //循环，如果第一个部门存在部门满三年的员工，则把其中年限最长的加入到第二个部门
                                            if(null !== $firsDocEmp[$i]){
                                                if(null == $secDocEmp[$i] && null !== $thirDocEmp[$i]){
                                                    //第一个部门和第三个部门有复合轮转的员工
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






                                    }else{//no组长不参与轮转

                                    }
                                }else{//no年龄满足至少多少不轮转到门诊

                                }

                            }else{//no门诊满几年轮转

                            }


                        }else{//no中级职称满多长时间轮转

                        }

                    }else{//研究生不平均分配

                    }
                }else{//no男士平均分配
                    if($data['averge_graduate']['status']==1){//研究生平均分配
                        if($data['averge_graduate']['status']==1){//中级职称满多长时间轮转
                            if($data['rotary_limit']['status']==1){//门诊满几年轮转
                                if($data['min_age_rotary']['status']==1){//年龄满足至少多少不轮转到门诊
                                    if($data['leader_no_rotary']['status']==1){//组长不参与轮转

                                    }else{//no组长不参与轮转

                                    }
                                }else{//no年龄满足至少多少不轮转到门诊

                                }

                            }else{//no门诊满几年轮转

                            }


                        }else{//no中级职称满多长时间轮转

                        }

                    }else{//研究生不平均分配
                        if($data['averge_graduate']['status']==1){//中级职称满多长时间轮转
                            if($data['rotary_limit']['status']==1){//门诊满几年轮转
                                if($data['min_age_rotary']['status']==1){//年龄满足至少多少不轮转到门诊
                                    if($data['leader_no_rotary']['status']==1){//组长不参与轮转

                                    }else{//no组长不参与轮转

                                    }
                                }else{//no年龄满足至少多少不轮转到门诊

                                }

                            }else{//no门诊满几年轮转

                            }


                        }else{//no中级职称满多长时间轮转
                            if($data['rotary_limit']['status']==1){//门诊满几年轮转
                                if($data['min_age_rotary']['status']==1){//年龄满足至少多少不轮转到门诊
                                    if($data['leader_no_rotary']['status']==1){//组长不参与轮转

                                    }else{//no组长不参与轮转

                                    }
                                }else{//no年龄满足至少多少不轮转到门诊

                                }

                            }else{//no门诊满几年轮转
                                if($data['min_age_rotary']['status']==1){//年龄满足至少多少不轮转到门诊
                                    if($data['leader_no_rotary']['status']==1){//组长不参与轮转

                                    }else{//no组长不参与轮转

                                    }
                                }else{//no年龄满足至少多少不轮转到门诊
                                    if($data['leader_no_rotary']['status']==1){//组长不参与轮转

                                    }else{//no组长不参与轮转

                                    }
                                }
                            }
                        }
                    }
                }
            }
        }*/

       for($i=0;$i<$moth_count;$i++){
            if($data['averge_man']['status']==1&&$data['averge_graduate']['status']==1&&$data['averge_mid_level']['status']==1){

                if(null !== $firsDocEmp[$i] && null !== $secDocEmp[$i] && null !== $thirDocEmp[$i]){    
                    //该部门员工中男士个数＝新加入的男士个数＋剩余的员工个数
                    $man_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',1);
                    $man_temp_fir=$this->getRotaryManWomen($tmp_fir,'gender',1);
                    $total_man_fir=count($man_orang_fir)+count($man_temp_fir)-1;
                    $compare_man_fir=$total_man_fir-$data['averge_man']['count'];

                    //该部门员工中研究生个数＝新加入的研究生个数＋剩余的研究生个数
                    $graduate_orang_fir=$this->getRotaryManWomen($firsDocEmp,'graduate',3);
                    $graduate_temp_fir=$this->getRotaryManWomen($tmp_fir,'graduate',3);
                    $total_graduate_fir=count($graduate_orang_fir)+count($graduate_temp_fir)-1;
                    $compare_gradaute_fir=$total_graduate_fir-$data['averge_graduate']['count'];

                    //该部门员工中中级职称个数＝新加入的中级生个数＋剩余的中级生个数
                    $job_orang_fir=$this->getRotaryManWomen($firsDocEmp,'job_title',3);
                    $job_temp_fir=$this->getRotaryManWomen($tmp_fir,'job_title',3);
                    $total_job_fir=count($job_orang_fir)+count($job_temp_fir)-1;
                    $compare_job_fir=$total_job_fir-$data['averge_mid_level']['count'];

                    //第一个部门，如果这个员工是男性，并且是研究生和中级。执行下面判断
                    if($firsDocEmp[$i]['gender']==1 && $firsDocEmp[$i]['graduate']==3 && $firsDocEmp[$i]['job_title']==3){//男，研究生，中层

                        //轮班后男士数量和研究生小于平均值，则选择其中经验最多的女性且其中非研究生参与轮转
                        if($compare_man_fir<0 && $compare_gradaute_fir<0 && $compare_job_fir<0){//只能选择经验最多的非研究生，并且是女员工,并且是中级员工

                            //获取部门中经验最多的女员工,
                            $women_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',2);
                         
                            //经验最的的女性中的非研究生列表
                            $women_nogradute_orang_fir=$this->getRotaryNoGraduate($women_orang_fir,'graduate',3);
                            //获取非中中层
                            $women_nogradute_nolevel_orang_fir=$this->getRotaryNoGraduate($women_nogradute_orang_fir,'job_title',3);



                            if(null==$women_nogradute_nolevel_orang_fir){
                                break;
                            }else{
                                $women_orang_key_fir=array_column($women_nogradute_nolevel_orang_fir,'location_days','empNumber');
                           
                                $em_key_fir=$this->getMaxEmpNum($women_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }        
                            
                        }else if($compare_man_fir<0 && $compare_gradaute_fir>-1 && $compare_job_fir<0){//只女非中层员工都可以轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',2);
                            $nojobtitle_orang_fir=$this->getRotaryNoGraduate($women_orang_fir,'job_title',3);
                            if(null==$nojobtitle_orang_fir){
                                break;
                            }else{
                                $women_orang_key_fir=array_column($nojobtitle_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($women_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                        
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }

                        }else if($compare_man_fir<0 && $compare_gradaute_fir>-1 && $compare_job_fir>-1){//只有女参与轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',2);
                            
                            if(null==$women_orang_fir){
                                break;
                            }else{
                                $women_orang_key_fir=array_column($women_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($women_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                        
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }

                        }else if($compare_man_fir<0 && $compare_gradaute_fir<0 && $compare_job_fir>-1){//只有女非研究生参与轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',2);
                            //再获取其中不适研究生的
                            $nojobtitle_orang_fir=$this->getRotaryNoGraduate($women_orang_fir,'graduate',3);
                            
                            if(null==$nojobtitle_orang_fir){
                                break;
                            }else{
                                $nogradute_orang_key_fir=array_column($nojobtitle_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($nogradute_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }

                        }else if($compare_man_fir>-1 && $compare_gradaute_fir<0 && $compare_job_fir<0){//只要不是研究生，且不是中层，都可参与轮转

                            //获取非研究生的的列表
                            $nogradute_orang_fir=$this->getRotaryNoGraduate($firsDocEmp,'graduate',3);
                            $nojobtitle_orang_fir=$this->getRotaryNoGraduate($nogradute_orang_fir,'job_title',3);
                            
                            if(null==$nojobtitle_orang_fir){
                                break;
                            }else{
                                $nogradute_orang_key_fir=array_column($nojobtitle_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($nogradute_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }

                        }else if($compare_man_fir>-1 && $compare_gradaute_fir<0 && $compare_job_fir>-1){//只要不是中层，都可参与轮转

                            //获取非中层的的列表
                          
                            $nojobtitle_orang_fir=$this->getRotaryNoGraduate($firsDocEmp,'job_title',3);
                            
                            if(null==$nojobtitle_orang_fir){
                                break;
                            }else{
                                $nogradute_orang_key_fir=array_column($nojobtitle_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($nogradute_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }

                        }else if($compare_man_fir>-1 && $compare_gradaute_fir>-1 && $compare_job_fir<0){//只有非中层参与轮转
                            //获取部门中经验最多的女员工,
                           $nojobtitle_orang_fir=$this->getRotaryNoGraduate($firsDocEmp,'job_title',3);
                            if(null==$nojobtitle_orang_fir){
                                break;
                            }else{
                                $women_orang_key_fir=array_column($nojobtitle_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($women_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                        
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }

                        }else {//如果轮班后男士数和研究生数大于或者等于平均值，将该员工轮转到第二个部门
                            $tmp_sec[$i]=$firsDocEmp[$i];
                            $tmp_sec[$i]['date_from']=$monarr[$i];
                            $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                            $rotary_department_one=$firsDocEmp[$i]['orange_department'];
                            unset($firsDocEmp[$i]);
                        }
                       
                    }else if($firsDocEmp[$i]['gender']==1 && $firsDocEmp[$i]['graduate']==3 && $firsDocEmp[$i]['job_title']!=3){//男，研究生,非中层

                        if($compare_man_fir<0 && $compare_gradaute_fir<0 && $compare_job_fir<0){//只能选择经验最多的非研究生，并且是女员工

                     
                            $women_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',2);
                         
                            //经验最的的女性中的非研究生列表
                            $women_nogradute_orang_fir=$this->getRotaryNoGraduate($women_orang_fir,'graduate',3);
                            //获取非中中层
                            $women_nogradute_nolevel_orang_fir=$this->getRotaryNoGraduate($women_nogradute_orang_fir,'job_title',3);

                            if(null==$women_nogradute_nolevel_orang_fir){
                                break;
                            }else{
                                $women_orang_key_fir=array_column($women_nogradute_nolevel_orang_fir,'location_days','empNumber');
                           
                                $em_key_fir=$this->getMaxEmpNum($women_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }              
                            
                        }else if($compare_man_fir>-1 && $compare_gradaute_fir<0 && $compare_job_fir<0){//只要不是研究生，且不是中层，都可参与轮转

                            //获取非研究生的的列表
                            $nogradute_orang_fir=$this->getRotaryNoGraduate($firsDocEmp,'graduate',3);
                            $nojobtitle_orang_fir=$this->getRotaryNoGraduate($nogradute_orang_fir,'job_title',3);
                            
                            if(null==$nojobtitle_orang_fir){
                                break;
                            }else{
                                $nogradute_orang_key_fir=array_column($nojobtitle_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($nogradute_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }

                        }else if($compare_man_fir<0 && $compare_gradaute_fir>-1 && $compare_job_fir<0){//只女非中层员工都可以轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',2);
                            $nojobtitle_orang_fir=$this->getRotaryNoGraduate($women_orang_fir,'job_title',3);
                            if(null==$nojobtitle_orang_fir){
                                break;
                            }else{
                                $women_orang_key_fir=array_column($nojobtitle_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($women_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                        
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }

                        }else if($compare_man_fir>-1 && $compare_gradaute_fir<0 && $compare_job_fir>-1){//只要不是中层，都可参与轮转

                            //获取非中层的的列表
                          
                            $nojobtitle_orang_fir=$this->getRotaryNoGraduate($firsDocEmp,'job_title',3);
                            
                            if(null==$nojobtitle_orang_fir){
                                break;
                            }else{
                                $nogradute_orang_key_fir=array_column($nojobtitle_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($nogradute_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }

                        }else if($compare_man_fir<0 && $compare_gradaute_fir<0 && $compare_job_fir>-1){//只有女非研究生参与轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',2);
                            //再获取其中不适研究生的
                            $nojobtitle_orang_fir=$this->getRotaryNoGraduate($women_orang_fir,'graduate',3);
                            
                            if(null==$nojobtitle_orang_fir){
                                break;
                            }else{
                                $nogradute_orang_key_fir=array_column($nojobtitle_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($nogradute_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }

                        }else if($compare_man_fir<0 && $compare_gradaute_fir>-1 && $compare_job_fir>-1){//只有女参与轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',2);
                            
                            if(null==$women_orang_fir){
                                break;
                            }else{
                                $women_orang_key_fir=array_column($women_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($women_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                        
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }

                        }else{//如果该元满足轮转条件，直接参与轮转
                            $tmp_sec[$i]=$firsDocEmp[$i];
                            $tmp_sec[$i]['date_from']=$monarr[$i];
                            $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                            $rotary_department_one=$firsDocEmp[$i]['orange_department'];
                            unset($firsDocEmp[$i]);
                        }

                    }else if($firsDocEmp[$i]['gender']==1 && $firsDocEmp[$i]['graduate']!=3 && $firsDocEmp[$i]['job_title']!=3){//男，非研究生,非中层

                        if($compare_man_fir<0 && $compare_gradaute_fir<0 && $compare_job_fir<0){//只能选择经验最多的非研究生，并且是女员工

                     
                            $women_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',2);
                         
                            //经验最的的女性中的非研究生列表
                            $women_nogradute_orang_fir=$this->getRotaryNoGraduate($women_orang_fir,'graduate',3);
                            //获取非中中层
                            $women_nogradute_nolevel_orang_fir=$this->getRotaryNoGraduate($women_nogradute_orang_fir,'job_title',3);

                            if(null==$women_nogradute_nolevel_orang_fir){
                                break;
                            }else{
                                $women_orang_key_fir=array_column($women_nogradute_nolevel_orang_fir,'location_days','empNumber');
                           
                                $em_key_fir=$this->getMaxEmpNum($women_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }              
                            
                        }else if($compare_man_fir<0 && $compare_gradaute_fir>-1 && $compare_job_fir<0){//只女非中层员工都可以轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',2);
                            $nojobtitle_orang_fir=$this->getRotaryNoGraduate($women_orang_fir,'job_title',3);
                            if(null==$nojobtitle_orang_fir){
                                break;
                            }else{
                                $women_orang_key_fir=array_column($nojobtitle_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($women_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                        
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }

                        }else if($compare_man_fir<0 && $compare_gradaute_fir>-1 && $compare_job_fir>-1){//只有女参与轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',2);
                            
                            if(null==$women_orang_fir){
                                break;
                            }else{
                                $women_orang_key_fir=array_column($women_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($women_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                        
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }

                        }else if($compare_man_fir<0 && $compare_gradaute_fir<0 && $compare_job_fir>-1){//只有女非研究生参与轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',2);
                            //再获取其中不适研究生的
                            $nojobtitle_orang_fir=$this->getRotaryNoGraduate($women_orang_fir,'graduate',3);
                            
                            if(null==$nojobtitle_orang_fir){
                                break;
                            }else{
                                $nogradute_orang_key_fir=array_column($nojobtitle_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($nogradute_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }

                        }else{//如果该元满足轮转条件，直接参与轮转
                            $tmp_sec[$i]=$firsDocEmp[$i];
                            $tmp_sec[$i]['date_from']=$monarr[$i];
                            $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                            $rotary_department_one=$firsDocEmp[$i]['orange_department'];
                            unset($firsDocEmp[$i]);
                        }

                    }else if($firsDocEmp[$i]['gender']==1 && $firsDocEmp[$i]['graduate']!=3 && $firsDocEmp[$i]['job_title']==3){//男，非研究生,中层

                        if($compare_man_fir<0 && $compare_gradaute_fir<0 && $compare_job_fir<0){//只能选择经验最多的非研究生，并且是女员工

                     
                            $women_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',2);
                         
                            //经验最的的女性中的非研究生列表
                            $women_nogradute_orang_fir=$this->getRotaryNoGraduate($women_orang_fir,'graduate',3);
                            //获取非中中层
                            $women_nogradute_nolevel_orang_fir=$this->getRotaryNoGraduate($women_nogradute_orang_fir,'job_title',3);

                            if(null==$women_nogradute_nolevel_orang_fir){
                                break;
                            }else{
                                $women_orang_key_fir=array_column($women_nogradute_nolevel_orang_fir,'location_days','empNumber');
                           
                                $em_key_fir=$this->getMaxEmpNum($women_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }              
                            
                        }else if($compare_man_fir>-1 && $compare_gradaute_fir<0 && $compare_job_fir<0){//只要不是研究生，且不是中层，都可参与轮转

                            //获取非研究生的的列表
                            $nogradute_orang_fir=$this->getRotaryNoGraduate($firsDocEmp,'graduate',3);
                            $nojobtitle_orang_fir=$this->getRotaryNoGraduate($nogradute_orang_fir,'job_title',3);
                            
                            if(null==$nojobtitle_orang_fir){
                                break;
                            }else{
                                $nogradute_orang_key_fir=array_column($nojobtitle_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($nogradute_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }

                        }else if($compare_man_fir<0 && $compare_gradaute_fir<0 && $compare_job_fir>-1){//只有女非研究生参与轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',2);
                            //再获取其中不适研究生的
                            $nojobtitle_orang_fir=$this->getRotaryNoGraduate($women_orang_fir,'graduate',3);
                            
                            if(null==$nojobtitle_orang_fir){
                                break;
                            }else{
                                $nogradute_orang_key_fir=array_column($nojobtitle_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($nogradute_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }

                        }else if($compare_man_fir<0 && $compare_gradaute_fir>-1 && $compare_job_fir<0){//只女非中层员工都可以轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',2);
                            $nojobtitle_orang_fir=$this->getRotaryNoGraduate($women_orang_fir,'job_title',3);
                            if(null==$nojobtitle_orang_fir){
                                break;
                            }else{
                                $women_orang_key_fir=array_column($nojobtitle_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($women_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                        
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }

                        }else if($compare_man_fir>-1 && $compare_gradaute_fir>-1 && $compare_job_fir<0){//只有非中层参与轮转
                            //获取部门中经验最多的女员工,
                           $nojobtitle_orang_fir=$this->getRotaryNoGraduate($firsDocEmp,'job_title',3);
                            if(null==$nojobtitle_orang_fir){
                                break;
                            }else{
                                $women_orang_key_fir=array_column($nojobtitle_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($women_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                        
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }

                        }else if($compare_man_fir<0 && $compare_gradaute_fir>-1 && $compare_job_fir>-1){//只有女参与轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',2);
                            
                            if(null==$women_orang_fir){
                                break;
                            }else{
                                $women_orang_key_fir=array_column($women_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($women_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                        
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }

                        }else{//如果该元满足轮转条件，直接参与轮转
                            $tmp_sec[$i]=$firsDocEmp[$i];
                            $tmp_sec[$i]['date_from']=$monarr[$i];
                            $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                            $rotary_department_one=$firsDocEmp[$i]['orange_department'];
                            unset($firsDocEmp[$i]);
                        }

                    }else if($firsDocEmp[$i]['gender']!=1 && $firsDocEmp[$i]['graduate']==3 && $firsDocEmp[$i]['job_title']==3){//非男,研究生,中层

                        if($compare_man_fir<0 && $compare_gradaute_fir<0 && $compare_job_fir<0){//只能选择经验最多的非研究生，并且是女员工

                     
                            $women_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',2);
                         
                            //经验最的的女性中的非研究生列表
                            $women_nogradute_orang_fir=$this->getRotaryNoGraduate($women_orang_fir,'graduate',3);
                            //获取非中中层
                            $women_nogradute_nolevel_orang_fir=$this->getRotaryNoGraduate($women_nogradute_orang_fir,'job_title',3);

                            if(null==$women_nogradute_nolevel_orang_fir){
                                break;
                            }else{
                                $women_orang_key_fir=array_column($women_nogradute_nolevel_orang_fir,'location_days','empNumber');
                           
                                $em_key_fir=$this->getMaxEmpNum($women_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }              
                            
                        }else if($compare_man_fir>-1 && $compare_gradaute_fir<0 && $compare_job_fir<0){//只要不是研究生，且不是中层，都可参与轮转

                            //获取非研究生的的列表
                            $nogradute_orang_fir=$this->getRotaryNoGraduate($firsDocEmp,'graduate',3);
                            $nojobtitle_orang_fir=$this->getRotaryNoGraduate($nogradute_orang_fir,'job_title',3);
                            
                            if(null==$nojobtitle_orang_fir){
                                break;
                            }else{
                                $nogradute_orang_key_fir=array_column($nojobtitle_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($nogradute_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }

                        }else if($compare_man_fir<0 && $compare_gradaute_fir>-1 && $compare_job_fir<0){//只女非中层员工都可以轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',2);
                            $nojobtitle_orang_fir=$this->getRotaryNoGraduate($women_orang_fir,'job_title',3);
                            if(null==$nojobtitle_orang_fir){
                                break;
                            }else{
                                $women_orang_key_fir=array_column($nojobtitle_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($women_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                        
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }

                        }else if($compare_man_fir<0 && $compare_gradaute_fir<0 && $compare_job_fir>-1){//只有女非研究生参与轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',2);
                            //再获取其中不适研究生的
                            $nojobtitle_orang_fir=$this->getRotaryNoGraduate($women_orang_fir,'graduate',3);
                            
                            if(null==$nojobtitle_orang_fir){
                                break;
                            }else{
                                $nogradute_orang_key_fir=array_column($nojobtitle_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($nogradute_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }

                        }else if($compare_man_fir>-1 && $compare_gradaute_fir>-1 && $compare_job_fir<0){//只有非中层参与轮转
                            //获取部门中经验最多的女员工,
                           $nojobtitle_orang_fir=$this->getRotaryNoGraduate($firsDocEmp,'job_title',3);
                            if(null==$nojobtitle_orang_fir){
                                break;
                            }else{
                                $women_orang_key_fir=array_column($nojobtitle_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($women_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                        
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }

                        }else if($compare_man_fir>-1 && $compare_gradaute_fir<0 && $compare_job_fir>-1){//只要不是中层，都可参与轮转

                            //获取非中层的的列表
                          
                            $nojobtitle_orang_fir=$this->getRotaryNoGraduate($firsDocEmp,'job_title',3);
                            
                            if(null==$nojobtitle_orang_fir){
                                break;
                            }else{
                                $nogradute_orang_key_fir=array_column($nojobtitle_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($nogradute_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }

                        }else{//如果该元满足轮转条件，直接参与轮转
                            $tmp_sec[$i]=$firsDocEmp[$i];
                            $tmp_sec[$i]['date_from']=$monarr[$i];
                            $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                            $rotary_department_one=$firsDocEmp[$i]['orange_department'];
                            unset($firsDocEmp[$i]);
                        }

                    }else if($firsDocEmp[$i]['gender']!=1 && $firsDocEmp[$i]['graduate']==3 && $firsDocEmp[$i]['job_title']!==3){//非男,研究生,非中层

                        if($compare_man_fir<0 && $compare_gradaute_fir<0 && $compare_job_fir<0){//只能选择经验最多的非研究生，并且是女员工

                     
                            $women_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',2);
                         
                            //经验最的的女性中的非研究生列表
                            $women_nogradute_orang_fir=$this->getRotaryNoGraduate($women_orang_fir,'graduate',3);
                            //获取非中中层
                            $women_nogradute_nolevel_orang_fir=$this->getRotaryNoGraduate($women_nogradute_orang_fir,'job_title',3);

                            if(null==$women_nogradute_nolevel_orang_fir){
                                break;
                            }else{
                                $women_orang_key_fir=array_column($women_nogradute_nolevel_orang_fir,'location_days','empNumber');
                           
                                $em_key_fir=$this->getMaxEmpNum($women_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }              
                            
                        }else if($compare_man_fir<0 && $compare_gradaute_fir<0 && $compare_job_fir>-1){//只女非中层员工都可以轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',2);
                            $nojobtitle_orang_fir=$this->getRotaryNoGraduate($women_orang_fir,'job_title',3);
                            if(null==$nojobtitle_orang_fir){
                                break;
                            }else{
                                $women_orang_key_fir=array_column($nojobtitle_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($women_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                        
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }

                        }else if($compare_man_fir>-1 && $compare_gradaute_fir<0 && $compare_job_fir>-1){//只有女参与轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',2);
                            
                            if(null==$women_orang_fir){
                                break;
                            }else{
                                $women_orang_key_fir=array_column($women_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($women_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                        
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }

                        }else if($compare_man_fir>-1 && $compare_gradaute_fir<0 && $compare_job_fir<0){//只要不是研究生，且不是中层，都可参与轮转

                            //获取非研究生的的列表
                            $nogradute_orang_fir=$this->getRotaryNoGraduate($firsDocEmp,'graduate',3);
                            $nojobtitle_orang_fir=$this->getRotaryNoGraduate($nogradute_orang_fir,'job_title',3);
                            
                            if(null==$nojobtitle_orang_fir){
                                break;
                            }else{
                                $nogradute_orang_key_fir=array_column($nojobtitle_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($nogradute_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }

                        }else{//如果该元满足轮转条件，直接参与轮转
                            $tmp_sec[$i]=$firsDocEmp[$i];
                            $tmp_sec[$i]['date_from']=$monarr[$i];
                            $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                            $rotary_department_one=$firsDocEmp[$i]['orange_department'];
                            unset($firsDocEmp[$i]);
                        }

                    }else if($firsDocEmp[$i]['gender']!=1 && $firsDocEmp[$i]['graduate']!=3 && $firsDocEmp[$i]['job_title']==3){//非男，非研生,中层

                        if($compare_man_fir<0 && $compare_gradaute_fir<0 && $compare_job_fir<0){//只能选择经验最多的非研究生，并且是女员工,并且是中级员工

                            //获取部门中经验最多的女员工,
                            $women_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',2);
                         
                            //经验最的的女性中的非研究生列表
                            $women_nogradute_orang_fir=$this->getRotaryNoGraduate($women_orang_fir,'graduate',3);
                            //获取非中中层
                            $women_nogradute_nolevel_orang_fir=$this->getRotaryNoGraduate($women_nogradute_orang_fir,'job_title',3);



                            if(null==$women_nogradute_nolevel_orang_fir){
                                break;
                            }else{
                                $women_orang_key_fir=array_column($women_nogradute_nolevel_orang_fir,'location_days','empNumber');
                           
                                $em_key_fir=$this->getMaxEmpNum($women_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }        
                            
                        }else if($compare_man_fir<0 && $compare_gradaute_fir>-1 && $compare_job_fir<0){//只女非中层员工都可以轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_fir=$this->getRotaryManWomen($firsDocEmp,'gender',2);
                            $nojobtitle_orang_fir=$this->getRotaryNoGraduate($women_orang_fir,'job_title',3);
                            if(null==$nojobtitle_orang_fir){
                                break;
                            }else{
                                $women_orang_key_fir=array_column($nojobtitle_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($women_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                        
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }

                        }else if($compare_man_fir>-1 && $compare_gradaute_fir<0 && $compare_job_fir<0){//只要不是研究生，且不是中层，都可参与轮转

                            //获取非研究生的的列表
                            $nogradute_orang_fir=$this->getRotaryNoGraduate($firsDocEmp,'graduate',3);
                            $nojobtitle_orang_fir=$this->getRotaryNoGraduate($nogradute_orang_fir,'job_title',3);
                            
                            if(null==$nojobtitle_orang_fir){
                                break;
                            }else{
                                $nogradute_orang_key_fir=array_column($nojobtitle_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($nogradute_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }

                        }else if($compare_man_fir>-1 && $compare_gradaute_fir>-1 && $compare_job_fir<0){//只有非中层参与轮转
                            //获取部门中经验最多的女员工,
                           $nojobtitle_orang_fir=$this->getRotaryNoGraduate($firsDocEmp,'job_title',3);
                            if(null==$nojobtitle_orang_fir){
                                break;
                            }else{
                                $women_orang_key_fir=array_column($nojobtitle_orang_fir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_fir=$this->getMaxEmpNum($women_orang_key_fir,$firsDocEmp);
                        
                                $tmp_sec[$i]=$firsDocEmp[$em_key_fir];
                                $tmp_sec[$i]['date_from']=$monarr[$i];
                                $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                        
                                $rotary_department_one=$firsDocEmp[$em_key_fir]['orange_department'];

                                unset($firsDocEmp[$em_key_fir]);
                            }

                        }else{//如果该元满足轮转条件，直接参与轮转
                            $tmp_sec[$i]=$firsDocEmp[$i];
                            $tmp_sec[$i]['date_from']=$monarr[$i];
                            $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                            $rotary_department_one=$firsDocEmp[$i]['orange_department'];
                            unset($firsDocEmp[$i]);
                        }

                    }else{
                        $tmp_sec[$i]=$firsDocEmp[$i];
                        $tmp_sec[$i]['date_from']=$monarr[$i];
                        $tmp_sec[$i]['rotary_department']=$secDocEmp[$i]['orange_department'];
                        $rotary_department_one=$firsDocEmp[$i]['orange_department'];
                        unset($firsDocEmp[$i]);
                    }

                    //该部门员工中男士个数＝新加入的男士个数＋剩余的员工个数
                    $man_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',1);
                    $man_temp_sec=$this->getRotaryManWomen($tmp_sec,'gender',1);
                    $total_man_sec=count($man_orang_sec)+count($man_temp_sec)-1;
                    $compare_man_sec=$total_man_sec-$data['averge_man']['count'];

                    //该部门员工中研究生个数＝新加入的研究生个数＋剩余的研究生个数
                    $graduate_orang_sec=$this->getRotaryManWomen($secDocEmp,'graduate',3);
                    $graduate_temp_sec=$this->getRotaryManWomen($tmp_sec,'graduate',3);
                    $total_graduate_sec=count($graduate_orang_sec)+count($graduate_temp_sec)-1;
                    $compare_gradaute_sec=$total_graduate_sec-$data['averge_graduate']['count'];

                     //该部门员工中中级职称个数＝新加入的中级生个数＋剩余的中级生个数
                    $job_orang_sec=$this->getRotaryManWomen($secDocEmp,'job_title',3);
                    $job_temp_sec=$this->getRotaryManWomen($tmp_sec,'job_title',3);
                    $total_job_sec=count($job_orang_sec)+count($job_temp_sec)-1;
                    $compare_job_sec=$total_job_sec-$data['averge_mid_level']['count'];

                    //第一个部门，如果这个员工是男性，并且是研究生和中级。执行下面判断
                    if($secDocEmp[$i]['gender']==1 && $secDocEmp[$i]['graduate']==3 && $secDocEmp[$i]['job_title']==3){//男，研究生，中层

                        //轮班后男士数量和研究生小于平均值，则选择其中经验最多的女性且其中非研究生参与轮转
                        if($compare_man_sec<0 && $compare_gradaute_sec<0 && $compare_job_sec<0){//只能选择经验最多的非研究生，并且是女员工,并且是中级员工

                            //获取部门中经验最多的女员工,
                            $women_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',2);
                         
                            //经验最的的女性中的非研究生列表
                            $women_nogradute_orang_sec=$this->getRotaryNoGraduate($women_orang_sec,'graduate',3);
                            //获取非中中层
                            $women_nogradute_nolevel_orang_sec=$this->getRotaryNoGraduate($women_nogradute_orang_sec,'job_title',3);



                            if(null==$women_nogradute_nolevel_orang_sec){
                                break;
                            }else{
                                $women_orang_key_sec=array_column($women_nogradute_nolevel_orang_sec,'location_days','empNumber');
                           
                                $em_key_sec=$this->getMaxEmpNum($women_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }        
                            
                        }else if($compare_man_sec<0 && $compare_gradaute_sec>-1 && $compare_job_sec<0){//只女非中层员工都可以轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',2);
                            $nojobtitle_orang_sec=$this->getRotaryNoGraduate($women_orang_sec,'job_title',3);
                            if(null==$nojobtitle_orang_sec){
                                break;
                            }else{
                                $women_orang_key_sec=array_column($nojobtitle_orang_sec,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_sec=$this->getMaxEmpNum($women_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                        
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }

                        }else if($compare_man_sec<0 && $compare_gradaute_sec>-1 && $compare_job_sec>-1){//只有女参与轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',2);
                            
                            if(null==$women_orang_sec){
                                break;
                            }else{
                                $women_orang_key_sec=array_column($women_orang_sec,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_sec=$this->getMaxEmpNum($women_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                        
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }

                        }else if($compare_man_sec<0 && $compare_gradaute_sec<0 && $compare_job_sec>-1){//只有女非研究生参与轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',2);
                            //再获取其中不适研究生的
                            $nojobtitle_orang_sec=$this->getRotaryNoGraduate($women_orang_sec,'graduate',3);
                            
                            if(null==$nojobtitle_orang_sec){
                                break;
                            }else{
                                $nogradute_orang_key_sec=array_column($nojobtitle_orang_sec,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_sec=$this->getMaxEmpNum($nogradute_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }

                        }else if($compare_man_sec>-1 && $compare_gradaute_sec<0 && $compare_job_sec<0){//只要不是研究生，且不是中层，都可参与轮转

                            //获取非研究生的的列表
                            $nogradute_orang_sec=$this->getRotaryNoGraduate($secDocEmp,'graduate',3);
                            $nojobtitle_orang_sec=$this->getRotaryNoGraduate($nogradute_orang_sec,'job_title',3);
                            
                            if(null==$nojobtitle_orang_sec){
                                break;
                            }else{
                                $nogradute_orang_key_sec=array_column($nojobtitle_orang_sec,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_sec=$this->getMaxEmpNum($nogradute_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }

                        }else if($compare_man_sec>-1 && $compare_gradaute_sec<0 && $compare_job_sec>-1){//只要不是中层，都可参与轮转

                            //获取非中层的的列表
                          
                            $nojobtitle_orang_sec=$this->getRotaryNoGraduate($secDocEmp,'job_title',3);
                            
                            if(null==$nojobtitle_orang_sec){
                                break;
                            }else{
                                $nogradute_orang_key_sec=array_column($nojobtitle_orang_sec,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_sec=$this->getMaxEmpNum($nogradute_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }

                        }else if($compare_man_sec>-1 && $compare_gradaute_sec>-1 && $compare_job_sec<0){//只有非中层参与轮转
                            //获取部门中经验最多的女员工,
                           $nojobtitle_orang_sec=$this->getRotaryNoGraduate($secDocEmp,'job_title',3);
                            if(null==$nojobtitle_orang_sec){
                                break;
                            }else{
                                $women_orang_key_sec=array_column($nojobtitle_orang_sec,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_sec=$this->getMaxEmpNum($women_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                        
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }

                        }else {//如果轮班后男士数和研究生数大于或者等于平均值，将该员工轮转到第二个部门
                            $tmp_thir[$i]=$secDocEmp[$i];
                            $tmp_thir[$i]['date_from']=$monarr[$i];
                            $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                            $rotary_department_two=$thirDocEmp[$i]['orange_department'];
                            unset($secDocEmp[$i]);
                        }
                       
                    }else if($secDocEmp[$i]['gender']==1 && $secDocEmp[$i]['graduate']==3 && $secDocEmp[$i]['job_title']!=3){//男，研究生,非中层

                        if($compare_man_sec<0 && $compare_gradaute_sec<0 && $compare_job_sec<0){//只能选择经验最多的非研究生，并且是女员工

                     
                            $women_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',2);
                         
                            //经验最的的女性中的非研究生列表
                            $women_nogradute_orang_sec=$this->getRotaryNoGraduate($women_orang_sec,'graduate',3);
                            //获取非中中层
                            $women_nogradute_nolevel_orang_sec=$this->getRotaryNoGraduate($women_nogradute_orang_sec,'job_title',3);

                            if(null==$women_nogradute_nolevel_orang_sec){
                                break;
                            }else{
                                $women_orang_key_sec=array_column($women_nogradute_nolevel_orang_sec,'location_days','empNumber');
                           
                                $em_key_sec=$this->getMaxEmpNum($women_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }              
                            
                        }else if($compare_man_sec>-1 && $compare_gradaute_sec<0 && $compare_job_sec<0){//只要不是研究生，且不是中层，都可参与轮转

                            //获取非研究生的的列表
                            $nogradute_orang_sec=$this->getRotaryNoGraduate($secDocEmp,'graduate',3);
                            $nojobtitle_orang_sec=$this->getRotaryNoGraduate($nogradute_orang_sec,'job_title',3);
                            
                            if(null==$nojobtitle_orang_sec){
                                break;
                            }else{
                                $nogradute_orang_key_sec=array_column($nojobtitle_orang_sec,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_sec=$this->getMaxEmpNum($nogradute_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }

                        }else if($compare_man_sec<0 && $compare_gradaute_sec>-1 && $compare_job_sec<0){//只女非中层员工都可以轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',2);
                            $nojobtitle_orang_sec=$this->getRotaryNoGraduate($women_orang_sec,'job_title',3);
                            if(null==$nojobtitle_orang_sec){
                                break;
                            }else{
                                $women_orang_key_sec=array_column($nojobtitle_orang_sec,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_sec=$this->getMaxEmpNum($women_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                        
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }

                        }else if($compare_man_sec>-1 && $compare_gradaute_sec<0 && $compare_job_sec>-1){//只要不是中层，都可参与轮转

                            //获取非中层的的列表
                          
                            $nojobtitle_orang_sec=$this->getRotaryNoGraduate($secDocEmp,'job_title',3);
                            
                            if(null==$nojobtitle_orang_sec){
                                break;
                            }else{
                                $nogradute_orang_key_sec=array_column($nojobtitle_orang_sec,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_sec=$this->getMaxEmpNum($nogradute_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }

                        }else if($compare_man_sec<0 && $compare_gradaute_sec<0 && $compare_job_sec>-1){//只有女非研究生参与轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',2);
                            //再获取其中不适研究生的
                            $nojobtitle_orang_sec=$this->getRotaryNoGraduate($women_orang_sec,'graduate',3);
                            
                            if(null==$nojobtitle_orang_sec){
                                break;
                            }else{
                                $nogradute_orang_key_sec=array_column($nojobtitle_orang_sec,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_sec=$this->getMaxEmpNum($nogradute_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }

                        }else if($compare_man_sec<0 && $compare_gradaute_sec>-1 && $compare_job_sec>-1){//只有女参与轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',2);
                            
                            if(null==$women_orang_sec){
                                break;
                            }else{
                                $women_orang_key_sec=array_column($women_orang_sec,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_sec=$this->getMaxEmpNum($women_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                        
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }

                        }else{//如果该元满足轮转条件，直接参与轮转
                            $tmp_thir[$i]=$secDocEmp[$i];
                            $tmp_thir[$i]['date_from']=$monarr[$i];
                            $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                            $rotary_department_two=$thirDocEmp[$i]['orange_department'];
                            unset($secDocEmp[$i]);
                        }

                    }else if($secDocEmp[$i]['gender']==1 && $secDocEmp[$i]['graduate']!=3 && $secDocEmp[$i]['job_title']!=3){//男，非研究生,非中层

                        if($compare_man_sec<0 && $compare_gradaute_sec<0 && $compare_job_sec<0){//只能选择经验最多的非研究生，并且是女员工

                     
                            $women_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',2);
                         
                            //经验最的的女性中的非研究生列表
                            $women_nogradute_orang_sec=$this->getRotaryNoGraduate($women_orang_sec,'graduate',3);
                            //获取非中中层
                            $women_nogradute_nolevel_orang_sec=$this->getRotaryNoGraduate($women_nogradute_orang_sec,'job_title',3);

                            if(null==$women_nogradute_nolevel_orang_sec){
                                break;
                            }else{
                                $women_orang_key_sec=array_column($women_nogradute_nolevel_orang_sec,'location_days','empNumber');
                           
                                $em_key_sec=$this->getMaxEmpNum($women_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }              
                            
                        }else if($compare_man_sec<0 && $compare_gradaute_sec>-1 && $compare_job_sec<0){//只女非中层员工都可以轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',2);
                            $nojobtitle_orang_sec=$this->getRotaryNoGraduate($women_orang_sec,'job_title',3);
                            if(null==$nojobtitle_orang_sec){
                                break;
                            }else{
                                $women_orang_key_sec=array_column($nojobtitle_orang_sec,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_sec=$this->getMaxEmpNum($women_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                        
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }

                        }else if($compare_man_sec<0 && $compare_gradaute_sec>-1 && $compare_job_sec>-1){//只有女参与轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',2);
                            
                            if(null==$women_orang_sec){
                                break;
                            }else{
                                $women_orang_key_sec=array_column($women_orang_sec,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_sec=$this->getMaxEmpNum($women_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                        
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }

                        }else if($compare_man_sec<0 && $compare_gradaute_sec<0 && $compare_job_sec>-1){//只有女非研究生参与轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',2);
                            //再获取其中不适研究生的
                            $nojobtitle_orang_sec=$this->getRotaryNoGraduate($women_orang_sec,'graduate',3);
                            
                            if(null==$nojobtitle_orang_sec){
                                break;
                            }else{
                                $nogradute_orang_key_sec=array_column($nojobtitle_orang_sec,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_sec=$this->getMaxEmpNum($nogradute_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }

                        }else{//如果该元满足轮转条件，直接参与轮转
                            $tmp_thir[$i]=$secDocEmp[$i];
                            $tmp_thir[$i]['date_from']=$monarr[$i];
                            $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                            $rotary_department_two=$thirDocEmp[$i]['orange_department'];
                            unset($secDocEmp[$i]);
                        }

                    }else if($secDocEmp[$i]['gender']==1 && $secDocEmp[$i]['graduate']!=3 && $secDocEmp[$i]['job_title']==3){//男，非研究生,中层

                        if($compare_man_sec<0 && $compare_gradaute_sec<0 && $compare_job_sec<0){//只能选择经验最多的非研究生，并且是女员工

                     
                            $women_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',2);
                         
                            //经验最的的女性中的非研究生列表
                            $women_nogradute_orang_sec=$this->getRotaryNoGraduate($women_orang_sec,'graduate',3);
                            //获取非中中层
                            $women_nogradute_nolevel_orang_sec=$this->getRotaryNoGraduate($women_nogradute_orang_sec,'job_title',3);

                            if(null==$women_nogradute_nolevel_orang_sec){
                                break;
                            }else{
                                $women_orang_key_sec=array_column($women_nogradute_nolevel_orang_sec,'location_days','empNumber');
                           
                                $em_key_sec=$this->getMaxEmpNum($women_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }              
                            
                        }else if($compare_man_sec>-1 && $compare_gradaute_sec<0 && $compare_job_sec<0){//只要不是研究生，且不是中层，都可参与轮转

                            //获取非研究生的的列表
                            $nogradute_orang_sec=$this->getRotaryNoGraduate($secDocEmp,'graduate',3);
                            $nojobtitle_orang_sec=$this->getRotaryNoGraduate($nogradute_orang_sec,'job_title',3);
                            
                            if(null==$nojobtitle_orang_sec){
                                break;
                            }else{
                                $nogradute_orang_key_sec=array_column($nojobtitle_orang_sec,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_sec=$this->getMaxEmpNum($nogradute_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }

                        }else if($compare_man_sec<0 && $compare_gradaute_sec<0 && $compare_job_sec>-1){//只有女非研究生参与轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',2);
                            //再获取其中不适研究生的
                            $nojobtitle_orang_sec=$this->getRotaryNoGraduate($women_orang_sec,'graduate',3);
                            
                            if(null==$nojobtitle_orang_sec){
                                break;
                            }else{
                                $nogradute_orang_key_sec=array_column($nojobtitle_orang_sec,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_sec=$this->getMaxEmpNum($nogradute_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }

                        }else if($compare_man_sec<0 && $compare_gradaute_sec>-1 && $compare_job_sec<0){//只女非中层员工都可以轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',2);
                            $nojobtitle_orang_sec=$this->getRotaryNoGraduate($women_orang_sec,'job_title',3);
                            if(null==$nojobtitle_orang_sec){
                                break;
                            }else{
                                $women_orang_key_sec=array_column($nojobtitle_orang_sec,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_sec=$this->getMaxEmpNum($women_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                        
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }

                        }else if($compare_man_sec>-1 && $compare_gradaute_sec>-1 && $compare_job_sec<0){//只有非中层参与轮转
                            //获取部门中经验最多的女员工,
                           $nojobtitle_orang_sec=$this->getRotaryNoGraduate($secDocEmp,'job_title',3);
                            if(null==$nojobtitle_orang_sec){
                                break;
                            }else{
                                $women_orang_key_sec=array_column($nojobtitle_orang_sec,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_sec=$this->getMaxEmpNum($women_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                        
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }

                        }else if($compare_man_sec<0 && $compare_gradaute_sec>-1 && $compare_job_sec>-1){//只有女参与轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',2);
                            
                            if(null==$women_orang_sec){
                                break;
                            }else{
                                $women_orang_key_sec=array_column($women_orang_sec,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_sec=$this->getMaxEmpNum($women_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                        
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }

                        }else{//如果该元满足轮转条件，直接参与轮转
                            $tmp_thir[$i]=$secDocEmp[$i];
                            $tmp_thir[$i]['date_from']=$monarr[$i];
                            $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                            $rotary_department_two=$thirDocEmp[$i]['orange_department'];
                            unset($secDocEmp[$i]);
                        }

                    }else if($secDocEmp[$i]['gender']!=1 && $secDocEmp[$i]['graduate']==3 && $secDocEmp[$i]['job_title']==3){//非男,研究生,中层

                        if($compare_man_sec<0 && $compare_gradaute_sec<0 && $compare_job_sec<0){//只能选择经验最多的非研究生，并且是女员工

                     
                            $women_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',2);
                         
                            //经验最的的女性中的非研究生列表
                            $women_nogradute_orang_sec=$this->getRotaryNoGraduate($women_orang_sec,'graduate',3);
                            //获取非中中层
                            $women_nogradute_nolevel_orang_sec=$this->getRotaryNoGraduate($women_nogradute_orang_sec,'job_title',3);

                            if(null==$women_nogradute_nolevel_orang_sec){
                                break;
                            }else{
                                $women_orang_key_sec=array_column($women_nogradute_nolevel_orang_sec,'location_days','empNumber');
                           
                                $em_key_sec=$this->getMaxEmpNum($women_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }              
                            
                        }else if($compare_man_sec>-1 && $compare_gradaute_sec<0 && $compare_job_sec<0){//只要不是研究生，且不是中层，都可参与轮转

                            //获取非研究生的的列表
                            $nogradute_orang_sec=$this->getRotaryNoGraduate($secDocEmp,'graduate',3);
                            $nojobtitle_orang_sec=$this->getRotaryNoGraduate($nogradute_orang_sec,'job_title',3);
                            
                            if(null==$nojobtitle_orang_sec){
                                break;
                            }else{
                                $nogradute_orang_key_sec=array_column($nojobtitle_orang_sec,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_sec=$this->getMaxEmpNum($nogradute_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }

                        }else if($compare_man_sec<0 && $compare_gradaute_sec>-1 && $compare_job_sec<0){//只女非中层员工都可以轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',2);
                            $nojobtitle_orang_sec=$this->getRotaryNoGraduate($women_orang_sec,'job_title',3);
                            if(null==$nojobtitle_orang_sec){
                                break;
                            }else{
                                $women_orang_key_sec=array_column($nojobtitle_orang_sec,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_sec=$this->getMaxEmpNum($women_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                        
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }

                        }else if($compare_man_sec<0 && $compare_gradaute_sec<0 && $compare_job_sec>-1){//只有女非研究生参与轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',2);
                            //再获取其中不适研究生的
                            $nojobtitle_orang_sec=$this->getRotaryNoGraduate($women_orang_sec,'graduate',3);
                            
                            if(null==$nojobtitle_orang_sec){
                                break;
                            }else{
                                $nogradute_orang_key_sec=array_column($nojobtitle_orang_sec,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_sec=$this->getMaxEmpNum($nogradute_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }

                        }else if($compare_man_sec>-1 && $compare_gradaute_sec>-1 && $compare_job_sec<0){//只有非中层参与轮转
                            //获取部门中经验最多的女员工,
                           $nojobtitle_orang_sec=$this->getRotaryNoGraduate($secDocEmp,'job_title',3);
                            if(null==$nojobtitle_orang_sec){
                                break;
                            }else{
                                $women_orang_key_sec=array_column($nojobtitle_orang_sec,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_sec=$this->getMaxEmpNum($women_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                        
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }

                        }else if($compare_man_sec>-1 && $compare_gradaute_sec<0 && $compare_job_sec>-1){//只要不是中层，都可参与轮转

                            //获取非中层的的列表
                          
                            $nojobtitle_orang_sec=$this->getRotaryNoGraduate($secDocEmp,'job_title',3);
                            
                            if(null==$nojobtitle_orang_sec){
                                break;
                            }else{
                                $nogradute_orang_key_sec=array_column($nojobtitle_orang_sec,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_sec=$this->getMaxEmpNum($nogradute_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }

                        }else{//如果该元满足轮转条件，直接参与轮转
                            $tmp_thir[$i]=$secDocEmp[$i];
                            $tmp_thir[$i]['date_from']=$monarr[$i];
                            $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                            $rotary_department_two=$thirDocEmp[$i]['orange_department'];
                            unset($secDocEmp[$i]);
                        }

                    }else if($secDocEmp[$i]['gender']!=1 && $secDocEmp[$i]['graduate']==3 && $secDocEmp[$i]['job_title']!==3){//非男,研究生,非中层

                        if($compare_man_sec<0 && $compare_gradaute_sec<0 && $compare_job_sec<0){//只能选择经验最多的非研究生，并且是女员工

                     
                            $women_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',2);
                         
                            //经验最的的女性中的非研究生列表
                            $women_nogradute_orang_sec=$this->getRotaryNoGraduate($women_orang_sec,'graduate',3);
                            //获取非中中层
                            $women_nogradute_nolevel_orang_sec=$this->getRotaryNoGraduate($women_nogradute_orang_sec,'job_title',3);

                            if(null==$women_nogradute_nolevel_orang_sec){
                                break;
                            }else{
                                $women_orang_key_sec=array_column($women_nogradute_nolevel_orang_sec,'location_days','empNumber');
                           
                                $em_key_sec=$this->getMaxEmpNum($women_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }              
                            
                        }else if($compare_man_sec<0 && $compare_gradaute_sec<0 && $compare_job_sec>-1){//只女非中层员工都可以轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',2);
                            $nojobtitle_orang_sec=$this->getRotaryNoGraduate($women_orang_sec,'job_title',3);
                            if(null==$nojobtitle_orang_sec){
                                break;
                            }else{
                                $women_orang_key_sec=array_column($nojobtitle_orang_sec,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_sec=$this->getMaxEmpNum($women_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                        
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }

                        }else if($compare_man_sec>-1 && $compare_gradaute_sec<0 && $compare_job_sec>-1){//只有女参与轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',2);
                            
                            if(null==$women_orang_sec){
                                break;
                            }else{
                                $women_orang_key_sec=array_column($women_orang_sec,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_sec=$this->getMaxEmpNum($women_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                        
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }

                        }else if($compare_man_sec>-1 && $compare_gradaute_sec<0 && $compare_job_sec<0){//只要不是研究生，且不是中层，都可参与轮转

                            //获取非研究生的的列表
                            $nogradute_orang_sec=$this->getRotaryNoGraduate($secDocEmp,'graduate',3);
                            $nojobtitle_orang_sec=$this->getRotaryNoGraduate($nogradute_orang_sec,'job_title',3);
                            
                            if(null==$nojobtitle_orang_sec){
                                break;
                            }else{
                                $nogradute_orang_key_sec=array_column($nojobtitle_orang_sec,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_sec=$this->getMaxEmpNum($nogradute_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }

                        }else{//如果该元满足轮转条件，直接参与轮转
                            $tmp_thir[$i]=$secDocEmp[$i];
                            $tmp_thir[$i]['date_from']=$monarr[$i];
                            $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                            $rotary_department_two=$thirDocEmp[$i]['orange_department'];
                            unset($secDocEmp[$i]);
                        }

                    }else if($secDocEmp[$i]['gender']!=1 && $secDocEmp[$i]['graduate']!=3 && $secDocEmp[$i]['job_title']==3){//非男，非研生,中层

                        if($compare_man_sec<0 && $compare_gradaute_sec<0 && $compare_job_sec<0){//只能选择经验最多的非研究生，并且是女员工,并且是中级员工

                            //获取部门中经验最多的女员工,
                            $women_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',2);
                         
                            //经验最的的女性中的非研究生列表
                            $women_nogradute_orang_sec=$this->getRotaryNoGraduate($women_orang_sec,'graduate',3);
                            //获取非中中层
                            $women_nogradute_nolevel_orang_sec=$this->getRotaryNoGraduate($women_nogradute_orang_sec,'job_title',3);



                            if(null==$women_nogradute_nolevel_orang_sec){
                                break;
                            }else{
                                $women_orang_key_sec=array_column($women_nogradute_nolevel_orang_sec,'location_days','empNumber');
                           
                                $em_key_sec=$this->getMaxEmpNum($women_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }        
                            
                        }else if($compare_man_sec<0 && $compare_gradaute_sec>-1 && $compare_job_sec<0){//只女非中层员工都可以轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_sec=$this->getRotaryManWomen($secDocEmp,'gender',2);
                            $nojobtitle_orang_sec=$this->getRotaryNoGraduate($women_orang_sec,'job_title',3);
                            if(null==$nojobtitle_orang_sec){
                                break;
                            }else{
                                $women_orang_key_sec=array_column($nojobtitle_orang_sec,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_sec=$this->getMaxEmpNum($women_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                        
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }

                        }else if($compare_man_sec>-1 && $compare_gradaute_sec<0 && $compare_job_sec<0){//只要不是研究生，且不是中层，都可参与轮转

                            //获取非研究生的的列表
                            $nogradute_orang_sec=$this->getRotaryNoGraduate($secDocEmp,'graduate',3);
                            $nojobtitle_orang_sec=$this->getRotaryNoGraduate($nogradute_orang_sec,'job_title',3);
                            
                            if(null==$nojobtitle_orang_sec){
                                break;
                            }else{
                                $nogradute_orang_key_sec=array_column($nojobtitle_orang_sec,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_sec=$this->getMaxEmpNum($nogradute_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }

                        }else if($compare_man_sec>-1 && $compare_gradaute_sec>-1 && $compare_job_sec<0){//只有非中层参与轮转
                            //获取部门中经验最多的女员工,
                           $nojobtitle_orang_sec=$this->getRotaryNoGraduate($secDocEmp,'job_title',3);
                            if(null==$nojobtitle_orang_sec){
                                break;
                            }else{
                                $women_orang_key_sec=array_column($nojobtitle_orang_sec,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_sec=$this->getMaxEmpNum($women_orang_key_sec,$secDocEmp);
                        
                                $tmp_thir[$i]=$secDocEmp[$em_key_sec];
                                $tmp_thir[$i]['date_from']=$monarr[$i];
                                $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                        
                                $rotary_department_two=$thirDocEmp[$em_key_sec]['orange_department'];

                                unset($secDocEmp[$em_key_sec]);
                            }

                        }else{//如果该元满足轮转条件，直接参与轮转
                            $tmp_thir[$i]=$secDocEmp[$i];
                            $tmp_thir[$i]['date_from']=$monarr[$i];
                            $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                            $rotary_department_two=$thirDocEmp[$i]['orange_department'];
                            unset($secDocEmp[$i]);
                        }

                    }else{
                        $tmp_thir[$i]=$secDocEmp[$i];
                        $tmp_thir[$i]['date_from']=$monarr[$i];
                        $tmp_thir[$i]['rotary_department']=$thirDocEmp[$i]['orange_department'];
                        $rotary_department_two=$thirDocEmp[$i]['orange_department'];
                        unset($secDocEmp[$i]);
                    }

                    //该部门员工中男士个数＝新加入的男士个数＋剩余的员工个数
                    $man_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',1);
                    $man_temp_thir=$this->getRotaryManWomen($tmp_thir,'gender',1);
                    $total_man_thir=count($man_orang_thir)+count($man_temp_thir)-1;
                    $compare_man_thir=$total_man_thir-$data['averge_man']['count'];

                    //该部门员工中研究生个数＝新加入的研究生个数＋剩余的研究生个数
                    $graduate_orang_thir=$this->getRotaryManWomen($thirDocEmp,'graduate',3);
                    $graduate_temp_thir=$this->getRotaryManWomen($tmp_thir,'graduate',3);
                    $total_graduate_thir=count($graduate_orang_thir)+count($graduate_temp_thir)-1;
                    $compare_gradaute_thir=$total_graduate_thir-$data['averge_graduate']['count'];

                    $job_orang_thir=$this->getRotaryManWomen($thirDocEmp,'job_title',3);
                    $job_temp_thir=$this->getRotaryManWomen($tmp_thir,'job_title',3);
                    $total_job_thir=count($job_orang_thir)+count($job_temp_thir)-1;
                    $compare_job_thir=$total_job_thir-$data['averge_mid_level']['count'];

                    // var_dump($compare_gradaute_thir);exit;
                    //第一个部门，如果这个员工是男性，并且是研究生和中级。执行下面判断
                    if($thirDocEmp[$i]['gender']==1 && $thirDocEmp[$i]['graduate']==3 && $thirDocEmp[$i]['job_title']==3){//男，研究生，中层

                        //轮班后男士数量和研究生小于平均值，则选择其中经验最多的女性且其中非研究生参与轮转
                        if($compare_man_thir<0 && $compare_gradaute_thir<0 && $compare_job_thir<0){//只能选择经验最多的非研究生，并且是女员工,并且是中级员工

                            //获取部门中经验最多的女员工,
                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                         
                            //经验最的的女性中的非研究生列表
                            $women_nogradute_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'graduate',3);
                            //获取非中中层
                            $women_nogradute_nolevel_orang_thir=$this->getRotaryNoGraduate($women_nogradute_orang_thir,'job_title',3);



                            if(null==$women_nogradute_nolevel_orang_thir){
                                break;
                            }else{
                                $women_orang_key_thir=array_column($women_nogradute_nolevel_orang_thir,'location_days','empNumber');
                           
                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }        
                            
                        }else if($compare_man_thir<0 && $compare_gradaute_thir>-1 && $compare_job_thir<0){//只女非中层员工都可以轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'job_title',3);
                            if(null==$nojobtitle_orang_thir){
                                break;
                            }else{
                                $women_orang_key_thir=array_column($nojobtitle_orang_thir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                        
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }

                        }else if($compare_man_thir<0 && $compare_gradaute_thir>-1 && $compare_job_thir>-1){//只有女参与轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                            
                            if(null==$women_orang_thir){
                                break;
                            }else{
                                $women_orang_key_thir=array_column($women_orang_thir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                        
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }

                        }else if($compare_man_thir<0 && $compare_gradaute_thir<0 && $compare_job_thir>-1){//只有女非研究生参与轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                            //再获取其中不适研究生的
                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'graduate',3);
                            
                            if(null==$nojobtitle_orang_thir){
                                break;
                            }else{
                                $nogradute_orang_key_thir=array_column($nojobtitle_orang_thir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_thir=$this->getMaxEmpNum($nogradute_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }

                        }else if($compare_man_thir>-1 && $compare_gradaute_thir<0 && $compare_job_thir<0){//只要不是研究生，且不是中层，都可参与轮转

                            //获取非研究生的的列表
                            $nogradute_orang_thir=$this->getRotaryNoGraduate($thirDocEmp,'graduate',3);
                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($nogradute_orang_thir,'job_title',3);
                            
                            if(null==$nojobtitle_orang_thir){
                                break;
                            }else{
                                $nogradute_orang_key_thir=array_column($nojobtitle_orang_thir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_thir=$this->getMaxEmpNum($nogradute_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }

                        }else if($compare_man_thir>-1 && $compare_gradaute_thir<0 && $compare_job_thir>-1){//只要不是中层，都可参与轮转

                            //获取非中层的的列表
                          
                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($thirDocEmp,'job_title',3);
                            
                            if(null==$nojobtitle_orang_thir){
                                break;
                            }else{
                                $nogradute_orang_key_thir=array_column($nojobtitle_orang_thir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_thir=$this->getMaxEmpNum($nogradute_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }

                        }else if($compare_man_thir>-1 && $compare_gradaute_thir>-1 && $compare_job_thir<0){//只有非中层参与轮转
                            //获取部门中经验最多的女员工,
                           $nojobtitle_orang_thir=$this->getRotaryNoGraduate($thirDocEmp,'job_title',3);
                            if(null==$nojobtitle_orang_thir){
                                break;
                            }else{
                                $women_orang_key_thir=array_column($nojobtitle_orang_thir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                        
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }

                        }else {//如果轮班后男士数和研究生数大于或者等于平均值，将该员工轮转到第二个部门
                            $tmp_fir[$i]=$thirDocEmp[$i];
                            $tmp_fir[$i]['date_from']=$monarr[$i];
                            $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                            $rotary_department_three=$thirDocEmp[$i]['orange_department'];
                            unset($thirDocEmp[$i]);
                        }
                       
                    }else if($thirDocEmp[$i]['gender']==1 && $thirDocEmp[$i]['graduate']==3 && $thirDocEmp[$i]['job_title']!=3){//男，研究生,非中层

                        if($compare_man_thir<0 && $compare_gradaute_thir<0 && $compare_job_thir<0){//只能选择经验最多的非研究生，并且是女员工

                     
                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                         
                            //经验最的的女性中的非研究生列表
                            $women_nogradute_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'graduate',3);
                            //获取非中中层
                            $women_nogradute_nolevel_orang_thir=$this->getRotaryNoGraduate($women_nogradute_orang_thir,'job_title',3);

                            if(null==$women_nogradute_nolevel_orang_thir){
                                break;
                            }else{
                                $women_orang_key_thir=array_column($women_nogradute_nolevel_orang_thir,'location_days','empNumber');
                           
                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }              
                            
                        }else if($compare_man_thir>-1 && $compare_gradaute_thir<0 && $compare_job_thir<0){//只要不是研究生，且不是中层，都可参与轮转

                            //获取非研究生的的列表
                            $nogradute_orang_thir=$this->getRotaryNoGraduate($thirDocEmp,'graduate',3);
                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($nogradute_orang_thir,'job_title',3);
                            
                            if(null==$nojobtitle_orang_thir){
                                break;
                            }else{
                                $nogradute_orang_key_thir=array_column($nojobtitle_orang_thir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_thir=$this->getMaxEmpNum($nogradute_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }

                        }else if($compare_man_thir<0 && $compare_gradaute_thir>-1 && $compare_job_thir<0){//只女非中层员工都可以轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'job_title',3);
                            if(null==$nojobtitle_orang_thir){
                                break;
                            }else{
                                $women_orang_key_thir=array_column($nojobtitle_orang_thir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                        
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }

                        }else if($compare_man_thir>-1 && $compare_gradaute_thir<0 && $compare_job_thir>-1){//只要不是中层，都可参与轮转

                            //获取非中层的的列表
                          
                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($thirDocEmp,'job_title',3);
                            
                            if(null==$nojobtitle_orang_thir){
                                break;
                            }else{
                                $nogradute_orang_key_thir=array_column($nojobtitle_orang_thir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_thir=$this->getMaxEmpNum($nogradute_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }

                        }else if($compare_man_thir<0 && $compare_gradaute_thir<0 && $compare_job_thir>-1){//只有女非研究生参与轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                            //再获取其中不适研究生的
                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'graduate',3);
                            
                            if(null==$nojobtitle_orang_thir){
                                break;
                            }else{
                                $nogradute_orang_key_thir=array_column($nojobtitle_orang_thir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_thir=$this->getMaxEmpNum($nogradute_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }

                        }else if($compare_man_thir<0 && $compare_gradaute_thir>-1 && $compare_job_thir>-1){//只有女参与轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                            
                            if(null==$women_orang_thir){
                                break;
                            }else{
                                $women_orang_key_thir=array_column($women_orang_thir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                        
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }

                        }else{//如果该元满足轮转条件，直接参与轮转
                            $tmp_fir[$i]=$thirDocEmp[$i];
                            $tmp_fir[$i]['date_from']=$monarr[$i];
                            $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                            $rotary_department_three=$thirDocEmp[$i]['orange_department'];
                            unset($thirDocEmp[$i]);
                        }

                    }else if($thirDocEmp[$i]['gender']==1 && $thirDocEmp[$i]['graduate']!=3 && $thirDocEmp[$i]['job_title']!=3){//男，非研究生,非中层

                        if($compare_man_thir<0 && $compare_gradaute_thir<0 && $compare_job_thir<0){//只能选择经验最多的非研究生，并且是女员工

                     
                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                         
                            //经验最的的女性中的非研究生列表
                            $women_nogradute_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'graduate',3);
                            //获取非中中层
                            $women_nogradute_nolevel_orang_thir=$this->getRotaryNoGraduate($women_nogradute_orang_thir,'job_title',3);

                            if(null==$women_nogradute_nolevel_orang_thir){
                                break;
                            }else{
                                $women_orang_key_thir=array_column($women_nogradute_nolevel_orang_thir,'location_days','empNumber');
                           
                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }              
                            
                        }else if($compare_man_thir<0 && $compare_gradaute_thir>-1 && $compare_job_thir<0){//只女非中层员工都可以轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'job_title',3);
                            if(null==$nojobtitle_orang_thir){
                                break;
                            }else{
                                $women_orang_key_thir=array_column($nojobtitle_orang_thir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                        
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }

                        }else if($compare_man_thir<0 && $compare_gradaute_thir>-1 && $compare_job_thir>-1){//只有女参与轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                            
                            if(null==$women_orang_thir){
                                break;
                            }else{
                                $women_orang_key_thir=array_column($women_orang_thir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                        
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }

                        }else if($compare_man_thir<0 && $compare_gradaute_thir<0 && $compare_job_thir>-1){//只有女非研究生参与轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                            //再获取其中不适研究生的
                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'graduate',3);
                            
                            if(null==$nojobtitle_orang_thir){
                                break;
                            }else{
                                $nogradute_orang_key_thir=array_column($nojobtitle_orang_thir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_thir=$this->getMaxEmpNum($nogradute_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }

                        }else{//如果该元满足轮转条件，直接参与轮转
                            $tmp_fir[$i]=$thirDocEmp[$i];
                            $tmp_fir[$i]['date_from']=$monarr[$i];
                            $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                            $rotary_department_three=$thirDocEmp[$i]['orange_department'];
                            unset($thirDocEmp[$i]);
                        }

                    }else if($thirDocEmp[$i]['gender']==1 && $thirDocEmp[$i]['graduate']!=3 && $thirDocEmp[$i]['job_title']==3){//男，非研究生,中层

                        if($compare_man_thir<0 && $compare_gradaute_thir<0 && $compare_job_thir<0){//只能选择经验最多的非研究生，并且是女员工

                     
                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                         
                            //经验最的的女性中的非研究生列表
                            $women_nogradute_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'graduate',3);
                            //获取非中中层
                            $women_nogradute_nolevel_orang_thir=$this->getRotaryNoGraduate($women_nogradute_orang_thir,'job_title',3);

                            if(null==$women_nogradute_nolevel_orang_thir){
                                break;
                            }else{
                                $women_orang_key_thir=array_column($women_nogradute_nolevel_orang_thir,'location_days','empNumber');
                           
                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }              
                            
                        }else if($compare_man_thir>-1 && $compare_gradaute_thir<0 && $compare_job_thir<0){//只要不是研究生，且不是中层，都可参与轮转

                            //获取非研究生的的列表
                            $nogradute_orang_thir=$this->getRotaryNoGraduate($thirDocEmp,'graduate',3);
                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($nogradute_orang_thir,'job_title',3);
                            
                            if(null==$nojobtitle_orang_thir){
                                break;
                            }else{
                                $nogradute_orang_key_thir=array_column($nojobtitle_orang_thir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_thir=$this->getMaxEmpNum($nogradute_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }

                        }else if($compare_man_thir<0 && $compare_gradaute_thir<0 && $compare_job_thir>-1){//只有女非研究生参与轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                            //再获取其中不适研究生的
                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'graduate',3);
                            
                            if(null==$nojobtitle_orang_thir){
                                break;
                            }else{
                                $nogradute_orang_key_thir=array_column($nojobtitle_orang_thir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_thir=$this->getMaxEmpNum($nogradute_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }

                        }else if($compare_man_thir<0 && $compare_gradaute_thir>-1 && $compare_job_thir<0){//只女非中层员工都可以轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'job_title',3);
                            if(null==$nojobtitle_orang_thir){
                                break;
                            }else{
                                $women_orang_key_thir=array_column($nojobtitle_orang_thir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                        
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }

                        }else if($compare_man_thir>-1 && $compare_gradaute_thir>-1 && $compare_job_thir<0){//只有非中层参与轮转
                            //获取部门中经验最多的女员工,
                           $nojobtitle_orang_thir=$this->getRotaryNoGraduate($thirDocEmp,'job_title',3);
                            if(null==$nojobtitle_orang_thir){
                                break;
                            }else{
                                $women_orang_key_thir=array_column($nojobtitle_orang_thir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                        
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }

                        }else if($compare_man_thir<0 && $compare_gradaute_thir>-1 && $compare_job_thir>-1){//只有女参与轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                            
                            if(null==$women_orang_thir){
                                break;
                            }else{
                                $women_orang_key_thir=array_column($women_orang_thir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                        
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }

                        }else{//如果该元满足轮转条件，直接参与轮转
                            $tmp_fir[$i]=$thirDocEmp[$i];
                            $tmp_fir[$i]['date_from']=$monarr[$i];
                            $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                            $rotary_department_three=$thirDocEmp[$i]['orange_department'];
                            unset($thirDocEmp[$i]);
                        }

                    }else if($thirDocEmp[$i]['gender']!=1 && $thirDocEmp[$i]['graduate']==3 && $thirDocEmp[$i]['job_title']==3){//非男,研究生,中层

                        if($compare_man_thir<0 && $compare_gradaute_thir<0 && $compare_job_thir<0){//只能选择经验最多的非研究生，并且是女员工

                     
                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                         
                            //经验最的的女性中的非研究生列表
                            $women_nogradute_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'graduate',3);
                            //获取非中中层
                            $women_nogradute_nolevel_orang_thir=$this->getRotaryNoGraduate($women_nogradute_orang_thir,'job_title',3);

                            if(null==$women_nogradute_nolevel_orang_thir){
                                break;
                            }else{
                                $women_orang_key_thir=array_column($women_nogradute_nolevel_orang_thir,'location_days','empNumber');
                           
                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }              
                            
                        }else if($compare_man_thir>-1 && $compare_gradaute_thir<0 && $compare_job_thir<0){//只要不是研究生，且不是中层，都可参与轮转

                            //获取非研究生的的列表
                            $nogradute_orang_thir=$this->getRotaryNoGraduate($thirDocEmp,'graduate',3);
                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($nogradute_orang_thir,'job_title',3);
                            
                            if(null==$nojobtitle_orang_thir){
                                break;
                            }else{
                                $nogradute_orang_key_thir=array_column($nojobtitle_orang_thir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_thir=$this->getMaxEmpNum($nogradute_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }

                        }else if($compare_man_thir<0 && $compare_gradaute_thir>-1 && $compare_job_thir<0){//只女非中层员工都可以轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'job_title',3);
                            if(null==$nojobtitle_orang_thir){
                                break;
                            }else{
                                $women_orang_key_thir=array_column($nojobtitle_orang_thir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                        
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }

                        }else if($compare_man_thir<0 && $compare_gradaute_thir<0 && $compare_job_thir>-1){//只有女非研究生参与轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                            //再获取其中不适研究生的
                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'graduate',3);
                            
                            if(null==$nojobtitle_orang_thir){
                                break;
                            }else{
                                $nogradute_orang_key_thir=array_column($nojobtitle_orang_thir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_thir=$this->getMaxEmpNum($nogradute_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }

                        }else if($compare_man_thir>-1 && $compare_gradaute_thir>-1 && $compare_job_thir<0){//只有非中层参与轮转
                            //获取部门中经验最多的女员工,
                           $nojobtitle_orang_thir=$this->getRotaryNoGraduate($thirDocEmp,'job_title',3);
                            if(null==$nojobtitle_orang_thir){
                                break;
                            }else{
                                $women_orang_key_thir=array_column($nojobtitle_orang_thir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                        
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }

                        }else if($compare_man_thir>-1 && $compare_gradaute_thir<0 && $compare_job_thir>-1){//只要不是中层，都可参与轮转

                            //获取非中层的的列表
                          
                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($thirDocEmp,'job_title',3);
                            
                            if(null==$nojobtitle_orang_thir){
                                break;
                            }else{
                                $nogradute_orang_key_thir=array_column($nojobtitle_orang_thir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_thir=$this->getMaxEmpNum($nogradute_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }

                        }else{//如果该元满足轮转条件，直接参与轮转
                            $tmp_fir[$i]=$thirDocEmp[$i];
                            $tmp_fir[$i]['date_from']=$monarr[$i];
                            $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                            $rotary_department_three=$thirDocEmp[$i]['orange_department'];
                            unset($thirDocEmp[$i]);
                        }

                    }else if($thirDocEmp[$i]['gender']!=1 && $thirDocEmp[$i]['graduate']==3 && $thirDocEmp[$i]['job_title']!==3){//非男,研究生,非中层

                        if($compare_man_thir<0 && $compare_gradaute_thir<0 && $compare_job_thir<0){//只能选择经验最多的非研究生，并且是女员工

                     
                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                         
                            //经验最的的女性中的非研究生列表
                            $women_nogradute_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'graduate',3);
                            //获取非中中层
                            $women_nogradute_nolevel_orang_thir=$this->getRotaryNoGraduate($women_nogradute_orang_thir,'job_title',3);

                            if(null==$women_nogradute_nolevel_orang_thir){
                                break;
                            }else{
                                $women_orang_key_thir=array_column($women_nogradute_nolevel_orang_thir,'location_days','empNumber');
                           
                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }              
                            
                        }else if($compare_man_thir<0 && $compare_gradaute_thir<0 && $compare_job_thir>-1){//只女非中层员工都可以轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'job_title',3);
                            if(null==$nojobtitle_orang_thir){
                                break;
                            }else{
                                $women_orang_key_thir=array_column($nojobtitle_orang_thir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                        
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }

                        }else if($compare_man_thir>-1 && $compare_gradaute_thir<0 && $compare_job_thir>-1){//只有女参与轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                            
                            if(null==$women_orang_thir){
                                break;
                            }else{
                                $women_orang_key_thir=array_column($women_orang_thir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                        
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }

                        }else if($compare_man_thir>-1 && $compare_gradaute_thir<0 && $compare_job_thir<0){//只要不是研究生，且不是中层，都可参与轮转

                            //获取非研究生的的列表
                            $nogradute_orang_thir=$this->getRotaryNoGraduate($thirDocEmp,'graduate',3);
                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($nogradute_orang_thir,'job_title',3);
                            
                            if(null==$nojobtitle_orang_thir){
                                break;
                            }else{
                                $nogradute_orang_key_thir=array_column($nojobtitle_orang_thir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_thir=$this->getMaxEmpNum($nogradute_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }

                        }else{//如果该元满足轮转条件，直接参与轮转
                            $tmp_fir[$i]=$thirDocEmp[$i];
                            $tmp_fir[$i]['date_from']=$monarr[$i];
                            $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                            $rotary_department_three=$thirDocEmp[$i]['orange_department'];
                            unset($thirDocEmp[$i]);
                        }

                    }else if($thirDocEmp[$i]['gender']!=1 && $thirDocEmp[$i]['graduate']!=3 && $thirDocEmp[$i]['job_title']==3){//非男，非研生,中层

                        if($compare_man_thir<0 && $compare_gradaute_thir<0 && $compare_job_thir<0){//只能选择经验最多的非研究生，并且是女员工,并且是中级员工

                            //获取部门中经验最多的女员工,
                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                         
                            //经验最的的女性中的非研究生列表
                            $women_nogradute_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'graduate',3);
                            //获取非中中层
                            $women_nogradute_nolevel_orang_thir=$this->getRotaryNoGraduate($women_nogradute_orang_thir,'job_title',3);



                            if(null==$women_nogradute_nolevel_orang_thir){
                                break;
                            }else{
                                $women_orang_key_thir=array_column($women_nogradute_nolevel_orang_thir,'location_days','empNumber');
                           
                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }        
                            
                        }else if($compare_man_thir<0 && $compare_gradaute_thir>-1 && $compare_job_thir<0){//只女非中层员工都可以轮转
                            //获取部门中经验最多的女员工,
                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'job_title',3);
                            if(null==$nojobtitle_orang_thir){
                                break;
                            }else{
                                $women_orang_key_thir=array_column($nojobtitle_orang_thir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                        
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }

                        }else if($compare_man_thir>-1 && $compare_gradaute_thir<0 && $compare_job_thir<0){//只要不是研究生，且不是中层，都可参与轮转

                            //获取非研究生的的列表
                            $nogradute_orang_thir=$this->getRotaryNoGraduate($thirDocEmp,'graduate',3);
                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($nogradute_orang_thir,'job_title',3);
                            
                            if(null==$nojobtitle_orang_thir){
                                break;
                            }else{
                                $nogradute_orang_key_thir=array_column($nojobtitle_orang_thir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_thir=$this->getMaxEmpNum($nogradute_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }

                        }else if($compare_man_thir>-1 && $compare_gradaute_thir>-1 && $compare_job_thir<0){//只有非中层参与轮转
                            //获取部门中经验最多的女员工,
                           $nojobtitle_orang_thir=$this->getRotaryNoGraduate($thirDocEmp,'job_title',3);
                            if(null==$nojobtitle_orang_thir){
                                break;
                            }else{
                                $women_orang_key_thir=array_column($nojobtitle_orang_thir,'location_days','empNumber');
                                 //获取经验最多的女员工emnumber
                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                        
                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                        
                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                unset($thirDocEmp[$em_key_thir]);
                            }

                        }else{//如果该元满足轮转条件，直接参与轮转
                            $tmp_fir[$i]=$thirDocEmp[$i];
                            $tmp_fir[$i]['date_from']=$monarr[$i];
                            $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                            $rotary_department_three=$thirDocEmp[$i]['orange_department'];
                            unset($thirDocEmp[$i]);
                        }

                    }else{
                        $tmp_fir[$i]=$thirDocEmp[$i];
                        $tmp_fir[$i]['date_from']=$monarr[$i];
                        $tmp_fir[$i]['rotary_department']=$rotary_department_one;
                        $rotary_department_three=$thirDocEmp[$i]['orange_department'];
                        unset($thirDocEmp[$i]);
                    }


                }else{
                    break;
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

