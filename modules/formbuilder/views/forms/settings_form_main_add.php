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
 * @link      http://wordpress-cost-estimator.zigaform.com
 */
if (!defined('ABSPATH')) {exit('No direct script access allowed');}
?>
<div class="uiform-set-field-wrap" >
  
    
    <div class="space10"></div>
    <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <div class="col-sm-4">
                        <label for=""><?php echo __('ADDITIONAL CSS','FRocket_admin'); ?> <a href="javascript:void(0);"
                       data-toggle="tooltip" data-placement="right" 
                       data-original-title="<?php echo __('This is not required. You can add extra css to your form. this will be added to css file ','FRocket_admin'); ?>"
                       ><span class="fa fa-question-circle"></span></a></label>
                    </div>
                    <div class="col-sm-8">
                         <textarea 
                               id="uifm_frm_main_addcss"
                               name="uifm_frm_main_addcss"
                              
                               style="width:100%;"
                               class="form-control autogrow"></textarea> 
                               <p class="help-block"><?php echo __('Just put the selectors. e.g. #id_form .control-label {color:red} ','FRocket_admin'); ?></p> 
                    </div>
                    
                   
                            
                   
                </div>
            </div>
    </div>
    <div class="space10 zgfm-opt-divider-stl1"></div>
    <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <div class="col-sm-4">
                       <label for=""><?php echo __('ADDITIONAL JAVASCRIPT','FRocket_admin'); ?></label>
                    <a href="javascript:void(0);"
                       data-toggle="tooltip" data-placement="right" 
                       data-original-title="<?php echo __('This is not required. You can add extra javascript code or some script to your form.','FRocket_admin'); ?>"
                       ><span class="fa fa-question-circle"></span></a> 
                    </div>
                    <div class="col-sm-8">
                           <textarea 
                               id="uifm_frm_main_addjs"
                               name="uifm_frm_main_addjs"
                              
                               style="width:100%;"
                               class="form-control autogrow"></textarea>
                         <p class="help-block"><?php echo __('proceed with caution. if you put a wrong javascript code, the form will not work properly e.g. ','FRocket_admin'); ?>
                         <code>
                             <?php ob_start();?>
                        <script type="text/javascript">
                        window.onload = function () {
                            document.getElementsByClassName('uiform-step-content')[0].style.background = "red";
                        };
                        </script>
                            <?php 
                            $cntACmp = ob_get_contents();
                        ob_end_clean();
                        echo htmlentities($cntACmp);
                            ?>
                         </code>
                         </p> 
                    </div>
                </div>
            </div>
    </div>
     
</div>