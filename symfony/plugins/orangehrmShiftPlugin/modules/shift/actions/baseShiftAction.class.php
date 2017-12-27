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
abstract class baseShiftAction extends sfAction {
    
    private $shiftTypeService;
    private $workShiftService;
    private $shifService;
    private $shiftDateService;
    
   
    public function getShiftService(){
       
        if(is_null($this->shifService)) {
            $this->shifService = new ShiftService();
            $this->shifService->setShiftDao(new ShiftDao());
        }
        return $this->shifService;
    }

     public function getShiftTypeService() {
        
        if (is_null($this->shiftTypeService)) {
            $this->shiftTypeService = new ShiftTypeService();
            $this->shiftTypeService->setShiftTypeDao(new ShiftTypeDao());
        }
        return $this->shiftTypeService;
    }

    public function getScheduleService() {
        if (is_null($this->workShiftService)) {
            $this->workShiftService = new ScheduleService();
            $this->workShiftService->setScheduleDao(new ScheduleDao());
        }
        return $this->workShiftService;
    }

    //获取排班类型实例
    public function getShiftDateService() {
        if (is_null($this->shiftDateService)) {
            $this->shiftDateService = new ShiftDateService();
            $this->shiftDateService->setShiftDateDao(new ShiftDateDao());
        }
        return $this->shiftDateService;
    }

    protected function setOperationName($actionName) {

        $sessionVariableManager = new DatabaseSessionManager();
        $sessionVariableManager->setSessionVariables(array(
            'orangehrm_action_name' => $actionName,
        ));
        $sessionVariableManager->registerVariables();
    }



}