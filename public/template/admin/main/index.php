<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
    <?= $this->_metaHTTP; ?>
    <?= $this->_metaName; ?>
    <title><?= $this->_title; ?></title>
    <?= $this->_cssFiles; ?>
    <?= $this->_jsFiles; ?>
</head>

<body>
    <!--------------------- HEADER --------------------->
    <?php
    include_once 'html/header.php';
    ?>
    <!--------------------- LOAD CONTENT --------------------->
    <div id="content-box">
        <?php
        require_once MODULE_PATH . $this->_moduleName . DS . 'views' . DS . $this->_fileView . '.php';
        ?>
    </div>
    <!--------------------- FOOTER --------------------->
    <?php
    include_once 'html/footer.php';
    ?>