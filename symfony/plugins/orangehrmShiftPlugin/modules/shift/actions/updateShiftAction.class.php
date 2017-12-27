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
 * Actions class for PIM module updateEmergencyContact
 */
class updateShiftAction extends baseShiftAction {

    /**
     * Add / update employee emergencyContact
     *
     * @param int $empNumber Employee number
     *
     * @return boolean true if successfully assigned, false otherwise
     */
    public function execute($request) {

        $contacts = $request->getParameter('emgcontacts');

        $schedule_id= $request->getParameter('schedule_id');
        // var_dump($schedule_id);exit;
       
       
        $loggedInEmpNum = $this->getUser()->getEmployeeNumber();
        $adminMode = $this->getUser()->hasCredential(Auth::ADMIN_ROLE);

        $essMode = !$adminMode && !empty($loggedInEmpNum) && ($empNumber == $loggedInEmpNum);

        $param = array('empNumber' => $empNumber, 'ESS' => $essMode, 'emergencyContactPermissions' => $this->emergencyContactPermissions);
       

        $this->form = new AddShiftTypeForm(array(), $param, true);
        
        if ($this->getRequest()->isMethod('post')) {

            $this->form->bind($request->getParameter($this->form->getName()));

            if ($this->form->isValid()) {

               
                $shifType=$this->form->save();
             
                $schedule_id=$shifType['scheduleID'];
                $this->getUser()->setFlash('viewEmergencyContacts.success', __(TopLevelMessages::SAVE_SUCCESS));
            }
        }
        
        $empNumber = $request->getParameter('empNumber');

        $this->redirect("shift/createShift?schedule_id=$schedule_id");
    }

}
