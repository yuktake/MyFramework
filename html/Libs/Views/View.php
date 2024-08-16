<?php
namespace Libs\Views;

use config\DirectorySettings;

class View
{
    protected array $_defaultData = [];

    public function __construct()
    {
        $this->_defaultData['escape'] = $this->escape();
    }

    public function render($_file_path_after_templates_dir, $_data = array())
    {
        $_file = DirectorySettings::TEMPLATES_ROOT_DIR . $_file_path_after_templates_dir . '.php';

        extract(array_merge($this->_defaultData, $_data));

        // echoとかの出力をため込む宣言です。
        ob_start();
        // ため込み先のバッファの上限を無効化します。
        ob_implicit_flush(0);
        require $_file;
        // ため込んだ出力を$contentに代入します。
        $content = ob_get_clean();

        return $content;
    }

    public function escape()
    {
        return function ($string, $echo = true) {
            $value = htmlspecialchars($string, ENT_QUOTES < 'UTF-8');
            if (!$echo)
                return $value;
            echo $value;
        };
    }
}
