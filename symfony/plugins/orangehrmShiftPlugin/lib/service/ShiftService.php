<?php

class ShiftService extends BaseService {

    /**
     * @ignore
     */
  
    private $shiftDao;
    private $configurationService;
    private $_dir;
    const XML='.xml';

 
    public function getShiftDao() {
        if (is_null($this->shiftDao)) {
            $this->shiftDao = new ShiftDao();
        }
        return $this->shiftDao;
    }

    public function setShiftDao(ShiftDao $shiftDao){

        $this->ShiftDao=$ShiftDao;
    }
    
    /**
     * Set Configuration Service
     * @param ConfigService $configurationService
     * @return void
     * @ignore
     */
    public function setConfigurationService(ConfigService $configurationService) {
        $this->configurationService = $configurationService;
    }
    
    /**
     * Get Configuration Service
     * @return ConfigService
     * @ignore
     */
    public function getConfigurationService() {
        if($this->configurationService) {
            return $this->configurationService;
        } else {
            return new ConfigService();
        }
    }

    /**
     * Construct
     * @ignore
     */
    public function __construct() {
        $this->shiftDao = new ShiftDao();
    }

   public function getShifts($schedule_id) {

        return $this->getShiftDao()->getShifts($schedule_id);
    }
    public function getShiftList($schedule_id) {

        return $this->getShiftDao()->getShiftList($schedule_id);
    }
    public function getShiftDateList($schedule_id) {

        return $this->getShiftDao()->getShiftDateList($schedule_id);
    }

    public function getShiftDateById($dateId) {

        return $this->getShiftDao()->getShiftDateById($dateId);
    }


    public function getShiftTypeList($schedule_id) {

        return $this->getShiftDao()->getShiftTypeList($schedule_id);
    }

    public function getJobDepartmentList(){
        return $this->getShiftDao()->getJobDepartmentList();
    }
 

    public function getShiftTypeById($type_id) {

        return $this->getShiftDao()->getShiftTypeById($type_id);
    }
    public function getShiftAssignmentList($schedule_id) {

        return $this->getShiftDao()->getShiftAssignmentList($schedule_id);
    }
    public function getSkillList() {
        return $this->getShiftDao()->getSkillList();
    }

    public function getEmployeeList(){
        return $this->getShiftDao()->getEmployeeList();
    }
    public function getShiftById($shift_id){
        return $this->getShiftDao()->getShiftById($shift_id);
    }

    public function getShiftByDate($shiftDate){
        return $this->getShiftDao()->getShiftByDate($shiftDate);
    }

    public function getShiftByType($shiftType){
        return $this->getShiftDao()->getShiftByType($shiftType);
    }

    public function getEmployeeSkillList(){
        return $this->getShiftDao()->getEmployeeSkillList();
    }

    public function getShiftTypeToSkillList($schedule_id){
        return $this->getShiftDao()->getShiftTypeToSkillList($schedule_id);
    }
    
    public function getShiftRosters(){
        return $this->getShiftDao()->getShiftRosters();
    }

    public function getEmoloyeeLocation(){
        return $this->getShiftDao()->getEmoloyeeLocation();
    }

    public function getLeaderList(){
        return $this->getShiftDao()->getLeaderList();
    }

    
    
    /**
     * Check if user with given userId is an admin
     * @param string $userId
     * @return bool - True if given user is an admin, false if not
     * @ignore
     *
     * @todo Move method to Auth Service
     */
    public function isAdmin($userId) {
        return $this->getShiftDao()->isAdmin($userId);
    }

    public function saveShift(WorkShiftNew $shift) {
        return $this->getShiftDao()->saveShift($shift);
    }

    public function saveShiftRotary(WorkShiftRotary $shiftRotary) {
        
        return $this->getShiftDao()->saveShiftRotary($shiftRotary);
    }

    public function saveRotaryResult(WorkRotaryEmplayee $workRotary) {
        
        return $this->getShiftDao()->saveRotaryResult($workRotary);
    }

    public function saveWorkEntityIds(WorkShiftEntityIds $shiftEntity) {


        return $this->getShiftDao()->saveWorkEntityIds($shiftEntity);
    }

    public function saveShiftAssignments(WorkShiftAssignment $shiftAssignment) {
        return $this->getShiftDao()->saveShiftAssignments($shiftAssignment);
    }

    public function getShiftContranct($shift_id,$status) {

        return $this->getShiftDao()->getShiftContranct($shift_id,$status);
    }
    public function getRotaryEmpListById($rotary_id) {

        return $this->getShiftDao()->getRotaryEmpListById($rotary_id);
    }
    

    public function getEntityByName($schedule_id,$tag_name) {

        return $this->getShiftDao()->getEntityByName($schedule_id,$tag_name);
    }


    public function saveShiftContranct(WorkShiftContranct $shiftcontranct) {
  
        return $this->getShiftDao()->saveShiftContranct($shiftcontranct);
    }

    public function saveContranctType(WorkContranctType $contranct_type) {
  
        return $this->getShiftDao()->saveContranctType($contranct_type);
    }

    public function getContranctType($id,$status) {

        return $this->getShiftDao()->getContranctType($id,$status);
    }

    public function getContranctTypes($schedule_id,$status=null) {

        return $this->getShiftDao()->getContranctTypes($schedule_id,$status);
    }

    public function getShiftContrancts($schedule_id,$status=null) {

        return $this->getShiftDao()->getShiftContrancts($schedule_id,$status);
    }

   
     public function saveEmployeeWorkShiftCollection(Doctrine_Collection $empWorkShiftCollection) {
        return $this->getShiftDao()->saveEmployeeWorkShiftCollection($empWorkShiftCollection);
     }

    public function getShiftByTypeAndDate($shiftype,$shifDate) {


        return $this->getShiftDao()->getShiftByTypeAndDate($shiftype,$shifDate);
    }
    function xmlToArray($xml){ 
 
         //禁止引用外部xml实体 
         
        libxml_disable_entity_loader(true); 
         
        $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA); 
         
        $val = json_decode(json_encode($xmlstring),true); 
         
        return $val; 
         
        }


     /**
     * 将一个XML字符串解析成一个数组
     *
     * 如果需要将数组转换成XML字符串，可使用 `array_to_xml($arr)` 方法
     *
     * ** 特殊的key **
     *
     *  key            | 说明
     * ----------------|-------------------------------
     *  `@attributes`  | XML里所有的 attributes 都存放在 `@attributes` key里，可自定义参数 `$attribute_key` 修改，设置成true则和标签里的内容合并
     *  `@name`        | 循环数组XML的标签(tag)存放在 `@name` 的key里
     *  `@tdata`       | CDATA内容存放在 `@tdata` 的key里
     *  `@data`        | 如果本来的值是字符串，但是又有 attributes，则内容被转移至 `@data` 的key里
     *
     *
     *     print_r(xml_to_array('http://flash.weather.com.cn/wmaps/xml/china.xml'));
     *
     * @param string|SimpleXMLElement $xml_string XML字符串，支持http的XML路径，接受 SimpleXMLElement 对象
     * @param string $attribute_key attributes所使用的key，默认 @attributes，设置成 true 则和内容自动合并
     * @param int $max_recursion_depth 解析最高层次，默认25
     * @return array | false 失败则返回false
     */
    public function xml_to_array($xml_string, $attribute_key = '@attributes', $max_recursion_depth = 25)
    {
        if (is_string($xml_string))
        {
            if (preg_match('#^http(s)?://#i', $xml_string))
            {
                $xml_string = file_get_contents($xml_string);
            }
            $xml_object = simplexml_load_string($xml_string, 'SimpleXMLElement', LIBXML_NOCDATA);
        }
        elseif (is_object($xml_string) && $xml_string instanceof SimpleXMLElement)
        {
            $xml_object = $xml_string;
        }
        else
        {
            return false;
        }

        if (!$attribute_key)$attribute_key = '@attributes';
        if (null===$max_recursion_depth || false===$max_recursion_depth)$max_recursion_depth = 25;

        $format_attribute_value = function (& $tmp_value)
        {
            switch ($tmp_value)
            {
                case 'true':
                    $tmp_value = true;
                    break;
                case 'false':
                    $tmp_value = false;
                    break;
                case 'null':
                    $tmp_value = null;
                    break;
                default:
                    $tmp_value = trim($tmp_value);
            }
        };

        $exec_xml_to_array = function ($xml_object, $attribute_key, $recursion_depth, $max_recursion_depth) use(&$exec_xml_to_array, $format_attribute_value)
        {
            

            /**
             * @var $xml_object SimpleXMLElement
             * @var $value SimpleXMLElement
             */
            $rs = array
            (
                '@name' => $xml_object->getName(),
            );

            $attr = get_object_vars($xml_object->attributes());

            if ($attr)
            {
                foreach($attr['@attributes'] as &$tmp_value)
                {
                    $format_attribute_value($tmp_value);
                }
                unset($tmp_value);

                if (true===$attribute_key)
                {
                    # 合并到一起
                   $rs += $attr['@attributes'];
                }
                else
                {
                    $rs[$attribute_key] = $attr['@attributes'];
                }
            }
            $tdata = trim("$xml_object");
            if (strlen($tdata)>0)
            {
                $rs['@tdata'] = $tdata;
            }

            $xml_object_var = get_object_vars($xml_object);

            foreach($xml_object as $key => $value)
            {
                $obj_value = $xml_object_var[$key];

                $attr = null;
                if (is_object($value))
                {
                    $attr = get_object_vars($value->attributes());

                    if ($attr)
                    {
                        foreach($attr['@attributes'] as &$tmp_value)
                        {
                            $format_attribute_value($tmp_value);
                        }
                        unset($tmp_value);
                        $attr = $attr['@attributes'];
                    }
                }

                if (is_string($obj_value))
                {
                    $format_attribute_value($obj_value);

                    if ($attr)
                    {
                        if (true===$attribute_key)
                        {
                            # 合并到一起
                           $rs[$key] = $attr + array('@data' => $obj_value);
                        }
                        else
                        {
                            $rs[$key] = array
                            (
                                $attribute_key => $attr,
                                '@data'        => $obj_value,
                            );
                        }
                    }
                    else
                    {
                        $rs[$key] = $obj_value;
                    }
                }
                else
                {
                    if (is_array($obj_value))
                    {
                        if ($recursion_depth>0)unset($rs['@name']);
                        $rs[] = $exec_xml_to_array($value, $attribute_key, $recursion_depth+1, $max_recursion_depth);
                    }
                    else
                    {
                        $rs[$key] = $exec_xml_to_array($value, $attribute_key, $recursion_depth+1, $max_recursion_depth);
                        if (is_array($rs[$key]) && !isset($rs[$key][0]))
                        {
                            unset($rs[$key]['@name']);
                        }
                    }
                }
            }

            return $rs;
        };

        return $exec_xml_to_array($xml_object, $attribute_key, 0, $max_recursion_depth);
    }



    /**
     * 将数组转换成XML字符串
     *
     * 本方法的反向方法为 `xml_to_array($xml_string)`
     *
     *     // 返回格式化好的XML字符串
     *     array_to_xml($arr);
     *
     *     // XML缩进使用4个空格
     *     array_to_xml($arr, '    ');
     *
     *     // 返回不带任何换行符、空格的XML
     *     array_to_xml($arr, '', '');
     *
     * @param array $array 数组
     * @param string $tab 缩进字符，默认 tab 符
     * @param string $crlf 换行符，默认window换行符
     * @param string $attribute_key XML的attributes所在key，默认 `@attributes`
     * @param string $xml_header_string XML第一行声明的字符串
     * @return string
     */
    public function array_to_xml(array $array, $tab = "\t", $crlf = "\r\n", $attribute_key = '@attributes', $xml_header_string = null)
    {

        if (!$xml_header_string)
        {
            $w = '?';
            $xml_header_string = '<'. $w . 'xml version="1.0" encoding="UTF-8"'. $w .'>';
        }

        $format_attribute_value = function (& $value)
        {
            if (true===$value)
            {
                $value = 'true';
            }
            elseif (false===$value)
            {
                $value = 'false';
            }
            elseif (null===$value)
            {
                $value = 'null';
            }
        };

        $format_to_xml_string = function($array, $attribute_key, $crlf, $tab, $left_str = '') use(&$format_to_xml_string, $format_attribute_value)
        {
            $str = '';

            if (isset($array['@name']))
            {
                $str .= "{$crlf}{$left_str}<{$array['@name']}";

                if (isset($array[$attribute_key]))
                {
                    foreach($array[$attribute_key] as $k=>$v)
                    {
                        $format_attribute_value($v);
                        $str .= " $k=\"{$v}\"";
                    }
                }

                $str .= ">";

                $close_str = "{$crlf}{$left_str}</{$array['@name']}>";

                $left_str .= $tab;
            }
            else
            {
                $close_str = '';
            }

            if (isset($array['@tdata']))
            {
                $str .= "<![CDATA[{$array['@tdata']}]]></{$array['@name']}>";
            }
            else
            {
                $have_str = false;
                foreach($array as $key => $value)
                {
                    if ($key === '@name' || $key === $attribute_key || $key === '@data' || $key === '@tdata')continue;

                    $have_str = true;

                    if (is_array($value))
                    {
                        if (!is_numeric($key))
                        {
                            $str .= "{$crlf}{$left_str}<{$key}";

                            if (isset($value[$attribute_key]))
                            {
                                foreach($value[$attribute_key] as $k=>$v)
                                {
                                    $str .= " $k=\"{$v}\"";
                                }
                            }

                            if (isset($value['@data']))
                            {
                                if(empty($value['@data'])){
                                    $str .= "/>";

                                }else{
                                    $format_attribute_value($value['@data']);
                                    $str .= ">";
                                    $str .= "{$value['@data']}";
                                    $str .= "</{$key}>";
                                }
                                

                            }

                            elseif (isset($value['@tdata']))
                            {
                                $str .= ">";
                                $str .= "<![CDATA[{$value['@tdata']}]]>";
                                $str .= "</{$key}>";
                            }
                            else
                            {
                                $str .= ">";
                                $tmp_str = $format_to_xml_string($value, $attribute_key, $crlf, $tab, $left_str . $tab);
                                if (''!==$tmp_str)
                                {
                                    $str .= $tmp_str."{$crlf}{$left_str}";
                                    $str .= "</{$key}>";
                                }
                                unset($tmp_str);
                            }

                            
                        }
                        else
                        {
                            $str .= $format_to_xml_string($value, $attribute_key, $crlf, $tab, $left_str);
                        }
                    }
                    else
                    {
                        $format_attribute_value($value);
                        $str .= "{$crlf}{$left_str}<{$key}>{$value}</{$key}>";
                    }
                }

                if ($have_str)
                {
                    if ($close_str)
                    {
                        $str .= $close_str;
                    }
                }
                else
                {
                    $str .= "</{$array['@name']}>";
                }
            }
            return $str;
        };


        return $xml_header_string . $format_to_xml_string($array, $attribute_key, $crlf, $tab, '');
    }


    public function cacheData($key,$value='',$path=''){
        
        $this->_dir=dirname(__FILE__).'/files/';

        $filename=$this->_dir.$path.$key.self::XML;
       
        
        if($value!==''){//写入缓存
            //首先判断目录是否存在，如果不存在，创建目录
            $dir=dirname($filename);
            if(!is_dir($dir)){
                mkdir($dir,0777);
            }
            //写入文件
             file_put_contents($filename, $value);
        }
        //读取缓存文件
        if(!is_file($filename)){
            return false;
        }else{
            return file_get_contents($filename);
        }
      
    }

    public function saveFile($key,$value='',$path=''){

        $this->_dir=dirname(__FILE__).'/files/';

        $filename=$this->_dir.$path.$key.self::XML;
       
        
        if($value!==''){//写入缓存
            //首先判断目录是否存在，如果不存在，创建目录
            $dir=dirname($filename);
            if(!is_dir($dir)){
                mkdir($dir,0777);
            }
            //写入文件
             file_put_contents($filename, json_encode($value));
        }


        //读取缓存文件
        if(!is_file($filename)){
            return false;
        }else{
      
            return json_decode(file_get_contents($filename),true);
        }
      
    }

    public function getFormatDate($date){
            $temp=explode(':', $date);
            $copy_day=$temp[0];
            $arr['y']=date('Y',strtotime($copy_day));
            $arr['m']=date('n',strtotime($copy_day));
            $arr['d']=date('j',strtotime($copy_day));
            $arr['hm']=date('H:m',strtotime($copy_day));
            // echo'<pre>';var_dump($arr);exit;
            return $arr;
    }

    public function birthday($birthday){
      list($year,$month,$day) = explode("-",$birthday);
      $year_diff = date("Y") - $year;
      $month_diff = date("m") - $month;
      $day_diff  = date("d") - $day;
      if ($day_diff < 0 || $month_diff < 0)
       $year_diff--;
      return $year_diff;
    }

    public function getShiftsByDate($scheduleID){

        $tcm_pharmacy=array();

        $shifts=$this->getShiftList($scheduleID);
        $shiftDates=$this->getShiftDateList($scheduleID);
        $shiftTypes=$this->getShiftTypeList($scheduleID);



        $shiftAssignments=$this->getShiftTypeList($scheduleID);

    

        $shifts = array_column($shifts, NULL, 'id');

        $shiftTypes = array_column($shiftTypes, NULL, 'id');

        $shiftDates = array_column($shiftDates, NULL, 'id');


        foreach ($shiftDates as $key => $date) {

            foreach ($shifts as $k => $shift) {

                if($shift['schedule_id']==$date['scheduleId']&&$shift['shiftdate_id']==$date['id']){
                    $shift['shift_type']=$shiftTypes[$shift['shift_type_id']];
                
                    $date['shift_list'][]=$shift;
                }
            }
            $date_format[]=$date;
            
        }

        return $date_format;
       
    }

    //最终结果拼接格式化数据
    public function createXml($scheduleID){


        
        $tcm_pharmacy=array();

        $shifts=$this->getShiftList($scheduleID);
        $shiftDates=$this->getShiftDateList($scheduleID);
        $shiftTypes=$this->getShiftTypeList($scheduleID);
        $shiftAssignments=$this->getShiftAssignmentList($scheduleID);

        //读取约束文件
        $filename='patternContranct_'.$scheduleID;
        $patternList=$this->saveFile($filename);

    
        

        $skillList=$this->getSkillList();
        $employeeList=$this->getEmployeeList();

        $employeeSkillList=$this->getEmployeeSkillList();
        $shiftTypeToSkillList=$this->getShiftTypeToSkillList($scheduleID);
        // echo'<pre>';var_dump($shiftTypeToSkillList);exit;


        // $shifts = array_column($shifts, NULL, 'id');
        $shiftTypes = array_column($shiftTypes, NULL, 'id');
        $shiftDatesByIndex = array_column($shiftDates, NULL, 'id');


        $xml_name="roster_".$scheduleID;
        $k=1;
        $tcm_pharmacy['@name']="NurseRoster";
        $tcm_pharmacy['@attributes']['id']=$k;
        $tcm_pharmacy['id']=1;
        $tcm_pharmacy['code']=$xml_name;
        $tcm_pharmacy['nurseRosterParametrization']['@attributes']['id']=$k+1;
        $tcm_pharmacy['nurseRosterParametrization']['id']='0';//全局变量

        $index=$k+2;
        $last_index=count($shiftDates)-1;
        

        $shiftTypeIndex=array();
        $shiftDateIndex=array();


        $dayIndex=0;

        //只是得到第一天和最后一天信息，同时将创建的shiftTypeId 和shiftDateID存储起来
        foreach ($shiftDates as $key => $shiftDate) {
            //如果是第一天
            if($key==0){
                $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['@attributes']['id']=$index;
                $firstDateIndex=$index;

                $shiftDateIndex[$shiftDate['id']]=$index;

                $date_format=$this->getFormatDate($shiftDate['shiftDate']);
                
                $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['id']=$dayIndex;
                $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['dayIndex']=$dayIndex;
                $index++;
                $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['date']['@attributes']['id']=$index;
                $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['date']['@attributes']['resolves-to']='java.time.Ser';

                $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['date'][]['byte']='3';
                $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['date']['int']=$date_format['y'];
                $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['date'][]['byte']=$date_format['m'];
                $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['date'][]['byte']=$date_format['d'];
                $index++;
                $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['shiftList']['@attributes']['id']=$index;


                foreach($shiftDate['shiftList'] as $k=>$shift){


                    $shiftType=$this->getShiftTypeById($shift['shift_type_id']);
     
                    $index++;

                    $shiftListIndex[$shift['id']]=$index;

                    $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['shiftList'][$k]['Shift']['@attributes']['id']=$index;

                    $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['shiftList'][$k]['Shift']['id']=$shift['id'];
                    $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['shiftList'][$k]['Shift']['shiftDate']['@attributes']['reference']=$firstDateIndex;
                    $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['shiftList'][$k]['Shift']['shiftDate']['@data']='';

                    $index++;

                    //记录shiftTypeID
                    $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['shiftList'][$k]['Shift']['shiftType']['@attributes']['id']=$index;
                    $shiftTypeIndex[$shiftType->id]=$index;
                    $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['shiftList'][$k]['Shift']['shiftType']['id']=$shiftType->id;
                    $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['shiftList'][$k]['Shift']['shiftType']['code']=$shiftType->id;
                    $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['shiftList'][$k]['Shift']['shiftType']['index']=$shiftType->id;
                    $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['shiftList'][$k]['Shift']['shiftType']['startTimeString']=$shiftType->start_time;
                    $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['shiftList'][$k]['Shift']['shiftType']['endTimeString']=$shiftType->end_time;
                    $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['shiftList'][$k]['Shift']['shiftType']['night']='false';
                    $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['shiftList'][$k]['Shift']['shiftType']['description']=$index;

                    $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['shiftList'][$k]['Shift']['index']=$shift['id'];
                    $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['shiftList'][$k]['Shift']['requiredEmployeeSize']=$shift['required_employee'];

                }


            }

   

            if($key==$last_index){
                $index++;

                $lastDateIndex=$index;

                $date_format=$this->getFormatDate($shiftDate['shiftDate']);

                $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['@attributes']['id']=$index;

                $shiftDateIndex[$shiftDate['id']]=$index;
                
                $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['id']=count($shiftDates)-1;
                $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['dayIndex']=count($shiftDates)-1;
                $index++;
                $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['date']['@attributes']['id']=$index;
                $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['date']['@attributes']['resolves-to']='java.time.Ser';
                $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['date'][]['byte']='3';
                $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['date']['int']=$date_format['y'];
                $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['date'][]['byte']=$date_format['m'];
                $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['date'][]['byte']=$date_format['d'];
                $index++;
                $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['shiftList']['@attributes']['id']=$index;

                foreach($shiftDates[$last_index]['shiftList'] as $k=>$shift){
           
                    $shiftType=$this->getShiftTypeById($shift['shift_type_id']);
   
                    $index++;

                    $shiftListIndex[$shift['id']]=$index;

                     $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['shiftList'][$k]['Shift']['@attributes']['id']=$index;
                  
                    $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['shiftList'][$k]['Shift']['id']=$shift['id'];
                    $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['shiftList'][$k]['Shift']['shiftDate']['@attributes']['reference']=$lastDateIndex;
                    $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['shiftList'][$k]['Shift']['shiftDate']['@data']='';


                    //记录shiftTypeID
                    $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['shiftList'][$k]['Shift']['shiftType']['@attributes']['reference']=$shiftTypeIndex[$shiftType->id];
                    $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['shiftList'][$k]['Shift']['shiftType']['@data']='';
                    

                    $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['shiftList'][$k]['Shift']['index']=$shift['id'];;
                    $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['shiftList'][$k]['Shift']['requiredEmployeeSize']=$shift['required_employee'];;

                }

            }

        }


        $tcm_pharmacy['nurseRosterParametrization']['planningWindowStart']['@attributes']['reference']=$firstDateIndex;
        $tcm_pharmacy['nurseRosterParametrization']['planningWindowStart']['@data']='';



        
        $index++;
        $tcm_pharmacy['skillList']['@attributes']['id']=$index;

        $index++;
        $tcm_pharmacy['shiftTypeList']['@attributes']['id']=$index;

        $index++;
        $tcm_pharmacy['shiftTypeSkillRequirementList']['@attributes']['id']=$index;



        //值班人员

        //持续上某一个班
        $index++;
        $tcm_pharmacy['patternList']['@attributes']['id']=$index;

        foreach ($patternList['assignmentAfterShift'] as $key => $assignmentAfterShift) {

            $shiftType=$assignmentAfterShift['assignmentAfterShiftSelect'];

            $index++;
            $tcm_pharmacy['patternList']['IdenticalShiftLastSomeDaysPattern']['@attributes']['id']=$index;
            $patternIndex['LastSomeDaysPattern']=$index;
            $tcm_pharmacy['patternList']['IdenticalShiftLastSomeDaysPattern']['id']='0';
            $tcm_pharmacy['patternList']['IdenticalShiftLastSomeDaysPattern']['code']='0';
            $tcm_pharmacy['patternList']['IdenticalShiftLastSomeDaysPattern']['weight']=$assignmentAfterShift['assignmentAfterShiftWeight'];
            $tcm_pharmacy['patternList']['IdenticalShiftLastSomeDaysPattern']['dayShiftType']['@attributes']['reference']=$shiftTypeIndex[$shiftType];
            $tcm_pharmacy['patternList']['IdenticalShiftLastSomeDaysPattern']['dayShiftType']['@data']='';
            $tcm_pharmacy['patternList']['IdenticalShiftLastSomeDaysPattern']['ShiftLastLength']=$assignmentAfterShift['assignmentAfterShiftDays'];
            
        }

        //班次只分配给男性
        foreach ($patternList['shiftdOnlyforMan'] as $key => $shiftdOnlyforMan) {

            $onlyforManShiftType=$shiftdOnlyforMan['shiftdOnlyforManShiftSelect'];

            $index++;
            $tcm_pharmacy['patternList']['ShiftAssignedOnlyforManPattern']['@attributes']['id']= $index;
            $patternIndex['AssignedOnlyforMan']=$index;
            $tcm_pharmacy['patternList']['ShiftAssignedOnlyforManPattern']['id']='1';
            $tcm_pharmacy['patternList']['ShiftAssignedOnlyforManPattern']['code']='1';
            $tcm_pharmacy['patternList']['ShiftAssignedOnlyforManPattern']['weight']=$shiftdOnlyforMan['shiftdOnlyforManWeight'];
            $tcm_pharmacy['patternList']['ShiftAssignedOnlyforManPattern']['dayShiftType']['@attributes']['reference']=$shiftTypeIndex[$onlyforManShiftType];
            $tcm_pharmacy['patternList']['ShiftAssignedOnlyforManPattern']['dayShiftType']['@data']='';
        }

        //周六工作在周二或周四安排调休
      
        $index++;
        $tcm_pharmacy['patternList']['FreeAfterWeekendWorkDayPattern']['@attributes']['id']= $index;
        $patternIndex['FreeAfterWeekendWorkDayPattern']=$index;
        $tcm_pharmacy['patternList']['FreeAfterWeekendWorkDayPattern']['id']='2';
        $tcm_pharmacy['patternList']['FreeAfterWeekendWorkDayPattern']['code']='2';
        $tcm_pharmacy['patternList']['FreeAfterWeekendWorkDayPattern']['weight']=$patternList['restOnTuOrTues']["restOnTuOrTuesWeight"];
        $tcm_pharmacy['patternList']['FreeAfterWeekendWorkDayPattern']['workDayOfWeek']='SATURDAY';
    
// echo '<pre>';var_dump($patternList['nightAfterNightLeisureShift']);exit;
        //夜班后夜休息
        
            $nightAfterNightLeisureShift=$patternList['nightAfterNightLeisureShift'];
            $nightAfterNightShiftType=$nightAfterNightLeisureShift['nightAfterNightLeisureShiftSelect'];
            $index++;
            $tcm_pharmacy['patternList']['FreeAfterANightShiftPattern']['@attributes']['id']= $index;
            $patternIndex['FreeAfterANightShiftPattern']=$index;

            $tcm_pharmacy['patternList']['FreeAfterANightShiftPattern']['id']='3';
            $tcm_pharmacy['patternList']['FreeAfterANightShiftPattern']['code']='3';
            $tcm_pharmacy['patternList']['FreeAfterANightShiftPattern']['weight']=$nightAfterNightLeisureShift['nightAfterNightLeisureWeight'];

            $tcm_pharmacy['patternList']['FreeAfterANightShiftPattern']['dayShiftType']['@attributes']['reference']=$shiftTypeIndex[$nightAfterNightShiftType];
            $tcm_pharmacy['patternList']['FreeAfterANightShiftPattern']['dayShiftType']['@data']='';


        //班次平均分配
        foreach ($patternList['averageAssignment'] as $key => $averageAssignment) {
 
            $averageAssignmentShiftType=$averageAssignment['averageAssignmentShiftSelect'];
            $index++;
            $tcm_pharmacy['patternList']['ShiftAssignedAveragedAtAllEmployeesPattern']['@attributes']['id']= $index;
            $patternIndex['ShiftAssignedAveragedAtAllEmployeesPattern']=$index;
            $tcm_pharmacy['patternList']['ShiftAssignedAveragedAtAllEmployeesPattern']['id']='4';
            $tcm_pharmacy['patternList']['ShiftAssignedAveragedAtAllEmployeesPattern']['code']='4';
            $tcm_pharmacy['patternList']['ShiftAssignedAveragedAtAllEmployeesPattern']['weight']=$averageAssignment['averageAssignmentWeight'];
            $tcm_pharmacy['patternList']['ShiftAssignedAveragedAtAllEmployeesPattern']['dayShiftType']['@attributes']['reference']=$shiftTypeIndex[$averageAssignmentShiftType];
            $tcm_pharmacy['patternList']['ShiftAssignedAveragedAtAllEmployeesPattern']['dayShiftType']['@data']='';
            $tcm_pharmacy['patternList']['ShiftAssignedAveragedAtAllEmployeesPattern']['dayShiftLength']=$averageAssignment['averageAssignment'];
        }
        

        // 每周公休分配
        $index++;
        $tcm_pharmacy['patternList']['FreeTwoDaysEveryWeekPattern']['@attributes']['id']= $index;
        $patternIndex['FreeTwoDaysEveryWeekPattern']=$index;
        $tcm_pharmacy['patternList']['FreeTwoDaysEveryWeekPattern']['id']='5';
        $tcm_pharmacy['patternList']['FreeTwoDaysEveryWeekPattern']['code']='5';
        $tcm_pharmacy['patternList']['FreeTwoDaysEveryWeekPattern']['weight']=$patternList['freeTwoDays']["freeTwoDaysWeight"];
        $tcm_pharmacy['patternList']['FreeTwoDaysEveryWeekPattern']['workDayLength']='7';
        $tcm_pharmacy['patternList']['FreeTwoDaysEveryWeekPattern']['freeDayLength']=$patternList['freeTwoDays']["freeTwoDaysSelect"];


        
        // 该班次分配后间隔后再分配
        foreach ($patternList['assignmentAfterInterval'] as $key => $assignmentAfterInterval) {
            $AfteInteShiftType=$assignmentAfterInterval['assignmentAfterIntervalShiftSelect'];
            $index++;
            $tcm_pharmacy['patternList']['ShiftAssignedSomeWeeksPattern']['@attributes']['id']= $index;
            $patternIndex['ShiftAssignedSomeWeeksPattern']=$index;
            $tcm_pharmacy['patternList']['ShiftAssignedSomeWeeksPattern']['id']='6';
            $tcm_pharmacy['patternList']['ShiftAssignedSomeWeeksPattern']['code']='6';
            $tcm_pharmacy['patternList']['ShiftAssignedSomeWeeksPattern']['weight']=$assignmentAfterInterval['assignmentAfterIntervalWeight'];
            $tcm_pharmacy['patternList']['ShiftAssignedSomeWeeksPattern']['dayShiftType']['@attributes']['reference']=$shiftTypeIndex[$AfteInteShiftType];
            $tcm_pharmacy['patternList']['ShiftAssignedSomeWeeksPattern']['dayShiftType']['@data']='';
            $tcm_pharmacy['patternList']['ShiftAssignedSomeWeeksPattern']['weekGapLength']=$assignmentAfterInterval['assignmentAfterIntervalEmployee'];
        }
        


      /*
            $index++;
           // echo'<pre>'; var_dump($restAfterOneShift);exit;
            $index0ShiftType=$restAfterOneShift['startShiftSelect'];
            $index1ShiftType=$restAfterOneShift['nextShiftSelect'];
            $tcm_pharmacy['patternList'][1]['ShiftType2DaysPattern']['@attributes']['id']= $index;
            $patternIndex['ShiftType2DaysPattern1']=$index;
            $tcm_pharmacy['patternList'][1]['ShiftType2DaysPattern']['id']='7';
            $tcm_pharmacy['patternList'][1]['ShiftType2DaysPattern']['code']='7';
            $tcm_pharmacy['patternList'][1]['ShiftType2DaysPattern']['weight']='0';
            $tcm_pharmacy['patternList'][1]['ShiftType2DaysPattern']['dayIndex0ShiftType']['@attributes']['reference']=$shiftTypeIndex[$index0ShiftType];
            $tcm_pharmacy['patternList'][1]['ShiftType2DaysPattern']['dayIndex0ShiftType']['@data']='';
            $tcm_pharmacy['patternList'][1]['ShiftType2DaysPattern']['dayIndex1ShiftType']['@attributes']['reference']=$shiftTypeIndex[$index1ShiftType];
            $tcm_pharmacy['patternList'][1]['ShiftType2DaysPattern']['dayIndex1ShiftType']['@data']='';


            $index++;
            $tcm_pharmacy['patternList'][2]['ShiftType2DaysPattern']['@attributes']['id']= $index;
            $patternIndex['ShiftType2DaysPattern1']=$index;
            $tcm_pharmacy['patternList'][2]['ShiftType2DaysPattern']['id']='8';
            $tcm_pharmacy['patternList'][2]['ShiftType2DaysPattern']['code']='8';
            $tcm_pharmacy['patternList'][2]['ShiftType2DaysPattern']['weight']=$restAfterOneShift['restAfterOneShiftWeight'];
            $tcm_pharmacy['patternList'][2]['ShiftType2DaysPattern']['dayIndex0ShiftType']['@attributes']['reference']=$shiftTypeIndex[$index0ShiftType];
            $tcm_pharmacy['patternList'][2]['ShiftType2DaysPattern']['dayIndex0ShiftType']['@data']='';
            $tcm_pharmacy['patternList'][2]['ShiftType2DaysPattern']['dayIndex1ShiftType']['@attributes']['reference']=$shiftTypeIndex[$index1ShiftType];
            $tcm_pharmacy['patternList'][2]['ShiftType2DaysPattern']['dayIndex1ShiftType']['@data']='';*/
     


        //contractList
       //第一个contranct
        $index++;
        $tcm_pharmacy['contractList']['@attributes']['id']=$index;


        $index++;
        $tcm_pharmacy['contractList'][1]['Contract']['@attributes']['id']=$index;

        $contractIndex[1]=$index;

        $tcm_pharmacy['contractList'][1]['Contract']['id']='0';
        $tcm_pharmacy['contractList'][1]['Contract']['code']='0';
        $tcm_pharmacy['contractList'][1]['Contract']['description']='fulltime';
        $tcm_pharmacy['contractList'][1]['Contract']['weekendDefinition']='SATURDAY_SUNDAY';


        $index++;
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList']['@attributes']['id']=$index;


        $index++;
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][0]['BooleanContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][0]['BooleanContractLine']['id']='0';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][0]['BooleanContractLine']['contract']['@attributes']['reference']=$contractIndex[1];
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][0]['BooleanContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][0]['BooleanContractLine']['contractLineType']='SINGLE_ASSIGNMENT_PER_DAY';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][0]['BooleanContractLine']['enabled']='true';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][0]['BooleanContractLine']['weight']='1';
        $booleanContractIndex[$index]=$index;


        
        $index++;
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][1]['MinMaxContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][1]['MinMaxContractLine']['id']='1';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][1]['MinMaxContractLine']['contract']['@attributes']['reference']=$contractIndex[1];
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][1]['MinMaxContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][1]['MinMaxContractLine']['contractLineType']='TOTAL_ASSIGNMENTS';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][1]['MinMaxContractLine']['minimumEnabled']='true';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][1]['MinMaxContractLine']['minimumValue']='10';  
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][1]['MinMaxContractLine']['minimumWeight']='1';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][1]['MinMaxContractLine']['maximumEnabled']='true';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][1]['MinMaxContractLine']['maximumValue']='20';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][1]['MinMaxContractLine']['maximumWeight']='1';
        $MinMaxContractIndex[$index]=$index;

        
        $index++;
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][2]['MinMaxContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][2]['MinMaxContractLine']['id']='2';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][2]['MinMaxContractLine']['contract']['@attributes']['reference']=$contractIndex[1];
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][2]['MinMaxContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][2]['MinMaxContractLine']['contractLineType']='CONSECUTIVE_WORKING_DAYS';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][2]['MinMaxContractLine']['minimumEnabled']='true';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][2]['MinMaxContractLine']['minimumValue']='3';  
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][2]['MinMaxContractLine']['minimumWeight']='1';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][2]['MinMaxContractLine']['maximumEnabled']='true';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][2]['MinMaxContractLine']['maximumValue']='5';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][2]['MinMaxContractLine']['maximumWeight']='1';
        $MinMaxContractIndex[$index]=$index;



        $index++;
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][3]['MinMaxContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][3]['MinMaxContractLine']['id']='3';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][3]['MinMaxContractLine']['contract']['@attributes']['reference']=$contractIndex[1];
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][3]['MinMaxContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][3]['MinMaxContractLine']['contractLineType']='CONSECUTIVE_FREE_DAYS';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][3]['MinMaxContractLine']['minimumEnabled']='true';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][3]['MinMaxContractLine']['minimumValue']='3';  
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][3]['MinMaxContractLine']['minimumWeight']='1';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][3]['MinMaxContractLine']['maximumEnabled']='true';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][3]['MinMaxContractLine']['maximumValue']='4';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][3]['MinMaxContractLine']['maximumWeight']='1';
        $MinMaxContractIndex[$index]=$index;


        //连续工作几个周末
         
        $index++;
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][4]['MinMaxContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][4]['MinMaxContractLine']['id']='4';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][4]['MinMaxContractLine']['contract']['@attributes']['reference']=$contractIndex[1];
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][4]['MinMaxContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][4]['MinMaxContractLine']['contractLineType']='CONSECUTIVE_WORKING_WEEKENDS';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][4]['MinMaxContractLine']['minimumEnabled']='true';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][4]['MinMaxContractLine']['minimumValue']=$patternList['minWorkWeekendNum']['minWorkWeekendCount'];  
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][4]['MinMaxContractLine']['minimumWeight']='1';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][4]['MinMaxContractLine']['maximumEnabled']='true';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][4]['MinMaxContractLine']['maximumValue']=$patternList['maxWeekendShift']["allowWeekendShift"];
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][4]['MinMaxContractLine']['maximumWeight']=$patternList['maxWeekendShift']["maxWeekendShiftWeight"];
        $MinMaxContractIndex[$index]=$index;


        $index++;

        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][5]['BooleanContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][5]['BooleanContractLine']['id']='5';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][5]['BooleanContractLine']['contract']['@attributes']['reference']=$contractIndex[1];
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][5]['BooleanContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][5]['BooleanContractLine']['contractLineType']='COMPLETE_WEEKENDS';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][5]['BooleanContractLine']['enabled']='true';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][5]['BooleanContractLine']['weight']='1';
        $booleanContractIndex[$index]=$index;


        $index++;

        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][6]['BooleanContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][6]['BooleanContractLine']['id']='6';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][6]['BooleanContractLine']['contract']['@attributes']['reference']=$contractIndex[1];
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][6]['BooleanContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][6]['BooleanContractLine']['contractLineType']='IDENTICAL_SHIFT_TYPES_DURING_WEEKEND';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][6]['BooleanContractLine']['enabled']='true';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][6]['BooleanContractLine']['weight']='1';
        $booleanContractIndex[$index]=$index;


        $index++;

        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][8]['BooleanContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][8]['BooleanContractLine']['id']='7';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][8]['BooleanContractLine']['contract']['@attributes']['reference']=$contractIndex[1];
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][8]['BooleanContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][8]['BooleanContractLine']['contractLineType']='NO_NIGHT_SHIFT_BEFORE_FREE_WEEKEND';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][8]['BooleanContractLine']['enabled']='true';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][8]['BooleanContractLine']['weight']='1';
        $booleanContractIndex[$index]=$index;

        $index++;

        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][9]['BooleanContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][9]['BooleanContractLine']['id']='8';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][9]['BooleanContractLine']['contract']['@attributes']['reference']=$contractIndex[1];
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][9]['BooleanContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][9]['BooleanContractLine']['contractLineType']='ALTERNATIVE_SKILL_CATEGORY';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][9]['BooleanContractLine']['enabled']='true';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][9]['BooleanContractLine']['weight']='1';

        $booleanContractIndex[$index]=$index;

        $index++;
        $tcm_pharmacy['contractList'][2]['Contract']['@attributes']['id']=$index;

        $contractIndex[2]=$index;

        $tcm_pharmacy['contractList'][2]['Contract']['id']='1';
        $tcm_pharmacy['contractList'][2]['Contract']['code']='1';
        $tcm_pharmacy['contractList'][2]['Contract']['description']='75_time';
        $tcm_pharmacy['contractList'][2]['Contract']['weekendDefinition']='SATURDAY_SUNDAY';

        $index++;
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList']['@attributes']['id']=$index;



        $index++;
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][0]['BooleanContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][0]['BooleanContractLine']['id']='9';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][0]['BooleanContractLine']['contract']['@attributes']['reference']=$contractIndex[2];
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][0]['BooleanContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][0]['BooleanContractLine']['contractLineType']='SINGLE_ASSIGNMENT_PER_DAY';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][0]['BooleanContractLine']['enabled']='true';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][0]['BooleanContractLine']['weight']='1';
        $booleanContractIndex[$index]=$index;


        $index++;
  
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][1]['MinMaxContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][1]['MinMaxContractLine']['id']='10';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][1]['MinMaxContractLine']['contract']['@attributes']['reference']=$contractIndex[2];
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][1]['MinMaxContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][1]['MinMaxContractLine']['contractLineType']='TOTAL_ASSIGNMENTS';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][1]['MinMaxContractLine']['minimumEnabled']='true';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][1]['MinMaxContractLine']['minimumValue']='6';  
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][1]['MinMaxContractLine']['minimumWeight']='1';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][1]['MinMaxContractLine']['maximumEnabled']='true';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][1]['MinMaxContractLine']['maximumValue']='15';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][1]['MinMaxContractLine']['maximumWeight']='1';
        $MinMaxContractIndex[$index]=$index;

        
        $index++;
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][2]['MinMaxContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][2]['MinMaxContractLine']['id']='11';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][2]['MinMaxContractLine']['contract']['@attributes']['reference']=$contractIndex[2];
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][2]['MinMaxContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][2]['MinMaxContractLine']['contractLineType']='CONSECUTIVE_WORKING_DAYS';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][2]['MinMaxContractLine']['minimumEnabled']='true';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][2]['MinMaxContractLine']['minimumValue']='3';  
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][2]['MinMaxContractLine']['minimumWeight']='1';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][2]['MinMaxContractLine']['maximumEnabled']='true';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][2]['MinMaxContractLine']['maximumValue']='5';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][2]['MinMaxContractLine']['maximumWeight']='1';
        $MinMaxContractIndex[$index]=$index;

        
        $index++;
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][3]['MinMaxContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][3]['MinMaxContractLine']['id']='12';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][3]['MinMaxContractLine']['contract']['@attributes']['reference']=$contractIndex[2];
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][3]['MinMaxContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][3]['MinMaxContractLine']['contractLineType']='CONSECUTIVE_FREE_DAYS';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][3]['MinMaxContractLine']['minimumEnabled']='true';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][3]['MinMaxContractLine']['minimumValue']='2';  
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][3]['MinMaxContractLine']['minimumWeight']='1';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][3]['MinMaxContractLine']['maximumEnabled']='true';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][3]['MinMaxContractLine']['maximumValue']='5';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][3]['MinMaxContractLine']['maximumWeight']='1';
        $MinMaxContractIndex[$index]=$index;
   


        $index++;
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][4]['MinMaxContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][4]['MinMaxContractLine']['id']='13';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][4]['MinMaxContractLine']['contract']['@attributes']['reference']=$contractIndex[2];
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][4]['MinMaxContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][4]['MinMaxContractLine']['contractLineType']='CONSECUTIVE_WORKING_WEEKENDS';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][4]['MinMaxContractLine']['minimumEnabled']='true';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][4]['MinMaxContractLine']['minimumValue']='1';  
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][4]['MinMaxContractLine']['minimumWeight']='1';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][4]['MinMaxContractLine']['maximumEnabled']='true';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][4]['MinMaxContractLine']['maximumValue']='3';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][4]['MinMaxContractLine']['maximumWeight']='1';
        $MinMaxContractIndex[$index]=$index;


        $index++;

        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][5]['BooleanContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][5]['BooleanContractLine']['id']='14';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][5]['BooleanContractLine']['contract']['@attributes']['reference']=$contractIndex[2];
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][5]['BooleanContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][5]['BooleanContractLine']['contractLineType']='COMPLETE_WEEKENDS';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][5]['BooleanContractLine']['enabled']='true';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][5]['BooleanContractLine']['weight']='1';
        $booleanContractIndex[$index]=$index;


        $index++;

        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][6]['BooleanContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][6]['BooleanContractLine']['id']='15';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][6]['BooleanContractLine']['contract']['@attributes']['reference']=$contractIndex[2];
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][6]['BooleanContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][6]['BooleanContractLine']['contractLineType']='IDENTICAL_SHIFT_TYPES_DURING_WEEKEND';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][6]['BooleanContractLine']['enabled']='true';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][6]['BooleanContractLine']['weight']='1';
        $booleanContractIndex[$index]=$index;



        $index++;

        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][7]['BooleanContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][7]['BooleanContractLine']['id']='16';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][7]['BooleanContractLine']['contract']['@attributes']['reference']=$contractIndex[2];
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][7]['BooleanContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][7]['BooleanContractLine']['contractLineType']='NO_NIGHT_SHIFT_BEFORE_FREE_WEEKEND';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][7]['BooleanContractLine']['enabled']='true';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][7]['BooleanContractLine']['weight']='1';
        $booleanContractIndex[$index]=$index;



        $index++;

        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][8]['BooleanContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][8]['BooleanContractLine']['id']='17';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][8]['BooleanContractLine']['contract']['@attributes']['reference']=$contractIndex[2];
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][8]['BooleanContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][8]['BooleanContractLine']['contractLineType']='ALTERNATIVE_SKILL_CATEGORY';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][8]['BooleanContractLine']['enabled']='true';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][8]['BooleanContractLine']['weight']='1';
        $booleanContractIndex[$index]=$index;
       


        $index++;

        $tcm_pharmacy['contractList'][3]['Contract']['@attributes']['id']=$index;
        $contractIndex[3]=$index;
        $tcm_pharmacy['contractList'][3]['Contract']['id']='2';
        $tcm_pharmacy['contractList'][3]['Contract']['code']='2';
        $tcm_pharmacy['contractList'][3]['Contract']['description']='50_percent';
        $tcm_pharmacy['contractList'][3]['Contract']['weekendDefinition']='FRIDAY_SATURDAY_SUNDAY';


        $index++;

        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList']['@attributes']['id']=$index;

        


        $index++;
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][0]['BooleanContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][0]['BooleanContractLine']['id']='18';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][0]['BooleanContractLine']['contract']['@attributes']['reference']=$contractIndex[3];
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][0]['BooleanContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][0]['BooleanContractLine']['contractLineType']='SINGLE_ASSIGNMENT_PER_DAY';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][0]['BooleanContractLine']['enabled']='true';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][0]['BooleanContractLine']['weight']='1';

        $booleanContractIndex[$index]=$index;


        $index++;
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][1]['MinMaxContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][1]['MinMaxContractLine']['id']='19';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][1]['MinMaxContractLine']['contract']['@attributes']['reference']=$contractIndex[3];
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][1]['MinMaxContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][1]['MinMaxContractLine']['contractLineType']='TOTAL_ASSIGNMENTS';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][1]['MinMaxContractLine']['minimumEnabled']='true';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][1]['MinMaxContractLine']['minimumValue']='4';  
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][1]['MinMaxContractLine']['minimumWeight']='1';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][1]['MinMaxContractLine']['maximumEnabled']='true';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][1]['MinMaxContractLine']['maximumValue']='10';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][1]['MinMaxContractLine']['maximumWeight']='1';
        $MinMaxContractIndex[$index]=$index;



        $index++;

        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][2]['MinMaxContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][2]['MinMaxContractLine']['id']='20';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][2]['MinMaxContractLine']['contract']['@attributes']['reference']=$contractIndex[3];
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][2]['MinMaxContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][2]['MinMaxContractLine']['contractLineType']='CONSECUTIVE_WORKING_DAYS';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][2]['MinMaxContractLine']['minimumEnabled']='true';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][2]['MinMaxContractLine']['minimumValue']='2';  
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][2]['MinMaxContractLine']['minimumWeight']='1';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][2]['MinMaxContractLine']['maximumEnabled']='true';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][2]['MinMaxContractLine']['maximumValue']='4';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][2]['MinMaxContractLine']['maximumWeight']='1';
        $MinMaxContractIndex[$index]=$index;


        $index++;

        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][3]['MinMaxContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][3]['MinMaxContractLine']['id']='21';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][3]['MinMaxContractLine']['contract']['@attributes']['reference']=$contractIndex[3];
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][3]['MinMaxContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][3]['MinMaxContractLine']['contractLineType']='CONSECUTIVE_FREE_DAYS';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][3]['MinMaxContractLine']['minimumEnabled']='true';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][3]['MinMaxContractLine']['minimumValue']='3';  
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][3]['MinMaxContractLine']['minimumWeight']='1';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][3]['MinMaxContractLine']['maximumEnabled']='true';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][3]['MinMaxContractLine']['maximumValue']='5';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][3]['MinMaxContractLine']['maximumWeight']='1';
        $MinMaxContractIndex[$index]=$index;



        $index++;
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][4]['MinMaxContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][4]['MinMaxContractLine']['id']='22';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][4]['MinMaxContractLine']['contract']['@attributes']['reference']=$contractIndex[3];
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][4]['MinMaxContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][4]['MinMaxContractLine']['contractLineType']='CONSECUTIVE_WORKING_WEEKENDS';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][4]['MinMaxContractLine']['minimumEnabled']='false';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][4]['MinMaxContractLine']['minimumValue']='0';  
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][4]['MinMaxContractLine']['minimumWeight']='0';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][4]['MinMaxContractLine']['maximumEnabled']='true';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][4]['MinMaxContractLine']['maximumValue']='1';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][4]['MinMaxContractLine']['maximumWeight']='1';
        $MinMaxContractIndex[$index]=$index;


        $index++;
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][5]['BooleanContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][5]['BooleanContractLine']['id']='23';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][5]['BooleanContractLine']['contract']['@attributes']['reference']=$contractIndex[3];
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][5]['BooleanContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][5]['BooleanContractLine']['contractLineType']='COMPLETE_WEEKENDS';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][5]['BooleanContractLine']['enabled']='true';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][5]['BooleanContractLine']['weight']='1';

        $booleanContractIndex[$index]=$index;


        $index++;

        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][6]['BooleanContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][6]['BooleanContractLine']['id']='24';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][6]['BooleanContractLine']['contract']['@attributes']['reference']=$contractIndex[3];
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][6]['BooleanContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][6]['BooleanContractLine']['contractLineType']='IDENTICAL_SHIFT_TYPES_DURING_WEEKEND';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][6]['BooleanContractLine']['enabled']='true';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][6]['BooleanContractLine']['weight']='1';
        $booleanContractIndex[$index]=$index;



        $index++;

        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][7]['BooleanContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][7]['BooleanContractLine']['id']='25';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][7]['BooleanContractLine']['contract']['@attributes']['reference']=$contractIndex[3];
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][7]['BooleanContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][7]['BooleanContractLine']['contractLineType']='NO_NIGHT_SHIFT_BEFORE_FREE_WEEKEND';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][7]['BooleanContractLine']['enabled']='true';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][7]['BooleanContractLine']['weight']='1';
        $booleanContractIndex[$index]=$index;


        $index++;

        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][8]['BooleanContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][8]['BooleanContractLine']['id']='26';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][8]['BooleanContractLine']['contract']['@attributes']['reference']=$contractIndex[3];
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][8]['BooleanContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][8]['BooleanContractLine']['contractLineType']='ALTERNATIVE_SKILL_CATEGORY';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][8]['BooleanContractLine']['enabled']='true';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][8]['BooleanContractLine']['weight']='1';
        $booleanContractIndex[$index]=$index;


        //contractLineList列表
        $index++;
        $tcm_pharmacy['contractLineList']['@attributes']['id']=$index;

        foreach ($booleanContractIndex as $key => $booleanContract) {
            $tcm_pharmacy['contractLineList'][$key]['BooleanContractLine']['@attributes']['reference']=$booleanContract;
            $tcm_pharmacy['contractLineList'][$key]['BooleanContractLine']['@data']='';
        }

        foreach ($MinMaxContractIndex as $key => $minMaxContract) {
            $tcm_pharmacy['contractLineList'][$key]['MinMaxContractLine']['@attributes']['reference']=$minMaxContract;
            $tcm_pharmacy['contractLineList'][$key]['MinMaxContractLine']['@data']='';
        }




        $index++;
        $tcm_pharmacy['patternContractLineList']['@attributes']['id']=$index;

        $index++;
        $tcm_pharmacy['patternContractLineList'][1]['PatternContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['patternContractLineList'][1]['PatternContractLine']['id']='0';
        $tcm_pharmacy['patternContractLineList'][1]['PatternContractLine']['contract']['@attributes']['reference']=$contractIndex[1];
        $tcm_pharmacy['patternContractLineList'][1]['PatternContractLine']['contract']['@data']='';
        
         $tcm_pharmacy['patternContractLineList'][1]['PatternContractLine']['pattern']['@attributes']['class']='IdenticalShiftLastSomeDaysPattern';
         $tcm_pharmacy['patternContractLineList'][1]['PatternContractLine']['pattern']['@attributes']['reference']=$patternIndex['LastSomeDaysPattern'];
        $tcm_pharmacy['patternContractLineList'][1]['PatternContractLine']['pattern']['@data']='';


        $index++;
        $tcm_pharmacy['patternContractLineList'][2]['PatternContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['patternContractLineList'][2]['PatternContractLine']['id']='1';
        $tcm_pharmacy['patternContractLineList'][2]['PatternContractLine']['contract']['@attributes']['reference']=$contractIndex[1];
        $tcm_pharmacy['patternContractLineList'][2]['PatternContractLine']['contract']['@data']='';
        
        $tcm_pharmacy['patternContractLineList'][2]['PatternContractLine']['pattern']['@attributes']['class']='ShiftAssignedOnlyforManPattern';
        $tcm_pharmacy['patternContractLineList'][2]['PatternContractLine']['pattern']['@attributes']['reference']=$patternIndex['AssignedOnlyforMan'];
        $tcm_pharmacy['patternContractLineList'][2]['PatternContractLine']['pattern']['@data']='';


        $index++;
        $tcm_pharmacy['patternContractLineList'][3]['PatternContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['patternContractLineList'][3]['PatternContractLine']['id']='2';
        $tcm_pharmacy['patternContractLineList'][3]['PatternContractLine']['contract']['@attributes']['reference']=$contractIndex[1];;
        $tcm_pharmacy['patternContractLineList'][3]['PatternContractLine']['contract']['@data']='';
        
         $tcm_pharmacy['patternContractLineList'][3]['PatternContractLine']['pattern']['@attributes']['class']='FreeAfterWeekendWorkDayPattern';
         $tcm_pharmacy['patternContractLineList'][3]['PatternContractLine']['pattern']['@attributes']['reference']=$patternIndex['FreeAfterWeekendWorkDayPattern'];
        $tcm_pharmacy['patternContractLineList'][3]['PatternContractLine']['pattern']['@data']='';


        $index++;
        $tcm_pharmacy['patternContractLineList'][4]['PatternContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['patternContractLineList'][4]['PatternContractLine']['id']='3';
        $tcm_pharmacy['patternContractLineList'][4]['PatternContractLine']['contract']['@attributes']['reference']=$contractIndex[1];;
        $tcm_pharmacy['patternContractLineList'][4]['PatternContractLine']['contract']['@data']='';
        
        $tcm_pharmacy['patternContractLineList'][4]['PatternContractLine']['pattern']['@attributes']['class']='FreeAfterANightShiftPattern';
        $tcm_pharmacy['patternContractLineList'][4]['PatternContractLine']['pattern']['@attributes']['reference']=$patternIndex['FreeAfterANightShiftPattern'];
        $tcm_pharmacy['patternContractLineList'][4]['PatternContractLine']['pattern']['@data']='';



        $index++;
        $tcm_pharmacy['patternContractLineList'][5]['PatternContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['patternContractLineList'][5]['PatternContractLine']['id']='4';
        $tcm_pharmacy['patternContractLineList'][5]['PatternContractLine']['contract']['@attributes']['reference']=$contractIndex[1];;
        $tcm_pharmacy['patternContractLineList'][5]['PatternContractLine']['contract']['@data']='';
        
         $tcm_pharmacy['patternContractLineList'][5]['PatternContractLine']['pattern']['@attributes']['class']='ShiftAssignedAveragedAtAllEmployeesPattern';
        $tcm_pharmacy['patternContractLineList'][5]['PatternContractLine']['pattern']['@attributes']['reference']=$patternIndex['ShiftAssignedAveragedAtAllEmployeesPattern'];
        $tcm_pharmacy['patternContractLineList'][5]['PatternContractLine']['pattern']['@data']='';


        $index++;
        $tcm_pharmacy['patternContractLineList'][6]['PatternContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['patternContractLineList'][6]['PatternContractLine']['id']='5';
        $tcm_pharmacy['patternContractLineList'][6]['PatternContractLine']['contract']['@attributes']['reference']=$contractIndex[1];;
        $tcm_pharmacy['patternContractLineList'][6]['PatternContractLine']['contract']['@data']='';
        
         $tcm_pharmacy['patternContractLineList'][6]['PatternContractLine']['pattern']['@attributes']['class']='FreeTwoDaysEveryWeekPattern';
        $tcm_pharmacy['patternContractLineList'][6]['PatternContractLine']['pattern']['@attributes']['reference']=$patternIndex['FreeTwoDaysEveryWeekPattern'];
        $tcm_pharmacy['patternContractLineList'][6]['PatternContractLine']['pattern']['@data']='';



        $index++;
        $tcm_pharmacy['patternContractLineList'][7]['PatternContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['patternContractLineList'][7]['PatternContractLine']['id']='6';
        $tcm_pharmacy['patternContractLineList'][7]['PatternContractLine']['contract']['@attributes']['reference']=$contractIndex[1];
        $tcm_pharmacy['patternContractLineList'][7]['PatternContractLine']['contract']['@data']='';
        
        $tcm_pharmacy['patternContractLineList'][7]['PatternContractLine']['pattern']['@attributes']['class']='ShiftAssignedSomeWeeksPattern';
        $tcm_pharmacy['patternContractLineList'][7]['PatternContractLine']['pattern']['@attributes']['reference']=$patternIndex['ShiftAssignedSomeWeeksPattern'];
        $tcm_pharmacy['patternContractLineList'][7]['PatternContractLine']['pattern']['@data']='';

/*
        $index++;

        $tcm_pharmacy['patternContractLineList'][8]['PatternContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['patternContractLineList'][8]['PatternContractLine']['id']='7';
        $tcm_pharmacy['patternContractLineList'][8]['PatternContractLine']['contract']['@attributes']['reference']=$contractIndex[1];
        $tcm_pharmacy['patternContractLineList'][8]['PatternContractLine']['contract']['@data']='';
        
        $tcm_pharmacy['patternContractLineList'][8]['PatternContractLine']['pattern']['@attributes']['class']='ShiftType2DaysPattern';
        $tcm_pharmacy['patternContractLineList'][8]['PatternContractLine']['pattern']['@attributes']['reference']=$patternIndex['ShiftType2DaysPattern1'];
        $tcm_pharmacy['patternContractLineList'][8]['PatternContractLine']['pattern']['@data']='';


        $index++;
        $tcm_pharmacy['patternContractLineList'][9]['PatternContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['patternContractLineList'][9]['PatternContractLine']['id']='8';
        $tcm_pharmacy['patternContractLineList'][9]['PatternContractLine']['contract']['@attributes']['reference']=$contractIndex[1];
        $tcm_pharmacy['patternContractLineList'][9]['PatternContractLine']['contract']['@data']='';
        
        $tcm_pharmacy['patternContractLineList'][9]['PatternContractLine']['pattern']['@attributes']['class']='ShiftType2DaysPattern';
        $tcm_pharmacy['patternContractLineList'][9]['PatternContractLine']['pattern']['@attributes']['reference']=$patternIndex['ShiftType2DaysPattern1'];
        $tcm_pharmacy['patternContractLineList'][9]['PatternContractLine']['pattern']['@data']='';*/



        $index++;
        $tcm_pharmacy['patternContractLineList'][10]['PatternContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['patternContractLineList'][10]['PatternContractLine']['id']='9';
        $tcm_pharmacy['patternContractLineList'][10]['PatternContractLine']['contract']['@attributes']['reference']=$contractIndex[2];
        $tcm_pharmacy['patternContractLineList'][10]['PatternContractLine']['contract']['@data']='';
        
         $tcm_pharmacy['patternContractLineList'][10]['PatternContractLine']['pattern']['@attributes']['class']='IdenticalShiftLastSomeDaysPattern';
        $tcm_pharmacy['patternContractLineList'][10]['PatternContractLine']['pattern']['@attributes']['reference']=$patternIndex['LastSomeDaysPattern'];
        $tcm_pharmacy['patternContractLineList'][10]['PatternContractLine']['pattern']['@data']='';



        $index++;
        $tcm_pharmacy['patternContractLineList'][11]['PatternContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['patternContractLineList'][11]['PatternContractLine']['id']='10';
        $tcm_pharmacy['patternContractLineList'][11]['PatternContractLine']['contract']['@attributes']['reference']=$contractIndex[2];
        $tcm_pharmacy['patternContractLineList'][11]['PatternContractLine']['contract']['@data']='';
        
         $tcm_pharmacy['patternContractLineList'][11]['PatternContractLine']['pattern']['@attributes']['class']='ShiftAssignedOnlyforManPattern';
        $tcm_pharmacy['patternContractLineList'][11]['PatternContractLine']['pattern']['@attributes']['reference']=$patternIndex['AssignedOnlyforMan'];
        $tcm_pharmacy['patternContractLineList'][11]['PatternContractLine']['pattern']['@data']='';



        $index++;
        $tcm_pharmacy['patternContractLineList'][12]['PatternContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['patternContractLineList'][12]['PatternContractLine']['id']='11';
        $tcm_pharmacy['patternContractLineList'][12]['PatternContractLine']['contract']['@attributes']['reference']=$contractIndex[2];
        $tcm_pharmacy['patternContractLineList'][12]['PatternContractLine']['contract']['@data']='';
        
        $tcm_pharmacy['patternContractLineList'][12]['PatternContractLine']['pattern']['@attributes']['class']='FreeAfterWeekendWorkDayPattern';
        $tcm_pharmacy['patternContractLineList'][12]['PatternContractLine']['pattern']['@attributes']['reference']=$patternIndex['FreeAfterWeekendWorkDayPattern'];
        $tcm_pharmacy['patternContractLineList'][12]['PatternContractLine']['pattern']['@data']='';



        $index++;
        $tcm_pharmacy['patternContractLineList'][14]['PatternContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['patternContractLineList'][14]['PatternContractLine']['id']='12';
        $tcm_pharmacy['patternContractLineList'][14]['PatternContractLine']['contract']['@attributes']['reference']=$contractIndex[2];
        $tcm_pharmacy['patternContractLineList'][14]['PatternContractLine']['contract']['@data']='';
        
        $tcm_pharmacy['patternContractLineList'][14]['PatternContractLine']['pattern']['@attributes']['class']='FreeAfterANightShiftPattern';
        $tcm_pharmacy['patternContractLineList'][14]['PatternContractLine']['pattern']['@attributes']['reference']=$patternIndex['FreeAfterANightShiftPattern'];
        $tcm_pharmacy['patternContractLineList'][14]['PatternContractLine']['pattern']['@data']='';




        $index++;
        $tcm_pharmacy['patternContractLineList'][15]['PatternContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['patternContractLineList'][15]['PatternContractLine']['id']='13';
        $tcm_pharmacy['patternContractLineList'][15]['PatternContractLine']['contract']['@attributes']['reference']=$contractIndex[2];
        $tcm_pharmacy['patternContractLineList'][15]['PatternContractLine']['contract']['@data']='';
        
         $tcm_pharmacy['patternContractLineList'][15]['PatternContractLine']['pattern']['@attributes']['class']='ShiftAssignedAveragedAtAllEmployeesPattern';
        $tcm_pharmacy['patternContractLineList'][15]['PatternContractLine']['pattern']['@attributes']['reference']=$patternIndex['ShiftAssignedAveragedAtAllEmployeesPattern'];
        $tcm_pharmacy['patternContractLineList'][15]['PatternContractLine']['pattern']['@data']='';



        $index++;
        $tcm_pharmacy['patternContractLineList'][16]['PatternContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['patternContractLineList'][16]['PatternContractLine']['id']='14';
        $tcm_pharmacy['patternContractLineList'][16]['PatternContractLine']['contract']['@attributes']['reference']=$contractIndex[2];
        $tcm_pharmacy['patternContractLineList'][16]['PatternContractLine']['contract']['@data']='';
        
        $tcm_pharmacy['patternContractLineList'][16]['PatternContractLine']['pattern']['@attributes']['class']='FreeTwoDaysEveryWeekPattern';
        $tcm_pharmacy['patternContractLineList'][16]['PatternContractLine']['pattern']['@attributes']['reference']=$patternIndex['FreeTwoDaysEveryWeekPattern'];
        $tcm_pharmacy['patternContractLineList'][16]['PatternContractLine']['pattern']['@data']='';




        $index++;
        $tcm_pharmacy['patternContractLineList'][17]['PatternContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['patternContractLineList'][17]['PatternContractLine']['id']='15';
        $tcm_pharmacy['patternContractLineList'][17]['PatternContractLine']['contract']['@attributes']['reference']=$contractIndex[2];
        $tcm_pharmacy['patternContractLineList'][17]['PatternContractLine']['contract']['@data']='';
        
         $tcm_pharmacy['patternContractLineList'][17]['PatternContractLine']['pattern']['@attributes']['class']='ShiftAssignedSomeWeeksPattern';
        $tcm_pharmacy['patternContractLineList'][17]['PatternContractLine']['pattern']['@attributes']['reference']=$patternIndex['ShiftAssignedSomeWeeksPattern'];
        $tcm_pharmacy['patternContractLineList'][17]['PatternContractLine']['pattern']['@data']='';



   /*     $index++;
        $tcm_pharmacy['patternContractLineList'][18]['PatternContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['patternContractLineList'][18]['PatternContractLine']['id']='16';
        $tcm_pharmacy['patternContractLineList'][18]['PatternContractLine']['contract']['@attributes']['reference']=$contractIndex[2];
        $tcm_pharmacy['patternContractLineList'][18]['PatternContractLine']['contract']['@data']='';
        
        $tcm_pharmacy['patternContractLineList'][18]['PatternContractLine']['pattern']['@attributes']['class']='ShiftType2DaysPattern';
        $tcm_pharmacy['patternContractLineList'][18]['PatternContractLine']['pattern']['@attributes']['reference']=$patternIndex['ShiftType2DaysPattern1'];
        $tcm_pharmacy['patternContractLineList'][18]['PatternContractLine']['pattern']['@data']='';



        $index++;
        $tcm_pharmacy['patternContractLineList'][19]['PatternContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['patternContractLineList'][19]['PatternContractLine']['id']='17';
        $tcm_pharmacy['patternContractLineList'][19]['PatternContractLine']['contract']['@attributes']['reference']=$contractIndex[2];
        $tcm_pharmacy['patternContractLineList'][19]['PatternContractLine']['contract']['@data']='';
        
         $tcm_pharmacy['patternContractLineList'][19]['PatternContractLine']['pattern']['@attributes']['class']='ShiftType2DaysPattern';
        $tcm_pharmacy['patternContractLineList'][19]['PatternContractLine']['pattern']['@attributes']['reference']=$patternIndex['ShiftType2DaysPattern1'];
        $tcm_pharmacy['patternContractLineList'][19]['PatternContractLine']['pattern']['@data']='';*/



        $index++;
        $tcm_pharmacy['patternContractLineList'][20]['PatternContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['patternContractLineList'][20]['PatternContractLine']['id']='18';
        $tcm_pharmacy['patternContractLineList'][20]['PatternContractLine']['contract']['@attributes']['reference']=$contractIndex[3];
        $tcm_pharmacy['patternContractLineList'][20]['PatternContractLine']['contract']['@data']='';
        
        $tcm_pharmacy['patternContractLineList'][20]['PatternContractLine']['pattern']['@attributes']['class']='IdenticalShiftLastSomeDaysPattern';
        $tcm_pharmacy['patternContractLineList'][20]['PatternContractLine']['pattern']['@attributes']['reference']=$patternIndex['LastSomeDaysPattern'];
        $tcm_pharmacy['patternContractLineList'][20]['PatternContractLine']['pattern']['@data']='';




        $index++;
        $tcm_pharmacy['patternContractLineList'][21]['PatternContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['patternContractLineList'][21]['PatternContractLine']['id']='19';
        $tcm_pharmacy['patternContractLineList'][21]['PatternContractLine']['contract']['@attributes']['reference']=$contractIndex[3];
        $tcm_pharmacy['patternContractLineList'][21]['PatternContractLine']['contract']['@data']='';
        
        $tcm_pharmacy['patternContractLineList'][21]['PatternContractLine']['pattern']['@attributes']['class']='ShiftAssignedOnlyforManPattern';
        $tcm_pharmacy['patternContractLineList'][21]['PatternContractLine']['pattern']['@attributes']['reference']=$patternIndex['AssignedOnlyforMan'];
        $tcm_pharmacy['patternContractLineList'][21]['PatternContractLine']['pattern']['@data']='';



        $index++;
        $tcm_pharmacy['patternContractLineList'][22]['PatternContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['patternContractLineList'][22]['PatternContractLine']['id']='20';
        $tcm_pharmacy['patternContractLineList'][22]['PatternContractLine']['contract']['@attributes']['reference']=$contractIndex[3];
        $tcm_pharmacy['patternContractLineList'][22]['PatternContractLine']['contract']['@data']='';
        
         $tcm_pharmacy['patternContractLineList'][22]['PatternContractLine']['pattern']['@attributes']['class']='FreeAfterWeekendWorkDayPattern';
         $tcm_pharmacy['patternContractLineList'][22]['PatternContractLine']['pattern']['@attributes']['reference']=$patternIndex['FreeAfterWeekendWorkDayPattern'];
        $tcm_pharmacy['patternContractLineList'][22]['PatternContractLine']['pattern']['@data']='';



        $index++;
        $tcm_pharmacy['patternContractLineList'][23]['PatternContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['patternContractLineList'][23]['PatternContractLine']['id']='21';
        $tcm_pharmacy['patternContractLineList'][23]['PatternContractLine']['contract']['@attributes']['reference']=$contractIndex[3];
        $tcm_pharmacy['patternContractLineList'][23]['PatternContractLine']['contract']['@data']='';
        
         $tcm_pharmacy['patternContractLineList'][23]['PatternContractLine']['pattern']['@attributes']['class']='FreeAfterANightShiftPattern';
         $tcm_pharmacy['patternContractLineList'][23]['PatternContractLine']['pattern']['@attributes']['reference']=$patternIndex['FreeAfterANightShiftPattern'];
        $tcm_pharmacy['patternContractLineList'][23]['PatternContractLine']['pattern']['@data']='';




        $index++;
        $tcm_pharmacy['patternContractLineList'][24]['PatternContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['patternContractLineList'][24]['PatternContractLine']['id']='22';
        $tcm_pharmacy['patternContractLineList'][24]['PatternContractLine']['contract']['@attributes']['reference']=$contractIndex[3];
        $tcm_pharmacy['patternContractLineList'][24]['PatternContractLine']['contract']['@data']='';
        
         $tcm_pharmacy['patternContractLineList'][24]['PatternContractLine']['pattern']['@attributes']['class']='ShiftAssignedAveragedAtAllEmployeesPattern';
         $tcm_pharmacy['patternContractLineList'][24]['PatternContractLine']['pattern']['@attributes']['reference']=$patternIndex['ShiftAssignedAveragedAtAllEmployeesPattern'];
        $tcm_pharmacy['patternContractLineList'][24]['PatternContractLine']['pattern']['@data']='';




        $index++;
        $tcm_pharmacy['patternContractLineList'][25]['PatternContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['patternContractLineList'][25]['PatternContractLine']['id']='23';
        $tcm_pharmacy['patternContractLineList'][25]['PatternContractLine']['contract']['@attributes']['reference']=$contractIndex[3];
        $tcm_pharmacy['patternContractLineList'][25]['PatternContractLine']['contract']['@data']='';
        
         $tcm_pharmacy['patternContractLineList'][25]['PatternContractLine']['pattern']['@attributes']['class']='FreeTwoDaysEveryWeekPattern';
         $tcm_pharmacy['patternContractLineList'][25]['PatternContractLine']['pattern']['@attributes']['reference']=$patternIndex['FreeTwoDaysEveryWeekPattern'];
        $tcm_pharmacy['patternContractLineList'][25]['PatternContractLine']['pattern']['@data']='';


        $index++;   
        $tcm_pharmacy['patternContractLineList'][26]['PatternContractLine']['@attributes']['id']=$index;
        $tcm_pharmacy['patternContractLineList'][26]['PatternContractLine']['id']='24';
        $tcm_pharmacy['patternContractLineList'][26]['PatternContractLine']['contract']['@attributes']['reference']=$contractIndex[3];
        $tcm_pharmacy['patternContractLineList'][26]['PatternContractLine']['contract']['@data']='';
        
         $tcm_pharmacy['patternContractLineList'][26]['PatternContractLine']['pattern']['@attributes']['class']='ShiftAssignedSomeWeeksPattern';
        $tcm_pharmacy['patternContractLineList'][26]['PatternContractLine']['pattern']['@attributes']['reference']=$patternIndex['ShiftAssignedSomeWeeksPattern'];
        $tcm_pharmacy['patternContractLineList'][26]['PatternContractLine']['pattern']['@data']='';






        $index++;
        $tcm_pharmacy['employeeList']['@attributes']['id']=$index;

        foreach ($employeeList as $key => $employee) {


            $age=$this->birthday($employee['emp_birthday']);
            
            $index++;
            $tcm_pharmacy['employeeList'][$key]['Employee']['@attributes']['id']=$index;
            $employeeIndex[$employee['empNumber']]=$index;
            $tcm_pharmacy['employeeList'][$key]['Employee']['id']=$employee['empNumber'];
            $tcm_pharmacy['employeeList'][$key]['Employee']['code']=$employee['empNumber'];;
            $tcm_pharmacy['employeeList'][$key]['Employee']['name']=$employee['empNumber'];
            $tcm_pharmacy['employeeList'][$key]['Employee']['age']='32';
            $tcm_pharmacy['employeeList'][$key]['Employee']['title']='1';
            $tcm_pharmacy['employeeList'][$key]['Employee']['gender']=$employee['emp_gender'];
            $tcm_pharmacy['employeeList'][$key]['Employee']['workyear']='1';
            $tcm_pharmacy['employeeList'][$key]['Employee']['freedays']='1';
            $tcm_pharmacy['employeeList'][$key]['Employee']['mutexname']='1';
            $tcm_pharmacy['employeeList'][$key]['Employee']['currdepartment']='1';
            $tcm_pharmacy['employeeList'][$key]['Employee']['allodepartment']='1';
         

            $tcm_pharmacy['employeeList'][$key]['Employee']['contract']['@attributes']['reference']=$contractIndex[1];
            $tcm_pharmacy['employeeList'][$key]['Employee']['contract']['@data']='';

            $index++;
            $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap']['@attributes']['id']=$index;
            
            // 如果没有给任何员工安排班，则所有员工的的未上班日期为计划中所有天
            if(empty($employee['EmployeeWorkShift'])){
                $noShiftDate=array_column($shiftDates,'id');
            }else{

                foreach ($employee['EmployeeWorkShift'] as $shiftkey => $shift) {
                    $shift_id=$shift['workShiftId'];

                    $shiftDateID[$shiftkey]=$this->getShiftById($shift_id)->shiftdate_id;
                    
                }
                //获得这个员工有班的日期；
                $shiftDateID=array_unique($shiftDateID);
                //查找所有日期
                $shiftDatesIdList=array_column($shiftDates,'id');

                $noShiftDate=array_diff($shiftDatesIdList,$shiftDateID);
            }

            $exist = true;         
            if (empty($noShiftDate)) {
                    $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap']['@data']="";
               
            }else{
                
               $exist = false;
            }

            if(!$exist) {//如果员工有些天休息，循环休息天

                foreach ($noShiftDate as $dayOffKey => $dayOff) {
                    $shiftDateOff=$this->getShiftDateById($dayOff);
                    //首先判断是否已经创建过该天和该类型,如果没有创建过，则运用下面模式，同时存储shiftDataindex中；
                    $exit_shiftDate=array_flip($shiftDateIndex);

                    if(!in_array($dayOff, $exit_shiftDate)){

                        $date_format=$this->getFormatDate($shiftDateOff->shiftDate);
                     
                        $index++;
                        $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['ShiftDate']['@attributes']['id']=$index;


                        $shiftDateIndex[$dayOff]=$index;
                       
                        $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['ShiftDate']['id']=$dayOffKey;
                        $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['ShiftDate']['dayIndex']=$dayOffKey;

                        $index++;
                        $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['ShiftDate']['date']['@attributes']['id']=$index;
                        $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['ShiftDate']['date']['@attributes']['resolves-to']='java.time.Ser';
                        
                        $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['ShiftDate']['date'][]['byte']='3';
                        $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['ShiftDate']['date'][]['int']=$date_format['y'];
                        $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['ShiftDate']['date'][]['byte']=$date_format['m'];
                        $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['ShiftDate']['date'][]['byte']=$date_format['d'];


                        $index++;
                        $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['ShiftDate']['shiftList']['@attributes']['id']=$index;

                        foreach ($shiftDateOff->shiftList as $k => $shift) {

                            $index++;
                            $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['ShiftDate']['shiftList'][$k]['Shift']['@attributes']['id']=$index;

                             $shiftListIndex[$shift->id]=$index;

                             $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['ShiftDate']['shiftList'][$k]['Shift']['id']=$shift->id;
                            $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['ShiftDate']['shiftList'][$k]['Shift']['shiftDate']['@attributes']['reference']=$shiftDateIndex[$dayOff];
                            $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['ShiftDate']['shiftList'][$k]['Shift']['shiftDate']['@data']='';
                            $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['ShiftDate']['shiftList'][$k]['Shift']['shiftType']['@attributes']['reference']=$shiftTypeIndex[$shift->shift_type_id];
                            $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['ShiftDate']['shiftList'][$k]['Shift']['shiftType']['@data']='';
                            $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['ShiftDate']['shiftList'][$k]['Shift']['index']=$shift->id;
                            $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['ShiftDate']['shiftList'][$k]['Shift']['requiredEmployeeSize']=$shift->required_employee;

                            }
                            $index++;
                            $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['DayOffRequest']['@attributes']['id']=$index;
                            $dayOffRequest[$index]=$index;
                            $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['DayOffRequest']['id']=$index;
                            $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['DayOffRequest']['employee']['@attributes']['reference']=$employeeIndex[$employee['empNumber']];
                            $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['DayOffRequest']['employee']['@data']='';
                            $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['DayOffRequest']['shiftDate']['@attributes']['reference']=$shiftDateIndex[$dayOff];
                            $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['DayOffRequest']['shiftDate']['@data']='';
                            $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['DayOffRequest']['weight']='10';


                    }else{//如果已经创建过该天信息
                   
                        $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['ShiftDate']['@attributes']['reference']=$shiftDateIndex[$dayOff];
                        $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['ShiftDate']['@data']='';
                        $index++;
                        $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['DayOffRequest']['@attributes']['id']=$index;

                        $dayOffRequest[$index]=$index;

                        $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['DayOffRequest']['id']=$index;
                        $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['DayOffRequest']['employee']['@attributes']['reference']=$employeeIndex[$employee['empNumber']];
                        $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['DayOffRequest']['employee']['@data']='';
                        $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['DayOffRequest']['shiftDate']['@attributes']['reference']=$shiftDateIndex[$dayOff];
                        $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['DayOffRequest']['shiftDate']['@data']='';
                        $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap'][$dayOffKey]['entry']['DayOffRequest']['weight']='10';

                    }
                    
                }
            }


            //查找具体那一天哪些班不分配给该员工；
            $shiftNoForEmp=array();
            foreach ($patternList['shiftNotForEmployee'] as $shifEmKey => $shifEmVal) {

                if($shifEmVal['shiftNotForEmployee']==$employee['empNumber']&&$shifEmVal==1){
                    $shiftNoForEmp[$shifEmKey]['shiftType']=$shifEmVal['shiftNotForEmployeeShiftSelect'];
                    $shiftNoForEmp[$shifEmKey]['empNumber']=$shifEmVal['shiftNotForEmployee'];
                    $shiftNoForEmp[$shifEmKey]['shifDate']=$shifEmVal['shiftDate'];
                    $shiftNoForEmp[$shifEmKey]['weight']=$shifEmVal['shiftNotForEmployeeWeight'];
                }


            }
            
            $existEmp = true;
            $index++;
            $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap']['@attributes']['id']=$index;

            /*
                1,判断如果shiftNoForEmp设置没有设置了值

            */
            if(empty($shiftNoForEmp)){
                $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap']['@data']='';
                $existEmp = true;
            }else{
                
             foreach ($shiftNoForEmp as $sneKey => $emNoShift) {
              
                $shiftDateEm=$emNoShift['shifDate'];
                $shifTypeEm=$emNoShift['shiftType'];
               
                 $ifCreate=true;
                 $shiftEmtity=$this->getShiftByTypeAndDate($shifTypeEm,$shiftDateEm);
              
                 //查看这一天是否在已经存在,如果存在，查找这一天的这个班；
                 if(!empty($shiftDateIndex[$emNoShift['shifDate']])){
                        $shiftEmtity=$this->getShiftByTypeAndDate($shifTypeEm,$shiftDateEm);

                        $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['Shift']['@attributes']['reference']=$shiftListIndex[$shiftEmtity->id];
                        $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['Shift']['@data']='';

                      
                        $index++;
                        $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['ShiftOffRequest']['@attributes']['id']=$index;

                        $shiftOffRequest[$index]=$index;

                        $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['ShiftOffRequest']['id']=$index;
                        $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['ShiftOffRequest']['employee']['@attributes']['reference']=$employeeIndex[$employee['empNumber']];
                        $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['ShiftOffRequest']['employee']['@data']='';
                        $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['ShiftOffRequest']['shift']['@attributes']['reference']=$shiftListIndex[$shiftEmtity->id];
                        $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['ShiftOffRequest']['shift']['@data']='';
                        $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['ShiftOffRequest']['weight']=$emNoShift['weight'];
          

                 }else{//如果这一天没有被创建过，则创建这一天；罗列出这一天所有班，
                    $ifCreate=false;
                 }

                //如果这一天没有被创建过，则创建这一天；罗列出这一天所有班，
                if($ifCreate==false){

                    //罗列出这一天所有的班
                    $empNoShifts=$this->getShiftByDate($emNoShift['shifDate']);
                    $date_format=$this->getFormatDate($shiftDatesByIndex[$shiftDateEm]['shiftDate']);
                    $entry_shift=array();

                    $index++;
                    $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['Shift']['@attributes']['id']=$index;
                    $entry_shift[$shiftEmtity->id]=$index;
                    $shiftListIndex[$shiftEmtity->id]=$index;
                    

                    $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['Shift']['id']=$index;


                    $index++;
                    $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['Shift']['shiftDate']['@attributes']['id']=$index;

                    $shiftDateIndex[$emNoShift['shifDate']]=$index;

                   
                    $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['Shift']['shiftDate']['id']=$emNoShift['shifDate'];
                    $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['Shift']['shiftDate']['dayIndex']=$emNoShift['shifDate'];

                    $index++;
                    $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['Shift']['shiftDate']['date']['@attributes']['id']=$index;
                    $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['Shift']['shiftDate']['date']['@attributes']['resolves-to']='java.time.Ser';
                    
                    $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['Shift']['shiftDate']['date'][]['byte']='3';
               
                    $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['Shift']['shiftDate']['date'][]['int']=$date_format['y'];
                    $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['Shift']['shiftDate']['date'][]['byte']=$date_format['m'];
                    $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['Shift']['shiftDate']['date'][]['byte']=$date_format['d'];


                    $index++;
                    $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['Shift']['shiftDate']['shiftList']['@attributes']['id']=$index;

                    //循环这一天所有班
                    foreach ($empNoShifts as $eskey => $empNoShift) {

                        if($shiftEmtity->id==$empNoShift['id']){
                            $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['Shift']['shiftDate']['shiftList'][$eskey]['Shift']['@attributes']['reference']=  $entry_shift[$shiftEmtity->id];
                            
                            $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['Shift']['shiftDate']['shiftList'][$eskey]['Shift']['@data']= '';
                        }else{
                            $index++;
                            $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['Shift']['shiftDate']['shiftList'][$eskey]['Shift']['@attributes']['id']=$index;

                              $shiftListIndex[$empNoShift['id']]=$index;

                             $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['Shift']['shiftDate']['shiftList'][$eskey]['Shift']['id']=$empNoShift['id'];
                            $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['Shift']['shiftDate']['shiftList'][$eskey]['Shift']['shiftDate']['@attributes']['reference']=$shiftDateIndex[$empNoShift['shiftdate_id']];
                            $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['Shift']['shiftDate']['shiftList'][$eskey]['Shift']['shiftDate']['@data']='';
                            $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['Shift']['shiftDate']['shiftList'][$eskey]['Shift']['shiftType']['@attributes']['reference']=$shiftTypeIndex[$empNoShift['shift_type_id']];
                            $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['Shift']['shiftDate']['shiftList'][$eskey]['Shift']['shiftType']['@data']='';
                            $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['Shift']['shiftDate']['shiftList'][$eskey]['Shift']['index']=$empNoShift['id'];
                            $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['Shift']['shiftDate']['shiftList'][$eskey]['Shift']['requiredEmployeeSize']=$empNoShift['required_employee'];
                        }
          
                    }


                   $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['Shift']['shiftType']['@attributes']['reference']=$shiftTypeIndex[$shiftEmtity->shift_type_id];
                   $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['Shift']['shiftType']['@data']='';
                   $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['Shift']['index']=$shiftEmtity->id;

                   $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['Shift']['requiredEmployeeSize']=$shiftEmtity->required_employee;


                    $index++;
                    $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['ShiftOffRequest']['@attributes']['id']=$index;
                    $shiftOffRequest[$index]=$index;

                    $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['ShiftOffRequest']['id']=$index;
                    $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['ShiftOffRequest']['employee']['@attributes']['reference']=$employeeIndex[$employee['empNumber']];
                    $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['ShiftOffRequest']['employee']['@data']='';
                    $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['ShiftOffRequest']['shift']['@attributes']['reference']=$entry_shift[$shiftEmtity->id];
                    $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['ShiftOffRequest']['shift']['@data']='';
                    $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap'][$sneKey]['entry']['ShiftOffRequest']['weight']=$emNoShift['weight'];
                }//$ifCreate==false结束
            }
           }


            $index++;
            $tcm_pharmacy['employeeList'][$key]['Employee']['dayOnRequestMap']['@attributes']['id']=$index;
            $tcm_pharmacy['employeeList'][$key]['Employee']['dayOnRequestMap']['@data']='';

            

            $index++;
            $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOnRequestMap']['@attributes']['id']=$index;
            $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOnRequestMap']['@data']='';
            
        }


    

        //只是得到第一天和最后一天信息，同时将创建的shiftTypeId 和shiftDateID存储起来
        $index++;
        $tcm_pharmacy['shiftDateList']['@attributes']['id']=$index;

       
       
        $exit_shiftDate=array_flip($shiftDateIndex);


        foreach ($shiftDates as $key => $shiftDate) {

           if(in_array($shiftDate['id'], $exit_shiftDate)){

                $tcm_pharmacy['shiftDateList'][$key]['ShiftDate']['@attributes']['reference']=$shiftDateIndex[$shiftDate['id']];
                $tcm_pharmacy['shiftDateList'][$key]['ShiftDate']['@data']='';
           }else{
                $date_format=$this->getFormatDate($shiftDate['shiftDate']);

                $index++;
                $dayIndex++;
                $tcm_pharmacy['shiftDateList'][$key]['ShiftDate']['@attributes']['id']=$index;
                $shiftDataIndex[$key]=$index;
                $tcm_pharmacy['shiftDateList'][$key]['ShiftDate']['id']=$dayIndex;
                $tcm_pharmacy['shiftDateList'][$key]['ShiftDate']['dayIndex']=$dayIndex;
                $index++;
                $tcm_pharmacy['shiftDateList'][$key]['ShiftDate']['date']['@attributes']['id']=$index;
                $tcm_pharmacy['shiftDateList'][$key]['ShiftDate']['date']['@attributes']['resolves-to']='java.time.Ser';
               
                $tcm_pharmacy['shiftDateList'][$key]['ShiftDate']['date'][]['byte']='3';
                $tcm_pharmacy['shiftDateList'][$key]['ShiftDate']['date'][]['int']=$date_format['y'];
                $tcm_pharmacy['shiftDateList'][$key]['ShiftDate']['date'][]['byte']=$date_format['m'];
                $tcm_pharmacy['shiftDateList'][$key]['ShiftDate']['date'][]['byte']=$date_format['d'];
                $index++;
                $tcm_pharmacy['shiftDateList'][$key]['ShiftDate']['shiftList']['@attributes']['id']=$index;

                
                foreach($shiftDates[$key]['shiftList'] as $k=>$shift){
           
                    $shiftType=$this->getShiftTypeById($shift['shift_type_id']);
   
                    $index++;

                    $shiftListIndex[$shift['id']]=$index;

                    $tcm_pharmacy['shiftDateList'][$key]['ShiftDate']['shiftList'][$k]['Shift']['@attributes']['id']=$index;
                  
                    $tcm_pharmacy['shiftDateList'][$key]['ShiftDate']['shiftList'][$k]['Shift']['id']=$shift['id'];
                    $tcm_pharmacy['shiftDateList'][$key]['ShiftDate']['shiftList'][$k]['Shift']['shiftDate']['@attributes']['reference']= $shiftDataIndex[$key];
                    $tcm_pharmacy['shiftDateList'][$key]['ShiftDate']['shiftList'][$k]['Shift']['shiftDate']['@data']='';


                    //记录shiftTypeID
                   $tcm_pharmacy['shiftDateList'][$key]['ShiftDate']['shiftList'][$k]['Shift']['shiftType']['@attributes']['reference']=$shiftTypeIndex[$shiftType->id];
                    $tcm_pharmacy['shiftDateList'][$key]['ShiftDate']['shiftList'][$k]['Shift']['shiftType']['@data']='';
                    

                    $tcm_pharmacy['shiftDateList'][$key]['ShiftDate']['shiftList'][$k]['Shift']['index']=$shift['id'];;
                    $tcm_pharmacy['shiftDateList'][$key]['ShiftDate']['shiftList'][$k]['Shift']['requiredEmployeeSize']=$shift['required_employee'];;

                }
           }
            
            
        }


          
        //shiftType列表；
        // $index++;
        // $tcm_pharmacy['shiftTypeList']['@attributes']['id']=$index;
        foreach ($shiftTypeIndex as $key => $shiftType) {
           $tcm_pharmacy['shiftTypeList'][$key]['ShiftType']['@attributes']['reference']=$shiftType;
           $tcm_pharmacy['shiftTypeList'][$key]['ShiftType']['@data']='';
        }


        //排班列表
        $index++;
        $tcm_pharmacy['shiftList']['@attributes']['id']=$index;
        if(empty($shifts)){

            $tcm_pharmacy['shiftList']['@data']='';

        }else{
            foreach ($shiftListIndex as $key => $shift) {
                $tcm_pharmacy['shiftList'][$key]['Shift']['@attributes']['reference']=$shift;
                $tcm_pharmacy['shiftList'][$key]['Shift']['@data']='';
        }
        }


        //技能列表
        // $index++;
        // $tcm_pharmacy['skillList']['@attributes']['id']=$index;
        foreach ($skillList as $key => $skill) {
          
            $index++;
            $tcm_pharmacy['skillList'][$key]['Skill']['@attributes']['id']=$index;
            $skillListIndex[$skill['id']]=$index;
            $tcm_pharmacy['skillList'][$key]['Skill']['id']=$skill['id'];
            $tcm_pharmacy['skillList'][$key]['Skill']['code']=$skill['id'];
        }

         //每个轮班类型所需岗位
        // $index++;
        // $tcm_pharmacy['shiftTypeSkillRequirementList']['@attributes']['id']=$index;


        foreach ($shiftTypeToSkillList as $key => $shiftTypeToSkill) {
     
            $index++;
            $tcm_pharmacy['shiftTypeSkillRequirementList'][$key]['ShiftTypeSkillRequirement']['@attributes']['id']=$index;
            $tcm_pharmacy['shiftTypeSkillRequirementList'][$key]['ShiftTypeSkillRequirement']['id']=$shiftTypeToSkill['id'];
            $tcm_pharmacy['shiftTypeSkillRequirementList'][$key]['ShiftTypeSkillRequirement']['shiftType']['@attributes']['reference']=$shiftTypeIndex[$shiftTypeToSkill['shift_type_id']];
            $tcm_pharmacy['shiftTypeSkillRequirementList'][$key]['ShiftTypeSkillRequirement']['shiftType']['@data']='';
            $tcm_pharmacy['shiftTypeSkillRequirementList'][$key]['ShiftTypeSkillRequirement']['skill']['@attributes']['reference']=$skillListIndex[$shiftTypeToSkill['skill_id']];
            $tcm_pharmacy['shiftTypeSkillRequirementList'][$key]['ShiftTypeSkillRequirement']['skill']['@data']='';
        }



         //技能列表
        $index++;
        $tcm_pharmacy['skillProficiencyList']['@attributes']['id']=$index;
        foreach ($employeeSkillList as $key => $employeeSkill) {

            $index++;
            $tcm_pharmacy['skillProficiencyList'][$key]['SkillProficiency']['@attributes']['id']=$index;
            $tcm_pharmacy['skillProficiencyList'][$key]['SkillProficiency']['id']=$employeeSkill['id'];
            $tcm_pharmacy['skillProficiencyList'][$key]['SkillProficiency']['employee']['@attributes']['reference']=$employeeIndex[$employeeSkill['emp_number']];
            $tcm_pharmacy['skillProficiencyList'][$key]['SkillProficiency']['employee']['@data']='';
            $tcm_pharmacy['skillProficiencyList'][$key]['SkillProficiency']['skill']['@attributes']['reference']=$skillListIndex[$employeeSkill['skillId']];
            $tcm_pharmacy['skillProficiencyList'][$key]['SkillProficiency']['skill']['@data']='';

        }


        //不上班
        if(!empty($dayOffRequest)){
            $index++;
            $tcm_pharmacy['dayOffRequestList']['@attributes']['id']=$index;
            foreach ($dayOffRequest as $key => $dayOff) {
            
                $tcm_pharmacy['dayOffRequestList'][$key]['DayOffRequest']['@attributes']['reference']=$dayOff;
                $tcm_pharmacy['dayOffRequestList'][$key]['DayOffRequest']['@data']='';

            }

        }else{
            $tcm_pharmacy['dayOffRequestList']['@attributes']['class']="empty-list";
            $tcm_pharmacy['dayOffRequestList']['@data']='';
        }
        

        $tcm_pharmacy['dayOnRequestList']['@attributes']['class']="empty-list";
        $tcm_pharmacy['dayOnRequestList']['@data']='';


        if(!empty($shiftOffRequest)){
            $index++;
            $tcm_pharmacy['dayOffRequestList']['@attributes']['id']=$index;
            foreach ($shiftOffRequest as $key => $shiftOff) {
            
                $tcm_pharmacy['shiftOffRequestList'][$key]['ShiftOffRequest']['@attributes']['reference']=$shiftOff;
                $tcm_pharmacy['shiftOffRequestList'][$key]['ShiftOffRequest']['@data']='';

            }

        }else{
            $tcm_pharmacy['shiftOffRequestList']['@attributes']['class']="empty-list";
            $tcm_pharmacy['shiftOffRequestList']['@data']='';
        }

    
        $tcm_pharmacy['shiftOnRequestList']['@attributes']['class']="empty-list";
        $tcm_pharmacy['shiftOnRequestList']['@data']='';
        
        // 例如10月1日的早班需要三个人，则会生成三个 ShiftAssignment-->
        //循环计划中所有班，然后依次取出每个班所需要人数；根据人数来复制

        // echo '<pre>';var_dump($shiftAssignments);exit;

        foreach($shiftAssignments as $key=>$shiftAssignment){
            $shift_assign_list[$shiftAssignment['shift_id']][]=$shiftAssignment;

        }


        $index++;
        $tcm_pharmacy['shiftAssignmentList']['@attributes']['id']=$index;


       // echo '<pre>'; var_dump($shiftAssignments);exit;
      
        foreach($shiftAssignments as $key=>$shiftAssignment){

                $index++;
                $tcm_pharmacy['shiftAssignmentList'][$key]['ShiftAssignment']['@attributes']['id']=$index;
                $tcm_pharmacy['shiftAssignmentList'][$key]['ShiftAssignment']['id']=$key;
                $tcm_pharmacy['shiftAssignmentList'][$key]['ShiftAssignment']['shift']['@attributes']['reference']=$shiftListIndex[$shiftAssignment['shift_id']];
                $tcm_pharmacy['shiftAssignmentList'][$key]['ShiftAssignment']['shift']['@data']='';
                $tcm_pharmacy['shiftAssignmentList'][$key]['ShiftAssignment']['indexInShift']=$shiftAssignment['shift_index'];

        }


        $array_name="roster".$scheduleID;
        //将数组存储为静态文件
        $TCM=$this->saveFile($array_name,$tcm_pharmacy);

         //将数据转化为xml格式；
        $toXmData=$this->array_to_xml($TCM);

        //将XML存储为静态文件
        $this->cacheData($xml_name,$toXmData);
       
        // header("Content-type: text/xml");

        // print_r($toXmData);exit;
    }

    public function getRosterResult($scheduleID){

        $file_path='http://localhost:8080/www/OrangeHRM/symfony/plugins/orangehrmShiftPlugin/lib/service/files/roster_'.$scheduleID.'_solved.xml';


       $arr = file_get_contents($file_path);

       $result=$this->xmlToArray($arr);
       return $result;
  
    }



}
