<?php
// File: functions.php

// Only include files from /inc to keep functions.php clean and modular
foreach (glob(__DIR__ . '/inc/**/*.php') as $file) {
    require_once $file;
}


