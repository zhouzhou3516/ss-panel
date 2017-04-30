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
        $myfile = fopen("./storage/logs/zzfnews.log",'a+') or die("Unable to open file:zzfnews.log!");

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
                    $dataPattern = "/^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$/";

                    if(preg_match($dataPattern, $ggdate) and $ggdate == date("Y-m-d"))
                        //if($i -> nodeValue == date("Y-m-d"))
                    {
                        $c_url = $base_url.$firstNode->getAttributeNode('href')->value;
                        $title = $firstNode->nodeValue;
                        $line  = '<a href=\''.$c_url.'\'>'.$title.'</a>  '.$ggdate.'<br>';

                        if(self::isexits($title.'-'.$ggdate."\n")==0){
                            fwrite($myfile,$title.'-'.$ggdate."\n");
                            $res_string = $res_string.$line;
                        }


                    }

                }
            }
        }
        return $res_string ;
    }

    /**
     * check if title already exists in zzfnews.log
     * @param $title
     * @return int
     */
    public static function isexits($title){
        $is_exist = 0;
        if(file_exists("./storage/logs/zzfnews.log") and !empty($title)){
            $myfile = fopen("./storage/logs/zzfnews.log",'r') or die("Unable to open file:zzfnews.log!");
            // 输出单行直到 end-of-file
            while(!feof($myfile)) {
                $line = fgets($myfile);
                if ($line == $title) {
                    $is_exist=1;
                }
            }
            fclose($myfile);
        }
        return $is_exist;
    }
}