<?

namespace App;

class User extends Core\User
{
    use ModelTrait;

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

    public function isOnline()
    {
        $time = strtotime($this->last_online);

        if (time() - $time > 300) {
            return false;
        }
        return true;
    }
}
