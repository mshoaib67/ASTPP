<?php

class Login extends MX_Controller {

    function Login() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('astpp/permission');
        $this->load->model('Auth_model');
        $this->load->model('db_model');
    }

    function index() {
        if ($this->session->userdata('user_login') == FALSE) {
            if (!empty($_POST)) {// AND $_SERVER['SERVER_ADDR'] == $_SERVER['REMOTE_ADDR'])
                $this->load->model('system_model');

                $config = $this->system_model->getAuthInfo();
                $config_info = @$config[0];

                $user_valid = $this->Auth_model->verify_login($_POST['username'], $_POST['password']);
                if ($user_valid == 1) {
                    $this->session->set_userdata('user_login', TRUE);
                    $result = $this->db_model->getSelect("*", "accounts", array("number" => $_POST['username']));
                    $result = $result->result_array();
                    $result = $result[0];
                    $this->session->set_userdata('logintype', $result['type']);
                    $this->session->set_userdata('userlevel_logintype', $result['type']);
                    $this->session->set_userdata('username', $_POST['username']);
                    $this->session->set_userdata('accountinfo', $result);
                    if ($result['type'] == 0 || $result['type'] == 1) {
                        $menu_list = $this->permission->get_module_access($result['type']);
                        $this->session->set_userdata('mode_cur', 'user');
                        if($result['type'] == 1){
                            redirect(base_url() . 'dashboard/');
                        }else{
                            redirect(base_url() . 'user/user');
                        }
                    } else {
                        $menu_list = $this->permission->get_module_access($result['type']);
                        $this->session->set_userdata('mode_cur', 'admin');
                        redirect(base_url() . 'dashboard/');
                    }
                } else {
                    if ($_POST['username'] == "" && $config_info->value == $_POST['password']) {
                        $this->session->set_userdata('user_login', TRUE);
                        $this->session->set_userdata('logintype', 2);
                        $this->session->set_userdata('userlevel_logintype', -1);
                        $this->session->set_userdata('mode_cur', 'admin');
                        $menu_list = $this->permission->get_module_access(-1);
                        redirect(base_url() . '/dashboard/');
                    } else {
                        $data['astpp_errormsg'] = "Login Failed! Try Again..";
                    }
                }
            }

            $this->session->set_userdata('user_login', FALSE);
            $data['app_name'] = 'ASTPP - Open Source Billing Solution';
            $this->load->view('view_login', $data);
        } else {
            redirect(base_url() . 'dashboard/');
        }
    }

    function logout() {
        $this->session->sess_destroy();
        redirect(base_url());
    }

}

?>
