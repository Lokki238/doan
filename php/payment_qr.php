<?php
/**
 * Chuyển tiếp tới giao diện HTML thanh toán VietQR
 */
$queryString = $_SERVER['QUERY_STRING'] ? ('?' . $_SERVER['QUERY_STRING']) : '';
header("Location: ../html/payment_qr.html" . $queryString);
exit;
