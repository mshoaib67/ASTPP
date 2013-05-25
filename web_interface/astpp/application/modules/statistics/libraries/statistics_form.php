<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Statistics_form{
    
    function build_error_list_for_admin(){
      // array(display name, width, db_field_parent_table,feidname, db_field_child_table,function name);
      $grid_field_arr  = json_encode(array(array("uniqueID","120","uniqueid","","",""),
				    array("Date","100","calldate","","",""),
				    array("CalleID","100","clid","","",""),
				    array("Source","100","src","","",""),
				    array("Dest","80","dst","","",""),
				    array("Dest. Context","70","dcontext","","",""),
				    array("Channel","170","channel","","",""),
				    array("Dest. Channel","90","dstchannel","","",""),
				    array("Last App","80","lastapp","","",""),
				    array("Last Data","70","lastdata","","",""),
				    array("Duration","70","duration","","",""),
				    array("Bill Sec","70","billsec","","",""),
				    array("Disposition","70","disposition","","",""),
				    array("AMA Flags","70","amaflags","","",""),
				    array("Account Code","80","accountcode","","",""),
				    array("User Field","50","userfield","","",""),
				    array("Cost","50","cost","","","")
                ));
      return $grid_field_arr;
    }
}
?>
