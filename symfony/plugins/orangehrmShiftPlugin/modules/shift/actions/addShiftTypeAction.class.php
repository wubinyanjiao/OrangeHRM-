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

/**
 * Actions class for PIM module emergency contacts
 */
class addShiftTypeAction extends baseShiftAction {

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
        
        $loggedInEmpNum = $this->getUser()->getEmployeeNumber();
        $this->showBackButton = true;  
        $contacts = $request->getParameter('emgcontacts');
      
        $schedule_id = (isset($contacts['schedule_id']))?$contacts['schedule_id']:$request->getParameter('schedule_id');
       
        $this->schedule_id=$schedule_id;
  
        $adminMode = $this->getUser()->hasCredential(Auth::ADMIN_ROLE);

        $param = array( 'scheduleID'=>$schedule_id);

        $this->setForm(new AddShiftTypeForm(array(), $param, true));
        $this->deleteForm = new EmployeeEmergencyContactsDeleteForm(array(), $param, true);
        $this->shiftTypes = $this->getShiftTypeService()->getShiftTypes($schedule_id);
    }

}
