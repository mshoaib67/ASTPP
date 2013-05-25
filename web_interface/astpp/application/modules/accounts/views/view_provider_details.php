<? extend('master.php') ?>
<? startblock('extra_head') ?>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/module_js/generate_grid.js"></script>
<script type="text/javascript" language="javascript">
    $(document).ready(function() {
        $('#tabs').tabs();
        build_grid("invoices_grid","<?php echo base_url(); ?>accounts/provider_details_json/invoices/<?= $account_data[0]['id']; ?>",<? echo $invoice_grid_fields ?>,"");

        build_grid("cdrs_grid","<?php echo base_url(); ?>accounts/customer_details_json/reports/<?= $account_data[0]['id']; ?>",<? echo $cdrs_grid_fields ?>,"");        

    });
</script>
<? endblock() ?>

<? startblock('page-title') ?>
<?= $page_title ?><br/>
<? endblock() ?>

<? startblock('content') ?>        
<div id="tabs">
    <ul>
        <li><a href="#customer_details">Provider Details</a></li>
        <li><a href="#invoices">Invoices</a></li>
        <li><a href="#cdrs">Cdrs</a></li>
    </ul>	
    <div id="customer_details">
        <div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
            <div class="portlet-header ui-widget-header"><!--< ?php echo isset($account)?"Edit":"Create New";?> Account-->
                <?= @$page_title ?>
                <span class="ui-icon ui-icon-circle-arrow-s"></span></div>
            <div style="color:red;margin-left: 60px;">
                <?php if (isset($validation_errors)) {
                    echo $validation_errors;
                } ?> 
            </div>
                <?php echo $form; ?>
        </div>
    </div>
    <div id='invoices'>
        <div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
            <div class="two-column" style="float:left;width: 100%;">
                <!--                <div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">-->
                <div class="portlet-header ui-widget-header">Invoices<span class="ui-icon ui-icon-circle-arrow-s"></span></div>
                <div class="portlet-content">          
                    <table id="invoices_grid" align="left" style="display:none;"></table>                 
                </div>
                <!--                </div>-->
            </div>
        </div>
    </div>

    <div id='cdrs'>
        <div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
            <div class="two-column" style="float:left;width: 100%;">
                <div class="portlet-header ui-widget-header">CDRs Report<span class="ui-icon ui-icon-circle-arrow-s"></span></div>
                <div class="portlet-content">          
                    <table id="cdrs_grid" align="left" style="display:none;"></table>                 
                </div>
            </div>
        </div>
    </div>

</div>
<? endblock() ?>	
<? startblock('sidebar') ?>
Filter by
<? endblock() ?>
<? end_extend() ?>  
