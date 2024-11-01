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
$id_field=(!empty($id))?$id:'';
?>
<div id="<?php echo $id_field;?>"  data-typefield="21" class="uiform-hiddeninput uiform-field  uiform-field-childs">
            <div class="uiform-field-wrap uiform-field-move">
                <div class="rkfm-row">
                    <div class="rkfm-col-sm-12">
                        <input type="text" value="" class="uifm-txtbox-inp8-val form-control" readonly>
                    </div>
                </div>
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
