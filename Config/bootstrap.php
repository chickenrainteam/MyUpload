<?php 
/* -------------------------------------------------------------------
 * The settings below have to be loaded to make the MyUpload plugin work.
 * -------------------------------------------------------------------
 *
 * See how it works in the README file
 */

/**
 * Absolute path to webroot folder, use APP if you want to upload files outside webroot folder
 */
Configure::write('Settings.location', WWW_ROOT);

/*
 * Name of upload folder
 * Default is files
 */
Configure::write('Settings.uploadDir', 'files');

/**
 * Default accepted mime types for uploading files
 * Remove or add more mime type to upload more file types
 */
Configure::write('Settings.mimeTypes', array('image/png', 'image/jpg', 'image/jpeg', 'image/gif','text/plain','application/zip'));

/**
 * Limited file size when uploading file, default is 2MB
 * This setting may be depend on your server setup
 */
Configure::write('Settings.maxSize', '2MB');

/**
 * Overwriting files set up
 * Default is true, if it set to false, the same file will not be uploaded
 */
Configure::write('Settings.overwrite', true);

 ?>
 