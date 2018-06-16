 <main class="post-main">
     <p class='bg-danger text-white'><?php echo $formBean->getWarning();?></p>
       <section class="breadcrumbs">
			<ul class="display-flex">
				<li><a href="#" rel="nofollow">Forums</a></li>
				<li><a href="<?php
                    $boardID =  $pollRstBean->getBoardID();
                    echo "post.php?doboard_$boardID"?>" rel="nofollow"><?php echo $pollRstBean->getBoardName() ?></a></li>
			</ul>
		</section>
		<section class="post-content">
			<div class="forum-section__top">
		        <div class="forum-head-one">
		            <h1> <?php echo $pollRstBean->getQuestion()?></h1>
			    </div>
			</div>
			<div>
				<div class="forum-bar">
                    <a href="#" class="btn" rel="nofollow">Reply to this topic</a>
                </div>

				<div>
					<section class="forum-messages">
						<div id="post1" class="message">
						    <div class="avatar-user js-avatar-user" data-user-slug="james" data-user-profile="#">
							    <div class="avatar-user__container">
								     <a href="#" class="avatar">
									     <div class="img">
										     <img alt="Avatar image for James" src="./static/images/user.jpg">
										 </div>
									</a>
									<dl class="user-info hide-mobile">
									    <dt class="user-info__name">
										    <a href="#"><?php echo $pollRstBean->getUsrRstBean()->getUsrName()?></a>
										</dt>
										<dd class="user-info__item">
										</dd>
										<dd class="user-info__item">
										    <span>Member Since:</span> December 1, 2017
                                        </dd>
										<dd class="user-info__item">
										    <span>Posts:</span> <?php echo $pollRstBean->getPostNum() ?>
										</dd>
									</dl>
								</div>
							</div>

							<div class="message-wrap">
								<div class="message-inner">
									<div class="message-title">
                                        Posted by <a href="#"><?php echo $pollRstBean->getUsrRstBean()->getUsrName();?></a>(<?php echo $pollRstBean->getPostNum() ?> posts)
										<span class="icon icon-time"> <?php echo $pollRstBean->getPostDateDisplay() ?></span>
									</div>
									<div class="message-breadcrumb"></div>
									<div class="message-content">
										<article class="content-body message-body js-message-body typography-format">
											<div class="poll">
												<div class="poll-header">
													<h3>
														<span>
															<i class="icon icon-align-left"></i>
                                                             Poll:
														</span>
                                                        <?php echo $pollRstBean->getQuestion()?> (<?php echo $pollRstBean->getTotalVotedNum() ?> votes)
													</h3>
												</div>
												<form class="js-poll-form" data-ajaxform="true" action = "pollRst.php?submit_vote" method="POST">

                                                    <input type="hidden" name="pollID" value="<?php echo $formBean->getPollId() ?>">
                                                    <input type="hidden" name="optionType" value="<?php echo $formBean->getOptionType() ?>">

                                                    <?php

                                                       $optionIDs = '';

                                                       if($formBean->getPageStatus() != "POLL"){
                                                           $count = 0;

                                                           foreach($pollOptionRstBeanCollection as $optionValue => $option){

                                                               //var_dump($option);
                                                               $optionName = $option->getOptionName();
                                                               $votePercentage = $option->getVotedPercentage();

                                                               //$votePercentage = 50;
                                                               echo "<div>$optionName    $votePercentage%</div>";


                                                               if($count%2 == 0){
                                                                   echo "<div class='progress progress-success'>";
                                                                   $count++;
                                                               }else{
                                                                   echo "<div class='progress'>";
                                                                   $count--;
                                                               }
                                                               echo "<div class='bar' style='width: $votePercentage%'>";
                                                               echo "</div></div>";
                                                           }
                                                       }else{
                                                           echo "<ul class=\"poll-options\">";
                                                           foreach($pollOptionRstBeanCollection as $optionValue => $option){

                                                               $optionID = $option->getPollOptionID();

                                                               echo "    <li class=\"poll-option\">";
                                                               echo "         <label class=\"radio form__radio\">";
                                                               if($pollRstBean->getOptionType() == 'S'){
                                                                   echo "<input type=\"radio\" name=\"poll_option\" value=\"$optionID\">";
                                                               }elseif ($pollRstBean->getOptionType() == 'M'){
                                                                   echo "<input type=\"checkbox\" name=\"pollOption_$optionValue\" value=\"$optionID\" class=\"content-layout-input\">";
                                                                   if(empty($optionIDs)){
                                                                       $optionIDs.= "pollOption_$optionValue";
                                                                   }else{
                                                                       $optionIDs.= ",pollOption_$optionValue";
                                                                   }
                                                               }else{
                                                                   echo "Cannot get options";
                                                               }
                                                               $optionName = $option->getOptionName();
                                                               echo "$optionName";
                                                               echo "</label></li></ul>";

                                                           }

                                                           echo "<input type=\"hidden\" name=\"optionCollection\"
                                                            value=\"$optionIDs\">";

                                                           echo "<div class=\"poll-footer\"> 
													                      <input type=\"submit\" value=\"Cast your vote\" class=\"btn btn-mini btn-primary \">
													                  </div></br>";

                                                       }

                                                    echo "<br>";
                                                    $content = $pollRstBean->getContent();
                                                    echo "<p>$content</p>";
                                                    echo "<br>";

                                                    ?>
												</form>
											</div>
										</article>
									</div>
									<div class="message-options">
										<a rel="reply" href="#forum_message_33428830" class="btn btn-mini btn-primary">Reply</a>
									</div>
							   </div>
							</div>
					    </div>

						<div id="post2" class="message message-plus">
						    <div class="avatar-user js-avatar-user" data-user-slug="james" data-user-profile="#">
							    <div class="avatar-user__container">
								     <a href="#" class="avatar">
									     <div class="img">
										     <img alt="Avatar image for James" src="./static/images/user2.jpg">
										 </div>
									</a>
									<dl class="user-info hide-mobile">
									    <dt class="user-info__name">
										    <a href="#">Amit</a>
										</dt>
										<dd class="user-info__item">
										</dd>
										<dd class="user-info__item">
										    <span>Member Since:</span> December 1, 2015
                                        </dd>
										<dd class="user-info__item">
										    <span>Posts:</span> 20
										</dd>
									</dl>
								</div>
							</div>

							<div class="message-wrap">
								<div class="message-inner">
									<div class="message-title">
                                        Posted by <a href="#">Amit</a>(20 posts)
										<span class="icon icon-time"> 1 year, 7 months ago</span>
									</div>
									<div class="message-breadcrumb"></div>
									<div class="message-content">
										<article class="content-body message-body js-message-body typography-format">
											<div class="poll-content-words">
												<p> Minotaur! </p>
											</div>
										</article>
									</div>
							   </div>
							</div>
					    </div>
						<div id="post2" class="message message-plus">
						    <div class="avatar-user js-avatar-user" data-user-slug="james" data-user-profile="#">
							    <div class="avatar-user__container">
								     <a href="#" class="avatar">
									     <div class="img">
										     <img alt="Avatar image for James" src="./static/images/user1.jpg">
										 </div>
									</a>
									<dl class="user-info hide-mobile">
									    <dt class="user-info__name">
										    <a href="#">Mike</a>
										</dt>
										<dd class="user-info__item">
										</dd>
										<dd class="user-info__item">
										    <span>Member Since:</span> December 1, 2015
                                        </dd>
										<dd class="user-info__item">
										    <span>Posts:</span> 20
										</dd>
									</dl>
								</div>
							</div>

							<div class="message-wrap">
								<div class="message-inner">
									<div class="message-title">
                                        Posted by <a href="#">Mike</a>(20 posts)
										<span class="icon icon-time"> 1 year, 3 months ago</span>
									</div>
									<div class="message-breadcrumb"></div>
									<div class="message-content">
										<article class="content-body message-body js-message-body typography-format">
											<div class="poll-content-words">
												<p> both... ! </p>
											</div>
										</article>
									</div>
							   </div>
							</div>
					    </div>
						<div id="post3" class="message message-plus">
						    <div class="avatar-user js-avatar-user" data-user-slug="james" data-user-profile="#">
							    <div class="avatar-user__container">
								     <a href="#" class="avatar">
									     <div class="img">
										     <img alt="Avatar image for James" src="./static/images/user3.jpg">
										 </div>
									</a>
									<dl class="user-info hide-mobile">
									    <dt class="user-info__name">
										    <a href="#">Rob</a>
										</dt>
										<dd class="user-info__item">
										</dd>
										<dd class="user-info__item">
										    <span>Member Since:</span> December 1, 2017
                                        </dd>
										<dd class="user-info__item">
										    <span>Posts:</span> 100
										</dd>
									</dl>
								</div>
							</div>

							<div class="message-wrap">
								<div class="message-inner">
									<div class="message-title">
                                        Posted by <a href="#">Rob</a>(100 posts)
										<span class="icon icon-time"> 2 days, 4 hours ago</span>
									</div>
									<div class="message-breadcrumb"></div>
									<div class="message-content">
										<article class="content-body message-body js-message-body typography-format">
											<div class="poll">

											<div class="poll-content-words">
												<p> Theseus! </p>
											</div>
										</article>
									</div>
							   </div>
							</div>
					    </div>
					</section>
				</div>
			</section>
	</main>