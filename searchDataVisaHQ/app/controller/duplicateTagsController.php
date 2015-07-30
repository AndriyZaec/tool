<?php
/**
 * Created by PhpStorm.
 * User: andrij
 * Date: 24.07.15
 * Time: 12:10
 */

class duplicateTagsController {
    /*
     *
     * show domain and pages from files
     */

    public function showDomainAndTags(){
        $domains=file_get_contents('../../public/files/domains.txt');
        $pages=file_get_contents('../../public/files/pages.txt');
        if (isset($_POST['search'])) {
            $domains=trim($_POST['domains']);
            $pages=trim($_POST['pages']);
            if ($domains=='' and $pages=='') {
                $error_text[]='Вы не ввели никаких данных для поиска';
                $error=1;
            }
            elseif($domains=='' and $pages!=''){
                $page_list=explode("\n", $pages);
                $domain_list=array('visahq.com');
            }
            elseif($domains!='' and $pages==''){
                $error=1;
                $error_text[]='Вы не ввели страници для поиска';
            }
            elseif($domains!='' and $pages!=''){
                $domain_list=explode("\n", $domains);
                if (!in_array('visahq.com', $domain_list)) {
                    $domain_list[]='visahq.com';
                }
                $page_list=explode("\n", $pages);
            }
        }
        require('../../app/view/searchFormDataVisaHQ.php');
    }
}