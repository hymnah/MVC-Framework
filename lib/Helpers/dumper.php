<?php


function dump($val = null) {
    $config = \App\AppConfiguration::getAppConfig();
    if ($config['app_env'] !== 'dev') {
        return;
    }

    echo '<style>';
    echo '* {margin:0;padding:0;}';
    echo 'pre.dumper {padding:10px;background-color: #000000; color: #ffa500;font-family: monospace;}';
    echo '</style>';
    echo '<pre class="dumper">';
    print_r($val);
    echo '</pre>';
}