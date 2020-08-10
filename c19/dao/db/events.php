<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace dao;

$dbc = \sys::dbCheck( 'events');

$dbc->defineField( 'description', 'varchar');
$dbc->defineField( 'start', 'datetime');
$dbc->defineField( 'end', 'datetime');
$dbc->defineField( 'open', 'tinyint');
$dbc->defineField( 'created', 'datetime');
$dbc->defineField( 'updated', 'datetime');

$dbc->check();
