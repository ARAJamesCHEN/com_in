<?php
/**
 * Created by PhpStorm.
 * User: yac0105
 * Date: 12/06/2018
 * Time: 4:18 PM
 */

namespace app\controllers;


use app\controllers\forward\ForwardController;
use comphp\base\Controller;
use app\controllers\formbeans\PollRstFormBeanFactory;
use app\models\modelInterface\PollAndOptionsUnionService;
use app\models\modelInterface\PollAndOptionUnionServiceImpl;
use app\models\modelInterface\PollOptionService;
use app\models\modelInterface\PollOptionServiceImpl;
use comphp\base\RstBean;
use comphp\base\Handler;

include(APP_PATH . 'app/controllers/formbeans/'.'PollRstFormBean.php');

CONST POLL_INIT = "doinit";
const POLL_RST_INIT = 'inti_rst';
const POLL_SUBMIT_VOTE = 'submit_vote';

const POLL_PAGE = "POLL";
CONST POLL_RST_PAGE = "RST";

class PollRstController extends ForwardController
{

    private $formBean;

    private $pollRstBean;

    private $pollOptionRstBeanCollection;

    public function init()
    {
        $this->formBean = PollRstFormBeanFactory::create();

        //$pollID = 1;

        if(strrpos($this->_actionName,"pollinit")>0){

            //echo 'haha';

            $begin = strrpos($this->_actionName,"_") + 1;

            $pollID = substr($this->_actionName,$begin);

            $this->formBean->setPageStatus(POLL_PAGE);
            $this->formBean->setPollId($pollID);
            $this->displayPollDetail($pollID);

        }elseif(POLL_RST_INIT == $this->_actionName){
            $this->formBean->setPageStatus(POLL_RST_PAGE);
            if(isset($_SESSION['PollId'])){
                $pollID = $_SESSION['PollId'];
            }

            if(!empty($pollID)){

                $this->formBean->setPollId($pollID);

                $this->displayPollDetail($pollID);


            }else{
                throw new \Exception("Cannot get PollID!");
            }
        }elseif (POLL_SUBMIT_VOTE == $this->_actionName){

            if($this->forwardToLogin()){return;}

            $this->formBean->setPageStatus(POLL_PAGE);

            if(!$this->isValidate()){
                $result = $this->submitVote($this->formBean);

                if($result){
                    $this->formBean->setPageStatus(POLL_RST_PAGE);
                }
            }

            $this->displayPollDetail($this->formBean->getPollId());

            $_SESSION['PollId'] = $this->formBean->getPollId();

            header( 'Location:pollRst.php?inti_rst') ;

        }else{
            $this->formBean->setWarning('Please select a poll first!');
            header( 'Location:post.php' ) ;
        }


        $this->assign("formBean", $this->formBean);
        $this->assign("pollRstBean", $this->pollRstBean);
        $this->assign("pollOptionRstBeanCollection", $this->pollOptionRstBeanCollection);
        $this->render();
    }

    private function submitVote(&$formBean){

        $rst = new RstBean();

        if($formBean->getOptionType() == 'S'){
            $rst = (new Handler())->callPollOptionService(new PollOptionServiceImpl())->updatePollOptionsVotedNumByID($formBean->getPollOptionId());
        }elseif ($formBean->getOptionType() == 'M'){

            $optionSelected = $formBean->getOptionSelected();

            //var_dump($optionSelected);

            try {
                $rst = (new Handler())->callPollOptionService(new PollOptionServiceImpl())->updatePollOptionsVotedNumByIDWithCollection($optionSelected);
            } catch (\Exception $e) {

                $formBean->setWarning($e->getMessage());

            }

        }



        if(!$rst->isSuccess()){
            $formBean->setWarning('Fail to update the vote!');
        }

        return $rst->isSuccess();


    }

    private function displayPollDetail($pollID){

        //var_dump($pollID);

        $rslt = (new Handler())->callPollAndOptionsUnionService(new PollAndOptionUnionServiceImpl())->searchPollWithOptionByID($pollID);

        if(is_null($rslt)){
            $this->formBean->setWarning('Fail to find the result');
            return;
        }

        //var_dump($rslt);

        $result = $rslt->getResult();

        //var_dump($rslt);

        $this->pollRstBean = $result[0];
        $this->pollOptionRstBeanCollection = $result[1];
        $this->formBean->setOptionType($this->pollRstBean->getOptionType());


    }

    private function isValidate(){

        $hasIssue = false;

        $pollID = null;

        if(isset($_POST["pollID"])){
            $pollID = $_POST["pollID"];
        }

        //var_dump($_POST);

        if(is_null($pollID) || empty($pollID)){
            $this->formBean->setWarning("Lost PollID!");
        }else{

            if(!is_numeric($pollID)){
                $this->formBean->setWarning("Not a right poll id!");
                $hasIssue = true;
            }

            $this->formBean->setPollId($pollID);
        }

        $optionType = null;

        if(isset($_POST["optionType"])){
            $optionType = $_POST["optionType"];
        }

        //var_dump($_POST);

        if(is_null($optionType) || empty($optionType)){
            $this->formBean->setWarning("Cannot get option type!");
            $hasIssue = true;
        }else{

            if(!is_string($optionType)){
                $this->formBean->setWarning("Not a right option type!");
                $hasIssue = true;
            }else{

                $this->formBean->setOptionType($optionType);

            }


        }


        if($optionType == 'M'){

            $optionCollectionPost = null;

            if(isset($_POST["optionCollection"])){
                $optionCollectionPost = $_POST["optionCollection"];
            }

            if(!is_null($optionCollectionPost)){

                $optionCollectionPostArray = explode(",",$optionCollectionPost);

                $optionSelected = array();

                //var_dump($optionCollectionPostArray);

                foreach ($optionCollectionPostArray as $optionName){

                    if(isset($_POST["$optionName"]) && !empty($_POST["$optionName"])){

                        $selectedOptionID = $_POST["$optionName"];

                        array_push($optionSelected, $selectedOptionID);

                    }
                }

                //var_dump($optionSelected);

                if(count($optionSelected)>0){
                    $this->formBean->setOptionSelected($optionSelected);
                }else{
                    $hasIssue = true;
                    $this->formBean->setWarning("Please select at least one option!");
                }


            }else{
                $hasIssue = true;
                $this->formBean->setWarning("Cannot get option collection!");
            }



        }else{
            $pollOption = null;

            if(isset($_POST["poll_option"])){
                $pollOption = $_POST["poll_option"];
            }

            //var_dump($_POST);

            if(is_null($pollOption) || empty($pollOption)){
                $this->formBean->setWarning("Please select the option!");
                $hasIssue = true;
            }else{

                if(!is_numeric($pollOption)){
                    $this->formBean->setWarning("Not a right option!");
                    $hasIssue = true;
                }

                $this->formBean->setPollOptionId($pollOption);
            }
        }




        return $hasIssue;
    }
}

