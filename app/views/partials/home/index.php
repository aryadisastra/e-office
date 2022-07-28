<?php 
$page_id = null;
$comp_model = new SharedController;
$current_page = $this->set_current_page_link();
?>
<div>
    <div  class="p-3 mb-3">
        <div class="container">
            <div class="row ">
                <div class="col-md-12 comp-grid">
                    <h4 >BERANDA</h4>
                </div>
            </div>
        </div>
    </div>
    <div  class="mb-5">
        <div class="container">
            <div class="row ">
                <div class="col-md-12 comp-grid">
                </div>
                <div class="col-md-4 col-sm-4 comp-grid">
                    <?php $rec_count = $comp_model->getcount_suratmasuk();  ?>
                    <a class="animated zoomIn record-count card bg-light text-white"  href="<?php print_link("surat_masuk/") ?>">
                        <div class="row">
                            <div class="col-2">
                                <i class="fa fa-globe"></i>
                            </div>
                            <div class="col-10">
                                <div class="flex-column justify-content align-center">
                                    <div class="title">SURAT MASUK</div>
                                    <small class=""></small>
                                </div>
                            </div>
                            <h4 class="value"><strong><?php echo $rec_count; ?></strong></h4>
                        </div>
                    </a>
                </div>
                <?php if(USER_NAME != 'kauraplikasi') { ?>
                    <div class="col-md-4 col-sm-4 comp-grid" >
                        <?php $rec_count = $comp_model->getcount_suratkeluar();  ?>
                        <a class="animated zoomIn record-count card bg-light text-white"  href="<?php print_link("index_surat/list_keluar") ?>">
                        <div class="row">
                            <div class="col-2">
                                <i class="fa fa-globe"></i>
                            </div>
                            <div class="col-10">
                                <div class="flex-column justify-content align-center">
                                    <div class="title">SURAT KELUAR</div>
                                    <small class=""></small>
                                </div>
                            </div>
                            <h4 class="value"><strong><?php echo $rec_count; ?></strong></h4>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 col-sm-4 comp-grid">
                    <?php $rec_count = $comp_model->getcount_disposisi();  ?>
                    <a class="animated zoomIn record-count card bg-light text-white"  href="<?php print_link("index_surat/list_disposisi") ?>">
                    <div class="row">
                            <div class="col-2">
                                <i class="fa fa-globe"></i>
                            </div>
                            <div class="col-10">
                                <div class="flex-column justify-content align-center">
                                    <div class="title">DISPOSISI</div>
                                    <small class=""></small>
                                </div>
                            </div>
                            <h4 class="value"><strong><?php echo $rec_count; ?></strong></h4>
                        </div>
                    </a>
                </div>
                <?php } ?>
                <?php if(USER_NAME == 'pokmin') { ?>
                    <div class="col-md-4 col-sm-4 comp-grid">
                        <?php $rec_count = $comp_model->getcount_suratprinted();  ?>
                        <a class="animated zoomIn record-count card bg-light text-white"  href="<?php print_link("index_surat/list_print") ?>">
                            <div class="row">
                                <div class="col-2">
                                    <i class="fa fa-globe"></i>
                                </div>
                                <div class="col-10">
                                    <div class="flex-column justify-content align-center">
                                        <div class="title">PRINT</div>
                                        <small class=""></small>
                                    </div>
                                </div>
                                <h4 class="value"><strong><?php echo $rec_count; ?></strong></h4>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>

            <div  class="">
        <div class="container">
            <div class="row ">
                <div class="col-md-12 comp-grid">
                </div>
                <div class="col-md-6 comp-grid" >
                    <h5 class="mb-3">SURAT MASUK</h5>
                    <div class="bg-secondary ">
                        <?php  
                        $this->render_page("surat_masuk/list?limit_count=5" , array( 'show_header' => false,'show_footer' => false,'show_pagination' => false )); 
                        ?>
                    </div>
                </div>
                
                <?php if(USER_NAME != 'kauraplikasi') { ?>
                <div class="col-md-6 comp-grid">
                    <h5 class="mb-3">DISPOSISI</h5>
                    <div class="bg-secondary ">
                        <?php  
                        $this->render_page("index_surat/tb_disposisi?limit_count=5" , array( 'show_header' => false,'show_footer' => false,'show_pagination' => false )); 
                        ?>
                    </div>
                </div>
                <?php } ?>
            </div>
            
            <?php if(USER_NAME != 'kauraplikasi') { ?>
            <div class="row ">
                <div class="col-md-12 comp-grid">
                </div>
                <div class="col-md-6 comp-grid" >
                    <h5 class="mb-3">SURAT KELUAR</h5>
                    <div class="bg-secondary ">
                        <?php  
                        $this->render_page("index_surat/index_keluar?limit_count=5" , array( 'show_header' => false,'show_footer' => false,'show_pagination' => false )); 
                        ?>
                    </div>
                </div>
                <div class="col-md-6 comp-grid">
                    <h5 class="mb-3">SURAT DITOLAK</h5>
                    <div class="bg-secondary ">
                        <?php  
                        $this->render_page("index_surat/surat_ditolak?limit_count=5" , array( 'show_header' => false,'show_footer' => false,'show_pagination' => false )); 
                        ?>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
        </div>
    </div>
</div>
