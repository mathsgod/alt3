<?php
namespace My;

class utf8encode_filter extends \php_user_filter {
    function filter($in, $out, &$consumed, $closing) {
        while ($bucket = stream_bucket_make_writeable($in)) {
            $bucket->data = iconv("BIG5", "UTF-8", $bucket->data);
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);
        }
        return PSFS_PASS_ON;
    }
}

class CSV extends \ArrayIterator {
    public $columns = [];

    public function __construct($file = null, $delimiter = ',', $big5 = true) {
        if (isset($file)) {
            $fp = fopen($file, 'r');
            if ($big5) {
                stream_filter_register("utf8encode", "utf8encode_filter");
                stream_filter_prepend($fp, "utf8encode");
            }

            $this->columns = fgetcsv($fp, 0, $delimiter);

            while (($d = fgetcsv($fp, 0, $delimiter)) !== false) {
                $row = [];
                foreach($d as $k => $c) {
                    $row[ $this->columns[$k] ] = $c;
                }
                $this[] = $row;
            }
            fclose($fp);
        }
    }

    public function save($file, $bom = true) {
        $fp = fopen($file, 'w');
        fputcsv($fp, $this->columns);
        foreach ($this as $row) {
            fputcsv($fp, $row);
        }
        fclose($fp);

        if ($bom) {
            $content = file_get_contents($file);
            file_put_contents($file, pack('CCC', 0xef, 0xbb, 0xbf) . $content);
        }
    }
}