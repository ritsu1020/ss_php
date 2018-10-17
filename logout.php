<?php

// logout.php

require_once 'config.php';
require_once 'functions.php';

session_start();

if (!isset($_SESSION['USER'])) {

      header('Locatio');
}

?>
