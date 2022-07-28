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
<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="edit" data-display-type="" data-page-url="<?php print_link($current_page); ?>">
    <?php
    if ($show_header == true) {
    ?>
        <div class="bg-light p-3 mb-3">
            <div class="container">
                <div class="row ">
                    <div class="col ">
                        <h4 class="record-title">Edit Isi Disposisi</h4>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
    <div class="">
        <div class="container">
            <div class="row ">
                <div class="col-md-7 comp-grid">
                    <?php $this::display_page_errors(); ?>
                    <div class="bg-light p-3 animated fadeIn page-content">
                        <form novalidate id="" role="form" enctype="multipart/form-data" class="form page-form form-horizontal needs-validation" action="<?php print_link("isidisposisi/edit/$page_id/?csrf_token=$csrf_token"); ?>" method="post">
                            <div>
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="username">Isi Disposisi <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <input id="ctrl-username" value="<?php echo $data['isi_disposisi']; ?>" type="text" name="isi_disposisi"class="form-control  ctrl-check-duplicate" />
                                                <div class="check-status"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="Bagian">Bagian <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <select required="" id="ctrl-bagian" name="bagian" placeholder="Select a value ..." class="custom-select">
                                                    <option value="">Select a value ...</option>
                                                    <?php
                                                    if (!empty($data['bagianOption'])) {
                                                        $field_value = $data['bagian'];
                                                        foreach ($data['bagianOption'] as $option) {
                                                            $value = $option['id'];
                                                            $label = $option['bagian'];
                                                            $selected = ($value == $field_value ? 'selected' : null);
                                                    ?>
                                                            <option <?php echo $selected ?> value="<?php echo $value ?>">
                                                                <?php echo $label ?>
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
                                            <label class="control-label" for="">Status <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                        <div class="form-check form-check-inline">
                                                <input class="form-check-input" name="status" type="radio"  id="inlineRadio2" value="1" <?php echo $data['status'] == 1 ? 'checked' : ''?>>
                                                <label class="form-check-label" for="inlineRadio2">Aktif</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio"name="status"  id="inlineRadio3" value="2" <?php echo $data['status'] != 1 ? 'checked' : ''?>>
                                                <label class="form-check-label" for="inlineRadio3">Non-Aktif</label>
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