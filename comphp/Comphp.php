<?php
namespace comphp;

const CORE_PATH = __DIR__;

class Comphp
{
    protected $config = [];

    protected $langFile = 'messages.ini';

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function run()
    {
        spl_autoload_register(array($this, 'loadClass'));
        $this->setReporting();
        $this->removeXssQuotes();
        $this->removeMagicQuotes();
        $this->unregisterGlobals();
        $this->defenseSQLInjection();
        $this->setDbConfig();
        $this->setLocale();
        $this->loadLangMessage();
        $this->route();
    }

    /**
     * www.test.com/controllerName/actionName/queryString
     * ：yoursite.com/item/detail/1/hello
     *
     * www.test.com/controllerName.php
     *
     * yoursite.com/login/init
     */
    public function route(){
        $controllerName = $this->config['defaultController'];

        $viewName  = $this->config['defaultView'];

        $actionName  = $this->config['defaultAction'];

        $url = $_SERVER['REQUEST_URI'];

        $position = strpos($url, '?');

        $url = $position === false ? $url : substr($url, 0, $position);

        $url = trim($url, '/');

        $input = $_SERVER['QUERY_STRING'];

        if(!empty($input)){
            $actionName = $input;
        }

        if ($url) {
            $urlArray = explode('/', $url);

            $urlArray = array_filter($urlArray);

            //var_dump($urlArray);

            //www.test.com/controllerName.php
            $size = count($urlArray);

            $phpFileName = ucfirst($urlArray[$size-1]);

            $pos = strrpos($phpFileName, ".");

            $controllerName = substr($phpFileName, 0 ,$pos);

            if(strtolower($controllerName) == 'index' || strtolower($controllerName) == 'comphp'){
                $controllerName = "Login";
            }else{
                $begStr = substr($controllerName, 0, 1);
                $endStr = substr($controllerName, 1);

                $controllerName = strtoupper($begStr).$endStr;

                //echo $controllerName;

            }

            array_shift($urlArray);

            $viewName = strtolower($controllerName);
        }

        $controller = 'app\\controllers\\'. $controllerName . 'Controller';

        if (!class_exists($controller)) {
            //exit($controller . ' [Controller is not exist!]');
            header( "Location:init.php" ) ;
        }else{
            new $controller($controllerName, $viewName, $actionName);
        }

    }

    public function setReporting()
    {
        if (APP_DEBUG === true) {
            error_reporting(E_ALL);
            ini_set('display_errors','On');
        } else {
            error_reporting(E_ALL);
            ini_set('display_errors','Off');
            ini_set('log_errors', 'On');
        }
    }

    public function setLocale(){
        $allow_lang = array('en', 'in');

        // echo $_SESSION['lang'];

        if(isset($_GET['lang']) == true && in_array($_GET['lang'], $allow_lang)){
            //echo 'here1';
            $_SESSION['lang'] = $_GET['lang'];
        }else if(isset($_SESSION['lang']) && in_array($_SESSION['lang'], $allow_lang)){
            //echo 'here2';
        }else{
            //echo 'here3';
            $_SESSION['lang'] = 'in';
        }
        //echo $_SESSION['lang'];

    }

    public function loadLangMessage(){
        $lang = $_SESSION['lang'];

        //var_dump(CORE_PATH);

        $ini_array = parse_ini_file( CORE_PATH.'\\lang\\'.$this->langFile, true);


        define('_LANG', $ini_array);

        //var_dump(_LANG['en']['logoTitle']);

        //echo _LANG['en']['logoTitle'];

        //echo _LANG['in']['logoTitle'];

        //var_dump(_LANG['in']['logoTitle']);

    }


    /**
     * defense for XSS
     */
    public function removeXssQuotes(){

        $_GET = isset($_GET) ? $this->cleanXssPrepare($_GET ) : '';
        $_POST = isset($_POST) ? $this->cleanXssPrepare($_POST ) : '';
        $_COOKIE = isset($_COOKIE) ? $this->cleanXssPrepare($_COOKIE) : '';
        $_SESSION = isset($_SESSION) ? $this->cleanXssPrepare($_SESSION) : '';

    }

    /**
     * defense for XSS
     * @param $value
     * @return array|mixed|null|string|string[]
     */
    public function cleanXssPrepare($value){
        $value = is_array($value) ? array_map(array($this, 'cleanXssStringQuotes'), $value) : $this->cleanXssStringQuotes($value);
        //var_dump($value) ;
        return $value;
    }

    /**
     * defense for XSS
     * @param $string
     * @return mixed|null|string|string[]
     */
    public function cleanXssStringQuotes($string){

        if(!is_array($string)){
            $string = trim($string);
            $string = strip_tags($string);
            $string = htmlspecialchars($string, ENT_QUOTES);
            $string = str_replace (array ('"', "\\", "'", "/", "..", "../", "./", "//" ), '', $string);
            $no = '/%0[0-8bcef]/';
            $string = preg_replace ($no, '', $string);
            $no = '/%1[0-9a-f]/';
            $string = preg_replace ($no, '', $string);
            $no = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';
            $string = preg_replace ($no, '', $string);
        }

        return $string;

    }


    /**
     * defense for MagicQuotes
     * @param $value
     * @return array|string
     */
    public function stripSlashesDeep($value)
    {
        $value = is_array($value) ? array_map(array($this, 'stripSlashesDeep'), $value) : stripslashes($value);
        return $value;
    }

    /**
     * defense for MagicQuotes
     */
    public function removeMagicQuotes()
    {
        if(get_magic_quotes_gpc()){
            $_GET = isset($_GET) ? $this->stripSlashesDeep($_GET ) : '';
            $_POST = isset($_POST) ? $this->stripSlashesDeep($_POST ) : '';
            $_COOKIE = isset($_COOKIE) ? $this->stripSlashesDeep($_COOKIE) : '';
            $_SESSION = isset($_SESSION) ? $this->stripSlashesDeep($_SESSION) : '';
        }

    }

    /**
     * 1. remove magic quotes
     * 2. addSlashes
     * 3. bind para query * strong
     */
    public function defenseSQLInjection(){
        if(!get_magic_quotes_gpc()){
            $_GET = isset($_GET) ? $this->doSthToDefenseSQLInject($_GET ) : '';
            $_POST = isset($_POST) ? $this->doSthToDefenseSQLInject($_POST ) : '';
            $_COOKIE = isset($_COOKIE) ? $this->doSthToDefenseSQLInject($_COOKIE) : '';
            $_SESSION = isset($_SESSION) ? $this->doSthToDefenseSQLInject($_SESSION) : '';
        }
    }

    /**
     * https://blog.csdn.net/u011781769/article/details/48470759
     * @param $value
     * @return mixed|string
     */
    public function doSthToDefenseSQLInject($value){

        //$value = is_array($value) ? array_map(array($this, 'addSlashesToStr'), $value) : addslashes($value);

        if(is_array($value)){
            array_map(array($this, 'doSthToDefenseSQLInject'), $value);
        }else{
            $value = addslashes($value);

            $value = str_replace("_", "\_", $value);
            $value = str_replace("%", "\%", $value);
            $value = nl2br($value);
        }

        return $value;

    }

    //http://php.net/manual/zh/faq.using.php#faq.register-globals
    public function unregisterGlobals()
    {
        if (ini_get('register_globals')) {
            $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
            foreach ($array as $value) {
                foreach ($GLOBALS[$value] as $key => $var) {
                    if ($var === $GLOBALS[$key]) {
                        unset($GLOBALS[$key]);
                    }
                }
            }
        }
    }


    public function setDbConfig()
    {
        if ($this->config['db']) {
            define('DB_HOST', $this->config['db']['host']);
            define('DB_NAME', $this->config['db']['dbname']);
            define('DB_USER', $this->config['db']['username']);
            define('DB_PASS', $this->config['db']['password']);
            define('DB_SYS', $this->config['db']['sys']);
        }
    }

    public function loadClass($className)
    {
        $classMap = $this->classMap();

        if (isset($classMap[$className])) {
            $file = $classMap[$className];
        } elseif (strpos($className, '\\') !== false) {
            $file = APP_PATH . str_replace('\\', '/', $className) . '.php';
            if (!is_file($file)) {
                return;
            }
        } else {
            return;
        }

        include $file;

    }

    protected function classMap()
    {
        return [
            'comphp\base\Controller' => CORE_PATH . '/base/Controller.php',
            'comphp\base\Model' => CORE_PATH . '/base/Model.php',
            'comphp\base\View' => CORE_PATH . '/base/View.php',
            'comphp\db\Db' => CORE_PATH . '/db/Db.php',
            'comphp\db\Sql' => CORE_PATH . '/db/Sql.php',
            'comphp\db\base' => CORE_PATH . '/db/FormBean.php',
             'comphp\lang' => CORE_PATH . '/lang/internationalization.php'
        ];
    }

}

