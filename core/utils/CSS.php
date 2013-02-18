<?php
class CSS{
    private static $uniqueCSS;
    /**
     * Will add the specified css file to the file list to include
     * @param string $file a css file url
     */
    public static function addToHeader($cssFileUrl){
        
        if (self::$uniqueCSS[$cssFileUrl]) {
            return;
        }
        
        self::$uniqueCSS[$cssFileUrl] = true;
        $file=  GiveMe::url($cssFileUrl,true);
        self::$headerFiles[]=$file;
    }
    /**
     *
     * @var array The css file list to include in the header 
     */
    private static $headerFiles=array();
    
    /**
     *
     * @return string tags
     */
    public static function includeHeaderFiles($compress=true){
        $outp="";
        $names=array();
        $targetDir=Site::$mediaFolder."/cache/css/";
        
        //if no file added
        if(empty(self::$headerFiles)){return false;}
        
        //if compression
        if($compress)
        {
            //check if file exists
            foreach(self::$headerFiles as $f)
            {
                $names[]=FileTools::filename($f);
            }
        
            $name='style'.md5(implode('-',$names)).'.css';
            $targetUrl = $targetDir.$name;
            
            if  (   !file_exists($targetUrl) || 
                    (file_exists($targetUrl) && filemtime($targetUrl)<time())
                )
            {
            
            //if file doesn't exist or exists but is too old: create
                foreach(self::$headerFiles as $f)
                {
                    $c=file_get_contents($f);
                    $min=new CSSmin();
                    $outp .= $min->run($c);
                }

                FileTools::mkDirOfFile($targetUrl);
                file_put_contents($targetUrl, $outp);
            }
            self::$headerFiles=array();
            $outp='<link rel="stylesheet" href="'.GiveMe::url($targetUrl).'">'."\n";
            return $outp;
        }   else    {
        //if no compression
            foreach(self::$headerFiles as $f)
            {
                $outp.='<link rel="stylesheet" href="'.$f.'">'."\n";
            }
            self::$headerFiles=array();
            return $outp;
        }
    }
    
    /**
     * A shortcut to have CSS include tag.
     * @param string $cssFile path to the css file
     * @return string  something like <link type="text/css" href="YOUR-FILE" etc...
     */
    public static function getIncludeTag($cssFile){
        return "<link type=\"text/css\" rel=\"stylesheet\" href=\"".$cssFile."\"/>";
    }
    
}