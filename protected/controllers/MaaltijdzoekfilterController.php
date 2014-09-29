<?php
/**
 * @copyright Copyright &copy; Brainpower Solutions.nl, 2014
 */

/**
 * Manages the requests for viewing, creating, updating and deleting maaltijdfilters.
 *
 * @author Barry M. van Kessel <bmvankessel@brainpowersolutions.nl>
 */
class MaaltijdzoekfilterController extends Controller {
	
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
    public function getPostData(array $entries, &$data, &$message) {
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

    public function actionIndex() {
        $this->render('index', array('dataProvider'=>  Maaltijdzoekfilter::model()->search()));
    }

    public function actionUp($id) {
        Maaltijdzoekfilter::model()->moveUp($id);
        $this->render('index', array('dataProvider'=>  Maaltijdzoekfilter::model()->search()));
    }

    public function actionDelete($id) {
        Maaltijdzoekfilter::model()->deleteByPk($id);
        $this->render('index', array('dataProvider'=>  Maaltijdzoekfilter::model()->search()));
    }

    public function actionDown($id) {
        Maaltijdzoekfilter::model()->moveDown($id);
        $this->render('index', array('dataProvider'=>  Maaltijdzoekfilter::model()->search()));
    }

    /**
	 * Creates a maaltijdfilter;
	 *
	 * @param string $_POST['data']		JSON encoded maaltijdfilter data.
	 * 									- productgroep-id
	 * 									- maaltijdtype-id
	 * 									- maaltijdsubtype-id
	 * 									- tooltip
	 *
	 * @return string 					Status of the create request including the id of the newly 
	 * 									created maaltijdfilter.
	 */
    public function actionCreate() {
		$result['status'] = 'not ok';
		$result['action'] = 'create';
		
		if ($this->getPostData(array('productgroep-id', 'maaltijdtype-id', 'maaltijdsubtype-id', 'tooltip'), $data, $message)) {
			$model = new Maaltijdzoekfilter();
			$model->productgroep_id = $data['productgroep-id'];
			$model->maaltijdtype_id = $data['maaltijdtype-id'];
			$model->maaltijdsubtype_id = $data['maaltijdsubtype-id'];
			$model->tooltip = $data['tooltip'];
			if ($model->save()) {
				$result['status'] = 'ok';
			} else {
				$result['message'] = 'Maaltijdfilter could not be saved.';
			}
		} else {
			$result['message'] = $message;
		}
		echo json_encode($result);
        Yii::app()->end();
	}

    /**
	 * Updates specified maaltijdfilter;
	 *
	 * @param string $_POST['data']		JSON encoded maaltijdfilter data:
	 * 									- id
	 * 									- productgroep-id
	 * 									- maaltijdtype-id
	 * 									- maaltijdsubtype-id
	 * 									- tooltip
	 *
	 * @return string 					Status of the update request.
	 */
    public function actionUpdate() {
		$result['status'] = 'not ok';
		$result['action'] = 'update';
		
		if ($this->getPostData(
			array('id','productgroep-id', 'maaltijdtype-id', 'maaltijdsubtype-id', 'tooltip'), 
			$data, $message)) 
		{
			$model = Maaltijdzoekfilter::model()->findByPk($data['id']);			
			if ($model !== null) {
				$model->productgroep_id = $data['productgroep-id'];
				$model->maaltijdtype_id = $data['maaltijdtype-id'];
				$model->maaltijdsubtype_id = $data['maaltijdsubtype-id'];
				$model->tooltip = $data['tooltip'];
				if ($model->save()) {
					$result['status'] = 'ok';
				} else {
					$result['message'] = 'Maaltijdfilter could not be saved.';
				}
			} else {
				$result['message'] = 'Maaltijdfilter could not be found [id=' . $data['id'] . ']';
			}
		} else {
			$result['message'] = $message;
		}
		echo json_encode($result);
        Yii::app()->end();
	}

    public function _actionUpdate($id=0) {
        if (Yii::app()->request->isPostRequest) {
            // update maaltijdzoekfilter
            
            $attributes = $_POST['Maaltijdzoekfilter'];
            
            if ($attributes['id'] == 0) {
                $zoekfilter = new Maaltijdzoekfilter();
                // remove id from attributes
                
                // determine sequence
                // will be placed as last item with the same maaltijdtype_id
                // or as last item if new (maaltijdtype_id does not exist)
                $sequence = 0;
                foreach (Maaltijdzoekfilter::model()->All() as $currentZoekfilter) {
                    if ($currentZoekfilter->sequence != $sequence) {
                        $currentZoekfilter->sequence = $sequence;
                        $currentZoekfilter->save();
                    }                    
                }

                $sequence++;
                $zoekfilter->sequence = $sequence;

            } else {
                $zoekfilter = Maaltijdzoekfilter::model()->findByPk($attributes['id']);
            }
            unset($attributes['id']);
            
            // set other attributes
            $zoekfilter->setAttributes($attributes,false);
            if (!$zoekfilter->save()) {
                throw new CException('Error saving maaltijdzoekfilter, data:' . json_encode($_attributes));
            }
            $this->render('index', array('dataProvider'=>  Maaltijdzoekfilter::model()->search()));
            
        } else {
            if ($id==0) {
                $zoekfilter = new Maaltijdzoekfilter();
                $zoekfilter->id = $id;
            } else {
                $zoekfilter = Maaltijdzoekfilter::model()->findByPk($id);
            }
            $maaltijdtypeDescriptions = Maaltijdtype::model()->omschrijvingen();
            $maaltijdsubtypeDescriptions = Maaltijdsubtype::model()->omschrijvingen();

            $this->render(
                    'update', 
                    array(
                        'zoekfilter'=>$zoekfilter, 
                        'maaltijdtypeDescriptions'=>$maaltijdtypeDescriptions,
                        'maaltijdsubtypeDescriptions'=>$maaltijdsubtypeDescriptions
                    )
            );            
        }
    }
}
