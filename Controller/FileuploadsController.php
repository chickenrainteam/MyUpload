<?php
App::uses('MyUploadAppController', 'MyUpload.Controller');

/**
*
* FileuploadsController
* 
* @author Truc Thanh - ChickenRainTeam <hi@chickenrain.com>
* @copyright	Copyright 2013, Truc Thanh - ChickenRainTeam - http://chickenrain.com
* @link		http://github.com/chickenrainteam/MyUpload
*/
class FileuploadsController extends MyUploadAppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array( 'Paginator', 'MyUpload.FileManager');

/**
 * Set up beforeFilter()
 */
	public function beforeFilter(){
		$settings = Configure::read('Settings');		
	    $this->FileManager->settings = array_merge($this->FileManager->settings, $settings);
	    parent :: beforeFilter();
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Fileupload->recursive = 0;
		$this->set('fileuploads', $this->Paginator->paginate());
	}

/**
 * add method
 * upload a file in the webroot folder
 * default is webroot/files
 * @return void
 */
	public function add(){
		if($this->request->is('post')){
			$validation = $this->FileManager->validate($this->request->data['Fileupload']);
			if($validation['status']){
				$result = $this->FileManager->upload($this->request->data['Fileupload']);

				if($result['status']){
					$data = array(
						'path' => $result['path'],
						'file_type' => $result['file_type'],
						'file_size' => $result['file_size']
						);
					$this->Fileupload->create();
					if ($this->Fileupload->save($data)) {
						$this->Session->setFlash(__('File has been uploaded successfully.'));
						$this->redirect(array('action' => 'index'));
					} else {
						$this->Session->setFlash(__('File data could not be saved. Please, try again!'));
					}
				}else{
					$this->Session->setFlash(__('File could not be uploaded.'));
				}
			}else{
				$this->Session->setFlash(__($validation['error']));
			}
		}
	}

/**
 * multi_upload method
 * upload multiple files in the webroot folder
 * default is webroot/files
 * @return void
 */	
	public function multi_upload(){
		if($this->request->is('post')){
			//pr($this->request->data['Fileupload']);
			foreach ($this->request->data['Fileupload'] as $file) {
				if(!empty($file['path']['name'])){
					$validation = $this->FileManager->validate($file);
					if($validation['status']){
						$result = $this->FileManager->upload($file);
						if($result['status']){
							$files[] = array(
								'path' => $result['path'],
								'file_type' => $result['file_type'],
								'file_size' => $result['file_size']
							);
						}
					}
				}
			}
			if(!empty($files)){
				if ($this->Fileupload->saveMany($files)) {
					$this->Session->setFlash(__('File has been uploaded successfully.'));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('File could not be saved. Please, try again.'));
				}
			}else{
				$this->Session->setFlash(__('File could not be uploaded. Please, try again.'));
			}
		}
	}

/**
 * delete method
 * delete files in the webroot folder
 * default is webroot/files
 * @return void
 */
	public function delete($id){
		$this->Fileupload->id = $id;
		if (!$this->Fileupload->exists()) {
			throw new NotFoundException(__('File was not found!'));
		}
		$this->request->onlyAllow('post', 'delete');
		$fileupload = $this->Fileupload->findById($id);
		
		if($this->FileManager->delete($fileupload['Fileupload']['path'])){
			if ($this->Fileupload->delete()) {
				$this->Session->setFlash(__('The file has been deleted successfully.'));
				$this->redirect(array('action' => 'index'));
			}else{
				$this->Session->setFlash(__('The file could not be deleted. Please, try again.'));
			}
		}else{
			$this->Session->setFlash(__('The file could not be deleted. Please, try again.'));	
		}
		$this->redirect(array('action' => 'index'));
	}

/**
 * scanfiles method
 * Scan files in the webroot folder
 * default is webroot/files
 * @return void
 */
	public function scanfiles(){
		if($this->request->is('post')){
			$this->Fileupload->query("TRUNCATE TABLE fileuploads");
			$files = $this->FileManager->scan();

			foreach ($files as $file) {
				$this->Fileupload->create();
				$data = array(
					'path' => $file['path'],
					'file_type' => $file['mime'],
					'file_size' => $file['filesize']
					);
				if(!$this->Fileupload->save($data)){
					$this->Session->setFlash(__('Oops! Scanning process has been interrupted!'));	
					break;
				}
			}
			$this->Session->setFlash(__('Done.'));	
			$this->redirect(array('action' => 'index'));
		}
	}

}
