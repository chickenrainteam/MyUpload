<?php
App::uses('AppModel', 'Model');
/**
 * Fileupload Model
 *
 */
class Fileupload extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'path' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
		'file_type' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
		'file_size' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
	);
}
