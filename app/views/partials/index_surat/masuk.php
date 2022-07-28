<?php 
//check if current user role is allowed access to the pages
$can_add = ACL::is_allowed("index_surat/add");
$can_edit = ACL::is_allowed("index_surat/edit");
$can_view = ACL::is_allowed("index_surat/view");
$can_delete = ACL::is_allowed("index_surat/delete");
?>
<?php
$comp_model = new SharedController;
$page_element_id = "view-page-" . random_str();
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;
//Page Data Information from Controller
$data = $this->view_data;
//$rec_id = $data['__tableprimarykey'];
$page_id = $this->route->page_id; //Page id from url
$view_title = $this->view_title;
$show_header = $this->show_header;
$show_edit_btn = $this->show_edit_btn;
$show_delete_btn = $this->show_delete_btn;
$show_export_btn = $this->show_export_btn;
?>
<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="view"  data-display-type="table" data-page-url="<?php print_link($current_page); ?>">
    <?php
    if( $show_header == true ){
    ?>
    <div  class="p-3 mb-3">
        <div class="container">
            <div class="row ">
                <div class="col ">
                    <h4 class="record-title">SURAT MASUK</h4>
                </div>
                <div class="col-md-1 comp-grid">
                    <a  class="btn btn-primary" href="<?php print_link("index_surat/index_masuk") ?>">
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
                <div class="col-md-12 comp-grid">
                    <?php $this :: display_page_errors(); ?>
                    <div  class="card animated fadeIn page-content">
                        <?php
                        $counter = 0;
                        if(!empty($data)){
                        $rec_id = (!empty($data['id_surat']) ? urlencode($data['id_surat']) : null);
                        $counter++;
                        ?>
                        <div id="page-report-body" class="">
                            <table class="table table-hover table-borderless table-striped">
                                <!-- Table Body Start -->
                                <tbody class="page-data" id="page-data-<?php echo $page_element_id; ?>">
                                    <tr  class="td-nomor_surat">
                                        <th class="title"> Nomor Surat: </th>
                                        <td class="value"> <?php echo $data['nomor_surat']; ?></td>
                                    </tr>
                                    <tr  class="td-tanggal">
                                        <th class="title"> Tanggal: </th>
                                        <td class="value"> <?php echo $data['tanggal']; ?></td>
                                    </tr>
                                    <tr  class="td-pengguna">
                                        <th class="title"> Pengguna: </th>
                                        <td class="value"> <?php echo $data['pengguna']; ?></td>
                                    </tr>
                                    <tr  class="td-kepada">
                                        <th class="title"> Kepada: </th>
                                        <td class="value"> <?php echo $data['kepada']; ?></td>
                                    </tr>
                                    <tr  class="td-tembusan">
                                        <th class="title"> Tembusan: </th>
                                        <td class="value"> <?php echo $data['tembusan']; ?></td>
                                    </tr>
                                    <tr  class="td-disposisi">
                                        <th class="title"> Disposisi: </th>
                                        <td class="value"> <?php echo $data['disposisi']; ?></td>
                                    </tr>
                                    <tr  class="td-subjek">
                                        <th class="title"> Subjek: </th>
                                        <td class="value"> <?php echo $data['subjek']; ?></td>
                                    </tr>
                                    <tr  class="td-keterangan">
                                        <th class="title"> Keterangan: </th>
                                        <td class="value"> <?php echo $data['keterangan']; ?></td>
                                    </tr>
                                    <tr  class="td-lampiran">
                                        <th class="title"> Lampiran: </th>
                                        <td class="value"><?php Html :: page_link_file($data['lampiran']); ?></td>
                                    </tr>
                                    <tr  class="td-sifat">
                                        <th class="title"> Sifat: </th>
                                        <td class="value">
                                            <?php
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
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr  class="td-persetujuan">
                                        <th class="title"> Persetujuan: </th>
                                        <td class="value"><a class="btn btn-sm btn-info page-modal" href="<?php print_link("persetujuan_disposisi"); ?>?id=<?php echo $data['nomor_surat']; ?>">Lihat Persetujuan</a></td>
                                    </tr>
                                    <tr  class="td-balasan">
                                        <th class="title"> Balasan: </th>
                                        <td class="value"><a class="btn btn-sm btn-info page-modal" href="<?php print_link("balasan_surat"); ?>?id=<?php echo $data['nomor_surat']; ?>">Lihat Balasan</a></td>
                                    </tr>
                                </tbody>
                                <!-- Table Body End -->
                            </table>
                        </div>
                        <div class="p-3 d-flex">
                            <div class="dropup export-btn-holder mx-1">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-save"></i> Export
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <?php $export_print_link = $this->set_current_page_link(array('format' => 'print')); ?>
                                    <a class="dropdown-item export-link-btn" data-format="print" href="<?php print_link($export_print_link); ?>" target="_blank">
                                        <img src="<?php print_link('assets/images/print.png') ?>" class="mr-2" /> PRINT
                                        </a>
                                        <?php $export_pdf_link = $this->set_current_page_link(array('format' => 'pdf')); ?>
                                        <a class="dropdown-item export-link-btn" data-format="pdf" href="<?php print_link($export_pdf_link); ?>" target="_blank">
                                            <img src="<?php print_link('assets/images/pdf.png') ?>" class="mr-2" /> PDF
                                            </a>
                                            <?php $export_word_link = $this->set_current_page_link(array('format' => 'word')); ?>
                                            <a class="dropdown-item export-link-btn" data-format="word" href="<?php print_link($export_word_link); ?>" target="_blank">
                                                <img src="<?php print_link('assets/images/doc.png') ?>" class="mr-2" /> WORD
                                                </a>
                                                <?php $export_csv_link = $this->set_current_page_link(array('format' => 'csv')); ?>
                                                <a class="dropdown-item export-link-btn" data-format="csv" href="<?php print_link($export_csv_link); ?>" target="_blank">
                                                    <img src="<?php print_link('assets/images/csv.png') ?>" class="mr-2" /> CSV
                                                    </a>
                                                    <?php $export_excel_link = $this->set_current_page_link(array('format' => 'excel')); ?>
                                                    <a class="dropdown-item export-link-btn" data-format="excel" href="<?php print_link($export_excel_link); ?>" target="_blank">
                                                        <img src="<?php print_link('assets/images/xsl.png') ?>" class="mr-2" /> EXCEL
                                                        </a>
                                                    </div>
                                                </div>
                                                <?php if($can_edit){ ?>
                                                <a class="btn btn-sm btn-info"  href="<?php print_link("index_surat/edit/$rec_id"); ?>">
                                                    <i class="fa fa-edit"></i> Balas
                                                </a>
                                                <?php } ?>
                                            </div>
                                            <?php
                                            }
                                            else{
                                            ?>
                                            <!-- Empty Record Message -->
                                            <div class="text-muted p-3">
                                                <i class="fa fa-ban"></i> No Record Found
                                            </div>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
