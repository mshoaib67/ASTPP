<?php

class Statistics extends CI_Controller {

    function Statistics() {
        parent::__construct();

        $this->load->helper('template_inheritance');

        $this->load->library('session');
        $this->load->library("statistics_form");
        $this->load->library('astpp/form');
        $this->load->model('statistics_model');

        if ($this->session->userdata('user_login') == FALSE)
            redirect(base_url() . '/astpp/login');
    }

    function listerrors() {
        $data['username'] = $this->session->userdata('user_name');
        $data['page_title'] = 'Error List';
        $this->session->set_userdata('advance_search', 0);
        $data['grid_fields'] = $this->statistics_form->build_error_list_for_admin();
        $data["grid_buttons"] = $this->statistics_form->build_grid_buttons();
        $this->load->view('view_error_list', $data);
    }

    /**
     * -------Here we write code for controller accounts functions account_list------
     * Listing of Accounts table data through php function json_encode
     */
    function listerrors_json() {
        $json_data = array();
        $count_all = $this->statistics_model->geterror_list(false, "", "");
        $paging_data = $this->form->load_grid_config($count_all, $_GET['rp'], $_GET['page']);
        $json_data = $paging_data["json_paging"];

        $query = $this->statistics_model->geterror_list(true, $paging_data["paging"]["start"], $paging_data["paging"]["page_no"]);
        $grid_fields = json_decode($this->statistics_form->build_error_list_for_admin());
        $json_data['rows'] = $this->form->build_grid($query, $grid_fields);

        echo json_encode($json_data);
    }

    function trunkstats() {
        $data['app_name'] = 'ASTPP - Open Source Billing Solution | System | Trunk Stats';
        $data['username'] = $this->session->userdata('user_name');
        $data['page_title'] = 'TrunkStats List';
        $this->session->set_userdata('advance_search', 0);
        $this->load->view('view_statistics_trunkstats', $data);
    }

    /**
     * -------Here we write code for controller statistics functions trunkstats------
     * Listing of trunks stat data through php function json_encode
     */
    function trunkstats_json($grid = NULL, $start_date = NULL, $end_date = NULL, $start_hour = NULL, $start_minute = NULL, $start_second = NULL, $end_hour = NULL, $end_minute = NULL, $end_second = NULL) {
        $data['app_name'] = 'ASTPP - Open Source Billing Solution | Accounts | Create';
        $data['username'] = $this->session->userdata('user_name');
        $data['page_title'] = 'Statistics - trunk stats';
        $sd = $start_date;
        $ed = $end_date;
        $data['start_date'] = $sd;
        $data['end_date'] = $ed;
        $data['start_hour'] = $start_hour;
        $data['start_minute'] = $start_minute;
        $data['start_second'] = $start_second;

        $data['end_hour'] = $end_hour;
        $data['end_minute'] = $end_minute;
        $data['end_second'] = $end_second;

        if ($sd == NULL || $ed == NULL || $sd == 'NULL' || $ed == 'NULL') {
            $sd = date("Y-m-d", strtotime(date('m') . '/01/' . date('Y') . ' 00:00:00'));
            $ed = date('Y-m-d 23:59:59');

            $data['start_date'] = date("Y-m-d", strtotime(date('m') . '/01/' . date('Y')));
            $data['end_date'] = date('Y-m-d');

            $data['start_hour'] = '00';
            $data['start_minute'] = '00';
            $data['start_second'] = '00';

            $data['end_hour'] = '23';
            $data['end_minute'] = '59';
            $data['end_second'] = '59';
        } else {

            $sd = $start_date . " " . $start_hour . ":" . $start_minute . ":" . $start_second;
            $ed = $end_date . " " . $end_hour . ":" . $end_minute . ":" . $end_minute;
        }

        //grid json data	
        $json_data = array();$count_all=0;
//         $count_all = $this->statistics_model->getTrunkStatsCount();
        $config['total_rows'] = $count_all=0;
        $config['per_page'] = $_GET['rp']=1;

        $page_no = $_GET['page'];
        $json_data['page'] = $page_no=1;
        $json_data['total'] = ($config['total_rows'] > 0) ? $config['total_rows'] : 0;
        $perpage = $config['per_page'];
        $start = ($page_no - 1) * $perpage;
        if ($start < 0)
            $start = 0;
	
//         $trunkstats = $this->statistics_model->getTrunkStatsList($start, $perpage, $sd, $ed);
        
//         if (count($trunkstats) > 0) {
//             foreach ($trunkstats as $key => $value) {
//                 $json_data['rows'][] = array('cell' => array(
//                         $value['tech_path'],
//                         $value['ct'],
//                         $value['bs'],
//                         $value['acwt'],
//                         "(" . $value['calls'] . ") " . $value['success_rate'] . "%",
//                         "(" . $value['failed_calls'] . ") " . $value['congestion_rate'] . "%"
//                         ));
//             }
//         }
//       $json_data = array();
 $json_data['rows']=array();
// {"page":null,"total":0,"rows":[]}

        echo json_encode($json_data);
    }

}

?>
 