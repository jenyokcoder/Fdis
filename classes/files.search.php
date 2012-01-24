<?php

class SearchFiles{

    protected $dir;

    protected $ext        = array("php","html","htm","php3","php4","php5","js","txt","sql","tpl","css");

    protected $searchResult = array();

    function __construct($sFolder){

    	$this->dir = $sFolder;

    	return  $this->scanFolder();
    }

    private function scanFolder(){        $files = array(); $dirs = array();

		$dir = new DirectoryIterator($this->dir);

		foreach ($dir as $file => $fileinfo) {
		    if (!$fileinfo->isDot()) {
		        if($fileinfo->isFile()){		        	$file = $fileinfo->getPath($fileinfo->getFilename())."/".$fileinfo->getFilename();

	                $ex = strtolower(pathinfo($file, PATHINFO_EXTENSION));

			    	if(in_array($ex,$this->ext)){

	                        @$buffer = file_get_contents($file);

	                            $str = 'GD_FAVORITES_NAME';

								if(@preg_match("/".$str."/i",$buffer)){
									$this->searchResult[]=$file;
									$files[] = '{"is_in_the_file":"'.$file.'"}';

								}else{
									$files[] = '{"not_in_the_file":"'.$file.'"}';

                                }

			    	}

		   		}else if($fileinfo->isDir()){		   			$path   = $fileinfo->getPath($fileinfo->getFilename());		   			$dirs[] = $path."/".$fileinfo->getFilename()."/";
		   		}
		    }
		}
		if(count($dirs)){			echo json_encode(array("files"=>$files,"folders"=>$dirs,"start"=>"true","searchResult"=>join(" , ",$this->searchResult)));		}else{			echo json_encode(array("files"=>$files,"folders"=>array(),"start"=>"false","searchResult"=>join(" , ",$this->searchResult)));		}    }}

/*

    private function iteration(){

		$iter=0;
		$endIter=50;

		#echo "<pre>";
		#print_r($_SERVER);
		#echo "</pre>";

		foreach (new RecursiveIteratorIterator(
					new RecursiveDirectoryIterator(
						$this->dir, RecursiveDirectoryIterator::KEY_AS_PATHNAME),
							RecursiveIteratorIterator::CHILD_FIRST) as $file => $info) {
            $iter++;
		    if($info->isFile()){

                $ex = strtolower(pathinfo($file, PATHINFO_EXTENSION));

		    	if(in_array($ex,$this->ext)){

                        $fold = $file;

                        $fnew = dirname($file)."/".basename($file,".".$ex).".txt";

                        rename($fold,$fnew);

                        $file=$fnew;

                        echo "<pre>";
                        $fp = fopen ($file, "r");

                        $buffer = fread($fp, filesize($file));
                            $str = '\/main\/include\/prolog\.php'; #addslashes('');
							if(preg_match("/".$str."/i",$buffer)){
								echo "<hr />file : ".$fold." <b>good</b><hr />";
							}else echo "<hr />file : ".$fold." <b>bad</b><hr />";

						fclose ($fp);

                        rename($fnew,$fold);

		    			echo "</pre>";

		    	}

		    }
		    if($iter % $endIter==0) sleep(5);
		}
    }

*/

?>