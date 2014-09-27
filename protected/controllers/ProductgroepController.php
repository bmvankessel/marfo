<?php
/**
 * @copyright Copyright @copy; Brainpower Solutions.nl, 2014
*/

Yii::import('application.controllers.BaseLookupController');

class ProductgroepController extends BaseLookupController {
	function __construct($id, $module=null) {
		parent::__construct($id, $module);
		$this->modelname = 'Productgroep';
		$this->title = 'Productgroepen';
		$this->captionButtonAdd ='Nieuwe Productgroep';
		$this->messageNoValues = 'Er zijn geen productgroepen';
	}	
}
