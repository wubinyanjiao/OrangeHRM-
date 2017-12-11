WorkRotaryEmplayee

<?php


abstract class BaseWorkRotaryEmplayee extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ohrm_work_rotary_employee');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('rotary_id', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('emp_number', 'integer', 7, array(
             'type' => 'integer',
             'length' => 7,
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
       
        $this->hasColumn('orange_department_id as orangeDepartment', 'integer', 6, array(
             'type' => 'integer',
             'length' => 6,
             ));
        $this->hasColumn('rotary_department_id as totaryDepartment', 'integer', 6, array(
             'type' => 'integer',
             'length' => 6,
             ));
    }



    
}