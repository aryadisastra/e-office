<?php
$comp_model = new SharedController;
$page_element_id = "edit-page-" . random_str();
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;
$data = $this->view_data;
//$rec_id = $data['__tableprimarykey'];
$page_id = $this->route->page_id;
$show_header = $this->show_header;
$view_title = $this->view_title;
$redirect_to = $this->redirect_to;
?>
<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="edit"  data-display-type="" data-page-url="<?php print_link($current_page); ?>">
    <?php
    if( $show_header == true ){
    ?>
    <div  class="bg-light p-3 mb-3">
        <div class="container">
            <div class="row ">
                <div class="col ">
                    <h4 class="record-title">Edit  Index Surat</h4>
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
                <div class="col-md-7 comp-grid">
                    <?php $this :: display_page_errors(); ?>
                    <div  class="bg-light p-3 animated fadeIn page-content">
                        <form novalidate  id="" role="form" enctype="multipart/form-data"  class="form page-form form-horizontal needs-validation" action="<?php print_link("index_surat/edit/$page_id/?csrf_token=$csrf_token"); ?>" method="post">
                            <div>
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="nomor_surat">Nomor Surat <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <input id="ctrl-nomor_surat"  value="<?php  echo $data['nomor_surat']; ?>" type="text" placeholder="Nomor Surat"  required="" name="nomor_surat"  data-url="api/json/index_surat_nomor_surat_value_exist/" data-loading-msg="Checking availability ..." data-available-msg="Available" data-unavailable-msg="Not available" class="form-control  ctrl-check-duplicate" />
                                                    <div class="check-status"></div> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="control-label" for="tanggal">Tanggal </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <input id="ctrl-tanggal" class="form-control datepicker  datepicker" value="<?php  echo $data['tanggal']; ?>" type="datetime"  name="tanggal" placeholder="Tanggal" data-enable-time="true" data-min-date="" data-max-date="" data-date-format="Y-m-d H:i:S" data-alt-format="Y-m-d H:i:s" data-inline="false" data-no-calendar="false" data-mode="single" /> 
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
                                                        <input id="ctrl-pengguna"  value="<?php  echo $data['pengguna']; ?>" type="text" placeholder="Pengguna"  readonly name="pengguna"  class="form-control " />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label class="control-label" for="kepada">Kepada <span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="">
                                                            <select required=""  id="ctrl-kepada" name="kepada[]"  placeholder="Kepada" multiple data-max-items="1000"  class="selectize" >
                                                                <?php
                                                                $selected_options = explode(",", $data['kepada']);
                                                                foreach($selected_options as $option){
                                                                ?>
                                                                <option selected><?php echo $option; ?></option>
                                                                <?php
                                                                }
                                                                ?>
                                                                <?php
                                                                $rec = $data['kepada'];
                                                                $kepada_options = $comp_model -> index_surat_kepada_option_list();
                                                                if(!empty($kepada_options)){
                                                                foreach($kepada_options as $option){
                                                                $value = (!empty($option['value']) ? $option['value'] : null);
                                                                $label = (!empty($option['label']) ? $option['label'] : $value);
                                                                $selected = ( $value == $rec ? 'selected' : null );
                                                                ?>
                                                                <option 
                                                                    <?php echo $selected; ?> value="<?php echo $value; ?>"><?php echo $label; ?>
                                                                </option>
                                                                <?php
                                                                }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label class="control-label" for="tembusan">Tembusan </label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="">
                                                            <select  id="ctrl-tembusan" name="tembusan[]"  placeholder="Tembusan" multiple data-max-items="1000"  class="selectize" >
                                                                <?php
                                                                $selected_options = explode(",", $data['tembusan']);
                                                                foreach($selected_options as $option){
                                                                ?>
                                                                <option selected><?php echo $option; ?></option>
                                                                <?php
                                                                }
                                                                ?>
                                                                <?php
                                                                $rec = $data['tembusan'];
                                                                $tembusan_options = $comp_model -> index_surat_tembusan_option_list();
                                                                if(!empty($tembusan_options)){
                                                                foreach($tembusan_options as $option){
                                                                $value = (!empty($option['value']) ? $option['value'] : null);
                                                                $label = (!empty($option['label']) ? $option['label'] : $value);
                                                                $selected = ( $value == $rec ? 'selected' : null );
                                                                ?>
                                                                <option 
                                                                    <?php echo $selected; ?> value="<?php echo $value; ?>"><?php echo $label; ?>
                                                                </option>
                                                                <?php
                                                                }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label class="control-label" for="disposisi">Disposisi </label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="">
                                                            <select  id="ctrl-disposisi" name="disposisi[]"  placeholder="Disposisi" multiple data-max-items="1000"  class="selectize" >
                                                                <?php
                                                                $selected_options = explode(",", $data['disposisi']);
                                                                foreach($selected_options as $option){
                                                                ?>
                                                                <option selected><?php echo $option; ?></option>
                                                                <?php
                                                                }
                                                                ?>
                                                                <?php
                                                                $rec = $data['disposisi'];
                                                                $disposisi_options = $comp_model -> index_surat_disposisi_option_list();
                                                                if(!empty($disposisi_options)){
                                                                foreach($disposisi_options as $option){
                                                                $value = (!empty($option['value']) ? $option['value'] : null);
                                                                $label = (!empty($option['label']) ? $option['label'] : $value);
                                                                $selected = ( $value == $rec ? 'selected' : null );
                                                                ?>
                                                                <option 
                                                                    <?php echo $selected; ?> value="<?php echo $value; ?>"><?php echo $label; ?>
                                                                </option>
                                                                <?php
                                                                }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label class="control-label" for="subjek">Subjek <span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="">
                                                            <input id="ctrl-subjek"  value="<?php  echo $data['subjek']; ?>" type="text" placeholder="Subjek"  required="" name="subjek"  class="form-control " />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group ">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <label class="control-label" for="keterangan">Keterangan <span class="text-danger">*</span></label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <div class="">
                                                                <textarea placeholder="Keterangan" id="ctrl-keterangan"  required="" rows="5" name="keterangan" class="htmleditor form-control"><?php  echo $data['keterangan']; ?></textarea>
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
                                                                    <input name="lampiran" id="ctrl-lampiran" class="dropzone-input form-control" value="<?php  echo $data['lampiran']; ?>" type="text"  />
                                                                        <!--<div class="invalid-feedback animated bounceIn text-center">Please a choose file</div>-->
                                                                        <div class="dz-file-limit animated bounceIn text-center text-danger"></div>
                                                                    </div>
                                                                </div>
                                                                <?php Html :: uploaded_files_list($data['lampiran'], '#ctrl-lampiran'); ?>
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
                                                                    $rec = $data['sifat'];
                                                                    if(!empty($sifat_options)){
                                                                    foreach($sifat_options as $option){
                                                                    $value = (!empty($option['value']) ? $option['value'] : null);
                                                                    $label = (!empty($option['label']) ? $option['label'] : $value);
                                                                    $checked = ( $value == $rec ? 'checked' : null );
                                                                    ?>
                                                                    <label class="form-check form-check-inline option-btn">
                                                                        <input id="ctrl-sifat" class="form-check-input" <?php echo $checked ?> value="<?php echo $value; ?>" type="radio"  name="sifat"   required="" />
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
                                                    <div class="form-ajax-status"></div>
                                                    <div class="form-group text-center">
                                                        <button class="btn btn-primary" type="submit">
                                                            Perbarui
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
