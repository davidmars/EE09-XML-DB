<?php
class JS{
        
    private static $uniqueJS;
    
    /**
     * Will add the specified js file to the file list to include in the header
     * @param string $jsFileUrl a js file <b>RELATIVE</b> url
     */
    public static function addToHeader($jsFileUrl){
        if(self::$uniqueJS[$jsFileUrl]) {
            return;
        }
        
        self::$uniqueJS[$jsFileUrl] = true;
        self::$headerFiles[]=$jsFileUrl;        
    }
    /**
     * Will add the specified js file to the file list to include after body
     * @param string $jsFileUrl a js file <b>RELATIVE</b> url
     */
    public static function addAfterBody($jsFileUrl){
        
        if(self::$uniqueJS[$jsFileUrl]) {
            return;
        }
        
        self::$uniqueJS[$jsFileUrl] = true;
        self::$afterBodyFiles[]=$jsFileUrl;
    }
    
    /**
     *
     * @var array The js file list to include in the header 
     */
    private static $headerFiles=array();
     /**
     *
     * @var array The js file list to include after body
     */
    private static $afterBodyFiles=array();
    
    /**
     *
     * @return string tags
     */
    public static function includeHeaderFiles($combine=true){

        $outp='';
        $names=array();
        $targetDir=Site::$mediaFolder."/cache/js/";
        
        //if no file added
        if(empty(self::$headerFiles)){return false;}
        
        //if compression
        if($compress){
            //check if file exists
            foreach(self::$headerFiles as $f){
                $names[]=FileTools::filename($f);
            }
            $name='script'.md5(implode('-',$names)).'.js';
            $targetUrl = $targetDir.$name;
            if(  !file_exists($targetUrl) || 
                 (file_exists($targetUrl) && filemtime($targetUrl)<time())
               ){
            
            //if file doesn't exist or exists but is too old: create
                foreach(self::$headerFiles as $f){
                    $c=file_get_contents($f);
                    $packer = new JavaScriptPacker($c, 'Normal', true, false);
                    $outp .= $packer->pack();
                }

                FileTools::mkDirOfFile($targetUrl);
                file_put_contents($targetUrl, $outp);
                $outp=self::getTag(GiveMe::url($targetUrl));
                
                self::$headerFiles=array();
                return $outp;
            } else {
                self::$headerFiles=array();
                return self::getTag(GiveMe::url($targetUrl));
            }
        } else {
            //if no compression
            $outp=self::getTags(self::$headerFiles);
            self::$headerFiles=array();
            return $outp;
        }
        
    }
    /**
     *
     * @return string tags
     */
    public static function includeAfterBodyFiles($combine=false,$compress=false){
        
        return self::getOutput(self::$afterBodyFiles, $combine, $compress);
        
        $outp='';
        $names=array();
        $targetDir=Site::$mediaFolder."/cache/js/";

        
        //if no file added
        if(empty(self::$afterBodyFiles)){return false;}
        
        //if compression
        if($compress){
            //check if file exists
            foreach(self::$afterBodyFiles as $f){
                $names[]=FileTools::filename($f);
            }
            
            $name='script'.md5(implode('-',$names)).'.js';
            $targetUrl=$targetDir.$name;
            
            if(  !file_exists($targetUrl) || 
                 (file_exists($targetUrl) && filemtime($targetUrl)<time())
               ){
            
                //if file doesn't exist or exists but is too old: create
                foreach(self::$afterBodyFiles as $f){
                    $c=file_get_contents($f);
                    $packer = new JavaScriptPacker($c, 'Normal', true, true);
                    $outp .= $packer->pack();
                }
                $outp.=";";
                //create file
                FileTools::mkDirOfFile($targetUrl);
                file_put_contents($targetUrl, $outp);
                $outp=self::getTag(GiveMe::url($targetUrl));
                
                self::$afterBodyFiles=array();
                return $outp;
            } else {
                self::$afterBodyFiles=array();
                return self::getTag(GiveMe::url($targetUrl));
            }
        } else {
            //if no compression
            $outp=self::getTags(self::$afterBodyFiles);
            self::$afterBodyFiles=array();
            return $outp;
        }
    }
    private static function getOutput($filesList,$combine=false,$compress=false){

        //if no files...
        if(empty($filesList)){
            return false;  
        }
        
        
        if($combine){
            return self::getTag(self::getCombined($filesList,$compress));
        }else{
            return self::getTags($filesList);
        }
        
        //if compression
        if($compress){
            
            
            $name="script".md5(implode('-',$names)).datfilemtime($targetUrl).".js";
            $targetUrl=$targetDir.$name;
            
            if(  !file_exists($targetUrl) || 
                 (file_exists($targetUrl) && filemtime($targetUrl)<time())
               ){
            
                //if file doesn't exist or exists but is too old: create
                foreach(self::$afterBodyFiles as $f){
                    $c=file_get_contents($f);
                    $packer = new JavaScriptPacker($c, 'Normal', true, true);
                    $outp .= $packer->pack();
                }
                $outp.=";";
                //create file
                FileTools::mkDirOfFile($targetUrl);
                file_put_contents($targetUrl, $outp);
                $outp=self::getTag(GiveMe::url($targetUrl));
                
                self::$afterBodyFiles=array();
                return $outp;
            } else {
                self::$afterBodyFiles=array();
                return self::getTag(GiveMe::url($targetUrl));
            }
        } else {
            //if no compression
            $outp=self::getTags(self::$afterBodyFiles);
            self::$afterBodyFiles=array();
            return $outp;
        }  
    }
    /**
     * Combine a file list in one file.
     * @param type $filesList
     * @param type $compress 
     * @return string A file url with all scripts inside.
     */
    private static function getCombined($filesList,$compress){
        
        $targetDir=Site::$mediaFolder."/cache/js/";
        
        //check if file exists and check most recent changes
        $latest=0;
        $err=array();
        $goodFiles=array();
        foreach($filesList as $f){
            if(file_exists($f)){
                $latest= max(array($latest,  filemtime($f))); 
                $goodFiles[]=$f;
            }else{
                $message="File ".$f." not found";
                //Human::log($message, "JS combine error", Human::TYPE_ERROR);
                $err[]=$message;
            }
           
        }
        //check if THIS FILE is modified :)
        $latest= max(array($latest,  filemtime(Site::$systemUtils."/forHipsters/JS.php"))); 
        //the file name
        $outputUrl=$targetDir."/js-combine-".($compress?"min-":"").date("Y-m-d-H-i-s")."-".md5(implode("-",$filesList)).".js";
        
        if(file_exists($outputUrl)){
            return $outputUrl;
        }else{
            foreach($goodFiles as $f){
               $content.=file_get_contents($f).";\n";
            }
            //compress
            if(false && $compress){
                    $packer = new JavaScriptPacker($content, 95,false,false);
                    //$packer = new JavaScriptPacker($content);
                    $content=$packer->pack(); 
                
                
                //$min=new JSMin();
                //$content=$min->minify($content);
                
                //$min=new Minify_YUICompressor();
                //$content=$min->minifyJs($content);
            }
            $content="/*\n".implode("\n",$filesList)."\n*/\n".$content;
            FileTools::mkDirOfFile($outputUrl);
            file_put_contents($outputUrl, $content);
            return $outputUrl;
        }
    }
    private static function getCompressed($script){
        return $script;
    }
    /**
     *
     * @param type $javascriptFile You should understand what it is...no?
     * @return string  something like <script src=... 
     */
    public static function getTag($javascriptFile){
        return '<script src="'. GiveMe::url($javascriptFile,false).'"></script>'."\n";
    }
    /**
     *
     * @param array $filesList
     * @return string a list of something like <script src=... 
     */
    public static function getTags($filesList){
        $outp="";
        foreach($filesList as $f){
            $outp.=self::getTag($f);
        }
        return $outp;
    }
}