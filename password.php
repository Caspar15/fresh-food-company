<?php
$plainPassword = "123456";  // 您想設置的明文密碼
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
echo $hashedPassword;
?>
