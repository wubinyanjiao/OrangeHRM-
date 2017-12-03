<?php

class WorkScheduleHeaderFactory extends ohrmListConfigurationFactory {
	
	protected function init() {

		$header1 = new ListHeader();
		$header2 = new ListHeader();
		$header3 = new ListHeader();
		$header4 = new ListHeader();
                
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
                
		$this->headers = array($header1,$header2,$header3,$header4);
	}

	public function getClassName() {
		return 'WorkShift';
	}
}

?>
