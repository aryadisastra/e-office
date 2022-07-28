<?php
$comp_model = new SharedController;
$page_element_id = "add-page-" . random_str();
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;
$show_header = $this->show_header;
$view_title = $this->view_title;
$redirect_to = $this->redirect_to;
?>
<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="add"  data-display-type="" data-page-url="<?php print_link($current_page); ?>">
    <?php
    if( $show_header == true ){
    ?>
    <div  class="p-3 mb-3">
        <div class="container">
            <div class="row ">
                <div class="col ">
                    <h4 class="record-title">BUAT SURAT KELUAR</h4>
                </div>
                <div class="col-md-1 comp-grid">
                    <a  class="btn btn-primary" href="<?php print_link("index_surat/index_keluar") ?>">
                        <i class="fa fa-times "></i>                                
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
    <div  class="">
        <div class="container">
            <div class="row ">
                <div class="col comp-grid">
                    <?php $this :: display_page_errors(); ?>
                    <div  class="bg-light p-3 animated fadeIn page-content">
                        <form id="index_surat-add-form" role="form" novalidate enctype="multipart/form-data" class="form page-form form-horizontal needs-validation" action="<?php print_link("index_surat/add?csrf_token=$csrf_token") ?>" method="post">
                            <div>
                                <div class="form-group ">
                                    <!-- <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="nomor_surat">Nomor Surat <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <input id="ctrl-nomor_surat"  value="<?php  echo $this->set_field_value('nomor_surat',""); ?>" type="text" placeholder="Nomor Surat"  required="" name="nomor_surat"  data-url="api/json/index_surat_nomor_surat_value_exist/" data-loading-msg="Checking availability ..." data-available-msg="Available" data-unavailable-msg="Not available" class="form-control  ctrl-check-duplicate" />
                                                    <div class="check-status"></div> 
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="form-group ">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="control-label" for="tanggal">Tanggal </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <input id="ctrl-tanggal" class="form-control" value="<?php  echo $this->set_field_value('tanggal',datetime_now()); ?>" type="datetime-local" name="tanggal" placeholder="Tanggal" data-enable-time="true" data-min-date="" data-max-date="" data-date-format="Y-m-d H:i:S" data-alt-format="Y-m-d H:i:s" data-inline="false" data-no-calendar="false" data-mode="single" /> 
                                                        <div class="input-group-append">
                                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="control-label" for="pengguna">Pengguna </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="">
                                                        <input id="ctrl-pengguna"  value="<?php  echo $this->set_field_value('pengguna',USER_NAME); ?>" type="text" placeholder="Pengguna"  readonly name="pengguna"  class="form-control " />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label class="control-label" for="kepada">Kepada <span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-8" >
                                                        <div class="" style="border-style: dashed; padding-left : 10px; border-color : #969590;">
                                                            <?php 
                                                                $kepada_options = $comp_model -> index_surat_kepada_tahap1_option_list();
                                                                if(!empty($kepada_options)){
                                                                    foreach($kepada_options as $option){
                                                                        $value = (!empty($option['value']) ? $option['value'] : null);
                                                                        $label = (!empty($option['label']) ? $option['label'] : $value);
                                                                        $selected = $this->set_field_selected('kepada',$value, "");
                                                                        ?>
                                                                        <div class="form-check">
                                                                            <input type="checkbox" name="kepada[]" id="<?php echo 'ctrl-kepada'.$value; ?>" value="<?php echo $value; ?>" class="form-check-input">
                                                                            <label class="form-check-label" for="<?php echo 'ctrl-kepada'.$value; ?>">   
                                                                                <?php echo strtoupper($label); ?>
                                                                            </label>
                                                                        </div>
                                                                <?php
                                                                    }
                                                                }
                                                                ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label class="control-label" for="tembusan">Tembusan </label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="" style="border-style: dashed; padding-left : 10px; border-color : #969590;">
                                                                <?php 
                                                                    $tembusan_options = $comp_model -> index_surat_kepada_tahap1_option_list();
                                                                    if(!empty($tembusan_options)){
                                                                        foreach($tembusan_options as $option){
                                                                            $value = (!empty($option['value']) ? $option['value'] : null);
                                                                            $label = (!empty($option['label']) ? $option['label'] : $value);
                                                                            $selected = $this->set_field_selected('tembusan',$value, "");
                                                                            ?>
                                                                                <div class="form-check">
                                                                                    <input type="checkbox" name="tembusan[]" id="<?php echo 'ctrl-tembusan'.$value; ?>" value="<?php echo $value; ?>" class="form-check-input">
                                                                                    <label class="form-check-label" for="<?php echo 'ctrl-tembusan'.$value; ?>">   
                                                                                        <?php echo strtoupper($label); ?>
                                                                                    </label>
                                                                                </div>
                                                                <?php
                                                                        }
                                                                    }
                                                                ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <div class="form-group ">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label class="control-label" for="disposisi">Isi Disposisi </label>
                                                    </div>
                                                    <div class="col-sm-8" style="column-count:2">
                                                        <div class="" style="border-style: dashed; padding-left : 10px; border-color : #969590; display:flex;flex-wrap:wrap; align-items: center;justify-content: left;text-align: left; width:100%; column-gap:70%" >
                                                                <?php 
                                                                    $disposisi_options = $comp_model -> getIsiDisposisi();
                                                                    if(!empty($disposisi_options)){
                                                                        foreach($disposisi_options as $option){
                                                                            $value = (!empty($option['isi_disposisi']) ? $option['isi_disposisi'] : null);
                                                                            $label = (!empty($option['isi_disposisi']) ? $option['isi_disposisi'] : $value);
                                                                            $selected = $this->set_field_selected('disposisi',$value, "");
                                                                            ?>
                                                                                <div class="form-check">
                                                                                    <input type="checkbox" name="disposisi[]" id="<?php echo 'ctrl-disposisi'.$value; ?>" value="<?php echo $value; ?>" class="form-check-input">
                                                                                    <label class="form-check-label" for="<?php echo 'ctrl-disposisi'.$value; ?>">   
                                                                                        <?php echo $label; ?>
                                                                                    </label>
                                                                                </div>
                                                                    <?php
                                                                        }
                                                                    } else {
                                                                        echo 'Silahkan Hubungi Operator Untuk Menambah Isi Disposisi';
                                                                    }
                                                                    ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->
                                            <div class="form-group ">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label class="control-label" for="subjek">Subjek <span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="">
                                                            <input id="ctrl-subjek"  value="<?php  echo $this->set_field_value('subjek',""); ?>" type="text" placeholder="Subjek"  required="" name="subjek"  class="form-control " />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group ">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <label class="control-label" for="keterangan">Catatan <span class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <div class="">
                                                                <textarea placeholder="Keterangan" id="ctrl-keterangan"  required="" rows="5" name="keterangan" class="form-control"><?php  echo $this->set_field_value('keterangan',""); ?></textarea>
                                                                <!--<div class="invalid-feedback animated bounceIn text-center">Please enter text</div>-->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group ">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <label class="control-label" for="lampiran">Lampiran </label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <div class="">
                                                                <div class="dropzone " input="#ctrl-lampiran" fieldname="lampiran"    data-multiple="true" dropmsg="Unggah Lampiran"    btntext="Browse" filesize="100" maximum="10">
                                                                    <input name="lampiran" id="ctrl-lampiran" class="dropzone-input form-control" value="<?php  echo $this->set_field_value('lampiran',""); ?>" type="text"  />
                                                                        <!--<div class="invalid-feedback animated bounceIn text-center">Please a choose file</div>-->
                                                                        <div class="dz-file-limit animated bounceIn text-center text-danger"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group ">
                                                        <div class="row">
                                                            <div class="col-sm-4">
                                                                <label class="control-label" for="sifat">Sifat <span class="text-danger">*</span></label>
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="">
                                                                    <?php 
                                                                    $sifat_options = $comp_model -> index_surat_sifat_option_list();
                                                                    if(!empty($sifat_options)){
                                                                    foreach($sifat_options as $option){
                                                                    $value = (!empty($option['value']) ? $option['value'] : null);
                                                                    $label = (!empty($option['label']) ? $option['label'] : $value);
                                                                    $checked = $this->set_field_checked('sifat', $value, "");
                                                                    ?>
                                                                    <label class="form-check form-check-inline">
                                                                        <input id="ctrl-sifat" class="form-check-input" <?php echo $checked; ?> value="<?php echo $value; ?>" type="radio"  name="sifat"   required="" />
                                                                            <span class="form-check-label"><?php echo $label; ?></span>
                                                                        </label>
                                                                        <?php
                                                                        }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-submit-btn-holder text-center mt-3">
                                                        <div class="form-ajax-status"></div>
                                                        <button class="btn btn-primary" type="submit">
                                                            Kirim
                                                            <i class="fa fa-send"></i>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
