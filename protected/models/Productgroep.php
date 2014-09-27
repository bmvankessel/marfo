<?php

/**
 * This is the model class for table "productgroep".
 *
 * @property integer $id
 * @property string $omschrijving
 */
class Productgroep extends CCustomActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'productgroep';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('omschrijving', 'required'),
			array('omschrijving', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, omschrijving', 'safe', 'on'=>'search'),
		);
	}

    public function attributeTypes() {
        return array(
            'id' => 'int',
            'omschrijving' => 'string'
        );
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
            return array(
                'id' => 'ID',
                'omschrijving' => 'Omschrijving',
            );
	}
        
    public function attributesToDisplay() {
        return array('omschrijving');
    }

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('omschrijving',$this->omschrijving,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Maaltijdtype the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
    public function omschrijvingen() {
        $omschrijvingen = array();
        foreach($this->findAll(array('order'=>'omschrijving')) as $maaltijdtype) {
            $omschrijvingen[$maaltijdtype->id] = $maaltijdtype->omschrijving;
        }
        
        return $omschrijvingen;
    }
        
    public function omschrijvingenNaarId() {
        $omschrijvingen = array();
        foreach($this->findAll(array('order'=>'omschrijving')) as $maaltijdtype) {
            $omschrijvingen[$maaltijdtype->omschrijving] = $maaltijdtype->id;
        }
        
        return $omschrijvingen;
    }

}
