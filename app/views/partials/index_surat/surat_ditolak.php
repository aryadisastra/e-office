<?php
$comp_model = new SharedController;
$page_element_id = "list-page-" . random_str();
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;
//Page Data From Controller
$can_view = ACL::is_allowed("index_surat/view");
$view_data = $this->view_data;
$field_value = $this->route->field_value;
$view_title = $this->view_title;
$show_header = $this->show_header;
$show_footer = $this->show_footer;
$show_pagination = $this->show_pagination;
?>
<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="list"  data-display-type="table" data-page-url="<?php print_link($current_page); ?>">
    <?php
    if( $show_header == true ){
    ?>
    <div  class="p-3 mb-3">
        <div class="container-fluid">
            <div class="row ">
                <div class="col ">
                    <h4 class="record-title">LOG</h4>
                </div>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
    <div  class="">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-md-12 comp-grid">
                    <?php $this :: display_page_errors(); ?>
                    <div  class="bg-secondary animated fadeIn page-content">
                        <div id="persetujuan_disposisi-list-records">
                            <div id="page-report-body" class="table-responsive">
                                    <table class="table  table-striped table-sm text-left">
                                        <thead class="table-header bg-light">
                                            <tr>
                                                <th class="td-sno">#</th>
                                                <th  class="td-pengguna"> Pengirim</th>
                                                <th  class="td-subjek"> Subjek</th>
                                                <th  class="td-sifat"> Sifat</th>
                                                <th  class="td-tanggal"> Tanggal</th>
                                                <th class="td-btn"></th>
                                            </tr>
                                        </thead>
                                        <?php
                                        if(!empty($view_data)){
                                        ?>
                                        <tbody class="page-data" id="page-data-<?php echo $page_element_id; ?>">
                                            <!--record-->
                                            <?php
                                            $counter = 0;
                                            foreach($view_data as $data){
                                            $rec_id = (!empty($data['id_surat']) ? urlencode($data['id_surat']) : null);
                                            $counter++;
                                            ?>
                                            <tr>
                                                <th class="td-sno"><?php echo $counter; ?></th>
                                                <td class="td-pengguna"> <?php echo $data['pengguna']; ?></td>
                                                <td class="td-subjek"> <?php echo $data['subjek']; ?></td>
                                                <td class="td-sifat"><?php
                                                    if($data['sifat'] == "Biasa"){?>
                                                    <span class="badge badge-success"><?php echo $data['sifat']; ?></span>
                                                    <?php } ?>
                                                    <?php
                                                    if($data['sifat'] == "Prioritas"){?>
                                                    <span class="badge badge-danger"><?php echo $data['sifat']; ?></span>
                                                    <?php } ?>
                                                    <?php
                                                    if($data['sifat'] == "Rahasia"){?>
                                                    <span class="badge badge-dark"><?php echo $data['sifat']; ?></span>
                                                <?php } ?></td>
                                                <td class="td-tanggal"> <?php echo $data['tanggal']; ?></td>
                                                <th class="td-btn">
                                                    <?php if($can_view){ ?>
                                                    <a class="btn btn-sm btn-success" href="<?php print_link("index_surat/keluar/$rec_id"); ?>">
                                                        <i class="fa fa-eye"></i> 
                                                    </a>
                                                    <?php } ?>
                                                </th>
                                            </tr>
                                            <?php 
                                            }
                                            ?>
                                            <!--endrecord-->
                                        </tbody>
                                        <tbody class="search-data" id="search-data-<?php echo $page_element_id; ?>"></tbody>
                                        <?php
                                        }
                                        ?>
                                    </table>
                                    <?php 
                                    if(empty($view_data)){
                                    ?>
                                    <h4 class="bg-light text-center border-top text-muted animated bounce  p-3">
                                        <i class="fa fa-ban"></i> Tidak ada data
                                    </h4>
                                    <?php
                                    }
                                    ?>
                            </div>
                        </div>
                    </section>
