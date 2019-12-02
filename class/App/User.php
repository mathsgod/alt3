<?

namespace App;

class User extends Core\User
{
    use ModelTrait;

    public function canUpdate(): bool
    {
        $user = self::$_app->user;
        if ($user->user_id == $this->user_id) {
            return true;
        }
        if ($user->isAdmin()) {
            return true;
        }
        return false;
    }

    public function createUserLog(string $result = null)
    {
        $r["user_id"] = $this->user_id;
        $r["login_dt"] = date("Y-m-d H:i:s");
        $r["ip"] = $_SERVER['REMOTE_ADDR'];
        $r["result"] = $result;
        $r["user_agent"] = $_SERVER['HTTP_USER_AGENT'];
        UserLog::_table()->insert($r);
    }

    public function logout()
    {
        $o = UserLog::Query([
            "user_id" => $this->user_id
        ])->orderBy("userlog_id desc")->first();

        if ($o) {
            $o->logout_dt = date("Y-m-d H:i:s");
            $o->save();
        }
    }

    public function online()
    {
        $this->update(["last_online" => date("Y-m-d H:i:s")]);
    }

    public function offline()
    { }

    public function isOnline(): bool
    {
        $time = strtotime($this->last_online);

        if (time() - $time > 300) {
            return false;
        }
        return true;
    }

    public function sendPassword(App $app): string
    {
        $password = Util::GeneratePassword();
        $e_pwd = password_hash($password, PASSWORD_DEFAULT);

        $ret = $this->update(["password" => $e_pwd]);


        $content = $app->config["user"]["forget pwd email/content"];
        $content = str_replace("{username}", $this->username, $content);
        $content = str_replace("{password}", $password, $content);

        // Send Mail
        $mm = $app->createMail();
        $mm->Subject = $app->config["user"]["forget pwd email/subject"];
        $mm->msgHTML($content);
        $mm->setFrom("admin@" . $app->config["user"]["domain"]);
        $mm->addAddress($this->email);
        $mm->Send();
        return $password;
    }
}
