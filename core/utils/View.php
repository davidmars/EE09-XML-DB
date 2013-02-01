<?php

    /**
     * Description the view system
     *
     * @author david marsalone
     */
    class View {

        /**
         * @var $_view View the view inside the template
         */

        /**
         *
         * @var ViewVariables Here are the variables used in the view.
         * Inside the template use $_vars to retrieve it.
         * It's an object, so it should be strict.
         */
        public $viewVariables;
        /**
         *
         * @var string Path to the template whithout view folder and without ".php".
         * @example a templates located at "_app/mvc/v/a-folder/hello-world.php" should be "a-folder/hello-world"
         */
        public $path;

        /**
         *
         * @var bool Will be true if the view is loaded via an ajax request.
         */
        public $isAjax=false;
        /**
         * @var View the View object that called the current view via render or inside...so it can be null too.
         */
        public $caller;
        /**
         *
         * @var string Contains the sub-templates that have called the inside function. So this will be set only if the current view is a kind of layout.
         */
        public $insideContent;
        /**
         *
         * @var View a view outside this view, in practice this view is a layout
         */
        private $outerView;

        /**
         * Constructeur
         *
         * @param string $path Chemin de la vue
         * @param mixed $viewVariables
         * @internal param string $theme Theme de la vue
         */
        public function __construct( $path,$viewVariables=null ){
            $this->path = $path;
            if(!$viewVariables){
                $viewVariables=new ViewVariables();
            }
            $this->viewVariables=$viewVariables;
        }
        /**
         * Try to return a valid path for a template file.
         * @param string $path a relative path to the template file without .php
         * @return string|bool the correct path or false if there is no file that match.
         */
        private static function getRealPath($path){
            foreach (self::$rootPaths as $root){
                $scriptPath = $root."/".$path.".php";
                if(file_exists($scriptPath)){
                    return $scriptPath;
                }
            }
            return false;


        }

        /**
         * @var array Here are the root paths to include views. Each time you will render a view it will be used to search for the template php file.
         */
        public static $rootPaths=array();

        /**
         * Process the template with the current properties.
         *
         * @return string Le template généré
         */
        private function run(){

            $scriptPath = self::getRealPath($this->path);

            if(!$scriptPath){
                $mess="";
                foreach (self::$rootPaths as $root){
                    $mess.="<div style='font-size:12px;color:#f00;'>
                    Can't find the template : <span style='color:#666;'>".$root."</span>/".$this->path."<span style='color:#666;'>.php</span></div>";
                    if($this->caller){
                        $mess.="<div style='font-size:12px;color:#f00;'>
                    ( called in ".$this->caller->path." )
                    </div>";
                    }
                }

                return $mess;
            }




            //declare the variables in the template
            /* @var $_vars ViewVariables */
            $_vars=$this->viewVariables;
            $view=$this;
            $_content=$this->insideContent;

            $_view=$this;

            ob_start();
            include $scriptPath;
            $content = ob_get_contents();
            ob_end_clean();
            if($this->outerView){
                $this->outerView->insideContent=$content;
                return $this->outerView->run();
            }else{
                return $content;
            }
        }


        /**
         * Process the template and return the result.
         * @param string|null $path
         * @param mixed $viewVariables Object that will feed the view template
         * @internal param String $view path to the template to execute or insert.
         * @return String The template result after execution
         */
        function render( $path=null , $viewVariables=null ){

            $viewVariables=isset($viewVariables) ? $viewVariables : $this->viewVariables;
            if($path){
                $view = new View($path,$viewVariables);
                $view->caller=$this;
                return $view->run();
            }else{
                $this->viewVariables=$viewVariables;
                return $this->run();
            }

        }




        /**
         * Insert the current template inside an other template.
         * In the layout template use the variable $_content to display the current template.
         * @param String $path path to the template file
         * @param mixed $viewVariables the data object given to the outer view, if not given, the object will be the current strictParams
         */
        function inside( $path, $viewVariables=null ){
            $viewVariables=$viewVariables ? $viewVariables : $this->viewVariables;
            $this->outerView = new View($path, $viewVariables);
            $this->outerView->caller=$this;
        }

        /**
         *
         * @param string $path
         * @return bool will be true if $path is a valid template url.
         */
        public static function isValid($path){
            if(self::getRealPath($path)){
                return true;
            }else{
                return false;
            }
        }

    }
