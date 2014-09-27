<?php

Yii::import('application.controllers.BaseLookupController');

class MaaltijdtypeController extends BaseLookupController {
	function __construct($id, $module=null) {
		parent::__construct($id, $module);
		$this->modelname = 'Maaltijdtype';
		$this->title = 'Maaltijdtypen';
		$this->captionButtonAdd ='Nieuw Maaltijdtype';
		$this->messageNoValues = 'Er zijn geen maaltijdtypen';
	}	
}
