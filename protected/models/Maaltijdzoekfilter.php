<?php

class Maaltijdzoekfilter extends CCustomActiveRecord {
    public function tableName()
    {
            return 'maaltijdzoekfilter';
    }

    public function attributeTypes() {
        return array(
            'id' => 'int',
            'maaltijdtype_id' => 'int',
            'maaltijdsubtype_id' => 'int',
            'tooltip' => 'string'
        );
    }
    
    public function relations() {
        return array(
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
    
    public function moveUp($id) {
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
    
    public function moveDown($id) {
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
