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
    public function getShiftTypeList($schedule_id) {

        return $this->getShiftDao()->getShiftTypeList($schedule_id);
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

    public function getShiftTypeToSkillList(){
        return $this->getShiftDao()->getShiftTypeToSkillList();
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

    public function saveWorkEntityIds(WorkShiftEntityIds $shiftEntity) {


        return $this->getShiftDao()->saveWorkEntityIds($shiftEntity);
    }

    public function saveShiftAssignments(WorkShiftAssignment $shiftAssignment) {
        return $this->getShiftDao()->saveShiftAssignments($shiftAssignment);
    }

    public function getShiftContranct($shift_id,$status) {

        return $this->getShiftDao()->getShiftContranct($shift_id,$status);
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
                        $str .= " $k='{$v}'";
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
                                    $str .= " $k='{$v}'";
                                }
                            }
                            $str .= ">";

                            if (isset($value['@data']))
                            {
                                $format_attribute_value($value['@data']);
                                $str .= "{$value['@data']}";
                            }
                            elseif (isset($value['@tdata']))
                            {
                                $str .= "<![CDATA[{$value['@tdata']}]]>";
                            }
                            else
                            {
                                $tmp_str = $format_to_xml_string($value, $attribute_key, $crlf, $tab, $left_str . $tab);
                                if (''!==$tmp_str)
                                {
                                    $str .= $tmp_str."{$crlf}{$left_str}";
                                }
                                unset($tmp_str);
                            }
                            $str .= "</{$key}>";
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
            $arr['m']=date('m',strtotime($copy_day));
            $arr['d']=date('d',strtotime($copy_day));
            $arr['hm']=date('H:m',strtotime($copy_day));
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



        $shiftAssignments=$this->getShiftAssignmentList($scheduleID);

    

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
        

        $skillList=$this->getSkillList();
        $employeeList=$this->getEmployeeList();

        $employeeSkillList=$this->getEmployeeSkillList();
        $shiftTypeToSkillList=$this->getShiftTypeToSkillList();


        // $shifts = array_column($shifts, NULL, 'id');
        $shiftTypes = array_column($shiftTypes, NULL, 'id');
        // $shiftDates = array_column($shiftDates, NULL, 'id');

/*

        foreach ($shifts as $k => $shift) {

                $shift_typeid=$shift['shift_type_id'];
              
                // $shift_format['Shift']['@attributes']['id']=$shift['id'];
                $shift_format['Shift']['id']=$k;
                // $shift_format['Shift']['shiftDate']['@attributes']['reference']=(int)$shift['shiftdate_id'];
                // $shift_format['Shift']['shiftDate']['@data']='';


                // $shift_format['Shift']['shiftType']['@attributes']['id']=$shift_typeid;
                $shift_format['Shift']['shiftType']['id']=$k;
                $shift_format['Shift']['shiftType']['code']=$shiftTypes[$shift_typeid]['name'];
                $shift_format['Shift']['shiftType']['index']=$k;
                $shift_format['Shift']['shiftType']['startTimeString']=$shiftTypes[$shift_typeid]['start_time'];
                $shift_format['Shift']['shiftType']['endTimeString']=$shiftTypes[$shift_typeid]['end_time'];
                $shift_format['Shift']['shiftType']['night']=false;
                $shift_format['Shift']['shiftType']['description']='no4';
                $shift_format['Shift']['index']=$k;
                $shift_format['Shift']['requiredEmployeeSize']='3';

                // $shifts_format['Shift']['@attributes']['id']=$shift['id'];
                $shifts_format['Shift']['id']=$k;
                // $shifts_format['Shift']['shiftDate']['@attributes']['reference']=(int)$shift['shiftdate_id'];

                // $shifts_format['Shift']['shiftDate']['@data']='';
                // $shifts_format['Shift']['shiftType']['@attributes']['reference']=$shift_typeid;
                // $shifts_format['Shift']['shiftType']['@data']='';
                $shifts_format['Shift']['index']=$k;
                $shifts_format['Shift']['requiredEmployeeSize']='3';
                $dats[$shift['shiftdate_id']][]=$shift_format;
                $dates[$shift['shiftdate_id']][]=$shifts_format;
                
        }






        $date_length=count($shiftDates)-1;

        //获取指定计划中每个日期下的排班列表
        $shiftListIndex=1;
        foreach ($shiftDates as $key => $date) {

            if(!empty($dats[$date['id']])){
                $shiftDates[$key]['shiftList']=$dats[$date['id']];
                $shiftDates[$key]['shiftList']['@attributes']['id']=$shiftListIndex;
            }else{
                $shiftDates[$key]['shiftList']=$dats[$date['id']];
                $shiftDates[$key]['shiftList']['@attributes']['id']=$shiftListIndex;
                $shiftDates[$key]['shiftList']['@data']='';
            }

            $shiftListIndex++;
        }

        $firstDate=reset($shiftDates);
        $endDate=end($shiftDates);


        $enddates=array();
        unset($endDate['shiftList']['@attributes']);

        foreach ($endDate['shiftList'] as $key => $endday) {

            $shift_typeid=$endday['Shift']['@attributes']['id'];
            // var_dump($shift_typeid);exit;
            unset($endday['Shift']['shiftType']);

            $endday['Shift']['shiftType']['@attributes']['reference']=$shift_typeid;
            $endday['Shift']['shiftType']['@data']='';

            $enddates[]=$endday;
        
        }

        $first_date_format=$this->getFormatDate($firstDate['shiftDate']);
        $end_date_format=$this->getFormatDate($endDate['shiftDate']);

        
        $firstShiftDate['@attributes']['id']=$firstDate['id'];
        $firstShiftDate['id']='0';
        $firstShiftDate['dayIndex']='0';
        $firstShiftDate['date']['@attributes']['id']=$firstDate['id'];
        $firstShiftDate['date']['@attributes']['resolves-to']='java.time.Ser';
        $firstShiftDate['date'][]['byte']='3';
        $firstShiftDate['date']['int']=$first_date_format['y'];
        $firstShiftDate['date'][]['byte']=$first_date_format['m'];
        $firstShiftDate['date'][]['byte']=$first_date_format['d'];
        $firstShiftDate['shiftList']=$firstDate['shiftList'];

        $lastShiftDate['@attributes']['id']=$endDate['id'];
        $lastShiftDate['id']=$date_length;;
        $lastShiftDate['dayIndex']=$date_length;
        $lastShiftDate['date']['@attributes']['id']=$endDate['id'];
        $lastShiftDate['date']['@attributes']['resolves-to']='java.time.Ser';
        $lastShiftDate['date'][]['byte']='3';
        $lastShiftDate['date']['int']=$end_date_format['y'];
        $lastShiftDate['date'][]['byte']=$end_date_format['m'];
        $lastShiftDate['date'][]['byte']=$end_date_format['d'];
        $lastShiftDate['shiftList']=$enddates;
        */








        $k=1;
        $tcm_pharmacy['@name']='NurseRoster';
        $tcm_pharmacy['@attributes']['id']=$k;
        $tcm_pharmacy['id']=0;
        $tcm_pharmacy['code']='long_hint01';
        $tcm_pharmacy['nurseRosterParametrization']['@attributes']['id']=$k+1;
        $tcm_pharmacy['nurseRosterParametrization']['id']='0';//全局变量

        $index=$k+2;
        $last_index=count($shiftDates)-1;
        foreach ($shiftDates as $key => $shiftDate) {

            //如果是第一天
            if($key==0){
                $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['@attributes']['id']=$index;
                $firstDateIndex=$index;

                
                $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['id']=0;
                $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['dayIndex']=0;
                $index++;
                $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['date']['@attributes']['id']=$index;

                $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['date'][]['byte']='3';
                $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['date']['int']=$first_date_format['y'];
                $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['date'][]['byte']='3';
                $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['date'][]['byte']='3';
                $index++;
                $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['shiftList']['@attributes']['id']=$index;

                foreach($shiftDate['shiftList'] as $k=>$shift){
           
                    $shiftType=$this->getShiftTypeById($shift['shift_type_id']);
     
                    $index++;

                    $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['shiftList'][$k]['Shift']['@attributes']['id']=$index;

                    $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['shiftList'][$k]['Shift']['id']=$shift['id'];
                    $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['shiftList'][$k]['Shift']['shiftDate']['@attributes']['reference']=$firstDateIndex;

                    $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['shiftList'][$k]['Shift']['shiftDate']['@data']='';

                    $index++;

                    //记录shiftTypeID
                    $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['shiftList'][$k]['Shift']['shiftType']['@attributes']['id']=$index;
                    $shiftTypeIndex[$shiftType->id]=$index;
                    $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['shiftList'][$k]['Shift']['shiftType']['id']=$shiftType->id;
                    $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['shiftList'][$k]['Shift']['shiftType']['code']=$shiftType->name;
                    $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['shiftList'][$k]['Shift']['shiftType']['index']=$shiftType->id;
                    $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['shiftList'][$k]['Shift']['shiftType']['startTimeString']=$shiftType->start_time;
                    $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['shiftList'][$k]['Shift']['shiftType']['endTimeString']=$shiftType->end_time;
                    $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['shiftList'][$k]['Shift']['shiftType']['night']='false';
                    $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['shiftList'][$k]['Shift']['shiftType']['description']=$index;

                    $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['shiftList'][$k]['Shift']['index']=0;
                    $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']['shiftList'][$k]['Shift']['requiredEmployeeSize']=0;

                }

            } 

            if($key==$last_index){
                $index++;

                $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['@attributes']['id']=$index;
                
                $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['id']=0;
                $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['dayIndex']=0;
                $index++;
                $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['date']['@attributes']['id']=$index;
                $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['date'][]['byte']='3';
                $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['date']['int']=$first_date_format['y'];
                $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['date'][]['byte']='3';
                $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['date'][]['byte']='3';
                $index++;
                $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['shiftList']['@attributes']['id']=$index;

                foreach($shiftDates[$last_index]['shiftList'] as $k=>$shift){
           
                    $shiftType=$this->getShiftTypeById($shift['shift_type_id']);
   
                    $index++;
  // var_dump($index);exit;
                     $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['shiftList'][$k]['Shift']['@attributes']['id']=$index;
                    // $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['shiftList'][$k]['Shift']['@attributes']['id']=$shiftTypeIndex[$shiftType->id];

                    $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['shiftList'][$k]['Shift']['id']=$shift['id'];
                    $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['shiftList'][$k]['Shift']['shiftDate']['@attributes']['reference']=$firstDateIndex;
                    $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['shiftList'][$k]['Shift']['shiftDate']['@data']='';

                    $index++;

                    //记录shiftTypeID
                    $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['shiftList'][$k]['Shift']['shiftType']['@attributes']['reference']=$shiftTypeIndex[$shiftType->id];
                    $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['shiftList'][$k]['Shift']['shiftType']['@data']='';
                    

                    $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['shiftList'][$k]['Shift']['index']=0;
                    $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']['shiftList'][$k]['Shift']['requiredEmployeeSize']=0;


                }

            }

            //开始除了第一天和最后一天的排序
           



        }

        $tcm_pharmacy['nurseRosterParametrization']['planningWindowStart']['@attributes']['reference']=$firstDateIndex;
        $tcm_pharmacy['nurseRosterParametrization']['planningWindowStart']['@data']='';

        





  /*
        // $tcm_pharmacy['nurseRosterParametrization']['firstShiftDate']=$firstShiftDate;
        // $tcm_pharmacy['nurseRosterParametrization']['lastShiftDate']=$lastShiftDate;

        $tcm_pharmacy['shiftDateList']['@attributes']['id']=$scheduleID;

        foreach ($shiftDates as $key => $date) {
            $shiftDates[$key]['shiftList']=$dates[$date['id']];
            $shiftDates[$key]['shiftList']['@attributes']['id']=$key;

        }

        $i=1;








        foreach ($shiftDates as $k => $shifts_date) {
           $date_format=$this->getFormatDate($shifts_date['shiftDate']);
           if($k==$firstDate['id']){//第一个
                $tcm_pharmacy['shiftDateList'][$k]['ShiftDate']['@attributes']['reference']=$firstDate['id'];
                $tcm_pharmacy['shiftDateList'][$k]['ShiftDate']['@data']='';

           }else if($k==$endDate['id']){//最后一天
                $tcm_pharmacy['shiftDateList'][$k]['ShiftDate']['@attributes']['reference']=$endDate['id'];
                $tcm_pharmacy['shiftDateList'][$k]['ShiftDate']['@data']='';
           }else{
                
                $tcm_pharmacy['shiftDateList'][$k]['ShiftDate']['@attributes']['id']=$shifts_date['id'];
                $tcm_pharmacy['shiftDateList'][$k]['ShiftDate']['id']=$i;
                $tcm_pharmacy['shiftDateList'][$k]['ShiftDate']['dayIndex']=$i;
                $tcm_pharmacy['shiftDateList'][$k]['ShiftDate']['date']['@attributes']['id']=$shifts_date['id'];
                $tcm_pharmacy['shiftDateList'][$k]['ShiftDate']['date']['@attributes']['reference']='java.time.Ser';
                $tcm_pharmacy['shiftDateList'][$k]['ShiftDate']['date'][]['byte']='3';
                $tcm_pharmacy['shiftDateList'][$k]['ShiftDate']['date'][]['int']=$date_format['y'];
                $tcm_pharmacy['shiftDateList'][$k]['ShiftDate']['date'][]['byte']=$date_format['m'];
                $tcm_pharmacy['shiftDateList'][$k]['ShiftDate']['date'][]['byte']=$date_format['d'];
                $tcm_pharmacy['shiftDateList'][$k]['ShiftDate']['shiftList']['@attributes']['reference']='自定义';
                $tcm_pharmacy['shiftDateList'][$k]['ShiftDate']['shiftList']=$shifts_date['shiftList'];
                $i++;
           }
            
        }*/

        

        //shiftType列表；

        $tcm_pharmacy['shiftTypeList']['@attributes']['id']=$scheduleID;
        foreach ($shiftTypes as $key => $shiftType) {
           $tcm_pharmacy['shiftTypeList'][$key]['ShiftType']['@attributes']['reference']=$shiftType['id'];
           $tcm_pharmacy['shiftTypeList'][$key]['ShiftType']['@data']='';
        }

        //排班列表
        $tcm_pharmacy['shiftList']['@attributes']['id']=$scheduleID;
        if(empty($shifts)){

            $tcm_pharmacy['shiftList']['@data']='';

        }else{
            foreach ($shifts as $key => $shift) {
           $tcm_pharmacy['shiftList'][$key]['Shift']['@attributes']['reference']=$shift['id'];
           $tcm_pharmacy['shiftList'][$key]['Shift']['@data']='';
        }
        }
        

        //技能列表

        $tcm_pharmacy['skillList']['@attributes']['id']=$scheduleID;
        foreach ($skillList as $key => $skill) {
            $tcm_pharmacy['skillList'][$key]['Skill']['@attributes']['id']=$skill['id'];
            $tcm_pharmacy['skillList'][$key]['Skill']['id']=$key;
            $tcm_pharmacy['skillList'][$key]['Skill']['code']=$skill['name'];
        }
        
   

        //每个轮班类型所需岗位
        $tcm_pharmacy['shiftTypeSkillRequirementList']['@attributes']['id']=$scheduleID;
        foreach ($shiftTypeToSkillList as $key => $shiftTypeToSkill) {
         
            $tcm_pharmacy['shiftTypeSkillRequirementList'][$key]['ShiftTypeSkillRequirement']['@attributes']['id']=$shiftTypeToSkill['id'];
            $tcm_pharmacy['shiftTypeSkillRequirementList'][$key]['ShiftTypeSkillRequirement']['id']=$key;
            $tcm_pharmacy['shiftTypeSkillRequirementList'][$key]['ShiftTypeSkillRequirement']['shiftType']['@attributes']['reference']=$shiftTypeToSkill['shift_type_id'];
            $tcm_pharmacy['shiftTypeSkillRequirementList'][$key]['ShiftTypeSkillRequirement']['shiftType']['@data']='';
            $tcm_pharmacy['shiftTypeSkillRequirementList'][$key]['ShiftTypeSkillRequirement']['skill']['@attributes']['reference']=$shiftTypeToSkill['skill_id'];
            $tcm_pharmacy['shiftTypeSkillRequirementList'][$key]['ShiftTypeSkillRequirement']['skill']['@data']='';
        }

        //值班人员
        $tcm_pharmacy['patternList']['@attributes']['id']=$scheduleID;
        $tcm_pharmacy['patternList']['IdenticalShiftLastSomeDaysPattern']['@attributes']['id']='35';
        $tcm_pharmacy['patternList']['IdenticalShiftLastSomeDaysPattern']['id']='0';
        $tcm_pharmacy['patternList']['IdenticalShiftLastSomeDaysPattern']['code']='0';
        $tcm_pharmacy['patternList']['IdenticalShiftLastSomeDaysPattern']['weight']='0';
        $tcm_pharmacy['patternList']['IdenticalShiftLastSomeDaysPattern']['dayShiftType']['@attributes']['reference']='481';
        $tcm_pharmacy['patternList']['IdenticalShiftLastSomeDaysPattern']['dayShiftType']['@data']='';
        $tcm_pharmacy['patternList']['IdenticalShiftLastSomeDaysPattern']['ShiftLastLength']='0';
        


        $tcm_pharmacy['patternList']['ShiftAssignedOnlyforManPattern']['@attributes']['id']='36';
        $tcm_pharmacy['patternList']['ShiftAssignedOnlyforManPattern']['id']='1';
        $tcm_pharmacy['patternList']['ShiftAssignedOnlyforManPattern']['code']='1';
        $tcm_pharmacy['patternList']['ShiftAssignedOnlyforManPattern']['weight']='5';
        $tcm_pharmacy['patternList']['ShiftAssignedOnlyforManPattern']['dayShiftType']['@attributes']['reference']='481';
        $tcm_pharmacy['patternList']['ShiftAssignedOnlyforManPattern']['dayShiftType']['@data']='';


        $tcm_pharmacy['patternList']['FreeAfterWeekendWorkDayPattern']['@attributes']['id']='37';
        $tcm_pharmacy['patternList']['FreeAfterWeekendWorkDayPattern']['id']='2';
        $tcm_pharmacy['patternList']['FreeAfterWeekendWorkDayPattern']['code']='2';
        $tcm_pharmacy['patternList']['FreeAfterWeekendWorkDayPattern']['weight']='0';
        $tcm_pharmacy['patternList']['FreeAfterWeekendWorkDayPattern']['workDayOfWeek']='SATURDAY';
    



        $tcm_pharmacy['patternList']['FreeAfterANightShiftPattern']['@attributes']['id']='38';
        $tcm_pharmacy['patternList']['FreeAfterANightShiftPattern']['id']='3';
        $tcm_pharmacy['patternList']['FreeAfterANightShiftPattern']['code']='3';
        $tcm_pharmacy['patternList']['FreeAfterANightShiftPattern']['weight']='5';
        $tcm_pharmacy['patternList']['FreeAfterANightShiftPattern']['dayShiftType']['@attributes']['reference']='803';
        $tcm_pharmacy['patternList']['FreeAfterANightShiftPattern']['dayShiftType']['@data']='';



        $tcm_pharmacy['patternList']['ShiftAssignedAveragedAtAllEmployeesPattern']['@attributes']['id']='39';
        $tcm_pharmacy['patternList']['ShiftAssignedAveragedAtAllEmployeesPattern']['id']='4';
        $tcm_pharmacy['patternList']['ShiftAssignedAveragedAtAllEmployeesPattern']['code']='4';
        $tcm_pharmacy['patternList']['ShiftAssignedAveragedAtAllEmployeesPattern']['weight']='5';
        $tcm_pharmacy['patternList']['ShiftAssignedAveragedAtAllEmployeesPattern']['dayShiftType']['@attributes']['reference']='482';
        $tcm_pharmacy['patternList']['ShiftAssignedAveragedAtAllEmployeesPattern']['dayShiftType']['@data']='';
        $tcm_pharmacy['patternList']['ShiftAssignedAveragedAtAllEmployeesPattern']['dayShiftLength']='1';


        $tcm_pharmacy['patternList']['FreeTwoDaysEveryWeekPattern']['@attributes']['id']='40';
        $tcm_pharmacy['patternList']['FreeTwoDaysEveryWeekPattern']['id']='5';
        $tcm_pharmacy['patternList']['FreeTwoDaysEveryWeekPattern']['code']='5';
        $tcm_pharmacy['patternList']['FreeTwoDaysEveryWeekPattern']['weight']='3';
        $tcm_pharmacy['patternList']['FreeTwoDaysEveryWeekPattern']['workDayLength']='7';
        $tcm_pharmacy['patternList']['FreeTwoDaysEveryWeekPattern']['freeDayLength']='2';

        $tcm_pharmacy['patternList']['ShiftAssignedSomeWeeksPattern']['@attributes']['id']='41';
        $tcm_pharmacy['patternList']['ShiftAssignedSomeWeeksPattern']['id']='6';
        $tcm_pharmacy['patternList']['ShiftAssignedSomeWeeksPattern']['code']='6';
        $tcm_pharmacy['patternList']['ShiftAssignedSomeWeeksPattern']['weight']='0';
        $tcm_pharmacy['patternList']['ShiftAssignedSomeWeeksPattern']['dayShiftType']['@attributes']['reference']='481';
        $tcm_pharmacy['patternList']['ShiftAssignedSomeWeeksPattern']['dayShiftType']['@data']='';
        $tcm_pharmacy['patternList']['ShiftAssignedSomeWeeksPattern']['weekGapLength']='2';


        $tcm_pharmacy['patternList'][1]['ShiftType2DaysPattern']['@attributes']['id']='42';
        $tcm_pharmacy['patternList'][1]['ShiftType2DaysPattern']['id']='7';
        $tcm_pharmacy['patternList'][1]['ShiftType2DaysPattern']['code']='7';
        $tcm_pharmacy['patternList'][1]['ShiftType2DaysPattern']['weight']='1';
        $tcm_pharmacy['patternList'][1]['ShiftType2DaysPattern']['dayIndex0ShiftType']['@attributes']['reference']='481';
        $tcm_pharmacy['patternList'][1]['ShiftType2DaysPattern']['dayIndex0ShiftType']['@data']='';
        $tcm_pharmacy['patternList'][1]['ShiftType2DaysPattern']['dayIndex1ShiftType']['@attributes']['reference']='482';
        $tcm_pharmacy['patternList'][1]['ShiftType2DaysPattern']['dayIndex1ShiftType']['@data']='';

        $tcm_pharmacy['patternList'][2]['ShiftType2DaysPattern']['@attributes']['id']='43';
        $tcm_pharmacy['patternList'][2]['ShiftType2DaysPattern']['id']='8';
        $tcm_pharmacy['patternList'][2]['ShiftType2DaysPattern']['code']='8';
        $tcm_pharmacy['patternList'][2]['ShiftType2DaysPattern']['weight']='1';
        $tcm_pharmacy['patternList'][2]['ShiftType2DaysPattern']['dayIndex0ShiftType']['@attributes']['reference']='481';
        $tcm_pharmacy['patternList'][2]['ShiftType2DaysPattern']['dayIndex0ShiftType']['@data']='';
        $tcm_pharmacy['patternList'][2]['ShiftType2DaysPattern']['dayIndex1ShiftType']['@attributes']['reference']='482';
        $tcm_pharmacy['patternList'][2]['ShiftType2DaysPattern']['dayIndex1ShiftType']['@data']='';



        //contractList
       //第一个contranct
        $tcm_pharmacy['contractList']['@attributes']['id']='44';
        $tcm_pharmacy['contractList'][1]['Contract']['@attributes']['id']='45';
        $tcm_pharmacy['contractList'][1]['Contract']['id']='0';
        $tcm_pharmacy['contractList'][1]['Contract']['code']='0';
        $tcm_pharmacy['contractList'][1]['Contract']['description']='fulltime';
        $tcm_pharmacy['contractList'][1]['Contract']['weekendDefinition']='SATURDAY_SUNDAY';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList']['@attributes']['id']='46';

        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][0]['BooleanContractLine']['@attributes']['id']='47';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][0]['BooleanContractLine']['id']='0';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][0]['BooleanContractLine']['contract']['@attributes']['reference']='45';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][0]['BooleanContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][0]['BooleanContractLine']['contractLineType']='SINGLE_ASSIGNMENT_PER_DAY';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][0]['BooleanContractLine']['enabled']='true';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][0]['BooleanContractLine']['weight']='1';

  
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][1]['MinMaxContractLine']['@attributes']['id']='48';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][1]['MinMaxContractLine']['id']='1';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][1]['MinMaxContractLine']['contract']['@attributes']['reference']='45';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][1]['MinMaxContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][1]['MinMaxContractLine']['contractLineType']='TOTAL_ASSIGNMENTS';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][1]['MinMaxContractLine']['minimumEnabled']='true';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][1]['MinMaxContractLine']['minimumValue']='10';  
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][1]['MinMaxContractLine']['minimumWeight']='1';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][1]['MinMaxContractLine']['maximumEnabled']='true';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][1]['MinMaxContractLine']['maximumValue']='20';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][1]['MinMaxContractLine']['maximumWeight']='1';

        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][2]['MinMaxContractLine']['@attributes']['id']='49';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][2]['MinMaxContractLine']['id']='3';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][2]['MinMaxContractLine']['contract']['@attributes']['reference']='45';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][2]['MinMaxContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][2]['MinMaxContractLine']['contractLineType']='CONSECUTIVE_WORKING_DAYS';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][2]['MinMaxContractLine']['minimumEnabled']='true';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][2]['MinMaxContractLine']['minimumValue']='3';  
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][2]['MinMaxContractLine']['minimumWeight']='1';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][2]['MinMaxContractLine']['maximumEnabled']='true';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][2]['MinMaxContractLine']['maximumValue']='5';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][2]['MinMaxContractLine']['maximumWeight']='1';

        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][3]['MinMaxContractLine']['@attributes']['id']='50';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][3]['MinMaxContractLine']['id']='3';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][3]['MinMaxContractLine']['contract']['@attributes']['reference']='45';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][3]['MinMaxContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][3]['MinMaxContractLine']['contractLineType']='CONSECUTIVE_FREE_DAYS';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][3]['MinMaxContractLine']['minimumEnabled']='true';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][3]['MinMaxContractLine']['minimumValue']='3';  
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][3]['MinMaxContractLine']['minimumWeight']='1';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][3]['MinMaxContractLine']['maximumEnabled']='true';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][3]['MinMaxContractLine']['maximumValue']='4';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][3]['MinMaxContractLine']['maximumWeight']='1';

        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][4]['MinMaxContractLine']['@attributes']['id']='51';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][4]['MinMaxContractLine']['id']='4';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][4]['MinMaxContractLine']['contract']['@attributes']['reference']='45';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][4]['MinMaxContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][4]['MinMaxContractLine']['contractLineType']='CONSECUTIVE_WORKING_WEEKENDS';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][4]['MinMaxContractLine']['minimumEnabled']='true';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][4]['MinMaxContractLine']['minimumValue']='2';  
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][4]['MinMaxContractLine']['minimumWeight']='1';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][4]['MinMaxContractLine']['maximumEnabled']='true';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][4]['MinMaxContractLine']['maximumValue']='3';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][4]['MinMaxContractLine']['maximumWeight']='1';

        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][5]['BooleanContractLine']['@attributes']['id']='52';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][5]['BooleanContractLine']['id']='5';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][5]['BooleanContractLine']['contract']['@attributes']['reference']='45';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][5]['BooleanContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][5]['BooleanContractLine']['contractLineType']='COMPLETE_WEEKENDS';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][5]['BooleanContractLine']['enabled']='true';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][5]['BooleanContractLine']['weight']='1';

        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][6]['BooleanContractLine']['@attributes']['id']='53';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][6]['BooleanContractLine']['id']='6';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][6]['BooleanContractLine']['contract']['@attributes']['reference']='45';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][6]['BooleanContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][6]['BooleanContractLine']['contractLineType']='IDENTICAL_SHIFT_TYPES_DURING_WEEKEND';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][6]['BooleanContractLine']['enabled']='true';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][6]['BooleanContractLine']['weight']='1';

        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][7]['BooleanContractLine']['@attributes']['id']='54';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][7]['BooleanContractLine']['id']='7';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][7]['BooleanContractLine']['contract']['@attributes']['reference']='45';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][7]['BooleanContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][7]['BooleanContractLine']['contractLineType']='NO_NIGHT_SHIFT_BEFORE_FREE_WEEKEND';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][7]['BooleanContractLine']['enabled']='true';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][7]['BooleanContractLine']['weight']='1';

        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][7]['BooleanContractLine']['@attributes']['id']='55';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][7]['BooleanContractLine']['id']='8';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][7]['BooleanContractLine']['contract']['@attributes']['reference']='45';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][7]['BooleanContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][7]['BooleanContractLine']['contractLineType']='ALTERNATIVE_SKILL_CATEGORY';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][7]['BooleanContractLine']['enabled']='true';
        $tcm_pharmacy['contractList'][1]['Contract']['contractLineList'][7]['BooleanContractLine']['weight']='1';

        


        $tcm_pharmacy['contractList'][2]['Contract']['@attributes']['id']='56';
        $tcm_pharmacy['contractList'][2]['Contract']['id']='1';
        $tcm_pharmacy['contractList'][2]['Contract']['code']='1';
        $tcm_pharmacy['contractList'][2]['Contract']['description']='75_time';
        $tcm_pharmacy['contractList'][2]['Contract']['weekendDefinition']='SATURDAY_SUNDAY';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList']['@attributes']['id']='57';

        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][0]['BooleanContractLine']['@attributes']['id']='58';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][0]['BooleanContractLine']['id']='9';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][0]['BooleanContractLine']['contract']['@attributes']['reference']='56';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][0]['BooleanContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][0]['BooleanContractLine']['contractLineType']='SINGLE_ASSIGNMENT_PER_DAY';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][0]['BooleanContractLine']['enabled']='true';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][0]['BooleanContractLine']['weight']='1';

  
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][1]['MinMaxContractLine']['@attributes']['id']='59';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][1]['MinMaxContractLine']['id']='10';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][1]['MinMaxContractLine']['contract']['@attributes']['reference']='56';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][1]['MinMaxContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][1]['MinMaxContractLine']['contractLineType']='TOTAL_ASSIGNMENTS';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][1]['MinMaxContractLine']['minimumEnabled']='true';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][1]['MinMaxContractLine']['minimumValue']='6';  
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][1]['MinMaxContractLine']['minimumWeight']='1';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][1]['MinMaxContractLine']['maximumEnabled']='true';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][1]['MinMaxContractLine']['maximumValue']='15';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][1]['MinMaxContractLine']['maximumWeight']='1';

        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][2]['MinMaxContractLine']['@attributes']['id']='60';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][2]['MinMaxContractLine']['id']='11';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][2]['MinMaxContractLine']['contract']['@attributes']['reference']='56';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][2]['MinMaxContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][2]['MinMaxContractLine']['contractLineType']='CONSECUTIVE_WORKING_DAYS';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][2]['MinMaxContractLine']['minimumEnabled']='true';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][2]['MinMaxContractLine']['minimumValue']='3';  
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][2]['MinMaxContractLine']['minimumWeight']='1';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][2]['MinMaxContractLine']['maximumEnabled']='true';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][2]['MinMaxContractLine']['maximumValue']='5';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][2]['MinMaxContractLine']['maximumWeight']='1';

        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][3]['MinMaxContractLine']['@attributes']['id']='61';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][3]['MinMaxContractLine']['id']='12';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][3]['MinMaxContractLine']['contract']['@attributes']['reference']='56';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][3]['MinMaxContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][3]['MinMaxContractLine']['contractLineType']='CONSECUTIVE_FREE_DAYS';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][3]['MinMaxContractLine']['minimumEnabled']='true';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][3]['MinMaxContractLine']['minimumValue']='2';  
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][3]['MinMaxContractLine']['minimumWeight']='1';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][3]['MinMaxContractLine']['maximumEnabled']='true';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][3]['MinMaxContractLine']['maximumValue']='5';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][3]['MinMaxContractLine']['maximumWeight']='1';

        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][4]['MinMaxContractLine']['@attributes']['id']='62';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][4]['MinMaxContractLine']['id']='13';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][4]['MinMaxContractLine']['contract']['@attributes']['reference']='56';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][4]['MinMaxContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][4]['MinMaxContractLine']['contractLineType']='CONSECUTIVE_WORKING_WEEKENDS';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][4]['MinMaxContractLine']['minimumEnabled']='true';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][4]['MinMaxContractLine']['minimumValue']='1';  
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][4]['MinMaxContractLine']['minimumWeight']='1';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][4]['MinMaxContractLine']['maximumEnabled']='true';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][4]['MinMaxContractLine']['maximumValue']='3';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][4]['MinMaxContractLine']['maximumWeight']='1';

        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][5]['BooleanContractLine']['@attributes']['id']='63';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][5]['BooleanContractLine']['id']='14';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][5]['BooleanContractLine']['contract']['@attributes']['reference']='56';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][5]['BooleanContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][5]['BooleanContractLine']['contractLineType']='COMPLETE_WEEKENDS';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][5]['BooleanContractLine']['enabled']='true';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][5]['BooleanContractLine']['weight']='1';

        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][6]['BooleanContractLine']['@attributes']['id']='64';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][6]['BooleanContractLine']['id']='15';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][6]['BooleanContractLine']['contract']['@attributes']['reference']='56';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][6]['BooleanContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][6]['BooleanContractLine']['contractLineType']='IDENTICAL_SHIFT_TYPES_DURING_WEEKEND';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][6]['BooleanContractLine']['enabled']='true';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][6]['BooleanContractLine']['weight']='1';

        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][7]['BooleanContractLine']['@attributes']['id']='65';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][7]['BooleanContractLine']['id']='16';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][7]['BooleanContractLine']['contract']['@attributes']['reference']='56';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][7]['BooleanContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][7]['BooleanContractLine']['contractLineType']='NO_NIGHT_SHIFT_BEFORE_FREE_WEEKEND';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][7]['BooleanContractLine']['enabled']='true';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][7]['BooleanContractLine']['weight']='1';

        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][7]['BooleanContractLine']['@attributes']['id']='66';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][7]['BooleanContractLine']['id']='17';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][7]['BooleanContractLine']['contract']['@attributes']['reference']='56';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][7]['BooleanContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][7]['BooleanContractLine']['contractLineType']='ALTERNATIVE_SKILL_CATEGORY';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][7]['BooleanContractLine']['enabled']='true';
        $tcm_pharmacy['contractList'][2]['Contract']['contractLineList'][7]['BooleanContractLine']['weight']='1';
       



        $tcm_pharmacy['contractList'][3]['Contract']['@attributes']['id']='67';
        $tcm_pharmacy['contractList'][3]['Contract']['id']='2';
        $tcm_pharmacy['contractList'][3]['Contract']['code']='2';
        $tcm_pharmacy['contractList'][3]['Contract']['description']='50_percent';
        $tcm_pharmacy['contractList'][3]['Contract']['weekendDefinition']='FRIDAY_SATURDAY_SUNDAY';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList']['@attributes']['id']='68';

        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][0]['BooleanContractLine']['@attributes']['id']='69';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][0]['BooleanContractLine']['id']='18';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][0]['BooleanContractLine']['contract']['@attributes']['reference']='67';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][0]['BooleanContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][0]['BooleanContractLine']['contractLineType']='SINGLE_ASSIGNMENT_PER_DAY';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][0]['BooleanContractLine']['enabled']='true';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][0]['BooleanContractLine']['weight']='1';

  
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][1]['MinMaxContractLine']['@attributes']['id']='70';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][1]['MinMaxContractLine']['id']='19';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][1]['MinMaxContractLine']['contract']['@attributes']['reference']='67';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][1]['MinMaxContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][1]['MinMaxContractLine']['contractLineType']='TOTAL_ASSIGNMENTS';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][1]['MinMaxContractLine']['minimumEnabled']='true';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][1]['MinMaxContractLine']['minimumValue']='4';  
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][1]['MinMaxContractLine']['minimumWeight']='1';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][1]['MinMaxContractLine']['maximumEnabled']='true';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][1]['MinMaxContractLine']['maximumValue']='10';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][1]['MinMaxContractLine']['maximumWeight']='1';

        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][2]['MinMaxContractLine']['@attributes']['id']='71';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][2]['MinMaxContractLine']['id']='20';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][2]['MinMaxContractLine']['contract']['@attributes']['reference']='67';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][2]['MinMaxContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][2]['MinMaxContractLine']['contractLineType']='CONSECUTIVE_WORKING_DAYS';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][2]['MinMaxContractLine']['minimumEnabled']='true';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][2]['MinMaxContractLine']['minimumValue']='2';  
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][2]['MinMaxContractLine']['minimumWeight']='1';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][2]['MinMaxContractLine']['maximumEnabled']='true';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][2]['MinMaxContractLine']['maximumValue']='4';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][2]['MinMaxContractLine']['maximumWeight']='1';

        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][3]['MinMaxContractLine']['@attributes']['id']='72';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][3]['MinMaxContractLine']['id']='21';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][3]['MinMaxContractLine']['contract']['@attributes']['reference']='67';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][3]['MinMaxContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][3]['MinMaxContractLine']['contractLineType']='CONSECUTIVE_FREE_DAYS';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][3]['MinMaxContractLine']['minimumEnabled']='true';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][3]['MinMaxContractLine']['minimumValue']='3';  
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][3]['MinMaxContractLine']['minimumWeight']='1';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][3]['MinMaxContractLine']['maximumEnabled']='true';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][3]['MinMaxContractLine']['maximumValue']='5';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][3]['MinMaxContractLine']['maximumWeight']='1';

        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][4]['MinMaxContractLine']['@attributes']['id']='73';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][4]['MinMaxContractLine']['id']='22';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][4]['MinMaxContractLine']['contract']['@attributes']['reference']='67';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][4]['MinMaxContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][4]['MinMaxContractLine']['contractLineType']='CONSECUTIVE_WORKING_WEEKENDS';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][4]['MinMaxContractLine']['minimumEnabled']='true';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][4]['MinMaxContractLine']['minimumValue']='0';  
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][4]['MinMaxContractLine']['minimumWeight']='0';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][4]['MinMaxContractLine']['maximumEnabled']='true';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][4]['MinMaxContractLine']['maximumValue']='1';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][4]['MinMaxContractLine']['maximumWeight']='1';

        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][5]['BooleanContractLine']['@attributes']['id']='74';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][5]['BooleanContractLine']['id']='23';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][5]['BooleanContractLine']['contract']['@attributes']['reference']='67';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][5]['BooleanContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][5]['BooleanContractLine']['contractLineType']='COMPLETE_WEEKENDS';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][5]['BooleanContractLine']['enabled']='true';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][5]['BooleanContractLine']['weight']='1';

        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][6]['BooleanContractLine']['@attributes']['id']='75';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][6]['BooleanContractLine']['id']='24';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][6]['BooleanContractLine']['contract']['@attributes']['reference']='67';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][6]['BooleanContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][6]['BooleanContractLine']['contractLineType']='IDENTICAL_SHIFT_TYPES_DURING_WEEKEND';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][6]['BooleanContractLine']['enabled']='true';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][6]['BooleanContractLine']['weight']='1';

        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][7]['BooleanContractLine']['@attributes']['id']='76';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][7]['BooleanContractLine']['id']='25';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][7]['BooleanContractLine']['contract']['@attributes']['reference']='67';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][7]['BooleanContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][7]['BooleanContractLine']['contractLineType']='NO_NIGHT_SHIFT_BEFORE_FREE_WEEKEND';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][7]['BooleanContractLine']['enabled']='true';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][7]['BooleanContractLine']['weight']='1';

        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][7]['BooleanContractLine']['@attributes']['id']='77';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][7]['BooleanContractLine']['id']='26';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][7]['BooleanContractLine']['contract']['@attributes']['reference']='67';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][7]['BooleanContractLine']['contract']['@data']='';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][7]['BooleanContractLine']['contractLineType']='ALTERNATIVE_SKILL_CATEGORY';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][7]['BooleanContractLine']['enabled']='true';
        $tcm_pharmacy['contractList'][3]['Contract']['contractLineList'][7]['BooleanContractLine']['weight']='1';



        //contractLineList列表
        $tcm_pharmacy['contractLineList']['@attributes']['id']='1';

        $tcm_pharmacy['contractLineList'][1]['BooleanContractLine']['@attributes']['reference']='47';
        $tcm_pharmacy['contractLineList'][1]['BooleanContractLine']['@data']='';

        $tcm_pharmacy['contractLineList'][2]['MinMaxContractLine']['@attributes']['reference']='48';
        $tcm_pharmacy['contractLineList'][2]['MinMaxContractLine']['@data']='';
        $tcm_pharmacy['contractLineList'][3]['MinMaxContractLine']['@attributes']['reference']='49';
        $tcm_pharmacy['contractLineList'][3]['MinMaxContractLine']['@data']='';
        $tcm_pharmacy['contractLineList'][4]['MinMaxContractLine']['@attributes']['reference']='50';
        $tcm_pharmacy['contractLineList'][4]['MinMaxContractLine']['@data']='';
        $tcm_pharmacy['contractLineList'][5]['MinMaxContractLine']['@attributes']['reference']='51';
        $tcm_pharmacy['contractLineList'][5]['MinMaxContractLine']['@data']='';

        $tcm_pharmacy['contractLineList'][6]['BooleanContractLine']['@attributes']['reference']='52';
        $tcm_pharmacy['contractLineList'][6]['BooleanContractLine']['@data']='';
        $tcm_pharmacy['contractLineList'][7]['BooleanContractLine']['@attributes']['reference']='53';
        $tcm_pharmacy['contractLineList'][7]['BooleanContractLine']['@data']='';
        $tcm_pharmacy['contractLineList'][8]['BooleanContractLine']['@attributes']['reference']='54';
        $tcm_pharmacy['contractLineList'][8]['BooleanContractLine']['@data']='';
        $tcm_pharmacy['contractLineList'][9]['BooleanContractLine']['@attributes']['reference']='55';
        $tcm_pharmacy['contractLineList'][9]['BooleanContractLine']['@data']='';
        $tcm_pharmacy['contractLineList'][10]['BooleanContractLine']['@attributes']['reference']='58';
        $tcm_pharmacy['contractLineList'][10]['BooleanContractLine']['@data']='';

        $tcm_pharmacy['contractLineList'][11]['MinMaxContractLine']['@attributes']['reference']='59';
        $tcm_pharmacy['contractLineList'][11]['MinMaxContractLine']['@data']='';
        $tcm_pharmacy['contractLineList'][12]['MinMaxContractLine']['@attributes']['reference']='60';
        $tcm_pharmacy['contractLineList'][12]['MinMaxContractLine']['@data']='';
        $tcm_pharmacy['contractLineList'][13]['MinMaxContractLine']['@attributes']['reference']='61';
        $tcm_pharmacy['contractLineList'][13]['MinMaxContractLine']['@data']='';
        $tcm_pharmacy['contractLineList'][14]['MinMaxContractLine']['@attributes']['reference']='62';
        $tcm_pharmacy['contractLineList'][14]['MinMaxContractLine']['@data']='';

        $tcm_pharmacy['contractLineList'][15]['BooleanContractLine']['@attributes']['reference']='63';
        $tcm_pharmacy['contractLineList'][15]['BooleanContractLine']['@data']='';
        $tcm_pharmacy['contractLineList'][16]['BooleanContractLine']['@attributes']['reference']='64';
        $tcm_pharmacy['contractLineList'][16]['BooleanContractLine']['@data']='';
        $tcm_pharmacy['contractLineList'][17]['BooleanContractLine']['@attributes']['reference']='65';
        $tcm_pharmacy['contractLineList'][17]['BooleanContractLine']['@data']='';
        $tcm_pharmacy['contractLineList'][18]['BooleanContractLine']['@attributes']['reference']='66';
        $tcm_pharmacy['contractLineList'][18]['BooleanContractLine']['@data']='';
        $tcm_pharmacy['contractLineList'][19]['BooleanContractLine']['@attributes']['reference']='69';
        $tcm_pharmacy['contractLineList'][19]['BooleanContractLine']['@data']='';

        $tcm_pharmacy['contractLineList'][20]['MinMaxContractLine']['@attributes']['reference']='70';
        $tcm_pharmacy['contractLineList'][20]['MinMaxContractLine']['@data']='';
        $tcm_pharmacy['contractLineList'][21]['MinMaxContractLine']['@attributes']['reference']='71';
        $tcm_pharmacy['contractLineList'][21]['MinMaxContractLine']['@data']='';
        $tcm_pharmacy['contractLineList'][22]['MinMaxContractLine']['@attributes']['reference']='72';
        $tcm_pharmacy['contractLineList'][22]['MinMaxContractLine']['@data']='';
        $tcm_pharmacy['contractLineList'][23]['MinMaxContractLine']['@attributes']['reference']='73';
        $tcm_pharmacy['contractLineList'][23]['MinMaxContractLine']['@data']='';

        $tcm_pharmacy['contractLineList'][24]['BooleanContractLine']['@attributes']['reference']='74';
        $tcm_pharmacy['contractLineList'][24]['BooleanContractLine']['@data']='';
        $tcm_pharmacy['contractLineList'][25]['BooleanContractLine']['@attributes']['reference']='75';
        $tcm_pharmacy['contractLineList'][25]['BooleanContractLine']['@data']='';
        $tcm_pharmacy['contractLineList'][26]['BooleanContractLine']['@attributes']['reference']='76';
        $tcm_pharmacy['contractLineList'][26]['BooleanContractLine']['@data']='';
        $tcm_pharmacy['contractLineList'][27]['BooleanContractLine']['@attributes']['reference']='77';
        $tcm_pharmacy['contractLineList'][27]['BooleanContractLine']['@data']='';



        //patternContractLineList

        $tcm_pharmacy['patternContractLineList']['@attributes']['id']='79';

        $tcm_pharmacy['patternContractLineList'][1]['PatternContractLine']['@attributes']['id']='80';
        $tcm_pharmacy['patternContractLineList'][1]['PatternContractLine']['id']='0';
        $tcm_pharmacy['patternContractLineList'][1]['PatternContractLine']['contract']['@attributes']['reference']='45';
        $tcm_pharmacy['patternContractLineList'][1]['PatternContractLine']['contract']['@data']='';
        $tcm_pharmacy['patternContractLineList'][1]['PatternContractLine']['pattern']['@attributes']['reference']='35';
         $tcm_pharmacy['patternContractLineList'][1]['PatternContractLine']['pattern']['@attributes']['class']='IdenticalShiftLastSomeDaysPattern';
        $tcm_pharmacy['patternContractLineList'][1]['PatternContractLine']['pattern']['@data']='';


        $tcm_pharmacy['patternContractLineList'][2]['PatternContractLine']['@attributes']['id']='81';
        $tcm_pharmacy['patternContractLineList'][2]['PatternContractLine']['id']='1';
        $tcm_pharmacy['patternContractLineList'][2]['PatternContractLine']['contract']['@attributes']['reference']='45';
        $tcm_pharmacy['patternContractLineList'][2]['PatternContractLine']['contract']['@data']='';
        $tcm_pharmacy['patternContractLineList'][2]['PatternContractLine']['pattern']['@attributes']['reference']='36';
         $tcm_pharmacy['patternContractLineList'][2]['PatternContractLine']['pattern']['@attributes']['class']='ShiftAssignedOnlyforManPattern';
        $tcm_pharmacy['patternContractLineList'][2]['PatternContractLine']['pattern']['@data']='';



        $tcm_pharmacy['patternContractLineList'][3]['PatternContractLine']['@attributes']['id']='82';
        $tcm_pharmacy['patternContractLineList'][3]['PatternContractLine']['id']='2';
        $tcm_pharmacy['patternContractLineList'][3]['PatternContractLine']['contract']['@attributes']['reference']='45';
        $tcm_pharmacy['patternContractLineList'][3]['PatternContractLine']['contract']['@data']='';
        $tcm_pharmacy['patternContractLineList'][3]['PatternContractLine']['pattern']['@attributes']['reference']='37';
         $tcm_pharmacy['patternContractLineList'][3]['PatternContractLine']['pattern']['@attributes']['class']='FreeAfterWeekendWorkDayPattern';
        $tcm_pharmacy['patternContractLineList'][3]['PatternContractLine']['pattern']['@data']='';


        $tcm_pharmacy['patternContractLineList'][4]['PatternContractLine']['@attributes']['id']='83';
        $tcm_pharmacy['patternContractLineList'][4]['PatternContractLine']['id']='3';
        $tcm_pharmacy['patternContractLineList'][4]['PatternContractLine']['contract']['@attributes']['reference']='45';
        $tcm_pharmacy['patternContractLineList'][4]['PatternContractLine']['contract']['@data']='';
        $tcm_pharmacy['patternContractLineList'][4]['PatternContractLine']['pattern']['@attributes']['reference']='38';
         $tcm_pharmacy['patternContractLineList'][4]['PatternContractLine']['pattern']['@attributes']['class']='FreeAfterANightShiftPattern';
        $tcm_pharmacy['patternContractLineList'][4]['PatternContractLine']['pattern']['@data']='';

        $tcm_pharmacy['patternContractLineList'][5]['PatternContractLine']['@attributes']['id']='84';
        $tcm_pharmacy['patternContractLineList'][5]['PatternContractLine']['id']='4';
        $tcm_pharmacy['patternContractLineList'][5]['PatternContractLine']['contract']['@attributes']['reference']='45';
        $tcm_pharmacy['patternContractLineList'][5]['PatternContractLine']['contract']['@data']='';
        $tcm_pharmacy['patternContractLineList'][5]['PatternContractLine']['pattern']['@attributes']['reference']='39';
         $tcm_pharmacy['patternContractLineList'][5]['PatternContractLine']['pattern']['@attributes']['class']='ShiftAssignedAveragedAtAllEmployeesPattern';
        $tcm_pharmacy['patternContractLineList'][5]['PatternContractLine']['pattern']['@data']='';


        $tcm_pharmacy['patternContractLineList'][6]['PatternContractLine']['@attributes']['id']='85';
        $tcm_pharmacy['patternContractLineList'][6]['PatternContractLine']['id']='5';
        $tcm_pharmacy['patternContractLineList'][6]['PatternContractLine']['contract']['@attributes']['reference']='45';
        $tcm_pharmacy['patternContractLineList'][6]['PatternContractLine']['contract']['@data']='';
        $tcm_pharmacy['patternContractLineList'][6]['PatternContractLine']['pattern']['@attributes']['reference']='40';
         $tcm_pharmacy['patternContractLineList'][6]['PatternContractLine']['pattern']['@attributes']['class']='FreeTwoDaysEveryWeekPattern';
        $tcm_pharmacy['patternContractLineList'][6]['PatternContractLine']['pattern']['@data']='';


        $tcm_pharmacy['patternContractLineList'][7]['PatternContractLine']['@attributes']['id']='86';
        $tcm_pharmacy['patternContractLineList'][7]['PatternContractLine']['id']='6';
        $tcm_pharmacy['patternContractLineList'][7]['PatternContractLine']['contract']['@attributes']['reference']='45';
        $tcm_pharmacy['patternContractLineList'][7]['PatternContractLine']['contract']['@data']='';
        $tcm_pharmacy['patternContractLineList'][7]['PatternContractLine']['pattern']['@attributes']['reference']='41';
         $tcm_pharmacy['patternContractLineList'][7]['PatternContractLine']['pattern']['@attributes']['class']='ShiftAssignedSomeWeeksPattern';
        $tcm_pharmacy['patternContractLineList'][7]['PatternContractLine']['pattern']['@data']='';

        $tcm_pharmacy['patternContractLineList'][8]['PatternContractLine']['@attributes']['id']='87';
        $tcm_pharmacy['patternContractLineList'][8]['PatternContractLine']['id']='7';
        $tcm_pharmacy['patternContractLineList'][8]['PatternContractLine']['contract']['@attributes']['reference']='45';
        $tcm_pharmacy['patternContractLineList'][8]['PatternContractLine']['contract']['@data']='';
        $tcm_pharmacy['patternContractLineList'][8]['PatternContractLine']['pattern']['@attributes']['reference']='42';
         $tcm_pharmacy['patternContractLineList'][8]['PatternContractLine']['pattern']['@attributes']['class']='ShiftType2DaysPattern';
        $tcm_pharmacy['patternContractLineList'][8]['PatternContractLine']['pattern']['@data']='';


        $tcm_pharmacy['patternContractLineList'][9]['PatternContractLine']['@attributes']['id']='88';
        $tcm_pharmacy['patternContractLineList'][9]['PatternContractLine']['id']='8';
        $tcm_pharmacy['patternContractLineList'][9]['PatternContractLine']['contract']['@attributes']['reference']='45';
        $tcm_pharmacy['patternContractLineList'][9]['PatternContractLine']['contract']['@data']='';
        $tcm_pharmacy['patternContractLineList'][9]['PatternContractLine']['pattern']['@attributes']['reference']='43';
         $tcm_pharmacy['patternContractLineList'][9]['PatternContractLine']['pattern']['@attributes']['class']='ShiftType2DaysPattern';
        $tcm_pharmacy['patternContractLineList'][9]['PatternContractLine']['pattern']['@data']='';


        $tcm_pharmacy['patternContractLineList'][10]['PatternContractLine']['@attributes']['id']='89';
        $tcm_pharmacy['patternContractLineList'][10]['PatternContractLine']['id']='9';
        $tcm_pharmacy['patternContractLineList'][10]['PatternContractLine']['contract']['@attributes']['reference']='56';
        $tcm_pharmacy['patternContractLineList'][10]['PatternContractLine']['contract']['@data']='';
        $tcm_pharmacy['patternContractLineList'][10]['PatternContractLine']['pattern']['@attributes']['reference']='35';
         $tcm_pharmacy['patternContractLineList'][10]['PatternContractLine']['pattern']['@attributes']['class']='IdenticalShiftLastSomeDaysPattern';
        $tcm_pharmacy['patternContractLineList'][10]['PatternContractLine']['pattern']['@data']='';


        $tcm_pharmacy['patternContractLineList'][11]['PatternContractLine']['@attributes']['id']='90';
        $tcm_pharmacy['patternContractLineList'][11]['PatternContractLine']['id']='10';
        $tcm_pharmacy['patternContractLineList'][11]['PatternContractLine']['contract']['@attributes']['reference']='56';
        $tcm_pharmacy['patternContractLineList'][11]['PatternContractLine']['contract']['@data']='';
        $tcm_pharmacy['patternContractLineList'][11]['PatternContractLine']['pattern']['@attributes']['reference']='36';
         $tcm_pharmacy['patternContractLineList'][11]['PatternContractLine']['pattern']['@attributes']['class']='ShiftAssignedOnlyforManPattern';
        $tcm_pharmacy['patternContractLineList'][11]['PatternContractLine']['pattern']['@data']='';


        $tcm_pharmacy['patternContractLineList'][12]['PatternContractLine']['@attributes']['id']='91';
        $tcm_pharmacy['patternContractLineList'][12]['PatternContractLine']['id']='11';
        $tcm_pharmacy['patternContractLineList'][12]['PatternContractLine']['contract']['@attributes']['reference']='56';
        $tcm_pharmacy['patternContractLineList'][12]['PatternContractLine']['contract']['@data']='';
        $tcm_pharmacy['patternContractLineList'][12]['PatternContractLine']['pattern']['@attributes']['reference']='37';
         $tcm_pharmacy['patternContractLineList'][12]['PatternContractLine']['pattern']['@attributes']['class']='FreeAfterWeekendWorkDayPattern';
        $tcm_pharmacy['patternContractLineList'][12]['PatternContractLine']['pattern']['@data']='';


        $tcm_pharmacy['patternContractLineList'][14]['PatternContractLine']['@attributes']['id']='92';
        $tcm_pharmacy['patternContractLineList'][14]['PatternContractLine']['id']='12';
        $tcm_pharmacy['patternContractLineList'][14]['PatternContractLine']['contract']['@attributes']['reference']='56';
        $tcm_pharmacy['patternContractLineList'][14]['PatternContractLine']['contract']['@data']='';
        $tcm_pharmacy['patternContractLineList'][14]['PatternContractLine']['pattern']['@attributes']['reference']='38';
         $tcm_pharmacy['patternContractLineList'][14]['PatternContractLine']['pattern']['@attributes']['class']='FreeAfterANightShiftPattern';
        $tcm_pharmacy['patternContractLineList'][14]['PatternContractLine']['pattern']['@data']='';



        $tcm_pharmacy['patternContractLineList'][15]['PatternContractLine']['@attributes']['id']='93';
        $tcm_pharmacy['patternContractLineList'][15]['PatternContractLine']['id']='13';
        $tcm_pharmacy['patternContractLineList'][15]['PatternContractLine']['contract']['@attributes']['reference']='56';
        $tcm_pharmacy['patternContractLineList'][15]['PatternContractLine']['contract']['@data']='';
        $tcm_pharmacy['patternContractLineList'][15]['PatternContractLine']['pattern']['@attributes']['reference']='39';
         $tcm_pharmacy['patternContractLineList'][15]['PatternContractLine']['pattern']['@attributes']['class']='ShiftAssignedAveragedAtAllEmployeesPattern';
        $tcm_pharmacy['patternContractLineList'][15]['PatternContractLine']['pattern']['@data']='';


        $tcm_pharmacy['patternContractLineList'][16]['PatternContractLine']['@attributes']['id']='94';
        $tcm_pharmacy['patternContractLineList'][16]['PatternContractLine']['id']='14';
        $tcm_pharmacy['patternContractLineList'][16]['PatternContractLine']['contract']['@attributes']['reference']='56';
        $tcm_pharmacy['patternContractLineList'][16]['PatternContractLine']['contract']['@data']='';
        $tcm_pharmacy['patternContractLineList'][16]['PatternContractLine']['pattern']['@attributes']['reference']='40';
         $tcm_pharmacy['patternContractLineList'][16]['PatternContractLine']['pattern']['@attributes']['class']='FreeTwoDaysEveryWeekPattern';
        $tcm_pharmacy['patternContractLineList'][16]['PatternContractLine']['pattern']['@data']='';



        $tcm_pharmacy['patternContractLineList'][17]['PatternContractLine']['@attributes']['id']='95';
        $tcm_pharmacy['patternContractLineList'][17]['PatternContractLine']['id']='15';
        $tcm_pharmacy['patternContractLineList'][17]['PatternContractLine']['contract']['@attributes']['reference']='56';
        $tcm_pharmacy['patternContractLineList'][17]['PatternContractLine']['contract']['@data']='';
        $tcm_pharmacy['patternContractLineList'][17]['PatternContractLine']['pattern']['@attributes']['reference']='41';
         $tcm_pharmacy['patternContractLineList'][17]['PatternContractLine']['pattern']['@attributes']['class']='ShiftAssignedSomeWeeksPattern';
        $tcm_pharmacy['patternContractLineList'][17]['PatternContractLine']['pattern']['@data']='';



        $tcm_pharmacy['patternContractLineList'][18]['PatternContractLine']['@attributes']['id']='96';
        $tcm_pharmacy['patternContractLineList'][18]['PatternContractLine']['id']='16';
        $tcm_pharmacy['patternContractLineList'][18]['PatternContractLine']['contract']['@attributes']['reference']='56';
        $tcm_pharmacy['patternContractLineList'][18]['PatternContractLine']['contract']['@data']='';
        $tcm_pharmacy['patternContractLineList'][18]['PatternContractLine']['pattern']['@attributes']['reference']='42';
         $tcm_pharmacy['patternContractLineList'][18]['PatternContractLine']['pattern']['@attributes']['class']='ShiftType2DaysPattern';
        $tcm_pharmacy['patternContractLineList'][18]['PatternContractLine']['pattern']['@data']='';


        $tcm_pharmacy['patternContractLineList'][19]['PatternContractLine']['@attributes']['id']='97';
        $tcm_pharmacy['patternContractLineList'][19]['PatternContractLine']['id']='17';
        $tcm_pharmacy['patternContractLineList'][19]['PatternContractLine']['contract']['@attributes']['reference']='56';
        $tcm_pharmacy['patternContractLineList'][19]['PatternContractLine']['contract']['@data']='';
        $tcm_pharmacy['patternContractLineList'][19]['PatternContractLine']['pattern']['@attributes']['reference']='43';
         $tcm_pharmacy['patternContractLineList'][19]['PatternContractLine']['pattern']['@attributes']['class']='ShiftType2DaysPattern';
        $tcm_pharmacy['patternContractLineList'][19]['PatternContractLine']['pattern']['@data']='';


        $tcm_pharmacy['patternContractLineList'][20]['PatternContractLine']['@attributes']['id']='98';
        $tcm_pharmacy['patternContractLineList'][20]['PatternContractLine']['id']='18';
        $tcm_pharmacy['patternContractLineList'][20]['PatternContractLine']['contract']['@attributes']['reference']='67';
        $tcm_pharmacy['patternContractLineList'][20]['PatternContractLine']['contract']['@data']='';
        $tcm_pharmacy['patternContractLineList'][20]['PatternContractLine']['pattern']['@attributes']['reference']='35';
         $tcm_pharmacy['patternContractLineList'][20]['PatternContractLine']['pattern']['@attributes']['class']='IdenticalShiftLastSomeDaysPattern';
        $tcm_pharmacy['patternContractLineList'][20]['PatternContractLine']['pattern']['@data']='';



        $tcm_pharmacy['patternContractLineList'][21]['PatternContractLine']['@attributes']['id']='99';
        $tcm_pharmacy['patternContractLineList'][21]['PatternContractLine']['id']='19';
        $tcm_pharmacy['patternContractLineList'][21]['PatternContractLine']['contract']['@attributes']['reference']='67';
        $tcm_pharmacy['patternContractLineList'][21]['PatternContractLine']['contract']['@data']='';
        $tcm_pharmacy['patternContractLineList'][21]['PatternContractLine']['pattern']['@attributes']['reference']='36';
         $tcm_pharmacy['patternContractLineList'][21]['PatternContractLine']['pattern']['@attributes']['class']='ShiftAssignedOnlyforManPattern';
        $tcm_pharmacy['patternContractLineList'][21]['PatternContractLine']['pattern']['@data']='';


        $tcm_pharmacy['patternContractLineList'][22]['PatternContractLine']['@attributes']['id']='100';
        $tcm_pharmacy['patternContractLineList'][22]['PatternContractLine']['id']='20';
        $tcm_pharmacy['patternContractLineList'][22]['PatternContractLine']['contract']['@attributes']['reference']='67';
        $tcm_pharmacy['patternContractLineList'][22]['PatternContractLine']['contract']['@data']='';
        $tcm_pharmacy['patternContractLineList'][22]['PatternContractLine']['pattern']['@attributes']['reference']='37';
         $tcm_pharmacy['patternContractLineList'][22]['PatternContractLine']['pattern']['@attributes']['class']='FreeAfterWeekendWorkDayPattern';
        $tcm_pharmacy['patternContractLineList'][22]['PatternContractLine']['pattern']['@data']='';


        $tcm_pharmacy['patternContractLineList'][23]['PatternContractLine']['@attributes']['id']='101';
        $tcm_pharmacy['patternContractLineList'][23]['PatternContractLine']['id']='21';
        $tcm_pharmacy['patternContractLineList'][23]['PatternContractLine']['contract']['@attributes']['reference']='67';
        $tcm_pharmacy['patternContractLineList'][23]['PatternContractLine']['contract']['@data']='';
        $tcm_pharmacy['patternContractLineList'][23]['PatternContractLine']['pattern']['@attributes']['reference']='38';
         $tcm_pharmacy['patternContractLineList'][23]['PatternContractLine']['pattern']['@attributes']['class']='FreeAfterANightShiftPattern';
        $tcm_pharmacy['patternContractLineList'][23]['PatternContractLine']['pattern']['@data']='';



        $tcm_pharmacy['patternContractLineList'][24]['PatternContractLine']['@attributes']['id']='102';
        $tcm_pharmacy['patternContractLineList'][24]['PatternContractLine']['id']='22';
        $tcm_pharmacy['patternContractLineList'][24]['PatternContractLine']['contract']['@attributes']['reference']='67';
        $tcm_pharmacy['patternContractLineList'][24]['PatternContractLine']['contract']['@data']='';
        $tcm_pharmacy['patternContractLineList'][24]['PatternContractLine']['pattern']['@attributes']['reference']='39';
         $tcm_pharmacy['patternContractLineList'][24]['PatternContractLine']['pattern']['@attributes']['class']='ShiftAssignedAveragedAtAllEmployeesPattern';
        $tcm_pharmacy['patternContractLineList'][24]['PatternContractLine']['pattern']['@data']='';



        $tcm_pharmacy['patternContractLineList'][25]['PatternContractLine']['@attributes']['id']='103';
        $tcm_pharmacy['patternContractLineList'][25]['PatternContractLine']['id']='23';
        $tcm_pharmacy['patternContractLineList'][25]['PatternContractLine']['contract']['@attributes']['reference']='67';
        $tcm_pharmacy['patternContractLineList'][25]['PatternContractLine']['contract']['@data']='';
        $tcm_pharmacy['patternContractLineList'][25]['PatternContractLine']['pattern']['@attributes']['reference']='40';
         $tcm_pharmacy['patternContractLineList'][25]['PatternContractLine']['pattern']['@attributes']['class']='FreeTwoDaysEveryWeekPattern';
        $tcm_pharmacy['patternContractLineList'][25]['PatternContractLine']['pattern']['@data']='';

        $tcm_pharmacy['patternContractLineList'][26]['PatternContractLine']['@attributes']['id']='104';
        $tcm_pharmacy['patternContractLineList'][26]['PatternContractLine']['id']='24';
        $tcm_pharmacy['patternContractLineList'][26]['PatternContractLine']['contract']['@attributes']['reference']='67';
        $tcm_pharmacy['patternContractLineList'][26]['PatternContractLine']['contract']['@data']='';
        $tcm_pharmacy['patternContractLineList'][26]['PatternContractLine']['pattern']['@attributes']['reference']='41';
         $tcm_pharmacy['patternContractLineList'][26]['PatternContractLine']['pattern']['@attributes']['class']='ShiftAssignedSomeWeeksPattern';
        $tcm_pharmacy['patternContractLineList'][26]['PatternContractLine']['pattern']['@data']='';


        $tcm_pharmacy['patternContractLineList'][27]['PatternContractLine']['@attributes']['id']='105';
        $tcm_pharmacy['patternContractLineList'][27]['PatternContractLine']['id']='25';
        $tcm_pharmacy['patternContractLineList'][27]['PatternContractLine']['contract']['@attributes']['reference']='67';
        $tcm_pharmacy['patternContractLineList'][27]['PatternContractLine']['contract']['@data']='';
        $tcm_pharmacy['patternContractLineList'][27]['PatternContractLine']['pattern']['@attributes']['reference']='42';
         $tcm_pharmacy['patternContractLineList'][27]['PatternContractLine']['pattern']['@attributes']['class']='ShiftType2DaysPattern';
        $tcm_pharmacy['patternContractLineList'][27]['PatternContractLine']['pattern']['@data']='';



        $tcm_pharmacy['patternContractLineList'][28]['PatternContractLine']['@attributes']['id']='106';
        $tcm_pharmacy['patternContractLineList'][28]['PatternContractLine']['id']='26';
        $tcm_pharmacy['patternContractLineList'][28]['PatternContractLine']['contract']['@attributes']['reference']='67';
        $tcm_pharmacy['patternContractLineList'][28]['PatternContractLine']['contract']['@data']='';
        $tcm_pharmacy['patternContractLineList'][28]['PatternContractLine']['pattern']['@attributes']['reference']='43';
         $tcm_pharmacy['patternContractLineList'][28]['PatternContractLine']['pattern']['@attributes']['class']='ShiftType2DaysPattern';
        $tcm_pharmacy['patternContractLineList'][28]['PatternContractLine']['pattern']['@data']='';




        //雇员信息

        $tcm_pharmacy['employeeList']['@attributes']['id']=$scheduleID;

        foreach ($employeeList as $key => $employee) {
        
            $age=$this->birthday($employee['emp_birthday']);
            $workyear=$this->birthday($employee['joined_date']);
            $tcm_pharmacy['employeeList'][$key]['Employee']['@attributes']['id']=$employee['empNumber'];
            $tcm_pharmacy['employeeList'][$key]['Employee']['id']=$key;
            $tcm_pharmacy['employeeList'][$key]['Employee']['code']=$employee['empNumber'];
            $tcm_pharmacy['employeeList'][$key]['Employee']['name']=$employee['firstName'];
            $tcm_pharmacy['employeeList'][$key]['Employee']['age']=$age;
            $tcm_pharmacy['employeeList'][$key]['Employee']['title']='1';
            $tcm_pharmacy['employeeList'][$key]['Employee']['gender']=$employee['emp_gender'];
            $tcm_pharmacy['employeeList'][$key]['Employee']['workyear']=$workyear;
            $tcm_pharmacy['employeeList'][$key]['Employee']['freedays']='1';
            $tcm_pharmacy['employeeList'][$key]['Employee']['education']=$employee['emp_gender'][0]['institute'];
            $tcm_pharmacy['employeeList'][$key]['Employee']['mutexname']='1';
            $tcm_pharmacy['employeeList'][$key]['Employee']['currdepartment']=$employee['job_title_code'];
            $tcm_pharmacy['employeeList'][$key]['Employee']['allodepartment']='1';
            $tcm_pharmacy['employeeList'][$key]['Employee']['contract']['@attributes']['reference']='1';
            $tcm_pharmacy['employeeList'][$key]['Employee']['contract']['@data']='';
            $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap']['@attributes']['id']='1';
            $tcm_pharmacy['employeeList'][$key]['Employee']['dayOffRequestMap']['@data']='';
            $tcm_pharmacy['employeeList'][$key]['Employee']['dayOnRequestMap']['@attributes']['id']='1';
            $tcm_pharmacy['employeeList'][$key]['Employee']['dayOnRequestMap']['@data']='';
            $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap']['@attributes']['id']='1';
            $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOffRequestMap']['@data']='';
            $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOnRequestMap']['@attributes']['id']='1';
            $tcm_pharmacy['employeeList'][$key]['Employee']['shiftOnRequestMap']['@data']='';
            
        }
       

        //技能列表
         $tcm_pharmacy['skillProficiencyList']['@attributes']['id']=$scheduleID;
        foreach ($employeeSkillList as $key => $employeeSkill) {
            
            $tcm_pharmacy['skillProficiencyList'][$key]['SkillProficiency']['@attributes']['id']=$employeeSkill['id'];
            $tcm_pharmacy['skillProficiencyList'][$key]['SkillProficiency']['id']=$key;
            $tcm_pharmacy['skillProficiencyList'][$key]['SkillProficiency']['employee']['@attributes']['reference']=$employeeSkill['emp_number'];
            $tcm_pharmacy['skillProficiencyList'][$key]['SkillProficiency']['employee']['@data']='';
            $tcm_pharmacy['skillProficiencyList'][$key]['SkillProficiency']['skill']['@attributes']['reference']=$employeeSkill['skillId'];
            $tcm_pharmacy['skillProficiencyList'][$key]['SkillProficiency']['skill']['@data']='';

        }
       

        $tcm_pharmacy['dayOffRequestList']['@attributes']['class']='empty-list';
        $tcm_pharmacy['dayOffRequestList']['@data']='';
        $tcm_pharmacy['dayOnRequestList']['@attributes']['class']='empty-list';
        $tcm_pharmacy['dayOnRequestList']['@data']='';
        $tcm_pharmacy['shiftOffRequestList']['@attributes']['class']='empty-list';
        $tcm_pharmacy['shiftOffRequestList']['@data']='';
        $tcm_pharmacy['shiftOnRequestList']['@attributes']['class']='empty-list';
        $tcm_pharmacy['shiftOnRequestList']['@data']='';


        
        // 例如10月1日的早班需要三个人，则会生成三个 ShiftAssignment-->
        //循环计划中所有班，然后依次取出每个班所需要人数；根据人数来复制
        $shiftAssignments=$this->getShiftAssignmentList($scheduleID);


        foreach($shiftAssignments as $key=>$shiftAssignment){
                $tcm_pharmacy['shiftAssignmentList'][$key]['ShiftAssignment']['@attributes']['id']=$shiftAssignment['id'];
                $tcm_pharmacy['shiftAssignmentList'][$key]['ShiftAssignment']['id']=$key;
                $tcm_pharmacy['shiftAssignmentList'][$key]['ShiftAssignment']['shift']['@attributes']['reference']=$shiftAssignment['shift_id'];
                $tcm_pharmacy['shiftAssignmentList'][$key]['ShiftAssignment']['shift']['@data']='';
                $tcm_pharmacy['shiftAssignmentList'][$key]['ShiftAssignment']['indexInShift']=$key;

        }


        //将数组存储为静态文件
        $TCM=$this->saveFile('TCMschedule',$tcm_pharmacy);

         //将数据转化为xml格式；
        $toXmData=$this->array_to_xml($TCM);

        //将XML存储为静态文件
        $this->cacheData('TCM_xml_schedule',$toXmData);
       
        // header("Content-type: text/xml");

        print_r($toXmData);exit;
    }


}
