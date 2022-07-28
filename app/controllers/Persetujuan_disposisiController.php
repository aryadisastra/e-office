<?php 
/**
 * Persetujuan_disposisi Page Controller
 * @category  Controller
 */
class Persetujuan_disposisiController extends SecureController{
	function __construct(){
		parent::__construct();
		$this->tablename = "persetujuan_disposisi";
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
			"isi_disposisi", 
			"persetujuan", 
			"komentar", 
			"id");
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				persetujuan_disposisi.id_surat LIKE ? OR 
				persetujuan_disposisi.nomor_surat LIKE ? OR 
				persetujuan_disposisi.pengguna LIKE ? OR 
				persetujuan_disposisi.tanggal LIKE ? OR 
				persetujuan_disposisi.persetujuan LIKE ? OR 
				persetujuan_disposisi.id LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "persetujuan_disposisi/search.php";
		}
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("persetujuan_disposisi.id", ORDER_TYPE);
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
		$page_title = $this->view->page_title = "Persetujuan Disposisi";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$value_dis = [];
		foreach ($data->records as $dt =>$key)
		{
			if($key['isi_disposisi'] != null) {

				$value_isi = explode(',',$key['isi_disposisi']);
				foreach($value_isi as $vi)
				{
					if( $vi != null){
						$db = $this->GetModel();
						$arr = $db->query("SELECT isi_disposisi FROM isi_disposisi WHERE id = '".$vi."'");
						if($arr != null) $value_dis['isi_disposisi'][] = $arr[0]['isi_disposisi'] != null ? $arr[0]['isi_disposisi'] : 'Data Sudah Dihapus';
					}
				}
				if($value_isi != null || $value_isi != '') {
					$changevalue = implode(',',$value_dis['isi_disposisi']);
					
					$data->records[$dt]['isi_disposisi'] = $changevalue;
				}
			}
		}
		$this->render_view("persetujuan_disposisi/list.php", $data); //render the full page
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
			"persetujuan");
		if($value){
			$db->where($rec_id, urldecode($value)); //select record based on field name
		}
		else{
			$db->where("persetujuan_disposisi.id", $rec_id);; //select record based on primary key
		}
		$record = $db->getOne($tablename, $fields );
		if($record){
			$page_title = $this->view->page_title = "View  Persetujuan Disposisi";
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
		return $this->render_view("persetujuan_disposisi/view.php", $record);
	}
	/**
     * Insert new record to the database table
	 * @param $formdata array() from $_POST
     * @return BaseView
     */
	function add($formdata = null){
		if($formdata){
			$db = $this->GetModel();
			$tablename = 'index_surat';
			$request = $this->request;
			if(isset($formdata['signature'])){
				$folderPath = IMG_DIR."signature/";
				$image_parts = explode(";base64,", $formdata['signature']);
				$image_type_aux = explode("image/", $image_parts[0]);
				$image_type = $image_type_aux[1];
				$image_base64 = base64_decode($image_parts[1]);
				$namefile = uniqid().'.'.$image_type;
				$file = $folderPath . $namefile;
			}
			$postdata = $this->format_request_data($formdata);
			$e = $db->query("select * from index_surat where nomor_surat = '".$postdata['nomor_surat']."'");
			foreach($e as $dt)
			{
				if(isset($formdata['signature'])){
					$bentuk = USER_BAGIAN != 4 ? (USER_BAGIAN != 5 ? 'Paraf' : 'Tanda Tangan') : 'Tanda Tangan';
					$add_sign = $db->query("INSERT INTO signature (id_surat,pengguna,bentuk,signature) VALUES ('".$dt['id_surat']."','".USER_NAMA."','".$bentuk."','".$namefile."')");
					if($add_sign)
					{
						file_put_contents($file, $image_base64);
					}
				}
				// return print_r($dt['id_surat']);
				$asalKepada = $dt['kepada'];
				$asalDisposisi = $dt['disposisi'];
				$asalKeterangan = $dt['keterangan'];
				$isiDisposisi = [USER_NAME => $postdata['disposisi']];
				// return print_r($asalKeterangan);
				// foreach($asalDisposisi as $ad => $key) {
				// }
				$bool = $db->where("index_surat.id_index", $dt['id_surat'])->update('index_surat',
				[	'status' => (int) USER_BAGIAN - 1,
					'kepada' => $asalKepada.','.$postdata['kepada'],
					'disposisi' => $asalDisposisi.'-'.json_encode($isiDisposisi),
					'keterangan' => $asalKeterangan.',<p>'.$postdata['keterangan'].'<br></p>',
					'status_persetujuan' => 1
				]);
				$bools = $db->where("surat_masuk.id_index", $dt['id_surat'])->update('surat_masuk',
				[	'status' => (int) USER_BAGIAN - 1,
					'kepada' => $asalKepada.','.$postdata['kepada'],
					'disposisi' => $asalDisposisi.'-'.json_encode($isiDisposisi),
					'keterangan' => $asalKeterangan.',<p>'.$postdata['keterangan'].'<br></p>',
					'status_persetujuan' => 1
				]);
				$log = $db->query("INSERT INTO log_surat (id_surat,pengguna,waktu,nomor_surat) VALUES ('".$dt['id_surat']."','".USER_NAMA."','".date("Y-m-d H:i",time())."','".$postdata['nomor_surat']."')");
				
				$disposisi = $db->query("INSERT INTO disposisi_surat (id_surat,pengguna,isi_disposisi,waktu,nomor_surat) VALUES ('".$dt['id_surat']."','".USER_NAMA."','".$postdata['disposisi']."','".date("Y-m-d H:i",time())."','".$postdata['nomor_surat']."')");
				$catatan = $db->query("INSERT INTO log_catatan_surat (id_surat,pengguna,catatan,waktu,nomor_surat) VALUES ('".$dt['id_surat']."','".USER_NAMA."','".$postdata['keterangan']."','".date("Y-m-d H:i",time())."','".$postdata['nomor_surat']."')");
			}
			// return print_r($postdata);
			
				// $bool = $db->update('index_surat', ['status' => $status]);
				if($bool && $bools && $log && $disposisi && $catatan)
				{
					$this->set_flash_msg("Data berhasil ditambahkan", "success");
					return	$this->redirect("home");
					// $rec_id = $this->rec_id = $db->insert($tablename, $modeldata);
					// if($rec_id){
					
					// }
				}
				else{
					$this->set_page_error();
				}
		}
		$page_title = $this->view->page_title = "Buat Persetujuan";
		$this->render_view("persetujuan_disposisi/add.php");
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
		$fields = $this->fields = array("id","nomor_surat","pengguna","tanggal","persetujuan");
		if($formdata){
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'persetujuan' => 'required',
			);
			$this->sanitize_array = array(
				'nomor_surat' => 'sanitize_string',
				'pengguna' => 'sanitize_string',
				'tanggal' => 'sanitize_string',
				'persetujuan' => 'sanitize_string',
			);
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
				$db->where("persetujuan_disposisi.id", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
				if($bool && $numRows){
					$this->set_flash_msg("Data berhasil diperbarui", "success");
					return $this->redirect("persetujuan_disposisi");
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
						return	$this->redirect("persetujuan_disposisi");
					}
				}
			}
		}
		$db->where("persetujuan_disposisi.id", $rec_id);;
		$data = $db->getOne($tablename, $fields);
		$page_title = $this->view->page_title = "Edit  Persetujuan Disposisi";
		if(!$data){
			$this->set_page_error();
		}
		return $this->render_view("persetujuan_disposisi/edit.php", $data);
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
		$db->where("persetujuan_disposisi.id", $arr_rec_id, "in");
		$bool = $db->delete($tablename);
		if($bool){
			$this->set_flash_msg("Data berhasil dihapus", "success");
		}
		elseif($db->getLastError()){
			$page_error = $db->getLastError();
			$this->set_flash_msg($page_error, "danger");
		}
		return	$this->redirect("persetujuan_disposisi");
	}
	/**
     * List page records
     * @param $fieldname (filter record by a field) 
     * @param $fieldvalue (filter field value)
     * @return BaseView
     */
	function group_disposisi($fieldname = null , $fieldvalue = null){
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array("nomor_surat", 
			"pengguna", 
			"tanggal", 
			"persetujuan", 
			"id");
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				persetujuan_disposisi.id_surat LIKE ? OR 
				persetujuan_disposisi.nomor_surat LIKE ? OR 
				persetujuan_disposisi.pengguna LIKE ? OR 
				persetujuan_disposisi.tanggal LIKE ? OR 
				persetujuan_disposisi.persetujuan LIKE ? OR 
				persetujuan_disposisi.id LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "persetujuan_disposisi/search.php";
		}
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("persetujuan_disposisi.id", ORDER_TYPE);
		}
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
		$page_title = $this->view->page_title = "Persetujuan Disposisi";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("persetujuan_disposisi/group_disposisi.php", $data); //render the full page
	}
}
