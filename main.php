
<?php
	$serverName = "RDTEST\\SQLEXPRESS"; 

	// Since UID and PWD are not specified in the $connectionInfo array,
	// The connection will be attempted using Windows Authentication.

	$connectionInfo = array( "Database"=>"DB_MMMS_R326_PD");
	$conn = sqlsrv_connect( $serverName, $connectionInfo);

	if( $conn ) {
	     echo "Connection established.<br />";
	}else{
	     echo "Connection could not be established.<br />";
	     die( print_r( sqlsrv_errors(), true));
	}

	$sql = 

	// Close the connection.
	sqlsrv_close( $conn );
?>


