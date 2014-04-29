<?php
/*
Plugin Name: Hello-World
Plugin URI: http://yourdomain.com/
Description: A simple hello world wordpress plugin
Version: 1.0
Author: Balakrishnan
Author URI: http://yourdomain.com
License: GPL
*/
/* This calls hello_world() function when wordpress initializes.*/
/* Note that the hello_world doesnt have brackets.
add_action('init','hello_world');
function hello_world()
{
echo "Hello World";
}
?>