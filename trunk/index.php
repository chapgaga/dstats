<?php
/*
 *
 * dstats - Utility for passive database load monitoring 
 *
 */

require_once ('./config.inc.php');

header('Content-type: text/html;charset=UTF-8');

$has_db = false;
if (!$db = mysql_connect(
			DSTATS_DB_HOSTNAME . ':' . DSTATS_DB_PORT,
			DSTATS_DB_USERNAME,
			DSTATS_DB_PASSWORD)) {
	
	die('Connection to the database is failed, check your config.inc.php please.');
} else {
	if (DSTATS_DB_SCHEMATA != '') {
		$has_db = true;
		mysql_select_db(DSTATS_DB_SCHEMATA, $db);
	}
}

$rs = mysql_query('SHOW STATUS', $db);
$status = array();
while ($row = mysql_fetch_assoc($rs)) {
	$status[$row['Variable_name']] = $row['Value'];
}
mysql_free_result($rs);

$rs = mysql_query('SHOW VARIABLES', $db);
while ($row = mysql_fetch_assoc($rs)) {
	$status[$row['Variable_name']] = $row['Value'];
}
mysql_free_result($rs);
?>
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
		<title>dstats</title>
	</head>
	<link rel="stylesheet" href="./style.css" type="text/css" />
	<body>
		<h1>dstats</h1>
		<table width="400" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td align="left" colspan="2"><h2>&nbsp;&nbsp;*&nbsp;Base Info</h2></td>
			</tr>
			<tr>
				<td align="right" width="150">MySQL version:&nbsp;&nbsp;</td><td align="left" width="250"><?php echo mysql_get_server_info($db);?></td>
			</tr>
			<tr>
				<td align="right" width="150">MySQL uptime:&nbsp;&nbsp;</td><td align="left" width="250"><?php echo $status['Uptime'] . ' seconds';?></td>
			</tr>
			<tr>
				<td align="right" width="150">Server OS:&nbsp;&nbsp;</td><td align="left" width="250"><?php echo PHP_OS;?></td>
			</tr>
			<tr>
				<td align="right" width="150">PHP version:&nbsp;&nbsp;</td><td align="left" width="250"><?php echo PHP_VERSION;?></td>
			</tr>
			<tr>
				<td align="left" colspan="2"><h2>&nbsp;&nbsp;*&nbsp;Performance Info</h2></td>
			</tr>
			<tr>
				<td align="right" width="150">Threads created:&nbsp;&nbsp;</td><td align="left" width="250"><?php echo $status['Threads_created']; printf (" (%.1f per minute)", $status['Threads_created'] / ($status['Uptime'] / 60));?></td>
			</tr>
			<tr>
				<td align="right" width="150">Questions:&nbsp;&nbsp;</td><td align="left" width="250"><?php echo $status['Questions']; printf (" (%.1f per minute)", $status['Questions'] / ($status['Uptime'] / 60));?></td>
			</tr>
			<tr>
				<td align="right" width="150">Free Qcache memory:&nbsp;&nbsp;</td><td align="left" width="250"><?php printf("%dKB",  floatval($status['Qcache_free_memory'] / 1024));?></td>
			</tr>
			<tr>
				<td align="right" width="150">Cached queries:&nbsp;&nbsp;</td><td align="left" width="250"><?php echo $status['Qcache_queries_in_cache'];?></td>
			</tr>
			<tr>
				<td align="right" width="150">Cache inserts:&nbsp;&nbsp;</td><td align="left" width="250"><?php echo $status['Qcache_inserts']; printf (" (%.1f per minute)", $status['Qcache_inserts'] / ($status['Uptime'] / 60));?></td>
			</tr>
			<tr>
				<td align="right" width="150">Cache hits:&nbsp;&nbsp;</td><td align="left" width="250"><?php echo $status['Qcache_hits']; printf (" (%.1f per minute)", $status['Qcache_hits'] / ($status['Uptime'] / 60));?></td>
			</tr>
			<tr>
				<td align="right" width="150">Cache hits ratio:&nbsp;&nbsp;</td><td align="left" width="250"><?php printf ("%.2f%%", ($status['Qcache_hits'] / $status['Questions']) * 100);?></td>
			</tr>
			<tr>
				<td align="right" width="150">Not cached queries:&nbsp;&nbsp;</td><td align="left" width="250"><?php echo $status['Qcache_not_cached']; printf (" (%.1f per minute)", $status['Qcache_not_cached'] / ($status['Uptime'] / 60));?></td>
			</tr>
		</table>
		<span class="copyright">Usage: dstats is a simple tool helps you to get an overview of your MySQL server.<br />Version: 0.0.1<br /><br />&copy; 2006 <a href="http://www.livid.cn/" target="_blank">Livid Torvalds</a><br /><a href="http://www.v2ex.com/" target="_blank">V2EX</a> | software for internet</span>
	</body>
</html>