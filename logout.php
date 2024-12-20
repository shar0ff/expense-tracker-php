<?php

/**
* User Logout Script
* 
* This script handles the user logout functionality. It terminates the session
* and redirects the user to the home page.
*/

require 'common.php';

// Destroy the current session
session_destroy();

// Redirect to the home page
header('Location: index.php');
exit;