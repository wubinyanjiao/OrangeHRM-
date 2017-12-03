<?php

class saveShiftContractAction extends baseShiftAction {

    /**
     * @param sfForm $form
     * @return
     */
    public function setShiftContractsForm(sfForm $form) {
        if (is_null($this->shiftContractsForm)) {
            $this->shiftContractsForm = $form;
        }
    }

    public function execute($request) {
        

        $form = new DefaultListForm();
        $form->bind($request->getParameter($form->getName()));
        $shiftContract = $request->getParameter('workShift');
  

        $shift=$this->getShiftService()->getShiftById($shiftContract['shiftId']);
        $shift_date=$this->getShiftDateService()->getShiftDateById($shiftContract['shiftDays']);
        $shift_type=$this->getShiftTypeService()->getShiftTypeById($shiftContract['shiftType']);

        $param = array('shift' => $shift, 'shift_date' => $shift_date, 'shift_type' => $shift_type,'scheduleID' => $schedule_id);
 
        $this->setShiftContractsForm(new shiftContractsForm(array(), $param, true));

        //this is to save work experience
        if ($request->isMethod('post')) {

            if ($request->getParameter('option') == "save") {
                    $this->shiftContractsForm->bind($request->getParameter($this->shiftContractsForm->getName()));
                   
                    if ($this->shiftContractsForm->isValid()) {

                        //为对象赋值
                        $workShift = $this->getShiftContract($this->shiftContractsForm);
                       
                        //设置名称

                        $this->setOperationName(($workShift->getId() == '') ? 'ADD WORK EXPERIENCE' : 'CHANGE WORK EXPERIENCE');

                        //保存
                        $this->getShiftService()->saveShift($workShift);

                        $this->getUser()->setFlash('workexperience.success', __(TopLevelMessages::SAVE_SUCCESS));
                    } else {
                        $this->getUser()->setFlash('workexperience.warning', __('Form Validation Failed.'));
                    }
                
            }
        }
        $this->getUser()->setFlash('qualificationSection', 'workexperience');
        $this->redirect('shift/addShiftContract?schedule_id=' . $shiftContract['scheduleID'] . '&shift_id='. $shiftContract['shiftId']);
    }

     protected function getDuration($fromTime, $toTime) {
        list($startHour, $startMin) = explode(':', $fromTime);
        list($endHour, $endMin) = explode(':', $toTime);

        $durationMinutes = (intVal($endHour) - intVal($startHour)) * 60 + (intVal($endMin) - intVal($startMin));
        $hours = $durationMinutes / 60;

        return number_format($hours, 2);
    }

    private function getShiftContract(sfForm $form) {

        $post = $form->getValues();
       
        $hours_per_day=$this->getDuration($post['start_time'],$post['end_time']);

        $workShift=$this->getShiftService()->getShiftById($post['shiftId']);

       
        //判断一个类是否是另一个类的实例,如果不是，则初始化
        if (!$workShift instanceof WorkShiftNew) {
            
            $workShift = new WorkShiftNew();
        }

        $workShift->name = $post['name'];

         $workShift->hoursPerDay = $hours_per_day;
        
        $workShift->start_time = $post['start_time'];
        $workShift->end_time = $post['end_time'];
        $workShift->schedule_id = $post['scheduleID'];
        $workShift->shift_type_id = $post['shiftType'];
        $workShift->shiftdate_id = $post['shiftDays'];
        $workShift->required_employee = $post['required_employee'];
        $workShift->status = $post['status'];


        return $workShift;
    }

}

?>