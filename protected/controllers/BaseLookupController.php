<?php
/**
 * @copyright Copyright &copy; Brainpower Solutions.nl, 2014
*/ 
 /**
 * Base controller class for lookup values.
 * 
 * @author Barry M. van Kessel <bmvankessel@brainpowersolutions.nl>
 */
abstract class BaseLookupController extends Controller {
	public $modelname;
	public $title;
	public $captionButtonAdd;
	public $messageNoValues;
	
	private function foreignKey() {
		$model = new $this->modelname;
		return $model->tablename() . '_id';
	}
	
	/**
	 * Deny GET access to the actions that will be called with POST ajax calls.
	 */
	public function accessRules() {
		return array(
			array(
				'deny',
				'actions' => array('relatedparents'),
				'verbs'=>array('get'),
			),
		);
	}
	
	public function actionIndex() {
		$dataProvider = new CActiveDataProvider($this->modelname);
		
		$this->render('//common/lookup/index', 
			array(
				'dataProvider'=>$dataProvider,
				'title'=>$this->title,
				'captionButtonAdd'=>$this->captionButtonAdd,
				'messageNoValues'=>$this->messageNoValues,
		));
	}

	/**
	 * Returns the posted data for the request.
	 * Data is expected to be posted as a JSON encode string with key = 'data'.
	 * 
	 * @param array $dataKeys		Keys expected within the data array.
	 * @param array | null $data	JSON decoded data array.
	 * $param array					Messages with validation errors.
	 * 
	 * @return boolean				True if data and data keys exist.
	 */
	private function getPostedData(array $dataKeys, &$data, &$validationMessages) {
		$validationMessages = array();

		if (array_key_exists('data', $_POST)) {
			$data = json_encode($_POST['data'], true);
			foreach ($dataKey as $dataKey) {
				if (!array_key_exists($dataKey, $data)) {
					$validationMessages[] = "Field '$field' missing in data.";
				}
			}
		} else {
			$validationMessages[] = "Field 'data' not posted.";
		}
		
		return (count($validationMessages) == 0);
	}

	/**
	 * Returns an overview of related parent models.
	 * 
	 * @param string $_POST['data']		JSON encoded request data.
	 */
	public function actionRelatedParents() {
		$result['status'] = 'not ok';
		$result['relations'] = array();
		if ($this->getPostedData(array('id'), $data, $messages)){
			$id = $data['id'];

			/* determine number of meal models */
			$count = Maaltijd::model()->count($this->foreignKey() . '=' . $id);
			if ($count > 0) {
				$result['relations'][] = array(
					'text' => "Aantal maaltijden: $count",
					'count' => $count,
					'modelNameSingle' => 'maaltijd',
					'modelNamePlural' => 'maaltijden',
				);
			}
			
			/* determine number of meal search filters */
			$count = Maaltijdzoekfilter::model()->count($this->foreignKey() . '=' . $id);
			if ($count > 0) {
				$result['relations'][] = array(
					'text' => "Aantal maaltijdzoekfilters: $count",
					'count' => $count,
					'modelNameSingle' => 'maaltijdzoekfilter',
					'modelNamePlural' => 'maaltijdzoekfilters',
				);
			}
			$result['status'] = 'ok';
		} else {
			$result['message'] = $messages;
		}		
		
		echo json_encode($result);
	}


    /** 
     * Returns the number of meals referencing the lookup value. 
     *
     * @return string Status of the request including the number of meals.
     */
    public function _actionRelatedParents() {
		$data = (isset($_POST['data'])) ? json_decode($_POST['data'], true) : null;
		$result['status'] = 'not ok';
		$data['id'] = 7;

		if ($data !== null) {
			$id = $data['id'];
			$modelCount = Maaltijd::model()->count($this->foreignKey() . '=' . $id);
			$result['parentCount'] = $modelCount;
			$result['status'] = 'ok';
		} else {
			$result['message'] = 'No data posted';
		}	

        echo json_encode($result);
        Yii::app()->end();
    }
    
    /**
     * Checks if the post array contains the entry 'data' with the subentries specified in the 
     * argument $entries.
     * 
     * @param array $entries	Entries expected in the post entry 'data'.
     * @param array $data		Posted 'data' values if function returns true.
     * @param string $message	Message specifying missing values if function returns false. 
     * 
     * @return					If post contains the expected data.
     */
    private function getPostData(array $entries, &$data, &$message) {
		$data = null;
		$missingEntries = array();
		$message = null;
		if (isset($_POST['data'])) {
			$data = json_decode($_POST['data'], true);
			foreach ($entries as $key) {
				if (!array_key_exists($key, $data)) {
					$missingEntries[] = $key;
				}
			}
			switch (count($missingEntries)) {
				case 0:
					return true;
					
				case 1:
					$message = "Post entry 'data' is missing entry '" . implode($missingEntries) . "'";
					$data = null;
					return false;
				
				default:
					$message = "Post entry 'data' is missing entries: '" . implode("', ", $missingEntries) . "'"; 
					$data = null;
					return false;
			}
		} else {
			$message = "Post entry 'data' expected.";
			return false;
		}
	}
    
    /**
	 * Creates a lookup value;
	 *
	 * @param string $_POST['data']		JSON encoded description of the lookup value.
	 *
	 * @return string 					Status of the request including the id of the newly created 
	 * 									lookup value.
	 */
    public function actionCreate() {
		$result['status'] = 'not ok';
		$result['action'] = 'create';
		
		if ($this->getPostData(array('description'), $data, $message)) {
			$model = new $this->modelname();
			$model->omschrijving = $data['description'];
			if ($model->save()) {
				$result['status'] = 'ok';
			} else {
				$result['message'] = 'Lookup value for ' . $this->modelname . ' could not be saved.';
			}
		} else {
			$result['message'] = $message;
		}
		echo json_encode($result);
        Yii::app()->end();
	}
	
	/**
	 * Updates a lookup value.
	 *
	 * @param string $_POST['data']		JSON encoded description of the lookup value.
	 * 
	 * @return string 					Status of the request.
	 */
	 public function actionUpdate() {
		$result['status'] = 'not ok';
		$result['action'] = 'update';
		
		if ($this->getPostData(array('id','description'), $data, $message)) {
			$model = new $this->modelname();
			$model = $model->findByPk($data['id']);
			
			if ($model !== null) {
				$model->omschrijving = $data['description'];
				if ($model->save()) {
					$result['status'] = 'ok';
				} else {
					$result['message'] = 'Lookup value for ' . $this->modelname . ' could not be saved.';
				}
			} else {
				$result['message'] = $this->modelname . ' not found [id=' . $data['id'] . '].';
			}
		} else {
			$result['message'] = $message;
		}
		echo json_encode($result);
        Yii::app()->end();
	 }
    
    /***
     * Deletes a lookup value.
     * 
     */
	public function actionDelete() {
        $result['status'] = 'not ok';
        $result['action'] = 'delete';
		if ($this->getPostData(array('id'), $data, $message)) {
			$model = new $this->modelname();
			if ($model->deleteByPk($data['id']) === 1) {
				$result['status'] = 'ok';
			} else {
				$result['message'] = $this->modelname . ' not found [id=' . $data['id'] . '].';
			}
		} else {
			$result['message'] = $message;
		}
		echo json_encode($result);
        Yii::app()->end();
	}
    
    /**
     * Creates a new lookup value.
     *
     * @return string Status of the request inclusing the id of the newly create lookup value. 
     */
    public function actionAjaxCreate() {
        $result['status'] = 'not ok';
        $result['action'] = 'create';
		$attributes = (isset($_POST['attributes'])) ? json_decode($_POST['attributes'], true) : null;

        if ($attributes !== null) {
            $model = new $this->modelname('insert');
            $model->setAttributes($attributes, false);

            if ($model->save(false)) {
                $result['id'] = $model->id;
                $result['status'] = 'ok';
            } else {
                $result['message'] = 'Save failed';                
            }
        } else{
            $result['message'] = 'No attributes posted';
        }
        
        echo json_encode($result);
        Yii::app()->end();
    }

    /**
     * Updates an existing lookup value.
     * 
     * @return string Status of the request.
     */
    public function actionAjaxUpdate()
    {
        $result['status'] = 'not ok';
        $result['action'] = 'update';

		$attributes = (isset($_POST['attributes'])) ? json_decode($_POST['attributes'], true) : null;

        if ($attributes !== null) {

            $model = $this->loadModel($attributes);
            $model->omschrijving = $attributes['omschrijving'];

            if ($model->save()) {
                $result['status'] = 'ok';
            } else {
                $result['message'] = 'Save failed';
            }
        } else {
            $result['message'] = 'No attributes posted';            
        }

        echo json_encode($result);
        Yii::app()->end();        
    }

    /** 
     * Deletes a lookup value. 
     *
     * @return string Status of the request. 
     */
    public function actionDelete_() {
		
        $result['status'] = 'not ok';

        $data = (isset($_POST['data'])) ? json_decode($_POST['data'],true) : null;
        if ($data !== null) {
			$id = $data['id'];
			$foreignKey = $this->foreignKey();
			Maaltijd::model()->updateAll(array($foreignKey=>null), "$foreignKey=$id");

			$model = new $this->modelname;
			$modelsDeleted =  $model->deleteByPk($id);
        
			$modelname = strtolower($this->modelname);
			switch($modelsDeleted) {
				case 0:
					$result ['message'] = "Record $modelname [id=$id] was not deleted.";
					break;

				case 1:
					$result['status'] = 'ok';        
					break;

				default:
					$result ['message'] = "Multiple records of '$modelname' [id=$id] are deleted.";
					break;
			}
		} else {
            $result['message'] = 'No attributes posted';            		
		}

        echo json_encode($result);
        Yii::app()->end();        
    }

    /** 
     * Returns the model identified by the id.
     *  
     * @param int id Id of the lookup value
     * 
     * @return object Lookup value
     */
    public function loadModel($id)
    {
		$model = new $this->modelname;
        $model = $model()->findByPk($id);
        if($model===null) {
            throw new CHttpException(404,'The requested page does not exist.');
        }
        return $model;
    }

    /** 
     * Validates the lookup attribute values.
     *
     * @param object Lookup value.
     * 
     * */
    protected function performAjaxValidation($model)
    {
        $formname = strtolower($this->modelname) . '-form';
        if(isset($_POST['ajax']) && $_POST['ajax']===$formname)
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
