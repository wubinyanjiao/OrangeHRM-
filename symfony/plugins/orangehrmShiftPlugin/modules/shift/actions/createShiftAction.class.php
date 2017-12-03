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
 */
class createShiftAction extends baseShiftAction {


     /**
     * @param sfForm $form
     * @return
     */
    public function setForm(sfForm $form) {
        if (is_null($this->form)) {
            $this->form = $form;
        }
    }

    // 首先判断schedule_id是否存在
    public function execute($request) {


        $loggedInEmpNum = $this->getUser()->getEmployeeNumber();
        $this->showBackButton = true;

        $dependentParams = $request->getParameter('dependent');


        $schedule_id = (isset($dependentParams['schedule_id']))?$dependentParams['schedule_id']:$request->getParameter('schedule_id');
        if($schedule_id){
            
            $this->empNumber = $empNumber;
            $this->schedule_id=$schedule_id;
       
            $adminMode = $this->getUser()->hasCredential(Auth::ADMIN_ROLE);

            if ($loggedInEmpNum == $empNumber) {
                $this->showBackButton = false;
            }

            $essMode = !$adminMode && !empty($loggedInEmpNum) && ($empNumber == $loggedInEmpNum);
            $param = array('empNumber' => $empNumber, 'ESS' => $essMode, 'dependentPermissions' => $this->dependentPermissions,'scheduleID' => $schedule_id);

            $this->setForm(new ShiftDependentForm(array(), $param, true));
            $this->deleteForm = new EmployeeEmergencyContactsDeleteForm(array(), $param, true);

            //从数据库中获取排班日期
            $this->shiftDates = $this->getShiftDateService()->getShiftDates($schedule_id);
            //数据库中获取排班类型
            $this->shiftTypes = $this->getShiftTypeService()->getShiftTypes($schedule_id);

            $shift_type_time=$this->_getShiftTypeTime();
            ////根据排班计划从数据库中获取排班列表
            $this->shift_list=$this->getShiftService()->getShifts($schedule_id);

            $this->shiftTypes=$this->_getShiftTypeList();
            $this->shiftDates=$this->_getShiftDateList();
          
            $shifttype=$_POST['shiftTypeID'];

            if(isset($shifttype)&&!empty($shifttype)){

                $data = $shift_type_time[$shifttype];
               
                $json_obj = json_encode($data);

                echo $json_obj;die;
            }
        }else{
            $this->credentialMessage = 'Credential Required';
        }
        
    }

    private function _getShiftTypeList() {

        $list = array();

        //获取排班类型列表
        foreach($this->shiftTypes as $shiftType) {
            $list[$shiftType->getId()] = $shiftType->getName();
        }
        
        return $list;
    }
    private function _getShiftTypeTime() {

        $type_time = array();

        //获取排班类型列表
        foreach($this->shiftTypes as $shiftType) {
          
            $type_time['start_time'] = substr($shiftType->start_time,0,5);
            $type_time['end_time'] = substr($shiftType->end_time,0,5);

            $time_list[$shiftType->getId()] = $type_time;
            
        }
        return $time_list;
    }

    private function _getShiftDateList() {

        $list = array();
        foreach($this->shiftDates as $shiftDate) {
            
            $list[$shiftDate->getId()] = $shiftDate->getShiftDate();
        }
        return $list;
    }

}

