<?php

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

