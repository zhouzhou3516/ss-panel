<?php

namespace App\Command;

/***
 * Class XCat
 * @package App\Command
 */

use App\Models\User;
use App\Services\Config;
use App\Utils\Hash;
use App\Utils\Tools;
use App\Services\Zzf;
use App\Services\Mail;


class XCat
{

    public $argv;

    public function __construct($argv)
    {
        $this->argv = $argv;
    }

    public function boot()
    {
        switch ($this->argv[1]) {
            case("install"):
                return $this->install();
            case("zzfggcx"):
                return $this->zzfggcx();
            case("createAdmin"):
                return $this->createAdmin();
            case("resetTraffic"):
                return $this->resetTraffic();
            case("sendDiaryMail"):
                return DailyMail::sendDailyMail();
            default:
                return $this->defaultAction();
        }
    }

    public function defaultAction()
    {
    }

    public function install()
    {
        echo "x cat will install ss-panel v3...../n";
    }

    public function createAdmin()
    {
        echo "add admin/ 创建管理员帐号.....";
        // ask for input
        fwrite(STDOUT, "Enter your email/输入管理员邮箱: ");
        // get input
        $email = trim(fgets(STDIN));
        // write input back
        fwrite(STDOUT, "Enter password for: $email / 为 $email 添加密码 ");
        $passwd = trim(fgets(STDIN));
        echo "Email: $email, Password: $passwd! ";
        fwrite(STDOUT, "Press [Y] to create admin..... 按下[Y]确认来确认创建管理员账户..... ");
        $y = trim(fgets(STDIN));
        if (strtolower($y) == "y") {
            echo "start create admin account";
            // create admin user
            // do reg user
            $user = new User();
            $user->user_name = "admin";
            $user->email = $email;
            $user->pass = Hash::passwordHash($passwd);
            $user->passwd = Tools::genRandomChar(6);
            $user->port = Tools::getLastPort() + 1;
            $user->t = 0;
            $user->u = 0;
            $user->d = 0;
            $user->transfer_enable = Tools::toGB(Config::get('defaultTraffic'));
            $user->invite_num = Config::get('inviteNum');
            $user->ref_by = 0;
            $user->is_admin = 1;
            if ($user->save()) {
                echo "Successful/添加成功!";
                return true;
            }
            echo "添加失败";
            return false;
        }
        echo "cancel";
        return false;
    }

    public function resetTraffic()
    {
        try {
            User::where("enable", 1)->update([
                'd' => 0,
                'u' => 0,
            ]);
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
        return "reset traffic successful";
    }

    public function zzfggcx()
    {
        $ret = Zzf::zzfggcx();
        $title = '自住房-' . (($ret == null)?'没有新公告':'有新公告') . '-QingZhouLee';
        $body  = ($ret == null? '没有新公告':$ret);
        $to_array = null;
        if (Config::get('debug') == "true") {
            $to_array = array('735338750@qq.com');
        }else{
            $to_array = array("1025541660@qq.com",'735338750@qq.com','1009056230@qq.com','357890780@qq.com','781598458@qq.com','nanjx1229@163.com');
        }
        //
        if($ret != null )
        {
            foreach ($to_array as $to)
            {
                Mail::sendSMTP($to,$title,$body);
            }
        }

        //Mail::sendSMTP('735338750@qq.com;1025541660@qq.com',$title,$body);
        print('sent email');
    }
}