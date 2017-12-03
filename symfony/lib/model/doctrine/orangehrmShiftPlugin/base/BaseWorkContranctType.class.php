<?php

abstract class BaseWorkContranctType extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ohrm_work_shift_contract_type');

        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));


        $this->hasColumn('schedule_id', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));


        $this->hasColumn('name', 'string', 260, array(
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

     public function setUp()
    {
        parent::setUp();
        $this->hasOne('WorkSchedule', array(
             'local' => 'schedule_id',
             'foreign' => 'id'));
    }
}