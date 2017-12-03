<?php


abstract class BaseWorkShiftDate extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ohrm_work_shift_date');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));

        $this->hasColumn('schedule_id as scheduleId', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));


        $this->hasColumn('shift_date as shiftDate', 'date', 25, array(
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
        $this->hasMany('WorkShiftNew as shiftList', array(
             'local' => 'id',
             'foreign' => 'shiftdate_id'));
    }

    
}