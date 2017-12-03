<?php

abstract class BaseWorkShiftContranct extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ohrm_work_shift_contract');

        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('contranct_type_id', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));

        $this->hasColumn('value', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('shift_id', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('apply_to', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('status', 'string', 260, array(
             'type' => 'string',
             'notnull' => 'true;',
             'length' => 260,
             ));
    }

     public function setUp()
    {
        parent::setUp();
        
        $this->hasOne('WorkShiftNew', array(
             'local' => 'shift_id',
             'foreign' => 'id'));

        $this->hasOne('WorkContranctType', array(
             'local' => 'contranct_type_id',
             'foreign' => 'id'));
    }
}