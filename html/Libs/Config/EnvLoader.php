<?php
namespace Libs\Config;

use Config\DirectorySettings;

class EnvLoader {
    function loadEnv() {
        $filePath = DirectorySettings::APPLICATION_ROOT_DIR . '.env';
        if (!file_exists($filePath)) {
            throw new \Exception('.env file not found');
        }
    
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            // コメント行を無視
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
    
            // 環境変数名と値を分割
            list($name, $value) = explode('=', $line, 2);
    
            // 値の前後の空白を削除
            $name = trim($name);
            $value = trim($value);
    
            // 値が囲まれていれば取り除く
            if (preg_match('/^["\'](.*)["\']$/', $value, $matches)) {
                $value = $matches[1];
            }
    
            // 環境変数を設定
            define($name, $value);
        }
    }
}