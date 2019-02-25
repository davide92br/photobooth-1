<?php
$config = array();
$config['os'] = (DIRECTORY_SEPARATOR == '\\') || (strtolower(substr(PHP_OS, 0, 3)) === 'win') ? 'windows' : 'linux';
$config['dev'] = false;
$config['use_print'] = true;
$config['use_qr'] = true;
$config['show_fork'] = true;
#$config['file_format'] = 'date'; // comment in to get dateformat images
// FOLDERS
// change the folders to whatever you like
$config['folders']['images'] = 'usbhdd/images';
$config['folders']['thumbs'] = 'usbhdd/thumbs';
$config['folders']['qrcodes'] = 'usbhdd/qrcodes';
$config['folders']['print'] = 'usbhdd/print';
// GALLERY
// should the gallery list the newest pictures first?
$config['gallery']['newest_first'] = true;
// LANGUAGE
// possible values: en, de, fr
$config['language'] = 'it';
// COMMANDS and MESSAGES
switch($config['os']) {
        case 'windows':
        $config['take_picture']['cmd'] = 'digicamcontrol\CameraControlCmd.exe /capture /filename %s';
        $config['take_picture']['msg'] = 'Photo transfer done.';
        $config['print']['cmd'] = 'sudo lp -d Canon_SELPHY_CP1200 -o landscape -o media=Custom.100x148mm -o fit-to-page %s';
        $config['print']['msg'] = '';
        break;
        case 'linux':
        default:
        $config['take_picture']['cmd'] = 'sudo gphoto2 --capture-image-and-download --filename=%s images';
        $config['take_picture']['msg'] = 'New file is in location';
        $config['print']['cmd'] = 'sudo lp -d Canon_SELPHY_CP1200 -o landscape -o media=Custom.100x148mm -o fit-to-page %s';
        $config['print']['msg'] = '';
        break;
}
// DON'T MODIFY
// preparation
foreach($config['folders'] as $directory) {
        if(!is_dir($directory)){
                mkdir($directory, 0777);
        }
}
