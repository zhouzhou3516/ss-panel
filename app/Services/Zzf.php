<?php
namespace App\Services;

use DOMDocument;
/**
 * Created by PhpStorm.
 * User: qingzhli
 * Date: 26/02/2017
 * Time: 11:39 PM
 */

class Zzf
{
    public static function zzfggcx()
    {
        $base_url='http://www.bjjs.gov.cn';
        $tzgx_list_url='http://www.bjjs.gov.cn/bjjs/fwgl/zzxspzf/tzgg/index.shtml';
        $doc =  new DOMDocument();
        $doc->loadHTMLFile($tzgx_list_url);
        $all_li_items = $doc->getElementsByTagName("li");

        $res_string=null;
        Date_default_timezone_set("PRC");
        foreach ($all_li_items as $item){
            if($item->childNodes->length) {
                $firstNode=null;
                foreach($item->childNodes as $key=>$i) {
                    if($key==0 ){
                        $firstNode = $i;
                    }
                    $ggdate = $i->nodeValue;
                    //if($i -> nodeValue == '2017-02-22')
                    $dataPattern = "/^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$/";

                    if(preg_match($dataPattern, $ggdate) and $ggdate > date("Y-m-d"))
                        //if($i -> nodeValue == date("Y-m-d"))
                    {
                        $c_url = $base_url.$firstNode->getAttributeNode('href')->value;
                        $title = $firstNode->nodeValue;
                        $line  = '<a href=\''.$c_url.'\'>'.$title.'</a>  '.$ggdate.'<br>';
                        $res_string = $res_string.$line;


                    }

                }
            }
        }
        return $res_string ;
    }
}