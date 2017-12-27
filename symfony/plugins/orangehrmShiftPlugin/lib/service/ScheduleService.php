<?php

class ScheduleService extends BaseService {

    /**
     * @ignore
     */
    private $scheduleDao;
    private $shiftDao;
    private $configurationService;

    public function getScheduleDao() {
        if (is_null($this->scheduleDao)) {
            $this->scheduleDao = new ScheduleDao();
        }
        return $this->scheduleDao;
    }

    public function setScheduleDao(ScheduleDao $scheduleDao){

        $this->scheduleDao=$scheduleDao;
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
        $this->scheduleDao = new ScheduleDao();
    }


    public function saveSchedule(WorkSchedule $schedule) {
        return $this->getScheduleDao()->saveSchedule($schedule);
    }


    public function getScheduleList() {
        return $this->scheduleDao->getScheduleList();
    }

    public function getScheduleListNew() {
        return $this->scheduleDao->getScheduleListNew();
    }

    public function updateSchedule($schedule) {
        return $this->scheduleDao->updateSchedule($schedule);
    }

    public function saveShiftDate(WorkSchedule $schedule) {
        return $this->getScheduleDao()->saveSchedule($schedule);
    }
    
    public function getShiftContranctsBySchedule($status=null) {

        return $this->getScheduleDao()->getShiftContranctsBySchedule($status);
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


    public function getScheduleById($id) {
        return $this->scheduleDao->getScheduleById($id);
    }

}
