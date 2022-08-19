<?php
require_once 'define.php';
// tu dong keo vao cac thu vien
// function __autoload($className){
//     require_once LIBRARY_PATH . "{$className}.php";
// }
function my_autoloader($className) {
    $fileName = LIBRARY_PATH . "{$className}.php";
    if(file_exists($fileName)) require_once $fileName;
}
spl_autoload_register('my_autoloader');

Session::init();

$bootstrap = new Bootstrap();
$bootstrap->init();