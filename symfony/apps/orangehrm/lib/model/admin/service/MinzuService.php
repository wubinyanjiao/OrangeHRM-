<?php

/*
 * 
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
 * 
 */

class MinzuService extends BaseService {

    protected $minzuDao;

    /**
     * 
     * @return MinzuDao
     */
    public function getMinzuDao() {
        if (!($this->minzuDao instanceof MinzuDao)) {
            $this->minzuDao = new MinzuDao();
        }
        return $this->minzuDao;
    }

    /**
     *
     * @param MinzuDao $dao 
     */
    public function setMinzuDao(MinzuDao $dao) {
        $this->minzuDao = $dao;
    }

    /**
     * Get minzu list
     * @return minzu
     */
    public function getMinzuList() {

        try {
            $q = Doctrine_Query::create()
                    ->from('Minzu c')
                    ->orderBy('c.id');
            $minzuList = $q->execute();
            return $minzuList;
        } catch (Exception $e) {
            throw new AdminServiceException($e->getMessage());
        }
    }

    /**
     * 
     * @return Province
     */
    public function getProvinceList() {
        try {
            $q = Doctrine_Query::create()
                    ->from('Province p')
                    ->orderBy('p.province_name');

            $provinceList = $q->execute();

            return $provinceList;
        } catch (Exception $e) {
            throw new AdminServiceException($e->getMessage());
        }
    }

    /**
     *
     * @param array $searchParams 
     */
    public function searchCountries(array $searchParams) {
        try {
            return $this->getminzuDao()->searchCountries($searchParams);
        } catch (Exception $e) {
            throw new ServiceException($e->getMessage());
        }
    }

    /**
     * Get minzu By minzu Name
     * @param $minzuName
     * @return Doctrine_Record
     * @throws DaoException
     */
    public function getMinzuByMinzuName($minzuName){
        return $this->getMinzuDao()->getMinzuByMinzuName($minzuName);
    }

    /**
     * Get minzu by minzu code
     *
     * @param $minzuCode
     * @return Doctrine_Record
     */
    public function getMinzuByMinzuCode($minzuCode){
        return $this->getMinzuDao()->getMinzuByMinzuCode($minzuCode);
    }


}