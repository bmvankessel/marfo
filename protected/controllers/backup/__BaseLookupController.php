<?php

/**
 * @copyright Copyright &copy; Barry van Kessel, BrainpowerSolutions.nl, 2014
 */
 
/**
 * Base class for controllers managing lookup tables.
 * 
 * @author Barry van Kessel <bmvankessel@brainpowersolutions.nl> 
 */
class BaseLookupController extends Controller
{
	/**
	 * @var string name of the model representing the lookup values.	 
	 */
    public $modelname;

	/*
	 * Database field name of the parent referencing the lookup value id. 
	 * 
	 * @return string 
	 */
    private function foreignKey() {
        return strtolower($this->modelname) . '_id';
    }

    public function actionIndex()
    {
//        $this->menu = Yii::app()->menuManager->getMain();
        $dataProvider=new CActiveDataProvider($this->modelname);

        return $this->render('index',array('dataProvider'=>$dataProvider));
    }
}
