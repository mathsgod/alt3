<?php
// Created By: Raymond Chong
// Created Date: 23/8/2010
// Last Updated: 2013-04-10
class System_phpinfo extends ALT\Page
{
    public function get()
    {
        $this->write('<iframe src="System/phpinfo/phpinfo" width="100%" height="800px"></iframe>');
    }

    public function phpinfo()
    {
        phpinfo();
    }
}
