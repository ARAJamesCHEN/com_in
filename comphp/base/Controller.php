<?php
/**
 * Created by PhpStorm.
 * User: yac0105
 * Date: 1/06/2018
 * Time: 6:02 PM
 */
namespace comphp\base;

use app\models\modelInterface\BoardModelService;
use app\models\modelInterface\BoardModelServiceImpl;
/**
 * Class Controller
 * @package comphp\base
 */
abstract class Controller
{
    protected $_controller;
    protected $_viewName;
    protected $_actionName;
    protected $_view;
    protected $_usrId;
    protected $_formbean;

    public function __construct($controller, $viewName, $actionName)
    {
        $this->_controller = $controller;
        $this->_viewName = $viewName;
        $this->_view = new View($controller, $viewName);
        $this->_actionName = $actionName;
        $this->assignFormBean();
        $this->init();


    }

    public function assignFormBean(){
        $this->_formbean = new FormBean();
        $this->initViewParasFunction(new BoardModelServiceImpl());
        $this->assign('_fromBean', $this->_formbean);
    }

    public abstract function init();

    private function initViewParasFunction(BoardModelService $boardModelService){

        $rslt = $boardModelService->searchAllBoard();

        $boardInfos = $rslt->getResult();

        if ( $boardInfos  && $boardInfos->size()>0 ){

            $boardArray = array();

            $rows = $boardInfos->fetchAll();

            //var_dump($rows);

            foreach ($rows as $key=>$aRow){

                $boardKey = $aRow['boardID'];
                $boardValue = $aRow['boardName'];

                $boardArray[$boardKey] = $boardValue;

            }

            $this->_formbean->setBoards($boardArray);

        }

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