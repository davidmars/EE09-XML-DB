<?php


/**
 * Description of Less
 *
 * @author  david marsalone
 */
class Less {
    
    /**
     * Guess...
     * @var Less 
     */
    private static $current;

    /**
     *
     * @return Less using it will prevent to declare more than one Less instance.
     */
    private static function me() {
        if(self::$current){
            return self::$current;
        }else{
            return new Less();
        }
    }


    /**
     *
     * @var lessc the wonderful & glorious php less compiler
     */
    private static $less;
    /**
     * where to put the output files
     */
    public static $outputPath;


    public function __construct() {        
        self::$current=$this;
        self::$less=new lessc();
        self::$less->setPreserveComments(true);
    }
    /**
     *
     * @param string $lessFile The file to compile (without .less extension)
     * @param array $variables The less variables to set from php.
     * @return string the relative path to the generated css file.
     */
    public static function getCss($lessFile,$variables=array()){
        self::$outputPath=$lessFile."-cache";
        if(!is_dir(self::$outputPath)){
            mkdir(self::$outputPath);
        }
        $outputFile= self::$outputPath."/".$lessFile."-".md5(implode("-", $variables));
        $me=self::me();
        $path=$me->compile($lessFile, $outputFile,$variables);
        return $path;
    }
    /**
     * 
     * @param String $inputFile the path to the less file you want to compile.
     * @param String $outputFile the path to the css file you want as result.
     * @return String the path to the result css file
     */
    public function compile ($inputFile,$outputFile,$variables=array()){
        
        try {
            $outputFile=$outputFile.".css";
            $inputFile=$inputFile.".less";
            
            $folderTest=FileTools::mkDirOfFile($outputFile);
            if(!$folderTest){
                //Human::log("Impossible to create the folder for".$outputFile,"Less compile error",  Human::TYPE_ERROR) ; 
                return false;
            }
            // load the cache
            $cacheFile = $outputFile.".cache";
            if (file_exists($cacheFile)) {
                $cache = unserialize(file_get_contents($cacheFile));
            } else {
                $cache = $inputFile;
            }

            $less = self::$less;
            $less->setVariables($variables);
            $newCache = $less->cachedCompile($cache);
            if (!is_array($cache) || $newCache["updated"] > $cache["updated"]) {
                //the cache is out of date
                //Human::log(Site::url($outputFile, true),"Less new style sheet");

                file_put_contents($cacheFile, serialize($newCache));
                file_put_contents($outputFile, $newCache['compiled']);

            }else{
                 //nothing the cache is up to date.
            }
            
            if(file_exists($outputFile)){
                return $outputFile;
            }else{
                //Human::log("Impossible to create the file ".$outputFile,"Less compile error",  Human::TYPE_ERROR) ; 
                return false; 
            }
            
            
        } catch (exception $e) {
            echo "fatal error: " . $e->getMessage();
            return false;
        }
    }
    /**
     * Return a <link type="text/css"... tag that will include the less compiled file.
     * @param string $lessFile The file to compile
     * @param array $variables The less variables to set from php.
     * @return string a <link type="text/css"... tag that will include the less compiled file.
     */
    public static function getIncludeTag($lessFile,$variables=array()){
        $css=self::getCss($lessFile, $variables);
        return CSS::getIncludeTag($css);
    }
}

