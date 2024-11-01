<?php
/**
 * Frontend
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
if (!defined('ABSPATH')) {
    exit('No direct script access allowed');
}
if (class_exists('Uiform_Fb_Controller_Frontend')) {
    return;
}

/**
 * Controller Frontend class
 *
 * @category  PHP
 * @package   Rocket_form
 * @author    Softdiscover <info@softdiscover.com>
 * @copyright 2013 Softdiscover
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   Release: 1.00
 * @link      http://wordpress-form-builder.zigaform.com/
 */
class Uiform_Fb_Controller_Frontend extends Uiform_Base_Module {

    const VERSION = '1.2';

    private $formsmodel = "";
    private $model_formrecords = "";
    private $model_fields = "";
    private $wpdb = "";
    private $flag_submitted = 0;
    private $form_response = array();
    private $current_form_id=array();
    private $form_rec_msg_summ = '';
    
    protected $modules;

    const PREFIX = 'wprofmr_';

    /**
     * Constructor
     *
     * @mvc Controller
     */
    protected function __construct() {

        $this->formsmodel = self::$_models['formbuilder']['form'];
        $this->model_formrecords = self::$_models['formbuilder']['form_records'];
        $this->model_fields = self::$_models['formbuilder']['fields'];
        global $wpdb;
        $this->wpdb = $wpdb;
        //Shortcodes
        add_shortcode('uiform', array(&$this, 'get_form_shortcode'));
        add_shortcode('zigaform', array(&$this, 'get_form_shortcode'));
        // ajax for verify recaptcha
        add_action('wp_ajax_rocket_front_checkrecaptcha', array(&$this, 'ajax_check_recaptcha'));
        add_action('wp_ajax_nopriv_rocket_front_checkrecaptcha', array(&$this, 'ajax_check_recaptcha'));
        // ajax refresh captcha
        add_action('wp_ajax_rocket_front_refreshcaptcha', array(&$this, 'ajax_refresh_captcha'));
        add_action('wp_ajax_nopriv_rocket_front_refreshcaptcha', array(&$this, 'ajax_refresh_captcha'));
        // ajax refresh captcha
        add_action('wp_ajax_rocket_front_valcaptcha', array(&$this, 'ajax_validate_captcha'));
        add_action('wp_ajax_nopriv_rocket_front_valcaptcha', array(&$this, 'ajax_validate_captcha'));
        // submit ajax mode
        add_action('wp_ajax_rocket_front_submitajaxmode', array(&$this, 'ajax_submit_ajaxmode'));
        add_action('wp_ajax_nopriv_rocket_front_submitajaxmode', array(&$this, 'ajax_submit_ajaxmode'));
        //shortcodes
        add_shortcode('uifm_wrap', array(&$this, 'shortcode_uifm_recvar_wrap') );
        add_shortcode('uifm_recvar', array(&$this, 'shortcode_uifm_recvar') );
        add_shortcode('uifm_var', array(&$this, 'shortcode_uifm_form_var') );
        // shortcode show version info
        add_action( 'wp_head', array( &$this, 'shortcode_show_version'));
    }

   public function get_summaryRecord($id_rec){
        $form_id = (isset($_POST['form_id'])) ? Uiform_Form_Helper::sanitizeInput($_POST['form_id']) : '';
        
        $name_fields = $this->model_formrecords->getNameField($id_rec);
        $form_rec_data = $this->model_formrecords->getFormDataById($id_rec);
        
        $form_data=json_decode($form_rec_data->fmb_data, true);
        
        $name_fields_check = array();
        foreach ($name_fields as $value) {
            $name_fields_check[$value->fmf_uniqueid] = $value->fieldname;
        }
        $data_record = $this->model_formrecords->getRecordById($id_rec);
        $record_user = json_decode($data_record->fbh_data, true);
        $new_record_user = array();
        foreach ($record_user as $key => $value) {
            if (isset($name_fields_check[$key])) {
                $key = $name_fields_check[$key];
            }
                $new_record_user[] = array('field' => $value['label'], 'value' => $value['input']);
        }
        $data=array();
                
        $data['record_info'] = $new_record_user;
        $form_summary=self::render_template('formbuilder/views/frontend/form_summary.php',$data);
               
        return $form_summary;
       
    }
    
    public function shortcode_uifm_recvar_wrap($atts, $content = null) {
              
        $vars = shortcode_atts( array(
            'id'=>"",
            'atr1'=>'input'
        ), $atts );
        
        $result='';
        
        $output=$this->model_formrecords->getFieldOptRecord($vars['id'].'_'.$vars['atr1'],$this->flag_submitted);
        
                if($output!=''){
                    $result = do_shortcode($content);
                }else{
                   $result = '';
                }
        
         return $result;
        
                
    }
    
    public function shortcode_uifm_recvar($atts) {
        
        $vars = shortcode_atts( array(
            'id'=>"",
            'atr1'=>'input'
        ), $atts );
        
        $output=$this->model_formrecords->getFieldOptRecord($vars['id'].'_'.$vars['atr1'],$this->flag_submitted);
        
        if($output!=''){
            return $output;
        }else{
            return '';
        }
    }
    
    
    public function shortcode_uifm_form_var($atts) {
        
        $vars = shortcode_atts( array(
            'atr1'=>"0", //source 0=>fmb_data2; 1=>fmb_data
            'atr2'=>"",
            'atr3'=>"",
            'opt'=>"" //quick option
        ), $atts );
        $output='';
        
        $rec_id=$this->flag_submitted;
        $data=$this->model_formrecords->getFormDataById($rec_id);
        
        if(!empty($vars['opt'])){
             switch ((string)$vars['opt']) {
               case "rec_summ":
                     $output=$this->form_rec_msg_summ;
                    break;
                case "rec_url_fm":
                     $output= isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '';
                    break;
                case "form_name":
                    $output=$data->fmb_name;
                    break;
                case "rec_id":
                    $output=$rec_id;
                    break; 
                default:
                
            }
        }else{
            
        }
       
        //get data from form
        if($output!=''){
            return $output;
        }else{
            return '';
        }
    }
    
    public function ajax_submit_ajaxmode() {
        
        check_ajax_referer( 'zgfm_ajax_nonce', 'zgfm_security' );
        
        $resp = array();
        $resp=$this->process_form();
       
        if (isset($this->flag_submitted) && intval($this->flag_submitted) > 0) {
            $resp['success'] = (isset($resp['success']))?$resp['success']:0;
            $resp['show_message'] = (isset($resp['show_message'])) ? Uiform_Form_Helper::encodeHex(do_shortcode($resp['show_message'])) :
                    '<div class="rockfm-alert rockfm-alert-danger"><i class="fa fa-exclamation-triangle"></i> ' . __('Success! your form was submitted', 'frocket_front') . '</div>';
        } else {
            $resp['success'] = 0;
            $resp['show_message'] = '<div class="rockfm-alert rockfm-alert-danger"><i class="fa fa-exclamation-triangle"></i> ' . __('warning! Form was not submitted', 'frocket_front') . '</div>';
        }
        
        $resp['sm_redirect_st'] = $resp['sm_redirect_st'];
        $resp['sm_redirect_url'] = $resp['sm_redirect_url'];
        //return data to ajax callback
        header('Content-Type: text/html; charset=UTF-8');
        echo json_encode($resp);
        wp_die();
    }

    public function ajax_validate_captcha() {
        
        check_ajax_referer( 'zgfm_ajax_nonce', 'zgfm_security' );
        
        $rockfm_code = (isset($_POST['rockfm-code'])) ? Uiform_Form_Helper::sanitizeInput($_POST['rockfm-code']) : '';
        $rockfm_inpcode = (isset($_POST['rockfm-inpcode'])) ? Uiform_Form_Helper::sanitizeInput($_POST['rockfm-inpcode']) : '';
        $resp = array();
        $resp['code'] = $rockfm_code;
        $resp['inpcode'] = $rockfm_inpcode;

        if (!empty($rockfm_code)) {
            $captcha_key = 'Rocketform-' . $_SERVER['HTTP_HOST'];
            $captcha_resp = Uiform_Form_Helper::data_decrypt($rockfm_code, $captcha_key);
            $resp['resp'] = $captcha_resp;
            if ((string) $captcha_resp === (string) ($rockfm_inpcode)) {
                $resp['success'] = true;
            } else {
                $resp['success'] = false;
            }
        } else {
            $resp['success'] = false;
        }

        //return data to ajax callback
        header('Content-Type: text/html; charset=UTF-8');
        echo json_encode($resp);
        wp_die();
    }

    public function ajax_refresh_captcha() {
        
        check_ajax_referer( 'zgfm_ajax_nonce', 'zgfm_security' );
        
        $rkver = (isset($_POST['rkver'])) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['rkver'])) : 0;
        if ($rkver) {
            $rkver = Uiform_Form_Helper::base64url_decode(json_decode($rkver));
            $rkver_arr = json_decode($rkver, true);

            $length = 5;
            $charset = 'abcdefghijklmnpqrstuvwxyz123456789';
            $phrase = '';
            $chars = str_split($charset);

            for ($i = 0; $i < $length; $i++) {
                $phrase .= $chars[array_rand($chars)];
            }
            $captcha_data = array();

            if (isset($rkver_arr['ca_txt_gen'])) {
                $rkver_arr['ca_txt_gen'] = $phrase;
                $captcha_data = $rkver_arr;
            } else {
                $captcha_data['ca_txt_gen'] = $phrase;
            }
            $captcha_options = Uiform_Form_Helper::base64url_encode(json_encode($captcha_data));
            $resp = array();
            $resp['rkver'] = $captcha_options;

            /* generate check code */
            $captcha_key = 'Rocketform-' . $_SERVER['HTTP_HOST'];
            $resp['code'] = Uiform_Form_Helper::data_encrypt($phrase, $captcha_key);

            //return data to ajax callback
            header('Content-Type: text/html; charset=UTF-8');
            echo json_encode($resp);
            wp_die();
        }
    }

    public function ajax_check_recaptcha() {
        
        check_ajax_referer( 'zgfm_ajax_nonce', 'zgfm_security' );
        
        require_once( UIFORM_FORMS_DIR . '/modules/formbuilder/controllers/uiform-fb-controller-recaptcha.php');
        $inst_re = new Uiform_Fb_Controller_Recaptcha();
        $inst_re->front_verify_recaptcha();
        
        
    }

    public function process_form() {
        try {
                $form_id = ($_POST['_rockfm_form_id']) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['_rockfm_form_id'])) : 0;
                $this->current_form_id=$form_id;
                $form_fields = (isset($_POST['uiform_fields']) && $_POST['uiform_fields']) ? array_map(array('Uiform_Form_Helper', 'sanitizeRecursive'), $_POST['uiform_fields']) : array();
                $form_f_tmp = array();
                $form_f_rec_tmp = array();
                $attachment_status=0;
                $attachments = array();  // initialize attachment array 
                //get data from form
                $form_data = $this->formsmodel->getFormById_2($form_id);
                $form_data_onsubm = json_decode($form_data->fmb_data2, true);
                $form_data_name = $form_data->fmb_name;
                
                
                //process fields
                $message_fields = '';
                $form_errors=array();
                $mail_errors=false;
                if(!empty($form_fields)){
                    foreach ($form_fields as $key => $value) {
                        $tmp_field_name = $this->model_fields->getFieldNameByUniqueId($key, $form_id);
                        
                       if(!isset($tmp_field_name->type)){
                            throw new Exception('error $key:'.$key.' - $form_id:'.$form_id);
                        }
                        
                        
                        switch (intval($tmp_field_name->type)){
                            case 6:
                                /*textbox*/
                            case 28:    
                            case 29:    
                            case 30:    
                                $tmp_fdata= json_decode($tmp_field_name->data,true);
                                if(isset($tmp_fdata['validate']) && isset($tmp_fdata['validate']['typ_val']) && intval($tmp_fdata['validate']['typ_val'])===4){
                               // $mail_replyto=$value;  
                                }
                            break;
                        }

                    /*storing to main array*/
                        
                        switch(intval($tmp_field_name->type)){
                                case 9:
                                    /*checkbox*/
                                case 11:
                                    /*multiselect*/
                                    $tmp_fdata= json_decode($tmp_field_name->data,true);
                                    
                                    $tmp_options = array();
                                    $tmp_field_label=(!empty($tmp_fdata['label']['text']))?$tmp_fdata['label']['text']:$tmp_field_name->fieldname;
                                    $form_f_tmp[$key]['label']=$tmp_field_label;
                                    /*mail notification*/
                                    $message_fields.='</br>' . $tmp_field_label . ' : </br>';
                                    $message_fields.='<table cellspacing="0" cellpadding="0">';
                                    if (is_array($value)) {
                                        //for records
                                        $tmp_options_rec = array();
                                        foreach ($value as $key2 => $value2) {
                                            $tmp_options_row=array();
                                            $tmp_options_row['label']=isset($tmp_fdata['input2']['options'][$value2]['label'])?$tmp_fdata['input2']['options'][$value2]['label']:'';
                                            $tmp_options_rec[] = $tmp_options_row['label'];
                                        }
                                        $form_f_rec_tmp[$key] = implode('^,^', $tmp_options_rec);
                                        //end for records
                                       
                                        foreach ($value as $key2=>$value2) {
                                            $tmp_options_row=array();
                                            $tmp_options_row['label']=isset($tmp_fdata['input2']['options'][$value2]['label'])?$tmp_fdata['input2']['options'][$value2]['label']:'';
                                             
                                            if(isset($tmp_fdata['input2']['options'][$value2]) && $tmp_fdata['input2']['options'][$value2]){
                                            
                                            /*mail notification*/
                                            $message_fields.='<tr>';
                                                $message_fields.='<td width="20" align="center" valign="top">&bull;</td>';
                                                $message_fields.='<td   align="left" valign="top">' . $tmp_options_row['label'] ;
 
                                                $message_fields.='</td>';
                                                $message_fields.='</tr>';
                                            }

                                            $tmp_options[] = $tmp_options_row;
                                        }
                                    }
                                   
                                    /*saving data to field array*/
                                    $form_f_tmp[$key]['input'] = $tmp_options;
                                     /*mail notification*/
                                    $message_fields.='</table>';
                                    
                                    break;
                               case 8:
                                    /*radiobutton*/      
                               case 10:
                                    /*select*/
                                    
                                    $tmp_fdata= json_decode($tmp_field_name->data,true);
                                    
                                    $tmp_options = array();
                                    $tmp_field_label=(!empty($tmp_fdata['label']['text']))?$tmp_fdata['label']['text']:$tmp_field_name->fieldname;
                                    $form_f_tmp[$key]['label']=$tmp_field_label;
                                    /*mail notification*/
                                    $message_fields.='</br>';
                                    $message_fields.='<table cellspacing="0" cellpadding="0">';
                                    
                                    //foreach ($value as $key2=>$value2) {
                                        $tmp_options_row=array();
                                        $tmp_options_row['label']=isset($tmp_fdata['input2']['options'][$value]['label'])?$tmp_fdata['input2']['options'][$value]['label']:'';
                                        //for records
                                        $form_f_rec_tmp[$key] = $tmp_options_row['label'];
                                        
                                        if(isset($tmp_fdata['input2']['options'][$value])){
                                            
                                           /*mail notification*/
                                           $message_fields.='<tr>';
                                           $message_fields.='<td align="center" valign="top">' . $tmp_field_label.' - '.$tmp_options_row['label'] . '</td>';
                                            $message_fields.='<td width="20" align="center" valign="top"></td>';
                                            $message_fields.='<td   align="left" valign="top">';
                                             
                                            $message_fields.='</td>';
                                            $message_fields.='</tr>';
                                        }
                                        
                                        $tmp_options[] = $tmp_options_row;
                                    //}
                                    /*saving data to field array*/
                                    $form_f_tmp[$key]['input'] = $tmp_options;
                                     /*mail notification*/
                                    $message_fields.='</table>';
                                    
                                    break;
                                case 12;
                                    /*file input field*/
                                case 13;
                                    /*image upload*/ 
                                    $tmp_fdata= json_decode($tmp_field_name->data,true);
                                    
                                    $tmp_options = array();
                                    $tmp_field_label=(!empty($tmp_fdata['label']['text']))?$tmp_fdata['label']['text']:$tmp_field_name->fieldname;
                                    $form_f_tmp[$key]['label']=$tmp_field_label;
                                    
                                    $allowedext_default = array('aaaa','png','doc','docx','xls','xlsx','csv','txt','rtf','zip','mp3','wma','wmv','mpg','flv','avi','jpg','jpeg','png','gif','ods','rar','ppt','tif','wav','mov','psd','eps','sit','sitx','cdr','ai','mp4','m4a','bmp','pps','aif','pdf');
                                    $custom_allowedext=(!empty($tmp_fdata['input16']['extallowed']))?array_map('trim', explode(',',$tmp_fdata['input16']['extallowed'])):$allowedext_default;
                                    $custom_maxsize=(!empty($tmp_fdata['input16']['maxsize']))?floatval($tmp_fdata['input16']['maxsize']):5;
                                    $custom_attach_st=(isset($tmp_fdata['input16']['attach_st']))?intval($tmp_fdata['input16']['attach_st']):0;
                                    
                                        if (isset($_FILES['uiform_fields']['name'][$key])
                                                && !empty($_FILES['uiform_fields']['name'][$key])) {


                                            $fileSize = $_FILES['uiform_fields']['size'][$key];
                                            if (floatval($fileSize) > $custom_maxsize * 1024 * 1024) {
                                                $form_errors[]=__('Error! The file exceeds the allowed size of', 'frocket_front').' '.$custom_maxsize.' MB';
                                            }
                                           /*find attachment max size found*/
                                            $attachment_status=($attachment_status<$custom_attach_st)?$custom_attach_st:$attachment_status;
                                            
                                        $ext = strtolower(substr($_FILES['uiform_fields']['name'][$key], strrpos($_FILES['uiform_fields']['name'][$key], '.') + 1));
                                            if(!in_array($ext, $custom_allowedext)) {
                                            $form_errors[]=__('Error! Type of file is not allowed to upload', 'frocket_front');
                                                }
                                            if(empty($form_errors)){
                                            $upload_data = wp_upload_dir();  // look for this function in wordpress documentation at codex 
                                                $upload_dir = $upload_data['path'];
                                                $upload_dirurl = $upload_data['baseurl'];
                                                $upload_subdir = $upload_data['subdir'];
                                                $rename = "file_" . md5(uniqid($_FILES['uiform_fields']['name'][$key], true));

                                                $_FILES['uiform_fields']['name'][$key] = $rename . "." . strtolower($ext);

                                                $form_f_tmp[$key]['input'] = $upload_dirurl.$upload_subdir.'/'.$_FILES['uiform_fields']['name'][$key];
                                                $form_f_rec_tmp[$key] = $upload_dirurl.$upload_subdir.'/'.$_FILES['uiform_fields']['name'][$key];
                                                $form_fields[$key] = $upload_dirurl.$upload_subdir.'/'.$_FILES['uiform_fields']['name'][$key];

                                                //attachment
                                            
                                                if ($_FILES['uiform_fields']['error'][$key] == UPLOAD_ERR_OK) {

                                                    $tmp_name = $_FILES['uiform_fields']['tmp_name'][$key]; // Get temp name of uploaded file
                                                    $name = $_FILES['uiform_fields']['name'][$key];  // rename it to whatever
                                                    move_uploaded_file($tmp_name, "$upload_dir/$name"); // move file to new location 
                                                    if(intval($custom_attach_st)===1){
                                                    $attachments[] = "$upload_dir/$name";  //  push the new uploaded file in attachment array
                                                    }
                                                } 
                                                
                                                 /*mail notification*/
                                                $message_fields.='</br>';
                                                $message_fields.='<table cellspacing="0" cellpadding="0">';
                                                $message_fields.='<tr>';
                                                    $message_fields.='<td align="center" valign="top">' . $tmp_field_name->fieldname . '</td>';
                                                    $message_fields.='<td width="20" align="center" valign="top">:</td>';
                                                    $message_fields.='<td  align="left" valign="top"> ' . $form_f_tmp[$key]['input'] . '</td>';
                                                $message_fields.='</tr>';
                                                $message_fields.='</table>';
                                            }
                                           

                                        } else {
                                            unset($form_fields[$key]);
                                            $form_f_tmp[$key]['input']='';
                                            $form_f_rec_tmp[$key]='';
                                        }

                                    break;
                                    case 16:
                                    /*slider*/ 
                                    case 18:
                                    /*spinner*/    
                                   $tmp_fdata= json_decode($tmp_field_name->data,true);
                                    
                                    $tmp_options = array();
                                    $tmp_field_label=(!empty($tmp_fdata['label']['text']))?$tmp_fdata['label']['text']:$tmp_field_name->fieldname;
                                    $form_f_tmp[$key]['label']=$tmp_field_label;
                                    /*mail notification*/
                                   $message_fields.='</br>';
                                    $message_fields.='<table cellspacing="0" cellpadding="0">';
                                    
                                   // foreach ($value as $key2=>$value2) {
                                        $tmp_options_row=array();
                                        
                                           $tmp_options_row['qty']=  floatval($value);
                                           $tmp_options_row['label']=floatval($value);
                                           //for records
                                           $form_f_rec_tmp[$key] = $value;
                                           
                                           /*mail notification*/
                                           $message_fields.='<tr>';
                                           $message_fields.='<td align="center" valign="top">' . $tmp_field_label . '</td>';
                                            $message_fields.='<td width="20" align="center" valign="top"></td>';
                                            $message_fields.='<td   align="left" valign="top">';
                                                $message_fields.=' : '.$value;
                                            $message_fields.='</td>';
                                            $message_fields.='</tr>';
                                        
                                        
                                        $tmp_options[] = $tmp_options_row;
                                   // }
                                    /*saving data to field array*/
                                    $form_f_tmp[$key]['input'] = $tmp_options;
                                     /*mail notification*/
                                    $message_fields.='</table>';
                                   break;
                                    case 40:
                                    /*switch*/
                                    $tmp_fdata= json_decode($tmp_field_name->data,true);
                                    
                                    $tmp_options = array();
                                    $tmp_field_label=(!empty($tmp_fdata['label']['text']))?$tmp_fdata['label']['text']:$tmp_field_name->fieldname;
                                    $form_f_tmp[$key]['label']=$tmp_field_label;
                                    /*mail notification*/
                                    $message_fields.='</br>';
                                    $message_fields.='<table cellspacing="0" cellpadding="0">';
                                    
                                    //foreach ($value as $key2=>$value2) {
                                        $tmp_options_row=array();
                                        $tmp_options_row['label']=$value;
                                        
                                        //for records
                                        $form_f_rec_tmp[$key] = $value;
                                         
                                           /*mail notification*/
                                           $message_fields.='<tr>';
                                           $message_fields.='<td align="center" valign="top">' . $tmp_field_label.' - '.$value. '</td>';
                                            $message_fields.='<td width="20" align="center" valign="top"></td>';
                                            $message_fields.='<td  align="left" valign="top">';
                                             $message_fields.='</td>';
                                            $message_fields.='</tr>';
                                          
                                        $tmp_options[] = $tmp_options_row;
                                    //}
                                    /*saving data to field array*/
                                    $form_f_tmp[$key]['input'] = $tmp_options;
                                     /*mail notification*/
                                    $message_fields.='</table>';
                                    break;
                                    case 41;
                                    /*dyn checkbox*/
                                    case 42;
                                    /*dyn radiobtn*/
                                      $tmp_fdata= json_decode($tmp_field_name->data,true);
                                      $tmp_options = array();
                                      $tmp_field_label=(!empty($tmp_fdata['label']['text']))?$tmp_fdata['label']['text']:$tmp_field_name->fieldname;
                                        $form_f_tmp[$key]['label']=$tmp_field_label;
                                      /*mail notification*/
                                        $message_fields.='</br>' . $tmp_field_label . ' : </br>';
                                        $message_fields.='<table cellspacing="0" cellpadding="0">';
                                        
                                        //for records
                                        $tmp_options_rec = array();
                                        foreach ($value as $value2) {
                                            $tmp_options_rec[] = $value2;
                                        }
                                        $form_f_rec_tmp[$key] = implode('^,^', $tmp_options_rec);
                                        //end for records
                                        
                                        foreach ($value as $key2=>$value2) {
                                            $tmp_options_row=array();
                                            $tmp_options_row['label']=$tmp_fdata['input17']['options'][$key2]['label'];

                                            if($tmp_fdata['input17']['options'][$key2]){
                                             
                                            $tmp_options_row['qty']=  $value2;
                                            /*mail notification*/
                                            $message_fields.='<tr>';
                                                $message_fields.='<td width="20" align="center" valign="top">&bull;</td>';
                                                $message_fields.='<td   align="left" valign="top">' . $tmp_fdata['input17']['options'][$key2]['label'] ;
     
                                                $message_fields.='</td>';
                                                $message_fields.='</tr>';
                                            }

                                            $tmp_options[] = $tmp_options_row;
                                        }
                                        /*saving data to field array*/
                                        $form_f_tmp[$key]['input'] = $tmp_options;
                                        /*mail notification*/
                                        $message_fields.='</table>';
                                    break;
                                    default:
                                   
                                        $tmp_fdata= json_decode($tmp_field_name->data,true);
                                     $tmp_field_label=(!empty($tmp_fdata['label']['text']))?$tmp_fdata['label']['text']:$tmp_field_name->fieldname;
                                      $form_f_tmp[$key]['label']=$tmp_field_label;
                                        if (is_array($value)) {
                                            $tmp_options = array();
                                            foreach ($value as $value2) {
                                                $tmp_options[] = $value2;
                                            }
                                        $form_f_tmp[$key]['input'] = implode('^,^', $tmp_options);
                                        //for records
                                        $form_f_rec_tmp[$key] = implode('^,^', $tmp_options);
                                        
                                            /*mail notification*/
                                        $message_fields.='</br>' . $tmp_field_label . ' : </br>';
                                            $message_fields.='<table cellspacing="0" cellpadding="0">';
                                            foreach ($value as $value2) {
                                                $message_fields.='<tr>';
                                                $message_fields.='<td width="20" align="center" valign="top">&bull;</td>';
                                            $message_fields.='<td   align="left" valign="top">' . $value2 . '</td>';
                                                $message_fields.='</tr>';
                                            }
                                        $message_fields.='</table>';  
                                    }else{
                                         $form_f_tmp[$key]['input'] = $value;
                                         //for records
                                         $form_f_rec_tmp[$key] = $value;
                                    
                                                    /*mail notification*/
                                        $message_fields.='</br>';
                                                    $message_fields.='<table cellspacing="0" cellpadding="0">';
                                                        $message_fields.='<tr>';
                                                $message_fields.='<td align="center" valign="top">' . $tmp_field_label . '</td>';
                                                            $message_fields.='<td width="20" align="center" valign="top">:</td>';
                                                $message_fields.='<td   align="left" valign="top"> ' . $value . '</td>';
                                                        $message_fields.='</tr>';
                                                        $message_fields.='</table>';
                                        }
                                    
                        break;
                            }
                         
                    }
                }
               
                if(count($form_errors)>0){
                    $data=array();
                    $data['success']=0;
                    $data['form_errors']=count($form_errors);
                    $tmp_err_msg='<ul>';
                        foreach ($form_errors as $value_er) {
                        $tmp_err_msg.='<li>'.$value_er.'</li>';    
                        }
                        $tmp_err_msg.='</ul>';
                        $tmp_err_msg=Uiform_Form_Helper::assign_alert_container($tmp_err_msg,4);
                    $data['form_error_msg']=$tmp_err_msg;
                    $this->form_response=$data;
                    $data['form_error_msg']=Uiform_Form_Helper::encodeHex($data['form_error_msg']);
                    return $data;
                }

                $this->form_rec_msg_summ = $message_fields;
                //ending form process

                //save to form records
                $agent = isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : '';
                $referer = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '';

                
                $form_f_rec_tmp=$this->process_DataRecord($form_f_tmp,$form_f_rec_tmp);    
                
                $data = array();
                $data['fbh_data'] = json_encode($form_f_tmp);
                $data['fbh_data_rec'] = json_encode($form_f_rec_tmp);
                $data['created_ip'] = $_SERVER['REMOTE_ADDR'];
                $data['form_fmb_id'] = $form_id;
                $data['fbh_data_rec_xml'] = Uiform_Form_Helper::array2xml($form_f_rec_tmp);
                $data['fbh_user_agent'] = $agent;
                $data['fbh_page'] = $_SERVER['REQUEST_URI'];
                $data['fbh_referer'] = $referer;

                $this->wpdb->insert($this->model_formrecords->table, $data);
                $idActivate = $this->wpdb->insert_id;
                $json=array();
                $json['status'] = 'created';
                $json['id'] = $idActivate;

                $this->flag_submitted = $idActivate;

                
                //preparing mail
                $mail_from_email = (isset($form_data_onsubm['onsubm']['mail_from_email'])) ? $form_data_onsubm['onsubm']['mail_from_email'] : '';
                $mail_from_name = (isset($form_data_onsubm['onsubm']['mail_from_name'])) ? $form_data_onsubm['onsubm']['mail_from_name'] : '';
                
                //admin
                //mail template
                $mail_template_msg = (isset($form_data_onsubm['onsubm']['mail_template_msg'])) ? urldecode($form_data_onsubm['onsubm']['mail_template_msg']) : '';
                $mail_template_msg =do_shortcode($mail_template_msg);
                
                $email_recipient = (isset($form_data_onsubm['onsubm']['mail_recipient'])) ? $form_data_onsubm['onsubm']['mail_recipient'] : get_option('admin_email');
                $email_cc = (isset($form_data_onsubm['onsubm']['mail_cc'])) ? $form_data_onsubm['onsubm']['mail_cc'] : '';
                $email_bcc = (isset($form_data_onsubm['onsubm']['mail_bcc'])) ? $form_data_onsubm['onsubm']['mail_bcc'] : '';
                $mail_subject = (isset($form_data_onsubm['onsubm']['mail_subject'])) ? do_shortcode($form_data_onsubm['onsubm']['mail_subject']) : __('New form request', 'FRocket_front');
                
                $mail_usr_recipient = (isset($form_data_onsubm['onsubm']['mail_usr_recipient'])) ? $form_data_onsubm['onsubm']['mail_usr_recipient'] : '';
                
                $data_mail=array();
                $data_mail['from_mail']=$mail_from_email;
                $data_mail['from_name']=$mail_from_name;
                $data_mail['message']=$mail_template_msg;
                $data_mail['subject']=$mail_subject;
                $data_mail['attachments']=$attachments;
                $data_mail['to']=$email_recipient;
                $data_mail['cc']=array_map('trim', explode(',',$email_cc));
                $data_mail['bcc']=array_map('trim', explode(',',$email_bcc));
                $data_mail['mail_replyto']=$this->model_formrecords->getFieldOptRecord($mail_usr_recipient.'_input',$idActivate);
                $mail_errors=$this->process_mail($data_mail);
                
                //customer 
                //mail template
                $mail_usr_st = (isset($form_data_onsubm['onsubm']['mail_usr_st'])) ? $form_data_onsubm['onsubm']['mail_usr_st'] : "0";
                if(intval($mail_usr_st)===1){
                    $mail_template_msg = (isset($form_data_onsubm['onsubm']['mail_usr_template_msg'])) ? urldecode($form_data_onsubm['onsubm']['mail_usr_template_msg']) : '';
                    $mail_template_msg =do_shortcode($mail_template_msg);
                    $mail_usr_cc = (isset($form_data_onsubm['onsubm']['mail_usr_cc'])) ? $form_data_onsubm['onsubm']['mail_usr_cc'] : '';
                    $mail_usr_bcc = (isset($form_data_onsubm['onsubm']['mail_usr_bcc'])) ? $form_data_onsubm['onsubm']['mail_usr_bcc'] : '';
                    $mail_usr_subject = (isset($form_data_onsubm['onsubm']['mail_usr_subject'])) ? do_shortcode($form_data_onsubm['onsubm']['mail_usr_subject']) : __('New form request', 'FRocket_front');
                
                    $mail_usr_pdf_st = (isset($form_data_onsubm['onsubm']['mail_usr_pdf_st'])) ? $form_data_onsubm['onsubm']['mail_usr_pdf_st'] : "0";
                    if (intval($mail_usr_pdf_st) === 1) {
                        
                        $data_mail=array();
                        $mail_template_msg_pdf = (isset($form_data_onsubm['onsubm']['mail_usr_pdf_template_msg'])) ? urldecode($form_data_onsubm['onsubm']['mail_usr_pdf_template_msg']) : '';
                        $mail_template_msg_pdf =do_shortcode($mail_template_msg_pdf);
                        $data_mail['mail_usr_pdf_template_msg']=$mail_template_msg_pdf;
                        $mail_pdf_fn = (isset($form_data_onsubm['onsubm']['mail_usr_pdf_fn'])) ? urldecode($form_data_onsubm['onsubm']['mail_usr_pdf_fn']) : '';
                        $mail_pdf_fn =do_shortcode($mail_pdf_fn);
                        $data_mail['mail_usr_pdf_fn']=$mail_pdf_fn;
                        $data_mail['rec_id']=$idActivate;
                        //$mail_pdf_font = (isset($form_data_onsubm['onsubm']['mail_usr_pdf_font'])) ? urldecode($form_data_onsubm['onsubm']['mail_usr_pdf_font']) : '';
                        //$data_mail['mail_usr_pdf_font']=$mail_pdf_font;
                        //$data_mail['mail_usr_pdf_charset']=(isset($form_data_onsubm['onsubm']['mail_usr_pdf_charset'])) ? $form_data_onsubm['onsubm']['mail_usr_pdf_charset'] : '';
                        $attachments[] = $this->process_custom_pdf($data_mail);
                    }
                    
                    
                    $data_mail=array();
                    $data_mail['from_mail']=$mail_from_email;
                    $data_mail['from_name']=$mail_from_name;
                    $data_mail['message']=$mail_template_msg;
                    $data_mail['subject']=do_shortcode($mail_usr_subject);
                    $data_mail['attachments']=$attachments;
                    $data_mail['attachement_status']=$attachment_status;
                    $data_mail['to']=$this->model_formrecords->getFieldOptRecord($mail_usr_recipient.'_input',$idActivate);
                    $data_mail['cc']=array_map('trim', explode(',',$mail_usr_cc));
                    $data_mail['bcc']=array_map('trim', explode(',',$mail_usr_bcc));
                    $data_mail['mail_replyto']='';
                    $mail_errors=$this->process_mail($data_mail);
                }
                
               
                
                
                 //success message
               
                $tmp_sm_successtext = (isset($form_data_onsubm['onsubm']['sm_successtext'])) ? urldecode($form_data_onsubm['onsubm']['sm_successtext']) : '';
                $tmp_sm_successtext =do_shortcode($tmp_sm_successtext);
                
               //url redirection
                $tmp_sm_redirect_st = (isset($form_data_onsubm['onsubm']['sm_redirect_st'])) ? $form_data_onsubm['onsubm']['sm_redirect_st'] : '0';
                $tmp_sm_redirect_url = (isset($form_data_onsubm['onsubm']['sm_redirect_url'])) ? urldecode($form_data_onsubm['onsubm']['sm_redirect_url']) : '';
                 
                $data=array();
                $data['success']=1;
                $data['show_message']=$tmp_sm_successtext;
                $data['sm_redirect_st']=$tmp_sm_redirect_st;
                $data['sm_redirect_url']=$tmp_sm_redirect_url;
                $data['form_errors']=0;
                $data['form_id']=$form_id;
                $data['mail_error']=($mail_errors)?1:0;
                $data['fbh_id']=$idActivate;
                $this->form_response=$data;
                return $data;
        } catch (Exception $exception) {
            $data=array();
            $data['success']=0;
            $data['form_errors']=count($form_errors);
            $data['error_debug']=__METHOD__ . ' error: ' . $exception->getMessage();
            $data['mail_error']=($mail_errors)?1:0;
            $this->form_response=$data;
            return $data;
        }
        
    }
    
    private function process_mail($data) {
        $mail_errors=false;
         
                /*getting admin mail*/
                $data['from_name']  = !empty($data['from_name']) ? $data['from_name'] : wp_specialchars_decode( get_option('blogname'), ENT_QUOTES );
                
                $headers = array();
                $message_format='html';
                $content_type = $message_format == "html" ? "text/html" : "text/plain";
                $headers[] = "MIME-Version: 1.0";
                $headers[] = "Content-type: {$content_type}";
                $headers[] = "charset=" . get_option('blog_charset');
                $headers[] = "From: \"{$data['from_name']}\" <{$data['from_mail']}>";
                if (!empty($data['mail_replyto']) 
                        && preg_match('/^[a-zA-Z0-9._+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/',$data['mail_replyto'])){
                        $mail_replyto_name=substr($data['mail_replyto'], 0, strrpos($data['mail_replyto'], '@'));
                        $headers[] = "Reply-To: \"{$mail_replyto_name}\" <{$data['mail_replyto']}>";
                        $data['subject'].=" - ".$data['mail_replyto'];
                }
                //cc
                if (!empty($data['cc'])) {
                    if(is_array($data['cc'])){
                      foreach ($data['cc'] as $value) {
                          if (preg_match('/^[a-zA-Z0-9._+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/',$value)){
                              $headers[] = "Cc: {$value}";
                            }
                        }
                    }
                }
                
                //bcc
                if (!empty($data['bcc'])) {
                    if(is_array($data['bcc'])){
                      foreach ($data['bcc'] as $value) {
                          if (preg_match('/^[a-zA-Z0-9._+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/',$value)){
                              $headers[] = "Bcc: {$value}";
                            }
                        }
                    }
                }
                 
                $to = $data['to'];
               
                if (preg_match('/^[a-zA-Z0-9._+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/',$to)){
                   if(!empty($data['attachments'])){
                    $mail_errors=wp_mail($to, $data['subject'], $data['message'], $headers, $data['attachments']);
                    }else{
                        $mail_errors=wp_mail($to, $data['subject'], $data['message'], $headers);
                    }


                    //pending option to delete attachment
                    if (false && !empty($data['attachments'])) {
                        foreach ($data['attachments'] as $attachment) {
                            @unlink($attachment); // delete files after sending them
                        }
                    }            
                }else{
                   $mail_errors=true; 
                }
                
        
        return $mail_errors;
    }
    
    private function process_DataRecord($data1,$data2) {
        
        $data3=array();
        
        if(!empty($data1)){
            foreach ($data1 as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                    if(is_array($value2)){
                        //index
                        $temp_input=array();
                        $temp_cost=array();
                        $temp_qty=array();
                        
                        foreach ($value2 as $key3 => $value3) {
                            //values
                            foreach ($value3 as $key4 => $value4) {
                                switch ($key4) {
                                        case 'label':
                                           $temp_input[]=$value4;
                                            break;
                                        case 'cost':
                                            $temp_cost[]=$value4;
                                            break;
                                        case 'qty':
                                            $temp_qty[]=$value4;
                                            break;
                                        default:
                                            
                                    }
                                $data3[$key.'_'.$key2.'_'.$key3.'_'.$key4]=$value4;
                                
                            }
                        }
                        
                        if(!empty($temp_input)){
                            $data3[$key.'_input']=implode('^,^', $temp_input);
                        }
                        if(!empty($temp_cost)){
                            $data3[$key.'_cost']=implode('^,^', $temp_cost);
                        }
                        if(!empty($temp_qty)){
                            $data3[$key.'_qty']=implode('^,^', $temp_qty);
                        }
                        
                        
                    }else{
                        $data3[$key.'_'.$key2]=$value2;
                    }
                }
                }
        }
        $data3=array_merge($data3,$data2);
                 
        return $data3;
            }
             
     private function get_enqueue_files($id_form) {
        
        //iframeResizer
        wp_enqueue_script('rockfm-iframeResizer', UIFORM_FORMS_URL . '/assets/frontend/js/iframe/iframeResizer.contentWindow.min.js');
       // wp_enqueue_script('jquery-core'); 
       // wp_enqueue_script('jquery-ui-core'); 
        
        //load resources
        $this->load_form_resources($id_form);
        
        $result = array();
        $result['scripts'] = array();
        $result['styles'] = array();
                
        // Print all loaded Scripts
        global $wp_scripts;
        $result['scripts']['base_url']=$wp_scripts->base_url;
        $result['scripts']['content_url']=$wp_scripts->content_url;
        
        
         $result['files'][]='<script type="text/javascript" src="'.$result['scripts']['base_url'].$wp_scripts->registered['jquery-core']->src.'"></script>'; 
         $result['files'][]='<script type="text/javascript" src="'.$result['scripts']['base_url'].$wp_scripts->registered['jquery-ui-core']->src.'"></script>';   
        foreach ($wp_scripts->queue as $script) :
            
            if(!Uiform_Form_Helper::isValidUrl_structure($wp_scripts->registered[$script]->src) && strpos($wp_scripts->registered[$script]->src, "wp-includes/js/jquery") !== false){
               $result['files'][]='<script type="text/javascript" src="'.$result['scripts']['base_url'].$wp_scripts->registered[$script]->src.'"></script>'; 
            }elseif(Uiform_Form_Helper::isValidUrl_structure($wp_scripts->registered[$script]->src) ){
               $result['files'][]='<script type="text/javascript" src="'.$wp_scripts->registered[$script]->src.'"></script>';  
            }
             
        
        endforeach;

        // Print all loaded Styles (CSS)
        global $wp_styles;
        $result['styles']['base_url']=$wp_styles->base_url;
        $result['styles']['content_url']=$wp_styles->content_url;
        foreach ($wp_styles->queue as $style) :
           $result['files'][]='<link href="'.$wp_styles->registered[$style]->src.'" rel="stylesheet">'; 
        endforeach;
       // var_dump($result);
       //         die();
        return $result;
    }
    
    public function get_form_iframe($id_form) {
        
        
        $rdata= $this->formsmodel->getAvailableFormById($id_form);
        if(empty($rdata)){
            return '';
        }
        
        $shortcode_string = stripslashes($rdata->fmb_html);
        
        $data = array();
       
                
        $content = '';
        $content.='<script type="text/javascript">';
        $content.='var rockfm_vars = rockfm_vars || {};';
                
        $content.='rockfm_vars.url_site = "'.site_url().'";';
        $content.='rockfm_vars.ajax_nonce = "'.wp_create_nonce('zgfm_ajax_nonce').'";';
        $content.='rockfm_vars.ajaxurl = "'.admin_url('admin-ajax.php').'";';
        $content.='rockfm_vars.imagesurl =  "'.UIFORM_FORMS_URL.'/assets/frontend/images";';
        $content.="rockfm_vars._uifmvar={};";
        
        $content.="rockfm_vars._uifmvar['fm_onload_scroll']=1;";
        
         $form_data_onsubm = json_decode($rdata->fmb_data2, true);
         $onload_scroll = (isset($form_data_onsubm['main']['onload_scroll'])) ? $form_data_onsubm['main']['onload_scroll'] : '0';
        if (intval($onload_scroll) === 1) {
             $content.="rockfm_vars._uifmvar['fm_onload_scroll']='1';";
        } 
        
         $preload_noconflict = (isset($form_data_onsubm['main']['preload_noconflict'])) ? $form_data_onsubm['main']['preload_noconflict'] : '0';    
        if (intval($preload_noconflict) === 1) {  
            $content.="rockfm_vars._uifmvar['fm_preload_noconflict']='1';";
        } 
                
        $content.="rockfm_vars._uifmvar['fm_loadmode']='iframe';";        
            
        $content.='</script>';
                
         $data['script_head'] = $content;        
        $data['form_id'] = $id_form;
        //get enqueue files  
        $data['head_files'] = $this->get_enqueue_files($id_form); 
        $data['form_html'] = do_shortcode($shortcode_string);
        $data['imagesurl'] = UIFORM_FORMS_URL . "/assets/frontend/images";
        $output='';
        $output = self::render_template('formbuilder/views/frontend/get_form_iframe.php',$data, 'always');
        
        return $output;
    }
    
    public function get_form_shortcode($attributes, $content = null) {
        
        //buffer 1   
        ob_start();    
          
        
        extract(shortcode_atts(array(
                    'id' => 1,
                    'ajax' => false,
                    'lmode'=>0,
                        ), $attributes));
        
        switch (intval($lmode)) { 
                        case 1:
                            /*iframe*/
                            $tmp_vars=array();
                            $tmp_vars['base_url']=UIFORM_FORMS_URL.'/';
                            $tmp_vars['url_form']=site_url().'/?uifm_fbuilder_api_handler&action=uifm_fb_api_handler&uifm_action=1&uifm_mode=lmode&id='.$id;
                        $output=self::render_template('formbuilder/views/frontend/get_code_iframe.php',$tmp_vars, 'always');
                            break;
                        default:
                            /*normal shortcode*/
        $shortcode_string = "";

        $data_form = $this->formsmodel->getAvailableFormById($id);
        if (empty($data_form)) {
            return;
        }
        $shortcode_string = stripslashes($data_form->fmb_html);
        //load resources
        $this->load_form_resources($id);
                           //buffer 2
        ob_start();
        // check for external shortcodes
        $shortcode_string = do_shortcode($shortcode_string);
        //adding alert message
        if (isset($_POST['_rockfm_type_submit'])
                && absint($_POST['_rockfm_type_submit']) === 0
                && absint($_POST['_rockfm_form_id']) === intval($id)
        ) {

            if(isset($this->form_response['success']) 
               && intval($this->form_response['success'])===1
               && isset($this->flag_submitted) && intval($this->flag_submitted) > 0     
                    ){
                echo (isset($_POST['_rockfm_onsubm_smsg'])) ? Uiform_Form_Helper::base64url_decode(Uiform_Form_Helper::sanitizeInput_html($_POST['_rockfm_onsubm_smsg'])) : __('Success! your form was submitted', 'frocket_front');
            }else{
                if (isset($this->form_response['form_errors']) && intval($this->form_response['form_errors']) > 0) {
                echo '<div class="rockfm-alert-container uiform-wrap"><div class="rockfm-alert-inner" >'.$this->form_response['form_error_msg'].'</div></div>';

            } else {
                    echo Uiform_Form_Helper::assign_alert_container(__('warning! Form was not submitted', 'frocket_front'),3);
                }
            }



        }
        if (!file_exists(UIFORM_FORMS_DIR . '/assets/frontend/css/rockfm_form' . $id . '.css')) {
            //buffer 3
            ob_start();
            ?>
            <style type="text/css">
            <?php echo $data_form->fmb_html_css; ?>
            </style> 
            <?php
            $css_string = ob_get_clean();
             //end buffer 3

            echo $css_string;
        }
        echo $shortcode_string;
         //end buffer 2
        $output = ob_get_clean();
        }
        
        return $output;
    }
    
    
    
    public function load_form_resources($id){
        
        //get form data
        $form_data = $this->formsmodel->getFormById_2($id);
        $form_data_onsubm = json_decode($form_data->fmb_data2, true);
        $onload_scroll = (isset($form_data_onsubm['main']['onload_scroll'])) ? $form_data_onsubm['main']['onload_scroll'] : '0';
        $preload_noconflict = (isset($form_data_onsubm['main']['preload_noconflict'])) ? $form_data_onsubm['main']['preload_noconflict'] : '0';
        
        
        
        wp_register_style(self::PREFIX . 'rockfm_global', UIFORM_FORMS_URL . '/assets/frontend/css/css.css', array(), UIFORM_VERSION, 'all'
        );

        /* load css */
        //loas ui
        wp_enqueue_style('jquery-ui');
        
        //bootstrap
        if (intval($preload_noconflict) === 1) {
           wp_enqueue_style('rockfm-bootstrap', UIFORM_FORMS_URL . '/assets/common/css/bootstrap-widget.css');
           wp_enqueue_style('rockfm-bootstrap-theme', UIFORM_FORMS_URL . '/assets/common/css/bootstrap-theme-widget.css');
           wp_enqueue_style('rockfm-bootstrap-def', UIFORM_FORMS_URL . '/assets/common/css/defbootstrap.css');     
        }else{
           wp_enqueue_style('rockfm-bootstrap', UIFORM_FORMS_URL . '/assets/common/css/bootstrap.css');
           wp_enqueue_style('rockfm-bootstrap-theme', UIFORM_FORMS_URL . '/assets/common/css/bootstrap-theme.css'); 
        }
        
        
        wp_enqueue_style('rockfm-fontawesome', UIFORM_FORMS_URL . '/assets/common/css/font-awesome.min.css');
        //jasny bootstrap
        wp_enqueue_style('rockfm-jasny-bootstrap', UIFORM_FORMS_URL . '/assets/common/js/bjasny/jasny-bootstrap.css');
        // bootstrap slider
        wp_enqueue_style('rockfm-bootstrap-slider', UIFORM_FORMS_URL . '/assets/backend/js/bslider/4.12.1/bootstrap-slider.min.css');
        // bootstrap touchspin
        wp_enqueue_style('rockfm-bootstrap-touchspin', UIFORM_FORMS_URL . '/assets/backend/js/btouchspin/jquery.bootstrap-touchspin.css');
        // bootstrap datetimepicker
        wp_enqueue_style('rockfm-bootstrap-datetimepicker', UIFORM_FORMS_URL . '/assets/backend/js/bdatetime/bootstrap-datetimepicker.css');
        //color picker
        wp_enqueue_style('rockfm-bootstrap-colorpicker', UIFORM_FORMS_URL . '/assets/backend/js/colorpicker/bootstrap-colorpicker.css');
        // star rating
        wp_enqueue_style('rockfm-star-rating', UIFORM_FORMS_URL . '/assets/backend/js/bratestar/star-rating.css');
        
        // bootstrap switch
        wp_enqueue_style('rockfm-bootstrap-switch', UIFORM_FORMS_URL . '/assets/backend/js/bswitch/bootstrap-switch.css');
         
        //global
        wp_enqueue_style(self::PREFIX . 'rockfm_global');

        if (file_exists(UIFORM_FORMS_DIR . '/assets/frontend/css/rockfm_form' . $id . '.css')) {
            wp_register_style(self::PREFIX . 'rockfm_form' . $id, UIFORM_FORMS_URL . '/assets/frontend/css/rockfm_form' . $id . '.css?'. date("Ymdgis"), array(), UIFORM_VERSION, 'all'
            );
            wp_enqueue_style(self::PREFIX . 'rockfm_form' . $id);
        }

        /* load js */
        wp_register_script(
                self::PREFIX . 'rockfm_js_global', UIFORM_FORMS_URL . '/assets/frontend/js/js.js',array(),UIFORM_VERSION, true
        );

        //load jquery
        wp_enqueue_script('jquery');

        // load jquery ui
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-widget');
        wp_enqueue_script('jquery-ui-mouse');
        wp_enqueue_script('jquery-ui-resizable');
        wp_enqueue_script('jquery-ui-position');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_script('jquery-ui-droppable');
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script('jquery-ui-autocomplete');
        wp_enqueue_script('jquery-ui-menu');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('jquery-ui-slider');
        wp_enqueue_script('jquery-ui-spinner');
        wp_enqueue_script('jquery-ui-button');
        wp_enqueue_script('jquery-ui-tooltip');

        //bootstrap
        wp_enqueue_script('rockfm-bootstrap', UIFORM_FORMS_URL . '/assets/common/js/bootstrap/3.2.0/bootstrap.min.js');
        //jasny bootstrap
        wp_enqueue_script('rockfm-jasny-bootstrap', UIFORM_FORMS_URL . '/assets/common/js/bjasny/jasny-bootstrap.js', array('jquery', 'rockfm-bootstrap'), '1.0', true);
        //bootstrap slider
        wp_enqueue_script('rockfm-bootstrap-slider', UIFORM_FORMS_URL . '/assets/backend/js/bslider/4.12.1/bootstrap-slider.min.js', array('jquery', 'rockfm-bootstrap'), '1.0', true);
        //bootstrap touchspin
        wp_enqueue_script('rockfm-bootstrap-touchspin', UIFORM_FORMS_URL . '/assets/backend/js/btouchspin/jquery.bootstrap-touchspin.js', array('jquery', 'rockfm-bootstrap'), '1.0', true);
        //bootstrap datetimepicker
        wp_enqueue_script('rockfm-bootstrap-dtpicker-locales', UIFORM_FORMS_URL . '/assets/backend/js/bdatetime/moment-with-locales.js', array('jquery', 'rockfm-bootstrap'), '1.0', true);
        wp_enqueue_script('rockfm-bootstrap-datetimepicker', UIFORM_FORMS_URL . '/assets/backend/js/bdatetime/bootstrap-datetimepicker.js', array('jquery', 'rockfm-bootstrap'), '1.0', true);
        //star rating
        wp_enqueue_script('rockfm-star-rating', UIFORM_FORMS_URL . '/assets/backend/js/bratestar/star-rating.js', array('jquery', 'rockfm-bootstrap'), '1.0', true);
        //color picker
        wp_enqueue_script('rockfm-bootstrap-colorpicker', UIFORM_FORMS_URL . '/assets/backend/js/colorpicker/bootstrap-colorpicker.min.js', array('jquery', 'rockfm-bootstrap'), '1.0', true);
        //bootstrap switch
        wp_enqueue_script('rockfm-bootstrap-switch', UIFORM_FORMS_URL . '/assets/backend/js/bswitch/bootstrap-switch.js', array('jquery', 'rockfm-bootstrap'), '1.0', true);
        //form
        wp_enqueue_script('rockfm-jform', UIFORM_FORMS_URL . '/assets/frontend/js/jquery.form.js');
        //load rocket form
        wp_enqueue_script(self::PREFIX . 'rockfm_js_global');
        
        //load form variables
        $form_variables=array();
        $form_variables['url_site']=site_url();
        $form_variables['ajax_nonce']=wp_create_nonce('zgfm_ajax_nonce');
        $form_variables['ajaxurl']=admin_url('admin-ajax.php');
        $form_variables['imagesurl']=UIFORM_FORMS_URL . "/assets/frontend/images";
        
        if (intval($onload_scroll) === 1) {
            $form_variables['_uifmvar']['fm_onload_scroll']="1";
        }else{
            $form_variables['_uifmvar']['fm_onload_scroll']="0";
        }
        wp_localize_script(self::PREFIX . 'rockfm_js_global', 'rockfm_vars', $form_variables);
    }
    
    public function shortcode_show_version(){
          $output='<noscript>';
          $output.='Powered by <a href="http://zigaform.com/?uifm_v='.UIFORM_VERSION.'&uifm_medium=wpzf" title="Wordpress Form Builder" >ZigaForm version '.UIFORM_VERSION.'</a>';
          $output.='</noscript>';
          echo $output;
    }
    
    /**
     * Register callbacks for actions and filters
     *
     * @mvc Controller
     */
    public function register_hook_callbacks() {
        
    }

    /**
     * Initializes variables
     *
     * @mvc Controller
     */
    public function init() {

        try {
            //$instance_example = new WPPS_Instance_Class( 'Instance example', '42' );
            //add_notice('ba');
        } catch (Exception $exception) {
            add_notice(__METHOD__ . ' error: ' . $exception->getMessage(), 'error');
        }
    }

    /*
     * Instance methods
     */

    /**
     * Prepares sites to use the plugin during single or network-wide activation
     *
     * @mvc Controller
     *
     * @param bool $network_wide
     */
    public function activate($network_wide) {

        return true;
    }

    /**
     * Rolls back activation procedures when de-activating the plugin
     *
     * @mvc Controller
     */
    public function deactivate() {
        return true;
    }

    /**
     * Checks if the plugin was recently updated and upgrades if necessary
     *
     * @mvc Controller
     *
     * @param string $db_version
     */
    public function upgrade($db_version = 0) {
        return true;
    }

    /**
     * Checks that the object is in a correct state
     *
     * @mvc Model
     *
     * @param string $property An individual property to check, or 'all' to check all of them
     * @return bool
     */
    protected function is_valid($property = 'all') {
        return true;
    }

}
?>
