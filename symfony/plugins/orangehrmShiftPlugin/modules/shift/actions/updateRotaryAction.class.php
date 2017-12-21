<?php


class updateRotaryAction extends baseShiftAction {

    /**
     * Add / update employee emergencyContact
     *
     * @param int $empNumber Employee number
     *
     * @return boolean true if successfully assigned, false otherwise
     */
    public function execute($request) {

        $rotary = $request->getParameter('rotary');
 

        $param = array();

        $this->form = new AddShiftRotary2Form(array(), $param, true);

        if ($this->getRequest()->isMethod('post')) {

            $this->form->bind($request->getParameter($this->form->getName()));



            if ($this->form->isValid()) {

               
                $this->form->save();
               
                $this->getUser()->setFlash('viewEmergencyContacts.success', __(TopLevelMessages::SAVE_SUCCESS));
            }
        }
        
        $empNumber = $request->getParameter('empNumber');

        $this->redirect("shift/AddShiftRotary");
    }

}
