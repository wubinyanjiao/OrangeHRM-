
<?php

class updateShiftResultAction extends baseShiftAction {

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

       
       
        $loggedInEmpNum = $this->getUser()->getEmployeeNumber();
        $adminMode = $this->getUser()->hasCredential(Auth::ADMIN_ROLE);

        $essMode = !$adminMode && !empty($loggedInEmpNum) && ($empNumber == $loggedInEmpNum);

        $param = array('empNumber' => $empNumber, 'ESS' => $essMode, 'emergencyContactPermissions' => $this->emergencyContactPermissions);
       

        $this->form = new AddShiftTypeForm(array(), $param, true);
        
        if ($this->getRequest()->isMethod('post')) {

                    

                    $shiftResult=$this->getShiftService()->getShiftResutById($contacts['shiftResultID']);

                    $shiftResult->setEmpNumber($contacts['employee']);
             
                	$this->getShiftService()->saveShiftResult($shiftResult); 
        }
        
        $empNumber = $request->getParameter('empNumber');

        
         $this->redirect("shift/createXML?schedule_id=$schedule_id");
    }

}
