<?php

class addShiftContractAction extends baseShiftAction {
    
    public function setShiftContractsForm(sfForm $form) {
        if (is_null($this->shiftContractsForm)) {
            $this->shiftContractsForm = $form;
        }
    }

     /**
     * @param sfForm $form
     * @return
     */
    public function setContranctListForm(sfForm $form) {
        if (is_null($this->contranctListForm)) {
            $this->contranctListForm = $form;
        }
    } 
    
    /**
     * @param sfForm $form分类员工
     * @return
     */
    public function setAllocateForm(sfForm $form) {
        if (is_null($this->allocateForm)) {
            $this->allocateForm = $form;
        }
    }

    //添加约束类型
    public function setContranctTypeForm(sfForm $form) {
        if (is_null($this->contranctTypeForm)) {
            $this->contranctTypeForm = $form;
        }
    }

   
    public function setMinEmpSkillLevelForm(sfForm $form) {
        if (is_null($this->minEmpSkillLevelForm)) {
            $this->minEmpSkillLevelForm = $form;
        }
    }

    public function setMinumumSupervisorForm(sfForm $form) {
        if (is_null($this->minumumSupervisorForm)) {
            $this->minumumSupervisorForm = $form;
        }
    }

    public function setMaximumSupervisorForm(sfForm $form) {
        if (is_null($this->maximumSupervisorForm)) {
            $this->maximumSupervisorForm = $form;
        }
    }
    
    public function execute($request) {

        $this->showBackButton = false;
        $empNumber = $request->getParameter('empNumber');
        $this->empNumber = $empNumber;


        $schedule_id = $request->getParameter('schedule_id');

        $shift_id= $request->getParameter('shift_id');


        $shift=$this->getShiftService()->getShiftById($shift_id);
        $shift_date=$this->getShiftDateService()->getShiftDateById($shift->shiftdate_id);
        $shift_type=$this->getShiftTypeService()->getShiftTypeById($shift->shift_type_id);
        $this->shift_date=$shitDates;
       
        $this->schedule_id= $schedule_id;
        $this->showBackButton = false;
        $this->_setMessage();

        $usrObj = $this->getUser()->getAttribute('user');

        if (!$usrObj->isAdmin()) {
            $this->redirect('pim/viewPersonalDetails');
        }

        //异步提交数据
        if(isset($_POST['shift_id'])&&!empty($_POST['shift_id'])){

            $shiftID=$_POST['shift_id'];
        }
        
        $param = array('shift' => $shift, 'shift_date' => $shift_date, 'shift_type' => $shift_type,'scheduleID' => $schedule_id);

        $param_new=array('shift_id'=>$shift_id,'scheduleID'=>$schedule_id,'shiftIdNew'=>$shiftID);
        

        $this->setShiftContractsForm(new shiftContractsForm(array(), $param, true));

        // 分配雇员
        $this->setAllocateForm(new ShiftAllocateForm(array(), $param_new, true));

        $this->setContranctListForm(new ShiftContractListForm(array(), $param_new, true));

        $this->setContranctTypeForm(new ContranctTypeForm(array(), $param_new, true));

        $this->setMinEmpSkillLevelForm(new ShiftContractListForm(array(), $$param_new, true));

        $this->setMinumumSupervisorForm(new ShiftContractListForm(array(), $$param_new, true));

        $this->setMaximumSupervisorForm(new ShiftContractListForm(array(), $$param_new, true));

        $this->listForm = new DefaultListForm();
    }
    
    protected function _setMessage() {
        $this->section = '';
        if ($this->getUser()->hasFlash('qualificationSection')) {
            $this->section = $this->getUser()->getFlash('qualificationSection');
        } 
    }
}
?>