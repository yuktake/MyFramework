<?php
namespace Libs\Views;

use config\DirectorySettings;

class View
{
    protected array $_defaultData = [];

    public function __construct() {
        // envから参照すると良いかもしれない
        $this->_defaultData = [
            'title' => 'Title',
            'description' => 'Description',
        ];
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

        $currentDir = dirname($_file);
        $currentPath = str_replace(DirectorySettings::TEMPLATES_ROOT_DIR, '', dirname($_file).'/');

        // 該当ファイルと同じ階層から見て、layouts/app.phpが存在する場合はそれを読み込む。
        // 階層ごとに使用するレイアウトを変えることができる。
        if (file_exists($currentDir . '/layouts/app.php')) {
            $layout_components = scandir($currentDir . '/layouts/');
            $variables = [];
            foreach($layout_components as $layout_component) {
                if ($layout_component === 'app.php' || $layout_component === '.' || $layout_component === '..') {
                    continue;
                }
                $filename = pathinfo($layout_component, PATHINFO_FILENAME);
                ob_start();
                require $currentDir . '/layouts/' . $filename . '.php';
                // ため込んだ出力を$contentに代入します。
                $variables[$filename] = ob_get_clean();
            }

            $data = array_merge($variables, [
                'content' => $content,
            ]);

            $layout_file = $currentPath . 'layouts/app';
            $content = $this->render($layout_file, $data);
        }

        return $content;
    }
}
