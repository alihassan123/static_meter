
<?php
	$serverName = "RDTEST\\SQLEXPRESS"; 
	$connectionInfo = array( "Database"=>"DB_MMMS_R326_PD");
	$conn = sqlsrv_connect($serverName, $connectionInfo);
	check_connection_error($conn);

	function select_latest_entry_from_table($table_name , $col_name){
		global $conn;
		$stmt = "SELECT TOP 1 [$col_name] FROM [DB_MMMS_R326_PD].[dbo].[$table_name]  ORDER BY LogDateTime DESC";
		$stmt = sqlsrv_query($conn , $stmt);
		check_query_error($stmt);
		$obj  = sqlsrv_fetch_object($stmt);
		$obj  = bin2hex($obj->DataRead) . '<br>';
		// echo $obj;
		// return $obj;
		$obj_len = strlen($obj);
		$obj_seprate = '';
		for($i = 0; $i < $obj_len-2; $i+=2){
			$obj_seprate .= $obj[$i].$obj[$i+1].' ';
		}
		$obj_seprate = explode(" ",$obj_seprate);
		array_pop($obj_seprate);
		array_pop($obj_seprate);
		return $obj_seprate;
	}

	function check_connection_error($conn){
		if( $conn ) {
		     echo "Connection established.<br />";
		}else{
		     echo "Connection could not be established.<br />";
		     die( print_r( sqlsrv_errors(), true));
		}
	}

	function check_query_error($stmt){
		if( $stmt ) {
		     echo "Statement is valid.<br />";
		}else{
		     echo "Statement is invalid.<br />";
		     die( print_r( sqlsrv_errors(), true));
		}
	}



	$instantaneous_raw_data = select_latest_entry_from_table('InstantaneousData' , 'DataRead');
	print_r($instantaneous_raw_data);
	echo '<br>';

	function extract_instantaneous_data($instantaneous_raw_data){
		// voltage
		$voltage_phase_a = hexdec($instantaneous_raw_data[20].$instantaneous_raw_data[19])/100;
		echo 'voltage_phase_a : '.$voltage_phase_a.'<br>';
		$voltage_phase_b = hexdec($instantaneous_raw_data[22].$instantaneous_raw_data[21])/100;
		echo 'voltage_phase_b : '.$voltage_phase_b.'<br>';
		$voltage_phase_c = hexdec($instantaneous_raw_data[24].$instantaneous_raw_data[23])/100;
		echo 'voltage_phase_c : '.$voltage_phase_c.'<br>';

		// current
		$current_phase_a = hexdec($instantaneous_raw_data[26].$instantaneous_raw_data[25])/100;
		echo 'current_phase_a : '.$current_phase_a.'<br>';
		$current_phase_b = hexdec($instantaneous_raw_data[28].$instantaneous_raw_data[27])/100;
		echo 'current_phase_b : '.$current_phase_b.'<br>';
		$current_phase_c = hexdec($instantaneous_raw_data[30].$instantaneous_raw_data[29])/100;
		echo 'current_phase_c : '.$current_phase_c.'<br>';
		$current_all_phases = $current_phase_a + $current_phase_b + $current_phase_c;
		echo 'current_all_phases : '.$current_all_phases.'<br>';

		// active
		echo '<br>DEBUG<br>';
		echo (((hexdec($instantaneous_raw_data[32].$instantaneous_raw_data[31]) & 32768) != 0)?'-':'').(hexdec($instantaneous_raw_data[32].$instantaneous_raw_data[31])^32768)/1000;
		echo '<br>DEBUG<br>';

		$active_power_phase_a = hexdec($instantaneous_raw_data[32].$instantaneous_raw_data[31])/1000;
		echo 'active_power_phase_a : '.$active_power_phase_a.'<br>';
		$active_power_phase_b = hexdec($instantaneous_raw_data[34].$instantaneous_raw_data[33])/1000;
		echo 'active_power_phase_b : '.$active_power_phase_b.'<br>';
		$active_power_phase_c = hexdec($instantaneous_raw_data[36].$instantaneous_raw_data[35])/1000;
		echo 'active_power_phase_c : '.$active_power_phase_c.'<br>';
		$active_power_all_phases = $active_power_phase_a + $active_power_phase_b + $active_power_phase_c;
		echo 'active_power_all_phases : '.$active_power_all_phases.'<br>';

		// reactive
		$reactive_power_phase_a = hexdec($instantaneous_raw_data[38].$instantaneous_raw_data[37])/1000;
		echo 'reactive_power_phase_a : '.$reactive_power_phase_a.'<br>';
		$reactive_power_phase_b = hexdec($instantaneous_raw_data[40].$instantaneous_raw_data[39])/1000;
		echo 'reactive_power_phase_b : '.$reactive_power_phase_b.'<br>';
		$reactive_power_phase_c = hexdec($instantaneous_raw_data[42].$instantaneous_raw_data[41])/1000;
		echo 'reactive_power_phase_c : '.$reactive_power_phase_c.'<br>';
		$reactive_power_all_phases = $reactive_power_phase_a + $reactive_power_phase_b + $reactive_power_phase_c;
		echo 'reactive_power_all_phases : '.$reactive_power_all_phases.'<br>';

		// apparent
		$apparent_power_phase_a = hexdec($instantaneous_raw_data[44].$instantaneous_raw_data[43])/1000;
		echo 'apparent_power_phase_a : '.$apparent_power_phase_a.'<br>';
		$apparent_power_phase_b = hexdec($instantaneous_raw_data[46].$instantaneous_raw_data[45])/1000;
		echo 'apparent_power_phase_b : '.$apparent_power_phase_b.'<br>';
		$apparent_power_phase_c = hexdec($instantaneous_raw_data[48].$instantaneous_raw_data[47])/1000;
		echo 'apparent_power_phase_c : '.$apparent_power_phase_c.'<br>';
		$apparent_power_all_phases = $apparent_power_phase_a + $apparent_power_phase_b + $apparent_power_phase_c;
		echo 'apparent_power_all_phases : '.$apparent_power_all_phases.'<br>';

		// power factor
		$power_factor_phase_a = hexdec($instantaneous_raw_data[65])/100;
		echo 'power_factor_phase_a : '.$power_factor_phase_a.'<br>';
		$power_factor_phase_b = hexdec($instantaneous_raw_data[66])/100;
		echo 'power_factor_phase_b : '.$power_factor_phase_b.'<br>';
		$power_factor_phase_c = hexdec($instantaneous_raw_data[67])/100;
		echo 'power_factor_phase_c : '.$power_factor_phase_c.'<br>';
		$power_factor_all_phases = ($power_factor_phase_a + $power_factor_phase_b + $power_factor_phase_c)/3;
		echo 'power_factor_all_phases : '.$power_factor_all_phases.'<br>';

		// power quadrant
		$power_quadrant_phase_a = hexdec($instantaneous_raw_data[65])/100;
		echo 'power_quadrant_phase_a : '.$power_quadrant_phase_a.'<br>';
		$power_quadrant_phase_b = hexdec($instantaneous_raw_data[66])/100;
		echo 'power_quadrant_phase_b : '.$power_quadrant_phase_b.'<br>';
		$power_quadrant_phase_c = hexdec($instantaneous_raw_data[67])/100;
		echo 'power_quadrant_phase_c : '.$power_quadrant_phase_c.'<br>';
		$power_quadrant_all_phases = ($power_quadrant_phase_a + $power_quadrant_phase_b + $power_quadrant_phase_c)/3;
		echo 'power_quadrant_all_phases : '.$power_quadrant_all_phases.'<br>';
	}

	extract_instantaneous_data($instantaneous_raw_data);


	sqlsrv_close( $conn );
?>


