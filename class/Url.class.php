<?php 

class Url{
	static $numberOfCalls = 0;
	protected $path;
	protected $fullUrl;
	protected $pathUrl;
	protected $urlArray;
	protected $urlString;
	protected $allowed;
	public $template = "n";

	function __construct(){
		/* We do not need to use this class twice,
		so it has a $numberOfCalls that just allow to
		call this class once*/
		if (self::$numberOfCalls > 0) {
			echo "POWW! Just one instance of Url, cowboy! This town ain't big enough for both of us!";
			exit();
		}
		self::$numberOfCalls++;
		/*Now its the only Url instance in system*/
		$this->fullUrl = (isset($_SERVER['HTTPS']) ? "https" : "http") . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
		$this->pathUrl = $_SERVER['REQUEST_URI'];
		/*Store url information*/
		$this->path = PATH;	
		

		$url = $this->explodeWithoutPath();

		if ($url == null) {
			exit();
		} elseif (count($url) == 2) {
			$this->urlString = $url[0];
			$this->urlArray = $url[1];
		}
		



		if(empty($this->allowed)){
			$this->configureAllowedPaths($GLOBALS['PUBLIC_PATHS']);
		}
		


		if(!$this->isAllowed($this->whereAmI(""))){


			/*      __    ___     __     */
			/*     /  |  / _ \   /  |    */
			/*    //| | | / \ | //| |    */
			/*   //_| |_||   ||//_| |_   */
			/*  |___   _||   ||___   _|  */
			/*      | | | \_/ |   | |    */
			/*      |_|  \___/    |_|    */
			/*                           */


			echo "<title>404</title>";
			exit();
		} else {
			// $GLOBALS['TITLE'] = array_reverse($this->whereAmI(array()))[0];
			$GLOBALS['TITLE'] = $this->titleGenerator($this->template);
		}
	}

	protected function explodeWithoutPath(){
		/*explode..*/
		$ret = array();
		
		$url = str_replace($this->path, "", $this->pathUrl);
		if(strlen($url) > 0){
			$ret[] = $url;
			$url = explode('/', $url);
			$ret[] = $url;
			return $ret;
		} return "";
	}

	public function whereAmI($type){
		/*
		
		$type ===> "" || array();

		Depending on $type return a string or an array 
		with the path information;

		*/


		if(!is_array($type) && !is_string($type)) {
			echo 'Url::whereAmI(/!\) ===> You need to see it like string or array?';
			exit();
		}
		if(is_string($type)){
			if(strlen($type) > 0){
				echo "Url::whereAmI(/!\) ===> Do not write here! I just want to know what type is it...";
				exit();
			}
			return $this->urlString;
		}
		if(is_array($type)){
			if(count($type) > 0){
				echo "Url::whereAmI(/!\) ===> Do not give this garbage! Your are using precious memory! I just want to know what type is it...";
				exit();
			}
			return $this->urlArray;
		})
	}
	public function howDeepAmI(){
		return count($this->urlArray);
	}

	public function configureAllowedPaths($paths){
		/*$paths needs to be an array, 
		it will be saved on $this->allowed
		
		the user can navigate in allowed paths just
		*/


		if($paths == null){
			echo "Url::configureAllowedPaths(/!\) ===> Great... So... I need an array of strings to run... Thank you!";
			exit();
		}
		if(!is_array($paths)){
			echo "Url::configureAllowedPaths(/!\) ===> I was looking for something more like an array... Do you know?...";
			exit();
		}
		if(is_array($paths)){
			if(count($paths) <= 0){
				echo "Url::configureAllowedPaths(/!\) ===> Why do you want configure allowed paths if you do not have any path?";
				exit();
			}
			$errorCount = 0;
			foreach ($paths as $key => $path) {
				if(!is_string($path)){
					if($errorCount == 0){
						echo "Url::configureAllowedPaths(/!\) ===> In the array of paths you gave me, the posiition [" . $key . "] is not a string.";
					} elseif($errorCount == 1){
						echo " Also correct: " . $key;
					} else {
						echo ", " . $key;
					}
					$errorCount++;
				}
			}
			if ($errorCount > 1) {
				echo ";";
				exit();
			}
			if ($errorCount > 0) {
				exit();
			}
			$this->allowed = $paths;
		}
	}
	protected function isAllowed($path){
		return in_array($path, $this->allowed);
	}
	protected function titleGenerator($template){
		$open = false;
		$num = 0;
		$numSize = 0;
		$ret = "";
		foreach ($template as $char) {
			switch($char){
				case "[": $open = true; continue;
				case "]": 
					if ($this->howDeepAmI() > $num) {
						if ($num == -1 && $numSize == 1) {
							$ret .= array_reverse($this->urlArray)[0];
						} elseif ($num == -1) continue;
						$ret .= $this->urlArray[$num];
					}
					$open = false; 
					$num = 0;
					$numSize = 0;
					continue;
				case "n":
					if($open){
						$num = -1;
						continue;
					} 
					$ret .= $char;
					continue;
				default: 
					if ($open) {
						$num *= 10;
						$num += intval($char);
						$numSize++;
					}
					$ret .= $char; 
					continue;
			}
		}
	}
}

?>