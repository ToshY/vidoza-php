<?php
   /**
   * PHP class for Vidoza API
   * 
   * @author     ToshY
   * @version    1.0.0
   */
	class VidozaMain{
		private $key;
		public $host;

		function __construct($apiHost, $apiKey){
			$this->host = $apiHost;
			$this->key = $apiKey;
		}

		public function curlBuilder($functionName, $functionArgs = NULL){
			// call requested method
			$req = $this->$functionName($functionArgs);
			// setopts
			$ch = curl_init($this->host.$req["url"]);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $req["opt"]);
		    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer '.$this->key));
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

			$response = curl_exec($ch);
			if (!curl_errno($ch)) {
				$res = json_decode($response,true);
			}else{
				$res = curl_error($ch);
			}
			curl_close($ch);
			return $res;
		}

		private function fileStatus($fileCodes){
			return array("opt"=>"GET","url"=>'/files/check?f[]='.((count($fileCodes) > 1) ? implode('&f[]=', $fileCodes) : implode('',$fileCodes)));
		}

		private function folderContent($folderId = NULL){
			return array("opt"=>"GET","url"=>'/folders'.(isset($folderId) ? ('/'.$folderId) : NULL));
		}

		private function renameFolder($inputArray){
			list($folderId, $folderName) = $inputArray;
			return array("opt"=>"PUT","url"=>'/folders/'.$folderId.'?name='.$folderName);
		}

		private function createFolder($inputArray){
			list($parentId, $folderName) = $inputArray;
			return array("opt"=>"POST","url"=>'/folders?name='.$folderName.'&parent_id='.$parentId);
		}

		private function uploadServer(){
			return array("opt"=>"GET","url"=>'/upload/http/server');
		}

		public function uploadFile($filePath, $params){
			// Get upload server
			$vres = $this->curlBuilder("uploadServer");

		    // POST variables
		    $postParams = array();
		    foreach (array_merge($vres["data"]["upload_params"], $params) as $field => $value) {
		        $postParams[$field] = $value;
		    }
		    // Create file
		    $postParams['file'] = new \CurlFile($filePath, mime_content_type($filePath), basename($filePath));

		    // Upload file
		    $ch = curl_init($vres["data"]["upload_url"]);
		    curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		    // Set execution time to inf
		    ini_set('max_execution_time', 0);
		    $response = curl_exec($ch);
			if (!curl_errno($ch)) {
				$res = json_decode($response, true);
				if (($res['status'] === 'OK')) {
					$rt = array("code"=>200,"msg"=>$res);
				}else{
					$rt = array("code"=>400,"msg"=>"Upload error for filecode: ".$res['code']);
				} 
			}else{
				$rt =  array("code"=>400,"msg"=>curl_error($ch));
			}
			curl_close($ch);
			return $rt;
		}
	}
?>
