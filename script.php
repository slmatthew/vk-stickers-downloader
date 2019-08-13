<?php

define("DOWNLOAD_FOLDER", "images");

if(!empty($argv)) {
	$ps = [
		'' => 'help',
		'st:' => 'start:',
		'en:' => 'end:',
		'fd::' => 'folder::',
		'sz::' => 'size::',
		'bg::' => 'background::'
	];
	$params = getopt(implode('', array_keys($ps)), $ps);

	if(isset($params['help'])) {
		echo "        VK Stickers downloader help        

    Usage: php download-stickers.php [--help] [--start=1] [--end=1] [--folder] [--size=512] [--background]

    --help        Show this message
    --start       Start sticker ID (required)
    --end         End sticker ID (required)
    --folder      Folder name (default: [start sticker ID]-[end sticker ID], like 1-2)
    --size        Stickers size. One of: 64, 128, 256, 512
    --background  With background";
		exit();
	}

	if(!isset($params['start']) && !isset($params['st'])) exit('You need provide start sticker ID');
	$start = isset($params['start']) ? (int)$params['start'] : (int)$params['st'];

	if(!isset($params['end']) && !isset($params['en'])) exit('You need provide end sticker ID');
	$end = isset($params['end']) ? (int)$params['end'] : (int)$params['en'];

	if(!isset($params['folder']) && !isset($params['fd'])) $folder = "{$start}-{$end}";
	else $folder = isset($params['folder']) ? $params['folder'] : $params['fd'];

	if(!isset($params['size']) && !isset($params['sz'])) $size = 512;
	else $size = isset($params['size']) ? (int)$params['size'] : (int)$params['sz'];

	$bg = isset($params['background']) || isset($params['bg']);

	if(isset($size)) {
		if(!in_array($size, [64, 128, 256, 512])) {
			echo "Warning: invalid size, will use 512\n";
			$size = 512;
		}
	} else {
		echo "Size not provided, will use 512\n";
		$size = 512;
	}

	echo "Start download...\n\n";

	for($i = (int)$start; $i <= (int)$end; $i++) {
		$file = file_get_contents("https://vk.com/sticker/1-{$i}-{$size}".($bg ? "b" : ""));
		@file_put_contents(__DIR__."\\".DOWNLOAD_FOLDER."\\{$folder}\sticker-{$i}-{$size}".($bg ? "b" : "").".png", $file);
		echo "Saved sticker: https://vk.com/sticker/1-{$i}-{$size}".($bg ? "b" : "")."\n";
	}

	echo "\nDownload end.\n";
	exit();
} else {
	echo 'Script need be started from terminal';
	exit();
}