<?php

abstract class BaseWorkTypeSkill extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ohrm_work_type_skill');

        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));

  
        $this->hasColumn('shift_type_id', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('skill_id', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('schedule_id', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));
    }

     public function setUp()
    {
        parent::setUp();
        $this->hasOne('WorkShiftType', array(
             'local' => 'shift_type_id',
             'foreign' => 'id'));
        $this->hasOne('Skill', array(
             'local' => 'skill_id',
             'foreign' => 'id'));
    }
}