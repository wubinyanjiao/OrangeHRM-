<?php


class ShiftDao extends BaseDao {

    public function saveShift(WorkShiftNew $shift) {

      // echo '<pre>';  var_dump($shift);exit;
        try {
            $shift->save();            
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
        
    }

    public function saveShiftRotary(WorkShiftRotary $shiftRotary) {
         
        try {
            $shiftRotary->save();            
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function saveRotaryResult(WorkRotaryEmplayee $workRotary) {
        
        try {
            $workRotary->save();            
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function saveWorkEntityIds(WorkShiftEntityIds $shiftEntity) {
        try {
            $shiftEntity->save();            
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
        
    }

    public function saveShiftAssignments(WorkShiftAssignment $shiftAssignment) {
        try {
            $shiftAssignment->save();            
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
        
    }

    
    public function saveContranctType(WorkContranctType $contranct_type) {

        try {
            $contranct_type->save();            
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
        
    }


    public function getShiftById($id) {
        
        try {
            return Doctrine::getTable('WorkShiftNew')->find($id);
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
        
    }

    public function getShifts($scheduleId) {
        
        try {
            $q = Doctrine_Query:: create()->from('WorkShiftNew st')
                            ->where('st.schedule_id = ?', $scheduleId)
                            ->orderBy('st.id ASC');

            return $q->execute();

        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
   
    }



    public function getShiftByDate($shiftDateId) {
        
        try {
            $q = Doctrine_Query:: create()->from('WorkShiftNew st')
                            ->where('st.shiftdate_id = ?', $shiftDateId)
                            ->orderBy('st.id ASC');

            return $q->fetchArray();

        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
   
    }

    public function getShiftByType($shiftTypeId) {
        
        try {
            $q = Doctrine_Query:: create()->from('WorkShiftNew st')
                            ->where('st.shift_type_id = ?', $shiftTypeId)
                            ->orderBy('st.id ASC');

            return $q->fetchArray();

        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
   
    }

     public function getShiftList($scheduleId) {
         
        try {
            $q = Doctrine_Query:: create()->from('WorkShiftNew sc')
                            ->leftJoin('sc.WorkSchedule t')
                            ->leftJoin('sc.shiftDate sd')
                            ->leftJoin('sc.shiftType st')
                            ->where('sc.schedule_id = ?', $scheduleId)
                            ->orderBy('sc.id ASC');

            return $q->fetchArray();

        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
        
    }
    

    public function saveShiftResult(WorkShiftResult $shiftResult) {
        
        try {
            $shiftResult->save();            
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
        
    }


    public function getShiftDateList($scheduleId) {
         
        try {
            $q = Doctrine_Query:: create()->from('WorkShiftDate sd')
                            ->leftJoin('sd.shiftList t')
                            ->where('sd.schedule_id = ?', $scheduleId)
                            ->orderBy('sd.id ASC');

            return $q->fetchArray();

        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
        
    }

 
    public function getShiftDateListArr($scheduleId) {
         
        try {
            $q = Doctrine_Query:: create()->from('WorkShiftDate sd')
                      
                            ->where('sd.schedule_id = ?', $scheduleId)
                            ->orderBy('sd.id ASC');

            return $q->fetchArray();

        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
        
    }

    public function getShiftDateById($dateId) {
         
        try {
            $q = Doctrine_Query:: create()->from('WorkShiftDate sd')
                            ->leftJoin('sd.shiftList t')
                            ->where('sd.id = ?', $dateId)
                            ->orderBy('sd.id ASC');

            return $q->fetchOne();

        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
        
    }

    public function getJobDepartmentList() {

        try {
            $q = Doctrine_Query :: create()
                ->from('JobDepartment')
                ->orderBy('name ASC');
            return $q->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    public function getShiftTypeList($scheduleId) {
         
        try {
            $q = Doctrine_Query:: create()->from('WorkShiftType st')
                            ->where('st.schedule_id = ?', $scheduleId)
                            ->orderBy('st.id ASC');

            return $q->fetchArray();

        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
        
    }


    public function getRosterResult($scheduleID){

        try {
            $q = Doctrine_Query:: create()->from('WorkShiftResult st')
                            ->where('st.schedule_id = ?', $scheduleID)
                            ->orderBy('st.id ASC');

            return $q->fetchArray();

        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
  
    }

    public function getShiftTypeById($typeid) {
         
        try {
            $q = Doctrine_Query:: create()->from('WorkShiftType st')
                            ->where('st.id = ?', $typeid)
                            ->orderBy('st.id ASC');

            return $q->fetchOne();

        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
        
    }

    public function getShiftResutById($id){
        try {
            $q = Doctrine_Query:: create()->from('WorkShiftResult st')
                            ->where('st.id = ?', $id)
                            ->orderBy('st.id ASC');

            return $q->fetchOne();

        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }

    }


    public function getShiftAssignmentList($scheduleId) {
         
        try {
            $q = Doctrine_Query:: create()->from('WorkShiftAssignment st')
                            ->where('st.schedule_id = ?', $scheduleId)
                            ->orderBy('st.shift_date ASC');

            return $q->fetchArray();

        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
        
    }

    public function getSkillList() {
         
        try {
           $q = Doctrine_Query::create()->from('Skill')
                                         ->orderBy('name');

            return $q->fetchArray();

        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
        
    }

     public function getEmployeeList($orderField = 'lastName', $orderBy = 'ASC', $includeTerminatedEmployees = false) {
        try {

            $q = Doctrine_Query :: create()->from('Employee e')
            ->leftJoin("e.EmployeeEducation d")
            ->leftJoin("e.EmployeeWorkShift w");
            $orderBy = strcasecmp($orderBy, 'DESC') === 0 ? 'DESC' : 'ASC';
            $q->orderBy($orderField . ' ' . $orderBy);

            if (!$includeTerminatedEmployees) {
                $q->andwhere("termination_id IS NULL");
            }

            return $q->fetchArray();
        // @codeCoverageIgnoreStart
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
        // @codeCoverageIgnoreEnd
    }

     /**
     * Get Skill
     * @param int $empNumber
     * @param int $SkillCode
     * @returns Collection/Skill
     * @throws DaoException
     */
    public function getEmployeeSkillList() {
        try {
            $q = Doctrine_Query::create()
                            ->from('EmployeeSkill es')
                            ->leftJoin('es.Skill s');

            $q->orderBy('s.name ASC');
            return $q->fetchArray();
        // @codeCoverageIgnoreStart
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
        // @codeCoverageIgnoreEnd
    }

    public function getShiftTypeToSkillList($schedule_id) {
        try {
            $q = Doctrine_Query::create()->from('WorkTypeSkill ws')
                                        ->where('ws.schedule_id = ?', $schedule_id)
                                         ->orderBy('id');

            return $q->fetchArray();
        // @codeCoverageIgnoreStart
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
        // @codeCoverageIgnoreEnd
    }

    public function getEmoloyeeLocation() {
        try {
            /*$q = Doctrine_Query::create()->from('EmpLocations el')
                                         ->leftJoin('el.Employee e')
                                         ->orderBy('el.emp_number');*/
            $q = Doctrine_Query::create()->from('Employee e')
                                         ->leftJoin('e.EmpLocations el')
                                         ->leftJoin('e.EmployeeEducation ee')
                                         ->orderBy('e.emp_number');

            return $q->fetchArray();
        // @codeCoverageIgnoreStart
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
        // @codeCoverageIgnoreEnd
    }

    public function getLeaderList(){
        try {
            $q = Doctrine_Query::create()->from('Subunit')
                                         ->orderBy('id');
            return $q->fetchArray();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getShiftRosters(){
        try {
            $q = Doctrine_Query::create()->from('WorkShiftRotary')
                                         ->orderBy('id');
            return $q->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }



    /**
     * Check if user with given userId is a admin
     * @param string $userId
     * @return bool - True if given user is a admin, false if not
     */
    public function isAdmin($userId) {
        try {
            $q = Doctrine_Query :: create()
                            ->from('SystemUser')
                            ->where('id = ?', $userId)
                            ->andWhere('deleted = ?', SystemUser::UNDELETED)
                            ->andWhere('status = ?', SystemUser::ENABLED)
                            ->andWhere('user_role_id = ?', SystemUser::ADMIN_USER_ROLE_ID);

            $result = $q->fetchOne();
            
            if ($result instanceof SystemUser) {
                return true;
            }
            
            return false;
            
        // @codeCoverageIgnoreStart
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
        // @codeCoverageIgnoreEnd
    }

    
    public function getShiftContranct($shift_id,$status) {

        try {
            $q = Doctrine_Query:: create()->from('WorkShiftContranct sc')
                            ->where('sc.shift_id = ?', $shift_id)
                            ->andwhere('sc.status=?',$status)
                            ->orderBy('sc.id ASC');

            return $q->fetchOne();

        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
   
    }

    public function getRotaryEmpListById($rotary_id) {

        try {
            $q = Doctrine_Query:: create()->from('WorkRotaryEmplayee we')
                            ->where('we.rotary_id = ?', $rotary_id)
                            ->orderBy('we.id ASC');

            return $q->fetchArray();

        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getEntityByName($schedule_id,$tag_name) {
        try {
            $q = Doctrine_Query:: create()->from('WorkShiftEntityIds se')
                            ->where('se.schedule_id = ?', $schedule_id)
                            ->andwhere('se.tag_name=?',$tag_name)
                            ->orderBy('se.id ASC');

            return $q->fetchArray();

        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
   
    }

    public function getShiftContrancts($scheduleId,$status=null) {

        try {
            $q = Doctrine_Query:: create()->from('WorkShiftContranct ct')
                            ->where('ct.schedule_id = ?', $scheduleId)
                            ->orderBy('ct.id ASC');
            if (!is_null($status)) {
                $q->andwhere('ct.status = ?', $status);
            }

            return $q->execute();

        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
   
    }

    public function getShiftByTypeAndDate($shiftype,$shifDate) {

        try {
            $q = Doctrine_Query:: create()->from('WorkShiftNew st')
                            ->where('st.shift_type_id = ?', $shiftype)
                            ->andWhere('st.shiftdate_id = ?', $shifDate)
                            ->orderBy('st.id ASC');
           

            return $q->fetchOne();

           

        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function saveEmployeeWorkShiftCollection(Doctrine_Collection $empWorkShiftCollection) {
        try {

            $empWorkShiftCollection->save();

            // @codeCoverageIgnoreStart
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
        // @codeCoverageIgnoreEnd
    }



    public function saveShiftContranct(WorkShiftContranct $shiftcontranct) {

        try {
            $shiftcontranct->save();            
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
        
    }

    public function getContranctType($id,$status) {

        try {
            $q = Doctrine_Query:: create()->from('WorkContranctType ct')
                            ->where('ct.id = ?', $id)
                            ->andwhere('ct.status=?',$status)
                            ->orderBy('ct.id ASC');
                           

            return $q->fetchOne();

        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
   
    }

    public function getContranctTypes($scheduleId,$status=null) {

        try {
            $q = Doctrine_Query:: create()->from('WorkContranctType ct')
                            ->where('ct.schedule_id = ?', $scheduleId)
                            ->orderBy('ct.id ASC');
            if (!is_null($status)) {
                $q->andwhere('ct.status = ?', $status);
            }

            return $q->execute();

        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
   
    }


    



}
