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
class saveMaxStaffAction extends baseShiftAction {
    
    /**
     * @param sfForm $form
     * @return
     */
    public function setShiftContranctForm(sfForm $form) {
        if (is_null($this->shiftContranctForm)) {
            $this->shiftContranctForm = $form;
        }
    }
    
    public function execute($request) {

        $form = new DefaultListForm();
        $form->bind($request->getParameter($form->getName()));
        $getmaxStaff = $request->getParameter('maxstaff');
 
    
        $shift_id= $getmaxStaff['shiftId'];
        $schedule_id=$getmaxStaff['scheduleID'];


        $param = array('shift_id'=>$shift_id,'scheduleID'=>$schedule_id);

        $this->setShiftContranctForm(new ShiftContractListForm(array(), $param, true));
    
        if ($request->isMethod('post')) {

            if ( $request->getParameter('option') == "save") {

                $this->shiftContranctForm->bind($request->getParameter($this->shiftContranctForm->getName()));


                if ($this->shiftContranctForm->isValid()) {

                    /*
                        保存约束，按照排班类型来复制约束；
                        例如，如果是将约束条件复制到计划中所有天的早班；
                        则，根据shiftype，查找计划中所有排班类型为早班的shift；
                        然后依次取出保存；

                    */
                    //约束仅对当前排班有效
                    if($getmaxStaff['applyTo']==0){
                        $shiftContranct = $this->getShiftContranct($this->shiftContranctForm);
                        if ($shiftContranct != NULL) {
                            
                            $this->getShiftService()->saveShiftContranct($shiftContranct);
                     
                        } 
                    }

                    //复制该约束
                    if($getmaxStaff['applyTo']!==0){

                        $shiftType=$getmaxStaff['applyTo'];
                        
                        //根据排班类型取出计划中所有的排班对象
                        $shifts_by_type=$this->getShiftService()->getShiftByType($shiftType);

                        foreach ($shifts_by_type as $key => $shift) {
                            $shiftContranct = $this->getShiftContranct($this->shiftContranctForm,$shift['id']);
                            if ($shiftContranct != NULL) {
                                $this->getShiftService()->saveShiftContranct($shiftContranct);
                               
                            } 
                        }

                    }



                     $this->getUser()->setFlash('language.success', __(TopLevelMessages::SAVE_SUCCESS));


                    
                } else {
                    $this->getUser()->setFlash('language.warning', __('Form Validation Failed'));
                }
            }

        }
        $this->getUser()->setFlash('qualificationSection', 'language');
        $this->redirect('shift/addShiftContract?schedule_id=' . $schedule_id . '&shift_id='. $shift_id);
    }

    private function getShiftContranct(sfForm $form,$shiftId=null) {

        

        $post = $form->getValues();

        if($shiftId==null){
            $shiftId=$post['shiftId'];
        }


        //查看数据库中是否有当前排班的关于最大员工的约束
        $shift_contract = $this->getShiftService()->getShiftContranct($shiftId, $post['status']);

        $isAllowed = FALSE;
        if (!$shift_contract instanceof WorkShiftContranct) {
            
            $shift_contract = new WorkShiftContranct();
            $isAllowed = TRUE;
         
        } else {
            
            $isAllowed = TRUE;
        }
       
        if ($isAllowed) {
            $shift_contract->contranct_type_id = $post['contranct_list'];
            $shift_contract->value = $post['maximum'];
            $shift_contract->apply_to = $post['applyTo'];
            $shift_contract->shift_id = $post['shiftId'];
            $shift_contract->status = $post['status'];
            return $shift_contract;
 
        } else {
            return NULL;
        }
    }
}