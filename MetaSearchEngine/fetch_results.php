<?php

global $scores;
$scores = array();
global $bingURLs;
$bingURLs = array();
global $yahooURLs;
$yahooURLs = array();
global $googleURLs;
$googleURLs = array();
global $uth;
$uth = array();
global $bordaBing;
$bordaBing = array();
global $bordaYahoo;
$bordaYahoo = array();
global $bordaGoogle;
$bordaGoogle = array();

function secondsToTime($s)
{
    $h = floor($s / 3600);
    $s -= $h * 3600;
    $m = floor($s / 60);
    $s -= $m * 60;
    return $h.':'.sprintf('%02d', $m).':'.sprintf('%02d', $s);
}


function url_get_contents( $url )
{
    if ( !function_exists( 'curl_init' ) )
    {
        die( 'The cURL library is not installed.' );
    }

    $ch = curl_init();

    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    $output = curl_exec( $ch );
    curl_close( $ch );

    return $output;
}

function bingSearch( $search_terms )
{
    global $uth;
    global $bingURLs;
    $results = 1;
    $cnt = 0;

    for ( $i = 0; $i < 1; $i++ )
    {
        $bing = file_get_html('https://www.bing.com/search?q=' . $search_terms . '&first='.$results);

        $classname = 'b_attribution';
        $links = $bing->find("div[class=$classname]");

        foreach ( $links as $link )
        {
            $href = strip_tags($link->first_child());

            if (substr( $href, 0, 7 ) === "http://")
            {
                $href = str_replace("http://", '', $href);

            }
            else if (substr( $href, 0, 8 ) === "https://")
            {
                $href = str_replace("https://", '', $href);

            }

            $bingURLs[ $cnt ] = $href;
            $uth[ $bingURLs[ $cnt ] ] = 0;
            $cnt++;
        }

        $results += 10;

//        sleep(5);

    }

}

function yahooSearch( $search_terms )
{
    global $uth;
    global $yahooURLs;
    $results = 1;
    $cnt = 0;

    for ( $i = 0; $i < 1; $i++ )
    {
        $yahoo = file_get_html('https://gr.search.yahoo.com/search?p='.$search_terms.'&b='.$results);
        $results += 10;

        $classname = 'compTitle';
        $links = $yahoo->find("div[class=$classname]");

        foreach ( $links as $link )
        {
            $href = strip_tags($link->last_child()->first_child());
            if (substr( $href, 0, 7 ) === "http://")
            {
                $href = str_replace("http://", '', $href);

            }
            else if (substr( $href, 0, 8 ) === "https://")
            {
                $href = str_replace("https://", '', $href);

            }
            $yahooURLs[ $cnt ] = $href;
            $uth[ $yahooURLs[ $cnt ] ] = 0;
            $cnt++;
        }

        //        sleep(5);

    }

}

function googleSearch( $search_terms )
{
    global $uth;
    global $googleURLs;
    $results = 0;
    $cnt = 0;

    for ( $i = 0; $i < 1; $i++ )
    {
        $google = file_get_html('https://www.google.gr/search?q='.$search_terms.'&start='.$results);

//        $classname = 'r';
//        $links = $google->find("h3[class=$classname]");

        $links = $google->find("cite");

        $results += 10;

        foreach ( $links as $link )
        {
//            $href = $link->first_child()->getAttribute('href');
//            $href = strstr($href, '&', true);
//            $href = str_replace( '/url?q=', '', $href );

            $href = $link->plaintext;

            if (substr( $href, 0, 7 ) === "http://")
            {
                $href = str_replace("http://", '', $href);

            }
            else if (substr( $href, 0, 8 ) === "https://")
            {
                $href = str_replace("https://", '', $href);

            }

            $googleURLs[ $cnt ] = $href;
            $uth[ $href ] = 0;
            $cnt++;
        }

        //        sleep(5);


    }
}

function printResults( $search_engine )
{
    global $bingURLs;
    global $yahooURLs;
    global $googleURLs;

    $result = 1;

    for ( $i = 1; $i <= 10; $i++ )
    {
        echo '<ul id="';
        echo 'page_'.$i;

        if ( $i == 1 )
            echo '" class="responsive_table tab active">';
        else
            echo '" class="responsive_table tab">';

        echo '<li class="table_row">';
        echo '<div class="table_section_small">Rank</div>';
        echo '<div class="table_section">'; echo $search_engine; echo '</div>';
        echo '</li>';

        if ( strcmp( $search_engine, 'Bing' ) == 0 )
        {
            if ( count($bingURLs) == 0 )
            {
                echo 'No results found';
                echo '</br>';
                echo '</ul>';
                break;
            }

            for ( $j = 0 ; $j < 10; $result++, $j++ )
            {
                echo '<li class="table_row">';
                echo '<div class="table_section_small">'; echo $result; echo '</div>';
                echo '<div class="table_section">';
                if ( empty($bingURLs[$result-1]) )
                    echo '-';
                else
                    echo $bingURLs[$result-1];
                echo '</div>';
                echo '</li>';
            }

            echo '</ul>';
        }
        else if ( strcmp( $search_engine, 'Yahoo' ) == 0 )
        {
            if ( count($yahooURLs) == 0 )
            {
                echo 'No results found';
                echo '</br>';
                echo '</ul>';
                break;
            }

            for ( $j = 0 ; $j < 10; $result++, $j++ )
            {
                echo '<li class="table_row">';
                echo '<div class="table_section_small">'; echo $result; echo '</div>';
                echo '<div class="table_section">';
                if ( empty($yahooURLs[$result-1]) )
                    echo '-';
                else
                    echo $yahooURLs[$result-1];
                echo '</div>';
                echo '</li>';
            }

            echo '</ul>';
        }
        else if ( strcmp( $search_engine, 'Google' ) == 0 )
        {
            if ( count($googleURLs) == 0 )
            {
                echo 'No results found';
                echo '</br>';
                echo '</ul>';
                break;
            }

            for ( $j = 0 ; $j < 10; $result++, $j++ )
            {
                echo '<li class="table_row">';
                echo '<div class="table_section_small">'; echo $result; echo '</div>';
                echo '<div class="table_section">';
                if ( empty($googleURLs[$result-1]) )
                    echo '-';
                else
                    echo $googleURLs[$result-1];
                echo '</div>';
                echo '</li>';
            }

            echo '</ul>';
        }

    }
}

function calcBordaCount( $google, $yahoo, $bing )
{
    global $uth;
    global $yahooURLs;
    global $bingURLs;
    global $googleURLs;

    if ( ( $google == false ) && ( $yahoo == false ) && ( $bing == false ) )
        return;
    else if ( ( $google == true ) && ( $yahoo == false ) && ( $bing == false ) )
    {
        for ( $i = 0; $i < count($googleURLs); $i++ )
        {
            $uth[ $googleURLs[$i] ] = count($googleURLs) - $i;
        }
    }
    else if ( ( $google == false ) && ( $yahoo == true ) && ( $bing == false ) )
    {
        for ( $i = 0; $i < count($yahooURLs); $i++ )
        {
            $uth[ $yahooURLs[$i] ] = count($yahooURLs) - $i;
        }
    }
    else if ( ( $google == false ) && ( $yahoo == false ) && ( $bing == true ) )
    {
        for ( $i = 0; $i < count($bingURLs); $i++ )
        {
            $uth[ $bingURLs[$i] ] = count($bingURLs) - $i;
        }
    }
    else if ( ( $google == false ) && ( $yahoo == true ) && ( $bing == true ) )
    {
        cmpBingYahoo();
    }
    else if ( ( $google == true ) && ( $yahoo == false ) && ( $bing == true ) )
    {
        cmpGoogleBing();
    }
    else if ( ( $google == true ) && ( $yahoo == true ) && ( $bing == false ) )
    {
        cmpGoogleYahoo();
    }
    else if ( ( $google == true ) && ( $yahoo == true ) && ( $bing == true ) )
    {
        cmpAll();
    }

}

function kendall_tau2($engine1,$engine2)
{
    for ($i=0; $i<count($engine1); $i++){
        for ($j=0; $j<count($engine2); $j++){
            if (strcmp($engine2[$j]['url'],$engine1[$i]['url'])==0){ //equals
                $interv2[$i] = $engine2[$j]['score'];

//                echo "i: ".$i.", score: ".$interv2[$i]."<br>";
            }
        }
    }
    $i = 0;
    foreach($interv2 as $key => $value){
//        echo $key." value : ".$value."<br>";
        $kendall[$i] = $value;
//        echo "inside foreach i=".$i." ".$kendall[$i]." kati<br>";
        $i++;

    }
    for($i=0; $i<count($kendall); $i++){
        $concordant[$i] = 0;
        $discordant[$i] = 0;
    }
    for($i=0; $i<count($kendall); $i++){
        for($j=$i; $j<count($kendall); $j++){
            if($kendall[$j]<$kendall[$i]&& ($j<=count($kendall))){
                //echo "kendal j+1  ".$kendall[$j+1]."<br>";
                $concordant[$i] =$concordant[$i]+ 1;
            }
            if($kendall[$j+1]>$kendall[$i] && ($j<=count($kendall))){
                //echo "kendal j+1  ".$kendall[$j+1]."<br>";
                $discordant[$i] =$discordant[$i]+ 1;

            }
        }
//        echo "concordant  i=".$i." ".$concordant[$i]."<br>";
//
//        echo "discordant  i=".$i."  ".$discordant[$i]."<br>";
        //echo "<br>";
    }
    $C=0.0;
    $D=0.0;
    for($i=0; $i<count($kendall); $i++){
        $C = $C + $concordant[$i];
        $D = $D + $discordant[$i];
    }
    $res = abs(($D-$C)/($D+$C));
    echo "Kendall's Tau: ".number_format((float)$res, 2, '.', '').'<br>';
    return ($D-$C)/($D+$C);
}

function corrBing()
{
//    global $uth;
//    global $bordaBing;
//
//    arsort( $uth );
//    arsort( $bordaBing );
//
//    $uth_keys = array_keys($uth);
//    $bing_keys = array_keys($bordaBing);

    global $uth;
    global $bingURLs;

    $uth_keys = array_keys($uth);

    for ( $i = 0; $i < count($uth); $i++ )
    {
        $uth1[$i]['url'] = $uth_keys[$i];
        $uth1[$i]['score'] = $uth[ $uth_keys[$i]];
    }

    for ( $i = 0; $i < count($bingURLs); $i++ )
    {
        $bing1[$i]['url'] = $bingURLs[$i];
        $bing1[$i]['score'] = count($bingURLs) - $i;
    }

    echo "Bing's ";

    kendall_tau2( $uth1, $bing1 );
}

function corrYahoo()
{
    global $uth;
    global $yahooURLs;

    $uth_keys = array_keys($uth);
//    $yahoo_keys = array_keys($bordaYahoo);

    for ( $i = 0; $i < count($uth); $i++ )
    {
        $uth1[$i]['url'] = $uth_keys[$i];
        $uth1[$i]['score'] = $uth[ $uth_keys[$i]];
    }

    for ( $i = 0; $i < count($yahooURLs); $i++ )
    {
        $yahoo1[$i]['url'] = $yahooURLs[$i];
        $yahoo1[$i]['score'] = count($yahooURLs) - $i;
    }

    echo "Yahoo's ";

    kendall_tau2( $uth1, $yahoo1 );



}

function corrGoogle()
{
    global $uth;
    global $googleURLs;

    $uth_keys = array_keys($uth);
//    $google_keys = array_keys($bordaGoogle);

    for ( $i = 0; $i < count($uth); $i++ )
    {
        $uth1[$i]['url'] = $uth_keys[$i];
        $uth1[$i]['score'] = $uth[ $uth_keys[$i]];
    }

    for ( $i = 0; $i < count($googleURLs); $i++ )
    {
        $google1[$i]['url'] = $googleURLs[$i];
        $google1[$i]['score'] = count($googleURLs) - $i;
    }

    echo "Google's ";

    kendall_tau2( $uth1, $google1 );
}

function kendall_tau( $google, $yahoo, $bing )
{
    $kendall_t = array();

    $kendall_t['google'] = -2.0;
    $kendall_t['yahoo'] = -2.0;
    $kendall_t['bing'] = -2.0;

    if ( ( $google == false ) && ( $yahoo == false ) && ( $bing == false ) )
        echo "No Kendall's Tau<br>";
    else if ( ( $google == true ) && ( $yahoo == false ) && ( $bing == false ) )
        echo "Google's Kendall's Tau: 1<br>";
    else if ( ( $google == false ) && ( $yahoo == true ) && ( $bing == false ) )
        echo "Yahoo's Kendall's Tau: 1<br>";
    else if ( ( $google == false ) && ( $yahoo == false ) && ( $bing == true ) )
        echo "Bing's Kendall's Tau: 1<br>";
    else if ( ( $google == false ) && ( $yahoo == true ) && ( $bing == true ) )
    {
        $kendall_t['yahoo'] = corrYahoo();
        $kendall_t['bing'] = corrBing();
    }
    else if ( ( $google == true ) && ( $yahoo == false ) && ( $bing == true ) )
    {
        $kendall_t['bing'] = corrBing();
        $kendall_t['google'] = corrGoogle();
    }
    else if ( ( $google == true ) && ( $yahoo == true ) && ( $bing == false ) )
    {
        $kendall_t['yahoo'] = corrYahoo();
        $kendall_t['google'] = corrGoogle();
    }
    else if ( ( $google == true ) && ( $yahoo == true ) && ( $bing == true ) )
    {
        $kendall_t['yahoo'] = corrYahoo();
        $kendall_t['google'] = corrGoogle();
        $kendall_t['bing'] = corrBing();
    }

    return $kendall_t;
}

function cmpAll()
{
    global $yahooURLs;
    global $bingURLs;
    global $googleURLs;
    global $bordaGoogle;
    global $bordaYahoo;
    global $bordaBing;
    global $uth;

    $found = false;

    for ( $i = 0; $i < count( $googleURLs ); $i++ )
    {
        for ( $j = 0; $j < count( $yahooURLs ); $j++ )
        {
            for ( $k = 0; $k < count( $bingURLs ); $k++ )
            {
                if ( ( $googleURLs[$i] == $yahooURLs[$j] ) && ( $googleURLs[$i] == $bingURLs[$k] ) )
                {
                    $found = true;
                    break;
                }
            }

            if ( $found == true )
                break;

        }

        if ( $found == true )
        {
            $bordaGoogle[ $googleURLs[$i] ] = count( $googleURLs ) - $i;
            $found = false;
        }
        else
        {
            $bordaGoogle[ $googleURLs[$i] ] = 0;
        }
    }

    $found = false;

    for ( $i = 0; $i < count( $yahooURLs ); $i++ )
    {
        for ( $j = 0; $j < count( $googleURLs ); $j++ )
        {
            for ( $k = 0; $k < count( $bingURLs ); $k++ )
            {
                if ( ( $yahooURLs[$i] == $googleURLs[$j] ) && ( $yahooURLs[$i] == $bingURLs[$k] ) )
                {
                    $found = true;
                    break;
                }
            }

            if ( $found == true )
                break;

        }

        if ( $found == true )
        {
            $bordaYahoo[ $yahooURLs[$i] ] = count( $yahooURLs ) - $i;
            $found = false;
        }
        else
        {
            $bordaYahoo[ $yahooURLs[$i] ] = 0;
        }
    }

    $found = false;

    for ( $i = 0; $i < count( $bingURLs ); $i++ )
    {
        for ( $j = 0; $j < count( $yahooURLs ); $j++ )
        {
            for ( $k = 0; $k < count( $googleURLs ); $k++ )
            {
                if ( ( $bingURLs[$i] == $yahooURLs[$j] ) && ( $bingURLs[$i] == $googleURLs[$k] ) )
                {
                    $found = true;
                    break;
                }
            }

            if ( $found == true )
                break;

        }

        if ( $found == true )
        {
            $bordaBing[ $bingURLs[$i] ] = count( $bingURLs ) - $i;
            $found = false;
        }
        else
        {
            $bordaBing[ $bingURLs[$i] ] = 0;
        }
    }


    arsort( $bordaGoogle );
    arsort( $bordaYahoo );
    arsort( $bordaBing );

    $i = 0;

    foreach ($bordaGoogle as $key => $value)
    {
        if ( $value == 0 )
            $bordaGoogle[ $key ] = count($bordaGoogle) - $i;
        else
        {
            $bordaGoogle[ $key ] = count($bordaGoogle) - $i;
            $i++;
        }

        $val = $bordaGoogle[ $key ];

        $uth[ $key ] += $val;


//        echo "<p>$key = $val</p>";
    }

    $i = 0;

    foreach ($bordaYahoo as $key => $value)
    {
        if ( $value == 0 )
            $bordaYahoo[ $key ] = count($bordaYahoo) - $i;
        else
        {
            $bordaYahoo[ $key ] = count($bordaYahoo) - $i;
            $i++;
        }

        $val = $bordaYahoo[ $key ];

        $uth[ $key ] += $val;

//        echo "<p>$key = $val</p>";
    }

    $i = 0;

    foreach ($bordaBing as $key => $value)
    {
        if ( $value == 0 )
            $bordaBing[ $key ] = count($bordaBing) - $i;
        else
        {
            $bordaBing[ $key ] = count($bordaBing) - $i;
            $i++;
        }

        $val = $bordaBing[ $key ];

        $uth[ $key ] += $val;

//        echo "<p>$key = $val</p>";
    }
}

function cmpGoogleYahoo()
{
    global $yahooURLs;
    global $googleURLs;
    global $bordaGoogle;
    global $bordaYahoo;
    global $uth;

    $found = false;

    for ( $i = 0; $i < count( $googleURLs ); $i++ )
    {
        for ( $j = 0; $j < count( $yahooURLs ); $j++ )
        {
            if ( $googleURLs[$i] == $yahooURLs[$j] )
            {
                $found = true;
                break;
            }
        }

        if ( $found == true )
        {
            $bordaGoogle[ $googleURLs[$i] ] = count( $googleURLs ) - $i;
            $found = false;
        }
        else
        {
            $bordaGoogle[ $googleURLs[$i] ] = 0;
        }
    }

    $found = false;

    for ( $i = 0; $i < count( $yahooURLs ); $i++ )
    {
        for ( $j = 0; $j < count( $googleURLs ); $j++ )
        {
            if ( $googleURLs[$i] == $yahooURLs[$j] )
            {
                $found = true;
                break;
            }
        }

        if ( $found == true )
        {
            $bordaYahoo[ $yahooURLs[$i] ] = count( $yahooURLs ) - $i;
            $found = false;
        }
        else
        {
            $bordaYahoo[ $yahooURLs[$i] ] = 0;
        }
    }

    arsort( $bordaGoogle );
    arsort( $bordaYahoo );

    $i = 0;

    foreach ($bordaGoogle as $key => $value)
    {
        if ( $value == 0 )
            $bordaGoogle[ $key ] = count($bordaGoogle) - $i;
        else
        {
            $bordaGoogle[ $key ] = count($bordaGoogle) - $i;
            $i++;
        }

        $val = $bordaGoogle[ $key ];

        $uth[ $key ] += $val;


//        echo "<p>$key = $val</p>";
    }

    $i = 0;

    foreach ($bordaYahoo as $key => $value)
    {
        if ( $value == 0 )
            $bordaYahoo[ $key ] = count($bordaYahoo) - $i;
        else
        {
            $bordaYahoo[ $key ] = count($bordaYahoo) - $i;
            $i++;
        }

        $val = $bordaYahoo[ $key ];

        $uth[ $key ] += $val;


//        echo "<p>$key = $val</p>";
    }
}

function cmpGoogleBing()
{
    global $googleURLs;
    global $bingURLs;
    global $bordaBing;
    global $bordaGoogle;
    global $uth;

    $found = false;

    for ( $i = 0; $i < count( $bingURLs ); $i++ )
    {
        for ( $j = 0; $j < count( $googleURLs ); $j++ )
        {
            if ( $bingURLs[$i] == $googleURLs[$j] )
            {
                $found = true;
                break;
            }
        }

        if ( $found == true )
        {
            $bordaBing[ $bingURLs[$i] ] = count( $bingURLs ) - $i;
            $found = false;
        }
        else
        {
            $bordaBing[ $bingURLs[$i] ] = 0;
        }
    }

    $found = false;

    for ( $i = 0; $i < count( $googleURLs ); $i++ )
    {
        for ( $j = 0; $j < count( $bingURLs ); $j++ )
        {
            if ( $bingURLs[$i] == $googleURLs[$j] )
            {
                $found = true;
                break;
            }
        }

        if ( $found == true )
        {
            $bordaGoogle[ $googleURLs[$i] ] = count( $googleURLs ) - $i;
            $found = false;
        }
        else
        {
            $bordaGoogle[ $googleURLs[$i] ] = 0;
        }
    }

    arsort( $bordaBing );
    arsort( $bordaGoogle );

    $i = 0;

    foreach ($bordaBing as $key => $value)
    {
        if ( $value == 0 )
            $bordaBing[ $key ] = count($bordaBing) - $i;
        else
        {
            $bordaBing[ $key ] = count($bordaBing) - $i;
            $i++;
        }

        $val = $bordaBing[ $key ];

        $uth[ $key ] += $val;

//        echo "<p>$key = $val</p>";
    }

    $i = 0;

    foreach ($bordaGoogle as $key => $value)
    {
        if ( $value == 0 )
            $bordaGoogle[ $key ] = count($bordaGoogle) - $i;
        else
        {
            $bordaGoogle[ $key ] = count($bordaGoogle) - $i;
            $i++;
        }

        $val = $bordaGoogle[ $key ];

        $uth[ $key ] += $val;


//        echo "<p>$key = $val</p>";
    }
}

function cmpBingYahoo()
{
    global $yahooURLs;
    global $bingURLs;
    global $bordaBing;
    global $bordaYahoo;
    global $uth;

    $found = false;

    for ( $i = 0; $i < count( $bingURLs ); $i++ )
    {
        for ( $j = 0; $j < count( $yahooURLs ); $j++ )
        {
            if ( $bingURLs[$i] == $yahooURLs[$j] )
            {
                $found = true;
                break;
            }
        }

        if ( $found == true )
        {
            $bordaBing[ $bingURLs[$i] ] = count( $bingURLs ) - $i;
            $found = false;
        }
        else
        {
            $bordaBing[ $bingURLs[$i] ] = 0;
        }
    }

    $found = false;

    for ( $i = 0; $i < count( $yahooURLs ); $i++ )
    {
        for ( $j = 0; $j < count( $bingURLs ); $j++ )
        {
            if ( $bingURLs[$i] == $yahooURLs[$j] )
            {
                $found = true;
                break;
            }
        }

        if ( $found == true )
        {
            $bordaYahoo[ $yahooURLs[$i] ] = count( $yahooURLs ) - $i;
            $found = false;
        }
        else
        {
            $bordaYahoo[ $yahooURLs[$i] ] = 0;
        }
    }

    arsort( $bordaBing );
    arsort( $bordaYahoo );

    $i = 0;

    foreach ($bordaBing as $key => $value)
    {
        if ( $value == 0 )
            $bordaBing[ $key ] = count($bordaBing) - $i;
        else
        {
            $bordaBing[ $key ] = count($bordaBing) - $i;
            $i++;
        }

        $val = $bordaBing[ $key ];

        $uth[ $key ] += $val;

//        echo "<p>$key = $val</p>";
    }

    $i = 0;

    foreach ($bordaYahoo as $key => $value)
    {
        if ( $value == 0 )
            $bordaYahoo[ $key ] = count($bordaYahoo) - $i;
        else
        {
            $bordaYahoo[ $key ] = count($bordaYahoo) - $i;
            $i++;
        }

        $val = $bordaYahoo[ $key ];

        $uth[ $key ] += $val;
//        echo "<p>$key = $val</p>";
    }
}

function printBordaCount()
{
    global $uth;

    arsort( $uth );

    $keys = array_keys($uth);

    $result = 1;

    for ( $i = 1; $i <= 10; $i++ )
    {
        echo '<ul id="page_'.$i;

        if ( $i == 1 )
            echo '" class="responsive_table tab active">';
        else
            echo '" class="responsive_table tab">';

        echo '<li class="table_row">';
        echo '<div class="table_section_small">Rank</div>';
        echo '<div class="table_section">UTH</div>';
        echo '</li>';

        if ( count($uth) == 0 )
        {
            echo 'No results found';
            echo '</br>';
            echo '</ul>';
            break;
        }

        for ( $j = 0 ; $j < 10; $result++, $j++ )
        {
            echo '<li class="table_row">';
            echo '<div class="table_section_small">'; echo $result; echo '</div>';
            echo '<div class="table_section">';
            if ( empty($keys[$result-1]) )
                echo '-';
            else
                echo $keys[$result-1].' ('.$uth[ $keys[$result-1]].')';
            echo '</div>';
            echo '</li>';
        }

        echo '</ul>';

    }

}

?>