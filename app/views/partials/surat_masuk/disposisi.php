<?php
$comp_model = new SharedController;
$page_element_id = "add-page-" . random_str();
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;
$show_header = $this->show_header;
$view_title = $this->view_title;
$redirect_to = $this->redirect_to;
$nomor = $_GET['id'];
$sumber = $_GET['sumber'];
?>

<style>
    #canvasDiv{
        position: relative;
        border: 2px dashed grey;
        height:100px;
    }
</style>
<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="add"  data-display-type="" data-page-url="<?php print_link($current_page); ?>">
    <?php
    if( $show_header == true ){
    ?>
    <div  class="p-3 mb-3">
        <div class="container">
            <div class="row ">
                <div class="col ">
                    <h4 class="record-title">Disposisikan</h4>
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
                        <form id="disposisikan-add-form" role="form" novalidate enctype="multipart/form-data" class="form page-form form-horizontal needs-validation" action="<?php print_link("surat_masuk/disposisi?csrf_token=$csrf_token") ?>" method="post">
                            <div>
                                <div class="form-group ">
                                    <div class="row" style="display:none">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="nomor_surat">Nomor Surat </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <input id="ctrl-nomor_surat"  value="<?php  echo $this->set_field_value('id_surat',$nomor); ?>" type="text" placeholder="Nomor Surat"  readonly name="id_surat"  class="form-control " />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="display:none">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="nomor_surat">Nomor Surat </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <input id="ctrl-nomor_surat"  value="<?php  echo $this->set_field_value('sumber',$sumber); ?>" type="text" placeholder="Nomor Surat"  readonly name="sumber"  class="form-control " />
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
                                                    <label class="control-label" for="tanggal">Tanggal </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <input id="ctrl-tanggal" class="form-control" value="<?php  echo $this->set_field_value('tanggal',datetime_now()); ?>" type="datetime" readonly name="tanggal" placeholder="Tanggal"/> 
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
                                                        <label class="control-label" for="kepada">Kepada <span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-8" >
                                                        <div class="" style="border-style: dashed; padding-left : 10px; border-color : #969590;">
                                                            <?php 
                                                                $kepada_options = $comp_model -> index_surat_kepada_option_list();
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
                                            <div class="form-group ">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label class="control-label" for="kepada">Tembusan</label>
                                                    </div>
                                                    <div class="col-sm-8" >
                                                        <div class="" style="border-style: dashed; padding-left : 10px; border-color : #969590;">
                                                            <?php 
                                                                $kepada_options = $comp_model -> index_surat_tembusan_new_disposisi_option_list();
                                                                if(!empty($kepada_options)){
                                                                    foreach($kepada_options as $option){
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
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label class="control-label" for="isi">Isi Disposisi <span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-8" style="column-count:2">
                                                    <div class="" width:100%; column-gap:70%">
                                                    <?php 
                                                    $isi = $comp_model -> getIsiDisposisi();
                                                    if(!empty($isi)){
                                                    foreach($isi as $option){
                                                    $value = (!empty($option['isi_disposisi']) ? $option['isi_disposisi'] : null);
                                                    $label = (!empty($option['isi_disposisi']) ? $option['isi_disposisi'] : $value);
                                                    $selected = $this->set_field_selected('disposisi',$value, "");
                                                    ?>
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="disposisi[]" type="checkbox" value="<?php echo $value?>" id="flexCheckDefault<?php echo $value?>" />
                                                        <label class="form-check-label" for="flexCheckDefault<?php echo $value?>"><?php echo $label ?></label>
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
                                            </div>
                                            <div class="form-group ">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label class="control-label" for="balasan">Catatan <span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="">
                                                            <textarea placeholder="Balasan" id="ctrl-balasan"  required="" rows="5" name="keterangan" class="htmleditor form-control"><?php  echo $this->set_field_value('keterangan',""); ?></textarea>
                                                            <!--<div class="invalid-feedback animated bounceIn text-center">Please enter text</div>-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group form-submit-btn-holder text-center mt-3">
                                            <div class="form-ajax-status"></div>
                                            <button class="btn btn-primary" type="submit" id="addButton">
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

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha256-pasqAKBDmFT4eHoN2ndd6lN370kFiGUFyTiUHWhU7k8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
<script>
    $('#addButton').click(function () {
        $('#disposisikan-add-form').submit();
    });
</script>
