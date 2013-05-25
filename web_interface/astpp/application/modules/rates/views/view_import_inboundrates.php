<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-1.7.1.js"></script>
<script>
    $(document).ready(function() {
        $("#ok").click(function(){
            window.location='/rates/origination_list/';
        });
    });
</script>

<div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">                        
    <div class="portlet-header ui-widget-header">Import Routes<span class="ui-icon ui-icon-circle-arrow-s"></span></div>
    <div class="portlet-content">
        <form method="post" action="/rates/origination_import_file/" enctype="multipart/form-data" target="submitter" id="termination_rates">
            <span style="font-size:12px">
                File must be in the following format:<br />
                LD PREPEND CODE ie. 00 or 011,CountryCode,Area Code,Destination,Connect Cost,Included Seconds,Per Minute Cost,Pricelist,Increment,Precedence<br />
                The file shall have the text fields escaped with quotation marks and the fields seperated by commas.<br />You do not need to have the patterns setup as regex patterns.  Your pattern will have "^" prepended and ".*" appended.<br /><br />
            </span>
            <label class="desc">Pricelist list:</label>  
            <? $pricelists = form_dropdown('pricelist_id', $this->db_model->build_dropdown("id,name", " pricelists", "where_arr", array("reseller_id" => "0", "status <>" => "2")), '');
            echo $pricelists;
            ?><br/><br/>
            <label class="desc">Select the file:</label>
            <input type="hidden" name="mode" value="Import Routes" />
            <input type="hidden" name="logintype" value="<?= $this->session->userdata('logintype') ?>" />
            <input type="hidden" name="username" value="<?= $this->session->userdata('username') ?>" />
            <input class="ui-state-default ui-corner-all ui-button" type="file" name="rateimport"  size="40" id="rateimport"/>
            <iframe name="submitter" id="submitter" frameborder="0" src="" height="100px" width="100%" style="background-color:transparent; float:left; display:block">
            </iframe>
            <div style="width:100%; float:left;height:40px;margin-top:20px;">            
                <input class="ui-state-default float-right ui-corner-all ui-button" id="ok" type="button" name="action" value="Cancel" />    
                <input class="ui-state-default float-right ui-corner-all ui-button" id="impoddrt_termination1" type="submit" name="action" value="Import..." />            

            </div>
        </form>
    </div>
</div>

