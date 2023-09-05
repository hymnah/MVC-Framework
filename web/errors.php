<?php


if (!isset($_GET['code']) && empty($_GET['code'])) {
    exit(0);
}

$code = $_GET['code'];
echo file_get_contents('../src/Views/Errors/' . $code . '.html');