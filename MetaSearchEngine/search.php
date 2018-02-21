<?php

    require('fetch_results.php');
    require('simple_html_dom.php');

    $google = false;
    $bing = false;
    $yahoo = false;

    $query = $_GET['query'];

    $query = trim( $query ); # Remove leading or trailing white spaces
    $search_terms = preg_replace( "/[\s]+/", '+', $query ); # Replace white space characters with +

?>

<!DOCTYPE html>
<html class="pixel-ratio-1 watch-active-state">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png" />
    <link rel="apple-touch-startup-image" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" href="apple-touch-startup-image-640x1096.png">
    <title>UTH MetaSearch Engine</title>
    <link rel="stylesheet" href="css/framework7.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/colors/magenta.css">
    <link type="text/css" rel="stylesheet" href="css/swipebox.css" />
    <link type="text/css" rel="stylesheet" href="css/animations.css" />
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,700,900' rel='stylesheet' type='text/css'>
</head>
<body id="mobile_wrap">

<div class="statusbar-overlay"></div>

<div class="panel-overlay"></div>

<div class="pages">
    <div data-page="projects" class="page no-toolbar no-navbar">
        <div class="page-content">

            <div class="navbarpages">
                <div class="nav_left_logo">
                    <a href="index.html" class="external" style="height: inherit;display: block;float: left;">
                        <img src="images/uth_logo_en_s.png" alt="" title="" />
                        <img src="images/uth_en.png" alt="" title="" style="width: 50%;height: 80%;margin-bottom: 3%" />
                    </a>
                </div>
            </div>


            <div id="pages_maincontent">

                <h2 class="page_title">Search results for: <?php echo $query ?></h2>

                <div class="page_content">

                    <div class="buttons-row">
                        <a href="#uth" class="tab-link button active">UTH</a>

                        <?php
                        if ( !empty($_GET['google']) )
                            echo '<a href="#google" class="tab-link button">Google</a>';

                        if ( !empty($_GET['yahoo']) )
                            echo '<a href="#yahoo" class="tab-link button">Yahoo</a>';

                        if ( !empty($_GET['bing']) )
                            echo '<a href="#bing" class="tab-link button">Bing</a>';

                        ?>

                    </div>

                    <div class="tabs-simple">
                        <div class="tabs">
                            <div id="uth" class="tab active">
                                <div class="tabs-simple">
                                    <div class="tabs">
                                        <?php
                                        if ( !empty($_GET['google']) )
                                        {
                                            $google = true;

                                            $googlestarttime = microtime(true);

                                            googleSearch( $search_terms );

                                            $googleendtime = microtime(true);
                                            $googletimediff = $googleendtime - $googlestarttime;


                                        }
                                        if ( !empty($_GET['yahoo']) )
                                        {
                                            $yahoo = true;

                                            $yahoostarttime = microtime(true);

                                            yahooSearch( $search_terms );

                                            $yahooendtime = microtime(true);
                                            $yahootimediff = $yahooendtime - $yahoostarttime;
                                        }
                                        if ( !empty($_GET['bing']) )
                                        {
                                            $bing = true;

                                            $bingstarttime = microtime(true);

                                            bingSearch( $search_terms );

                                            $bingendtime = microtime(true);
                                            $bingtimediff = $bingendtime - $bingstarttime;
                                        }

                                        $uthstarttime = microtime(true);
                                        calcBordaCount( $google, $yahoo, $bing );
                                        printBordaCount();
                                        $uthendtime = microtime(true);

                                        $uthtimediff = $uthendtime - $uthstarttime;

                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                            if ( !empty($_GET['google']) )
                            {
                                echo '<div id="google" class="tab">';
                                echo '<div class="tabs-simple">';
                                echo '<div class="tabs">';
                                printResults( 'Google' );
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';

                            }

                            if ( !empty($_GET['yahoo']) )
                            {
                                echo '<div id="yahoo" class="tab">';
                                echo '<div class="tabs-simple">';
                                echo '<div class="tabs">';
                                printResults( 'Yahoo' );
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';

                            }

                            if ( !empty($_GET['bing']) )
                            {
                                echo '<div id="bing" class="tab">';
                                echo '<div class="tabs-simple">';
                                echo '<div class="tabs">';
                                printResults( 'Bing' );
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>

                    <div class="buttons-row">
                        <a href="#page_1" id="pg1" class="tab-link button active">1</a>
                        <a href="#page_2" id="pg2" class="tab-link button">2</a>
                        <a href="#page_3" class="tab-link button">3</a>
                        <a href="#page_4" class="tab-link button">4</a>
                        <a href="#page_5" class="tab-link button">5</a>
                        <a href="#page_6" class="tab-link button">6</a>
                        <a href="#page_7" class="tab-link button">7</a>
                        <a href="#page_8" class="tab-link button">8</a>
                        <a href="#page_9" class="tab-link button">9</a>
                        <a href="#page_10" class="tab-link button">10</a>
                    </div>

                    <div class="call_button" style="width: 70%;margin-left: 12%;margin-top: 10%;"><a href="index.html" class="external">Search again</a></div>

                    <?php
                    if ( $google == true )
                        echo 'Time elapsed (Google): '.$googletimediff.'<br>';
                    if ( $yahoo == true )
                        echo 'Time elapsed (Yahoo): '.$yahootimediff.'<br>';
                    if ( $bing == true )
                        echo 'Time elapsed (Bing): '.$bingtimediff.'<br>';

                    echo 'Time elapsed (UTH): '.$uthtimediff.'<br>';

                    kendall_tau($google, $yahoo, $bing ); ?>

                </div>

            </div>


        </div>
    </div>
</div>

<script type="text/javascript" src="js/jquery-1.10.1.min.js"></script>
<script src="js/jquery.validate.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/framework7.js"></script>
<script type="text/javascript" src="js/my-app.js"></script>
<script type="text/javascript" src="js/jquery.swipebox.js"></script>
<script type="text/javascript" src="js/email.js"></script>
<script type="text/javascript">
    MutationObserver = window.MutationObserver || window.WebKitMutationObserver;

    var observer = new MutationObserver(function(mutations, observer) {
        if ( mutations[0].target.innerText == 'UTH' )
        {
            console.log('UTH');

            var page_num = document.querySelectorAll('[id^="page_"]');

            for ( var i = 0; i < page_num.length; i++ )
                page_num[i].removeAttribute('id');

            var search_engine = document.getElementById('uth');
            var results = search_engine.firstElementChild.firstElementChild;

            var pages = results.children;
            for ( var i = 0; i < pages.length; i++ )
            {
                var page = pages[i];
                page.setAttribute('id', 'page_' + (i+1));
            }

            pages = document.querySelectorAll('[id^="pg"]');

            for ( var i = 0; i < pages.length; i++ )
            {
                if ( pages[i].classList.contains('active') )
                    pages[i].click();
            }

        }
        else if ( mutations[0].target.innerText == 'Google' )
        {
            console.log('Google');

            var rem = document.querySelectorAll('[id^="page_"]');

            for ( var i = 0; i < rem.length; i++ )
                rem[i].removeAttribute('id');

            var section = document.getElementById('google');
            var div = section.firstElementChild.firstElementChild;

            var children = div.children;
            for (var i = 0; i < children.length; i++) {
                var child = children[i];
                child.setAttribute('id', 'page_' + (i+1));
                // Do stuff
            }

            var rem2 = document.querySelectorAll('[id^="pg"]');
//            console.log(rem2);

            for ( var i = 0; i < rem2.length; i++ )
            {
                console.log(rem2[i]);

                if ( rem2[i].classList.contains('active') )
                {
                    rem2[i].click();
                    console.log('Click');
                }
            }

        }
        else if ( mutations[0].target.innerText == 'Bing' )
        {
            console.log('Bingo');

            var rem = document.querySelectorAll('[id^="page_"]');

            for ( var i = 0; i < rem.length; i++ )
                rem[i].removeAttribute('id');

            var section = document.getElementById('bing');
            var div = section.firstElementChild.firstElementChild;

            var children = div.children;
            for (var i = 0; i < children.length; i++) {
                var child = children[i];
                child.setAttribute('id', 'page_' + (i+1));
                // Do stuff
            }

            var rem2 = document.querySelectorAll('[id^="pg"]');
//            console.log(rem2);

            for ( var i = 0; i < rem2.length; i++ )
            {
                console.log(rem2[i]);

                if ( rem2[i].classList.contains('active') )
                {
                    rem2[i].click();
                    console.log('Click');
                }
            }

        }
        else if ( mutations[0].target.innerText == 'Yahoo' )
        {
            var rem = document.querySelectorAll('[id^="page_"]');

            for ( var i = 0; i < rem.length; i++ )
                rem[i].removeAttribute('id');

            var section = document.getElementById('yahoo');
            var div = section.firstElementChild.firstElementChild;

            var children = div.children;
            for (var i = 0; i < children.length; i++) {
                var child = children[i];
                child.setAttribute('id', 'page_' + (i+1));
                // Do stuff
            }
//            console.log(div.firstElementChild.firstElementChild);

            var rem2 = document.querySelectorAll('[id^="pg"]');
            console.log(rem2);

            for ( var i = 0; i < rem2.length; i++ )
            {
                console.log(rem2[i]);

                if ( rem2[i].classList.contains('active') )
                {
                    rem2[i].click();
                    console.log('Click');
                }
            }

        }

    });

    // define what element should be observed by the observer
    // and what types of mutations trigger the callback
    observer.observe(document, {
        subtree: true,
        attributes: true
    });
</script>
</body>
</html>