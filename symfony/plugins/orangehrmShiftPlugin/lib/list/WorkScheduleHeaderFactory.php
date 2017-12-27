<?php

class WorkScheduleHeaderFactory extends ohrmListConfigurationFactory {
	const LINK_NONE = 0;
    const LINK_ALL_EMPLOYEES = 1;    
    const LINK_ACTIVE_EMPLOYEES = 2;
    const LINK_DELETED_EMPLOYEES = 3;
    protected static $linkSetting = self::LINK_NONE;
	
	protected function init() {

		$header1 = new ListHeader();
		$header2 = new ListHeader();
		$header3 = new ListHeader();
		$header4 = new ListHeader();
		$header5 = new ListHeader();
                
		$header1->populateFromArray(array(
		    'name' => 'Shift Name',
		    'elementType' => 'link',
		    'elementProperty' => array(
			'labelGetter' => 'getName',
			'urlPattern' => 'javascript:'),
		));
		
		$header2->populateFromArray(array(
		    'name' => '轮班第一天',
		    'elementType' => 'label',                   
		    'elementProperty' => array('getter' => 'getShiftDate'),
		));
		$header3->populateFromArray(array(
		    'name' => '状态',
		    'elementType' => 'label',                   
		    'elementProperty' => array('getter' => 'getStatus'),
		));
		$header4->populateFromArray(array(
		    'name' => '创建时间',
		    'elementType' => 'label',                   
		    'elementProperty' => array('getter' => 'getCreateAt'),
		));
		$header5->populateFromArray(array(
            'name' => '查看排班',
            'width' => '5%',
            'isSortable' => true,
            'sortField' => 'employeeId',
            'elementType' => 'link',
            'textAlignmentStyle' => 'left',
            'elementProperty' => array(
                'labelGetter' => array('getFullLastName'),
                'placeholderGetters' => array('id' => 'getEmpNumber'),
                'linkable' => $this->getLinkable(),
                'urlPattern' => public_path('index.php/shift/createXML/schedule_id/{id}'),
            ),
        ));
                
		$this->headers = array($header1,$header2,$header3,$header4,$header5);
	}

	public function getClassName() {
		return 'WorkShift';
	}

    
    public static function getLinkSetting() {
        return self::$linkSetting;
    }
    
    public static function setLinkSetting($setting) {
        self::$linkSetting = $setting;
    }
	public function getLinkable() {
        $linkable = false;
        
        if (self::$linkSetting == self::LINK_ALL_EMPLOYEES) {
            $linkable = true;            
        }
        
        return $linkable;
    }
}

?>
