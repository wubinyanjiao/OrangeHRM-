
<?php


abstract class BaseWorkShiftRotary extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ohrm_work_shift_rotary');
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

        $this->hasColumn('date_from as dateFrom', 'date', 25, array(
             'type' => 'date',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 25,
             ));
        $this->hasColumn('date_to as dateTo', 'date', 25, array(
             'type' => 'date',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 25,
             ));
        $this->hasColumn('first_department_id as firstDepartment', 'integer', 6, array(
             'type' => 'integer',
             'length' => 6,
             ));
        $this->hasColumn('second_department_id as secondDepartment', 'integer', 6, array(
             'type' => 'integer',
             'length' => 6,
             ));
        $this->hasColumn('third_department_id as thirdDepartment', 'integer', 6, array(
             'type' => 'integer',
             'length' => 6,
             ));
    }



    
}