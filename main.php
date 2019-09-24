
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
	// print_r($instantaneous_raw_data);
	echo '<br>';
	// print_r($instantaneous_raw_data);
	echo '<br>';

	function extract_instantaneous_data($instantaneous_raw_data){
		// voltage
		$voltage_phase_a = hexdec($instantaneous_raw_data[20].$instantaneous_raw_data[19])/100;
		// echo 'voltage_phase_a : '.$voltage_phase_a.'<br>';
		$voltage_phase_b = hexdec($instantaneous_raw_data[22].$instantaneous_raw_data[21])/100;
		// echo 'voltage_phase_b : '.$voltage_phase_b.'<br>';
		$voltage_phase_c = hexdec($instantaneous_raw_data[24].$instantaneous_raw_data[23])/100;
		// echo 'voltage_phase_c : '.$voltage_phase_c.'<br>';

		// current
		$current_phase_a = hexdec($instantaneous_raw_data[26].$instantaneous_raw_data[25])/100;
		// echo 'current_phase_a : '.$current_phase_a.'<br>';
		$current_phase_b = hexdec($instantaneous_raw_data[28].$instantaneous_raw_data[27])/100;
		// echo 'current_phase_b : '.$current_phase_b.'<br>';
		$current_phase_c = hexdec($instantaneous_raw_data[30].$instantaneous_raw_data[29])/100;
		// echo 'current_phase_c : '.$current_phase_c.'<br>';
		$current_all_phases = round($current_phase_a + $current_phase_b + $current_phase_c,3);
		// echo 'current_all_phases : '.$current_all_phases.'<br>';

		// active
		$active_power_phase_a = (((hexdec($instantaneous_raw_data[32].$instantaneous_raw_data[31]) & 32768) != 0)?'-':'').(hexdec($instantaneous_raw_data[32].$instantaneous_raw_data[31])&32767)/1000;
		// echo 'active_power_phase_a : '.$active_power_phase_a.'<br>';
		$active_power_phase_b = (((hexdec($instantaneous_raw_data[34].$instantaneous_raw_data[33]) & 32768) != 0)?'-':'').(hexdec($instantaneous_raw_data[34].$instantaneous_raw_data[33])&32767)/1000;;
		// echo 'active_power_phase_b : '.$active_power_phase_b.'<br>';
		$active_power_phase_c = (((hexdec($instantaneous_raw_data[36].$instantaneous_raw_data[35]) & 32768) != 0)?'-':'').(hexdec($instantaneous_raw_data[36].$instantaneous_raw_data[35])&32767)/1000;;
		// echo 'active_power_phase_c : '.$active_power_phase_c.'<br>';
		$active_power_all_phases = round(abs($active_power_phase_a) + abs($active_power_phase_b) + abs($active_power_phase_c),3);
		// echo 'active_power_all_phases : '.$active_power_all_phases.'<br>';

		// reactive
		$reactive_power_phase_a = (((hexdec($instantaneous_raw_data[38].$instantaneous_raw_data[37]) & 32768) != 0)?'-':'').(hexdec($instantaneous_raw_data[38].$instantaneous_raw_data[37])&32767)/1000;
		// echo 'reactive_power_phase_a : '.$reactive_power_phase_a.'<br>';
		$reactive_power_phase_b = (((hexdec($instantaneous_raw_data[40].$instantaneous_raw_data[39]) & 32768) != 0)?'-':'').(hexdec($instantaneous_raw_data[40].$instantaneous_raw_data[39])&32767)/1000;
		// echo 'reactive_power_phase_b : '.$reactive_power_phase_b.'<br>';
		$reactive_power_phase_c = (((hexdec($instantaneous_raw_data[42].$instantaneous_raw_data[41]) & 32768) != 0)?'-':'').(hexdec($instantaneous_raw_data[42].$instantaneous_raw_data[41])&32767)/1000;
		// echo 'reactive_power_phase_c : '.$reactive_power_phase_c.'<br>';
		$reactive_power_all_phases = round(abs($reactive_power_phase_a) + abs($reactive_power_phase_b) + abs($reactive_power_phase_c),3);
		$current_all_phases = $current_phase_a + $current_phase_b + $current_phase_c;
		// echo 'current_all_phases : '.$current_all_phases.'<br>';

		// active
		// echo '<br>DEBUG<br>';
		echo (((hexdec($instantaneous_raw_data[32].$instantaneous_raw_data[31]) & 32768) != 0)?'-':'').(hexdec($instantaneous_raw_data[32].$instantaneous_raw_data[31])^32768)/1000;
		// echo '<br>DEBUG<br>';

		$active_power_phase_a = hexdec($instantaneous_raw_data[32].$instantaneous_raw_data[31])/1000;
		// echo 'active_power_phase_a : '.$active_power_phase_a.'<br>';
		$active_power_phase_b = hexdec($instantaneous_raw_data[34].$instantaneous_raw_data[33])/1000;
		// echo 'active_power_phase_b : '.$active_power_phase_b.'<br>';
		$active_power_phase_c = hexdec($instantaneous_raw_data[36].$instantaneous_raw_data[35])/1000;
		// echo 'active_power_phase_c : '.$active_power_phase_c.'<br>';
		$active_power_all_phases = $active_power_phase_a + $active_power_phase_b + $active_power_phase_c;
		// echo 'active_power_all_phases : '.$active_power_all_phases.'<br>';

		// reactive
		$reactive_power_phase_a = hexdec($instantaneous_raw_data[38].$instantaneous_raw_data[37])/1000;
		// echo 'reactive_power_phase_a : '.$reactive_power_phase_a.'<br>';
		$reactive_power_phase_b = hexdec($instantaneous_raw_data[40].$instantaneous_raw_data[39])/1000;
		// echo 'reactive_power_phase_b : '.$reactive_power_phase_b.'<br>';
		$reactive_power_phase_c = hexdec($instantaneous_raw_data[42].$instantaneous_raw_data[41])/1000;
		// echo 'reactive_power_phase_c : '.$reactive_power_phase_c.'<br>';
		$reactive_power_all_phases = $reactive_power_phase_a + $reactive_power_phase_b + $reactive_power_phase_c;
		// echo 'reactive_power_all_phases : '.$reactive_power_all_phases.'<br>';

		// apparent
		$apparent_power_phase_a = hexdec($instantaneous_raw_data[44].$instantaneous_raw_data[43])/1000;
		// echo 'apparent_power_phase_a : '.$apparent_power_phase_a.'<br>';
		$apparent_power_phase_b = hexdec($instantaneous_raw_data[46].$instantaneous_raw_data[45])/1000;
		// echo 'apparent_power_phase_b : '.$apparent_power_phase_b.'<br>';
		$apparent_power_phase_c = hexdec($instantaneous_raw_data[48].$instantaneous_raw_data[47])/1000;
		// echo 'apparent_power_phase_c : '.$apparent_power_phase_c.'<br>';
		$apparent_power_all_phases = round($apparent_power_phase_a + $apparent_power_phase_b + $apparent_power_phase_c,3);
		$apparent_power_all_phases = $apparent_power_phase_a + $apparent_power_phase_b + $apparent_power_phase_c;
		// echo 'apparent_power_all_phases : '.$apparent_power_all_phases.'<br>';

		// power factor
		$power_factor_phase_a = hexdec($instantaneous_raw_data[65])/100;
		// echo 'power_factor_phase_a : '.$power_factor_phase_a.'<br>';
		$power_factor_phase_b = hexdec($instantaneous_raw_data[66])/100;
		// echo 'power_factor_phase_b : '.$power_factor_phase_b.'<br>';
		$power_factor_phase_c = hexdec($instantaneous_raw_data[67])/100;
		// echo 'power_factor_phase_c : '.$power_factor_phase_c.'<br>';
		$power_factor_all_phases = round(($power_factor_phase_a + $power_factor_phase_b + $power_factor_phase_c)/3,3);
		// echo 'power_factor_all_phases : '.$power_factor_all_phases.'<br>';

		// power quadrant
		$power_quadrant_phase_a = quadrant_calc($active_power_phase_a , $reactive_power_phase_a);
		// echo 'power_quadrant_phase_a : '.$power_quadrant_phase_a.'<br>';
		$power_quadrant_phase_b = quadrant_calc($active_power_phase_b , $reactive_power_phase_b);
		// echo 'power_quadrant_phase_b : '.$power_quadrant_phase_b.'<br>';
		$power_quadrant_phase_c = quadrant_calc($active_power_phase_c , $reactive_power_phase_c);
		// echo 'power_quadrant_phase_c : '.$power_quadrant_phase_c.'<br>';
		$power_quadrant_all_phases = round(floor(($power_quadrant_phase_a + $power_quadrant_phase_b + $power_quadrant_phase_c)/3),3);
		// echo 'power_quadrant_all_phases : '.$power_quadrant_all_phases.'<br>';

		//active current month mdi
		$active_current_month_mdi_t_1 = hexdec($instantaneous_raw_data[76].$instantaneous_raw_data[75])/1000;
		// echo 'active_current_month_mdi_t_1 : '.$active_current_month_mdi_t_1.'<br>';
		$active_current_month_mdi_t_2 = hexdec($instantaneous_raw_data[84].$instantaneous_raw_data[83])/1000;
		// echo 'active_current_month_mdi_t_2 : '.$active_current_month_mdi_t_2.'<br>';
		$active_current_month_mdi_t_3 = hexdec($instantaneous_raw_data[92].$instantaneous_raw_data[91])/1000;
		// echo 'active_current_month_mdi_t_3 : '.$active_current_month_mdi_t_3.'<br>';
		$active_current_month_mdi_t_4 = hexdec($instantaneous_raw_data[100].$instantaneous_raw_data[99])/1000;
		// echo 'active_current_month_mdi_t_4 : '.$active_current_month_mdi_t_4.'<br>';
		$active_current_month_mdi_t_L = max($active_current_month_mdi_t_1,$active_current_month_mdi_t_2,$active_current_month_mdi_t_3,$active_current_month_mdi_t_4);
		// echo 'active_current_month_mdi_t_L : '.$active_current_month_mdi_t_L.'<br>';

		//reactive current month mdi
		$reactive_current_month_mdi_t_1 = hexdec($instantaneous_raw_data[80].$instantaneous_raw_data[79])/1000;
		// echo 'reactive_current_month_mdi_t_1 : '.$reactive_current_month_mdi_t_1.'<br>';
		$reactive_current_month_mdi_t_2 = hexdec($instantaneous_raw_data[88].$instantaneous_raw_data[87])/1000;
		// echo 'reactive_current_month_mdi_t_2 : '.$reactive_current_month_mdi_t_2.'<br>';
		$reactive_current_month_mdi_t_3 = hexdec($instantaneous_raw_data[96].$instantaneous_raw_data[95])/1000;
		// echo 'reactive_current_month_mdi_t_3 : '.$reactive_current_month_mdi_t_3.'<br>';
		$reactive_current_month_mdi_t_4 = hexdec($instantaneous_raw_data[104].$instantaneous_raw_data[103])/1000;
		// echo 'reactive_current_month_mdi_t_4 : '.$reactive_current_month_mdi_t_4.'<br>';
		$reactive_current_month_mdi_t_L = max($reactive_current_month_mdi_t_1,$reactive_current_month_mdi_t_2,$reactive_current_month_mdi_t_3,$reactive_current_month_mdi_t_4);
		// echo 'reactive_current_month_mdi_t_L : '.$reactive_current_month_mdi_t_L.'<br>';

		//running active current month mdi
		$running_active_current_interval_mdi_slide_1 = hexdec($instantaneous_raw_data[155].$instantaneous_raw_data[156].$instantaneous_raw_data[157].$instantaneous_raw_data[158])/1000;
		// echo 'running_active_current_interval_mdi_slide_1 : '.$running_active_current_interval_mdi_slide_1.'<br>';
		$running_active_current_interval_mdi_slide_2 = hexdec($instantaneous_raw_data[159].$instantaneous_raw_data[160].$instantaneous_raw_data[161].$instantaneous_raw_data[162])/1000;
		// echo 'running_active_current_interval_mdi_slide_2 : '.$running_active_current_interval_mdi_slide_2.'<br>';
		$running_active_current_interval_mdi_slide_3 = hexdec($instantaneous_raw_data[163].$instantaneous_raw_data[164].$instantaneous_raw_data[165].$instantaneous_raw_data[166])/1000;
		// echo 'running_active_current_interval_mdi_slide_3 : '.$running_active_current_interval_mdi_slide_3.'<br>';
		$running_active_current_interval_mdi_slide_4 = hexdec($instantaneous_raw_data[167].$instantaneous_raw_data[168].$instantaneous_raw_data[169].$instantaneous_raw_data[170])/1000;
		// echo 'running_active_current_interval_mdi_slide_4 : '.$running_active_current_interval_mdi_slide_4.'<br>';
		$running_active_current_interval_mdi_slide_5 = hexdec($instantaneous_raw_data[171].$instantaneous_raw_data[172].$instantaneous_raw_data[173].$instantaneous_raw_data[174])/1000;
		// echo 'running_active_current_interval_mdi_slide_5 : '.$running_active_current_interval_mdi_slide_5.'<br>';

		//running active import current month mdi
		$running_active_import_current_interval_mdi_slide_1 = hexdec($instantaneous_raw_data[179].$instantaneous_raw_data[180].$instantaneous_raw_data[181].$instantaneous_raw_data[182])/1000;
		// echo 'running_active_import_current_interval_mdi_slide_1 : '.$running_active_import_current_interval_mdi_slide_1.'<br>';
		$running_active_import_current_interval_mdi_slide_2 = hexdec($instantaneous_raw_data[183].$instantaneous_raw_data[184].$instantaneous_raw_data[185].$instantaneous_raw_data[186])/1000;
		// echo 'running_active_import_current_interval_mdi_slide_2 : '.$running_active_import_current_interval_mdi_slide_2.'<br>';
		$running_active_import_current_interval_mdi_slide_3 = hexdec($instantaneous_raw_data[187].$instantaneous_raw_data[188].$instantaneous_raw_data[189].$instantaneous_raw_data[190])/1000;
		// echo 'running_active_import_current_interval_mdi_slide_3 : '.$running_active_import_current_interval_mdi_slide_3.'<br>';
		$running_active_import_current_interval_mdi_slide_4 = hexdec($instantaneous_raw_data[191].$instantaneous_raw_data[192].$instantaneous_raw_data[193].$instantaneous_raw_data[194])/1000;
		// echo 'running_active_import_current_interval_mdi_slide_4 : '.$running_active_import_current_interval_mdi_slide_4.'<br>';
		$running_active_import_current_interval_mdi_slide_5 = hexdec($instantaneous_raw_data[195].$instantaneous_raw_data[196].$instantaneous_raw_data[197].$instantaneous_raw_data[198])/1000;
		// echo 'running_active_import_current_interval_mdi_slide_5 : '.$running_active_import_current_interval_mdi_slide_5.'<br>';

		//running active export current month mdi
		$running_active_export_current_interval_mdi_slide_1 = hexdec($instantaneous_raw_data[203].$instantaneous_raw_data[204].$instantaneous_raw_data[205].$instantaneous_raw_data[206])/1000;
		// echo 'running_active_export_current_interval_mdi_slide_1 : '.$running_active_export_current_interval_mdi_slide_1.'<br>';
		$running_active_export_current_interval_mdi_slide_2 = hexdec($instantaneous_raw_data[207].$instantaneous_raw_data[208].$instantaneous_raw_data[209].$instantaneous_raw_data[210])/1000;
		// echo 'running_active_export_current_interval_mdi_slide_2 : '.$running_active_export_current_interval_mdi_slide_2.'<br>';
		$running_active_export_current_interval_mdi_slide_3 = hexdec($instantaneous_raw_data[211].$instantaneous_raw_data[212].$instantaneous_raw_data[213].$instantaneous_raw_data[214])/1000;
		// echo 'running_active_export_current_interval_mdi_slide_3 : '.$running_active_export_current_interval_mdi_slide_3.'<br>';
		$running_active_export_current_interval_mdi_slide_4 = hexdec($instantaneous_raw_data[215].$instantaneous_raw_data[216].$instantaneous_raw_data[217].$instantaneous_raw_data[218])/1000;
		// echo 'running_active_export_current_interval_mdi_slide_4 : '.$running_active_export_current_interval_mdi_slide_4.'<br>';
		$running_active_export_current_interval_mdi_slide_5 = hexdec($instantaneous_raw_data[219].$instantaneous_raw_data[220].$instantaneous_raw_data[221].$instantaneous_raw_data[222])/1000;
		// echo 'running_active_export_current_interval_mdi_slide_5 : '.$running_active_export_current_interval_mdi_slide_5.'<br>';


		//running reactive current month mdi
		$running_reactive_current_interval_mdi_slide_1 = hexdec($instantaneous_raw_data[227].$instantaneous_raw_data[228].$instantaneous_raw_data[229].$instantaneous_raw_data[230])/1000;
		// echo 'running_reactive_current_interval_mdi_slide_1 : '.$running_reactive_current_interval_mdi_slide_1.'<br>';
		$running_reactive_current_interval_mdi_slide_2 = hexdec($instantaneous_raw_data[231].$instantaneous_raw_data[232].$instantaneous_raw_data[233].$instantaneous_raw_data[234])/1000;
		// echo 'running_reactive_current_interval_mdi_slide_2 : '.$running_reactive_current_interval_mdi_slide_2.'<br>';
		$running_reactive_current_interval_mdi_slide_3 = hexdec($instantaneous_raw_data[235].$instantaneous_raw_data[236].$instantaneous_raw_data[237].$instantaneous_raw_data[238])/1000;
		// echo 'running_reactive_current_interval_mdi_slide_3 : '.$running_reactive_current_interval_mdi_slide_3.'<br>';
		$running_reactive_current_interval_mdi_slide_4 = hexdec($instantaneous_raw_data[239].$instantaneous_raw_data[240].$instantaneous_raw_data[241].$instantaneous_raw_data[242])/1000;
		// echo 'running_reactive_current_interval_mdi_slide_4 : '.$running_reactive_current_interval_mdi_slide_4.'<br>';
		$running_reactive_current_interval_mdi_slide_5 = hexdec($instantaneous_raw_data[243].$instantaneous_raw_data[244].$instantaneous_raw_data[245].$instantaneous_raw_data[246])/1000;
		// echo 'running_reactive_current_interval_mdi_slide_5 : '.$running_reactive_current_interval_mdi_slide_5.'<br>';

		//running reactive import current month mdi
		$running_reactive_import_current_interval_mdi_slide_1 = hexdec($instantaneous_raw_data[251].$instantaneous_raw_data[252].$instantaneous_raw_data[253].$instantaneous_raw_data[254])/1000;
		// echo 'running_reactive_import_current_interval_mdi_slide_1 : '.$running_reactive_import_current_interval_mdi_slide_1.'<br>';
		$running_reactive_import_current_interval_mdi_slide_2 = hexdec($instantaneous_raw_data[255].$instantaneous_raw_data[256].$instantaneous_raw_data[257].$instantaneous_raw_data[258])/1000;
		// echo 'running_reactive_import_current_interval_mdi_slide_2 : '.$running_reactive_import_current_interval_mdi_slide_2.'<br>';
		$running_reactive_import_current_interval_mdi_slide_3 = hexdec($instantaneous_raw_data[259].$instantaneous_raw_data[260].$instantaneous_raw_data[261].$instantaneous_raw_data[262])/1000;
		// echo 'running_reactive_import_current_interval_mdi_slide_3 : '.$running_reactive_import_current_interval_mdi_slide_3.'<br>';
		$running_reactive_import_current_interval_mdi_slide_4 = hexdec($instantaneous_raw_data[263].$instantaneous_raw_data[264].$instantaneous_raw_data[265].$instantaneous_raw_data[266])/1000;
		// echo 'running_reactive_import_current_interval_mdi_slide_4 : '.$running_reactive_import_current_interval_mdi_slide_4.'<br>';
		$running_reactive_import_current_interval_mdi_slide_5 = hexdec($instantaneous_raw_data[267].$instantaneous_raw_data[268].$instantaneous_raw_data[269].$instantaneous_raw_data[270])/1000;
		// echo 'running_reactive_import_current_interval_mdi_slide_5 : '.$running_reactive_import_current_interval_mdi_slide_5.'<br>';

		//running reactive export current month mdi
		$running_reactive_export_current_interval_mdi_slide_1 = hexdec($instantaneous_raw_data[275].$instantaneous_raw_data[276].$instantaneous_raw_data[277].$instantaneous_raw_data[278])/1000;
		// echo 'running_reactive_export_current_interval_mdi_slide_1 : '.$running_reactive_export_current_interval_mdi_slide_1.'<br>';
		$running_reactive_export_current_interval_mdi_slide_2 = hexdec($instantaneous_raw_data[279].$instantaneous_raw_data[280].$instantaneous_raw_data[281].$instantaneous_raw_data[282])/1000;
		// echo 'running_reactive_export_current_interval_mdi_slide_2 : '.$running_reactive_export_current_interval_mdi_slide_2.'<br>';
		$running_reactive_export_current_interval_mdi_slide_3 = hexdec($instantaneous_raw_data[283].$instantaneous_raw_data[284].$instantaneous_raw_data[285].$instantaneous_raw_data[286])/1000;
		// echo 'running_reactive_export_current_interval_mdi_slide_3 : '.$running_reactive_export_current_interval_mdi_slide_3.'<br>';
		$running_reactive_export_current_interval_mdi_slide_4 = hexdec($instantaneous_raw_data[287].$instantaneous_raw_data[288].$instantaneous_raw_data[289].$instantaneous_raw_data[290])/1000;
		// echo 'running_reactive_export_current_interval_mdi_slide_4 : '.$running_reactive_export_current_interval_mdi_slide_4.'<br>';
		$running_reactive_export_current_interval_mdi_slide_5 = hexdec($instantaneous_raw_data[291].$instantaneous_raw_data[292].$instantaneous_raw_data[293].$instantaneous_raw_data[294])/1000;
		// echo 'running_reactive_export_current_interval_mdi_slide_5 : '.$running_reactive_export_current_interval_mdi_slide_5.'<br>';

		//running reactive export current month mdi
		$meter_date = hexdec($instantaneous_raw_data[3]).'/'.hexdec($instantaneous_raw_data[4]).'/'.hexdec($instantaneous_raw_data[5]);
		// echo 'meter_date : '.$meter_date.'<br>';
		$meter_time = hexdec($instantaneous_raw_data[2]).':'.hexdec($instantaneous_raw_data[1]).':'.hexdec($instantaneous_raw_data[0]);
		// echo 'meter_time : '.$meter_time.'<br>';
		$mdi_reset_count = hexdec($instantaneous_raw_data[11]);
		// echo 'mdi_reset_count : '.$mdi_reset_count.'<br>';
		$season = hexdec($instantaneous_raw_data[7])+1;
		// echo 'season : '.$season.'<br>';
		$tariff = hexdec($instantaneous_raw_data[8])+1;
		// echo 'tariff : '.$tariff.'<br>';
		$frequency = hexdec($instantaneous_raw_data[52].$instantaneous_raw_data[51])/100;
		// echo 'frequency : '.$frequency.'<br>';
		$tamper_power = hexdec($instantaneous_raw_data[50].$instantaneous_raw_data[49])/1000;
		// echo 'tamper_power : '.$tamper_power.'<br>';
		$last_interval_mdi_kw = hexdec($instantaneous_raw_data[60].$instantaneous_raw_data[59].$instantaneous_raw_data[58].$instantaneous_raw_data[57])/1000;
		// echo 'last_interval_mdi_kw : '.$last_interval_mdi_kw.'<br>';
		$last_interval_mdi_kvar = hexdec($instantaneous_raw_data[247].$instantaneous_raw_data[248].$instantaneous_raw_data[249].$instantaneous_raw_data[250])/1000;
		// echo 'last_interval_mdi_kvar : '.$last_interval_mdi_kvar.'<br>';
		$last_interval_mdi_import_kw = hexdec($instantaneous_raw_data[199].$instantaneous_raw_data[200].$instantaneous_raw_data[201].$instantaneous_raw_data[202])/1000;
		// echo 'last_interval_mdi_import_kw : '.$last_interval_mdi_import_kw.'<br>';
		$last_interval_mdi_import_kvar = hexdec($instantaneous_raw_data[271].$instantaneous_raw_data[272].$instantaneous_raw_data[273].$instantaneous_raw_data[274])/1000;
		// echo 'last_interval_mdi_import_kvar : '.$last_interval_mdi_import_kvar.'<br>';
		$last_interval_mdi_export_kw = hexdec($instantaneous_raw_data[223].$instantaneous_raw_data[224].$instantaneous_raw_data[225].$instantaneous_raw_data[226])/1000;
		// echo 'last_interval_mdi_export_kw : '.$last_interval_mdi_export_kw.'<br>';
		$last_interval_mdi_export_kvar = hexdec($instantaneous_raw_data[295].$instantaneous_raw_data[296].$instantaneous_raw_data[297].$instantaneous_raw_data[298])/1000;
		// echo 'last_interval_mdi_export_kvar : '.$last_interval_mdi_export_kvar.'<br>';
		$counter = hexdec($instantaneous_raw_data[300]);
		// echo 'counter : '.$counter.'<br>';
		$slide_count = hexdec($instantaneous_raw_data[306]);
		// echo 'slide_count : '.$slide_count.'<br>';
		$slide_counter = hexdec($instantaneous_raw_data[305]);
		// echo 'slide_counter : '.$slide_counter.'<br>';
		$time_period = hexdec($instantaneous_raw_data[302]);
		// echo 'time_period : '.$time_period.'<br>';
		$time_span_index = hexdec($instantaneous_raw_data[304]);
		// echo 'time_span_index : '.$time_span_index.'<br>';
		$time_interval_left_in_seconds = ($time_period * 60) - ((hexdec($instantaneous_raw_data[1])%$time_period)*60+hexdec($instantaneous_raw_data[0]));
		$time_interval_left = floor($time_interval_left_in_seconds/60).':'.$time_interval_left_in_seconds%60;
		// echo 'time_interval_left : '.$time_interval_left.'<br>';

		$send_json = (object)NULL;

		$send_json->voltage_phase_a = $voltage_phase_a;
		$send_json->voltage_phase_b = $voltage_phase_b;
		$send_json->voltage_phase_c = $voltage_phase_c;
		$send_json->current_phase_a = $current_phase_a;
		$send_json->current_phase_b = $current_phase_b;
		$send_json->current_phase_c = $current_phase_c;
		$send_json->current_all_phases = $current_all_phases;
		$send_json->active_power_phase_a = $active_power_phase_a;
		$send_json->active_power_phase_b = $active_power_phase_b;
		$send_json->active_power_phase_c = $active_power_phase_c;
		$send_json->active_power_all_phases = $active_power_all_phases;
		$send_json->reactive_power_phase_a = $reactive_power_phase_a;
		$send_json->reactive_power_phase_b = $reactive_power_phase_b;
		$send_json->reactive_power_phase_c = $reactive_power_phase_c;
		$send_json->current_all_phases = $current_all_phases;
		$send_json->active_power_phase_a = $active_power_phase_a;
		$send_json->active_power_phase_b = $active_power_phase_b;
		$send_json->active_power_phase_c = $active_power_phase_c;
		$send_json->active_power_all_phases = $active_power_all_phases;
		$send_json->reactive_power_phase_a = $reactive_power_phase_a;
		$send_json->reactive_power_phase_b = $reactive_power_phase_b;
		$send_json->reactive_power_phase_c = $reactive_power_phase_c;
		$send_json->reactive_power_all_phases = $reactive_power_all_phases;
		$send_json->apparent_power_phase_a = $apparent_power_phase_a;
		$send_json->apparent_power_phase_b = $apparent_power_phase_b;
		$send_json->apparent_power_phase_c = $apparent_power_phase_c;
		$send_json->apparent_power_all_phases = $apparent_power_all_phases;
		$send_json->power_factor_phase_a = $power_factor_phase_a;
		$send_json->power_factor_phase_b = $power_factor_phase_b;
		$send_json->power_factor_phase_c = $power_factor_phase_c;
		$send_json->power_factor_all_phases = $power_factor_all_phases;
		$send_json->power_quadrant_phase_a = $power_quadrant_phase_a;
		$send_json->power_quadrant_phase_b = $power_quadrant_phase_b;
		$send_json->power_quadrant_phase_c = $power_quadrant_phase_c;
		$send_json->power_quadrant_all_phases = $power_quadrant_all_phases;
		$send_json->active_current_month_mdi_t_1 = $active_current_month_mdi_t_1;
		$send_json->active_current_month_mdi_t_2 = $active_current_month_mdi_t_2;
		$send_json->active_current_month_mdi_t_3 = $active_current_month_mdi_t_3;
		$send_json->active_current_month_mdi_t_4 = $active_current_month_mdi_t_4;
		$send_json->active_current_month_mdi_t_L = $active_current_month_mdi_t_L;
		$send_json->reactive_current_month_mdi_t_1 = $reactive_current_month_mdi_t_1;
		$send_json->reactive_current_month_mdi_t_2 = $reactive_current_month_mdi_t_2;
		$send_json->reactive_current_month_mdi_t_3 = $reactive_current_month_mdi_t_3;
		$send_json->reactive_current_month_mdi_t_4 = $reactive_current_month_mdi_t_4;
		$send_json->reactive_current_month_mdi_t_L = $reactive_current_month_mdi_t_L;
		$send_json->running_active_current_interval_mdi_slide_1 = $running_active_current_interval_mdi_slide_1;
		$send_json->running_active_current_interval_mdi_slide_2 = $running_active_current_interval_mdi_slide_2;
		$send_json->running_active_current_interval_mdi_slide_3 = $running_active_current_interval_mdi_slide_3;
		$send_json->running_active_current_interval_mdi_slide_4 = $running_active_current_interval_mdi_slide_4;
		$send_json->running_active_current_interval_mdi_slide_5 = $running_active_current_interval_mdi_slide_5;
		$send_json->running_active_import_current_interval_mdi_slide_1 = $running_active_import_current_interval_mdi_slide_1;
		$send_json->running_active_import_current_interval_mdi_slide_2 = $running_active_import_current_interval_mdi_slide_2;
		$send_json->running_active_import_current_interval_mdi_slide_3 = $running_active_import_current_interval_mdi_slide_3;
		$send_json->running_active_import_current_interval_mdi_slide_4 = $running_active_import_current_interval_mdi_slide_4;
		$send_json->running_active_import_current_interval_mdi_slide_5 = $running_active_import_current_interval_mdi_slide_5;
		$send_json->running_active_export_current_interval_mdi_slide_1 = $running_active_export_current_interval_mdi_slide_1;
		$send_json->running_active_export_current_interval_mdi_slide_2 = $running_active_export_current_interval_mdi_slide_2;
		$send_json->running_active_export_current_interval_mdi_slide_3 = $running_active_export_current_interval_mdi_slide_3;
		$send_json->running_active_export_current_interval_mdi_slide_4 = $running_active_export_current_interval_mdi_slide_4;
		$send_json->running_active_export_current_interval_mdi_slide_5 = $running_active_export_current_interval_mdi_slide_5;
		$send_json->running_reactive_current_interval_mdi_slide_1 = $running_reactive_current_interval_mdi_slide_1;
		$send_json->running_reactive_current_interval_mdi_slide_2 = $running_reactive_current_interval_mdi_slide_2;
		$send_json->running_reactive_current_interval_mdi_slide_3 = $running_reactive_current_interval_mdi_slide_3;
		$send_json->running_reactive_current_interval_mdi_slide_4 = $running_reactive_current_interval_mdi_slide_4;
		$send_json->running_reactive_current_interval_mdi_slide_5 = $running_reactive_current_interval_mdi_slide_5;
		$send_json->running_reactive_import_current_interval_mdi_slide_1 = $running_reactive_import_current_interval_mdi_slide_1;
		$send_json->running_reactive_import_current_interval_mdi_slide_2 = $running_reactive_import_current_interval_mdi_slide_2;
		$send_json->running_reactive_import_current_interval_mdi_slide_3 = $running_reactive_import_current_interval_mdi_slide_3;
		$send_json->running_reactive_import_current_interval_mdi_slide_4 = $running_reactive_import_current_interval_mdi_slide_4;
		$send_json->running_reactive_import_current_interval_mdi_slide_5 = $running_reactive_import_current_interval_mdi_slide_5;
		$send_json->running_reactive_export_current_interval_mdi_slide_1 = $running_reactive_export_current_interval_mdi_slide_1;
		$send_json->running_reactive_export_current_interval_mdi_slide_2 = $running_reactive_export_current_interval_mdi_slide_2;
		$send_json->running_reactive_export_current_interval_mdi_slide_3 = $running_reactive_export_current_interval_mdi_slide_3;
		$send_json->running_reactive_export_current_interval_mdi_slide_4 = $running_reactive_export_current_interval_mdi_slide_4;
		$send_json->running_reactive_export_current_interval_mdi_slide_5 = $running_reactive_export_current_interval_mdi_slide_5;
		$send_json->meter_date = $meter_date;
		$send_json->meter_time = $meter_time;
		$send_json->mdi_reset_count = $mdi_reset_count;
		$send_json->season = $season;
		$send_json->tariff = $tariff;
		$send_json->frequency = $frequency;
		$send_json->tamper_power = $tamper_power;
		$send_json->last_interval_mdi_kw = $last_interval_mdi_kw;
		$send_json->last_interval_mdi_kvar = $last_interval_mdi_kvar;
		$send_json->last_interval_mdi_import_kw = $last_interval_mdi_import_kw;
		$send_json->last_interval_mdi_import_kvar = $last_interval_mdi_import_kvar;
		$send_json->last_interval_mdi_export_kw = $last_interval_mdi_export_kw;
		$send_json->last_interval_mdi_export_kvar = $last_interval_mdi_export_kvar;
		$send_json->counter = $counter;
		$send_json->slide_count = $slide_count;
		$send_json->slide_counter = $slide_counter;
		$send_json->time_period = $time_period;
		$send_json->time_span_index = $time_span_index;
		$send_json->time_interval_left = $time_interval_left;

		$send_json = json_encode($send_json);

		echo $send_json;
	}

	function quadrant_calc($active_power , $reactive_power){
			if(is_pos($active_power) && is_pos($reactive_power))return 1; // QUAD 1
		elseif(is_neg($active_power) && is_pos($reactive_power))return 2; // QUAD 2
		elseif(is_neg($active_power) && is_neg($reactive_power))return 3; // QUAD 3
		elseif(is_pos($active_power) && is_neg($reactive_power))return 4; // QUAD 4
	}

	function is_pos($i){
		if(is_numeric($i) && $i >= 0) { return true; }
	}

	function is_neg($i){
		if(is_numeric($i) && $i < 0) { return true; }
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

	// extract_instantaneous_data($instantaneous_raw_data);


	//DIO
	$port_name = 'COM7';
	$port_mode = O_RDWR;
	$fd = dio_open( $port_name , $port_mode );
	$packets = (object)NULL;
	$packets->sd = array(0x4d,0x54,0x4c,0x53,0x00,0x00,0x00,0xbb,0x0b,0x05,0x01,0x01,0x52,0x05,0x08,0x05,0x00,0x77,0xb5,0x4e,0x9c,0x00,0x00,0x01,0x00);

	function set_data_in_meter(){

	}

	function get_data_from_meter( $fd , $packet , $delay = 1 ) 
	{
		if( $fd == FALSE )return -1;
		$packet = pack_array( 'C*' , $packet );
		echo '<br><br>REQUEST<br>'.$packet;
		dio_write( $fd  , $packet);
		sleep($delay);
		$r = dio_read( $fd );
		echo '<br><br>RESPONSE<br>'.$r;
		return $r;
	}
	
	function pack_array($format, $arg)
	{
	    $result="";
	    foreach ($arg as $item) $result .= pack ($format, $item);
	    return $result;
	}

	get_data_from_meter( $fd , $packets->sd );

	dio_close( $fd );
	sqlsrv_close( $conn );
?>


