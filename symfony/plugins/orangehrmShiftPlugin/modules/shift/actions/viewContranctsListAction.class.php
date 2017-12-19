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
class viewContranctsListAction extends baseShiftAction {

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


        $this->form = new ContranctaSearchForm(array(), $param, true);

        // $this->setForm(new ContranctaSearchForm(array(), $param, true));

        if ($request->isMethod('post')) {



            $this->form->bind($request->getParameter($this->form->getName()));
       
           //组合夜班后夜休数组

            $nightAfterNight=array();
            /*$nightAfterNightLeisure=array();

             if(!isset($_POST['personal']['nightAfterNightLeisureShiftSelect'])){
                foreach ($_POST['nightAfterNightLeisureShiftSelect'] as $key => $value) {
                    $nightAfterNightLeisure[$key]['nightAfterNightLeisureShiftSelect']=$value;
                    $nightAfterNightLeisure[$key]['nightAfterNightLeisureWeight']=$_POST['nightAfterNightLeisureWeight'][$key];
                    $nightAfterNightLeisure[$key]['nightAfterNightLeisureStatus']=$_POST['nightAfterNightLeisureStatus'][$key];
                
                }

                $nightAfterNight=$nightAfterNightLeisure;


            }else{
                $nightAfterNight[0]['nightAfterNightLeisureShiftSelect']=$_POST['personal']['nightAfterNightLeisureShiftSelect'];
                $nightAfterNight[0]['nightAfterNightLeisureWeight']=$_POST['personal']['nightAfterNightLeisureWeight'];
                $nightAfterNight[0]['nightAfterNightLeisureStatus']=$_POST['personal']['nightAfterNightLeisureStatus'];

                
                if(isset($_POST['nightAfterNightLeisureShiftSelect'])&&!empty($_POST['nightAfterNightLeisureShiftSelect'])){

                    foreach ($_POST['nightAfterNightLeisureShiftSelect'] as $key => $value) {
                    $nightAfterNightLeisure[$key]['nightAfterNightLeisureShiftSelect']=$value;
                    $nightAfterNightLeisure[$key]['nightAfterNightLeisureWeight']=$_POST['nightAfterNightLeisureWeight'][$key];
                    $nightAfterNightLeisure[$key]['nightAfterNightLeisureStatus']=$_POST['nightAfterNightLeisureStatus'][$key];
                    }
                     //重新组合数组；
                    $nightAfterNight=array_merge($nightAfterNight,$nightAfterNightLeisure);

                }
            }*/

            $nightAfterNight['nightAfterNightLeisureShiftSelect']=$_POST['personal']['nightAfterNightLeisureShiftSelect'];
            $nightAfterNight['nightAfterNightLeisureWeight']=$_POST['personal']['nightAfterNightLeisureWeight'];
            $nightAfterNight['nightAfterNightLeisureStatus']=$_POST['personal']['nightAfterNightLeisureStatus'];


            $patternList['nightAfterNightLeisureShift']=$nightAfterNight;

            //组合班次尽量平均分配

            $average_assignment=array();
            $averageAssignment=array();

            if(!isset($_POST['personal']['averageAssignmentShiftSelect'])){
                foreach ($_POST['averageAssignmentShiftSelect'] as $key => $value) {
                        $averageAssignment[$key]['averageAssignmentShiftSelect']=$value;
                        $averageAssignment[$key]['averageAssignmentWeight']=$_POST['averageAssignmentWeight'][$key];
                        $averageAssignment[$key]['averageAssignment']=$_POST['averageAssignment'][$key];
                        $averageAssignment[$key]['averageAssignmentStatus']=$_POST['averageAssignmentStatus'][$key];
                    }
                    
                    $average_assignment=$averageAssignment;
            }else{
                $average_assignment[0]['averageAssignmentShiftSelect']=$_POST['personal']['averageAssignmentShiftSelect'];
                $average_assignment[0]['averageAssignmentWeight']=$_POST['personal']['averageAssignmentWeight'];
                $average_assignment[0]['averageAssignment']=$_POST['personal']['averageAssignment'];
                $average_assignment[0]['averageAssignmentStatus']=$_POST['personal']['averageAssignmentStatus'];

                if(isset($_POST['averageAssignmentShiftSelect'])&&!empty($_POST['averageAssignmentShiftSelect'])){
                    foreach ($_POST['averageAssignmentShiftSelect'] as $key => $value) {
                        $averageAssignment[$key]['averageAssignmentShiftSelect']=$value;
                        $averageAssignment[$key]['averageAssignmentWeight']=$_POST['averageAssignmentWeight'][$key];
                        $averageAssignment[$key]['averageAssignment']=$_POST['averageAssignment'][$key];
                        $averageAssignment[$key]['averageAssignmentStatus']=$_POST['averageAssignmentStatus'][$key];
                    }
                    
                    $average_assignment=array_merge($average_assignment,$averageAssignment);
                }
            }
            

            $patternList['averageAssignment']=$average_assignment;




            //只给男性安排班次

            $shiftdOnlyforMan=array();
            $shift_for_man=array();

            if(!isset($_POST['personal']['shiftdOnlyforManShiftSelect'])){
                foreach ($_POST['shiftdOnlyforManShiftSelect'] as $key => $value) {
                    $shift_for_man[$key]['shiftdOnlyforManShiftSelect']=$value;
                    $shift_for_man[$key]['shiftdOnlyforManWeight']=$_POST['shiftdOnlyforManWeight'][$key];
                    $shift_for_man[$key]['shiftdOnlyforManStatus']=$_POST['shiftdOnlyforManStatus'][$key];
                }
                 //重新组合数组；
                $shiftdOnlyforMan=$shift_for_man;

            }else{
                $shiftdOnlyforMan[0]['shiftdOnlyforManShiftSelect']=$_POST['personal']['shiftdOnlyforManShiftSelect'];
                $shiftdOnlyforMan[0]['shiftdOnlyforManWeight']=$_POST['personal']['shiftdOnlyforManWeight'];
                $shiftdOnlyforMan[0]['shiftdOnlyforManStatus']=$_POST['personal']['shiftdOnlyforManStatus'];

                
                if(isset($_POST['shiftdOnlyforManShiftSelect'])&&!empty($_POST['shiftdOnlyforManShiftSelect'])){

                    foreach ($_POST['shiftdOnlyforManShiftSelect'] as $key => $value) {
                    $shift_for_man[$key]['shiftdOnlyforManShiftSelect']=$value;
                    $shift_for_man[$key]['shiftdOnlyforManWeight']=$_POST['shiftdOnlyforManWeight'][$key];
                    $shift_for_man[$key]['shiftdOnlyforManStatus']=$_POST['shiftdOnlyforManStatus'][$key];
                    }
                     //重新组合数组；
                    $shiftdOnlyforMan=array_merge($shiftdOnlyforMan,$shift_for_man);

                }
            }
            
            $patternList['shiftdOnlyforMan']=$shiftdOnlyforMan;


            //该班次分配后间隔后再分配

            $assignment_after_interval=array();
            $assignmentAfterInterval=array();

            if(!isset($_POST['personal']['assignmentAfterIntervalShiftSelect'])){

                foreach ($_POST['assignmentAfterIntervalShiftSelect'] as $key => $value) {
                        $assignmentAfterInterval[$key]['assignmentAfterIntervalShiftSelect']=$value;
                        $assignmentAfterInterval[$key]['assignmentAfterIntervalWeight']=$_POST['assignmentAfterIntervalWeight'][$key];
                        $assignmentAfterInterval[$key]['assignmentAfterIntervalEmployee']=$_POST['assignmentAfterIntervalEmployee'][$key];
                        $assignmentAfterInterval[$key]['assignmentAfterIntervalStatus']=$_POST['assignmentAfterIntervalStatus'][$key];
                    }
                $assignment_after_interval=$assignmentAfterInterval;


            }else{
                $assignment_after_interval[0]['assignmentAfterIntervalShiftSelect']=$_POST['personal']['assignmentAfterIntervalShiftSelect'];
                $assignment_after_interval[0]['assignmentAfterIntervalWeight']=$_POST['personal']['assignmentAfterIntervalWeight'];
                $assignment_after_interval[0]['assignmentAfterIntervalEmployee']=$_POST['personal']['assignmentAfterIntervalEmployee'];
                $assignment_after_interval[0]['assignmentAfterIntervalStatus']=$_POST['personal']['assignmentAfterIntervalStatus'];

                if(isset($_POST['assignmentAfterIntervalShiftSelect'])&&!empty($_POST['assignmentAfterIntervalShiftSelect'])){
                    foreach ($_POST['assignmentAfterIntervalShiftSelect'] as $key => $value) {
                        $assignmentAfterInterval[$key]['assignmentAfterIntervalShiftSelect']=$value;
                        $assignmentAfterInterval[$key]['assignmentAfterIntervalWeight']=$_POST['assignmentAfterIntervalWeight'][$key];
                        $assignmentAfterInterval[$key]['assignmentAfterIntervalEmployee']=$_POST['assignmentAfterIntervalEmployee'][$key];
                        $assignmentAfterInterval[$key]['assignmentAfterIntervalStatus']=$_POST['assignmentAfterIntervalStatus'][$key];
                    }
                    
                    $assignment_after_interval=array_merge($assignment_after_interval,$assignmentAfterInterval);
                }
            }


            $patternList['assignmentAfterInterval']=$assignment_after_interval;


            //该班次不分配给某员工
            $shift_notfor_employee=array();
            $shiftNotForEmployee=array();


            if(!isset($_POST['personal']['shiftNotForEmployeeShiftSelect'])){

                foreach ($_POST['shiftNotForEmployeeShiftSelect'] as $key => $value) {
                    $shiftNotForEmployee[$key]['shiftNotForEmployeeShiftSelect']=$value;
                    $shiftNotForEmployee[$key]['shiftNotForEmployeeWeight']=$_POST['shiftNotForEmployeeWeight'][$key];
                    $shiftNotForEmployee[$key]['shiftNotForEmployee']=$_POST['shiftNotForEmployee'][$key];
                    $shiftNotForEmployee[$key]['shiftDate']=$_POST['shiftDate'][$key];
                    $shiftNotForEmployee[$key]['shiftNotForEmployeeStatus']=$_POST['shiftNotForEmployeeStatus'][$key];
                }

                $shift_notfor_employee=$shiftNotForEmployee;

            }else{

                    $shift_notfor_employee[0]['shiftNotForEmployeeShiftSelect']=$_POST['personal']['shiftNotForEmployeeShiftSelect'];
                    $shift_notfor_employee[0]['shiftNotForEmployeeWeight']=$_POST['personal']['shiftNotForEmployeeWeight'];
                    $shift_notfor_employee[0]['shiftNotForEmployee']=$_POST['personal']['shiftNotForEmployee'];
                    $shift_notfor_employee[0]['shiftDate']=$_POST['personal']['shiftDate'];
                    $shift_notfor_employee[0]['shiftNotForEmployeeStatus']=$_POST['personal']['shiftNotForEmployeeStatus'];

                    if(isset($_POST['shiftNotForEmployeeShiftSelect'])&&!empty($_POST['shiftNotForEmployeeShiftSelect'])){
                
                        foreach ($_POST['shiftNotForEmployeeShiftSelect'] as $key => $value) {
                            $shiftNotForEmployee[$key]['shiftNotForEmployeeShiftSelect']=$value;
                            $shiftNotForEmployee[$key]['shiftNotForEmployeeWeight']=$_POST['shiftNotForEmployeeWeight'][$key];
                            $shiftNotForEmployee[$key]['shiftNotForEmployee']=$_POST['shiftNotForEmployee'][$key];
                            $shiftNotForEmployee[$key]['shiftDate']=$_POST['shiftDate'][$key];
                            $shiftNotForEmployee[$key]['shiftNotForEmployeeStatus']=$_POST['shiftNotForEmployeeStatus'][$key];
                        }
                        $shift_notfor_employee=array_merge($shift_notfor_employee,$shiftNotForEmployee);
                    }

            }

           


            $patternList['shiftNotForEmployee']=$shift_notfor_employee;


            //该班次分配给某员工
            $shift_for_employee=array();
            $shiftForEmployee=array();

            if(!isset($_POST['personal']['shiftForEmployeeShiftSelect'])){

                foreach ($_POST['shiftForEmployeeShiftSelect'] as $key => $value) {
                    $shiftForEmployee[$key]['shiftForEmployeeShiftSelect']=$value;
                    $shiftForEmployee[$key]['shiftForEmployeeWeight']=$_POST['shiftForEmployeeWeight'][$key];
                    $shiftForEmployee[$key]['shiftForEmployee']=$_POST['shiftForEmployee'][$key];
                    $shiftForEmployee[$key]['shiftDateForEmployee']=$_POST['shiftDateForEmployee'][$key];
                    $shiftForEmployee[$key]['shiftForEmployeeStatus']=$_POST['shiftForEmployeeStatus'][$key];
                }

                $shift_for_employee=$shiftForEmployee;

            }else{

                    $shift_for_employee[0]['shiftForEmployeeShiftSelect']=$_POST['personal']['shiftForEmployeeShiftSelect'];
                    $shift_for_employee[0]['shiftForEmployeeWeight']=$_POST['personal']['shiftForEmployeeWeight'];
                    $shift_for_employee[0]['shiftForEmployee']=$_POST['personal']['shiftForEmployee'];
                    $shift_for_employee[0]['shiftDateForEmployee']=$_POST['personal']['shiftDateForEmployee'];
                    $shift_for_employee[0]['shiftForEmployeeStatus']=$_POST['personal']['shiftForEmployeeStatus'];

                   
                    if(isset($_POST['shiftForEmployeeShiftSelect'])&&!empty($_POST['shiftForEmployeeShiftSelect'])){
                
                        foreach ($_POST['shiftForEmployeeShiftSelect'] as $key => $value) {
                            $shiftForEmployee[$key]['shiftForEmployeeShiftSelect']=$value;
                            $shiftForEmployee[$key]['shiftForEmployeeWeight']=$_POST['shiftForEmployeeWeight'][$key];
                            $shiftForEmployee[$key]['shiftForEmployee']=$_POST['shiftForEmployee'][$key];
                            $shiftForEmployee[$key]['shiftDateForEmployee']=$_POST['shiftDateForEmployee'][$key];
                            $shiftForEmployee[$key]['shiftForEmployeeStatus']=$_POST['shiftForEmployeeStatus'][$key];
                        }
                        $shift_for_employee=array_merge($shift_for_employee,$shiftForEmployee);
                    }

            }

            $patternList['shiftForEmployee']=$shift_for_employee;

            //该班次分配后持续分配
            $assignment_after_shift=array();
            $assignmentAfterShift=array();

            if(!isset($_POST['personal']['assignmentAfterShiftSelect'])){

                foreach ($_POST['assignmentAfterShiftSelect'] as $key => $value) {
                    $assignmentAfterShift[$key]['assignmentAfterShiftSelect']=$value;
                    $assignmentAfterShift[$key]['assignmentAfterShiftWeight']=$_POST['assignmentAfterShiftWeight'][$key];
                    $assignmentAfterShift[$key]['assignmentAfterShiftDays']=$_POST['assignmentAfterShiftDays'][$key];
                    $assignmentAfterShift[$key]['assignmentAfterShiftStatus']=$_POST['assignmentAfterShiftStatus'][$key];
                }

                $assignment_after_shift=$assignmentAfterShift;

            }else{

                    $assignment_after_shift[0]['assignmentAfterShiftSelect']=$_POST['personal']['assignmentAfterShiftSelect'];
                    $assignment_after_shift[0]['assignmentAfterShiftWeight']=$_POST['personal']['assignmentAfterShiftWeight'];
                    $assignment_after_shift[0]['assignmentAfterShiftDays']=$_POST['personal']['assignmentAfterShiftDays'];
                    $assignment_after_shift[0]['assignmentAfterShiftStatus']=$_POST['personal']['assignmentAfterShiftStatus'];

                   
                    if(isset($_POST['assignmentAfterShiftSelect'])&&!empty($_POST['assignmentAfterShiftSelect'])){
                
                        foreach ($_POST['assignmentAfterShiftSelect'] as $key => $value) {
                            $assignmentAfterShift[$key]['assignmentAfterShiftSelect']=$value;
                            $assignmentAfterShift[$key]['assignmentAfterShiftWeight']=$_POST['assignmentAfterShiftWeight'][$key];
                            $assignmentAfterShift[$key]['assignmentAfterShiftDays']=$_POST['assignmentAfterShiftDays'][$key];
                            $assignmentAfterShift[$key]['assignmentAfterShiftStatus']=$_POST['assignmentAfterShiftStatus'][$key];
                        }
                        $assignment_after_shift=array_merge($assignment_after_shift,$assignmentAfterShift);
                    }

            }

            
            $patternList['assignmentAfterShift']=$assignment_after_shift;



            //不希望此班次后继续班次
            $no_behind_shift=array();
            $noBehindShift=array();

            if(!isset($_POST['personal']['startShiftSelect'])){

                foreach ($_POST['startShiftSelect'] as $key => $value) {
                    $noBehindShift[$key]['startShiftSelect']=$value;
                    $noBehindShift[$key]['restAfterOneShiftWeight']=$_POST['restAfterOneShiftWeight'][$key];
                    $noBehindShift[$key]['nextShiftSelect']=$_POST['nextShiftSelect'][$key];
                    $noBehindShift[$key]['restAfterOneShiftStatus']=$_POST['restAfterOneShiftStatus'][$key];
                }

                $no_behind_shift=$noBehindShift;

            }else{

                    $no_behind_shift[0]['startShiftSelect']=$_POST['personal']['startShiftSelect'];
                    $no_behind_shift[0]['restAfterOneShiftWeight']=$_POST['personal']['restAfterOneShiftWeight'];
                    $no_behind_shift[0]['nextShiftSelect']=$_POST['personal']['nextShiftSelect'];
                    $no_behind_shift[0]['restAfterOneShiftStatus']=$_POST['personal']['restAfterOneShiftStatus'];

                   
                    if(isset($_POST['startShiftSelect'])&&!empty($_POST['startShiftSelect'])){
                
                        foreach ($_POST['startShiftSelect'] as $key => $value) {
                            $noBehindShift[$key]['startShiftSelect']=$value;
                            $noBehindShift[$key]['restAfterOneShiftWeight']=$_POST['restAfterOneShiftWeight'][$key];
                            $noBehindShift[$key]['nextShiftSelect']=$_POST['nextShiftSelect'][$key];
                            $noBehindShift[$key]['restAfterOneShiftStatus']=$_POST['restAfterOneShiftStatus'][$key];
                        }
                        $no_behind_shift=array_merge($no_behind_shift,$noBehindShift);
                    }

            }
            
            $patternList['restAfterOneShift']=$no_behind_shift;

            //每周公休分配
            $freeTwoDays=array();
            $freeTwoDays['freeTwoDaysWeight']=$_POST['personal']['freeTwoDaysWeight'];
            $freeTwoDays['freeTwoDaysSelect']=$_POST['personal']['freeTwoDaysSelect'];
            $freeTwoDays['freeTwoDaysStatus']=$_POST['personal']['freeTwoDaysStatus'];
            $patternList['freeTwoDays']=$freeTwoDays;

            //最多允许连续工作几个周末
            $maxWeekendShift=array();
            $maxWeekendShift['maxWeekendShiftWeight']=$_POST['personal']['maxWeekendShiftWeight'];
            $maxWeekendShift['allowWeekendShift']=$_POST['personal']['allowWeekendShift'];
            $maxWeekendShift['maxWeekendShiftStatus']=$_POST['personal']['maxWeekendShiftStatus'];
            $patternList['maxWeekendShift']=$maxWeekendShift;

            //最少任务天数分配

            $minWorkDay=array();
            $minWorkDay['minWorkDayWeight']=$_POST['personal']['minWorkDayWeight'];
            $minWorkDay['minWorkDayCount']=$_POST['personal']['minWorkDayCount'];
            $minWorkDay['minWorkDayStatus']=$_POST['personal']['minWorkDayStatus'];
            $patternList['minWorkDay']=$minWorkDay;

            //最多任务天数分配

            $maxWorkDay=array();
            $maxWorkDay['maxWorkDayWeight']=$_POST['personal']['maxWorkDayWeight'];
            $maxWorkDay['maxWorkDayCount']=$_POST['personal']['maxWorkDayCount'];
            $maxWorkDay['maxWorkDayStatus']=$_POST['personal']['maxWorkDayStatus'];
            $patternList['maxWorkDay']=$maxWorkDay;

            //周六日连休
            $restOnStaAndSun=array();
            $restOnStaAndSun['restOnStaAndSunWeight']=$_POST['personal']['restOnStaAndSunWeight'];
            $restOnStaAndSun['restOnStaAndSunOn']=$_POST['personal']['restOnStaAndSunOn'];
            $restOnStaAndSun['restOnStaAndSunStatus']=$_POST['personal']['restOnStaAndSunStatus'];
            $patternList['restOnStaAndSun']=$restOnStaAndSun;

            //周六工作在周二或周四安排调休
            $restOnTuOrTues=array();
            $restOnTuOrTues['restOnTuOrTuesWeight']=$_POST['personal']['restOnTuOrTuesWeight'];
            $restOnTuOrTues['restOnTuOrTuesStatus']=$_POST['personal']['restOnTuOrTuesStatus'];
            $patternList['restOnTuOrTues']=$restOnTuOrTues;

            //连续周末分配同一班次
            $continuWeekOneShift=array();
            $continuWeekOneShift['continuWeekOneShiftWeight']=$_POST['personal']['continuWeekOneShiftWeight'];
            $continuWeekOneShift['continuWeekOneShiftStatus']=$_POST['personal']['continuWeekOneShiftStatus'];
            $patternList['continuWeekOneShift']=$continuWeekOneShift;

            //最少允许连续工作几个周末
            $minWorkWeekendNum=array();
            $minWorkWeekendNum['minWorkWeekendCount']=$_POST['personal']['minWorkWeekendCount'];
            $minWorkWeekendNum['minWorkWeekendStatus']=$_POST['personal']['minWorkWeekendStatus'];
            $patternList['minWorkWeekendNum']=$minWorkWeekendNum;

            $file_name='patternContranct_'.$schedule_id;

            $data=$this->getShiftService()->saveFile($file_name,$patternList);

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
