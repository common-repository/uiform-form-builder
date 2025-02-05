<?php
if (!defined('ABSPATH')) {exit('No direct script access allowed');}
ob_start();
?>
<div class="rockfm-control-label">
    <label class="control-label">
        
        <?php 
            $req_icon_left='';
            $req_icon_right='';
            if(isset($validate['reqicon_st']) && intval($validate['reqicon_st'])===1){
                if(isset($validate['reqicon_pos']) && intval($validate['reqicon_pos'])===1){
                    $req_icon_right='<i class="glyphicon '.$validate['reqicon_img'].'"></i>';
                }else{
                    $req_icon_left='<i class="glyphicon '.$validate['reqicon_img'].'"></i>';
                }
            }
        ?>
        <span  
                class="rockfm-label"><?php 
        if(isset($help_block['show_st']) && intval($help_block['show_st'])===1){
        switch ($help_block['pos']) {
                case 2:
                    //tooltip
                    ?>
                    <span 
                        data-toggle="tooltip"
                        data-placement="top"
                        data-original-title="<?php 
                        if(isset($help_block['text'])){
                            echo urldecode($help_block['text']);
                        }
                        ?>"
                        data-field-option="rockfm-help-block"
                        class="rockfm-label-helpblock">
                        <span class="fa fa-question-circle"></span>
                    </span>
                    <?php
                    break;
                case 3:
                    //popup
                    ?>
                    <a role="button"
                        data-toggle="modal"
                        href="#modaltemplate_<?php echo $id;?>"
                        data-field-option="rockfm-help-block"
                        class="rockfm-label-helpblock">
                        <span class="fa fa-question-circle"></span>
                    </a>
      <!-- Modal -->
            <div  class="modal fade"  id="modaltemplate_<?php echo $id;?>">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" 
                        data-dismiss="modal" aria-hidden="true">
                            &times;
                        </button>
                        <h4 class="modal-title" id="myModalLabel">
                        <span class=" fa fa-question-circle"></span>
                        </h4>
                    </div>
                    <div class="modal-body">
                            <?php 
                        if(isset($help_block['text'])){
                           echo urldecode($help_block['text']);
                        }
                        ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" 
                        data-dismiss="modal"><?php echo __('Close','FRocket_admin'); ?>
                        </button>

                    </div>
                </div><!-- /.modal-content -->
            </div>
            </div><!-- /.modal --> 
                    <?php
                    break;

            } 
        }
            ?><?php echo $req_icon_left;?><?php echo $label['text']; ?><?php echo $req_icon_right;?></span>
        <span data-field-store="sublabel-text"
                data-field-option="rockfm-sublabel"
                class="rockfm-sublabel"><?php echo $sublabel['text']; ?></span>
    </label>

</div>

<?php
$tmp_label_html = ob_get_contents();
ob_end_clean();
?>
<?php ob_start();?>
<input class="rockfm-input-ratingstar"
       data-uifm-tabnum="<?php echo $tab_num;?>"
       data-uifm-txt-star1="<?php echo $input9['txt_star1'];?>"
       data-uifm-txt-star2="<?php echo $input9['txt_star2'];?>"
       data-uifm-txt-star3="<?php echo $input9['txt_star3'];?>"
       data-uifm-txt-star4="<?php echo $input9['txt_star4'];?>"
       data-uifm-txt-star5="<?php echo $input9['txt_star5'];?>"
       data-uifm-txt-norate="<?php echo $input9['txt_norate'];?>"
       name="uiform_fields[<?php echo $id;?>]"
                                     data-min="1" 
                                     data-max="5" 
                                     data-step="1">

<?php $tmp_label_inner_html = ob_get_contents();
ob_end_clean();?>
<?php
ob_start();
?>
<?php 
    if(isset($help_block['show_st']) && intval($help_block['show_st'])===1){
       switch ($help_block['pos']) {
            case 1:
                //top
                ?>
                <div class="rockfm-help-block"><?php echo urldecode($help_block['text']);?></div>
                <div class="rockfm-input-container">
                   <?php echo $tmp_label_inner_html;?>
                </div>
                <?php
                break;
            case 2:
                //tooltip
                ?>
                <div class="rockfm-input-container">
                   <?php echo $tmp_label_inner_html;?>
                </div>
                <?php
                break;
            case 3:
                //popup
                ?>
                <div class="rockfm-input-container">
                    <?php echo $tmp_label_inner_html;?>
                </div>
                <?php
                break;
            case 0:
            default:
                //bottom
                ?>
                <div class="rockfm-input-container">
                    <?php echo $tmp_label_inner_html;?>
                </div>
                <div class="rockfm-help-block"><?php echo urldecode($help_block['text']);?></div>
                <?php
                break;
        } 
    }else{
        ?>
                <div class="rockfm-input-container">
                   <?php echo $tmp_label_inner_html;?>
                </div>
        <?php
    }
    

?>
                        
<?php
$tmp_input_html = ob_get_contents();
ob_end_clean();
?>
<?php
ob_start();
?>
<div id="rockfm_<?php echo $id;?>"  
     data-idfield="<?php echo $id;?>"
     data-typefield="22" 
     class="rockfm-ratingstar rockfm-field 
     <?php if(isset($clogic) && intval($clogic['show_st'])===1){?>
     rockfm-clogic-fcond
     <?php } ?>
     "
     <?php if(isset($clogic) && intval($clogic['show_st'])===1&& intval($clogic['f_show'])===1){?>
      style="display:none;"
     <?php } ?>
     >
            <div class="rockfm-field-wrap ">
                <div class="rkfm-row">
                    <?php 
                    if(intval($txt_block['block_st'])===1){
                    ?>  
                        <?php
                        switch (intval($txt_block['block_pos'])) {
                                case 1:
                                    //top
                                    ?>
                                    <div class="rkfm-col-sm-12 rockfm-wrap-label">
                                        <?php echo $tmp_label_html;?>
                                    </div>
                                    <div class="rkfm-col-sm-12">
                                        <?php echo $tmp_input_html;?>
                                    </div>
                                    <?php
                                    break;
                                case 2:
                                    //right
                                    ?>
                                    <div class="rkfm-col-sm-10">
                                        <?php echo $tmp_input_html;?>
                                    </div>
                                    <div class="rkfm-col-sm-2 rockfm-wrap-label">
                                        <?php echo $tmp_label_html;?>
                                    </div>
                                    <?php
                                    break;
                                case 3:
                                    //bottom
                                    ?>
                                    <div class="rkfm-col-sm-12">
                                        <?php echo $tmp_input_html;?>
                                    </div>
                                    <div class="rkfm-col-sm-12 rockfm-wrap-label">
                                        <?php echo $tmp_label_html;?>
                                    </div>
                                    <?php
                                    break;
                                case 0:
                                default:
                                    //left
                                    ?>
                                    <div class="rkfm-col-sm-2 rockfm-wrap-label">
                                        <?php echo $tmp_label_html;?>
                                    </div>
                                    <div class="rkfm-col-sm-10">
                                        <?php echo $tmp_input_html;?>
                                    </div>
                                    <?php
                                    break;
                            }
                        ?>
                    <?php } else{ ?>
                        <div class="rkfm-col-sm-12">
                            <?php echo $tmp_input_html;?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
<?php
$cntACmp = ob_get_contents();
$cntACmp = str_replace("\n", '', $cntACmp);
$cntACmp = str_replace("\t", '', $cntACmp);
$cntACmp = str_replace("\r", '', $cntACmp);
$cntACmp = str_replace("//-->", ' ', $cntACmp);
$cntACmp = str_replace("//<!--", ' ', $cntACmp);
@$cntACmp = eregi_replace("[[:space:]]+", " ", $cntACmp);
ob_end_clean();
echo $cntACmp;
?>