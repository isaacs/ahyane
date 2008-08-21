#!/opt/local/bin/php
<?php

require( dirname(__FILE__) . '/main.php' );
array_shift($argv);
Builder::make($argv);
