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
 * View employee list action
 */
class addRotaryConstractAction extends baseShiftAction {

    protected $leftMenuService;
    
    /**
     * Index action. Displays employee list
     *      `
     * @param sfWebRequest $request
     */
    public function execute($request) {
      
        if ($this->getUser()->hasFlash('templateMessage')) {
            list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
        }
        
        // $empNumber = $request->getParameter('empNumber');
        $schedule_id = $request->getParameter('schedule_id');
        $this->schedule_id=$schedule_id;
        $param=array('scheduleID'=>$schedule_id);


        $this->form = new RotarySearchForm(array(), $param, true);

        // $this->setForm(new ContranctaSearchForm(array(), $param, true));

        if ($request->isMethod('post')) {


echo'<pre>';var_dump($_POST);exit;
            $this->form->bind($request->getParameter($this->form->getName()));

           
            //中级职称平均分配
            $averge_mid_level=array();
            $averge_mid_level['count']=$_POST['rotartContract']['averge_mid_level_count'];
            $averge_mid_level['weight']=$_POST['rotartContract']['averge_mid_level_weight'];
            $averge_mid_level['status']=$_POST['rotartContract']['averge_mid_level_status'];
            $rotary_List['averge_mid_level']=$averge_mid_level;

            //男士平均分配
            $averge_man=array();
            $averge_man['count']=$_POST['rotartContract']['averge_man_count'];
            $averge_man['weight']=$_POST['rotartContract']['averge_man_weight'];
            $averge_man['status']=$_POST['rotartContract']['averge_man_status'];
            $rotary_List['averge_man']=$averge_man;

            //研究生平均分配
            $averge_graduate=array();
            $averge_graduate['count']=$_POST['rotartContract']['averge_graduate_count'];
            $averge_graduate['weight']=$_POST['rotartContract']['averge_graduate_weight'];
            $averge_graduate['status']=$_POST['rotartContract']['averge_graduate_status'];
            $rotary_List['averge_graduate']=$averge_graduate;


            //中级职称满多长时间轮转
            $midlevel_year=array();
            $midlevel_year['count']=$_POST['rotartContract']['midlevel_year_count'];
            $midlevel_year['weight']=$_POST['rotartContract']['midlevel_year_weight'];
            $midlevel_year['status']=$_POST['rotartContract']['midlevel_year_status'];
            $rotary_List['midlevel_year']=$midlevel_year;

            //轮转部门顺序
            $document_rotary=array();
            $document_rotary['firt_document']=$_POST['rotartContract']['firt_rotary_document'];
            $document_rotary['sec_document']=$_POST['rotartContract']['sec_rotary_document'];
            $document_rotary['third_document']=$_POST['rotartContract']['third_rotary_document'];
            $document_rotary['weight']=$_POST['rotartContract']['document_rotary_Weight'];
            $document_rotary['status']=$_POST['rotartContract']['document_rotary_status'];
            $document_rotary['document_rotary']=$document_rotary;


             //门诊满几年轮转
            $rotary_limit=array();
            $rotary_limit['count']=$_POST['rotartContract']['rotary_limit_year'];
            $rotary_limit['weight']=$_POST['rotartContract']['rotary_limit_time_weight'];
            $rotary_limit['status']=$_POST['rotartContract']['rotary_limit_time_status'];
            $rotary_List['rotary_limit']=$rotary_limit;


             //年龄满足至少多少不轮转到门诊
            $min_age_rotary=array();
            $min_age_rotary['count']=$_POST['rotartContract']['min_age_rotary'];
            $min_age_rotary['weight']=$_POST['rotartContract']['min_age_rotary_weight'];
            $min_age_rotary['status']=$_POST['rotartContract']['min_age_rotary_status'];
            $rotary_List['min_age_rotary']=$min_age_rotary;


            //组长不参与轮转
            $leader_no_rotary=array();
            $leader_no_rotary['count']=$_POST['rotartContract']['leader_no_rotary_year'];
            $leader_no_rotary['weight']=$_POST['rotartContract']['leader_no_rotary_weight'];
            $leader_no_rotary['status']=$_POST['rotartContract']['leader_no_rotary_status'];
            $rotary_List['leader_no_rotary']=$leader_no_rotary;
          
            $data=$this->getShiftService()->saveFile('rotaryContranct',$patternList);

        }
    }
    
    /**
     *
     * @param array $filters
     * @return unknown_type
     */
    protected function setFilters(array $filters) {
        return $this->getUser()->setAttribute('emplist.filters', $filters, 'pim_module');
    }

    /**
     *
     * @return unknown_type
     */
    protected function getFilters() {
        return $this->getUser()->getAttribute('emplist.filters', null, 'pim_module');
    }
 
}
