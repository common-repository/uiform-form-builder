<?php
if (!defined('ABSPATH')) {exit('No direct script access allowed');}
?>
<!-- Modal -->
<div class="modal fade" id="uifm_modal_msg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title"></h4>

            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" 
                        class="btn btn-default"
                        data-dismiss="modal"><?php echo __('Close','FRocket_admin'); ?></button>
                
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- Modal -->
<div class="modal fade" id="uifm_modal_alert_regen_msg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                 <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" 
                        class="btn btn-primary"
                        onclick="javascript:rocketform.regenerateform();rocketform.loadFormSaved_regen_closePopUp();"
                        ><?php echo __('Regenerate','FRocket_admin'); ?></button>
                
                
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<div style="display:none;" class="uifm_modal_caption">
    <input type="hidden" value="<?php echo __('Tour guide info','FRocket_admin'); ?>" id="uifm_guidetour_popup_title">
    <input type="hidden" value="<?php echo __('there is not tour guide for this page. press the accept button','FRocket_admin'); ?>" id="uifm_guidetour_popup_notfound">
    <input type="hidden" value="<?php echo __('Success! Updated successfully','FRocket_admin'); ?>" id="uifm_globalopt_success">
    <input type="hidden" value="<?php echo __('Form was created','FRocket_admin'); ?>" id="uifm_newform_popup_success">
    <input type="hidden" value="<?php echo __('Success! The form was created. Now just copy and paste the shortcode to your content','FRocket_admin'); ?>" id="uifm_newform_popup_success_cont">
    <input type="hidden" value="<?php echo __('the form did not loaded properly. Press regenerate button in order to recover the form','FRocket_admin'); ?>" id="alert_uifm_loading_reg_cont">
</div>
<div style="display:none;">
    
    <input type="hidden" id="alert_uifm_loading_reg_title" value="<?php echo __('Regenerate Form','FRocket_admin'); ?>">
    
    <input type="hidden" id="uifm_fld_del_box_title" value="<?php echo __('Delete field selected','FRocket_admin'); ?>" >
    <input type="hidden" id="uifm_fld_del_box_msg" value="<?php echo __('Are you sure?','FRocket_admin'); ?>" >
    <input type="hidden" id="uifm_fld_del_box_bt1_title" value="<?php echo __('Cancel','FRocket_admin'); ?>" >
    <input type="hidden" id="uifm_fld_del_box_bt2_title" value="<?php echo __('Yes','FRocket_admin'); ?>" >
</div>
<div id="uiform-confirmation-func-action-dialog" style="display: none;">
    <?php echo __('Are you sure about this?','FRocket_admin'); ?>
</div>