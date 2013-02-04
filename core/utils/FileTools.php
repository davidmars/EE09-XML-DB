<?php
/**
 * User: juliette david
 * Date: 03/02/13
 * Time: 18:33
 * To change this template use File | Settings | File Templates.
 */
class FileTools
{
    /**
     * Renvoie le type mime d'un fichier
     * @param string $path Le chemin du fichier
     * @return string Un type mime ( exemple : image/jpeg , etc )
     */
    public static function mime( $path ) {

        if( function_exists("finfo_file") ) {
            $f = new finfo(FILEINFO_MIME_TYPE);
            return $f->file( $path );

        }elseif( function_exists( "mime_content_type" ) ) {
            return mime_content_type( $path );
        }else {
            //trace("ola");
            $type = exec("file -bi '".escapeshellarg( $path )."'");
            if( $type !== false ) {
                return $type;
            }
        }

    }
    /**
     * Renvoie le nom d'un fichier, sans son extension et sans son dossier
     *
     * @param string $path Chemin du fichier
     * @return string Nom du fichier
     */
    public static function filename( $path ) {
        return pathinfo( $path , PATHINFO_FILENAME );
    }

    /**
     * Renvoie le chemin du dossier dans lequel se trouve le fichier
     *
     * @param string $path Chemin du fichier
     * @return string Chemin du dossier
     */
    public static function dirname( $path ) {
        return pathinfo( $path , PATHINFO_DIRNAME );
    }

    /**
     * Renvoie le nom de base du fichier (avec extension)
     *
     * @param string $path Chemin du fichier
     * @return string Nom de base du fichier
     */
    public static function basename( $path ) {
        return pathinfo( $path , PATHINFO_BASENAME );
    }

    /**
     * Renvoie l'extension du fichier
     *
     * @param string $path Chemin du fichier
     * @return string Extension
     */
    public static function extension( $path ) {
        return pathinfo( $path , PATHINFO_EXTENSION );
    }

    public static function pathname( $path ){
        return substr( $path , 0 , strrpos( $path ,  '/' ) );
    }
    /**
     * Convertit une taille en octets en une taille approximative en Mo, Go, ...
     *
     * @param int $size Taille en octets
     * @param string $b Abbréviation de "byte"
     */
    public static function humanSize( $bytes , $b = "B",$precision=0 ) {
        $units = array("$b", "K$b", "M$b", "G$b", "T$b");

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];

    }

    public static function size( $path,$human=false ){
        if($human){
            return self::humanSize( filesize( $path ),"B",2 );
        }
        return filesize( $path );
    }

    /**
     *	retourne un tableau plat contenant les fichiers contenus dans $dir
     * @param String $dir répertoire à lister
     * @param Boolean $recursive récursion dans les sous dossiers ou pas
     * @return Array tableau contenant les url des fichiers classés par ordre alphabétique
     */
    public static function listDir($dir,$recursive){
        $handle = opendir($dir);
        $files=array();
        while (($file = readdir($handle)) !== false) {
            $completePath=$dir."/".$file;
            if($file != "." && $file !=".."){
                if (is_file($completePath)) {
                    array_push($files, $completePath);
                }
                if($recursive && is_dir($completePath)){
                    $files=array_merge($files, self::listDir($dir."/".$file, true));
                }
            }
        }
        sort($files);
        return $files;
    }
    /**
     * Crée les répertoires et sous répertoire contenant $fileUrl
     * @param String $fileUrl url complete du fichier dont il faut éventuellement créer les répertoires conteneurs
     */
    static function mkDirOfFile($fileUrl){
        $splitted=explode("/",$fileUrl);
        $dir="";
        while(count($splitted)>1){
            $newFolder=array_shift($splitted);
            $dir=$dir.$newFolder;
            if(!is_dir($dir)){
                $r=@mkdir( $dir , 0777 , true );

                if(!$r){
                    return false;
                }else{
                    chmod($dir, 0777);
                }
            }

            $dir.="/";
        }
        return true;
    }
}
