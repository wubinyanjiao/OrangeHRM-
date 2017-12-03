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
class addScheduleAction extends baseShiftAction {

    private $workShiftService;
    public function getScheduleService() {
        if (is_null($this->workShiftService)) {
            $this->workShiftService = new ScheduleService();
            $this->workShiftService->setScheduleDao(new ScheduleDao());
        }
        return $this->workShiftService;
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
        $workScheduleList = $this->getScheduleService()->getScheduleList();
         

        $usrObj = $this->getUser()->getAttribute('user');
        if (!$usrObj->isAdmin()) {
            $this->redirect('pim/viewPersonalDetails');
        }

        $this->setForm(new AddScheduleForm());
        // $workScheduleList = $this->getScheduleService()->getScheduleList();
        $this->_setListComponent($workScheduleList);
        $params = array();
        $this->parmetersForListCompoment = $params;
        $hideForm = true;

        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                
                $schedule_id=$this->form->save();
 
                $this->getUser()->setFlash('success', __(TopLevelMessages::SAVE_SUCCESS));
                $this->redirect("shift/createShift?schedule_id=$schedule_id");
            } else {
                $hideForm = false;
            }
        }
        $this->hideForm = $hideForm;
        // $this->default = $this->getWorkScheduleService()->getWorkScheduleDefaultStartAndEndTime();
    }

    private function _setListComponent($workShiftList) {
        $configurationFactory = new WorkScheduleHeaderFactory();
        ohrmListComponent::setConfigurationFactory($configurationFactory);
        ohrmListComponent::setListData($workShiftList);
    }

}

