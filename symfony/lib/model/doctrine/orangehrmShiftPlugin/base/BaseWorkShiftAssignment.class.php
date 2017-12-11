<?php

abstract class BaseWorkShiftAssignment extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ohrm_work_shift_assignment');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));


        $this->hasColumn('schedule_id', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('shift_id', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('shift_index', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));
       
    }

     public function setUp()
    {
        parent::setUp();
        $this->hasOne('WorkSchedule', array(
             'local' => 'schedule_id',
             'foreign' => 'id'));
       
    }
}