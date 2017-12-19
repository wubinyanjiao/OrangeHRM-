<?php

//轮转模块
class addShiftRotaryAction extends baseShiftAction {

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

        $this->setForm(new AddShiftRotary2Form(array(), $param, true));
      
        $this->deleteForm = new EmployeeEmergencyContactsDeleteForm(array(), $param, true);
        $this->shiftRosters = $this->getShiftService()->getShiftRosters();


        $this->jopdocument_list=$this->getJobDocument();
       // echo'<pre>'; var_dump($this->jopdocument_list);exit;

  
    }

   

    public function getJobDocument(){
        $locationList = array('' => '-- ' . __('Select') . ' --');

        $locationService = new LocationService();
        $locations = $locationService->getLocationList();        

        $accessibleLocations = UserRoleManagerFactory::getUserRoleManager()->getAccessibleEntityIds('Location');
 
        foreach ($locations as $location) {
            if (in_array($location->id, $accessibleLocations)) {
                $locationList[$location->id] = $location->name;
            }
        }
      
        return($locationList);
    }

}
