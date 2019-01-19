<?php
# HOW TO USE VIDOZA CLASS
require_once 'vidozaClass.php';

# Pass your API key from the VIDOZA user panel in here
$vdz = new VidozaMain('https://api.vidoza.net/v1','1d0ntkn0wh4tk3y2g1v3th1s');

# Get file status
/* Arguments (in array):
- Required
	FileCodes; the file code(s)
*/
$res = $vdz->curlBuilder('fileStatus', array('h3ll0w0r1d','f00b4rz'));
print_r($res);

# Get folder content
/* Arguments:
- Required
	FolderId; folder id
*/
$res = $vdz->curlBuilder('folderContent', 123);
print_r($res);

# Create folder
/* Arguments (in array):
- Required
	ParentId; parent (folder) id
	FolderName; the name of folder
*/
$res = $vdz->curlBuilder('createFolder', array(0, 'Cogito ergo es'));
print_r($res);

# Rename folder
/* Arguments (in array):
- Required
	FolderId; folder id
	FolderName; the new name of folder
*/
$res = $vdz->curlBuilder('renameFolder', array(0,'I think therefore you are'));
print_r($res);

# Get upload folder
$res = $vdz->curlBuilder('uploadServer');
print_r($res);

# Upload file
/* Arguments (in array):
- Required
	FilePath; folder id
- Optional
	array('file_title'=>'',
		  'file_descr'=>'',
		   'fld_id'=>'',
		   'cat_id'=>'',
		   'extra_Year'=>'',
		   'tags'=>'');
*/
$res = $vdz->uploadFile('/foo/barz.mp4', array('fld_id'=>123,'cat_id'=>3));
print_r($res);
?>