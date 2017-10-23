<?php
set_time_limit(1000);
    class SingleFileSender{
    	
    	private $prot = 80;
        private $headers=array();
        private $boundary="";
        private $postData="";

        /**
         * @param {Array} $url参数集
         *                 {String} path
         *                 {String} host
         **/
        private $urlParams=array();

        function __construct($url){
            $this->updateURLParams($url);
            $this->initHeaders();
        }
        /**
         * 指定端口，缺省80
         * @param unknown $port
         */
        public function setPort($port){
        	$this->prot=$port;
        }
        /**
         * 设置发送的header
         * @param {String} $key
         * @param {String} $value
         */
        public function setHeader($key="",$value=""){
              $this->headers[$key]=$value;
        }

        /**
         * POST发送数据
         * @param {Array} $datas
         *                     array(
                                array(
                                      "postName"=>"pic",
                                      "fileName"=>"/data/a.text",
                                      "file"=>"file content",
                                    "type"=>"text/plain"
                                      ),
                                  array(
                                      "name"=>"text1",
                                      "value"=>"text1's content"
                                      )
                              ));
         **/
        public function post($datas=array(),$fileflag){

            $ret="";
            $this->updateBoundary();
            $postData=$this->getPostData($datas,$fileflag);



            $this->headers["Content-Type"]="multipart/form-data; boundary=".$this->boundary;
            $this->headers["Content-Length"]=strlen($postData);

            $sendContent = $this->getHeaderStr($this->headers)."\r\n".$postData;

            //echo $sendContent;
            //$sendContent = $postData;
            
            $fp = fsockopen($this->urlParams["host"], $this->prot, $errno, $errstr, 30);



            if (!$fp) {
                $ret="$errstr ($errno)<br/>\n";
            } else {
                fwrite($fp, $sendContent);
                $ret = fread($fp,10240);
                fclose($fp);
            }

            return $ret;
        }

        /**
         * 初始化header
         *
         */
        private function initHeaders(){
            $this->headers["Accept"]="*/*";
            $this->headers["Connection"]="Keep-Alive";
            $this->headers["Host"]=$this->urlParams["host"];
        }

        /**
         * 更新boundary
         */
        private function updateBoundary(){
            $this->boundary="BOUNDARY".microtime(true)*10000;
        }

        /**
         * 获取要post的数据
         * @param {Array} $datas
         * @return {String} $ret
         */
        private function getPostData($datas=array(),$fileflag){
            $ret="";
            if($fileflag){
            $fileData=array_shift($datas);
			$ret="--".$this->boundary."\r\n".
                    'Content-Disposition: form-data; name="'.$fileData["postName"].'"; filename="'.$fileData["fileName"]."\"\r\n".
                    "Content-Type: ".$fileData["type"]."\r\n\r\n".
                    $fileData["file"]."\r\n";
			}


            foreach($datas as $k => $v){
                $ret.="--".$this->boundary."\r\n".
                    'Content-Disposition: form-data; name="'.$v["name"]."\"\r\n\r\n".
                    $v["value"]."\r\n";
            }

            $ret.="--".$this->boundary."--\r\n";

            return $ret;
        }

        /**
         * 更新URL参数
         * @param {String} $url
         */
        private function updateURLParams($url){
            if(preg_match('/^http\:\/\/([^\/]+)\/?(.*)/',$url,$rets)){
                $rets[1] && $this->urlParams["host"]=$rets[1];
                $rets[2] && $this->urlParams["path"]=$rets[2];
            }
        }

        /**
         * 将header数组转化为String
         * @param {Array} $headerArr
         * @return {String} $ret
         */
        private function getHeaderStr($headerArr=array()){
            $ret="POST /".$this->urlParams["path"]." HTTP/1.1\r\n";

            foreach($headerArr as $k=>$v){
                $ret.="$k: $v\r\n";
            }

            return $ret;
        }
    }
?>