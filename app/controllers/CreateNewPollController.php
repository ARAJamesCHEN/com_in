<?php
/**
 * Created by PhpStorm.
 * User: James
 * Date: 2018/6/10
 * Time: 18:25
 */

namespace app\controllers;
use app\controllers\formbeans\CreateNewPollFormBean;
use app\controllers\formbeans\CreateNewPollFormBeanFactory;
use app\controllers\forward\ForwardController;
use app\models\modelbusiness\modelutils\ModelUtil;
use app\models\modelInterface\PollAndOptionUnionServiceImpl;
use app\models\modelInterface\PollModelService;
use comphp\base\Controller;
use app\models\modelInterface\PollAndOptionsUnionService;
use comphp\base\Handler;


include(APP_PATH . 'app/controllers/formbeans/'.'CreateNewPollFormBean.php');

define('NEWPOLL_ACTION_INIT', "init" );

define('NEWPOLL_ACTION_ADD', "add" );

/**
 * Class CreateNewPollController
 * @package app\controllers
 */
class CreateNewPollController extends ForwardController
{
    private $boards;

    private $formBean;

    public function init()
    {

        if($this->forwardToLogin()){return;}

        $this->formBean = CreateNewPollFormBeanFactory::create();

        $this->boards = $this->_formbean->getBoards();

        if ($this->_actionName == NEWPOLL_ACTION_ADD){

            $this->addNewPollFunction();

        }

        $this->assign('boards', $this->boards);
        $this->assign('formBean', $this->formBean);
        $this->render();

    }

    /**
     *
     */
    private function addNewPollFunction(){

        if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {

            $this->validate($this->formBean);

            if($this->formBean->isHasError()){

                return;

            }

            $pollModelVO = (new ModelUtil())->getPollModelVO($this->formBean);

            //var_dump($pollModelVO);

            $options = $this->formBean->getOptions();

            try {
                $rslt = (new Handler())->callPollAndOptionsUnionService(new PollAndOptionUnionServiceImpl())->addNewPollWithOption($pollModelVO, $options);
            } catch (\Exception $e) {

                $this->formBean->setWarning("Insert Failure!".$e->getMessage());

            }

            if(!$rslt->isSuccess()){
                $this->formBean->setWarning("Insert Failure!");
                return;
            }else{
                $this->formBean = CreateNewPollFormBeanFactory::create();
            }


            $thePollID = $rslt->getResult();

            header("Location:pollRst.php?dopollinit_$thePollID" );


        }

    }

    /**
     * validate
     * @param CreateNewPollFormBean $formBean
     */
    private function  validate(CreateNewPollFormBean &$formBean){

        if(is_null($formBean)){
            return;
        }

        $selectedBoardId = $_POST['boardID'];

        if (!filter_var(intval($selectedBoardId), FILTER_VALIDATE_INT)) {
            $formBean->setHasError(true);
            $formBean->setWarning("Not a legal board ID");
        }


        $formBean->setSelectedBoard($selectedBoardId);

        $question = $_POST['question'];

        $question = is_null($question)? '' : trim($question);

        /**
         * cannot check illegal symobal only transformation, that is done in comphp.php
         */
        if(strlen($question)>500){

            $formBean->setHasError(true);
            $formBean->setTitleWarning("No more than 500 characters");

        }elseif (strlen($question)<1){
            $formBean->setHasError(true);
            $formBean->setTitleWarning("At leasr one character or you input some illegal character and we delete them!");
        }else{

            $formBean->setQuestion($question);

        }

        $option1 = $_POST['option1'];
        $option2 = $_POST['option2'];
        $option3 = $_POST['option3'];
        $option4 = $_POST['option4'];
        $option5 = $_POST['option5'];

        if(empty($option1) && empty($option2) &&  empty($option3) && empty($option4) && empty($option5)){
            $formBean->setHasError(true);
            $formBean->setOptionWarning("Please input at least one option");
        }else{

            $options = [$option1,$option2,$option3,$option4, $option5];
            $optionsData = array();

            foreach ($options as $option){
                if(!is_null($option) && !empty($option)){
                    array_push($optionsData, trim($option));
                }
            }

            $formBean->setOptions($optionsData);

        }

        $isMutilChoice = false;

        if(isset($_POST['multi_choice'])){
            $isMutilChoice = $_POST['multi_choice'];

            if (!filter_var($isMutilChoice, FILTER_VALIDATE_BOOLEAN)) {
                $formBean->setHasError(true);
                $formBean->setIsMutiWarning("Sorry, you have entered an incorrect multiple choice");
            }else{
                $formBean->setIsMultiple(true);
            }


        }else{
            $formBean->setIsMultiple(false);
        }

        //var_dump($_POST);
        $content = $_POST['content'];

        if(strlen($content)>2000){
            $formBean->setHasError(true);
            $formBean->setContentWarning("No more than 2000 characters");
        }else{
            $formBean->setContent($content);
        }



    }
}