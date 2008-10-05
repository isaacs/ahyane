#!/usr/bin/env php
<?php

require( dirname(__FILE__) . '/main.php' );
array_shift($argv);
Builder::make(Config::readArgs($argv));
