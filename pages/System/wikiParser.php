<?php

class System_wikiParser extends ALT\Page {
    public function get() {
        return;
        $wp = $this->addLib("wikiParser");
        $content = file_get_contents(CMS_ROOT . $wp . "/examples/example.wiki");
        $c1 = nl2br($content);

        $wp = new wikiParser();
        $c2 = $wp->parse($content);
        $this->write(<<<EOT
<div>
	<div class="col-xs-6">
		$c1
	</div>
	<div class="col-xs-6">
		$c2
	</div>
</div>


EOT
            );
    }
}