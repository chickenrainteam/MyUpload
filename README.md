# MyUpload v1.0 beta #

MyUpload Plugin is a CakePHP Plugin use for uploading simple files to webroot folder and managing them after uploaded.

**Screenshots:**

[http://d.pr/i/L7D4](http://d.pr/i/L7D4+)

[http://d.pr/i/BDWH](http://d.pr/i/BDWH+)

[http://d.pr/i/AVfQ](http://d.pr/i/AVfQ+)

## Author - Contact ##

Author: Truc Thanh ([ChickenRainTeam](http://chickenrain.com))

Contact: ChickenRainTeam - hi@chickenrain.com

## Requirements ##

* CakePHP 2.x
* All requirements for CakePHP 2.x

## Tested on ##

* v1.0 alpha - CakePHP 2.4.1, PHP 5.4.3
* v1.0 beta - CakePHP 2.4.1, PHP 5.4.3

## Features ##

* Upload a file or multiple files with default settings (image, zip and plain text file and file size <= 2MB) to your webroot folder
* Get the direct link view/download from uploaded files
* Show the list of files after uploaded
* Delete files after uploaded
* Scan upload folder and insert files data to the database
* Using FileManager Component for uploading files from your single project.

## Instruction ##
* Unzip the plugin file and rename unzip folder to MyUpload
* Copy unzip folder to your project Plugin folder
* Load your plugin by add this line to Config/bootstrap.php: 
CakePlugin::load('MyUpload',array('routes'=>true));
* Open Cake Console, move to your project by using cd command
* Create plugin's database by using this command in Cake Console: 
cake schema create -p MyUpload 
Or import file MyUpload/Config/Schema/my_upload_db.sql if you can not use Cake Console.
* Access plugin by using the link: http://localhost/your-project/my_upload

## Component Setup ##
Beside using plugin as a integrated cakephp application, you also use the FileManager Component for configuration and uploading files in a single project.

### Controller ###
Load the component to your controller: 

	public $components = array('MyUpload.FileManager');

If you want more configurations, just add it to an array, eg.

	public $components = array(
		'MyUpload.FileManager' => array(
				'location' => WWW_ROOT, // upload to webroot folder, or use APP if you want to upload files outside webroot
				'uploadDir' => 'files', // upload folder name
				'dbColumn' => 'path', // database field name for storing file path, default is 'path'
				'mimeTypes' => array('image/png', 'image/jpg', 'image/jpeg', 'image/gif'), //default accepted mime types for uploading
				'maxSize' => '2MB', // limited file size when uploading file, default is 2MB
				'overwrite' => true // default is true, if it set to false, the same file will not be uploaded
			)
		);

### Uploading and Validating Files ###
Once you have your upload defined, you will need to add the input field in the form. Both the form and input will need the file type applied.

	echo $this->Form->create('Model', array('type' => 'file'));
	echo $this->Form->input('path', array('type' => 'file'));
	echo $this->Form->end('Upload');

And finally, just call FileManager::upload() and your file should be uploaded.

	$result = $this->FileManager->upload($this->request->data['Model']);
	if ($result['status']) {
		// Do something
	}else{
		// Upload failed
	}

FileManager::upload() method return an array with some values like this:

	array(
		'status' => true,
		'file_name' => 'the-file-name.ext',
		'path' => '/the/folder/path/file-name.ext',
		'file_type' => 'image/jpg',
		'file_size' => '23849'
	);

Like any upload form, you want to validate the file before it is actually uploaded. We can do this by using the FileManager::validate(). The FileManager Component use setings include maxSize and mimeTypes to check validation of file before uploading. All we need to do is call validate method, like so.

	$validation = $this->FileManager->validate($this->request->data['Model']);
	if($validation['status']){
		// Do upload
	}else{
		// Error
		$this->Session->setFlash($validation['error']);
	}

### Deleting File ###
You can delete file by calling FileManager::delete() method.
	
	if($this->FileManager->delete($fileupload['Model']['path'])){
		// deleted
	}else{
		// error
	}

### Scanning Upload Folder ###
When you have set up your upload folder, you can scan that folder to update old uploaded files data to your database before starting to upload new files. Just call FileManager::scan(), like so:
$files = $this->FileManager->scan();
It will return all the files data are existing in your upload folder including dirname, basename, extension, filename, mime, filesize, and path. And you can use that data for saving or updating to the database.

### Other ###
There are some useful methods that you can use for improving your project.

* Checking existent file in the upload folder, use FileManager::exists()
	
	$status = $this->FileManager->exists($filename);

* Get the absolute path of upload folder, use FileManager::getPath()
	
	$absolute_path = $this->FileManager->getPath();

* Rename the file in the upload folder, use FileManager::rename(), it will return an old file name with random number string.
	
	$filename = 'old_name.txt';
	$filename = $this->FileManager->rename($filename);
	//result: $filename = 'old_name_123456.txt';

* Upload multiple files:
If you want to upload more files in the same time, you first need to make an upload form like this:
	
	echo $this->Form->create('Model', array('type' => 'file'));
	echo $this->Form->input('Model.0.path', array('type' => 'file'));
	echo $this->Form->input('Model.1.path', array('type' => 'file'));
	echo $this->Form->input('Model.2.path', array('type' => 'file'));
	echo $this->Form->end('Upload');

Or using for loop to make inputs:
	
	echo $this->Form->create('Model', array('type' => 'file'));
	for($i = 0; $i<5; $i++){
		echo $this->Form->input('Model.'.$i.'.path', array('type' => 'file'));
	}
	echo $this->Form->end('Upload');

And then at the controller, just call FileManager::upload method in a foreach loop:
	
	foreach ($this->request->data['Model'] as $file) {
		if(!empty($file['path']['name'])){
			$result = $this->FileManager->upload($file);
		}
	}

### Notes ###
FileManager Component just help you to upload files to a destination folder. If you want to save files data to the database, you will need to use CakePHP default saving method (Model::save(, Model::saveMany() etc..) for saving the data. And you can get all the files data needed to save from the return result of FileManager::upload() method.
