<?php

include(APP_PATH . 'comphp/lang/'.'internationalization.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
	<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php
            if(isset($_SESSION[ 'thePageName' ]) && !empty($_SESSION[ 'thePageName' ]) ){
                echo  $_SESSION[ 'thePageName' ];
            }else{
                echo  'Login';
            }
        ?>:: Hindi Language Learning Community</title>
	<!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">

	<link rel="Stylesheet" href="./static/css/Style.css">
</head>
<body>
    <header id="header" class="row">
	    <div id="logo" class="col display-flex justify-content-space-between">
		    <div class="logo-img">
                <a href="post.php"  rel="nofollow"><img id="logo" src="./static/images/logo2.png" alt="logo"></a>
			</div>
			<span class="logo-title"><p><?php echo msg('logoTitle'); ?><p/><span>
	    </div>
		<nav class="col">
		    <ul>
			    <li id="navHome" class="nav-item"><a class="nav-link" href="temp.html" rel="nofollow"><?php echo msg('headerHome'); ?></a></li>
			    <!--li id="navPost"><a href="post.php" rel="nofollow">Forms</a></li-->
                <li id="navPost" class="nav-item dropdown ">
                    <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                        <?php echo msg('headerForms'); ?>
                    </a>
                    <div class="dropdown-menu">
                        <?php
                           $boards =  $_fromBean->getBoards();

                           if(is_array($boards) && !empty($boards)){
                              foreach ($boards as $key => $value){
                                  echo " <a class=\"dropdown-item\" href=\"post.php?doboard_$key\">$value</a>";
                            }
                          }
                        ?>
                    </div>
                </li>
			    <li id="navSign" class="nav-item" style="padding-top: 0.5em;">
				    <?php
					   $theUsrName = null;

					   if(isset($_SESSION[ 'theUsrName' ]) && !empty($_SESSION[ 'theUsrName' ]) ){
						   $theUsrName = $_SESSION['theUsrName'];

						   $headerUsr = msg('headerUsr');
						   $headerLogout = msg('headerLogout');

						   echo "<a href='#'  rel='nofollow'>$headerUsr:$theUsrName</a> /
                                 <a href='logout.php'  rel='nofollow'>$headerLogout</a>
                                </li>";
					   }else{

					       $headerSign = msg('headerSign');
                           $headerJoin =  msg('headerJoin');

						   echo "<a href=\"login.php\" class=\"nav-link\"  rel=\"nofollow\">$headerSign/$headerJoin</a></li>";
					   }
				    ?>
			    <li id="navAboutUs" class="nav-item"><a href="temp.html" class="nav-link" rel="nofollow"><?php echo msg('headerAbout'); ?></a></li>
		    </ul>
		    <input id="searchbox" type="text" name="search" placeholder="Search..">
	    </nav>
	</header>
