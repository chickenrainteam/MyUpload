<?php
/**
*
* FileManagerComponent
* Uploading and managing files after uploaded
* @author Truc Thanh - ChickenRainTeam <hi@chickenrain.com>
* @copyright	Copyright 2013, Truc Thanh - ChickenRainTeam - http://chickenrain.com
* @link		http://github.com/chickenrainteam/MyUpload
*/

App::uses('Folder','Utility');
App::uses('File','Utility');
App::uses('CakeNumber', 'Utility');
class FileManagerComponent extends Component{
	
/**
 * Default setting
 */
	public $settings = array(
		'location' => WWW_ROOT,
		'uploadDir' => 'files',
		'dbColumn' => 'path',
		'mimeTypes' => array('image/png', 'image/jpg', 'image/jpeg', 'image/gif'),
		'maxSize' => '2MB',
		'overwrite' => true
	);

	public function __construct(ComponentCollection $collection, $settings = array()) {
		$settings = array_merge($this->settings, (array)$settings);
		$this->Controller = $collection->getController();
		parent::__construct($collection, $settings);
	}

	private function __fileConstruct($location = null ,$file_name = null){
		if(substr($location, -1) == '/' || substr($location, -1) == '\\'){
			$file = new File($location . $file_name);	
		}else{
			$file = new File($location . '/' .$file_name);	
		}
		return $file;
	}
	
/**
 * Get upload folder path
 * @return folder path
 */
	public function getPath(){
		return $this->settings['location'] . $this->settings['uploadDir'];
	}
	
/**
 * Valiadate data for uploading
 * Using mimeTypes and maxSize settings
 * @param $data = array()
 * @return $validation = array('status','error');
 */	
	public function validate($data = array()){
		$validation = array(
			'status' => false,
			'error' => null
			);

		if(in_array($data[$this->settings['dbColumn']]['type'], $this->settings['mimeTypes'])){
			$validation['status'] = true;
			if($data[$this->settings['dbColumn']]['size'] > CakeNumber::fromReadableSize($this->settings['maxSize'])){
				$validation['status'] = false;
				$validation['error'] = "Invalid file size.";
			}
		}else{
			$validation['error'] = "Invalid file type.";
		}
		return $validation;
	}

/**
 * Checking file exist when upload
 * @param string $file_name
 * @return boolean
 */	
	public function exists($file_name = null){
		$file = $this->__fileConstruct($this->getPath(),$file_name);
		if($file->exists()){
			return true;
		}
		return false;
	}

/**
 * Rename file to a new name when upload file
 * if $new_name is null, it will return a new name with random number string
 * @param string $file_name, $new_name
 * @return $file_name
 */	
	public function rename($file_name = null, $new_name = null){
		$file = $this->__fileConstruct($this->getPath(), $file_name);
		if(is_null($new_name)){
			$file_name = $file->name().'_'.rand(0,999999).'.'.$file->ext();
		}else{
			$file_name = $new_name.'.'.$file->ext();
		}
		return $file_name;
	}

/**
 * Upload file
 * Rename file when upload if overwrite = true
 * @param $data = array()
 * @return $result = array('status','file_name','path','file_type','file_size');
 */	
	public function upload($data){
		$folder = new Folder();
		if($folder->create($this->getPath())){
			$dbColumn = $this->settings['dbColumn'];
			$file = new File($data[$dbColumn]['tmp_name']);
			$file_name = $data[$dbColumn]['name'];
			$file_type = $data[$dbColumn]['type'];
			$file_size = $data[$dbColumn]['size'];
			$result = array(
					'status' => false,
					'file_name' => null,
					'path' => null,
					'file_type' => null,
					'file_size' => null
					);
			if($this->exists($file_name)){
				if($this->settings['overwrite']){
					$file_name = $this->rename($file_name);
				}
			}
			if($file->copy($this->getPath() . '/' .$file_name, false)){
				$result = array(
					'status' => true,
					'file_name' => $file_name,
					'path' => '/' . $this->settings['uploadDir'] . '/' . $file_name,
					'file_type' => $file_type,
					'file_size' => $file_size
					);
			}else{
				$result = array(
					'status' => false,
					'file_name' => $file_name,
					'path' => '/' . $this->settings['uploadDir'] . '/' . $file_name,
					'file_type' => $file_type,
					'file_size' => $file_size
					);
			}
			
		}
		return $result;
	}

/**
 * Delete file after upload
 * @param string $path
 * @return boolean
 */	
	public function delete($path = null){
		$file = $this->__fileConstruct($this->settings['location'], $path);
		if($file->delete()){
			return true;
		}
		return false;
	}

/**
 * Scan upload folder for storing upload data in the database
 * @return $data = array('dirname','basename','extension','filename','mime','filesize','path');
 */	
	public function scan(){
		$folder = new Folder();
		$files = $folder->tree($this->getPath(), false,'file');
		
		foreach ($files as $file) {
			$path = '/'.substr($file, strlen($this->settings['location']) -strlen($file));
			$path = str_replace("\\", "/", $path);

			$file = new File($file);
			$file_info = $file->info();
			$file_info['path'] = $path;			
			$data[] = $file_info;
		}
		return $data;
	}
} 

?>
