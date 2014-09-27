<?php

Yii::import('application.controllers.BaseLookupController');

class MaaltijdsubtypeController extends BaseLookupController
{
	function __construct($id, $module=null) {
		parent::__construct($id, $module);
		$this->modelname = 'Maaltijdsubtype';
		$this->title = 'Maaltijdsubtypen';
		$this->captionButtonAdd ='Nieuw Maaltijdsubtype';
		$this->messageNoValues = 'Er zijn geen maaltijdsubtypen';
	}	
}
