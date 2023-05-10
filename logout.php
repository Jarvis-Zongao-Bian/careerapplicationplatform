<?php
session_start();
session_destroy();
require "common.php";
// Redirect to the login page:
gotoPage("'index.php'");