
<?php

abstract class BaseWorkShiftResult extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ohrm_work_shift_result');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));

        $this->hasColumn('schedule_id', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('emp_number', 'integer', 7, array(
             'type' => 'integer',
             'length' => 7,
             ));
        $this->hasColumn('shift_type_id', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));


        $this->hasColumn('shift_date', 'date', 25, array(
             'type' => 'date',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 25,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('WorkSchedule', array(
             'local' => 'schedule_id',
             'foreign' => 'id'));
        $this->hasOne('WorkShiftType', array(
             'local' => 'shift_type_id',
             'foreign' => 'id'));
       
    }
}