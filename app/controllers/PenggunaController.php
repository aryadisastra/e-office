<?php

/**
 * Pengguna Page Controller
 * @category  Controller
 */
class PenggunaController extends SecureController
{
	function __construct()
	{
		parent::__construct();
		$this->tablename = "pengguna";
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
			"id",
			"username",
			"nama",
			"alamat",
			"telp",
			"email",
			"parent_id",
			"role",
			"bagian"
		);
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if (!empty($request->search)) {
			$text = trim($request->search);
			$search_condition = "(
				pengguna.id LIKE ? OR 
				pengguna.username LIKE ? OR 
				pengguna.password LIKE ? OR 
				pengguna.nama LIKE ? OR 
				pengguna.alamat LIKE ? OR 
				pengguna.telp LIKE ? OR 
				pengguna.email LIKE ? OR 
				pengguna.parent_id LIKE ? OR 
				pengguna.role LIKE ?
			)";
			$search_params = array(
				"%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%", "%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			//template to use when ajax search
			$this->view->search_template = "pengguna/search.php";
		}
		if (!empty($request->orderby)) {
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		} else {
			$db->orderBy("pengguna.id", ORDER_TYPE);
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
		$page_title = $this->view->page_title = "Pengguna";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		foreach ($data->records as $key => $dt) {
			$dbs = $this->GetModel();
			$sqltext = "SELECT bagian FROM bagian WHERE id = " . $dt['bagian'];
			$queryparams = null;
			if (isset($dt['bagian'])) {
				$arr = $dbs->rawQuery($sqltext, $queryparams);
				foreach ($arr as $a) {
					$res = $a['bagian'];
					$data->records[$key]['bagian'] = $res;
				}
			}
		}
		// if (!$data['bagian']) {
		// 	$this->set_page_error();
		// }
		$this->render_view("pengguna/list.php", $data); //render the full page
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
			"id",
			"username",
			"nama",
			"alamat",
			"telp",
			"email",
			"parent_id",
			"role",
			"bagian"
		);
		if ($value) {
			$db->where($rec_id, urldecode($value)); //select record based on field name
		} else {
			$db->where("pengguna.id", $rec_id);; //select record based on primary key
		}
		$record = $db->getOne($tablename, $fields);
		if ($record) {
			$page_title = $this->view->page_title = "View  Pengguna";
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
		$dbs = $this->GetModel();
		$sqltext = "SELECT bagian FROM bagian WHERE id = " . $record['bagian'];
		$sqltext2 = "SELECT nama AS parent FROM  pengguna WHERE id = " . $record['parent_id'];
		$queryparams = null;
		if (isset($record['bagian'])) {
			$arr = $dbs->rawQuery($sqltext, $queryparams);
			foreach ($arr as $a) {
				$res = $a['bagian'];
				$record['bagian'] = $res;
			}
		}
		if (isset($record['parent_id'])) {
			$arr = $dbs->rawQuery($sqltext2, $queryparams);
			foreach ($arr as $a) {
				$res = $a['parent'];
				$record['parent_id'] = $res;
			}
		}
		return $this->render_view("pengguna/view.php", $record);
	}
	/**
	 * Insert new record to the database table
	 * @param $formdata array() from $_POST
	 * @return BaseView
	 */
	function add($formdata = null)
	{
		$db = $this->GetModel();
		if ($formdata) {
			$tablename = $this->tablename;
			$request = $this->request;
			//fillable fields
			$fields = $this->fields = array("username", "password", "nama", "alamat", "telp", "email", "parent_id", "role", "bagian");
			$postdata = $this->format_request_data($formdata);
			$cpassword = $postdata['confirm_password'];
			$password = $postdata['password'];
			if ($cpassword != $password) {
				$this->view->page_error[] = "Your password confirmation is not consistent";
			}
			$this->rules_array = array(
				'username' => 'required',
				'password' => 'required',
				'nama' => 'required',
				'alamat' => 'required',
				'telp' => 'required',
				'email' => 'required|valid_email',
				'parent_id' => 'required',
				'role' => 'required',
				'bagian' =>  'required'
			);
			$this->sanitize_array = array(
				'username' => 'sanitize_string',
				'nama' => 'sanitize_string',
				'alamat' => 'sanitize_string',
				'telp' => 'sanitize_string',
				'email' => 'sanitize_string',
				'parent_id' => 'sanitize_string',
				'role' => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			$password_text = $modeldata['password'];
			//update modeldata with the password hash
			$modeldata['password'] = $this->modeldata['password'] = password_hash($password_text, PASSWORD_DEFAULT);
			//Check if Duplicate Record Already Exit In The Database
			$db->where("username", $modeldata['username']);
			if ($db->has($tablename)) {
				$this->view->page_error[] = $modeldata['username'] . " Already exist!";
			}
			//Check if Duplicate Record Already Exit In The Database
			$db->where("email", $modeldata['email']);
			if ($db->has($tablename)) {
				$this->view->page_error[] = $modeldata['email'] . " Already exist!";
			}
			if ($this->validated()) {
				$rec_id = $this->rec_id = $db->insert($tablename, $modeldata);
				if ($rec_id) {
					$this->set_flash_msg("Data berhasil ditambahkan", "success");
					return	$this->redirect("pengguna");
				} else {
					$this->set_page_error();
				}
			}
		}
		$bagian['bagian'] = $db->get('bagian');
		if (!$bagian['bagian']) {
			$this->set_page_error();
		}
		$page_title = $this->view->page_title = "Tambah Pengguna";
		return $this->render_view("pengguna/add.php", $bagian);
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
		$fields = $this->fields = array("id", "username", "nama", "alamat", "telp", "parent_id", "role", "bagian");
		if ($formdata) {
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'username' => 'required',
				'nama' => 'required',
				'alamat' => 'required',
				'telp' => 'required',
				'parent_id' => 'required',
				'role' => 'required',
				'bagian' => 'required',
			);
			$this->sanitize_array = array(
				'username' => 'sanitize_string',
				'nama' => 'sanitize_string',
				'alamat' => 'sanitize_string',
				'telp' => 'sanitize_string',
				'parent_id' => 'sanitize_string',
				'role' => 'sanitize_string',
			);
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			//Check if Duplicate Record Already Exit In The Database
			if (isset($modeldata['username'])) {
				$db->where("username", $modeldata['username'])->where("id", $rec_id, "!=");
				if ($db->has($tablename)) {
					$this->view->page_error[] = $modeldata['username'] . " Already exist!";
				}
			}
			if ($this->validated()) {
				$db->where("pengguna.id", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
				if ($bool && $numRows) {
					$this->set_flash_msg("Data berhasil diperbarui", "success");
					return $this->redirect("pengguna");
				} else {
					if ($db->getLastError()) {
						$this->set_page_error();
					} elseif (!$numRows) {
						//not an error, but no record was updated
						$page_error = "No record updated";
						$this->set_page_error($page_error);
						$this->set_flash_msg($page_error, "warning");
						return	$this->redirect("pengguna");
					}
				}
			}
		}
		$db->where("pengguna.id", $rec_id);;
		$data = $db->getOne($tablename, $fields);
		$page_title = $this->view->page_title = "Edit  Pengguna";
		if (!$data) {
			$this->set_page_error();
		}

		$data['bagianOption'] = $db->get('bagian');
		if (!$data['bagianOption']) {
			$this->set_page_error();
		}
		return $this->render_view("pengguna/edit.php", $data);
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
		$db->where("pengguna.id", $arr_rec_id, "in");
		$bool = $db->delete($tablename);
		if ($bool) {
			$this->set_flash_msg("Data berhasil dihapus", "success");
		} elseif ($db->getLastError()) {
			$page_error = $db->getLastError();
			$this->set_flash_msg($page_error, "danger");
		}
		return	$this->redirect("pengguna");
	}

	function add_sign($formdata = null){
		if($formdata){
			$db = $this->GetModel();
		}
		$page_title = $this->view->page_title = "Tanda Tangan";
		$this->render_view("pengguna/add_sign.php");
	}

	function post_sign($formdata = null){
		
		$folderPath = IMG_DIR."signature/";
  
		$image_parts = explode(";base64,", $formdata['signature']);
			
		$image_type_aux = explode("image/", $image_parts[0]);
		
		$image_type = $image_type_aux[1];
		
		$image_base64 = base64_decode($image_parts[1]);
		// return print_r($folderPath);
		
		$file = $folderPath . uniqid() . '.'.$image_type;
		
		file_put_contents($file, $image_base64);
		echo "Signature Uploaded Successfully.";
	}
}
