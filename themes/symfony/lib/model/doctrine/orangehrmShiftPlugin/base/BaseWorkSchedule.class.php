<?php


abstract class BaseWorkSchedule extends sfDoctrineRecord
{
    
    public function setTableDefinition()
    {
        $this->setTableName('ohrm_work_schedule');
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

        $this->hasColumn('shift_date as shiftDate', 'date', 25, array(
             'type' => 'date',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 25,
             ));
        $this->hasColumn('copy_type', 'string', 260, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 260,
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
}