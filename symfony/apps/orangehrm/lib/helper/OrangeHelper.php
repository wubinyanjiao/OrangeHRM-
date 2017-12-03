<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 *
 */

/**
 * Helper functions for orangehrm specific actions
 */

/**
 * Formats the employee number as expected by other components, by 
 * left padding with zeros if needed.
 * 
 * @param int $empNumber employee number
 * @return String $empNumber left padded with zeros
 */
function format_emp_number($empNumber) {
    
    $empIdLength = OrangeConfig::getInstance()->getSysConf()->getEmployeeIdLength();    
    $formattedNumber = str_pad($empNumber, $empIdLength, "0", STR_PAD_LEFT);
    return $formattedNumber;    
}

/**
 * 
 * @return String Html
 */
function message() {
	$html =	'';
	$user = sfContext::getInstance()->getUser();
	if($user->hasFlash('messageType') && $user->hasFlash('message') )
	{
		$class	=	'';
		switch( $user->getFlash('messageType') )
		{
			case 'SUCCESS':
				$class	=	'messageBalloon_success';
			break;
			
			case 'NOTICE':
				$class	=	'messageBalloon_notice';
			break;
			
			case 'WARNING':
				$class	=	'messageBalloon_warning';
			break;

			case 'FAILURE':
				$class	=	'messageBalloon_failure';
			break;

		}
		$html .=	"<div id='".$class."' class='".$class."'>";
		$html	.=	"<ul>";
            $messageList = $user->getFlash('message');
			foreach( $messageList as $message)
			{
				$html .= "<li>".$message."</li>";
			}
		$html	.=	"</ul>";
		$html	.=	"</div>";
		
	}
	
	return $html ;
}

/**
 * Uses the passed array for showing the message.
 * Doesn't use session data.
 */

function templateMessage($errors) {
	
	$html =	'';
	
	if (!empty($errors)) {
	
		$class = '';
		
		switch ($errors[0]) {
		    
			case 'SUCCESS': $class = 'messageBalloon_success';
							break;
			case 'NOTICE':	$class = 'messageBalloon_notice';
							break;
			case 'WARNING':	$class = 'messageBalloon_warning';
							break;
			case 'FAILURE':	$class = 'messageBalloon_failure';
							break;
		    
		} 

		$html .= "<div id='".$class."' class='".$class."'>";

		$count = count($errors);

                // Only show a list if more than one error
                if ($count == 2) {
                    $html .= htmlspecialchars_decode($errors[1]);
                } else if ($count > 2) {
                    $html .= "<ul>";

                    $count = count($errors);

                    for ($i=1; $i<$count; $i++) {
                        $html .= "<li>".htmlspecialchars_decode($errors[$i])."</li>";
                    }

                    $html .= "</ul>";
                }
		$html .= "</div>";
	    
	}
	
	return $html;

}

/**
 * String format the given number with SI unit prefix for units.
 *
 * Method has an accuracy of 2 d.p
 *
 * e.g. 1000 => 1K
 *
 * @param Integer number
 * @return String formatted
 */
function add_si_unit($number) {
    switch ($number) {
        case $number >= 1000000000000 : $prefix = " T";
            $divisor = 1000000000000;
            break;
        case $number >= 1000000000 : $prefix = " G";
            $divisor = 1000000000;
            break;
        case $number >= 1000000 : $prefix = " M";
            $divisor = 1000000;
            break;
        case $number >= 1000 : $prefix = " k";
            $divisor = 1000;
            break;
        default : $prefix = "";
            $divisor = 1;
            break;
    }
    $formatted = round(($number / $divisor), 2) . "{$prefix}";

    return $formatted;
}

function formatDate($currentDate, $formatData) {
    
    $cDate = explode($formatData['currentSeparater'], $currentDate);
    $cFormat = explode($formatData['currentSeparater'], $formatData['currentFormat']);
    
    $cDateAssoc[$cFormat[0]] = $cDate[0];
    $cDateAssoc[$cFormat[1]] = $cDate[1];
    $cDateAssoc[$cFormat[2]] = $cDate[2];
    
    $nFormat = explode($formatData['newSeparater'], $formatData['newFormat']);
    
    $nDate[0] = $cDateAssoc[$nFormat[0]];
    $nDate[1] = $cDateAssoc[$nFormat[1]];
    $nDate[2] = $cDateAssoc[$nFormat[2]];
    
    return implode($formatData['newSeparater'], $nDate);
    
}

/**
 * Escape string for use in javascript
 *
 * Escapes characters \, ", ' in the string by adding a \ in front.
 *
 * (based on http://code.google.com/p/doctype/wiki/ArticleXSSInJavaScript)
 *
 * @param String $string String to be escaped
 * @return String escaped string
 */
function escapeForJavascript($string) {
        $charArray = str_split($string);
        $escapedString = '';

        foreach($charArray as $char) {
                switch ($char) {
                        case "'":
                                $escapedString .= "\\x27";
                                break;
                        case "\"":
                                $escapedString .= "\\x22";
                                break;
                        case '\\':
                                $escapedString .= "\\\\";
                                break;
                        case "\n":
                                $escapedString .= "\\n";
                                break;
                        case "\r":
                                $escapedString .= "\\r";
                                break;
        case "\t":
            $escapedString .= "\\t";
            break;                    
                        case "\f":
                                $escapedString .= "\\f";
                                break;
        case "&":
            $escapedString .= "\\x26";
                                break;
        case "<":
            $escapedString .= "\\x3c";
                                break;
        case ">": 
            $escapedString .= "\\x3e";
                                break;
        case "=":
            $escapedString .= "\\x3d";
                                break;                    

                        default :
                                $escapedString .= $char;
                                break;
                }
        }
        return $escapedString;
}
        

/**
 * Escapes any special characters to html entities and make it safe for including into a web page.
 *
 * @param string $value Value to escape
 * @return string Escaped value
 */
function escapeHtml($value) {
    return htmlspecialchars($value, ENT_QUOTES);
}

function displayMainMessage($messageType, $message) {
    
    $message = "<div class=\"message $messageType fadable\">$message<a href=\"#\" class=\"messageCloseButton\">Close</a></div>";
    
    $message .= '<script type="text/javascript">
                //<![CDATA[
                    $("div.fadable").delay(2000)
                        .fadeOut("slow", function () {
                            $("div.fadable").remove();
                        }); 
                //<![CDATA[
                </script>';
    
    return $message;
    
}

function theme_path($path) {
    
    $sfUser = sfContext::getInstance()->getUser();
    
    if (!$sfUser->hasAttribute('meta.themeName')) {
        $themeName = OrangeConfig::getInstance()->getAppConfValue(ConfigService::KEY_THEME_NAME);
        if (empty($themeName)) {
            $themeName = 'default';
        }
        $sfUser->setAttribute('meta.themeName', $themeName);
    }

    $themeName = $sfUser->getAttribute('meta.themeName'); 
    
    $resourceDir = sfConfig::get('ohrm_resource_dir');
    $themePath = public_path($resourceDir . '/themes/' . $themeName . '/');
    $themePath = empty($path)? $themePath : $themePath . $path;
    
    return $themePath;
    
}

function plugin_web_path($plugin, $path) {
    
    $resourceDir = sfConfig::get('ohrm_resource_dir');
    $path = public_path($resourceDir . '/' . $plugin . '/' . $path);
    
    return $path;    
}

/**
 * 
 * @param  array  $file    上传的文件
 * @param  str $address 存放图片的文件夹名称 
 *                      head_pic  用户头像  
 *                          
 */
function uplaodeimg_files($file ,$address)
    {
        $path = sfConfig::get('sf_web_dir');   //图片存放路径
         // $path = sfConfig::getAll('sf_root_dir');   //图片存放路径
         // echo '<pre>';var_dump($path);die;

        $time = (string) time();
        $rand = rand(1,10000);

        $path_url = "public/".$address; // 接收文件目录
        
        if (!is_dir( $path_url )) {            
            mkdir ( "$path_url", 0777, true );              
        }

        if(is_array($file)){
            $jpg = explode('.', $file['photofile']['name']);
            $new_name = '/public/'.$address.'/'.$time.$rand.'.'.$jpg[1];
            $a= move_uploaded_file($file['photofile']['tmp_name'],$path.$new_name);
        }else if(is_object($file)){
            $jpg = explode('.', $file->getOriginalName());
            $new_name = '/public/'.$address.'/'.$time.$rand.'.'.$jpg[1];
            $a= move_uploaded_file($file->getTempName(),$path.$new_name);
        }

        if($a){
            //return '/symfony/web'.$new_name;
            return $new_name;
        }else{
            return false;
        }

        
    }
/*
*   //下载文件
*   $fileurl   文件地址
*   $file_name  文件名称
***/
function downfile_url($fileurl,$file_name=null)
{
     ob_start(); 
     $filename = substr_url($fileurl);
     // var_dump($filename);die;
     // var_dump($fileurl);die;
     // $filename=public_path($filename);
     // $filename = $fileurl;
     if(empty($file_name)){
        $file_name = date("Ymd-H:i:m");
     }
     header( "Content-type:  application/octet-stream "); 
     header( "Accept-Ranges:  bytes "); 
     header( "Content-Disposition:  attachment;  filename= {$file_name}"); 
     $size=readfile($filename); 
     header( "Accept-Length: " .$size);
}

/*
*   //下载文件
*   $fileurl   文件地址
*   $file_name  文件名称
***/
function substr_url($fileurl)
{
    if(empty($fileurl)||strlen($fileurl)<13){
        return false;
    }
     $filename=substr($fileurl,1);//截取'/somfony/web/public/fujian/15077908383600.txt' 
     return $filename;
}