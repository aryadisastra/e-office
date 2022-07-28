<?php
$comp_model = new SharedController;
$page_element_id = "add-page-" . random_str();
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;
$show_header = $this->show_header;
$view_title = $this->view_title;
$redirect_to = $this->redirect_to;
$nomor = $_GET['nomor_surat'];
?>
<style>
    #canvasDiv{
        position: relative;
        border: 2px dashed grey;
        height:100px;
    }
</style>
<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="add" data-display-type="" data-page-url="<?php print_link($current_page); ?>">
    <?php
    if ($show_header == true) {
    ?>
        <div class="p-3 mb-3">
            <div class="container">
                <div class="row ">
                    <div class="col ">
                        <h4 class="record-title">Disposisi Lanjutan</h4>
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
                <div class="col comp-grid">
                    <?php $this::display_page_errors(); ?>
                    <div class="bg-light p-3 animated fadeIn page-content">
                        <form id="persetujuan_disposisi-add-form" role="form" novalidate enctype="multipart/form-data" class="form page-form form-horizontal needs-validation" action="<?php print_link("persetujuan_disposisi/add?csrf_token=$csrf_token") ?>" method="post">
                            <div>
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="nomor_surat">Nomor Surat </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <input id="ctrl-nomor_surat" value="<?php echo $this->set_field_value('nomor_surat', $nomor); ?>" type="text" placeholder="Nomor Surat" readonly name="nomor_surat" class="form-control " />
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
                                                <input id="ctrl-pengguna" value="<?php echo $this->set_field_value('pengguna', USER_NAME); ?>" type="text" placeholder="Enter Pengguna" readonly name="pengguna" class="form-control " />
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
                                                <input id="ctrl-tanggal" class="form-control" value="<?php echo $this->set_field_value('tanggal', datetime_now()); ?>" type="datetime-local" readonly name="tanggal" placeholder="Enter Tanggal" data-enable-time="true" data-min-date="" data-max-date="" data-date-format="Y-m-d H:i:S" data-alt-format="Y-m-d H:i:s" data-inline="false" data-no-calendar="false" data-mode="single" />
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
                                            <label class="control-label" for="Kepada">Kepada </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="input-group">
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
                                <?php if(USER_BAGIAN != 1) {?>
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
                                            <input class="form-check-input" name="disposisi[]" type="checkbox" value="<?php echo $value?>" id="flexCheckDefault" />
                                            <label class="form-check-label" for="flexCheckDefault"><?php echo $label ?></label>
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
                                            <label class="control-label" for="komentar">Catatan </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <input id="ctrl-pengguna" value="" type="text" name="keterangan" class="form-control " />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php if(USER_BAGIAN == 5) {?>
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="sign">Tanda Tangan <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <div id="canvasDiv"></div>
                                                <input type="hidden" id="signature" name="signature">
                                                <input type="hidden" name="signaturesubmit" value="1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="form-group " style="display:none">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="persetujuan">Persetujuan <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <?php
                                                $persetujuan_options = $comp_model->persetujuan_disposisi_persetujuan_option_list();
                                                if (!empty($persetujuan_options)) {
                                                    foreach ($persetujuan_options as $option) {
                                                        $value = (!empty($option['value']) ? $option['value'] : null);
                                                        $label = (!empty($option['label']) ? $option['label'] : $value);
                                                        $checked = $this->set_field_checked('persetujuan', $value, "");
                                                ?>
                                                        <label class="form-check form-check-inline">
                                                            <input id="ctrl-persetujuan" class="form-check-input" <?php echo $checked; ?> value="<?php echo $value; ?>" type="radio" name="persetujuan" />
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
                                <button class="btn btn-primary" type="submit" id="addButton">
                                    Simpan
                                    <i class="fa fa-save"></i>
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
    $(document).ready(() => {
        var canvasDiv = document.getElementById('canvasDiv');
        var canvas = document.createElement('canvas');
        canvas.setAttribute('id', 'canvas');
        canvasDiv.appendChild(canvas);
        $("#canvas").attr('height', $("#canvasDiv").outerHeight());
        $("#canvas").attr('width', $("#canvasDiv").width());
        if (typeof G_vmlCanvasManager != 'undefined') {
            canvas = G_vmlCanvasManager.initElement(canvas);
        }
        
        context = canvas.getContext("2d");
        $('#canvas').mousedown(function(e) {
            var offset = $(this).offset()
            var mouseX = e.pageX - this.offsetLeft;
            var mouseY = e.pageY - this.offsetTop;

            paint = true;
            addClick(e.pageX - offset.left, e.pageY - offset.top);
            redraw();
        });

        $('#canvas').mousemove(function(e) {
            if (paint) {
                var offset = $(this).offset()
                //addClick(e.pageX - this.offsetLeft, e.pageY - this.offsetTop, true);
                addClick(e.pageX - offset.left, e.pageY - offset.top, true);
                console.log(e.pageX, offset.left, e.pageY, offset.top);
                redraw();
            }
        });

        $('#canvas').mouseup(function(e) {
            paint = false;
        });

        $('#canvas').mouseleave(function(e) {
            paint = false;
        });

        var clickX = new Array();
        var clickY = new Array();
        var clickDrag = new Array();
        var paint;

        function addClick(x, y, dragging) {
            clickX.push(x);
            clickY.push(y);
            clickDrag.push(dragging);
        }

        $("#reset-btn").click(function() {
            context.clearRect(0, 0, window.innerWidth, window.innerWidth);
            clickX = [];
            clickY = [];
            clickDrag = [];
        });

        $(document).on('click', '#addButton', function() {
            var mycanvas = document.getElementById('canvas');
            var img = mycanvas.toDataURL("image/png");
            anchor = $("#signature");
            anchor.val(img);
            $("#signatureform").submit();
        });

        var drawing = false;
        var mousePos = {
            x: 0,
            y: 0
        };
        var lastPos = mousePos;

        canvas.addEventListener("touchstart", function(e) {
            mousePos = getTouchPos(canvas, e);
            var touch = e.touches[0];
            var mouseEvent = new MouseEvent("mousedown", {
                clientX: touch.clientX,
                clientY: touch.clientY
            });
            canvas.dispatchEvent(mouseEvent);
        }, false);


        canvas.addEventListener("touchend", function(e) {
            var mouseEvent = new MouseEvent("mouseup", {});
            canvas.dispatchEvent(mouseEvent);
        }, false);


        canvas.addEventListener("touchmove", function(e) {

            var touch = e.touches[0];
            var offset = $('#canvas').offset();
            var mouseEvent = new MouseEvent("mousemove", {
                clientX: touch.clientX,
                clientY: touch.clientY
            });
            canvas.dispatchEvent(mouseEvent);
        }, false);



        // Get the position of a touch relative to the canvas
        function getTouchPos(canvasDiv, touchEvent) {
            var rect = canvasDiv.getBoundingClientRect();
            return {
                x: touchEvent.touches[0].clientX - rect.left,
                y: touchEvent.touches[0].clientY - rect.top
            };
        }


        var elem = document.getElementById("canvas");

        var defaultPrevent = function(e) {
            e.preventDefault();
        }
        elem.addEventListener("touchstart", defaultPrevent);
        elem.addEventListener("touchmove", defaultPrevent);


        function redraw() {
            //
            lastPos = mousePos;
            for (var i = 0; i < clickX.length; i++) {
                context.beginPath();
                if (clickDrag[i] && i) {
                    context.moveTo(clickX[i - 1], clickY[i - 1]);
                } else {
                    context.moveTo(clickX[i] - 1, clickY[i]);
                }
                context.lineTo(clickX[i], clickY[i]);
                context.closePath();
                context.stroke();
            }
        }
    })

</script>

<script>
    $('#addButton').click(function () {
        window.location = '/index_surat/index_disposisi';
    });
</script>