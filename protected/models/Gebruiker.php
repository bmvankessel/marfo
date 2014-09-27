<?php

class Gebruiker extends CCustomActiveRecord{
	public $gebruikersnaam;
	public $wachtwoord;
        public $voornaam;
        public $tussenvoegsel;
        public $achternaam;
        public $voorletters;
        public $rol;

        public function primaryKey() {
            return 'id';
        }

        public function tableName()
	{
            return 'gebruiker';
	}

        public function rules()
	{
            return array(
                array('gebruikersnaam, achternaam', 'required', 'message'=>'{attribute} mag niet leeg zijn!'),
                array('wachtwoord', 'required', 'on'=>'new', 'message'=>'{attribute} mag niet leeg zijn!'),
                array('id, voorletters, salt, hash, voornaam, tussenvoegsel, rol', 'safe'),
            );
        }

        public function attributeTypes() {
            return array(
                'id' => 'int',
                'gebruikersnaam' => 'string',
                'wachtwoord'=>'string',
                'voornaam'=>'string',
                'tussenvoegsel'=>'string',
                'achternaam'=>'string',
                'voorletters'=>'string',
            );
        }

        public function attributeLabels()
	{
            return array(
                'rol'=>'Rol',
                'gebruikersnaam'=>'Gebruikersnaam',
                'wachtwoord'=>'Wachtwoord',
                'voornaam'=>'Voornaam',
                'tussenvoegsel'=>'Tussenvoegsel',
                'achternaam'=>'Achternaam',
                'voorletters'=>'Voorletters',
            );
	}

        public function activeFormFields() {
            return array(
                'voorletters', 'voornaam', 'tussenvoegsel',
                'achternaam', 'gebruikersnaam', 'wachtwoord');
        }

        public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
     }
?>
