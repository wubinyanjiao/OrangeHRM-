<?php

/*
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
 * EmployeeDao for CRUD operation
 *
 */
class ShiftTypeDao extends BaseDao {

    public function saveShiftType(WorkShiftType $shiftType) {
        
        try {
            $shiftType->save();            
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
        
    }
    public function getShiftTypeById($id) {
        
        try {
            return Doctrine::getTable('WorkShiftType')->find($id);
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
        
    }

    public function getShiftTypes($scheduleId) {
      
        try {
            $q = Doctrine_Query:: create()->from('WorkShiftType st')
                            ->where('st.schedule_id = ?', $scheduleId)
                            ->orderBy('st.id ASC');
            return $q->execute();

        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
   
    }

    public function getSkillsByType($shifttype_id) {
      
        try {
            $q = Doctrine_Query:: create()->from('WorkTypeSkill ts')
                            ->where('ts.shift_type_id = ?', $shifttype_id)
                            ->orderBy('ts.id ASC');
            return $q->execute();

        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
   
    }
    public function getSkillsArrayByType($shifttype_id) {
      
        try {
            $q = Doctrine_Query:: create()->from('WorkTypeSkill ts')
                            ->where('ts.shift_type_id = ?', $shifttype_id)
                            ->orderBy('ts.id ASC');
            return $q->fetchArray();

        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
   
    }

     public function getShiftTypeList() {
        
        try {
            
            $q = Doctrine_Query::create()->from('WorkShiftType')
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

}
