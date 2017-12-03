<?php

class ShiftTypeService extends BaseService {

    /**
     * @ignore
     */
    private $shiftTypeDao;
    private $configurationService;

    /**
     * Get Employee Dao
     * @return EmployeeDao
     * @ignore
     */
    public function getShiftTypeDao() {
        return $this->shiftTypeDao;
    }

    /**
     * Set Employee Dao
     * @param EmployeeDao $shiftTypeDao
     * @return void
     * @ignore
     */
    public function setShiftTypeDao(ShiftTypeDao $shiftTypeDao) {
        $this->shiftTypeDao = $shiftTypeDao;
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
        $this->shiftTypeDao = new ShiftTypeDao();
    }


    public function saveShiftType(WorkShiftType $shiftType) {
        return $this->getShiftTypeDao()->saveShiftType($shiftType);
    }

    public function getShiftTypes($schedule_id) {
        return $this->getShiftTypeDao()->getShiftTypes($schedule_id);
    }

    public function getSkillsByType($shifttype_id) {
        return $this->getShiftTypeDao()->getSkillsByType($shifttype_id);
    }

    public function getSkillsArrayByType($shifttype_id) {
        return $this->getShiftTypeDao()->getSkillsArrayByType($shifttype_id);
    }

    public function getShiftTypeList() {
        return $this->getShiftTypeDao()->getShiftTypeList();
    }

    public function getShiftTypeById($id) {
        return $this->getShiftTypeDao()->getShiftTypeById($id);
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
