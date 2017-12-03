<?php

class ShiftDateService extends BaseService {

    /**
     * @ignore
     */
    private $shiftDateDao;
    private $configurationService;

    /**
     * Get Employee Dao
     * @return EmployeeDao
     * @ignore
     */
    public function getShiftDateDao() {
        return $this->shiftDateDao;
    }

    /**
     * Set Employee Dao
     * @param EmployeeDao $shiftDateDao
     * @return void
     * @ignore
     */
    public function setShiftDateDao(ShiftDateDao $shiftDateDao) {
        $this->shiftDateDao = $shiftDateDao;
    }
    
    /**
     * Set Configuration Service
     * @param ConfigService $configurationService
     * @return void
     * @ignore
     */
    public function setConfigurationService(ConfigService $configurationService) {
        $this->configurationService = $configurationService;
    }
    
    /**
     * Get Configuration Service
     * @return ConfigService
     * @ignore
     */
    public function getConfigurationService() {
        if($this->configurationService) {
            return $this->configurationService;
        } else {
            return new ConfigService();
        }
    }

    /**
     * Construct
     * @ignore
     */
    public function __construct() {
        $this->shiftDateDao = new ShiftDateDao();
    }


    public function saveShiftDate(WorkShiftDate $shiftDate) {
        return $this->getShiftDateDao()->saveShiftDate($shiftDate);
    }


    public function getShiftDates($schedule_id) {

        return $this->getShiftDateDao()->getShiftDates($schedule_id);
    }

    public function getShiftDateList() {
        return $this->getShiftDateDao()->getShiftDateList();
    }

    public function getShiftDateById($id) {
        return $this->getShiftDateDao()->getShiftDateById($id);
    }

    
    /**
     * Check if user with given userId is an admin
     * @param string $userId
     * @return bool - True if given user is an admin, false if not
     * @ignore
     *
     * @todo Move method to Auth Service
     */
    public function isAdmin($userId) {
        return $this->getEmployeeDao()->isAdmin($userId);
    }

    


}
