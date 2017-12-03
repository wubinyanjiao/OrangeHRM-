<?php

abstract class BaseWorkShiftNew extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ohrm_work_shift');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('name', 'string', 260, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 260,
             ));
        $this->hasColumn('hours_per_day as hoursPerDay', 'decimal', 4, array(
             'type' => 'decimal',
             'notnull' => true,
             'scale' => false,
             'length' => 4,
             ));
        $this->hasColumn('start_time', 'time', null, array(
             'type' => 'time',
             'notnull' => true,
             ));
        $this->hasColumn('end_time', 'time', null, array(
             'type' => 'time',
             'notnull' => 'true;',
             ));
        $this->hasColumn('schedule_id', 'integer', 11, array(
         'type' => 'integer',
         'length' => 11,
         ));
        $this->hasColumn('shift_type_id', 'integer', 11, array(
         'type' => 'integer',
         'length' => 11,
         ));
        $this->hasColumn('shiftdate_id', 'integer', 11, array(
         'type' => 'integer',
         'length' => 11,
         ));

        $this->hasColumn('required_employee', 'integer', 11, array(
         'type' => 'integer',
         'length' => 11,
         ));

        $this->hasColumn('create_at', 'date', 25, array(
             'type' => 'date',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 25,
             ));
        $this->hasColumn('status', 'string', 260, array(
             'type' => 'string',
             'notnull' => 'true;',
             'length' => 260,
             ));
    }

    // public function setUp()
    // {
    //     parent::setUp();

    //     $this->hasOne('WorkShiftType', array(
    //          'local' => 'shift_type_id',
    //          'foreign' => 'id'));
    // }
    public function setUp()
    {
        parent::setUp();
      
        $this->hasOne('WorkSchedule', array(
             'local' => 'schedule_id',
             'foreign' => 'id'));
        $this->hasOne('WorkShiftType as shiftType', array(
             'local' => 'shift_type_id',
             'foreign' => 'id'));

        $this->hasOne('WorkShiftDate as shiftDate', array(
             'local' => 'shiftdate_id',
             'foreign' => 'id'));



}
}