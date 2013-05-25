<?php

class trunk_model extends CI_Model {

    function trunk_model() {
        parent::__construct();
    }

    function gettrunk_list($flag, $start = 0, $limit = 0) {
        $this->db_model->build_search('trunk_list_search');
        $where = array("status != " => "2");
        if ($flag) {
            $query = $this->db_model->getSelect("*", "trunks", $where, "id", "ASC", $limit, $start);
        } else {
            $query = $this->db_model->countQuery("*", "trunks", $where);
        }
        return $query;
    }

    function add_trunk($add_array) {
        unset($add_array["action"]);
        $new_value = '';
        if (!empty($add_array['resellers_id'])) {

            foreach ($add_array['resellers_id'] as $value) {
                $new_value.=$value . ",";
            }
            $new_value = rtrim($new_value, ',');
        }
        unset($add_array['resellers_id']);
        $add_array['resellers_id'] = $new_value;
        $this->db->insert("trunks", $add_array);
        return true;
    }

    function edit_trunk($data, $id) {
        unset($data["action"]);
        $new_value = '';
        if (!empty($data['resellers_id'])) {
            foreach ($data['resellers_id'] as $value) {
                $new_value.=$value . ",";
            }
            $new_value = rtrim($new_value, ',');
        }
        unset($data['resellers_id']);
        $data['resellers_id'] = $new_value;
        $this->db->where("id", $id);
        $this->db->update("trunks", $data);
    }

    function remove_trunk($id) {
        $this->db->where("id", $id);
        $this->db->update("trunks", array("status" => 2));
        return true;
    }

}
