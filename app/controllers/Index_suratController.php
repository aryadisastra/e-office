<?php

/**
 * Index_surat Page Controller
 * @category  Controller
 */
class Index_suratController extends SecureController
{
	function __construct()
	{
		parent::__construct();
		$this->tablename = "index_surat";
	}
	/**
	 * List page records
	 * @param $fieldname (filter record by a field) 
	 * @param $fieldvalue (filter field value)
	 * @return BaseView
	 */
	function index($fieldname = null, $fieldvalue = null)
	{
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array(
			"nomor_surat",
			"pengguna",
			"subjek",
			"sifat",
			"tanggal",
			"id_surat"
		);
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if (!empty($request->search)) {
			$text = trim($request->search);
			$search_condition = "(
				index_surat.nomor_surat LIKE ? OR 
				index_surat.kepada LIKE ? OR 
				index_surat.tembusan LIKE ? OR 
				index_surat.disposisi LIKE ? OR 
				index_surat.subjek LIKE ? OR 
				index_surat.sifat LIKE ? OR 
				index_surat.tanggal LIKE ?
			)";
			$search_params = array(
				"%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			//template to use when ajax search
			$this->view->search_template = "index_surat/search.php";
		}
		if (!empty($request->orderby)) {
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		} else {
			$db->orderBy("index_surat.id_surat", ORDER_TYPE);
		}
		if ($fieldname) {
			$db->where($fieldname, $fieldvalue); //filter by a single field name
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
		if ($db->getLastError()) {
			$this->set_page_error();
		}
		$page_title = $this->view->page_title = "Index Surat";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("index_surat/list.php", $data); //render the full page
	}
	/**
	 * View record detail 
	 * @param $rec_id (select record by table primary key) 
	 * @param $value value (select record by value of field name(rec_id))
	 * @return BaseView
	 */
	function view($rec_id = null, $value = null)
	{
		$request = $this->request;
		$db = $this->GetModel();
		$rec_id = $this->rec_id = urldecode($rec_id);
		$tablename = $this->tablename;
		$fields = array(
			"id_surat",
			"id_index",
			"nomor_surat",
			"tanggal",
			"pengguna",
			"kepada",
			"tembusan",
			"disposisi",
			"subjek",
			"keterangan",
			"lampiran",
			"sifat",
			"persetujuan",
			"balasan"
		);
		if ($value) {
			$db->where($rec_id, urldecode($value)); //select record based on field name
		} else {
			$db->where("index_surat.id_surat", $rec_id);; //select record based on primary key
		}
		$record = $db->getOne($tablename, $fields);
		if ($record) {
			$record['tanggal'] = relative_date($record['tanggal']);
			$page_title = $this->view->page_title = "Index Surat";
			$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
			$this->view->report_title = $page_title;
			$this->view->report_layout = "report_layout.php";
			$this->view->report_paper_size = "A4";
			$this->view->report_orientation = "portrait";
		} else {
			if ($db->getLastError()) {
				$this->set_page_error();
			} else {
				$this->set_page_error("No record found");
			}
		}
		return $this->render_view("index_surat/view.php", $record);
	}
	/**
	 * Insert new record to the database table
	 * @param $formdata array() from $_POST
	 * @return BaseView
	 */
	function add($formdata = null)
	{
		if ($formdata) {
			$db = $this->GetModel();
			$tablename = $this->tablename;
			$request = $this->request;
			$fields = $this->fields = array("tanggal","keteranga", "pengguna", "kepada", "tembusan", "subjek", "lampiran", "sifat","status","tahap_surat","id_index");
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'kepada' => 'required',
				'subjek' => 'required',
				'sifat' => 'required',
			);
			$this->sanitize_array = array(
				'tanggal' => 'sanitize_string',
				'pengguna' => 'sanitize_string',
				'kepada' => 'sanitize_string',
				'tembusan' => 'sanitize_string',
				'subjek' => 'sanitize_string',
				'keterangan' => 'sanitize_string',
				'lampiran' => 'sanitize_string',
				'sifat' => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			$modeldata['nomor_surat'] = $postdata['nomor_surat'];
			$modeldata['status'] = 2;
			$modeldata['status_persetujuan'] = 1;
			$modeldata['tahap_surat'] = 1;
			$modeldata['keterangan'] = $postdata['keterangan'];
			$modeldata['can_view'] = ''.USER_NAME.', '.$postdata['kepada'].', '.$postdata['tembusan'];
			$getlast_id = $db->query("SELECT MAX(id_surat) AS last FROM index_surat");
			$fixedId = (int) $getlast_id[0]['last'] + 1;
			$modeldata['id_index'] = $fixedId;
			if(strtolower(USER_NAME) != 'operator') {
				$isiDisposisi = [USER_NAME => $modeldata['disposisi']];
				$disposisiNew = $modeldata['disposisi'];
				$modeldata['disposisi'] = json_encode($isiDisposisi);
			}
			$db->where("nomor_surat", $modeldata['nomor_surat']);
			if ($db->has($tablename)) {
				$this->view->page_error[] = $modeldata['nomor_surat'] . " Already exist!";
			}
			if ($this->validated()) {
				// return print_r($modeldata);
				$rec_id = $this->rec_id = $db->insert($tablename, $modeldata);
				$log = $db->query("INSERT INTO log_surat (id_surat,nomor_surat,pengguna,waktu,keterangan,lampiran,sumber) VALUES ('".$fixedId."','".$modeldata['nomor_surat']."','".USER_NAMA."','".date("Y-m-d H:i",time())."','Di Buat','".$postdata['lampiran']."',2)");
				$catatan = $db->query("INSERT INTO log_catatan_surat (id_surat,nomor_surat,pengguna,catatan,waktu,keterangan,lampiran,sumber) VALUES ('".$fixedId."','".$modeldata['nomor_surat']."','".USER_NAMA."','".$modeldata['keterangan']."','".date("Y-m-d H:i",time())."','Di Buat','".$postdata['lampiran']."',2)");
				if (!isset($formdata['disposisi'])){
					if(strtolower(USER_NAME) != 'operator') {
						$db->query('UPDATE index_surat SET status_persetujuan = 1 where nomor_surat = '.$modeldata['nomor_surat']);
					} 
				}
				if ($rec_id && $log && $catatan) {
					$this->set_flash_msg("Surat Berhasil Dikirim", "success");
					return	$this->redirect("home");
				} else {
					$this->set_page_error();
				}
			}
		}
		$page_title = $this->view->page_title = "Buat Surat Keluar";
		$this->render_view("index_surat/add.php");
	}
	/**
	 * Update table record with formdata
	 * @param $rec_id (select record by table primary key)
	 * @param $formdata array() from $_POST
	 * @return array
	 */
	function edit($rec_id = null, $formdata = null)
	{
		$request = $this->request;
		$db = $this->GetModel();
		$this->rec_id = $rec_id;
		$tablename = $this->tablename;
		//editable fields
		$fields = $this->fields = array("id_surat", "nomor_surat", "tanggal", "pengguna", "kepada", "tembusan", "disposisi", "subjek", "keterangan", "lampiran", "sifat");
		if ($formdata) {
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'nomor_surat' => 'required',
				'kepada' => 'required',
				'subjek' => 'required',
				'keterangan' => 'required',
				'sifat' => 'required',
			);
			$this->sanitize_array = array(
				'nomor_surat' => 'sanitize_string',
				'tanggal' => 'sanitize_string',
				'pengguna' => 'sanitize_string',
				'kepada' => 'sanitize_string',
				'tembusan' => 'sanitize_string',
				'disposisi' => 'sanitize_string',
				'subjek' => 'sanitize_string',
				'lampiran' => 'sanitize_string',
				'sifat' => 'sanitize_string',
			);
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			//Check if Duplicate Record Already Exit In The Database
			if (isset($modeldata['nomor_surat'])) {
				$db->where("nomor_surat", $modeldata['nomor_surat'])->where("id_surat", $rec_id, "!=");
				if ($db->has($tablename)) {
					$this->view->page_error[] = $modeldata['nomor_surat'] . " Already exist!";
				}
			}
			if ($this->validated()) {
				$db->where("index_surat.id_surat", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$bools = $db->update('surat_masuk', $modeldata);
				$numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
				if ($bool && $numRows && $bools) {
					$this->set_flash_msg("Data berhasil diperbarui", "success");
					return $this->redirect("index_surat");
				} else {
					if ($db->getLastError()) {
						$this->set_page_error();
					} elseif (!$numRows) {
						//not an error, but no record was updated
						$page_error = "No record updated";
						$this->set_page_error($page_error);
						$this->set_flash_msg($page_error, "warning");
						return	$this->redirect("index_surat");
					}
				}
			}
		}
		$db->where("index_surat.id_surat", $rec_id);;
		$data = $db->getOne($tablename, $fields);
		$page_title = $this->view->page_title = "Edit  Index Surat";
		if (!$data) {
			$this->set_page_error();
		}
		return $this->render_view("index_surat/edit.php", $data);
	}
	/**
	 * Delete record from the database
	 * Support multi delete by separating record id by comma.
	 * @return BaseView
	 */
	function delete($rec_id = null)
	{
		Csrf::cross_check();
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$this->rec_id = $rec_id;
		//form multiple delete, split record id separated by comma into array
		$arr_rec_id = array_map('trim', explode(",", $rec_id));
		$db->where("index_surat.id_surat", $arr_rec_id, "in");
		$bool = $db->delete($tablename);
		if ($bool) {
			$this->set_flash_msg("Data berhasil dihapus", "success");
		} elseif ($db->getLastError()) {
			$page_error = $db->getLastError();
			$this->set_flash_msg($page_error, "danger");
		}
		return	$this->redirect("index_surat");
	}
	/**
	 * List page records
	 * @param $fieldname (filter record by a field) 
	 * @param $fieldvalue (filter field value)
	 * @return BaseView
	 */
	function tb_masuk($fieldname = null, $fieldvalue = null)
	{
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = 'surat_masuk';
		$fields = array(
			"pengguna",
			"subjek",
			"sifat",
			"tanggal",
			"id_surat"
		);
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if (!empty($request->search)) {
			$text = trim($request->search);
			$search_condition = "(
				index_surat.nomor_surat LIKE ? OR 
				index_surat.pengguna LIKE ? OR 
				index_surat.kepada LIKE ? OR 
				index_surat.tembusan LIKE ? OR 
				index_surat.disposisi LIKE ? OR 
				index_surat.subjek LIKE ? OR 
				index_surat.keterangan LIKE ? OR 
				index_surat.lampiran LIKE ? OR 
				index_surat.sifat LIKE ? OR 
				index_surat.tanggal LIKE ? OR 
				index_surat.persetujuan LIKE ? OR 
				index_surat.balasan LIKE ? OR 
				index_surat.id_surat LIKE ?
			)";
			$search_params = array(
				"%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			//template to use when ajax search
			$this->view->search_template = "index_surat/search.php";
		}
		if (!empty($request->orderby)) {
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		} else {
			$db->orderBy("surat_masuk.id_surat", ORDER_TYPE);
		}
		if(USER_BAGIAN != 6)
		{
			$db->where("can_view LIKE '%".USER_NAME."%' AND status >= 4");
		}
		if ($fieldname) {
			$db->where($fieldname, $fieldvalue); //filter by a single field name
		}
		$tc = $db->withTotalCount();
		$records = $db->get('surat_masuk', $pagination, $fields);
		$records_count = count($records);
		$total_records = intval($tc->totalCount);
		$page_limit = $pagination[1];
		$total_pages = ceil($total_records / $page_limit);
		if (!empty($records)) {
			foreach ($records as &$record) {
				$record['tanggal'] = relative_date($record['tanggal']);
			}
		}
		$data = new stdClass;
		$data->records = $records;
		$data->record_count = $records_count;
		$data->total_records = $total_records;
		$data->total_page = $total_pages;
		if ($db->getLastError()) {
			$this->set_page_error();
		}
		$page_title = $this->view->page_title = "Index Surat";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("index_surat/tb_masuk.php", $data); //render the full page
	}
	/**
	 * List page records
	 * @param $fieldname (filter record by a field) 
	 * @param $fieldvalue (filter field value)
	 * @return BaseView
	 */
	function tb_disposisi($fieldname = null, $fieldvalue = null)
	{
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = 'surat_masuk';
		$role = USER_BAGIAN;
		$fields = array(
			"pengguna",
			"subjek",
			"sifat",
			"tanggal",
			"id_surat"
		);
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if (!empty($request->search)) {
			$text = trim($request->search);
			$search_condition = "(
				index_surat.nomor_surat LIKE ? OR 
				index_surat.pengguna LIKE ? OR 
				index_surat.kepada LIKE ? OR 
				index_surat.tembusan LIKE ? OR 
				index_surat.disposisi LIKE ? OR 
				index_surat.subjek LIKE ? OR 
				index_surat.keterangan LIKE ? OR 
				index_surat.lampiran LIKE ? OR 
				index_surat.sifat LIKE ? OR 
				index_surat.tanggal LIKE ? OR 
				index_surat.persetujuan LIKE ? OR 
				index_surat.balasan LIKE ? OR 
				index_surat.id_surat LIKE ?
			)";
			$search_params = array(
				"%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			//template to use when ajax search
			$this->view->search_template = "index_surat/search.php";
		}
		if (!empty($request->orderby)) {
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		} else {
			$db->orderBy("surat_masuk.id_surat", ORDER_TYPE);
		}
		if(USER_BAGIAN != 6 && strtolower(USER_NAME) != 'operator'){
			$db->where("can_view LIKE '%".USER_NAME."%' AND status = ".USER_BAGIAN);
		}
		if ($fieldname) {
			$db->where($fieldname, $fieldvalue); //filter by a single field name
		}
		$tc = $db->withTotalCount();
		$records = $db->get('surat_masuk', $pagination, $fields);
		$records_count = count($records);
		$total_records = intval($tc->totalCount);
		$page_limit = $pagination[1];
		$total_pages = ceil($total_records / $page_limit);
		if (!empty($records)) {
			foreach ($records as &$record) {
				$record['tanggal'] = relative_date($record['tanggal']);
			}
		}
		$data = new stdClass;
		$data->records = $records;
		$data->record_count = $records_count;
		$data->total_records = $total_records;
		$data->total_page = $total_pages;
		if ($db->getLastError()) {
			$this->set_page_error();
		}
		$page_title = $this->view->page_title = "Index Surat";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("index_surat/tb_disposisi.php", $data); //render the full page
	}

	function list_disposisi($fieldname = null, $fieldvalue = null)
	{
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = 'surat_masuk';
		$role = USER_BAGIAN;
		$fields = array(
			"pengguna",
			"subjek",
			"sifat",
			"tanggal",
			"id_surat"
		);
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if (!empty($request->search)) {
			$text = trim($request->search);
			$search_condition = "(
				index_surat.nomor_surat LIKE ? OR 
				index_surat.pengguna LIKE ? OR 
				index_surat.kepada LIKE ? OR 
				index_surat.tembusan LIKE ? OR 
				index_surat.disposisi LIKE ? OR 
				index_surat.subjek LIKE ? OR 
				index_surat.keterangan LIKE ? OR 
				index_surat.lampiran LIKE ? OR 
				index_surat.sifat LIKE ? OR 
				index_surat.tanggal LIKE ? OR 
				index_surat.persetujuan LIKE ? OR 
				index_surat.balasan LIKE ? OR 
				index_surat.id_surat LIKE ?
			)";
			$search_params = array(
				"%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			//template to use when ajax search
			$this->view->search_template = "index_surat/search.php";
		}
		if (!empty($request->orderby)) {
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		} else {
			$db->orderBy("surat_masuk.id_surat", ORDER_TYPE);
		}
		if(USER_BAGIAN != 6 && strtolower(USER_NAME) != 'operator'){
			$db->where("can_view LIKE '%".USER_NAME."%'");
		}
		if ($fieldname) {
			$db->where($fieldname, $fieldvalue); //filter by a single field name
		}
		$tc = $db->withTotalCount();
		$records = $db->get('surat_masuk', $pagination, $fields);
		$records_count = count($records);
		$total_records = intval($tc->totalCount);
		$page_limit = $pagination[1];
		$total_pages = ceil($total_records / $page_limit);
		if (!empty($records)) {
			foreach ($records as &$record) {
				$record['tanggal'] = date('d-M-Y',strtotime($record['tanggal']));
			}
		}
		$data = new stdClass;
		$data->records = $records;
		$data->record_count = $records_count;
		$data->total_records = $total_records;
		$data->total_page = $total_pages;
		if ($db->getLastError()) {
			$this->set_page_error();
		}
		$page_title = $this->view->page_title = "Index Surat";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("index_surat/list_disposisi.php", $data); //render the full page
	}
	/**
	 * List page records
	 * @param $fieldname (filter record by a field) 
	 * @param $fieldvalue (filter field value)
	 * @return BaseView
	 */
	function index_masuk($fieldname = null, $fieldvalue = null)
	{
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array(
			"nomor_surat",
			"pengguna",
			"subjek",
			"sifat",
			"tanggal",
			"id_surat"
		);
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if (!empty($request->search)) {
			$text = trim($request->search);
			$search_condition = "(
				index_surat.nomor_surat LIKE ? OR 
				index_surat.kepada LIKE ? OR 
				index_surat.tembusan LIKE ? OR 
				index_surat.disposisi LIKE ? OR 
				index_surat.subjek LIKE ? OR 
				index_surat.sifat LIKE ? OR 
				index_surat.tanggal LIKE ?
			)";
			$search_params = array(
				"%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			//template to use when ajax search
			$this->view->search_template = "index_surat/search.php";
		}
		if (!empty($request->orderby)) {
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		} else {
			$db->orderBy("index_surat.id_surat", ORDER_TYPE);
		}
		$db->query("Select * from index_surat where kepada LIKE '%".USER_NAME."%' OR tembusan LIKE '%".USER_NAME."%'");
		if ($fieldname) {
			$db->where($fieldname, $fieldvalue); //filter by a single field name
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
		if ($db->getLastError()) {
			$this->set_page_error();
		}
		$page_title = $this->view->page_title = "Surat Masuk";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("index_surat/index_masuk.php", $data); //render the full page
	}
	/**
	 * List page records
	 * @param $fieldname (filter record by a field) 
	 * @param $fieldvalue (filter field value)
	 * @return BaseView
	 */
	function index_keluar($fieldname = null, $fieldvalue = null)
	{
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array(
			"nomor_surat",
			"kepada",
			"subjek",
			"sifat",
			"tanggal",
			"id_index",
			"id_surat"
		);
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if (!empty($request->search)) {
			$text = trim($request->search);
			$search_condition = "(
				index_surat.nomor_surat LIKE ? OR 
				index_surat.kepada LIKE ? OR 
				index_surat.tembusan LIKE ? OR 
				index_surat.disposisi LIKE ? OR 
				index_surat.subjek LIKE ? OR 
				index_surat.sifat LIKE ? OR 
				index_surat.tanggal LIKE ?
			)";
			$search_params = array(
				"%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			//template to use when ajax search
			$this->view->search_template = "index_surat/search.php";
		}
		if (!empty($request->orderby)) {
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		} else {
			$db->orderBy("index_surat.id_surat", ORDER_TYPE);
		}
		$bagian = USER_BAGIAN;
		if(USER_NAME != 'OPERATOR' || USER_BAGIAN != 6) $db->where("can_view LIKE '%" . USER_NAME . "%' AND status  =".$bagian." AND status != 404");
		if ($fieldname) {
			$db->where($fieldname, $fieldvalue); //filter by a single field name
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
		if ($db->getLastError()) {
			$this->set_page_error();
		}
		$page_title = $this->view->page_title = "Surat Keluar";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("index_surat/index_keluar.php", $data); //render the full page
	}

	function list_keluar($fieldname = null, $fieldvalue = null)
	{
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array(
			"nomor_surat",
			"kepada",
			"subjek",
			"sifat",
			"tanggal",
			"id_index",
			"id_surat"
		);
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if (!empty($request->search)) {
			$text = trim($request->search);
			$search_condition = "(
				index_surat.nomor_surat LIKE ? OR 
				index_surat.kepada LIKE ? OR 
				index_surat.tembusan LIKE ? OR 
				index_surat.disposisi LIKE ? OR 
				index_surat.subjek LIKE ? OR 
				index_surat.sifat LIKE ? OR 
				index_surat.tanggal LIKE ?
			)";
			$search_params = array(
				"%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			//template to use when ajax search
			$this->view->search_template = "index_surat/search.php";
		}
		if (!empty($request->orderby)) {
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		} else {
			$db->orderBy("index_surat.id_surat", ORDER_TYPE);
		}
		if(USER_NAME != 'OPERATOR' || USER_BAGIAN != 6) $db->where("can_view LIKE '%" . USER_NAME . "%' AND status != 404");
		if ($fieldname) {
			$db->where($fieldname, $fieldvalue); //filter by a single field name
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
		if ($db->getLastError()) {
			$this->set_page_error();
		}
		$page_title = $this->view->page_title = "Surat Keluar";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("index_surat/index_keluar.php", $data); //render the full page
	}

	function list_print($fieldname = null, $fieldvalue = null)
	{
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array(
			"nomor_surat",
			"kepada",
			"subjek",
			"sifat",
			"tanggal",
			"id_index",
			"id_surat"
		);
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if (!empty($request->search)) {
			$text = trim($request->search);
			$search_condition = "(
				index_surat.nomor_surat LIKE ? OR 
				index_surat.kepada LIKE ? OR 
				index_surat.tembusan LIKE ? OR 
				index_surat.disposisi LIKE ? OR 
				index_surat.subjek LIKE ? OR 
				index_surat.sifat LIKE ? OR 
				index_surat.tanggal LIKE ?
			)";
			$search_params = array(
				"%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			//template to use when ajax search
			$this->view->search_template = "index_surat/search.php";
		}
		if (!empty($request->orderby)) {
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		} else {
			$db->orderBy("index_surat.id_surat", ORDER_TYPE);
		}
		if(USER_NAME != 'OPERATOR' || USER_BAGIAN != 6) $db->where("can_view LIKE '%" . USER_NAME . "%' AND status = 6");
		if ($fieldname) {
			$db->where($fieldname, $fieldvalue); //filter by a single field name
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
		if ($db->getLastError()) {
			$this->set_page_error();
		}
		$page_title = $this->view->page_title = "Surat Keluar";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("index_surat/index_keluar.php", $data); //render the full page
	}
	/**
	 * List page records
	 * @param $fieldname (filter record by a field) 
	 * @param $fieldvalue (filter field value)
	 * @return BaseView
	 */
	function index_disposisi($fieldname = null, $fieldvalue = null)
	{
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array(
			"nomor_surat",
			"pengguna",
			"subjek",
			"sifat",
			"tanggal",
			"id_surat"
		);
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if (!empty($request->search)) {
			$text = trim($request->search);
			$search_condition = "(
				index_surat.nomor_surat LIKE ? OR 
				index_surat.kepada LIKE ? OR 
				index_surat.tembusan LIKE ? OR 
				index_surat.disposisi LIKE ? OR 
				index_surat.subjek LIKE ? OR 
				index_surat.sifat LIKE ? OR 
				index_surat.tanggal LIKE ?
			)";
			$search_params = array(
				"%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			//template to use when ajax search
			$this->view->search_template = "index_surat/search.php";
		}
		if (!empty($request->orderby)) {
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		} else {
			$db->orderBy("index_surat.id_surat", ORDER_TYPE);
		}
		$role = USER_BAGIAN;
		if(USER_NAME != 'OPERATOR') {
			if($role != null)$db->where("disposisi LIKE '%" . USER_NAME . "%' OR status = " .$role);
		}
		// if(USER_NAME != 'OPERATOR') $db->query("SELECT * from index_surat WHERE disposisi LIKE '%".USER_NAME."%' AND status >= ".USER_BAGIAN." OR pengguna = '".USER_NAME."'");
		if ($fieldname) {
			$db->where($fieldname, $fieldvalue); //filter by a single field name
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
		if ($db->getLastError()) {
			$this->set_page_error();
		}
		$page_title = $this->view->page_title = "Disposisi";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("index_surat/index_disposisi.php", $data); //render the full page
	}
	/**
	 * View record detail 
	 * @param $rec_id (select record by table primary key) 
	 * @param $value value (select record by value of field name(rec_id))
	 * @return BaseView
	 */
	function masuk($rec_id = null, $value = null)
	{
		$request = $this->request;
		$db = $this->GetModel();
		$rec_id = $this->rec_id = urldecode($rec_id);
		$tablename = $this->tablename;
		$fields = array(
			"id_surat",
			"nomor_surat",
			"tanggal",
			"pengguna",
			"kepada",
			"tembusan",
			"disposisi",
			"subjek",
			"keterangan",
			"lampiran",
			"sifat",
			"persetujuan",
			"balasan"
		);
		if ($value) {
			$db->where($rec_id, urldecode($value)); //select record based on field name
		} else {
			$db->where("index_surat.id_surat", $rec_id);; //select record based on primary key
		}
		$record = $db->getOne($tablename, $fields);
		if ($record) {
			$record['tanggal'] = relative_date($record['tanggal']);
			$page_title = $this->view->page_title = "Surat Masuk";
			$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
			$this->view->report_title = $page_title;
			$this->view->report_layout = "report_layout.php";
			$this->view->report_paper_size = "A4";
			$this->view->report_orientation = "portrait";
		} else {
			if ($db->getLastError()) {
				$this->set_page_error();
			} else {
				$this->set_page_error("No record found");
			}
		}
		return $this->render_view("index_surat/masuk.php", $record);
	}
	/**
	 * View record detail 
	 * @param $rec_id (select record by table primary key) 
	 * @param $value value (select record by value of field name(rec_id))
	 * @return BaseView
	 */
	function keluar($rec_id = null, $value = null)
	{
		$request = $this->request;
		$db = $this->GetModel();
		$rec_id = $this->rec_id = urldecode($rec_id);
		$tablename = $this->tablename;
		$fields = array(
			"id_surat",
			"id_index",
			"nomor_surat",
			"tanggal",
			"pengguna",
			"kepada",
			"tembusan",
			"disposisi",
			"subjek",
			"keterangan",
			"lampiran",
			"flow_status",
			"sifat",
			"tahap_surat",
			"status",
			"persetujuan",
			"balasan"
		);
		if ($value) {
			$db->where($rec_id, urldecode($value)); //select record based on field name
		} else {
			$db->where("index_surat.id_surat", $rec_id);; //select record based on primary key
		}
		$record = $db->getOne($tablename, $fields);
		if ($record) {
			$record['tanggal'] = date('d-M-Y',strtotime($record['tanggal']));
			$record['can_act'] = false;
			$getDataView = $db->query("SELECT * FROM index_surat WHERE kepada LIKE '%".USER_NAME."%' AND id_surat = ".$record['id_surat']);
			if($getDataView) $record['can_act'] = true;
			$page_title = $this->view->page_title = "Surat Keluar";
			$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
			$this->view->report_title = $page_title;
			if(USER_BAGIAN == 1 AND $record['status'] == 6) $db->query("UPDATE index_surat SET is_printed = 1 WHERE id_surat = ".$record['id_surat']);
			$this->view->report_layout = "report_layout.php";
			$this->view->report_paper_size = "A4";
			$this->view->report_orientation = "portrait";
		} else {
			if ($db->getLastError()) {
				$this->set_page_error();
			} else {
				$this->set_page_error("No record found");
			}
		}
		return $this->render_view("index_surat/keluar.php", $record);
	}
	/**
	 * View record detail 
	 * @param $rec_id (select record by table primary key) 
	 * @param $value value (select record by value of field name(rec_id))
	 * @return BaseView
	 */
	function disposisi($rec_id = null, $value = null)
	{
		$request = $this->request;
		$db = $this->GetModel();
		$rec_id = $this->rec_id = urldecode($rec_id);
		$tablename = $this->tablename;
		$fields = array(
			"id_surat",
			"id_index",
			"nomor_surat",
			"tanggal",
			"pengguna",
			"kepada",
			"tembusan",
			"disposisi",
			"subjek",
			"keterangan",
			"lampiran",
			"sifat",
			"persetujuan",
			"balasan",
			"status"
		);
		if ($value) {
			$db->where($rec_id, urldecode($value)); //select record based on field name
		} else {
			$db->where("index_surat.id_surat", $rec_id);; //select record based on primary key
		}
		$record = $db->getOne($tablename, $fields);
		if ($record) {
			$record['tanggal'] = $record['tanggal'];
			$page_title = $this->view->page_title = "Disposisi";
			$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
			$this->view->report_title = $page_title;
			$this->view->report_layout = "report_layout.php";
			$this->view->report_paper_size = "A4";
			$this->view->report_orientation = "portrait";
		} else {
			if ($db->getLastError()) {
				$this->set_page_error();
			} else {
				$this->set_page_error("No record found");
			}
		}
		return $this->render_view("index_surat/disposisi.php", $record);
	}

	function cek_disposisi($fieldname = null , $fieldvalue = null) {
		$request = $this->request;
		$db = $this->GetModel();
		$nomorSurat = $_GET['id'];
		$data = $db->query("select * from disposisi_surat where id_surat = '".$nomorSurat."'");
		$this->render_view("index_surat/cek_disposisi.php", $data);
	}

	function log(){
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = 'log_surat';
		$nomorSurat = $_GET['id'];
		$sumber = $_GET['sumber'];
		$data = $db->query("select * from log_surat where id_surat = '".$nomorSurat."' AND sumber = '".$sumber."'");
		$this->render_view("index_surat/log.php", $data);
	}

	function cek_catatan(){
		$request = $this->request;
		$db = $this->GetModel();
		$nomorSurat = $_GET['id'];
		$sumber = $_GET['sumber'];
		$data = $db->query("select * from log_catatan_surat where id_surat = '".$nomorSurat."' AND sumber = '".$sumber."'");
		$this->render_view("index_surat/cek_catatan.php", $data);
	}

	function cek_signature(){
		$request = $this->request;
		$db = $this->GetModel();
		$nomorSurat = $_GET['id'];
		$data = $db->query("select * from signature where id_surat = '".$nomorSurat."'");
		$this->render_view("index_surat/cek_signature.php", $data);
	}

	function buat_nomor($formdata = null){
		if($formdata){
			$db = $this->GetModel();
			$status = strtolower($formdata['kepada'][0]) != 'kasubditbinkom' ? 5 : 4; 
			$getData = $db->query("SELECT * FROM index_surat WHERE id_surat =".$formdata['id_surat']);
			$updateView = $getData[0]['can_view'].', '.$formdata['kepada'][0];
			$updateData = $db->query("UPDATE index_surat SET status = '".$status."', pengguna = '".USER_NAME."', kepada = '".$formdata['kepada'][0]."',nomor_surat = '".$formdata['nomor_surat']."', tahap_surat = 2, can_view = '".$updateView."' WHERE id_surat = '".$getData[0]['id_surat']."'");
			$log = $db->query("INSERT INTO log_surat (id_surat,pengguna,waktu,keterangan,sumber) VALUES ('".$formdata['id_surat']."','".USER_NAMA."','".date("Y-m-d H:i",time())."','Nomor Dibuat',2)");
			$catatan = $db->query("INSERT INTO log_catatan_surat (id_surat,pengguna,catatan,waktu,keterangan,sumber) VALUES ('".$formdata['id_surat']."','".USER_NAMA."','".$formdata['keterangan']."','".date("Y-m-d H:i",time())."','Nomor Dibuat',2)");
			
			if ($updateData) {
				$this->set_flash_msg("Surat Berhasil Diupdate", "success");
				return	$this->redirect("home");
			} else {
				$this->set_page_error();
			}
		}
		$page_title = $this->view->page_title = "Balas Surat";
		$this->render_view("index_surat/buat_nomor.php");
	}

	function surat_selesai()
	{
		$db = $this->GetModel();
		$done = $db->query("select * from index_surat where flow_status = 4 AND (pengguna LIKE '".USER_NAME."' OR tembusan LIKE '".USER_NAME."' OR kepada LIKE '".USER_NAME."')");
		if(USER_BAGIAN == 6) $done = $db->query("select * from index_surat where flow_status = 4");
		$this->render_view("index_surat/surat_selesai.php", $done);
	}

	function surat_ditolak()
	{
		$db = $this->GetModel();
		$reject = $db->query("select * from index_surat where status = 404 AND can_view LIKE '%".USER_NAME."%'");
		if(USER_BAGIAN == 6) $reject = $db->query("select * from index_surat where status = 404");
		$this->render_view("index_surat/surat_ditolak.php", $reject);
	}

	function log_surat_ditolak($rec_id = null, $value = null)
	{
		$request = $this->request;
		$db = $this->GetModel();
		$rec_id = $this->rec_id = urldecode($rec_id);
		$tablename = $this->tablename;
		$fields = array(
			"id_surat",
			"id_index",
			"nomor_surat",
			"tanggal",
			"pengguna",
			"kepada",
			"tembusan",
			"disposisi",
			"subjek",
			"keterangan",
			"lampiran",
			"sifat",
			"persetujuan",
			"balasan",
			"status"
		);
		if ($value) {
			$db->where($rec_id, urldecode($value)); //select record based on field name
		} else {
			$db->where("index_surat.id_surat", $rec_id);; //select record based on primary key
		}
		$record = $db->getOne($tablename, $fields);
		if ($record) {
			$record['tanggal'] = relative_date($record['tanggal']);
			$page_title = $this->view->page_title = "Surat Ditolak";
			$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
			$this->view->report_title = $page_title;
			$this->view->report_layout = "report_layout.php";
			$this->view->report_paper_size = "A4";
			$this->view->report_orientation = "portrait";
		} else {
			if ($db->getLastError()) {
				$this->set_page_error();
			} else {
				$this->set_page_error("No record found");
			}
		}
		return $this->render_view("index_surat/log_surat_ditolak.php", $record);
	}

	function log_surat_selesai($rec_id = null, $value = null)
	{
		$request = $this->request;
		$db = $this->GetModel();
		$rec_id = $this->rec_id = urldecode($rec_id);
		$tablename = $this->tablename;
		$fields = array(
			"id_surat",
			"id_index",
			"nomor_surat",
			"tanggal",
			"pengguna",
			"kepada",
			"tembusan",
			"disposisi",
			"subjek",
			"keterangan",
			"lampiran",
			"sifat",
			"persetujuan",
			"balasan",
			"status"
		);
		if ($value) {
			$db->where($rec_id, urldecode($value)); //select record based on field name
		} else {
			$db->where("index_surat.id_surat", $rec_id);; //select record based on primary key
		}
		$record = $db->getOne($tablename, $fields);
		if ($record) {
			$record['tanggal'] = relative_date($record['tanggal']);
			$page_title = $this->view->page_title = "Surat Ditolak";
			$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
			$this->view->report_title = $page_title;
			$this->view->report_layout = "report_layout.php";
			$this->view->report_paper_size = "A4";
			$this->view->report_orientation = "portrait";
		} else {
			if ($db->getLastError()) {
				$this->set_page_error();
			} else {
				$this->set_page_error("No record found");
			}
		}
		return $this->render_view("index_surat/log_surat_selesai.php", $record);
	}

	function selesaikan_surat($formdata = null){
		if($formdata){
			$db = $this->GetModel();
			$getData = $db->query("SELECT * FROM index_surat WHERE id_surat =".$formdata['id_surat']);
			$updateData = $db->query("UPDATE index_surat SET flow_status = 4, pengguna = '".USER_NAME."' WHERE id_surat = '".$getData[0]['id_surat']."'");
			$updateData2 = $db->query("UPDATE surat_masuk SET flow_status = 4, pengguna = '".USER_NAME."' WHERE id_index = '".$getData[0]['id_surat']."'");
			$log = $db->query("INSERT INTO log_surat (id_surat,pengguna,waktu,keterangan,lampiran,nomor_surat) VALUES ('".$formdata['id_surat']."','".USER_NAMA."','".date("Y-m-d H:i",time())."','Di Selesaikan','".$formdata['lampiran']."','".$formdata['nomor_surat']."')");
			$catatan = $db->query("INSERT INTO log_catatan_surat (id_surat,pengguna,catatan,lampiran,waktu,keterangan,nomor_surat) VALUES ('".$formdata['id_surat']."','".USER_NAMA."','".$formdata['keterangan']."','".$formdata['lampiran']."','".date("Y-m-d H:i",time())."','Di Selesaikan','".$formdata['nomor_surat']."')");
			
			if ($log && $catatan && $updateData && $updateData2) {
				$this->set_flash_msg("Surat Berhasil Diupdate", "success");
				return	$this->redirect("home");
			} else {
				$this->set_page_error();
			}
		}
		$page_title = $this->view->page_title = "Selesaikan Surat";
		$this->render_view("index_surat/selesaikan_surat.php");
	}
}
