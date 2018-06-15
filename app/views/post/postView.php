<form id="content" class="row display-flex justify-content-space-between" action="post.php?detail" method="POST">
    <p class='bg-danger text-white'><?php echo $_fromBean->getWarning();?></p>
    <input type="hidden" name="pollID" value="">
    <img id="leftImg" class="barImg col-sm-2" src="./static/images/leftImg.jpg" alt="leftImg">
    <main class="post-main col-sm-8">
        <section class="breadcrumbs">
            <ul class="display-flex">
                <li><a href="#" rel="nofollow">Forums</a></li>
                <li><a href="#" rel="nofollow"><?php echo $boardName?></a></li>
            </ul>
        </section>
        <section class="post-content">
            <div class="forum-section__top">
                <div class="forum-head-one">
                    <h1>  Recent topics on <?php echo $boardName?></h1>
                </div>
            </div>
            <div>
                <div class="forum-bar">
                    <ul class="paginate">
                        <li class="paginate__item paginate__results">87097 results</li>
                        <li class="paginate__item on"><a href="#" class="btn btn_page" data-original-title="">1</a></li>
                        <li class="paginate__item">
                            <a href="#" class="btn btn_page" data-original-title="">2</a>
                        </li>
                        <li class="paginate__item">
                            <a href="#" class="btn btn_page" data-original-title="">3</a>
                        </li>
                        <li class="paginate__item">
                            <a href="#" class="btn btn_page" data-original-title="">4</a>
                        </li>
                        <li class="paginate__item">
                            <a href="#" class="btn btn_page" data-original-title="">5</a>
                        </li>
                        <li class="paginate__item paginate__ellipse">...</li>
                        <li class="paginate__item">
                            <a href="#" class="btn btn_page" data-original-title="">1742</a>
                        </li>
                        <li class="paginate__item skip next">
                            <a href="#" class="btn btn_page" data-original-title="Next page">
                                <i>
                                    <svg version="1.1" width="28" height="28" viewBox="0 0 28 28" aria-hidden="true" aria-role="icon" class="symbol symbol-arrow-right">
                                        <path d="M23 15q0 0.844-0.578 1.422l-10.172 10.172q-0.609 0.578-1.422 0.578-0.797 0-1.406-0.578l-1.172-1.172q-0.594-0.594-0.594-1.422t0.594-1.422l4.578-4.578h-11q-0.812 0-1.32-0.586t-0.508-1.414v-2q0-0.828 0.508-1.414t1.32-0.586h11l-4.578-4.594q-0.594-0.562-0.594-1.406t0.594-1.406l1.172-1.172q0.594-0.594 1.406-0.594 0.828 0 1.422 0.594l10.172 10.172q0.578 0.547 0.578 1.406z">
                                        </path>
                                    </svg>
                                </i>
                            </a>
                        </li>
                    </ul>
                    <a href="#" class="btn btn-primary" rel="nofollow">Start a new topic</a>
                    <a href="createNewPoll.php?init" class="btn btn-primary" rel="nofollow">Start a new Poll</a>
                </div>
                <table class="table table-striped table-bordered table-forums">
                    <thead>
                    <tr>
                        <th>Topic</th>
                        <th>Poster</th>
                        <th>Posts</th>
                        <th>Views</th>
                    </tr>
                    <thead>
                    <tbody>
                    <?php

                    if(!is_null($pollRstBeanCollection)){
                         foreach ($pollRstBeanCollection as $pollRstBean){
                             $pollID = $pollRstBean->getPollID();

                             $question = $pollRstBean->getQuestion();

                             echo '<tr><td>';

                             echo "<a href=\"pollRst.php?dopollinit_$pollID\" class=\"js-topic-name topic-name tipsy\">$question</a>";

                             echo "<span class=\"type\">
                                            <i>
                                                <svg version=\"1.1\" width=\"28\" height=\"28\" viewBox=\"0 0 28 28\" aria-hidden=\"true\" aria-role=\"icon\" class=\"symbol symbol-tasks\">
                                                    <path d=\"M16 22h10v-2h-10v2zM10 14h16v-2h-16v2zM20 6h6v-2h-6v2zM28 19v4q0 0.406-0.297 0.703t-0.703 0.297h-26q-0.406 0-0.703-0.297t-0.297-0.703v-4q0-0.406 0.297-0.703t0.703-0.297h26q0.406 0 0.703 0.297t0.297 0.703zM28 11v4q0 0.406-0.297 0.703t-0.703 0.297h-26q-0.406 0-0.703-0.297t-0.297-0.703v-4q0-0.406 0.297-0.703t0.703-0.297h26q0.406 0 0.703 0.297t0.297 0.703zM28 3v4q0 0.406-0.297 0.703t-0.703 0.297h-26q-0.406 0-0.703-0.297t-0.297-0.703v-4q0-0.406 0.297-0.703t0.703-0.297h26q0.406 0 0.703 0.297t0.297 0.703z\">
                                                    </path>
                                                </svg>
                                            </i> Poll
                                        </span></td>";

                             $useName = $pollRstBean->getUsrRstBean()->getUsrName();
                             $postNum =  $pollRstBean->getPostNum();
                             $viewNum = $pollRstBean->getViewNum();

                             echo "<td>$useName</td>";

                             echo "<td>$postNum</td>";

                             echo "<td>$viewNum</td>";

                             echo '</tr>';
                     }

                    }
                    ?>
                    <tbody>
                </table>
                <div class="forum-bar">
                    <ul class="paginate">
                        <li class="paginate__item paginate__results">87097 results</li>
                        <li class="paginate__item on"><a href="#" class="btn btn_page" data-original-title="">1</a></li>
                        <li class="paginate__item">
                            <a href="#" class="btn btn_page" data-original-title="">2</a>
                        </li>
                        <li class="paginate__item">
                            <a href="#" class="btn btn_page" data-original-title="">3</a>
                        </li>
                        <li class="paginate__item">
                            <a href="#" class="btn btn_page" data-original-title="">4</a>
                        </li>
                        <li class="paginate__item">
                            <a href="#" class="btn btn_page" data-original-title="">5</a>
                        </li>
                        <li class="paginate__item paginate__ellipse">...</li>
                        <li class="paginate__item">
                            <a href="#" class="btn btn_page" data-original-title="">1742</a>
                        </li>
                        <li class="paginate__item skip next">
                            <a href="#" class="btn btn_page" data-original-title="Next page">
                                <i>
                                    <svg version="1.1" width="28" height="28" viewBox="0 0 28 28" aria-hidden="true" aria-role="icon" class="symbol symbol-arrow-right">
                                        <path d="M23 15q0 0.844-0.578 1.422l-10.172 10.172q-0.609 0.578-1.422 0.578-0.797 0-1.406-0.578l-1.172-1.172q-0.594-0.594-0.594-1.422t0.594-1.422l4.578-4.578h-11q-0.812 0-1.32-0.586t-0.508-1.414v-2q0-0.828 0.508-1.414t1.32-0.586h11l-4.578-4.594q-0.594-0.562-0.594-1.406t0.594-1.406l1.172-1.172q0.594-0.594 1.406-0.594 0.828 0 1.422 0.594l10.172 10.172q0.578 0.547 0.578 1.406z">
                                        </path>
                                    </svg>
                                </i>
                            </a>
                        </li>
                    </ul>
                    <a href="#" class="btn btn-primary" rel="nofollow">Start a new topic</a>
                    <a href="createNewPoll.php?init" class="btn btn-primary" rel="nofollow">Start a new Poll</a>
                </div>
            </div>
        </section>
    </main>
    <img id="rightImg" class="barImg col-sm-2" src="./static/images/rightImg.jpg" alt="rightImg">
</form>

<button id="alertID" type="button" style="display:none" data-toggle="modal" data-target="#exampleModalCenter">
    Alert
</button>
<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Hello</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-primary">
                    Login successfully! Your passwords is weak. We suggest you to modify the password!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">OK! Got It</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if(isset($_SESSION[ 'isWeakPwd' ])){
    //var_dump($_SESSION);
    $isWeakPwd = $_SESSION[ 'isWeakPwd' ];
    $isDisplayed = $_SESSION[ 'isDisplayed' ];
    if($isWeakPwd == "true" && ($isDisplayed == "false")){
        //var_dump('in');
        echo '<script type="text/javascript">';
        echo 'window.onload = function(){document.getElementById("alertID").click()}';
        echo '</script>';
        $_SESSION[ 'isDisplayed' ] = "true";
    }

}

?>