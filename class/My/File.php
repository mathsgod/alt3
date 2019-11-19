<?php

namespace My;
class File {
    private $name = "";
    public static function _($name) {
        return new File($name);
    }
    public function __construct($name) {
        $this->name = $name;
    }

    public function exists() {
        return file_exists($this->name);
    }

    public function delete() {
        if (file_exists($this->name)) {
            unlink($this->name);
        }
    }

    public function download($filename) {
        $filename = urlencode($filename);
        if (strstr($_SERVER["HTTP_USER_AGENT"], "MSIE") == false) {
            header("Content-type: application/force-download");
            header("Content-Disposition: inline; filename=\"{$filename}\"");
            header("Content-Length: " . filesize($this->name));
        } else {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($this->name));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
        }
        readfile($this->name);
    }

    public function permission() {
        return substr(sprintf('%o', fileperms($this->name)), - 4);
    }
}

?>