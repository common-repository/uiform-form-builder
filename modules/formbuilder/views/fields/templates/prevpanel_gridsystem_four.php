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
ob_start();
?>
<?php 
$id_field=(!empty($id_field))?$id_field:'';
?>
<div id="<?php echo $id_field;?>" data-typefield="4" data-iscontainer="1" class="uiform-gridsytem-table uiform-gridsystem-four uiform-field">
        <div class="uiform-field-wrap uiform-field-move">
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tr>
                        <td data-maxpercent="25" data-blocks="3" width="25%">
                            <div class="uiform-items-container uiform-grid-inner-col rkfm-bend-fcontainer-wrap">
                                
                            </div>
                        </td>
                        <td  data-maxpercent="50" data-blocks="3" width="25%">
                            <div class="uiform-items-container uiform-grid-inner-col rkfm-bend-fcontainer-wrap">
                                
                            </div>
                        </td>
                        <td data-maxpercent="75" data-blocks="3" width="25%">
                            <div class="uiform-items-container uiform-grid-inner-col rkfm-bend-fcontainer-wrap">
                                
                            </div>
                        </td>
                        <td  data-maxpercent="100" data-blocks="3" width="25%">
                            <div class="uiform-items-container uiform-grid-inner-col">
                                
                            </div>
                        </td>    
                    </tr>
            </table>
            <?php echo $quick_options;?>
       </div>
    </div>
<?php
$cntACmp = ob_get_contents();
/*$cntACmp = str_replace("\n", '', $cntACmp);
$cntACmp = str_replace("\t", '', $cntACmp);
$cntACmp = str_replace("\r", '', $cntACmp);
$cntACmp = str_replace("//-->", ' ', $cntACmp);
$cntACmp = str_replace("//<!--", ' ', $cntACmp);
@$cntACmp = eregi_replace("[[:space:]]+", " ", $cntACmp);*/
ob_end_clean();
echo $cntACmp;
?>
