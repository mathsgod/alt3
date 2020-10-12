var scripts = document.getElementsByTagName("script"),
    src = scripts[scripts.length - 1].src;
import path from 'path';

__webpack_public_path__ = path.dirname(src);