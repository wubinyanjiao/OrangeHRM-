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
class saveContranctTypeAction extends baseShiftAction {
    
    /**
     * @param sfForm $form
     * @return
     */
    public function setContranctTypeForm(sfForm $form) {
        if (is_null($this->contrantTypeForm)) {
            $this->contrantTypeForm = $form;
        }
    }
    
    public function execute($request) {

        $form = new DefaultListForm();
        $form->bind($request->getParameter($form->getName()));
        $getContranctType = $request->getParameter('contrancttype');
        
    
        $shift_id= $getContranctType['shiftId'];
        $schedule_id=$getContranctType['scheduleID'];


        $param = array('shift_id'=>$shift_id,'scheduleID'=>$schedule_id);

        $this->setContranctTypeForm(new ContranctTypeForm(array(), $param, true));
    
        if ($request->isMethod('post')) {

            if ( $request->getParameter('option') == "save") {

                $this->contrantTypeForm->bind($request->getParameter($this->contrantTypeForm->getName()));
                if ($this->contrantTypeForm->isValid()) {

                    $contranct_type = $this->getContranctTypes($this->contrantTypeForm);
                    if ($contranct_type != NULL) {
                        $this->getShiftService()->saveContranctType($contranct_type);
                    } 
                
                     $this->getUser()->setFlash('language.success', __(TopLevelMessages::SAVE_SUCCESS));
                    
                } else {
                    $this->getUser()->setFlash('language.warning', __('Form Validation Failed'));
                }
            }

        }

        $this->redirect('shift/addShiftContract?schedule_id=' . $schedule_id . '&shift_id='. $shift_id);
    }

    private function getContranctTypes(sfForm $form) {

        $post = $form->getValues(); 
        $post['create_at']=date('Y-m-d');
        
        $isAllowed = FALSE;
        if (!empty($post['id'])) {
            $contranct_type = $this->getShiftService()->getContranctType($post['id'],1);

            $isAllowed = TRUE;
        } 
        
        if (!$contranct_type instanceof WorkContranctType) {
            $contranct_type = new WorkContranctType();
            $isAllowed = TRUE;
            
        }

        
        if ($isAllowed) {
            $contranct_type->schedule_id = $post['scheduleID'];
            $contranct_type->name = $post['name'];
            $contranct_type->status = $post['status'];
            $contranct_type->create_at = $post['create_at'];
        }

        return $contranct_type;
    }
}