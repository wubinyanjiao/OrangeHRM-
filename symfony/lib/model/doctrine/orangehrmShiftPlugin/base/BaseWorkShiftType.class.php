<?php

abstract class BaseWorkShiftType extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ohrm_work_shift_type');
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

        $this->hasColumn('schedule_id', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('skill_id', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));

        $this->hasColumn('copy_type', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));

        $this->hasColumn('require_employee', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));




        $this->hasColumn('start_time', 'time', null, array(
             'type' => 'time',
             'notnull' => true,
             ));
        $this->hasColumn('end_time', 'time', null, array(
             'type' => 'time',
             'notnull' => 'true;',
             ));

        $this->hasColumn('status', 'string', 260, array(
             'type' => 'string',
             'notnull' => 'true;',
             'length' => 260,
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
    }

     public function setUp()
    {
        parent::setUp();
        $this->hasOne('WorkSchedule', array(
             'local' => 'schedule_id',
             'foreign' => 'id'));
       
    }
}