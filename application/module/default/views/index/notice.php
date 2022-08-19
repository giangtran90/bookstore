<?php
$message = '';
switch ($this->arrParam['type']) {
    case 'register-success':
        $message = 'Tài khoản của bạn đã được tạo thành công. Vui lòng chờ kích hoạt từ quản trị viên!';
        break;
    case 'not-permission':
        $message = 'Bạn không có quyền truy cập vào chức năng đó!';
        break;
    case 'not-url':
        $message = 'Đường dẫn không hợp lệ!';
        break;
}
?>
<div class="notice"><?= $message; ?></div>