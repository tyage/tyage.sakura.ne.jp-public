<?php
class Profile extends AppModel{
	var $belongsTo = array('User','UserJob');

	var $virtualFields = array(
		'bmi' => 'CAST((Profile.weight / (Profile.height/100) / (Profile.height/100)) AS SIGNED)',
		'maxEnergy' => 'CAST((Profile.arm+Profile.leg+Profile.quick+Profile.soft)/4 AS SIGNED)',
		'maxSpirit' => 'CAST((Profile.language+Profile.math+Profile.science+Profile.society)/4 AS SIGNED)'
	);
	
	var $validate = array(
		'sex' => 'boolean',
		'email' => array(
			'rule' => 'email',
			'allowEmpty' => true
		),
		'born' => 'date'
	);
}