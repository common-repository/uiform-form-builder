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
<div id="uiform-container" data-uiform-page="" class="uiform-wrap">
        <div class="col-md-12">
            <div class="space20"></div>
            <div class="uifm-settings-response"></div>
           
            <div class="uiform-systemcheck-inner-box">
                <div class="row">
        <div class="col-lg-12">
        <div class="widget widget-padding span12">
            <div class="widget-header">
                <i class="fa fa-list-alt"></i>
                <h5>
                <?php echo __('System check','FRocket_admin');?>
                </h5>
               
            </div>  
            <div class="widget-body">
               <div class="widget-forms clearfix">
                   <form 
                       id="uifrm-setting-form"
                       chartset="utf-8"
                       name="frmform"
                       class="form-horizontal">
                       <div id="uiform-settings-inner-body">
                        
                        <div class="form-group">
                            <label class=" col-sm-2 control-label"><?php echo __('Database Integrity','FRocket_admin');?></label>
                            <div class="col-sm-10">
                                <div class="span4">
                                    <table class="systemcheck-db-table table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th><?php echo __('Tables','FRocket_admin');?></th>
                                                <th><?php echo __('Status','FRocket_admin');?></th>
                                                    
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <?php foreach ($database_int as $value) {
                                            ?>
                                            <tr>
                                                <td><?php echo $value['table'];?></td>
                                                <td><?php if(intval($value['status'])===1){
                                                    ?>
                                                        <i class="fa fa-thumbs-up"></i>
                                                        <?php
                                                }else{
                                                    ?>
                                                      <i class="fa fa-exclamation-triangle"></i>  
                                                        <?php
                                                }
                                                
                                                ?></td>
                                                    
                                            </tr>
                                            <?php }?>
                                                
                                                
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5" class="text-center"> <?php echo __('if you see a red status for any table, it means the plugin installation have failed', 'FRocket_admin'); ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                   
                                </div>
                            </div>
                        </div>   
                           
                        </div>
                   </form>
               </div>
                <div class="space20"></div>
                <h3><?php echo __('Directives','FRocket_admin');?></h3>
                <div class="uiform-systemcheck-directive-container">
                    <div class="form-group">
                            <label class=" col-sm-2 control-label"><?php echo __('allow_url_fopen','FRocket_admin');?> (For Recaptcha)</label>
                            <div class="col-sm-10">
                                <div class="span4">
                                     <?php if(ini_get('allow_url_fopen')){
                                                    ?>
                                                        <i class="fa fa-thumbs-up"></i>
                                                        <?php
                                                }else{
                                                    ?>
                                                      <i class="fa fa-exclamation-triangle"></i>  <div class="alert alert-danger">
  <?php echo __('allow_url_fopen php directive is disabled and Recaptcha will not work','FRocket_admin');?>
</div>
                                                        <?php
                                                }
                                                
                                                ?>
                                </div>
                            </div>
                        </div>
                </div>
                
                
                
                <div class="clear"></div>
            </div>
            <div class="widget-footer">
                
        </div>
        </div> 
    </div>
    </div>
            </div>
        </div>
    
</div>
<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function () {
    rocketform();
});
//]]>
</script>