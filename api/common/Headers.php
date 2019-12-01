<?php

header("X-XSS-Protection: 1; mode=block");
header('X-Content-Type-Options: nosniff');
header("Strict-Transport-Security: max-age=63072000; includeSubDomains; preload");
header('X-Frame-Options: SAMEORIGIN');

?>
