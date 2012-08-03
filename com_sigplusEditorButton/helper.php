<?php
// no direct access
defined('_JEXEC') or die;

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder' );
jimport('joomla.application.component.helper');
jimport('joomla.plugin.helper');

class ImageData {
	public $label = '';
	public $desc = '';
}

class sigplusEditorButtonHelper {
	const FLAG_ASPECTRATIO = 1;
	const FLAG_PADDING = 2;
	
	private $flags = 0;
	
	function sigplusEditorButtonHelper() {
	
	}
	
	function isFileBrowserComponentEnabled() {
		return JComponentHelper::isEnabled('com_sigpluseditorbutton');
	}
	
	function isSIGPLUSEnabled() {
		return JPluginHelper::isEnabled('content', 'sigplus');
	}
	
	function getBaseFolder($filesystem = true) {
		$plugin = &JPluginHelper::getPlugin('content', 'sigplus');
		$pluginParams = new JParameter($plugin->params);
		return ($filesystem?JPATH_ROOT.DS:JURI::root()).$pluginParams->get('base_folder');
	}
	
	function getFolders($path) {
		$path = preg_replace('/\./i', '', $path);
	
		$folders = JFolder::folders($this->getBaseFolder().DS.$path);
		
		foreach($folders as $key => &$folder) {
			// hide thumb folder
			if($folder == 'preview' || $folder == 'thumbs') {
				unset($folders[$key]);
			}
		}
		
		return $folders;
	}
	
	function createDirectory($dir) {
		@mkdir($dir) && file_put_contents('index.html', '<html><body bgcolor="#FFFFFF"></body></html>');
	}
	
	function getFiles($path) {
		$files = JFolder::files($this->getBaseFolder().DS.$path);
		
		$types = array("jpg", "jpeg", "png", "gif");
		
		foreach($files as $key => &$file) {
			// allow only image types
			$fileext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
			if(!in_array($fileext, $types)) {
				unset($files[$key]);
				continue;
			}
			
			// generate or load cached thumb
			$this->getThumb($this->getBaseFolder().DS.$path, $file);
		}
		
		return $files;
	}
	
	public function getThumb($path, $filename) {
		$thumb_dir = $path.DS.'thumbs';
	
		if(!JFolder::exists($thumb_dir)) {
			if(!JFolder::create($thumb_dir)) {
				echo 'error: '.$thumb_dir;
				return;
			}
		}
	
		if (!JFile::exists($thumb_dir.DS.$filename)) {
			$this->resizeImage($path.DS.$filename, $thumb_dir.DS.$filename, 80, 60);
		} else {
			
		}
	}
	
	public function saveImageData($path, $imageData) {
		$l = $this->getBaseFolder().DS.$path.DS.'labels.txt';
		
		$buffer = '';
		foreach($imageData as $key => $iD) {
			$buffer .= $key.'|'.$iD->label.'|'.preg_replace('/\r\n|\r|\n/', ' ', $iD->desc)."\n";
		}
		JFile::write($l, $buffer);
	}
	
	public function loadImageData($path) {
		$l = $this->getBaseFolder().DS.$path.DS.'labels.txt';
		
		$imageData = array();
		if(JFile::exists($l)) {
			$content = JFile::read($l);
			if($l !== false) {
				$lines = preg_split('/\r\n|\r|\n/', $content);
				foreach($lines as $line) {
					$parts = preg_split('/\|/', $line);
					$parts_count = count($parts);
					switch($parts_count) {
						case 3:
							$iD = new ImageData();
							$iD->label = $parts[1];
							$iD->desc = $parts[2];
							$imageData[$parts[0]] = $iD;
							break;
						default:
						
							break;
					}
				}
			}
		}
		
		return $imageData;
	}
	
	/*
	 * Setters
	 */
	public function setAspectRatio($bool) {
		if ($bool == true)
			$this->flags = $this->flags | self::FLAG_ASPECTRATIO;
		else
			$this->flags = $this->flags ^ self::FLAG_ASPECTRATIO;
	}
	
	public function setPadding ($bool) {
		if ($bool == true)
			$this->flags = $this->flags | self::FLAG_PADDING;
		else
			$this->flags = $this->flags ^ self::FLAG_PADDING;
	}
	
	/*
	 * Getters
	 */
	public function getAspectRatio() {
		if (($this->flags & self::FLAG_ASPECTRATIO) == self::FLAG_ASPECTRATIO)
			return true;
		else
			return false;
	}
	
	public function getPadding() {
		if (($this->flags & self::FLAG_PADDING) == self::FLAG_PADDING)
			return true;
		else
			return false;
	}
	
	/**
	 * Returns false if failed
	 * Returns image data if successful
	 
	 file_get_contents(
	 **/
	private function resizeImage($src, $dst, $newWidth, $newHeight) {
		$imageData = file_get_contents($src);
		$imageSrc = imagecreatefromstring($imageData);
		imagealphablending($imageSrc, true);
		imagesavealpha($imageSrc, true);
		
		$imageWidth = imagesx($imageSrc);
		$imageHeight = imagesy($imageSrc);
		
		if ($this->getAspectRatio())
		{
			if ($this->getPadding())
			{
				$paddingWidth = $newWidth;
				$paddingHeight = $newHeight;
			}
			$this->calculateAspectRatio ($imageWidth, $imageHeight, $newWidth, $newHeight);
		}
		$newImageSrc = imagecreatetruecolor($newWidth, $newHeight);
		imagealphablending($newImageSrc, false);
		
		imagecopyresampled($newImageSrc, $imageSrc, 0, 0, 0, 0, $newWidth, $newHeight, $imageWidth, $imageHeight);
		
		if ($this->getPadding())
		{
			$tmp = imagecreatetruecolor($paddingWidth, $paddingHeight);
			imagealphablending($tmp, false);
			$trans_colour = imagecolorallocatealpha($tmp, 0, 0, 0, 127);
			imagefill($tmp, 0, 0, $trans_colour);
			
			$pad_side = floor(($paddingWidth - $newWidth) / 2);
			$pad_top = floor(($paddingHeight - $newHeight) / 2);
			
			imagecopyresampled($tmp, $newImageSrc, $pad_side, $pad_top, 0, 0, $newWidth, $newHeight, $newWidth, $newHeight);
			$newImageSrc = $tmp;
		}
		imagesavealpha($newImageSrc, true);

		//$newImageFile = tempnam(self::TMP_DIR, self::TMP_PREFIX);

		imagepng($newImageSrc, $dst);
		//$newImageData = file_get_contents($newImageFile);
		
		/*
		 * Clean Up
		 */
		imagedestroy($imageSrc);
		imagedestroy($newImageSrc);
		//unlink($newImageFile);
		
		return;
	}
}

?>