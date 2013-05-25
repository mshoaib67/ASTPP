<?php

class Invoices extends MX_Controller {

    function Invoices() {
        parent::__construct();

        $this->load->helper('template_inheritance');

        $this->load->library('session');
        $this->load->library('invoices_form');
        $this->load->library('astpp/form');
        $this->load->model('invoices_model');
        $this->load->model('Astpp_common');

        if ($this->session->userdata('user_login') == FALSE)
            redirect(base_url() . '/astpp/login');
    }

    function invoice_list() { 
	$data['app_name'] = 'ASTPP - Open Source Billing Solution | Accounting | Manage Invoice';
        $data['username'] = $this->session->userdata('user_name');
        $data['page_title'] = 'Invoices List';
        $data['cur_menu_no'] = 2;
        $this->session->set_userdata('advance_search',0);
        $data['grid_fields']= $this->invoices_form->build_invoices_list_for_admin();
        $data["grid_buttons"] = $this->invoices_form->build_grid_buttons();
        $data['form_search']=$this->form->build_serach_form($this->invoices_form->get_invoice_search_form());
        $this->load->view('view_invoices_list',$data);
    }
    /**
     * -------Here we write code for controller accounts functions account_list------
     * Listing of Accounts table data through php function json_encode
     */
    function invoice_list_json() {
	
        $json_data = array();
 
	$count_all = $this->invoices_model->getcharges_list(false);
        
        $paging_data =  $this->form->load_grid_config($count_all,10,1);
        $json_data = $paging_data["json_paging"];
	
        $query = $this->invoices_model->getcharges_list(true,$paging_data["paging"]["start"], $paging_data["paging"]["page_no"]);
        
        $grid_fields= json_decode($this->invoices_form->build_invoices_list_for_admin());
        
        
        $json_data['rows'] = $this->form->build_grid($query,$grid_fields);
        
        echo json_encode($json_data);
        
    }
      function invoice_conf() {
          
        $data['page_title'] = 'Invoice Configuration';

//        $invoiceconf = $this->charges_model->get_invoiceconf();
//        $data['invoiceconf'] = $invoiceconf;
        
        if ($this->input->post('action')) {
           $post_array=$this->input->post();
           
           $this->invoices_model->save_invoiceconf($post_array);
            $this->session->set_userdata('astpp_notification', 'Invoice Configuration Updated Sucessfully!');
        }
$invoiceconf=array();
        $invoiceconf = $this->invoices_model->get_invoiceconf();
//        $data['invoiceconf'] = $invoiceconf;

        $data['form']=$this->form->build_form($this->invoices_form->get_invoiceconf_form_fields(),$invoiceconf);
        
        
        $this->load->view('view_invoiceconf', $data);
    }
      function customer_invoices($accountid){
        $json_data = array();
        $where = array('accountid' => $accountid);
        $count_all = $this->db_model->countQuery("*","invoices",$where);
        
        $paging_data =  $this->form->load_grid_config($count_all,10,1);
        $json_data = $paging_data["json_paging"];
	
        $Invoice_grid_data = $this->db_model->select("*","invoices",$where,"date","desc",$paging_data["paging"]["page_no"],$paging_data["paging"]["start"]);
        $grid_fields= json_decode($this->invoices_form->build_invoices_list_for_admin());
        
        $json_data['rows'] = $this->form->build_grid($Invoice_grid_data,$grid_fields);
        
        echo json_encode($json_data);
    }
    
    /**
     * -------Here we write code for controller accounts functions view_invoice------
     * We fetch invoice detail from CDRS table through Invoice ID
     * @invoiceid: Invoice ID
     */
    function invoice_list_view_invoice($invoiceid=false) {
        
        $data['username'] = $this->session->userdata('user_name');
        $data['page_title'] = 'Invoice Detail';
        

        $cdrs_query = $this->invoices_model->getCdrs_invoice($invoiceid);
        
        $invoice_cdr_list = array();
        $cdr_list = array();
        if ($cdrs_query->num_rows() > 0) {
            foreach ($cdrs_query->result_array() as $cdr) {
                $cdr['charge'] = $this->common_model->calculate_currency($cdr['debit'] - $cdr['credit']);
// 				$cdr['charge'] = money_format( "%.6n", $cdr['charge'] );
                array_push($cdr_list, $cdr);
            }
        }
        $data['invoice_cdr_list'] = $cdr_list;

        $invoice_total_query = $this->Astpp_common->get_invoice_total($invoiceid);
        
        $total_list = array();
        $invoice_total_list = array();

        if ($invoice_total_query->num_rows() > 0) {
            foreach ($invoice_total_query->result_array() as $total) {
                array_push($total_list, $total);
            }
        }

        $data['invoice_total_list'] = $total_list;

        $invoicedata = $this->Astpp_common->get_invoice($invoiceid);
               

        $data['invoiceid'] = @$invoicedata[0]['invoiceid'];
        $data['invoicedate'] = @$invoicedata[0]['date'];
        $data['accountid'] = @$invoicedata[0]['accountid'];

//        echo "<pre>";
//        print_r(@$invoicedata);
//        exit;
        if(!empty($invoicedata)){
        $accountinfo = $this->invoices_model->get_account_including_closed(@$invoicedata[0]['accountid']);
        $data['accountinfo'] = $accountinfo;
        }
        //Get invoice header information
        $invoiceconf = $this->invoices_model->get_invoiceconf($accountinfo['reseller']);
        $data['invoiceconf'] = $invoiceconf;
        $this->load->view('view_account_invoice_detail', $data);
    }
}

?>
 