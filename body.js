var vue_app_display = new Vue({
	el:"#vue_app_display",
	data:{
			pm_monitoring_time_bit:false,
			pm_limit_bit:false,
			pm_mdi_bit:false,
			pm_load_profile_bit:false,
			pm_clock_calibration_bit:false,
			pm_decimal_point_bit:false,
			pm_energy_bit:false,
			pm_season_setting_bit:false,
			pm_tariff_setting_bit:false,
			pm_season_a_bit:false,
			pm_season_b_bit:false,
			pm_season_c_bit:false,
			pm_season_d_bit:false,
			pm_calender_setting_bit:false,
			pm_display_windows_bit:false,
			pm_power_off_display_bit:false,
			pm_password_bit:false,
			pm_cautions_and_errors_bit:false,
			monthly_billing_data_bit:false,
			cummulative_billing_bit:false,
			instantaneous_bit:true,
			load_profile_bit:false,
			event_bit:false,
	},
	methods:{
		init_all_bits:function(){
			this.pm_monitoring_time_bit=false;
			this.pm_limit_bit=false;
			this.pm_mdi_bit=false;
			this.pm_load_profile_bit=false;
			this.pm_clock_calibration_bit=false;
			this.pm_decimal_point_bit=false;
			this.pm_energy_bit=false;
			this.pm_season_setting_bit=false;
			this.pm_tariff_setting_bit=false;
			this.pm_season_a_bit=false;
			this.pm_season_b_bit=false;
			this.pm_season_c_bit=false;
			this.pm_season_d_bit=false;
			this.pm_calender_setting_bit=false;
			this.pm_display_windows_bit=false;
			this.pm_power_off_display_bit=false;
			this.pm_password_bit=false;
			this.pm_cautions_and_errors_bit=false;
			this.monthly_billing_data_bit=false;
			this.cummulative_billing_bit=false;
			this.instantaneous_bit=false;
			this.load_profile_bit=false;
			this.event_bit=false;
		}
	}
})

var vue_app_data_nav = new Vue({
	el:"#vue_app_data_nav",
	data:{

	},
	methods:{
		mb_on:function(){
			vue_app_display.init_all_bits();
			vue_app_display.monthly_billing_data_bit = true;
		},
		cb_on:function(){
			vue_app_display.init_all_bits();
			vue_app_display.cummulative_billing_bit = true;
		},
		in_on:function(){
			vue_app_display.init_all_bits();
			vue_app_display.instantaneous_bit = true;
		},
		lp_on:function(){
			vue_app_display.init_all_bits();
			vue_app_display.load_profile_bit = true;
		},
		ev_on:function(){
			vue_app_display.init_all_bits();
			vue_app_display.event_bit = true;
		},
		mt_on:function(){
			vue_app_display.init_all_bits();
			vue_app_display.pm_monitoring_time_bit = true;
		},
		lm_on:function(){
			vue_app_display.init_all_bits();
			vue_app_display.pm_limit_bit = true;
		},
		md_on:function(){
			vue_app_display.init_all_bits();
			vue_app_display.pm_mdi_bit = true;
		},
		lp_on:function(){
			vue_app_display.init_all_bits();
			vue_app_display.pm_load_profile_bit = true;
		},
		cc_on:function(){
			vue_app_display.init_all_bits();
			vue_app_display.pm_clock_calibration_bit = true;
		},
		dp_on:function(){
			vue_app_display.init_all_bits();
			vue_app_display.pm_decimal_point_bit = true;
		},
		ep_on:function(){
			vue_app_display.init_all_bits();
			vue_app_display.pm_energy_bit = true;
		},
		ss_on:function(){
			vue_app_display.init_all_bits();
			vue_app_display.pm_season_setting_bit = true;
		},
		ts_on:function(){
			vue_app_display.init_all_bits();
			vue_app_display.pm_tariff_setting_bit = true;
		},
		sa_on:function(){
			vue_app_display.init_all_bits();
			vue_app_display.pm_season_a_bit = true;
		},
		sb_on:function(){
			vue_app_display.init_all_bits();
			vue_app_display.pm_season_b_bit = true;
		},
		sc_on:function(){
			vue_app_display.init_all_bits();
			vue_app_display.pm_season_c_bit = true;
		},
		sd_on:function(){
			vue_app_display.init_all_bits();
			vue_app_display.pm_season_d_bit = true;
		},
		cs_on:function(){
			vue_app_display.init_all_bits();
			vue_app_display.pm_calender_setting_bit = true;
		},
		dw_on:function(){
			vue_app_display.init_all_bits();
			vue_app_display.pm_display_windows_bit = true;
		},
		po_on:function(){
			vue_app_display.init_all_bits();
			vue_app_display.pm_power_off_display_bit = true;
		},
		ps_on:function(){
			vue_app_display.init_all_bits();
			vue_app_display.pm_password_bit = true;
		},
		ce_on:function(){
			vue_app_display.init_all_bits();
			vue_app_display.pm_cautions_and_errors_bit = true;
		},
	},
})

function on(s){
	switch(s){
		case 'mb':
			vue_app_data_nav.mb_on();
			break;
		case 'cb':
			vue_app_data_nav.cb_on();
			break;
		case 'in':
			vue_app_data_nav.in_on();
			break;
		case 'lps':
			vue_app_data_nav.lp_on();
			break;
		case 'ev':
			vue_app_data_nav.ev_on();
			break;
		case'mt':
			vue_app_data_nav.mt_on();
			break;
		case'lm':
			vue_app_data_nav.lm_on();
			break;
		case'md':
			vue_app_data_nav.md_on();
			break;
		case'lp':
			vue_app_data_nav.lp_on();
			break;
		case'cc':
			vue_app_data_nav.cc_on();
			break;
		case'dp':
			vue_app_data_nav.dp_on();
			break;
		case'ep':
			vue_app_data_nav.ep_on();
			break;
		case'ss':
			vue_app_data_nav.ss_on();
			break;
		case'ts':
			vue_app_data_nav.ts_on();
			break;
		case'sa':
			vue_app_data_nav.sa_on();
			break;
		case'sb':
			vue_app_data_nav.sb_on();
			break;
		case'sc':
			vue_app_data_nav.sc_on();
			break;
		case'sd':
			vue_app_data_nav.sd_on();
			break;
		case'cs':
			vue_app_data_nav.cs_on();
			break;
		case'dw':
			vue_app_data_nav.dw_on();
			break;
		case'po':
			vue_app_data_nav.po_on();
			break;
		case'ps':
			vue_app_data_nav.ps_on();
			break;
		case'ce':
			vue_app_data_nav.ce_on();
			break;
		default: return;
	}
}