# MyUpload v1.0 beta #

MyUpload Plugin is a CakePHP Plugin uses for uploading simple files to webroot folder and managing them after uploaded.

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

* Upload a file or multiple files with default settings (image, zip and plain text file with file size <= 2MB) to your webroot folder
* Get the direct link view/download from uploaded files
* Show the list of files after uploaded
* Delete files after uploaded
* Scan upload folder and insert files data to the database
* Using FileManager Component for uploading files from your single project.

## Instruction ##
### Installation ###
* Download the plugin, unzip and rename the result folder to MyUpload
* Copy unzip folder to your project Plugin folder
* Load your plugin by add this line to Config/bootstrap.php: 

```php
	CakePlugin::load('MyUpload', array('bootstrap' => true, 'routes' => true));
```
* Open Cake Console, go to your project by using cd command
* Create plugin's database by using this command in Cake Console: 

```console
	cake schema create -p MyUpload 
```
Or import file MyUpload/Config/Schema/my_upload_db.sql if you can not use Cake Console.

* Access plugin by using the link: 

```console 
	http://localhost/your-project/my_upload
```
### Plugin Options ###
Once you have finished plugin installation. The plugin will work well by default, you also can change some settings in MyUpload/Config/bootstrap.php to fit your requirements.
For Example:
* By default, the upload folder is placed inside webroot folder and named files, if you want to change different folder for uploading files into. Just change this line:

```php
	Configure::write('Settings.uploadDir', 'files');
```
to:

```php
	Configure::write('Settings.uploadDir', 'place-your-upload-folder-name-here');
```
* If you want to place upload folder outside the webroot folder, change this line:

```php
	Configure::write('Settings.location', WWW_ROOT);
```
to 

```php
	Configure::write('Settings.location', APP);
```
MyUpload plugin will upload files to the destination that you set up above.
See more settings in MyUpload/Config/bootstrap.php

## Component Setup ##
Beside using plugin as a integrated cakephp application, you also use the FileManager Component for uploading files in a single project.

### Controller ###
Load the component to your controller: 
```php
	public $components = array('MyUpload.FileManager');
```
If you want more settings, just add it to an array, eg.
```php
	public $components = array(
		'MyUpload.FileManager' => array(
				'location' => WWW_ROOT, // default is WWW_ROOT, upload to webroot folder, or use APP if you want to upload files outside webroot
				'uploadDir' => 'files', // upload folder name, default is files
				'dbColumn' => 'path', // database field name for storing file path, default is 'path'
				'mimeTypes' => array('image/png', 'image/jpg', 'image/jpeg', 'image/gif'), //default accepted mime types for uploading
				'maxSize' => '2MB', // limited file size when uploading file, default is 2MB
				'overwrite' => true // default is true, if it set to false, the same file will not be uploaded
			)
		);
```
### Uploading and Validating Files ###
Once you have done your upload settings, you will need to add the input field in the form. Both the form and input will need the file type applied.
```php
	echo $this->Form->create('Model', array('type' => 'file'));
	echo $this->Form->input('path', array('type' => 'file'));
	echo $this->Form->end('Upload');
```
And finally, call FileManager::upload() and your file should be uploaded.
```php
	$result = $this->FileManager->upload($this->request->data['Model']);
	if ($result['status']) {
		// Do something
	}else{
		// Upload failed
	}
```
If your want rename your file before uploading, just add value of the new file name to the second variable, like this:
```php
	$result = $this->FileManager->upload($this->request->data['Model'], 'the-new-file-name');
```
FileManager::upload() method return an array with some values like this:
```php
	array(
		'status' => true,
		'file_name' => 'the-file-name.ext',
		'path' => '/the/folder/path/file-name.ext',
		'file_type' => 'image/jpg',
		'file_size' => '23849'
	);
```
Like any upload form, you want to validate the file before it is actually uploaded. We can do this by using the FileManager::validate(). The FileManager Component use setings include maxSize and mimeTypes to check validation of file before uploading. All we need to do is call validate method, like so.
```php
	$validation = $this->FileManager->validate($this->request->data['Model']);
	if($validation['status']){
		// Do upload
	}else{
		// Error
		$this->Session->setFlash($validation['error']);
	}
```
### Deleting File ###
You can delete file by calling FileManager::delete() method.
```php	
	if($this->FileManager->delete($fileupload['Model']['path'])){
		// deleted
	}else{
		// error
	}
```
### Scanning Upload Folder ###
When you have set up your upload folder, you can scan that folder to update old uploaded files data to your database before starting to upload new files. Just call FileManager::scan(), like so:
```php
	$files = $this->FileManager->scan();
```
It will return all the files data are existing in your upload folder including dirname, basename, extension, filename, mime, filesize, and path. And you can use that data for saving or updating to the database.

### Other ###
There are some useful methods that you can use for improving your project.

* Checking existent file in the upload folder, use FileManager::exists()

```php
	$status = $this->FileManager->exists($filename);
```
* Get the absolute path of upload folder, use FileManager::getPath()

```php	
	$absolute_path = $this->FileManager->getPath();
```
* Rename the file in the upload folder, use FileManager::rename()

```php	
	$filename = 'old_name.jpg';
	$filename = $this->FileManager->rename($filename, 'new_name');
	//result: $filename = 'new_name.jpg';
```
If the second variable is null, it will return an old file name with random number string:

```php	
	$filename = 'old_name.txt';
	$filename = $this->FileManager->rename($filename);
	//result: $filename = 'old_name_123456.txt';
```
* Upload multiple files:
If you want to upload more files in the same time, you first need to make an upload form like this:

```php	
	echo $this->Form->create('Model', array('type' => 'file'));
	echo $this->Form->input('Model.0.path', array('type' => 'file'));
	echo $this->Form->input('Model.1.path', array('type' => 'file'));
	echo $this->Form->input('Model.2.path', array('type' => 'file'));
	echo $this->Form->end('Upload');
```
Or using for loop to make inputs:
```php	
	echo $this->Form->create('Model', array('type' => 'file'));
	for($i = 0; $i<5; $i++){
		echo $this->Form->input('Model.'.$i.'.path', array('type' => 'file'));
	}
	echo $this->Form->end('Upload');
```
And then at the controller, just call FileManager::upload method in a foreach loop:
```php	
	foreach ($this->request->data['Model'] as $file) {
		if(!empty($file['path']['name'])){
			$result = $this->FileManager->upload($file);
		}
	}
```
### Notes ###
FileManager Component only helps you upload files to the folder destination. If you want to save files data to the database, you will need to use CakePHP default saving methods (Model::save(), Model::saveMany() etc..) for saving the data. And you can get all the files data needed to save from the return result of FileManager::upload() method.
