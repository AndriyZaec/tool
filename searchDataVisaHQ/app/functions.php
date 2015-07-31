<?php
require_once('lib/phpQuery.php');

//--------------------------------------------Functions----------------------------------------------

/**
 * @param $url
 * @param array $advanced_options
 * @return mixed
 */
function get_content($url,$advanced_options=array()){
    $ch = curl_init();
    $encUrl = $url;
    $options = array(
        CURLOPT_RETURNTRANSFER => true,	 // return web page
        CURLOPT_HEADER	 => false,	// don't return headers
        CURLOPT_FOLLOWLOCATION => false,	 // follow redirects
        CURLOPT_ENCODING	 => "",	 // handle all encodings
        CURLOPT_USERAGENT	 => 'MJ12bot', // who am i
        CURLOPT_REFERER => 'http://localhost/',
        CURLOPT_AUTOREFERER	=> true,	 // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 5,	 // timeout on connect
        CURLOPT_TIMEOUT	 => 10,	 //timeout on response
        CURLOPT_MAXREDIRS	 => 3,	 //stop after 10 redirects
        CURLOPT_URL	 => $encUrl,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => false,
    );
    $options=$advanced_options+$options;
    curl_setopt_array($ch, $options);
    $content = curl_exec($ch);
    return $content;
}

/**
 * @param $url
 * @return mixed
 */
function getStatusCode($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'MJ12bot');
    curl_exec($ch);
    $status_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
    return $status_code;
}

/**
 * @param $robots
 * @return array
 */
function robots_ms($robots){
    $ms=array_map(function($val){
        if ($val!='') {
            $val=trim($val);
            return($val);
        }

    }, explode("\n", $robots));
    $ms=array_diff($ms,array(''));
    sort($ms);
    return $ms;
}

/**
 * @param $url
 * @param array $advanced_options
 * @return mixed
 */
function getContent($url,$advanced_options=array()){
    $ch = curl_init();
    $encUrl = $url;
    $options = array(
        CURLOPT_RETURNTRANSFER => true,	 // return web page
        CURLOPT_HEADER	 => false,	// don't return headers
        CURLOPT_FOLLOWLOCATION => false,	 // follow redirects
        CURLOPT_ENCODING	 => "",	 // handle all encodings
        CURLOPT_USERAGENT	 => 'MJ12bot', // who am i
        CURLOPT_REFERER => 'http://localhost/',
        CURLOPT_AUTOREFERER	=> true,	 // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 5,	 // timeout on connect
        CURLOPT_TIMEOUT	 => 10,	 //timeout on response
        CURLOPT_MAXREDIRS	 => 3,	 //stop after 10 redirects
        CURLOPT_URL	 => $encUrl,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => false,
    );
    $options=$advanced_options+$options;
    curl_setopt_array($ch, $options);
    $content = curl_exec($ch);
    return $content;
}

/**
 * @param $ms
 * @return array
 */
function findDuplicate($ms){
    $res=array();
    $tmp_ms=array_count_values($ms);
    foreach ($tmp_ms as $key => $value) {
        if ($value>1) {
            $res[]=array_keys($ms,$key);
        }
    }
    return $res;
}

/**
 * @param $domains
 * @param $robots
 * @return array
 */
function robot_test ($domains, $robots) {
    for($i=2;$i<count($domains);$i++) {
        $domainsList[$i] = explode("\n", $domains[$i]);
        $robotsList[$i] = explode("\n", $robots[$i]);
        array_pop($domainsList[$i]);
        array_pop($robotsList[$i]);
        $count_domains=count($domainsList[$i]);
        for ($j=0; $j<$count_domains; $j++) {
            $domainsList[$i][$j]=trim($domainsList[$i][$j]);
            if (substr_count($domainsList[$i][$j], 'https://')>0 || substr_count($domainsList[$i][$j], 'http://')>0) {
                echo $robots_url=$domainsList[$i][$j]."/robots.txt";
            }
            else{
                $robots_url="http://www.".$domainsList[$i][$j]."/robots.txt";
            }
            $get_visa_robots=get_content($robots_url);
              $visa_robots_ms=robots_ms($get_visa_robots);print_r($visa_robots_ms);}}
//            if (count($robotsList)!=count($visa_robots_ms)) {
//                $check_robots_result[][$robots_url]='Не идентичны';
//            }
//            else{
//                $tmp_res=array_diff($robotsList, $visa_robots_ms);
//                if (count($tmp_res)!=0) {
//                    $check_robots_result[][$robots_url]='Не идентичны';
//                }
//                else{
//                    $check_robots_result[][$robots_url]='Идентичны';
//                }
//            }
//        }

        //return $check_robots_result;
    }
//}
//------------------------------------------------------------------------------------------------

if($_POST['action']=='getHtmlErrors'){
    if(isset($_POST['pages'])){
        $pages=trim($_POST['pages']);
        $page_list=explode("\n", $pages);
        $count_pages=count($page_list);
        for ($i=0; $i<$count_pages; $i++) {
            $errors=0;
            $warnings=0;
            $visa_url_check='http://validator.w3.org/check?uri='.urlencode($page_list[$i]).'&charset='.urlencode('(detect automatically)').'&doctype=Inline&group=0&user-agent=W3C_Validator%2F1.3+http%3A%2F%2Fvalidator.w3.org%2Fservices';
            $html_content=file_get_contents($visa_url_check);
            $html = phpQuery::newDocumentHTML($html_content);
            $errors=$error=$html->find('li[class="error"]')->length;
            $warnings=$error=$html->find('li[class="info warning"]')->length;
            if ($warnings==0 and $errors==0) {
                $ms_errors[$page_list[$i]]="Errors and warnings not found";
            }else{
                $ms_errors[$page_list[$i]]="Errors: $errors \t warnings: $warnings";
            }
            phpQuery::unloadDocuments();
            sleep(1);
        }
        if (count($ms_errors)>0) {
            foreach ($ms_errors as $key => $value) {
                $str.="<tr><td>$key</td><td>$value</td></tr>";
            }
            echo $str;
        }
    }
}
if($_POST['action']=='getDuplicateTags'){
    if(isset($_POST['pages']) and isset($_POST['domains'])){
        $page_list=explode("\n", $_POST['pages']);
        $domain_list=explode("\n",$_POST['domains']);
        $count_domains=count($domain_list);
        $count_pages=count($page_list);
        for ($i=0; $i <$count_domains; $i++) {
            for ($j=0; $j <$count_pages; $j++) {
                $domain=trim($domain_list[$i]);
                $page=trim($page_list[$j]);
                $url=str_replace('visahq.com', $domain, $page);
                $code=getStatusCode($url);
                if ($code!=200) {

                }else{
                    $url_list[]=$url;
                    //$urllol.=$url.' '.$code.'<br>';
                }
            }
        }
        if (count($url_list)>1) {
            $count_url=count($url_list);
            for ($i=0; $i <$count_url; $i++) {
                $html_content=getContent($url_list[$i]);
                $html = phpQuery::newDocumentHTML($html_content);
                $description=$html->find("meta[name='description']")->attr('content');
                if ($description!='') {
                    $result['description'][$url_list[$i]]=$description;
                }
                $title=$html->find("title")->text();
                if ($title!='') {
                    $result['title'][$url_list[$i]]=$title;
                }
            }
            if (isset($result['description'])) {
                $duplicates_desc=findDuplicate($result['description']);
            }
            if (isset($result['title'])) {
                $duplicates_title=findDuplicate($result['title']);
            }
            $duplicates=array_combine($duplicates_desc,$duplicates_title);
            foreach($duplicates as $key => $val){
                $str.="<tr><td>$key</td><td>$val</td></tr>";
            }
            if($str==''){
                $str="<tr><td>Duplicates descriptions not found</td><td>Duplicates descriptions not found</td></tr>";
            }
            echo $str;
        }
    }
}
if($_POST['action']=='checkRobots') {
    $domains = $_POST['domains'];
    $robots  = $_POST['robots'];
    $resultArr=robot_test($domains,$robots);
    foreach ($resultArr as $value) {
        foreach ($value as $key => $val) {
            $str.="<tr><td>$key</td><td>$val</td>";
        }

    }
    echo $str;
}


