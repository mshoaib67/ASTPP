<?php

class Reports extends MX_Controller {

    function Reports() {
        parent::__construct();

        $this->load->helper('template_inheritance');

        $this->load->library('session');
        $this->load->library("reports_form");
        $this->load->library('astpp/form');
        $this->load->model('reports_model');
        $this->load->library('fpdf');
        $this->load->library('pdf');
        $this->fpdf = new PDF('P', 'pt');
        $this->fpdf->initialize('P', 'mm', 'A4');

        if ($this->session->userdata('user_login') == FALSE)
            redirect(base_url() . '/astpp/login');
    }

    function customerReport_search() {
        $ajax_search = $this->input->post('ajax_search', 0);
        if ($this->input->post('advance_search', TRUE) == 1) {
            $this->session->set_userdata('advance_search', $this->input->post('advance_search'));
            $action = $this->input->post();
            unset($action['action']);
            unset($action['advance_search']);
            $this->session->set_userdata('customer_cdr_list_search', $action);
        }
        if (@$ajax_search != 1) {
            redirect(base_url() . 'reports/customerReport/');
        }
    }

    function customerReport_clearsearchfilter() {
        $this->session->set_userdata('advance_search', 0);
        $this->session->set_userdata('account_search', "");
//        redirect(base_url() . 'accounts/reseller_list/');
    }

    function customerReport() {
        $data['username'] = $this->session->userdata('user_name');
        $data['page_title'] = 'Customer CDRs Report';
        $this->session->set_userdata('advance_search', 0);
        $data['grid_fields'] = $this->reports_form->build_report_list_for_admin();
        $data["grid_buttons"] = $this->reports_form->build_grid_buttons();
        $data['form_search'] = $this->form->build_serach_form($this->reports_form->get_customer_cdr_form());
        $this->load->view('view_configuration_list', $data);
    }

    /**
     * -------Here we write code for controller accounts functions account_list------
     * Listing of Accounts table data through php function json_encode
     */
    function customerReport_json() {
        $json_data = array();
        $count_all = $this->reports_model->getsystem_list(false, "", "");
        $paging_data = $this->form->load_grid_config($count_all, $_GET['rp'], $_GET['page']);
        $json_data = $paging_data["json_paging"];

        $query = $this->reports_model->getsystem_list(true, $paging_data["paging"]["start"], $paging_data["paging"]["page_no"]);
        $grid_fields = json_decode($this->reports_form->build_report_list_for_admin());
        $data['form_search'] = $this->form->build_serach_form($this->reports_form->get_customer_cdr_form());
        $json_data['rows'] = $this->form->build_grid($query, $grid_fields);

        echo json_encode($json_data);
    }

    function resellerReport() {
        $data['username'] = $this->session->userdata('user_name');
        $data['page_title'] = 'Resellers CDRs Report';
        $this->session->set_userdata('advance_search', 0);
        $data['grid_fields'] = $this->reports_form->build_report_list_for_reseller();
        $data["grid_buttons"] = $this->reports_form->build_grid_buttons_reseller();
        $data['form_search'] = $this->form->build_serach_form($this->reports_form->get_reseller_cdr_form());
        $this->load->view('view_cdr_reseller_list', $data);
    }

    /**
     * -------Here we write code for controller accounts functions account_list------
     * Listing of Accounts table data through php function json_encode
     */
    function resellerReport_json() {
        $json_data = array();
        $count_all = $this->reports_model->getreseller_list(false, "", "");
        $paging_data = $this->form->load_grid_config($count_all, $_GET['rp'], $_GET['page']);
        $json_data = $paging_data["json_paging"];

        $query = $this->reports_model->getreseller_list(true, $paging_data["paging"]["start"], $paging_data["paging"]["page_no"]);
        $grid_fields = json_decode($this->reports_form->build_report_list_for_reseller());
        $json_data['rows'] = $this->form->build_grid($query, $grid_fields);

        echo json_encode($json_data);
    }

    function resellerReport_search() {
        $ajax_search = $this->input->post('ajax_search', 0);
        if ($this->input->post('advance_search', TRUE) == 1) {
            $this->session->set_userdata('advance_search', $this->input->post('advance_search'));
            $action = $this->input->post();
            unset($action['action']);
            unset($action['advance_search']);
            $this->session->set_userdata('reseller_cdr_list_search', $action);
        }
        if (@$ajax_search != 1) {
            redirect(base_url() . 'reports/resellerReport/');
        }
    }

    function resellerReport_clearsearchfilter() {
        $this->session->set_userdata('advance_search', 0);
        $this->session->set_userdata('account_search', "");
    }

    function providerReport() {
        $data['username'] = $this->session->userdata('user_name');
        $data['page_title'] = 'Provider CDRs Report';
        $this->session->set_userdata('advance_search', 0);
        $data['grid_fields'] = $this->reports_form->build_report_list_for_provider();
        $data["grid_buttons"] = $this->reports_form->build_grid_buttons();
        $data['form_search'] = $this->form->build_serach_form($this->reports_form->get_provider_cdr_form());
        $this->load->view('view_cdr_provider_list', $data);
    }

    /**
     * -------Here we write code for controller accounts functions account_list------
     * Listing of Accounts table data through php function json_encode
     */
    function providerReport_json() {
        $json_data = array();
        $count_all = $this->reports_model->getprovider_list(false, "", "");
        $paging_data = $this->form->load_grid_config($count_all, $_GET['rp'], $_GET['page']);
        $json_data = $paging_data["json_paging"];

        $query = $this->reports_model->getprovider_list(true, $paging_data["paging"]["start"], $paging_data["paging"]["page_no"]);
        $grid_fields = json_decode($this->reports_form->build_report_list_for_provider());
        $json_data['rows'] = $this->form->build_grid($query, $grid_fields);

        echo json_encode($json_data);
    }

    function providerReport_search() {
        $ajax_search = $this->input->post('ajax_search', 0);
        if ($this->input->post('advance_search', TRUE) == 1) {
            $this->session->set_userdata('advance_search', $this->input->post('advance_search'));
            $action = $this->input->post();
            unset($action['action']);
            unset($action['advance_search']);
            $this->session->set_userdata('provider_cdr_list_search', $action);
        }
        if (@$ajax_search != 1) {
            redirect(base_url() . 'reports/providerReport/');
        }
    }

    function providerReport_clearsearchfilter() {
        $this->session->set_userdata('advance_search', 0);
        $this->session->set_userdata('account_search', "");
    }

    function customerReport_export_cdr_xls() {
        $query = $this->reports_model->getcustomercdrs(true, '', '', false);
        $customer_array = array();
        if ($this->session->userdata['logintype'] == 2) {
//             $customer_array[] = array("Date", "CallerID", "Called Number", "Account Number", "Bill Seconds", "Disposition", "Debit", "Cost", "Trunk", "Provider", "Pricelist", "Code", "Destination", "Call Type");
            
            $customer_array[] = array("Date", "CallerID", "Called Number","Code",  "Destination", "Bill Seconds","Debit", "Cost","Disposition", "Account Number",  "Trunk", "Rate Group",  "Call Type");
            
        } else {
           $customer_array[] = array("Date", "CallerID", "Called Number","Code",  "Destination", "Bill Seconds","Debit", "Cost","Disposition", "Account Number",   "Rate Group",  "Call Type");
        }
        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                if ($this->session->userdata['logintype'] == 2) {
                    $customer_array[] = array(
                        $row['callstart'],
                        $row['callerid'],
                        $row['callednum'],
			$row['pattern'],
                        $row['notes'],
                        $row['billseconds'],
                        $this->common_model->calculate_currency($row['debit']),
                        $this->common_model->calculate_currency($row['cost']),
                        $row['disposition'],
			$row['number'],
                        $row['trunk_id'],
                        $row['pricelist_id'],
                        $row['calltype']
                    );
                } else {
                    $customer_array[] = array(
                        $row['callstart'],
                        $row['callerid'],
                        $row['callednum'],
                        $row['pattern'],
                        $row['notes'],
			$row['billseconds'],
			$this->common_model->calculate_currency($row['debit']),
			$this->common_model->calculate_currency($row['cost']),
			$row['disposition'],
			$row['number'],
			$row['pricelist_id'],
			$row['calltype']
                    );
                }
            }
        }
        $this->load->helper('csv');
        array_to_csv($customer_array, 'Customer_CDR_' . date("Y-m-d") . '.xls');
    }

    function customerReport_export_cdr_pdf() {
        $query = $this->reports_model->getcustomercdrs(true, '', '', false);
        $customer_array = array();
        $this->load->library('fpdf');
        $this->load->library('pdf');
        $this->fpdf = new PDF('P', 'pt');
        $this->fpdf->initialize('P', 'mm', 'A4');

        if ($this->session->userdata['logintype'] == 2) {
            $this->fpdf->tablewidths = array(20, 20, 16, 16, 10, 18, 13, 13, 16, 14, 12, 10, 15);
//             $customer_array[] = array("Date", "CallerID", "Called Number", "Account Number", "BillSec", "Dispo.", "Debit", "Cost", "Trunk", "Provider", "Pricelist", "Code", "Destination", "Call Type");
            
            
             $customer_array[] = array("Date", "CallerID", "Called Number","Code",  "Destination", "Bill Seconds","Debit", "Cost","Disposition", "Account Number",  "Trunk", "Rate Group",  "Call Type");
            
        } else {
            $this->fpdf->tablewidths = array(22, 24, 20, 18, 10, 27, 13, 13, 14, 13, 15, 16);
          $customer_array[] = array("Date", "CallerID", "Called Number","Code",  "Destination", "Bill Seconds","Debit", "Cost","Disposition", "Account Number",   "Rate Group",  "Call Type");
        }
        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                if ($this->session->userdata['logintype'] == 2) {
                    $customer_array[] = array(
                        $row['callstart'],
                        $row['callerid'],
                        $row['callednum'],
                        $row['pattern'],
                        $row['notes'],
                        $row['billseconds'],
                        $this->common_model->calculate_currency($row['debit']),
                        $this->common_model->calculate_currency($row['cost']),
			$row['disposition'],
                        $row['number'],
			$row['trunk_id'],
                        $row['pricelist_id'],
                        $row['calltype']
                    );
                } else {
                    $customer_array[] = array(
                        $row['callstart'],
                        $row['callerid'],
                        $row['callednum'],
                        $row['pattern'],
                        $row['notes'],
                         $row['billseconds'],
                         $this->common_model->calculate_currency($row['debit']),
                        $this->common_model->calculate_currency($row['cost']),
                        $row['disposition'],
                        $row['number'],
			$row['pricelist_id'],
                        $row['calltype']
                    );
                }
            }
        }

        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage();

        $this->fpdf->SetFont('Arial', '', 15);
        $this->fpdf->SetXY(60, 5);
        $this->fpdf->Cell(100, 10, "Customer CDR Report " . date('Y-m-d'));

        $this->fpdf->SetY(20);
        $this->fpdf->SetFont('Arial', '', 7);
        $this->fpdf->SetFillColor(255, 255, 255);
        $this->fpdf->lMargin = 2;

        $dimensions = $this->fpdf->export_pdf($customer_array, "5");
        $this->fpdf->Output('Customer_CDR_' . date("Y-m-d") . '.pdf', "D");
    }

    function resellerReport_export_cdr_xls() {
        $query = $this->reports_model->getresellercdrs(true, '', '', false);
        $customer_array = array();
        if ($this->session->userdata['logintype'] == 2) {
              $customer_array[] = array("Date", "CallerID", "Called Number","Code",  "Destination", "Bill Seconds","Debit", "Cost","Disposition", "Account Number",  "Trunk", "Rate Group",  "Call Type");
        } else {
            $customer_array[] = array("Date", "CallerID", "Called Number","Code",  "Destination", "Bill Seconds","Debit", "Cost","Disposition", "Account Number",   "Rate Group",  "Call Type");
        }
        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                if ($this->session->userdata['logintype'] == 2) {
                    $customer_array[] = array(
                        $row['callstart'],
                        $row['callerid'],
                        $row['callednum'],
                        $row['number'],
                        $row['billseconds'],
                        $row['disposition'],
                        $this->common_model->calculate_currency($row['debit']),
                        $this->common_model->calculate_currency($row['cost']),
                        
                        $row['trunk_id'],
                        $row['provider_id'],
                        $row['pricelist_id'],
                        $row['pattern'],
                        $row['notes'],
                        $row['calltype']
                    );
                } else {
                    $customer_array[] = array(
                        $row['callstart'],
                        $row['callerid'],
                        $row['callednum'],
                        $row['number'],
                        $row['billseconds'],
                        $row['disposition'],
                        $this->common_model->calculate_currency($row['debit']),
                        $this->common_model->calculate_currency($row['cost']),
                        $row['pricelist_id'],
                        $row['pattern'],
                        $row['notes'],
                        $row['calltype']
                    );
                }
            }
        }
        $this->load->helper('csv');
        array_to_csv($customer_array, 'Reseller_CDR_' . date("Y-m-d") . '.xls');
    }

    function resellerReport_export_cdr_pdf() {
        $query = $this->reports_model->getresellercdrs(true);
        $customer_array = array();
        $this->load->library('fpdf');
        $this->load->library('pdf');
        $this->fpdf = new PDF('P', 'pt');
        $this->fpdf->initialize('P', 'mm', 'A4');

        if ($this->session->userdata['logintype'] == 2) {
            $this->fpdf->tablewidths = array(20, 20, 16, 16, 10, 18, 13, 13, 16, 14, 12, 10, 15);
             $customer_array[] = array("Date", "CallerID", "Called Number","Code",  "Destination", "Bill Seconds","Debit", "Cost","Disposition", "Account Number",  "Trunk", "Rate Group",  "Call Type");
        } else {
            $this->fpdf->tablewidths = array(22, 24, 20, 18, 10, 27, 13, 13, 14, 13, 15, 16);
        $customer_array[] = array("Date", "CallerID", "Called Number","Code",  "Destination", "Bill Seconds","Debit", "Cost","Disposition", "Account Number",   "Rate Group",  "Call Type");
        }
       
        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                if ($this->session->userdata['logintype'] == 2) {
                    $customer_array[] = array(
                        $row['callstart'],
                        $row['callerid'],
                        $row['callednum'],
			$row['pattern'],
                        $row['notes'],
                        $row['billseconds'],
                        $row['disposition'],
                        $this->common_model->calculate_currency($row['debit']),
                        $this->common_model->calculate_currency($row['cost']),
			$row['number'],
                        $row['trunk_id'],
                        $row['pricelist_id'],
			$row['calltype']
                    );
                } else {
                    $customer_array[] = array(
                        $row['callstart'],
                        $row['callerid'],
                        $row['callednum'],
                        $row['pattern'],
                        $row['notes'],
                        $row['billseconds'],
                        $row['disposition'],
                        $this->common_model->calculate_currency($row['debit']),
                        $this->common_model->calculate_currency($row['cost']),
			$row['number'],
                        $row['pricelist_id'],
			$row['calltype']
                    );
                }
            }
        }

        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage();

        $this->fpdf->SetFont('Arial', '', 15);
        $this->fpdf->SetXY(60, 5);
        $this->fpdf->Cell(100, 10, "Reseller CDR Report " . date('Y-m-d'));

        $this->fpdf->SetY(20);
        $this->fpdf->SetFont('Arial', '', 7);
        $this->fpdf->SetFillColor(255, 255, 255);
        $this->fpdf->lMargin = 2;

        $dimensions = $this->fpdf->export_pdf($customer_array, "5");
        $this->fpdf->Output('Reseller_CDR_' . date("Y-m-d") . '.pdf', "D");
    }

    function providerReport_export_cdr_xls() {
        $query = $this->reports_model->getprovidercdrs(true, '', '', false);
        $customer_array = array();
        if ($this->session->userdata['logintype'] == 2) {
               $customer_array[] = array("Date", "CallerID", "Called Number","Code",  "Destination", "Bill Seconds","Debit", "Cost","Disposition", "Account Number",  "Trunk", "Rate Group",  "Call Type");
        } else {
             $customer_array[] = array("Date", "CallerID", "Called Number","Code",  "Destination", "Bill Seconds","Debit", "Cost","Disposition", "Account Number",   "Rate Group",  "Call Type");
        }
        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                if ($this->session->userdata['logintype'] == 2) {
                    $customer_array[] = array(
                        $row['callstart'],
                        $row['callerid'],
                        $row['callednum'],
                        $row['pattern'],
                        $row['notes'],
                        $row['billseconds'],
                        $row['disposition'],
                        $this->common_model->calculate_currency($row['debit']),
                        $this->common_model->calculate_currency($row['cost']),
                        $row['number'],
                        $row['trunk_id'],
                        $row['pricelist_id'],
                        $row['calltype']
                    );
                } else {
                    $customer_array[] = array(
                        $row['callstart'],
                        $row['callerid'],
                        $row['callednum'],
                        $row['pattern'],
                        $row['notes'],
                        $row['billseconds'],
                        $row['disposition'],
                        $this->common_model->calculate_currency($row['debit']),
                        $this->common_model->calculate_currency($row['cost']),
                        $row['number'],
                        $row['pricelist_id'],
                        $row['calltype']
                    );
                }
            }
        }
        $this->load->helper('csv');
        array_to_csv($customer_array, 'Provider_CDR_' . date("Y-m-d") . '.xls');
    }

    function providerReport_export_cdr_pdf() {
        $query = $this->reports_model->getprovidercdrs(true);
        $customer_array = array();
        $this->load->library('fpdf');
        $this->load->library('pdf');
        $this->fpdf = new PDF('P', 'pt');
        $this->fpdf->initialize('P', 'mm', 'A4');

        if ($this->session->userdata['logintype'] == 2) {
            $this->fpdf->tablewidths = array(20, 20, 16, 16, 10, 18, 13, 13, 16, 14, 12, 10, 15);
            $customer_array[] = array("Date", "CallerID", "Called Number","Code",  "Destination", "Bill Seconds","Debit", "Cost","Disposition", "Account Number",  "Trunk", "Rate Group",  "Call Type");
        } else {
            $this->fpdf->tablewidths = array(22, 24, 20, 18, 10, 27, 13, 13, 14, 13, 15, 16);
           $customer_array[] = array("Date", "CallerID", "Called Number","Code",  "Destination", "Bill Seconds","Debit", "Cost","Disposition", "Account Number",   "Rate Group",  "Call Type");
        }
        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                if ($this->session->userdata['logintype'] == 2) {
                    $customer_array[] = array(
                        $row['callstart'],
                        $row['callerid'],
                        $row['callednum'],
                        $row['pattern'],
                        $row['notes'],
			$row['billseconds'],
                        $row['disposition'],
                        $this->common_model->calculate_currency($row['debit']),
                        $this->common_model->calculate_currency($row['cost']),
			$row['number'],
                        $row['trunk_id'],
                        $row['pricelist_id'],
                        $row['calltype']
                    );
                } else {
                    $customer_array[] = array(
                        $row['callstart'],
                        $row['callerid'],
                        $row['callednum'],
                        $row['pattern'],
                        $row['notes'],
                        $row['billseconds'],
                        $row['disposition'],
                        $this->common_model->calculate_currency($row['debit']),
                        $this->common_model->calculate_currency($row['cost']),
                        $row['number'],
                        $row['pricelist_id'],
                        $row['calltype']
                    );
                }
            }
        }

        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage();

        $this->fpdf->SetFont('Arial', '', 15);
        $this->fpdf->SetXY(60, 5);
        $this->fpdf->Cell(100, 10, "Provider CDR Report " . date('Y-m-d'));

        $this->fpdf->SetY(20);
        $this->fpdf->SetFont('Arial', '', 7);
        $this->fpdf->SetFillColor(255, 255, 255);
        $this->fpdf->lMargin = 2;

        $dimensions = $this->fpdf->export_pdf($customer_array, "5");
        $this->fpdf->Output('Provider_CDR_' . date("Y-m-d") . '.pdf', "D");
    }

    function userReport() {
        $data['username'] = $this->session->userdata('user_name');
        $data['page_title'] = 'Customer CDRs Report';
        $data['cur_menu_no'] = 5;
        $this->session->set_userdata('advance_search', 0);
        $this->load->view('view_adminReports_userReport', $data);
    }

    function userReport_grid($grid = NULL, $start_date = NULL, $end_date = NULL, $reseller = NULL, $destination = NULL, $pattern = NULL, $start_hour = NULL, $start_minute = NULL, $start_second = NULL, $end_hour = NULL, $end_minute = NULL, $end_second = NULL) {
        $name = "User";
        $type = "0";
        $data['username'] = $this->session->userdata('user_name');
        $data['page_title'] = 'User Report';

        //For Reseller
        //$Reseller_post = $this->input->post('Reseller',0);
        if ($this->session->userdata('advance_search') == 1) {

            $user_search = $this->session->userdata('user_search');

            if (!empty($user_search['reseller'])) {
                $reseller = $user_search['reseller'];
            }

            if (!empty($user_search['Destination'])) {
                $destination = $user_search['Destination'];
            }


            if (!empty($user_search['Pattern'])) {
                $pattern = $user_search['Pattern'];
            }

            if (!empty($user_search['start_date'])) {
                $start_date_before = $user_search['start_date'];

                $start_date_before = explode(" ", $start_date_before);
                $start_date = @$start_date_before[0];
                $time = explode(":", @$start_date_before[1]);

                $start_hour = $time[0];
                $start_minute = $time[1];
                $start_second = "00";
            }

            if (!empty($user_search['end_date'])) {
                $end_date_before = $user_search['end_date'];

                $end_date_before = explode(" ", $end_date_before);
                $end_date = @$end_date_before[0];
                $time = explode(":", @$end_date_before[1]);

                $end_hour = $time[0];
                $end_minute = $time[1];
                $end_second = "59";
            }
        }

        if ($reseller == NULL) {
            $reseller = "ALL";
        }

        if ($this->session->userdata('logintype') == 1 || $this->session->userdata('logintype') == 5) {
            $sth_reseller = $this->reports_model->getReseller("" . $this->session->userdata('username') . "", $type);
        } else {
            $sth_reseller = $this->reports_model->getReseller("", $type);
        }

        $data['Reseller'] = $reseller;
        $data['reseller'] = $sth_reseller;

        //For Destination
        //$Destination_post = $this->input->post('destination',0);
        if ($destination == NULL) {
            $destination = "ALL";
        }
        if ($pattern == NULL) {
            $pattern = "ALL";
        }

        if ($this->session->userdata('logintype') == 1 || $this->session->userdata('logintype') == 5) {
            $sth_destination = $this->reports_model->getDestination("" . $this->session->userdata('username') . "");
        } else {
            $sth_destination = $this->reports_model->getDestination();
        }

        $destination_list = $sth_destination[1];
        $pattern_list = $sth_destination[2];

        $data['Destination'] = $destination;
        $data['destination'] = $destination_list;

        $data['Pattern'] = urldecode($pattern);
        $data['pattern'] = $pattern_list;

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

        if (!empty($_POST)) {// AND $_SERVER['SERVER_ADDR'] == $_SERVER['REMOTE_ADDR'])
            // put your processing code here... we show what we do for emailing. You will need to add a correct email address
            if ($this->_process_create($_POST)) {
                $this->session->set_flashdata('success', TRUE);
                redirect('.');
            }
        }
        $where = "";

        if ($sd != 'NULL' && $ed != 'NULL' && $sd != "" && $ed != "") {
            $where = " AND callstart BETWEEN '" . $sd . "' AND '" . $ed . "' ";
        }

        if ($reseller == 'ALL') {
            if ($this->session->userdata('logintype') == 1 || $this->session->userdata('logintype') == 5) {
                $where .="AND accountid IN (SELECT `id` FROM accounts WHERE reseller_id = '" . $reseller . "' AND type IN (" . $type . ")) ";
            } else {
                $where .="AND accountid IN (SELECT `id` FROM accounts WHERE type IN (" . $type . ")) ";
            }
        } else {
            if ($this->session->userdata('logintype') == 1 || $this->session->userdata('logintype') == 5) {
                $where .="AND accountid = '" . $reseller . "' ";
            } else {
                if (strpos($type, "1") != -1) {
                    $where .= "AND accountid IN (SELECT `id` FROM accounts WHERE `id` = '" . $reseller . "' AND type IN (" . $type . ")) ";
                }
            }
        }

        if ($destination == 'ALL') {
            if (urldecode($pattern) == 'ALL' || urldecode($pattern) == "") {
                $where .= "";
            } else {
                $where .= "AND notes LIKE '" . "%|" . urldecode($pattern) . "' ";
            }
        } else {
            if (urldecode($pattern) == 'ALL' || urldecode($pattern) == "") {
                $where .= "";
            } else {
                $where .= "AND (notes LIKE '" . "%|" . $destination . "|%" . "' "
                        . "OR notes LIKE '" . "%|" . urldecode($pattern) . "' ";
            }
        }

        $table = "tmp_" . time();
        $query = "CREATE TEMPORARY TABLE  $table AS SELECT * FROM cdrs WHERE uniqueid != '' " . $where;
        //CREATE VIEW prodsupp AS

        $rs_create = $this->db->query($query);

        if ($rs_create) {
            $sql = $this->db->query("SELECT DISTINCT accountid AS '" . $name . "' FROM $table");
            $count_all = $sql->num_rows();

            $config['total_rows'] = $count_all;
            $config['per_page'] = $_GET['rp'] = 10;

            $page_no = $_GET['page'] = 1;

            $json_data = array();
            $json_data['page'] = $page_no;
            $json_data['total'] = ($config['total_rows'] > 0) ? $config['total_rows'] : 0;

            $perpage = $config['per_page'];
            $start = ($page_no - 1) * $perpage;
            if ($start < 0)
                $start = 0;

            $admin_reseller_report = $this->reports_model->getCardNum($reseller, $table, $start, $perpage, $name);
            if (count($admin_reseller_report) > 0) {

                foreach ($admin_reseller_report as $key => $value) {
                    $json_data['rows'][] = array('cell' => array($value['bth'],
                            $value['dst'],
                            $value['idd'],
                            $value['atmpt'],
                            $value['cmplt'],
                            $value['asr'],
                            $value['mcd'],
                            $value['act'],
                            $value['bill'],
                            $this->common_model->calculate_currency($value['price']),
                            $this->common_model->calculate_currency($value['cost'])));
                }
            }
            echo json_encode($json_data);
        }
    }

    /**
     * -------Here we write code for controller adminReports functions resellerReport------
     * Reseller report with call record info from start date to end date with IDD code and destination
     */
    function reseller_summery_Report() {
        $data['username'] = $this->session->userdata('user_name');
        $data['page_title'] = 'Reseller CDRs Report';
        $this->session->set_userdata('advance_search', 0);
        $this->load->view('view_adminReports_resellerReport', $data);
    }

    function reseller_sum_Report($grid = NULL, $start_date = NULL, $end_date = NULL, $reseller = NULL, $destination = NULL, $pattern = NULL, $start_hour = NULL, $start_minute = NULL, $start_second = NULL, $end_hour = NULL, $end_minute = NULL, $end_second = NULL) {
        $name = "Reseller";
        $type = "1";

        $data['username'] = $this->session->userdata('user_name');
        $data['page_title'] = 'Reseller Report';

        //For Reseller
        //$Reseller_post = $this->input->post('Reseller',0);

        if ($this->session->userdata('advance_search') == 1) {
            $reseller_search = $this->session->userdata('reseller_search');
            if (!empty($reseller_search['reseller'])) {
                $reseller = $reseller_search['reseller'];
            }
            if (!empty($reseller_search['Destination'])) {
                $destination = $reseller_search['Destination'];
            }
            if (!empty($reseller_search['Pattern'])) {
                $pattern = $reseller_search['Pattern'];
            }
            if (!empty($reseller_search['start_date'])) {
                $start_date_before = $reseller_search['start_date'];
                $start_date_before = explode(" ", $start_date_before);
                $start_date = @$start_date_before[0];
                $time = explode(":", @$start_date_before[1]);

                $start_hour = $time[0];
                $start_minute = $time[1];
                $start_second = "00";
            }
            if (!empty($reseller_search['end_date'])) {
                $end_date_before = $reseller_search['end_date'];

                $end_date_before = explode(" ", $end_date_before);
                $end_date = @$end_date_before[0];
                $time = explode(":", @$end_date_before[1]);

                $end_hour = $time[0];
                $end_minute = $time[1];
                $end_second = "59";
            }
        }

        if ($reseller == NULL) {
            $reseller = "ALL";
        }

        if ($this->session->userdata('logintype') == 1 || $this->session->userdata('logintype') == 5) {
            $sth_reseller = $this->reports_model->getReseller("" . $this->session->userdata('username') . "", $type);
        } else {
            $sth_reseller = $this->reports_model->getReseller("", $type);
        }

        $data['Reseller'] = $reseller;
        $data['reseller'] = $sth_reseller;

        //For Destination
        //$Destination_post = $this->input->post('destination',0);
        if ($destination == NULL) {
            $destination = "ALL";
        }
        if (urldecode($pattern) == NULL) {
            $pattern = "ALL";
        }

        if ($this->session->userdata('logintype') == 1 || $this->session->userdata('logintype') == 5) {
            $sth_destination = $this->reports_model->getDestination("" . $this->session->userdata('username') . "");
        } else {
            $sth_destination = $this->reports_model->getDestination();
        }

        $destination_list = $sth_destination[1];
        $pattern_list = $sth_destination[2];

        $data['Destination'] = $destination;
        $data['destination'] = $destination_list;

        $data['Pattern'] = urldecode($pattern);
        $data['pattern'] = $pattern_list;

        $sd = $start_date;
        $ed = $end_date;

        $data['start_date'] = $sd;
        $data['end_date'] = $ed;

        $data['start_hour'] = $start_hour;
        $data['start_minute'] = $start_minute;
        $data['start_second'] = $start_second;

        $data['end_hour'] = $end_hour;
        $data['end_minute'] = $end_minute;
        $data['end_second'] = $end_minute;

        if ($sd == NULL || $ed == NULL || $sd == 'NULL' || $ed == 'NULL') {
            $sd = date("Y-m-d", strtotime(date('m') . '/01/' . date('Y') . ' 00:00:00'));
            $sd = $sd . " 00:00:00";
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

        if (!empty($_POST)) {// AND $_SERVER['SERVER_ADDR'] == $_SERVER['REMOTE_ADDR'])
            // put your processing code here... we show what we do for emailing. You will need to add a correct email address
            if ($this->_process_create($_POST)) {
                $this->session->set_flashdata('success', TRUE);
                redirect('.');
            }
        }
        $where = "";

        if ($sd != 'NULL' && $ed != 'NULL' && $sd != "" && $ed != "") {
            $where = " AND callstart BETWEEN '" . $sd . "' AND '" . $ed . "' ";
        }

        if ($reseller == 'ALL') {
            if ($this->session->userdata('logintype') == 1 || $this->session->userdata('logintype') == 5) {
                $where .="AND accounid IN (SELECT `id` FROM accounts WHERE reseller_id = '" . $reseller . "' AND type IN (" . $type . ")) ";
            } else {
                $where .="AND accountid IN (SELECT `id` FROM accounts WHERE type IN (" . $type . ")) ";
            }
        } else {
            if ($this->session->userdata('logintype') == 1 || $this->session->userdata('logintype') == 5) {
                $where .="AND accountid = '" . $reseller . "' ";
            } else {
                if (strpos($type, "1") != -1) {
                    $where .= "AND accountid IN (SELECT `number` FROM accounts WHERE `number` = '" . $reseller . "' AND type IN (" . $type . ")) ";
                } elseif (strpos($type, "3") != -1) {
                    if ($reseller != 'ALL') {
                        $where .= "AND accountid = '" . $reseller . "' ";
                    }
                }
            }
        }

        if ($destination == 'ALL') {
            if (urldecode($pattern) == 'ALL' || urldecode($pattern) == "") {
                $where .= "";
            } else {
                $where .= "AND notes LIKE '" . "%|" . urldecode($pattern) . "' ";
            }
        } else {
            if (urldecode($pattern) == 'ALL' || urldecode($pattern) == "") {
                $where .= "";
            } else {

                $where .= "AND (notes LIKE '" . "%|" . $destination . "|%" . "' "
                        . "OR notes LIKE '" . "%|" . urldecode($pattern) . "') ";
            }
        }

        $table = "tmp_" . time();
        $query = "CREATE TEMPORARY TABLE  $table AS SELECT * FROM cdrs WHERE uniqueid != '' " . $where;
        //CREATE VIEW prodsupp AS

        $rs_create = $this->db->query($query);
        
//print_r($this->db->last_query());
//exit;

        if ($rs_create) {
            $sql = $this->db->query("SELECT DISTINCT accountid AS '" . $name . "' FROM $table");
            $count_all = $sql->num_rows();

            $config['total_rows'] = $count_all;
            $config['per_page'] = $_GET['rp'];

            $page_no = $_GET['page'];

            $json_data = array();
            $json_data['page'] = $page_no;
            $json_data['total'] = ($config['total_rows'] > 0) ? $config['total_rows'] : 0;

            $perpage = $config['per_page'];
            $start = ($page_no - 1) * $perpage;
            if ($start < 0)
                $start = 0;

            $admin_reseller_report = $this->reports_model->getCardNum($reseller, $table, $start, $perpage, $name);

            if (count($admin_reseller_report) > 0) {

                foreach ($admin_reseller_report as $key => $value) {
                    $json_data['rows'][] = array('cell' => array($value['bth'],
                            $value['dst'],
                            $value['idd'],
                            $value['atmpt'],
                            $value['cmplt'],
                            $value['asr'],
                            $value['acd'],
                            $value['mcd'],
                            $value['act'],
                            $value['bill'],
                            $this->common_model->calculate_currency($value['price']),
                            $this->common_model->calculate_currency($value['cost'])));
                }
            }
            echo json_encode($json_data);
        }
    }

    function provider_summery_Report() {

        $data['username'] = $this->session->userdata('user_name');
        $data['page_title'] = 'Provider CDRs Report';
        $data['cur_menu_no'] = 5;
        $this->session->set_userdata('advance_search', 0);
        $this->load->view('view_adminReports_providerReport', $data);
    }

    function provider_sum_Report($grid = NULL, $start_date = NULL, $end_date = NULL, $reseller = NULL, $destination = NULL, $pattern = NULL, $start_hour = NULL, $start_minute = NULL, $start_second = NULL, $end_hour = NULL, $end_minute = NULL, $end_second = NULL) {
        $name = "Provider";
        $type = "3";

        $data['username'] = $this->session->userdata('user_name');
        $data['page_title'] = 'Provider Report';

        //For Reseller		
        //$Reseller_post = $this->input->post('Reseller',0);
        if ($this->session->userdata('advance_search') == 1) {
            $provider_search = $this->session->userdata('provider_search');
            if (!empty($provider_search['reseller'])) {
                $reseller = $provider_search['reseller'];
            }
            if (!empty($provider_search['Destination'])) {
                $destination = $provider_search['Destination'];
            }
            if (!empty($provider_search['Pattern'])) {
                $pattern = $provider_search['Pattern'];
            }
            if (!empty($provider_search['start_date'])) {
                $start_date_before = $provider_search['start_date'];

                $start_date_before = explode(" ", $start_date_before);
                $start_date = @$start_date_before[0];
                $time = explode(":", @$start_date_before[1]);

                $start_hour = $time[0];
                $start_minute = $time[1];
                $start_second = "00";
            }
            if (!empty($provider_search['end_date'])) {
                $end_date_before = $provider_search['end_date'];

                $end_date_before = explode(" ", $end_date_before);
                $end_date = @$end_date_before[0];
                $time = explode(":", @$end_date_before[1]);

                $end_hour = $time[0];
                $end_minute = $time[1];
                $end_second = "59";
            }
        }
        if ($reseller == NULL) {
            $reseller = "ALL";
        }

        if ($this->session->userdata('logintype') == 1 || $this->session->userdata('logintype') == 5) {
            $sth_reseller = $this->reports_model->getReseller("" . $this->session->userdata('username') . "", $type);
        } else {
            $sth_reseller = $this->reports_model->getReseller("", $type);
        }

        $data['Reseller'] = $reseller;
        $data['reseller'] = $sth_reseller;

        //For Destination
        //$Destination_post = $this->input->post('destination',0);
        if ($destination == NULL) {
            $destination = "ALL";
        }
        if ($pattern == NULL) {
            $pattern = "ALL";
        }

        if ($this->session->userdata('logintype') == 1 || $this->session->userdata('logintype') == 5) {
            $sth_destination = $this->reports_model->getDestination("" . $this->session->userdata('username') . "");
        } else {
            $sth_destination = $this->reports_model->getDestination();
        }

        $destination_list = $sth_destination[1];
        $pattern_list = $sth_destination[2];

        $data['Destination'] = $destination;
        $data['destination'] = $destination_list;

        $data['Pattern'] = urldecode($pattern);
        $data['pattern'] = $pattern_list;

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

        if (!empty($_POST)) {// AND $_SERVER['SERVER_ADDR'] == $_SERVER['REMOTE_ADDR'])
            // put your processing code here... we show what we do for emailing. You will need to add a correct email address
            if ($this->_process_create($_POST)) {
                $this->session->set_flashdata('success', TRUE);
                redirect('.');
            }
        }
        $where = "";
        if ($sd != 'NULL' && $ed != 'NULL' && $sd != "" && $ed != "") {
            $where = " AND callstart BETWEEN '" . $sd . "' AND '" . $ed . "' ";
        }

        if ($reseller == 'ALL') {
            if ($this->session->userdata('logintype') == 1 || $this->session->userdata('logintype') == 5) {
                $where .="AND accountid IN (SELECT `id` FROM accounts WHERE reseller_id = '" . $reseller . "' AND type IN (" . $type . ")) ";
            } else {
                $where .="AND accountid IN (SELECT `id` FROM accounts WHERE type IN (" . $type . ")) ";
            }
        } else {
            if ($this->session->userdata('logintype') == 1 || $this->session->userdata('logintype') == 5) {
                $where .="AND accountid = '" . $reseller . "' ";
            } else {
                if (strpos($type, "1") != -1) {
                    $where .= "AND accountid IN (SELECT `id` FROM accounts WHERE `id` = '" . $reseller . "' AND type IN (" . $type . ")) ";
                } elseif (strpos($type, "3") != -1) {
                    if ($reseller != 'ALL') {
                        $where .= "AND accountid = '" . $reseller . "' ";
                    }
                }
            }
        }

        if ($destination != 'ALL') {
            $where .= "AND pattern = '" . urldecode($destination) . "'";
        }
        if ($destination == 'ALL') {
            if (urldecode($pattern) == 'ALL' || urldecode($pattern) == "") {
                $where .= "";
            } else {
                $where .= "AND notes LIKE '" . "%|" . urldecode($pattern) . "' ";
            }
        } else {
            if (urldecode($pattern) == 'ALL' || urldecode($pattern) == "") {
                $where .= "";
            } else {
                $where .= "AND (notes LIKE '" . "%|" . $destination . "|%" . "' "
                        . "OR notes LIKE '" . "%|" . urldecode($pattern) . "'  ) ";
            }
        }

        $table = "tmp_" . time();
        //$drop_view = @mysql_query("DROP TEMPORARY TABLE $table");
        //$query ="CREATE TEMPORARY TABLE $table SELECT * FROM cdrs WHERE uniqueid != '' " . $where;
        $query = "CREATE TEMPORARY TABLE  $table AS SELECT * FROM cdrs WHERE uniqueid != '' " . $where;
        //CREATE VIEW prodsupp AS

        $rs_create = $this->db->query($query);

        if ($rs_create) {
            $sql = $this->db->query("SELECT DISTINCT accountid AS '" . $name . "' FROM $table");
            $count_all = $sql->num_rows();

            $config['total_rows'] = $count_all;
            $config['per_page'] = $_GET['rp'];

            $page_no = $_GET['page'];

            $json_data = array();
            $json_data['page'] = $page_no;
            $json_data['total'] = ($config['total_rows'] > 0) ? $config['total_rows'] : 0;

            $perpage = $config['per_page'];
            $start = ($page_no - 1) * $perpage;
            if ($start < 0)
                $start = 0;

            $admin_reseller_report = $this->reports_model->getCardNum($reseller, $table, $start, $perpage, $name);
            if (count($admin_reseller_report) > 0) {
                $json_data['page'] = $page_no;
                $json_data['total'] = $config['total_rows'];

                foreach ($admin_reseller_report as $key => $value) {

                    $json_data['rows'][] = array('cell' => array($value['bth'],
                            $value['dst'],
                            $value['idd'],
                            $value['atmpt'],
                            $value['cmplt'],
                            $value['asr'],
                            $value['acd'],
                            $value['mcd'],
                            $value['act'],
                            $value['bill'],
                            $this->common_model->calculate_currency($value['cost'])));
                }
            }
            echo json_encode($json_data);
        }
    }

    function user_cdrreport() {
        $json_data = array();
        $count_all = $this->reports_model->getuser_cdrs_list(false, "", "");
        $paging_data = $this->form->load_grid_config($count_all, $_GET['rp'], $_GET['page']);
        $json_data = $paging_data["json_paging"];

        $query = $this->reports_model->getuser_cdrs_list(true, $paging_data["paging"]["start"], $paging_data["paging"]["page_no"]);
        $grid_fields = json_decode($this->reports_form->build_report_list_for_user());
        $json_data['rows'] = $this->form->build_grid($query, $grid_fields);

        echo json_encode($json_data);
    }

    function user_cdrreport_search() {
        $ajax_search = $this->input->post('ajax_search', 0);
        if ($this->input->post('advance_search', TRUE) == 1) {
            $this->session->set_userdata('advance_search', $this->input->post('advance_search'));
            $action = $this->input->post();
            unset($action['action']);
            unset($action['advance_search']);
            $this->session->set_userdata('customer_cdr_list_search', $action);
        }
        if (@$ajax_search != 1) {
            redirect(base_url() . 'user/user_cdrs_report/');
        }
    }

    function user_cdrreport_clearsearchfilter() {
        $this->session->set_userdata('advance_search', 0);
        $this->session->set_userdata('account_search', "");
    }

    function user_paymentreport() {
        $json_data = array();
        $count_all = $this->reports_model->getuser_payment_list(false, "", "");
        $paging_data = $this->form->load_grid_config($count_all, $_GET['rp'], $_GET['page']);
        $json_data = $paging_data["json_paging"];

        $query = $this->reports_model->getuser_payment_list(true, $paging_data["paging"]["start"], $paging_data["paging"]["page_no"]);
        $grid_fields = json_decode($this->reports_form->build_payment_report_for_user());
        $json_data['rows'] = $this->form->build_grid($query, $grid_fields);

        echo json_encode($json_data);
    }

    function user_paymentreport_search() {
        $ajax_search = $this->input->post('ajax_search', 0);
        if ($this->input->post('advance_search', TRUE) == 1) {
            $this->session->set_userdata('advance_search', $this->input->post('advance_search'));
            $action = $this->input->post();
            unset($action['action']);
            unset($action['advance_search']);
            $this->session->set_userdata('cdr_payment_search', $action);
        }
        if (@$ajax_search != 1) {
            redirect(base_url() . 'user/user_cdrs_report/');
        }
    }

    function user_paymentreport_clearsearchfilter() {
        $this->session->set_userdata('advance_search', 0);
        $this->session->set_userdata('account_search', "");
    }

    function customer_cdrreport($accountid) {
        $json_data = array();
        $count_all = $this->reports_model->getuser_cdrs_list(false, "", "", $accountid);
        $paging_data = $this->form->load_grid_config($count_all, $_GET['rp'], $_GET['page']);
        $json_data = $paging_data["json_paging"];

        $query = $this->reports_model->getuser_cdrs_list(true, $paging_data["paging"]["start"], $paging_data["paging"]["page_no"], $accountid);
        $grid_fields = json_decode($this->reports_form->build_report_list_for_user());
        $json_data['rows'] = $this->form->build_grid($query, $grid_fields);

        echo json_encode($json_data);
    }

    function customer_paymentreport() {
        $data['username'] = $this->session->userdata('user_name');
        $data['page_title'] = 'Customer Payment Report';
        $this->session->set_userdata('advance_search', 0);
        $data['grid_fields'] = $this->reports_form->build_payment_report_for_user();
        $data['form_search'] = $this->form->build_serach_form($this->reports_form->get_user_cdr_payment_form());
        $this->load->view('view_payment_report', $data);
    }

    function customer_paymentreport_json() {
        $json_data = array();
        $count_all = $this->reports_model->getcustomer_payment_list(false, "", "");
        $paging_data = $this->form->load_grid_config($count_all, $_GET['rp'], $_GET['page']);
        $json_data = $paging_data["json_paging"];

        $query = $this->reports_model->getcustomer_payment_list(true, $paging_data["paging"]["start"], $paging_data["paging"]["page_no"]);
        $grid_fields = json_decode($this->reports_form->build_payment_report_for_user());
        $json_data['rows'] = $this->form->build_grid($query, $grid_fields);

        echo json_encode($json_data);
    }

    function customer_paymentreport_search() {
        $ajax_search = $this->input->post('ajax_search', 0);
        if ($this->input->post('advance_search', TRUE) == 1) {
            $this->session->set_userdata('advance_search', $this->input->post('advance_search'));
            $action = $this->input->post();
            unset($action['action']);
            unset($action['advance_search']);
            $this->session->set_userdata('cdr_payment_search', $action);
        }
        if (@$ajax_search != 1) {
            redirect(base_url() . 'user/user_cdrs_report/');
        }
    }

    function customer_paymentreport_clearsearchfilter() {
        $this->session->set_userdata('advance_search', 0);
        $this->session->set_userdata('account_search', "");
    }

}

?>
 