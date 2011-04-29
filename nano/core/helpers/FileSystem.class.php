<?php
namespace nano\core\helpers;


/**
 * Filesystem helper library.
 * 
 * @author Christopher Beck, Alessandro Barzanti & Alex Cipriani
 * @version SVN: $id
 * @package nanophp
 * @subpackage project.core.helpers
 */
class FileSystem {
	
	/**
	 * Get an array of all files and folders in the specified directory.
	 * The associative array returned will contain two keys, 'files' and 'folders' respectively,
	 * each of which will contain a one-dimentional array of the names of elements found in the specified
	 * directory.
	 * 
	 * @param mixed $dir Directory to scan.
	 * @param mixed $showHidden Specifies whether to include hidden files/folders in the results.
	 * @return array Multidimentional array in the format specified above.
	 */
	public static function getFilesAndFoldersInDirectory($dir,$showHidden=false) {
		$fileNames = array();
		$folderNames = array();
		if($handle = opendir($dir)){
			while(false !== ($file = readdir($handle))){
				$sysfilePath = $dir.'/'.$file;
				$phpFilePath = preg_replace('/^\.\//','',$sysfilePath);
				if(!is_dir($sysfilePath)){
					if($showHidden){
						$fileNames[$phpFilePath] = $file;
					}else{
						if(!preg_match('/^\./',$file)){
							$fileNames[$phpFilePath] = $file;
						}
					}
				}else{
					$file = preg_replace('/^\.\//','',$file);
					if($showHidden){
						$folderNames[$phpFilePath] = $file;
					}else{
						if(!preg_match('/^\./',$file)){
							$folderNames[$phpFilePath] = $file;
						}
					}
				}
			}
			closedir($handle);
		}
		return array(
			'files' => $fileNames,
			'folders' => $folderNames
		);
	}
	
	/**
	 * Get all the folders (non-recursively) in the specified directory.
	 * @param string $dir Directory to scan.
	 * @return array An array of all directories found, with each element prefixed by $dir.
	 */
	public static function getFoldersIn($dir) {
		$folderScan = scandir($dir);
		$folders = array();
		foreach($folderScan as $folderName){
			if(is_dir($dir.$folderName)){
				if(!preg_match('/^\./',$folderName)){
					$folders[$folderName] = $dir.$folderName.'/';
				}
			}
		}
		return $folders;
	}
	
	/**
	 * Get all the files in the specified directory.
	 * @param string $dir Directory to scan.
	 * @return array An array of all files found, with each element prefixed by $dir.
	 */
	public static function getFilesIn($dir) {
		$folderScan = scandir($dir);
		$files = array();
		foreach($folderScan as $fileName){
			if(!is_dir($dir.$fileName)){
				if(!preg_match('/^\./',$fileName)){
					$files[$fileName] = $dir.$fileName;
				}
			}
		}
		return $files;
	}
	
	/**
	 * Get all the files in the specified directory and any below it.
	 * @param string $dir Directory to scan.
	 * @param array $filesUnder An option array to append the results to. Used internally for recursion.
	 * @return array An array of all files found. Each element will be prefixed by $dir.
	 */
	public static function getFilesUnder($dir,$filesUnder = array()) {
		foreach(self::getFoldersIn($dir) as $newDir){
			$newFiles = self::getFilesIn($newDir);
			foreach($newFiles as $newFile){
				$filesUnder[] = $newFile;
			}
			$filesUnder = self::getFilesUnder($newDir,$filesUnder);
		}
		return $filesUnder;
	}
}