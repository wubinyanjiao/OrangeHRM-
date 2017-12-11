<?php


class createCSVAction extends baseShiftAction {



    public function execute($request) {


        $scheduleID=$request->getParameter('schedule_id');
      
        $result=$this->getShiftService()->getRosterResult();

        $shiftTypes=$this->getShiftService()->getShiftTypeList($scheduleID);
        $shiftTypes = array_column($shiftTypes, NULL, 'id');

        $employeeList=$this->getShiftService()->getEmployeeList();
        $employeeList = array_column($employeeList, NULL, 'empNumber');
        


        $this->result=$result;
        $assignment_list=$result['Assignment'];



        $employ_list=array_column($assignment_list,'Employee');
        $employ_list=array_unique($employ_list);

        $date_list=array_column($assignment_list,'Date');
        $date_list=array_unique($date_list);

      

        //列出每一个员工的所有天的排班
       // var_dump($result['Assignment']);exit;

        foreach ($employ_list as $key => $employee) {

            foreach ($assignment_list as $k => $assignment) {

                if($assignment['Employee']==$employee){
                    $employee_array[$employee][]=$assignment;
                }
            }
        }


        $emarray=array();
        $stringType="";

        foreach ($employee_array as $key => $employee) {

            $employ_day=array_column($employee,'Date');
            $employ_day=array_unique($employ_day);
            

            $diff=array_diff($date_list, $employ_day);

            if(!empty($diff)){
                foreach ($diff as $difkey=> $difday) {
                     $stringType="休息";
                    $emarray[$key][$difday][]=$stringType;
                   
                }
            }

            foreach ($date_list as $ked => $date) {
                foreach ($employee as $ks => $emday) {
                    if($date==$emday['Date']){            
                        $emarray[$key][$date][]=$shiftTypes[$emday['ShiftType']]['name'];

                    }

                }
                
            }
            ksort($emarray[$key]);
        }


        foreach ($emarray as $key => $ema) {
          
            foreach ($ema as $k => $one) {
                

                $str=implode(',', $one);
                $format[$key][0]= $employeeList[$key]['firstName'];
                $format[$key][$k]=$str;
                $data[$key] = array_values($format[$key]);


            }
            
        }


        $this->emarray=$emarray;
        $this->date_list=$date_list;
        $this->employeeList=$employeeList;
      
  
         $excelHead = "中药房排班表"; 
         $title = "排班计划表";   #文件命名
         $headtitle= "<tr><th  colspan='3' >{$excelHead}</th></tr>"; 
     
        $titlename .= "</tr>";
        $titlename .= "<th style='width:70px;'>姓名</th>";
         foreach ($date_list as $key => $date) {
             $titlename .= "<th style='width:70px;'>{$date}</th>";
         }

         $titlename .= "</tr>";
         $filename = $title.".xls"; 
         // var_dump($titlename);exit;
         $this->excelData($data,$titlename,$headtitle,$filename); 
    }

    /**
      * @desc   将数据导出到Excel中
      * @param  $data array 设置表格数据 
      * @param  $titlename string 设置head 
      * @param  $title string 设置表头 
      * @param  $filename 设置默认文件名
      * @return 将字符串输出，即输出字节流，下载Excel文件
     */ 
      function excelData($data,$titlename,$title,$filename){ 
       #xmlns即是xml的命名空间
         $str = "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\"\r\nxmlns:x=\"urn:schemas-microsoft-com:office:excel\"\r\nxmlns=\"http://www.worg/TR/REC-html40\">\r\n<head>\r\n<meta http-equiv=Content-Type content=\"text/html; charset=utf-8\">\r\n</head>\r\n<body>"; 
        #以下构建一个html类型格式的表格
         $str .= $title; 
         $str .="<table border=1><head>".$titlename."</head>"; 
        foreach ($data  as $key=> $rt ) 
         { 
             $str .= "<tr>"; 
             foreach ( $rt as $k => $v ) 
             { 
                $str .= "<td>{$v}</td>"; 
             } 
             $str .= "</tr>\n"; 
         } 
         $str .= "</table></body></html>"; 
         header( "Content-Type: application/vnd.ms-excel; name='excel'" );   #类型
         header( "Content-type: application/octet-stream" );     #告诉浏览器响应的对象的类型（字节流、浏览器默认使用下载方式处理）
         header( "Content-Disposition: attachment; filename=".$filename );   #不打开此文件，刺激浏览器弹出下载窗口、下载文件默认命名
         header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" ); 
         header( "Pragma: no-cache" );   #保证不被缓存或者说保证获取的是最新的数据
         header( "Expires: 0" ); 
        exit( $str ); 
     }
}

