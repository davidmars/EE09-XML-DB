<?php
/**
 * Represents an file that is an image.
 * Here it will be possible to manipulate image.
 *
 * @see http://phpimageworkshop.com/
 */
class GinetteFileImage extends GinetteFile
{


    /**
     * @return string The folder where are stored the cached images for this image
     */
    private function cacheFolder(){
        return $this->db->paths->cacheImg."/".$this->relativePath;
    }

    /**
     * Return the url of an image produced by a image manipulation function call.
     * @param $functionName
     * @param $arguments
     * @param $extension
     * @return string
     */
    private function getCacheUrl($functionName,$arguments,$extension){
        $r="";
        $r.=$functionName."-";
        $r.=array_shift($arguments);
        foreach($arguments as $arg){
            $arg=str_replace("#","",$arg);
            $r.="-".$arg;
        }
        $r.=".$extension";
        return $r;
    }
    /**
     * @var ImageWorkshopLayer
     */
    private $im;
    /**
     * @return PHPImageWorkshop\Core\ImageWorkshopLayer
     */
    private function img(){

        if($this->im){
           return $this->im;
        }
        $this->im = \PHPImageWorkshop\ImageWorkshop::initFromPath($this->path);
        return $this->im;
    }

    /**
     * @return int The original image width
     */
    public function width(){
        if($this->meta()->width){
            return $this->meta()->width;
        }
        $w=$this->img()->getWidth();
        $this->meta()->width=$w;
        $this->meta()->save();
        return $this->meta()->width;
    }

    /**
     * @return int The original image height
     */
    public function height(){
        if($this->meta()->height){
            return $this->meta()->height;
        }
        $h=$this->img()->getHeight();
        $this->meta()->height=$h;
        $this->meta()->save();
        return $this->meta()->height;
    }

    /**
     * Return a sized image url.
     * The width of the result image is the given parameter, the height is deduced from the original image proportions.
     *
     * @param int $width Width you want for the image
     * @param string $bgColor Something like "#ff0000" or "transparent" (for png only).
     * @param string $mime Something like "jpg", "png" or "gif"
     * @param int $quality the image quality between 0 and 100
     * @return string Url of the produced image (note that image is processed once, after the file is in cache).
     */
    public function sizedWidth($width,$bgColor="#000000",$quality=95,$mime="jpg"){
        $filename=$this->getCacheUrl(__FUNCTION__,array($width,$bgColor,$quality),$mime);
        $finalUrl=$this->cacheFolder()."/".$filename;
        if(self::$processImage && !file_exists($finalUrl)){
            $this->img()->resizeInPixel($width,null,true);
            $this->img()->save($this->cacheFolder(), $filename, true, $bgColor, $quality);
            $this->im=null;
        }
        return $finalUrl;
    }

    /**
     * Return a sized image url.
     * The height of the result image is the given parameter, the width is deduced from the original image proportions.
     *
     * @param string $bgColor Something like "#ff0000" or "transparent" (for png only).
     * @param int $quality the image quality between 0 and 100
     * @param string $mime Something like "jpg", "png" or "gif"
     * @param int $height Height you want for the image
     * @return string Url of the produced image (note that image is processed once, after the file is in cache).
     */
    public function sizedHeight($height,$bgColor="#000000",$quality=95,$mime="jpg"){
        $filename=$this->getCacheUrl(__FUNCTION__,array($height,$bgColor,$quality),$mime);
        $finalUrl=$this->cacheFolder()."/".$filename;
        if(self::$processImage && !file_exists($finalUrl)){
            $this->img()->resizeInPixel(null,$height,true);
            $this->img()->save($this->cacheFolder(), $filename, true, $bgColor, $quality);
            $this->im=null;
        }
        return $finalUrl;
    }

    /**
     * @param int $width Width you want for the image
     * @param int $height Height you want for the image
     * @param string $bgColor Something like "#ff0000" or "transparent" (for png only).
     * @param int $quality the image quality between 0 and 100
     * @param string $mime Something like "jpg", "png" or "gif"
     * @return string Url of the produced image (note that image is processed once, after the file is in cache).
     */
    public function sizedShowAll($width,$height,$bgColor="#000000",$quality=95,$mime="jpg"){
        $filename=$this->getCacheUrl(__FUNCTION__,array($width,$height,$bgColor,$quality),$mime);
        $finalUrl=$this->cacheFolder()."/".$filename;

        if(self::$processImage && !file_exists($finalUrl)){
            // $positionX, $positionY & $position will have an impact on the layer position in the new box of 300px/300px (try another one !)
            $this->img()->resizeInPixel($width, $height, true, 0, 0, 'MM');
            $this->img()->save($this->cacheFolder(), $filename, true, $bgColor, $quality);
            $this->im=null;
        }
        return $finalUrl;
    }

    /**
     * @param int $width Width you want for the image
     * @param int $height Height you want for the image
     * @param string $bgColor Something like "#ff0000" or "transparent" (for png only).
     * @param int $quality the image quality between 0 and 100
     * @param string $mime Something like "jpg", "png" or "gif"
     * @return string Url of the produced image (note that image is processed once, after the file is in cache).
     */
    public function sizedNoBorder($width,$height,$bgColor="#000000",$quality=95,$mime="jpg"){
        $filename=$this->getCacheUrl(__FUNCTION__,array($width,$height,$bgColor,$quality),$mime);
        $finalUrl=$this->cacheFolder()."/".$filename;

            if(self::$processImage && !file_exists($finalUrl) ){
                $this->img()->resizeInPixel($width,null,true,0,0,"MM");
                if($this->img()->getHeight()<$height){
                    $this->im=null;
                    $this->img()->resizeInPixel(null,$height,true,0,0,"MM");
                }
                $this->img()->cropInPixel($width,$height,0,0,"MM");
                $this->img()->save($this->cacheFolder(), $filename, true, $bgColor, $quality);
                $this->im=null;
            }
        return $finalUrl;
    }
    public static $processImage=false;

    /**
     * Output the image with good header according the mime and die!
     * @param string $url Url of an image
     */
    public static function output($url){
        $file=new Francis($url);
        switch($file->extension()){
            case "jpg";
                header('Content-type: image/jpeg');
                break;
            case "png":
            default:
                header('Content-type: image/png');
        }
        readfile($url);
        die();
    }


























    //-----------classic stuff--------------------------



    /**
     * @return GinetteFileImageMeta Here you can get extra information for the image
     */
    public function meta(){
        $id=str_replace("/","-",$this->relativePath);
        $m=$this->db->getRecordInstance($id,"GinetteFileImageMeta",true);
        return $m;
    }

    public function __toString(){
        return "File Image ".$this->relativePath;
    }

    /**
     * @param string $url The relative url
     * @param GinetteDb $db The database where to search
     * @return bool|GinetteFileImage
     */
    public static function getByUrl($url,$db){
        return parent::getByUrl($url,$db);
    }


}
