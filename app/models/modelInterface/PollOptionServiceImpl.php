<?php
/**
 * Created by PhpStorm.
 * User: yac0105
 * Date: 12/06/2018
 * Time: 3:16 PM
 */

namespace app\models\modelInterface;


use app\models\modelbusiness\modelEntity\PollOptionModel;
use app\models\modelbusiness\modelVOs\PollOptionModelVO;
use comphp\base\RstBean;
use app\models\modelbusiness\modelRstBean\PollOptionsRstBean;
use app\models\modelbusiness\modelutils\ModelUtil;

class PollOptionServiceImpl implements PollOptionService
{


    public function addNewPollOptions(PollOptionModelVO $pollOptionModelVO)
    {
        // TODO: Implement addNewPollOptions() method.
        $rst = new RstBean();


        $pollOptionModel = new PollOptionModel();

        $insert_id = $pollOptionModel->addNewPollOptions($pollOptionModelVO);

        if($insert_id){
            $rst->setIsSuccess(true);
        }else{
            $rst->setIsSuccess(false);
        }

        return $rst;


    }

    public function searchPollOptionsByPollID($pollID)
    {
        // TODO: Implement searchPollOptionsByPollID() method.
        $rst = new PollOptionsRstBean();

        $pollOptionModel = new PollOptionModel();

        $result = $pollOptionModel->searchPollOptionsByPollID($pollID);

        $pollRstBeanCollection = (new ModelUtil())->getPollOptionRstBean($result);

        if(is_null($result) || !$result){
            $rst->setIsSuccess(false);
        }else{
            $rst->setIsSuccess(true);
            $rst->setResult($pollRstBeanCollection);
        }

        return $rst;

    }
}