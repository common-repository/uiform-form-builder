<?php
/**
 * Intranet
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   Rocket_form
 * @author    Softdiscover <info@softdiscover.com>
 * @copyright 2015 Softdiscover
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link      http://wordpress-form-builder.zigaform.com/
 */
if (!defined('ABSPATH')) {exit('No direct script access allowed');}
?>
 
<div class="uiform-editing-main" data-uiform-page="form_edit">
        <div class="uifm-edit-panel-left uifm-edit-panel">
                <div class="uiform-builder-fields">
                    <div 
                        data-intro="<?php echo __('Fields section. Drag and Drop or just click to add to the preview section','FRocket_admin');?>"
                        class="uiform-sidebar-fields">
                         <?php include('fields_available.php');?>
                    </div>

                </div>
            <div id="uifm-panel-arrow-left" style="" 
                 class="uifm-panel-toggler uifm-panel-toggler-west uifm-layout-toggler-open"
                 title="Close">
                <span class="uifm-arrow uifm-arrow-open" style="display: block; margin-top: 17px;">
                    <i class="fa fa-caret-left"></i>
                </span>
                <span class="uifm-arrow uifm-arrow-closed" style="display: none; margin-top: 17px;">
                    <i class="fa fa-caret-right"></i>
                </span>
            </div>
        </div>
        <div class="uifm-edit-panel-center uifm-edit-panel">
            <div class="uiform-builder-preview">
            <div 
                data-intro="<?php echo __('preview section. you can view how the fields will look like and you can change the settings on live mode  ','FRocket_admin');?>"
                class="uiform-preview-base">
                <div class="uiform-main-form">
                    <div class="uiform-step-list uiform-wiztheme0" style="display:none;">
                        <ul class="uiform-steps">
                            <li class="uifm-current">
                                <a data-tab-nro="0" href="#uifm-step-tab-0">
                                    <span class="uifm-number">1</span>
                                    <span class="uifm-title"><?php echo __('Tab title','FRocket_admin'); ?></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="uiform-step-content" style="min-height:100px;">
                        <div data-uifm-step="0" id="uifm-step-tab-0" class="uiform-step-pane">
                            <div id="" class="uiform-items-container uiform-tab-container">
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
               
            </div>
        </div>
        <div class="uifm-edit-panel-right uifm-edit-panel">
           <div class="uiform-builder-data">
              <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                            <li class="active"><a href="#uiform-build-form-tab" data-toggle="tab"><?php echo __('Form','FRocket_admin');?></a></li>
                            <li class="uifm-tab-selectedfield"  style="display:none;"><a href="#uiform-build-field-tab"  data-toggle="tab" ><?php echo __('Selected Field','FRocket_admin');?> </a><i id="uifm-close-setting-tab" class="glyphicon glyphicon-remove"></i></li>
                            </ul> 
               <!-- Tab panes -->
                            <div class="tab-content">
                                
                                    <div class="tab-pane in active" id="uiform-build-form-tab">
                                        <div 
                                            data-intro="<?php echo __('Form settings. you can set all options related to the form','FRocket_admin');?>"
                                            class="uiform-builder-maintab-container">
                                            <div class="uiform-set-panel-header">
                                            <div class="uiform-set-panel-header-inner">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                            <div class="uiform-set-panel-header-fldnme">
                                                                <label for=""><?php echo __('Form name','FRocket_admin');?> 
                                                                    <a data-original-title="This is important to identify the form on backend" 
                                                                       data-placement="right" 
                                                                       data-toggle="tooltip" 
                                                                       href="javascript:void(0);"><span class="fa fa-question-circle"></span></a>
                                                                </label>
                                                                <input type="text" placeholder="Type form name" 
                                                                       class="" 
                                                                       id="uifm_frm_main_title"
                                                                        name="uifm_frm_main_title" >
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                            
                                    <div class="uiform-set-panel-container">
                                        <!-- first panel -->
                                            <div class="uiform-set-panel-1 ">
                                                    <!-- Nav tabs -->
                                                    <ul class="nav nav-tabs">
                                                        <!--<li>
                                                            <a 
                                                                class="uiform-settings-main"
                                                                data-intro="<?php echo __('setting section. you can change the submit mode between ajax and post','FRocket_admin');?>"
                                                                href="#uiform-settings-tab3-1"
                                                                              data-toggle="tab"><?php echo __('Settings','FRocket_admin'); ?></a></li>-->
                                                        <li  class="active"><a 
                                                                class="uiform-settings-skin"
                                                                data-intro="<?php echo __('skin section. you can change skin options of your form','FRocket_admin');?>"
                                                                href="#uiform-settings-tab3-2" data-toggle="tab" ><?php echo __('Skin','FRocket_admin'); ?></a></li>
                                                        <li>
                                                            <a 
                                                                class="uiform-settings-wizard"
                                                                data-intro="<?php echo __('wizard section. you can enable the wizard form and add many forms','FRocket_admin');?>"
                                                                href="#uiform-settings-tab3-3" data-toggle="tab" ><?php echo __('Wizard','FRocket_admin'); ?></a></li>
                                                       <!-- <li>
                                                            <a 
                                                                class="uiform-settings-email"
                                                                data-intro="<?php echo __('email section. you can set mail options. e.g. the recipient mail','FRocket_admin');?>"
                                                                href="#uiform-settings-tab3-4" data-toggle="tab" ><?php echo __('Email','FRocket_admin'); ?></a></li>
                                                        <li>
                                                            <a 
                                                                class="uiform-settings-subm"
                                                                data-intro="<?php echo __('submission section. you can modify the success message and other messages','FRocket_admin');?>"
                                                                href="#uiform-settings-tab3-5" data-toggle="tab" ><?php echo __('On Submission','FRocket_admin'); ?></a></li>-->
                                                    </ul>

                                                    <!-- Tab panes -->
                                                    <div class="tab-content  ">

                                                            <!--<div class="tab-pane in active" id="uiform-settings-tab3-1">
                                                                <div class="uiform-tab-content scroll-pane-arrows">
                                                                    <div class="uiform-tab-content-inner">
                                                                    
                                                                    </div>
                                                                </div>
                                                            </div>-->
                                                            <div class="tab-pane in active" id="uiform-settings-tab3-2">
                                                                <div class="uiform-tab-content  scroll-pane-arrows">
                                                                    <div class="uiform-tab-content-inner">
                                                                     <!--container -->
                                                                        <?php include('settings_form_skin.php');?>
                                                                    <!--\end container -->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <div class="tab-pane" id="uiform-settings-tab3-3">
                                                                <div class="uiform-tab-content  scroll-pane-arrows">
                                                                    <div class="uiform-tab-content-inner">
                                                                        <!--container -->
                                                                        <?php include('settings_form_wizard.php');?>
                                                                        <!--\end container -->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                      <!--  <div class="tab-pane" id="uiform-settings-tab3-4">
                                                                <div class="uiform-tab-content  scroll-pane-arrows">
                                                                    <div class="uiform-tab-content-inner">
                                                                       
                                                                    </div>
                                                                </div>
                                                            </div> --> 
                                                        <!--<div class="tab-pane" id="uiform-settings-tab3-5">
                                                                <div class="uiform-tab-content  scroll-pane-arrows">
                                                                    <div class="uiform-tab-content-inner">
                                                                      
                                                                    </div>
                                                                </div>
                                                            </div>-->
                                                    </div>
                                                    <!--\ End Tab panes --> 
                                            </div>
                                            
                                    </div>
                                        </div>
                                        
                                        
                                    </div>
                                    <div class="tab-pane" id="uiform-build-field-tab" >
                                        <input type="hidden" id="uifm-field-selected-id" value="">
                                        <div class="uiform-builder-maintab-container">
                                            <div class="uiform-set-panel-header">
                                            <div class="uiform-set-panel-header-inner">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="uifm-set-section-fieldname">
                                                            <div class="uiform-set-panel-header-fldnme">
                                                                <label for=""><?php echo __('Field name','FRocket_admin'); ?> <a href="javascript:void(0);"
                       data-toggle="tooltip" data-placement="right"
                       data-original-title="<?php echo __('This is important to identify the field on reports and conditional logic','FRocket_admin'); ?>"
                       ><span class="fa fa-question-circle"></span></a></label>
                                                                <input type="text" id="uifm_fld_main_fldname" class="form-control" placeholder="<?php echo __('Type field name','FRocket_admin'); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="uiform-set-panel-header-opts">
                                                        <span> <?php echo __('Options','FRocket_admin'); ?>:</span>
                                                        <div class="btn-group ">
                                                            
                                                            <button class="btn btn-sm btn-danger"
                                                                    onclick="javascript:rocketform.fieldsetting_deleteFieldDialog();">
                                                                <i class="fa fa-trash-o"></i> <?php echo __('Remove','FRocket_admin'); ?></button>
                                                        </div>  
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                
                                                
                                            </div>
                                        </div>
                                         
                                    <div class="uiform-set-panel-container">
                                        <!-- first panel -->
                                            <div class="uiform-set-panel-1 ">
                                                    <div class="uiform-set-options-tabs">
                                                        <!-- Nav tabs -->
                                                        <ul class="nav nav-tabs" style="left:0;">
                                                        <li class="active">
                                                            <a data-uifm-title="label" href="#uiform-settings-tab-1" class="uifm-tab-fld-label" 
                                                            data-toggle="tab"><?php echo __('Label','FRocket_admin'); ?></a></li>
                                                        <li><a data-uifm-title="input" href="#uiform-settings-tab-2" 
                                                            class="uifm-tab-fld-input"  
                                                            data-toggle="tab" ><?php echo __('Input','FRocket_admin'); ?></a></li>
                                                        <li><a data-uifm-title="helpb" href="#uiform-settings-tab-3" 
                                                            class="uifm-tab-fld-helpblock"  
                                                            data-toggle="tab" ><?php echo __('Help Block','FRocket_admin'); ?></a></li>
                                                        <li><a data-uifm-title="validate" href="#uiform-settings-tab-4" 
                                                            class="uifm-tab-fld-validation"  
                                                            data-toggle="tab" ><?php echo __('Validators','FRocket_admin'); ?></a></li>       
                                                        <li><a href="#uiform-settings-tab-5" 
                                                            class="uifm-tab-fld-appendimgs"  
                                                            data-toggle="tab"><?php echo __('Images','FRocket_admin'); ?></a></li>
                                                        <li><a href="#uiform-settings-tab-6" 
                                                            class="uifm-tab-fld-logicrls last-child"  
                                                            data-toggle="tab" ><?php echo __('C.Logic','FRocket_admin'); ?></a></li>
                                                        <li><a href="#uiform-settings-tab-7" 
                                                            class="uifm-tab-fld-misc"  
                                                            data-toggle="tab" ><?php echo __('Misc','FRocket_admin'); ?></a></li>       
                                                        </ul>
                                                        <div class="uifm-tab-navigation" style="display:none;">
                                                            <div>
                                                                <a class="uifm-previous-tab" href="javascript:void(0);" onclick="javascript:rocketform.setScrollTab(1,this);" >Ã¢â€”â‚¬</a>
                                                                <a class="uifm-next-tab" href="javascript:void(0);" onclick="javascript:rocketform.setScrollTab(-1,this);">Ã¢â€“Â¶</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    

                                                    <!-- Tab panes -->
                                                    <div class="tab-content  ">
                                                            <div class="tab-pane in active uifm-tab-fld-label" id="uiform-settings-tab-1">
                                                                <div class="uiform-tab-content scroll-pane-arrows">
                                                                    <div class="uiform-tab-content-inner">
                                                                        <!--\container -->
                                                                         <?php include('settings_field_label.php');?>
                                                                        <!--\end container -->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane uifm-tab-fld-input" id="uiform-settings-tab-2">
                                                                <div class="uiform-tab-content  scroll-pane-arrows">
                                                                    <div class="uiform-tab-content-inner">
                                                                          <!--container -->    
                                                                        <?php include('settings_field_input.php');?>
                                                                    <!--\end container -->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <div class="tab-pane uifm-tab-fld-helpblock" id="uiform-settings-tab-3">
                                                                <div class="uiform-tab-content  scroll-pane-arrows">
                                                                    <div class="uiform-tab-content-inner">
                                                                         <?php include('settings_field_helpblock.php');?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <div class="tab-pane uifm-tab-fld-validation" id="uiform-settings-tab-4">
                                                                <div class="uiform-tab-content  scroll-pane-arrows">
                                                                    <div class="uiform-tab-content-inner">
                                                                        <?php include('settings_field_validate.php');?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <div class="tab-pane uifm-tab-fld-appendimgs" id="uiform-settings-tab-5">
                                                                <div class="uiform-tab-content  scroll-pane-arrows">
                                                                    <div class="uiform-tab-content-inner">
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <div class="tab-pane uifm-tab-fld-logicrls" id="uiform-settings-tab-6">
                                                                <div class="uiform-tab-content  scroll-pane-arrows">
                                                                    <div class="uiform-tab-content-inner">
                                                                       <?php include('settings_form_clogic.php');?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <div class="tab-pane uifm-tab-fld-misc" id="uiform-settings-tab-7">
                                                                <div class="uiform-tab-content  scroll-pane-arrows">
                                                                    <div class="uiform-tab-content-inner">
                                                                     
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    </div>
                                                    <!--\ End Tab panes --> 
                                            </div>
                                            <!-- second panel -->
                                           
                                    </div>
                                        </div>
                                        
                                        
                                        
                                    </div>
                            </div>
                            <!--\ End Tab panes --> 
                              
              
           
           </div>
            <div id="uifm-panel-arrow-right" style="" 
                 class="uifm-panel-toggler uifm-panel-toggler-west uifm-layout-toggler-open"
                 title="Close">
                <span class="uifm-arrow uifm-arrow-open" style="display: block; margin-top: 17px;">
                    <i class="fa fa-caret-right"></i>
                </span>
                <span class="uifm-arrow uifm-arrow-closed" style="display: none; margin-top: 17px;">
                    <i class="fa fa-caret-left"></i>
                </span>
            </div>
        </div>
    
</div>
<script>
  
    window.onload = function () {
    rocketform.loadForm_globalSettings();
    <?php
if(isset($form_id) && intval($form_id)>0){
 ?>
    rocketform.loadFormSaved(<?php echo $form_id?>);
  <?php
}else{
?>  
     rocketform.loadNewForm();
     <?php  if(isset($_GET['opt']) && Uiform_Form_Helper::sanitizeInput($_GET['opt'])==='import'){?>
          rocketform.importForm_openModal(); 
      <?php }else{?>
          rocketform.formsetting_setFieldName(); 
      <?php } ?>    
 <?php
    }
    
    ?>
            rocketform.loadForm_globalSettings_end();
};
</script>

<?php
if(isset($form_id) && intval($form_id)>0){
 ?>
<input type="hidden" id="uifm_frm_main_id" value="<?php echo $form_id?>" >
<?php
}else{
?>
<input type="hidden" id="uifm_frm_main_id" value="0" >
<?php
}
?>   
<input type="hidden" id="uifm_frm_preview_msg_notsaved" value="<?php echo __('You need to save the form before seeing the preview','FRocket_admin'); ?>">
<input type="hidden" id="uifm_frm_preview_msg_desktop_title" value="<?php echo __('Preview for desktops','FRocket_admin'); ?>">
<input type="hidden" id="uifm_frm_preview_msg_tablet_title" value="<?php echo __('Preview for tablets','FRocket_admin'); ?>">
<input type="hidden" id="uifm_frm_preview_msg_phone_title" value="<?php echo __('Preview for smartphone','FRocket_admin'); ?>">
<input type="hidden" id="uifm_frm_preview_import_title" value="<?php echo __('Import form','FRocket_admin'); ?>">
<input type="hidden" id="uifm_frm_preview_import_onfail" value="<?php echo __('The import has failed. press the accept button','FRocket_admin'); ?>">