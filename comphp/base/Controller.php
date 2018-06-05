<?php
/**
 * Created by PhpStorm.
 * User: yac0105
 * Date: 1/06/2018
 * Time: 6:02 PM
 */
namespace comphp\base;

/**
 * Class Controller
 * @package comphp\base
 */
class Controller
{
    protected $_controller;
    protected $_viewName;
    protected $_actionName;
    protected $_view;

    public function __construct($controller, $viewName, $actionName)
    {
        $this->_controller = $controller;
        $this->_viewName = $viewName;
        $this->_view = new View($controller, $viewName);
        $this->_actionName = $actionName;
        $this->init();
        $this->render();

    }

    public function init(){


    }

    public function assign($name, $value)
    {
        $this->_view->assign($name, $value);
    }

    public function render()
    {
        $this->_view->render();
    }

}