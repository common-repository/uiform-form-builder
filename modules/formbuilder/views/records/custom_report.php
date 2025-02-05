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
<div id="uiform-container" data-uiform-page="" class="uiform-wrap">
        <div class="col-md-12">
            <div class="space20"></div>
            <div class="row">
                <div class="col-md-12">
                <select name="uifm-record-form"
                        onchange="javascript:rocketform.customReport_loadFieldbyForm();"
                        id="uifm-record-form-cmb"
                        >
                    <?php foreach ($list_forms as $value) { ?>
                    <option value="<?php echo $value->fmb_id;?>"><?php echo $value->fmb_name;?></option>
                    <?php } ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <h2><?php echo __('Custom report','FRocket_admin'); ?></h2>
            </div>
            <div class="row">
                <div class="alert alert-info"><?php echo __('Select your custom fields to be shown on list report','FRocket_admin'); ?></div>
            </div>
            <div class="row">
                <form enctype="multipart/form-data" 
                      class="uiform_estimator"
                      id="uifm-customreport-form"
                      accept-charset="utf-8" method="post">
                <div id="uifm-customreport-results"></div>
                </form>
            </div>
            <div class="space10"></div>
            <div class="row">
                <a 
                   href="javascript:void(0);" 
                   onclick="javascript:rocketform.customReport_saveFields();"
                   class="btn btn-primary"
                   >
                    <?php echo __('Save Changes','FRocket_admin'); ?>
                </a>
            </div>
        </div>
    
</div>
<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function () {
    rocketform();
    rocketform.customReport_loadFieldbyForm();
});
//]]>
</script>