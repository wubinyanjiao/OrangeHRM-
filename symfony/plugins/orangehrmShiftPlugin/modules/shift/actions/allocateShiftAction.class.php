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
class allocateShiftAction extends baseShiftAction {

    private $workShiftService;

    public function getWorkShiftService() {
        if (is_null($this->workShiftService)) {
            $this->workShiftService = new WorkShiftService();
            $this->workShiftService->setWorkShiftDao(new WorkShiftDao());
        }
        return $this->workShiftService;
    }

    public function getShiftService(){
       
        if(is_null($this->shifService)) {
            $this->shifService = new ShiftService();
            $this->shifService->setShiftDao(new ShiftDao());
        }
        return $this->shifService;
    }

    /**
     * @param sfForm $form
     * @return
     */
    public function setForm(sfForm $form) {
        if (is_null($this->form)) {
            $this->form = $form;
        }
    }

    public function execute($request) {

        //获取scheduleID与shiftID;
        $schedule_id = $request->getParameter('schedule_id');
        $shift_id= $request->getParameter('shift_id');

        $shift_list = $this->getShiftService()->getShifts($schedule_id);
        $shitDates= $this->getShiftDateService()->getShiftDates($schedule_id);
        $shiftTypes= $this->getShiftTypeService()->getShiftTypes($schedule_id);
        // echo '<pre>';var_dump($shift_list);exit;

    
        $shift=$this->getShiftService()->getShiftById($shift_id);
        $shift_date=$this->getShiftDateService()->getShiftDateById($shift->shiftdate_id);
        $shift_type=$this->getShiftTypeService()->getShiftTypeById($shift->shift_type_id);
       

        $this->schedule_id= $schedule_id;

        $this->menuItems= $shiftlists;

        $usrObj = $this->getUser()->getAttribute('user');
        if (!$usrObj->isAdmin()) {
            $this->redirect('pim/viewPersonalDetails');
        }

        $param = array('shift' => $shift, 'shift_date' => $shift_date, 'shift_type' => $shift_type,'scheduleID' => $schedule_id);
        $this->setForm(new AllocateShiftForm(array(), $param, true));

        $workShiftList = $this->getWorkShiftService()->getWorkShiftList();
        $this->_setListComponent($workShiftList);
        $params = array();
        $this->parmetersForListCompoment = $params;
        $hideForm = true;

        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $replayparm=$this->form->save();
                $schedule_id=$replayparm['schedule_id'];
                $shift_id=$replayparm['shift_id'];
                $this->getUser()->setFlash('success', __(TopLevelMessages::SAVE_SUCCESS));
                
                $this->redirect("shift/allocateShift?schedule_id=$schedule_id&shift_id=$shift_id");
            } else {
                $hideForm = false;
            }
        }

        $this->hideForm = $hideForm;
        $this->default = $this->getWorkShiftService()->getWorkShiftDefaultStartAndEndTime();
    }

    private function _setListComponent($workShiftList) {

        $configurationFactory = new WorkShiftHeaderFactory();
        ohrmListComponent::setConfigurationFactory($configurationFactory);
        ohrmListComponent::setListData($workShiftList);
    }

}

