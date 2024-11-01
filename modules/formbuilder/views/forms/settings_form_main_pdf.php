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
<div class="uiform-set-field-wrap"  >
  <div class="space20"></div>
     
    <div class="row">
        <div class="form-group">
            <div class="col-sm-4">
                <label ><?php echo __('SELECT CHARACTER ENCONDING', 'FRocket_admin'); ?></label> 
            </div>
            <div class="col-sm-8">
                 <select name="uifm_frm_email_usr_pdf_charset"
                        id="uifm_frm_email_usr_pdf_charset">
                    <option value="UTF-8">Unicode (UTF-8)</option>
                    <option value="EUC-KR">Korean</option>
                    <option value="EUC-JP">Japanese (EUC)</option>
                    <option value="Shift_JIS">Japanese (Shift-JIS)</option>
                    <option value="Big5">Chinese Traditional (Big5)</option>
                    <option value="HZ">Chinese Simplified (HZ)</option>
                    <option value="GB2312">Chinese Simplified (GB2312)</option>
                    <option value="GB18030">Chinese Simplified (GB18030)</option>
                    <option value="ISO-8859-1">Western European (ISO)</option>
                    <option value="ISO-8859-9">Turkish (ISO)</option>
                    <option value="ISO-8859-15">Latin 9 (ISO)</option>
                    <option value="ISO-8859-8">Hebrew (ISO-Visual)</option>
                    <option value="ISO-8859-8-l">Hebrew (ISO-Logical)</option>
                    <option value="ISO-8859-7">Greek (ISO)</option>
                    <option value="ISO-8859-13">Estonian (ISO)</option>
                    <option value="ISO-8859-5">Cyrillic (ISO)</option>
                    <option value="ISO-8859-2">Central European (ISO)</option>
                    <option value="ISO-8859-4">Baltic (ISO)</option>
                    <option value="ISO-8859-6">Arabic (ISO)</option>
                </select>
            </div>
        </div>
    </div>
    <div class="space10 zgfm-opt-divider-stl1"></div> 
    <div class="row">
         <div class="form-group">
            <div class="col-sm-4">
                 <label ><?php echo __('TEXT FONT', 'FRocket_admin'); ?></label>
            </div>
            <div class="col-sm-8">
                <select class="form-control zgfm-form-control" id="uifm_frm_email_usr_tmpl_pdf_font" name="uifm_frm_email_usr_tmpl_pdf_font">
                    <option value="1">Courier</option>
                    <option value="2">DejaVu Sans Mono</option>
                    <option value="3">Korean</option>
                    <option value="4">Japanese</option>
                    <option value="5">Chinese</option>
                </select>
                  
            </div> 
        </div>
    </div>
</div>