<?php
use App\UserLog;
use App\User;
class UserLog_list1 extends App\Page {
    public function get() {
        $jq = $this->createDT([$this, "ds"]);

        $jq->order("userlog_id", "desc");
        $jq->add("ID", "userlog_id")->order();//->sort()->searchEq();
        $jq->add("User", "user_id")->searchOption(User::find());
        $jq->add("Login time", "login_dt")->order()->searchDate();
        $jq->add("Logout time", "logout_dt")->order()->searchDate();
        $jq->add("IP address", "ip")->ss();
        $jq->add("Result", "result")->order()->searchOption(array("SUCCESS" => "SUCCESS", "FAIL" => "FAIL"));
        $jq->add("User agent", "user_agent")->search();

        $this->write($jq);
    }

    public function ds($r) {

        $r=$this->createDTResponse(UserLog::Query());
        $r->fields=["userlog_id","login_dt","logout_dt","ip","result","user_agent"];
        
        $r->add("user_id","User()");

        return  $r;
        $w = $jq->where();

        return array("total" => UserLog::count($w),
            "data" => UserLog::find($w, $jq->Order(), $jq->Limit()));
    }
}

?>