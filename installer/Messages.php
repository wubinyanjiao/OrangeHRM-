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
 *Related class to web installer.
 *This class contain all validation messages. 
 *Constant contain basic messages only.
 *More details added on relevant places which this constant call. Eg:- 'PHP_OK_MESSAGE' and 'PHP_FAIL_MESSAGE' will called with available php version on calling place.
 *
 *Here available only one method (public method).
 *It is display relevant messages with correct format.- displayMessage($message)
 *
 */
class Messages{

public function displayMessage($message){
   echo $message."\n";
}

const DB_CONFIG_SUCCESS = "Basic configuration successful";
const SUPER_USER_NEED = "Need super user credential. (sudo).";
const SEPERATOR = "----------------------------------";

const PHP_OK_MESSAGE = "PHP Version - Ok";
const PHP_FAIL_MESSAGE = "PHP Version - PHP 5.3.0 or higher is required";

const MYSQL_CLIENT_OK_MESSAGE = "MySQL Client - Ok";
const MYSQL_CLIENT_RECOMMEND_MESSAGE = "MySQL Client - ver 4.1.x or later recommended";
const MYSQL_CLIENT_FAIL_MESSAGE = "MySQL Client - MySQL support not available in PHP settings";

const MYSQL_SERVER_OK_MESSAGE = "MySQL Server - Ok";
const MYSQL_SERVER_RECOMMEND_MESSAGE = "MySQL Server - ver 5.1.6 or later recommended";
const MYSQL_SERVER_FAIL_MESSAGE = "MySQL Server - Not Available";

const WritableLibConfs_OK_MESSAGE = "Write Permissions for lib/confs/ - Writeable";
const WritableLibConfs_FAIL_MESSAGE = "Write Permissions for lib/confs/ - Not Writeable";

const WritableSymfonyConfig_OK_MESSAGE = "Write Permissions for - symfony/config - Ok";
const WritableSymfonyConfig_FAIL_MESSAGE = "Write Permissions for - symfony/config - not writeable";

const WritableSymfonyCache_OK_MESSAGE = "Write Permissions for symfony/cache - Writeable";
const WritableSymfonyCache_FAIL_MESSAGE = "Write Permissions for symfony/cache - Not Writeable";

const WritableSymfonyLog_OK_MESSAGE =  "Write Permissions for symfony/log - Writeable";
const WritableSymfonyLog_FAIL_MESSAGE =  "Write Permissions for symfony/log - Not Writeable";

const MaximumSessionIdle_OK_MESSAGE =  "Maximum Session Idle Time before Timeout - Good";
const MaximumSessionIdle_SHORT_MESSAGE =  "Maximum Session Idle Time before Timeout - Short";
const MaximumSessionIdle_TOO_SHORT_MESSAGE = "Maximum Session Idle Time before Timeout - Too short";

const RegisterGlobalsOff_OK_MESSAGE =  "Ok";
const RegisterGlobalsOff_FAIL_MESSAGE =  "On. Should be off";

const GgExtensionEnable_OK_MESSAGE = "PHP gd extension - Enabled";
const GgExtensionEnable_FAIL_MESSAGE = "PHP gd extension - Not enabled";

const PHPExifEnable_OK_MESSAGE = "PHP exif extension - Enabled";
const PHPExifEnable_FAIL_MESSAGE = "PHP exif extension - Not enabled";

const PHPAPCEnable_OK_MESSAGE = "PHP APC - Enabled";
const PHPAPCEnable_FAIL_MESSAGE = "PHP APC - Not Available. This may affect system performance.";

const ApacheExpiresModule_OK_MESSAGE = "Apache expired modules - Enabled";
const ApacheExpiresModule_DISABLE_MESSAGE = "Apache expired modules - Disabled. This may affect system performance";
const ApacheExpiresModule_UNABLE_MESSAGE = "Apache expired modules - Unable to determine";

const ApacheHeadersModule_UNABLE_MESSAGE = "Apache mod_headers module - Unable to determine";
const ApacheHeadersModule_ENABLE_MESSAGE = "Apache mod_headers module - Enabled";
const ApacheHeadersModule_DISABLE_MESSAGE = "Apache mod_headers module - Disabled. This may affect system performance";

const EnableRewriteMod_DISABLE_MESSAGE = "Apache mod_rewrite module - Disabled";
const EnableRewriteMod_OK_MESSAGE = "Apache mod_rewrite module - Enabled";
const EnableRewriteMod_UNABLE_MESSAGE = "Apache mod_rewrite module - Unable to determine";

const MySQLEventStatus_FAIL_MESSAGE = "MySQL Event Scheduler status - Cannot connect to the database";
const MySQLEventStatus_DISABLE_MESSAGE = "MySQL Event Scheduler status - Disabled. This is required for automatic leave status changes of Leave module";
const MySQLEventStatus_OK_MESSAGE = "MySQL Event Scheduler status - Enabled";


const INTERUPT_MESSAGE = "Above error found. Please correct it to continue";
const DB_WRONG_INFO = "Please check Database name, port , Privileged user name and password";

const PHP_MIN_VERSION = '5.3.0';
const MYSQL_MIN_VERSION = '4.1.0';
}

?>

