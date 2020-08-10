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

$dbc = \sys::dbCheck( 'registrations');

$dbc->defineField( 'event', 'varchar');
$dbc->defineField( 'name', 'varchar', 100);
$dbc->defineField( 'party', 'varchar');
$dbc->defineField( 'phone', 'varchar');
$dbc->defineField( 'address', 'varchar', 100);
$dbc->defineField( 'created', 'datetime');

$dbc->check();
