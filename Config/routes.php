<?php
	Router::connect('/my_upload', array('plugin'=>'my_upload', 'controller' => 'fileuploads', 'action' => 'index'));
	Router::connect('/my_upload/upload', array('plugin'=>'my_upload', 'controller' => 'fileuploads', 'action' => 'add'));
	Router::connect('/my_upload/multi_upload', array('plugin'=>'my_upload', 'controller' => 'fileuploads', 'action' => 'multi_upload'));
