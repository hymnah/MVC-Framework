<?php

$blocks = [];
$extends = [];
$currentType = '';

function blockStart($type) {
    global $blocks;
    global $currentType;

    $currentType = $type;
    $blocks[$currentType] = [
        'opened' => true
    ];
    ob_start();
}

function blockEnd() {
    global $blocks;
    global $currentType;

    $var = ob_get_contents();
    ob_end_clean();

    $blocks[$currentType] += [
        'content' => $var,
        'closed' => 'true'
    ];
}

function getBlock($type) {
    global $blocks;
    if (isset($blocks[$type]['opened']) && !isset($blocks[$type]['closed'])) {
        throw new \Exceptions\CriticalException('Block "' . $type . '" is not closed');
    }

    if (isset($blocks[$type])) {
        return $blocks[$type]['content'];
    }
}

function extendPage($page) {
    global $extends;
    $extends[] = $page;
}
