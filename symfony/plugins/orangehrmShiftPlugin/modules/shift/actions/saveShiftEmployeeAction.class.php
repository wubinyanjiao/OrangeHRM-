<?php

class saveShiftEmployeeAction extends baseShiftAction {

    /**
     * @param sfForm $form
     * @return
     */
     public function setAllocateForm(sfForm $form) {
        if (is_null($this->allocateForm)) {
            $this->allocateForm = $form;
        }
    }

    public function execute($request) {
        
        $form = new DefaultListForm();
        $form->bind($request->getParameter($form->getName()));
        $shiftContract = $request->getParameter('workShift');

        $shift=$this->getShiftService()->getShiftById($shiftContract['shiftId']);
        $param = array('shift' => $shift, 'scheduleID' => $shiftContract['scheduleID']);
 
        $this->setAllocateForm(new ShiftAllocateForm(array(), $param, true));

        if ($request->isMethod('post')) {

            if ($request->getParameter('option') == "save") {
                    $this->allocateForm->bind($request->getParameter($this->allocateForm->getName()));
                 
                    if ($this->allocateForm->isValid()) {
                        $this->allocateForm->save();
                        $this->getUser()->setFlash('success', __(TopLevelMessages::SAVE_SUCCESS));
                       
                    } else {
                        $this->getUser()->setFlash('workexperience.warning', __('Form Validation Failed.'));
                    }
            }
        }
        $this->redirect('shift/createShift?schedule_id=' . $shiftContract['scheduleID'] . '&shift_id='. $shiftContract['workShiftId']);
    }
}

?>