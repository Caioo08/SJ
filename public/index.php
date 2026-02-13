<?php
session_start();

require_once '../app/helpers/Csrf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Csrf::abortIfInvalid();
}

require_once '../config/database.php';

require_once '../routes/web.php';
