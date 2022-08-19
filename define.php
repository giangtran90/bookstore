<?php
//==============================PATHS==============================//

define('DS'                     , '/'); // dinh nghia day xuyec
define('ROOT_PATH'              , dirname(__FILE__)); // dinh nghia duong dan den thu muc goc
define('LIBRARY_PATH'           , ROOT_PATH . DS . 'libs' . DS); // dinh nghia duong dan den thu muc thu vien
define('LIBRARY_EXTENDS_PATH'   , LIBRARY_PATH . 'extends' . DS); // dinh nghia duong dan den thu muc thu vien extends
define('PUBLIC_PATH'            , ROOT_PATH . DS . 'public' . DS); // dinh nghia duong dan den thu muc public
define('UPLOAD_PATH'            , PUBLIC_PATH . 'files' . DS); // dinh nghia duong dan den thu muc files
define('SCRIPT_PATH'            , PUBLIC_PATH . 'scripts' . DS); // dinh nghia duong dan den thu muc scripts
define('APPLICATION_PATH'       , ROOT_PATH . DS . 'application' . DS); // dinh nghia duong dan den thu muc application
define('MODULE_PATH'            , APPLICATION_PATH . 'module' . DS); // dinh nghia duong dan den thu muc module
define('BLOCK_PATH'             , APPLICATION_PATH . 'block' . DS); // dinh nghia duong dan den thu muc block
define('TEMPLATE_PATH'          , PUBLIC_PATH  . 'template' . DS); // dinh nghia duong dan den thu muc template path

define('ROOT_URL'           , DS . 'bookstore' . DS);
define('APPLICATION_URL'    , ROOT_URL . 'application' . DS);
define('PUBLIC_URL'         , ROOT_URL . 'public' . DS); // dinh nghia duong dan tuong doi
define('UPLOAD_URL'         , PUBLIC_URL . 'files' . DS); // dinh nghia duong dan tuong doi files
define('TEMPLATE_URL'       , PUBLIC_URL . 'template' . DS); // dinh nghia duong dan tuong doi template

define('DEFAULT_MODULE'     , 'default'); // dinh nghia module mac dinh->gia tri thuong su dung
define('DEFAULT_CONTROLLER' , 'index'); 
define('DEFAULT_ACTION'     , 'index'); 

//==============================DATABASE==============================//

define('DB_HOST'            , 'localhost');
define('DB_USERS'           , 'root');
define('DB_PASS'            , '');
define('DB_NAME'            , 'bookstore');
define('DB_TABLE'           , 'group');

//==============================DATABASE==============================//

define('TBL_GROUP'          , 'group');
define('TBL_USER'           , 'user');
define('TBL_PRIVILEGE'      , 'privilege');
define('TBL_CATEGORY'       , 'category');
define('TBL_BOOK'           , 'book');
define('TBL_CART'           , 'cart');

//==============================CONFIG==============================//

define('TIME_LOGIN'         , 3600);
