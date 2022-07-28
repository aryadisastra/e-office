<?php 
/**
 * Balasan_surat Page Controller
 * @category  Controller
 */
class Balasan_suratController extends SecureController{
	function __construct(){
		parent::__construct();
		$this->tablename = "balasan_surat";
	}
	/**
     * List page records
     * @param $fieldname (filter record by a field) 
     * @param $fieldvalue (filter field value)
     * @return BaseView
     */
	function index($fieldname = null , $fieldvalue = null){
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array("nomor_surat", 
			"pengguna", 
			"tanggal", 
			"balasan", 
			"id");
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				balasan_surat.id_surat LIKE ? OR 
				balasan_surat.nomor_surat LIKE ? OR 
				balasan_surat.pengguna LIKE ? OR 
				balasan_surat.tanggal LIKE ? OR 
				balasan_surat.balasan LIKE ? OR 
				balasan_surat.id LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "balasan_surat/search.php";
		}
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("balasan_surat.id", ORDER_TYPE);
		}
		$db->where("nomor_surat = '".$_GET['id']."'");
		if($fieldname){
			$db->where($fieldname , $fieldvalue); //filter by a single field name
		}
		$tc = $db->withTotalCount();
		$records = $db->get($tablename, $pagination, $fields);
		$records_count = count($records);
		$total_records = intval($tc->totalCount);
		$page_limit = $pagination[1];
		$total_pages = ceil($total_records / $page_limit);
		$data = new stdClass;
		$data->records = $records;
		$data->record_count = $records_count;
		$data->total_records = $total_records;
		$data->total_page = $total_pages;
		if($db->getLastError()){
			$this->set_page_error();
		}
		$page_title = $this->view->page_title = "Balasan Surat";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("balasan_surat/list.php", $data); //render the full page
	}
	/**
     * View record detail 
	 * @param $rec_id (select record by table primary key) 
     * @param $value value (select record by value of field name(rec_id))
     * @return BaseView
     */
	function view($rec_id = null, $value = null){
		$request = $this->request;
		$db = $this->GetModel();
		$rec_id = $this->rec_id = urldecode($rec_id);
		$tablename = $this->tablename;
		$fields = array("id", 
			"id_surat", 
			"nomor_surat", 
			"pengguna", 
			"tanggal", 
			"balasan");
		if($value){
			$db->where($rec_id, urldecode($value)); //select record based on field name
		}
		else{
			$db->where("balasan_surat.id", $rec_id);; //select record based on primary key
		}
		$record = $db->getOne($tablename, $fields );
		if($record){
			$page_title = $this->view->page_title = "View  Balasan Surat";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		}
		else{
			if($db->getLastError()){
				$this->set_page_error();
			}
			else{
				$this->set_page_error("No record found");
			}
		}
		return $this->render_view("balasan_surat/view.php", $record);
	}
	/**
     * Insert new record to the database table
	 * @param $formdata array() from $_POST
     * @return BaseView
     */
	function add($formdata = null){
		if($formdata){
			$folderPath = IMG_DIR."signature/";
			$image_parts = explode(";base64,", $formdata['signature']);
			$image_type_aux = explode("image/", $image_parts[0]);
			$image_type = $image_type_aux[1];
			$image_base64 = base64_decode($image_parts[1]);
			$namefile = uniqid().'.'.$image_type;
			$file = $folderPath . $namefile;
			$postdata = $this->format_request_data($formdata);
			$db = $this->GetModel();
			$getData = $db->query("SELECT * FROM index_surat WHERE id_surat =".$formdata['id_surat']);
			$bentuk = USER_BAGIAN != 4 ? (USER_BAGIAN != 5 ? 'Paraf' : 'Tanda Tangan') : 'Tanda Tangan';
			if(strtolower(USER_NAME) != 'operator') $add_sign = $db->query("INSERT INTO signature (id_surat,pengguna,bentuk,signature) VALUES ('".$formdata['id_surat']."','".USER_NAMA."','".$bentuk."','".$namefile."')");
			if($add_sign)
			{
				file_put_contents($file, $image_base64);
			}
			$fixStatus = (int) $getData[0]['status'] + 1;
			$viewBefore = $db->query("SELECT * FROM index_surat WHERE id_surat = '".$getData[0]['id_surat']."'");
			$updateView = $viewBefore[0]['can_view'].', '.$postdata['kepada'].', '.$postdata['tembusan'] ;
			if($getData[0]['status'] == 4) {
				$updateData = $db->query("UPDATE index_surat SET tahap_surat = 2, kepada = 'pokmin', status = 1 where id_surat = ".$formdata['id_surat']);
			}
			if($getData[0]['status'] == 2 && $getData[0]['tahap_surat'] == 2) {
				$updateData = $db->query("UPDATE index_surat SET tahap_surat = 1, kepada = 'dircab', status = 5 where id_surat = ".$formdata['id_surat']);
			}
			$updateData = $db->query("UPDATE index_surat SET status = '".$fixStatus."', pengguna = '".USER_NAME."', kepada = '".$postdata['kepada']."', tembusan = '".$postdata['tembusan']."', can_view = '".$updateView."' WHERE id_surat = '".$getData[0]['id_surat']."'");
			$log = $db->query("INSERT INTO log_surat (id_surat,pengguna,waktu,keterangan,lampiran,sumber) VALUES ('".$formdata['id_surat']."','".USER_NAMA."','".date("Y-m-d H:i",time())."','Di Lanjutkan','".$formdata['lampiran']."',2)");
			$catatan = $db->query("INSERT INTO log_catatan_surat (id_surat,pengguna,catatan,waktu,keterangan,lampiran,sumber) VALUES ('".$formdata['id_surat']."','".USER_NAMA."','".$formdata['keterangan']."','".date("Y-m-d H:i",time())."','Di Lanjutkan','".$formdata['lampiran']."',2)");
			$tembusanarr = explode(',',$postdata['tembusan']);
			foreach($tembusanarr as $tb)
			{
				$tembusan = $db->query("INSERT INTO log_tembusan_surat (id_surat,pengguna,waktu,sumber) VALUES ('".$formdata['id_surat']."','".$tb."','".date("Y-m-d H:i",time())."',2)");
			}

			if ($log && $catatan && $updateData) {
				$this->set_flash_msg("Surat Berhasil Diupdate", "success");
				return	$this->redirect("home");
			} else {
				$this->set_page_error();
			}
		}
		$page_title = $this->view->page_title = "Balas Surat";
		$this->render_view("balasan_surat/add.php");
	}

	function verifikasi($formdata = null){
		if($formdata){
			$folderPath = IMG_DIR."signature/";
			$image_parts = explode(";base64,", $formdata['signature']);
			$image_type_aux = explode("image/", $image_parts[0]);
			$image_type = $image_type_aux[1];
			$image_base64 = base64_decode($image_parts[1]);
			$namefile = uniqid().'.'.$image_type;
			$file = $folderPath . $namefile;
			$postdata = $this->format_request_data($formdata);
			$db = $this->GetModel();
			$getData = $db->query("SELECT * FROM index_surat WHERE id_surat =".$formdata['id_surat']);
			$bentuk = USER_BAGIAN != 4 ? (USER_BAGIAN != 5 ? 'Paraf' : 'Tanda Tangan') : 'Tanda Tangan';
			if(strtolower(USER_NAME) != 'operator') $add_sign = $db->query("INSERT INTO signature (id_surat,pengguna,bentuk,signature) VALUES ('".$formdata['id_surat']."','".USER_NAMA."','".$bentuk."','".$namefile."')");
			if($add_sign)
			{
				file_put_contents($file, $image_base64);
			}
			$fixStatus = 1;
			$viewBefore = $db->query("SELECT * FROM index_surat WHERE id_surat = '".$getData[0]['id_surat']."'");
			$updateView = $viewBefore[0]['can_view'].', pokmin';
			if($getData[0]['status'] == 4) {
				$updateData = $db->query("UPDATE index_surat SET tahap_surat = 2, kepada = 'pokmin', status = 1 where id_surat = ".$formdata['id_surat']);
			}
			if($getData[0]['status'] == 2 && $getData[0]['tahap_surat'] == 2) {
				$updateData = $db->query("UPDATE index_surat SET tahap_surat = 1, kepada = 'dircab', status = 5 where id_surat = ".$formdata['id_surat']);
			}
			$updateData = $db->query("UPDATE index_surat SET tahap_surat = 3, status = '".$fixStatus."', pengguna = '".USER_NAME."', kepada = 'pokmin', tembusan = '".$postdata['tembusan']."', can_view = '".$updateView."' WHERE id_surat = '".$getData[0]['id_surat']."'");
			$log = $db->query("INSERT INTO log_surat (id_surat,pengguna,waktu,keterangan,lampiran,sumber) VALUES ('".$formdata['id_surat']."','".USER_NAMA."','".date("Y-m-d H:i",time())."','Di Lanjutkan','".$formdata['lampiran']."',2)");
			$catatan = $db->query("INSERT INTO log_catatan_surat (id_surat,pengguna,catatan,waktu,keterangan,lampiran,sumber) VALUES ('".$formdata['id_surat']."','".USER_NAMA."','".$formdata['keterangan']."','".date("Y-m-d H:i",time())."','Di Lanjutkan','".$formdata['lampiran']."',2)");
			$tembusanarr = explode(',',$postdata['tembusan']);
			foreach($tembusanarr as $tb)
			{
				$tembusan = $db->query("INSERT INTO log_tembusan_surat (id_surat,pengguna,waktu,sumber) VALUES ('".$formdata['id_surat']."','".$tb."','".date("Y-m-d H:i",time())."',2)");
			}

			if ($log && $catatan && $updateData) {
				$this->set_flash_msg("Surat Berhasil Diupdate", "success");
				return	$this->redirect("home");
			} else {
				$this->set_page_error();
			}
		}
		$page_title = $this->view->page_title = "Balas Surat";
		$this->render_view("balasan_surat/verifikasi.php");
	}

	function kembalikan($formdata = null){
		if($formdata){
			$db = $this->GetModel();
			$getData = $db->query("SELECT * FROM index_surat WHERE id_surat =".$formdata['id_surat']);
			$fixStatus = (int) $getData[0]['status'] - 1;
			$flow_status = isset($formdata['reject']) ? 3 : 2;
			$keterangan = isset($formdata['reject']) ? 'Di Tolak' : 'Di Kembalikan';
			$updateData = $db->query("UPDATE index_surat SET status = '".$fixStatus."',flow_status = '".$flow_status."', pengguna = '".USER_NAME."', kepada = '".$getData[0]['pengguna']."' WHERE id_surat = '".$getData[0]['id_surat']."'");
			$log = $db->query("INSERT INTO log_surat (id_surat,pengguna,waktu,keterangan,lampiran,sumber) VALUES ('".$formdata['id_surat']."','".USER_NAMA."','".date("Y-m-d H:i",time())."','".$keterangan."','".$formdata['lampiran']."',2)");
			$catatan = $db->query("INSERT INTO log_catatan_surat (id_surat,pengguna,catatan,lampiran,waktu,keterangan,sumber) VALUES ('".$formdata['id_surat']."','".USER_NAMA."','".$formdata['keterangan']."','".$formdata['lampiran']."','".date("Y-m-d H:i",time())."','".$keterangan."',2)");
			
			if ($log && $catatan && $updateData) {
				$this->set_flash_msg("Surat Berhasil Diupdate", "success");
				return	$this->redirect("home");
			} else {
				$this->set_page_error();
			}
		}
		$page_title = $this->view->page_title = "Balas Surat";
		$this->render_view("balasan_surat/kembalikan.php");
	}

	function buat_nomor($formdata = null){
		if($formdata){
			$folderPath = IMG_DIR."signature/";
			$image_parts = explode(";base64,", $formdata['signature']);
			$image_type_aux = explode("image/", $image_parts[0]);
			$image_type = $image_type_aux[1];
			$image_base64 = base64_decode($image_parts[1]);
			$namefile = uniqid().'.'.$image_type;
			$file = $folderPath . $namefile;
			$db = $this->GetModel();
			$getData = $db->query("SELECT * FROM index_surat WHERE id_surat =".$formdata['id_surat']);
			$bentuk = USER_BAGIAN != 4 ? (USER_BAGIAN != 5 ? 'Paraf' : 'Tanda Tangan') : 'Tanda Tangan';
			$add_sign = $db->query("INSERT INTO signature (id_surat,pengguna,bentuk,signature) VALUES ('".$formdata['id_surat']."','".USER_NAMA."','".$bentuk."','".$namefile."')");
			if($add_sign)
			{
				file_put_contents($file, $image_base64);
			}
			$viewBefore = $db->query("SELECT * FROM index_surat WHERE id_surat = '".$getData[0]['id_surat']."'");
			$updateView = $viewBefore[0]['can_view'].', pokmin' ;
			$updateData = $db->query("UPDATE index_surat SET status = 1, pengguna = '".USER_NAME."', kepada = 'pokmin', can_view = '".$updateView."', tahap_surat = 2 WHERE id_surat = '".$getData[0]['id_surat']."'");
			$log = $db->query("INSERT INTO log_surat (id_surat,pengguna,waktu,keterangan,lampiran,sumber) VALUES ('".$formdata['id_surat']."','".USER_NAMA."','".date("Y-m-d H:i",time())."','Pembuatan Nomor','".$formdata['lampiran']."',2)");
			$catatan = $db->query("INSERT INTO log_catatan_surat (id_surat,pengguna,catatan,waktu,keterangan,lampiran,sumber) VALUES ('".$formdata['id_surat']."','".USER_NAMA."','".$formdata['keterangan']."','".date("Y-m-d H:i",time())."','Pembuatan Nomor','".$formdata['lampiran']."',2)");
			
			if ($log && $catatan && $updateData) {
				$this->set_flash_msg("Surat Berhasil Diupdate", "success");
				return	$this->redirect("home");
			} else {
				$this->set_page_error();
			}
		}
		$page_title = $this->view->page_title = "Balas Surat";
		$this->render_view("balasan_surat/buat_nomor.php");
	}
	/**
     * Update table record with formdata
	 * @param $rec_id (select record by table primary key)
	 * @param $formdata array() from $_POST
     * @return array
     */
	function edit($rec_id = null, $formdata = null){
		$request = $this->request;
		$db = $this->GetModel();
		$this->rec_id = $rec_id;
		$tablename = $this->tablename;
		 //editable fields
		$fields = $this->fields = array("id","nomor_surat","pengguna","tanggal","balasan");
		if($formdata){
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'balasan' => 'required',
			);
			$this->sanitize_array = array(
				'nomor_surat' => 'sanitize_string',
				'pengguna' => 'sanitize_string',
				'tanggal' => 'sanitize_string',
			);
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
				$db->where("balasan_surat.id", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
				if($bool && $numRows){
					$this->set_flash_msg("Data berhasil diperbarui", "success");
					return $this->redirect("balasan_surat");
				}
				else{
					if($db->getLastError()){
						$this->set_page_error();
					}
					elseif(!$numRows){
						//not an error, but no record was updated
						$page_error = "No record updated";
						$this->set_page_error($page_error);
						$this->set_flash_msg($page_error, "warning");
						return	$this->redirect("balasan_surat");
					}
				}
			}
		}
		$db->where("balasan_surat.id", $rec_id);;
		$data = $db->getOne($tablename, $fields);
		$page_title = $this->view->page_title = "Edit  Balasan Surat";
		if(!$data){
			$this->set_page_error();
		}
		return $this->render_view("balasan_surat/edit.php", $data);
	}
	/**
     * Delete record from the database
	 * Support multi delete by separating record id by comma.
     * @return BaseView
     */
	function delete($rec_id = null){
		Csrf::cross_check();
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$this->rec_id = $rec_id;
		//form multiple delete, split record id separated by comma into array
		$arr_rec_id = array_map('trim', explode(",", $rec_id));
		$db->where("balasan_surat.id", $arr_rec_id, "in");
		$bool = $db->delete($tablename);
		if($bool){
			$this->set_flash_msg("Data berhasil dihapus", "success");
		}
		elseif($db->getLastError()){
			$page_error = $db->getLastError();
			$this->set_flash_msg($page_error, "danger");
		}
		return	$this->redirect("balasan_surat");
	}
}
