<?php

class Maaltijdzoekfilter extends CCustomActiveRecord {
	public $maxSequence;
	
    public function tableName()
    {
            return 'maaltijdzoekfilter';
    }

    public function attributeTypes() {
        return array(
            'id' => 'int',
            'productgroep_id' => 'int',
            'maaltijdtype_id' => 'int',
            'maaltijdsubtype_id' => 'int',
            'tooltip' => 'string'
        );
    }
    
    public function relations() {
        return array(
			'productgroep'=>array(self::BELONGS_TO, 'Productgroep', 'productgroep_id'),
            'maaltijdtype'=>array(self::BELONGS_TO, 'Maaltijdtype', 'maaltijdtype_id'),
            'maaltijdsubtype'=>array(self::BELONGS_TO, 'Maaltijdsubtype', 'maaltijdsubtype_id'),
            );
    }

    public function search() {
        return new CActiveDataProvider(
                'Maaltijdzoekfilter', 
                array(
                    'criteria'=>array(
                        'order'=>'sequence'
                    ),
                    'pagination'=>array(
                        'pageSize'=>'100'
                    ),
                )
        );
    }

    public function All() {
        return $this->findAll(array('order'=>'sequence'));
    }

	/**
	 * Moves the zoekfilter one place up (= decreasing the sequence) within the zoekfilters.
	 * 
	 * @param integer $id	Id identifying the zoekfilter.
	 */
    public function moveUp($id) {
		$zoekfilters = $this->findAll(array(
			'order'=>'sequence desc', 
		));

		/* add the sequence numbers to be assigned to an array stack */
		$sequenceNumbers = array();
		for ($i=1;$i<=count($zoekfilters);$i++) {
			array_push($sequenceNumbers, $i);
		}

		/* loop through all zoekfilters and change sequence */
		foreach ($zoekfilters as $zoekfilter) {
			/* get next available sequence number */
			$currentSequenceNumber = array_pop($sequenceNumbers);
			if ($id == $zoekfilter->id && $currentSequenceNumber != 1) {
				/* the next sequence number from the stack is needed and the current one must be put 
				 * back on the stack */
				$tmp = array_pop($sequenceNumbers);
				array_push($sequenceNumbers, $currentSequenceNumber);
				$currentSequenceNumber = $tmp;
			}
			if ($zoekfilter->sequence != $currentSequenceNumber) {
				$zoekfilter->sequence = $currentSequenceNumber;
				$zoekfilter->save();
			}
		}
	}
	
	/**
	 * Moves the zoekfilter one place down (= incresing the sequence) within the zoekfilters.
	 * 
	 * @param integer $id	Id identifying the zoekfilter.
	 */
    public function moveDown($id) {
		$zoekfilters = $this->findAll(array(
			'order'=>'sequence', 
		));

		/* add the sequence numbers to be assigned to an array stack */
		$lastSequenceNumber = count($zoekfilters);
		$sequenceNumbers = array();
		for ($i=$lastSequenceNumber;$i>0;$i--) {
			array_push($sequenceNumbers, $i);
		}
				
		/* loop through all zoekfilters and change sequence */
		foreach ($zoekfilters as $zoekfilter) {
			/* get next available sequence number */
			$currentSequenceNumber = array_pop($sequenceNumbers);
			if ($id == $zoekfilter->id && $currentSequenceNumber !=$lastSequenceNumber) {
				/* the next sequence number from the stack is needed and the current one must be put 
				 * back on the stack */
				$tmp = array_pop($sequenceNumbers);
				array_push($sequenceNumbers, $currentSequenceNumber);
				$currentSequenceNumber = $tmp;
			}
			if ($zoekfilter->sequence != $currentSequenceNumber) {
				$zoekfilter->sequence = $currentSequenceNumber;
				$zoekfilter->save();
			}
		}
	}	
    
    public function _moveUp($id) {
        $previousZoekfilter = null;
        $zoekfilters = $this->All();
        
        $sequence = 0;
        foreach($zoekfilters as $zoekfilter) {
            $sequence++;
            if ($zoekfilter->id == $id) {
                // zoekfilter found
                if ($previousZoekfilter !== null) {
                    // set the previous zoekfilter to the current sequence number
                    $previousZoekfilter->sequence = $sequence;
                    $previousZoekfilter->save();
                    
                    // set the target zoekfilgter to the previous sequence number
                    $zoekfilter->sequence = $sequence - 1;
                    $zoekfilter->save();                            
                    
                } else {
                    // this is the first zoekfilter
                    // sequence should be 1 but to lazy to check
                    $zoekfilter->sequence = $sequence;
                    $zoekfilter->save();                            
                }

            } else {
                // check the sequence number
                if ($zoekfilter->sequence != $sequence) {
                    // repair if neccessary
                    $zoekfilter->sequence = $sequence;
                    $zoekfilter->save();
                }
            }
            $previousZoekfilter = $zoekfilter;
        }    
    }
    
    public function _moveDown($id) {
        $zoekfilters = $this->All();
        $oekfilterToMove = null;
        
        $sequence = 0;
        foreach($zoekfilters as $zoekfilter) {
            if ($zoekfilter->id == $id) {
                // zoekfilter found, store for later update
                $zoekfilterToMove = $zoekfilter;
            } else {
                $sequence++;
                // check the sequence number
                if ($zoekfilter->sequence != $sequence) {
                    // repair if neccessary
                    $zoekfilter->sequence = $sequence;
                    $zoekfilter->save();
                }
            }
        }    
        
        if ($zoekfilterToMove === null) {
            throw new CException("Zoekfilter to move not found (id=$id)");
        } else {
            $sequence++;
            $zoekfilterToMove->sequence = $sequence;
            $zoekfilterToMove->save();            
        }
    }
    
    public static function model($className=__CLASS__)
    {
            return parent::model($className);
    }
}
