<?php 
/**
 * Arsip Page Controller
 * @category  Controller
 */
class ArsipController extends SecureController{
	function __construct(){
		parent::__construct();
		$this->tablename = "arsip";
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
		$fields = array("nomor_arsip", 
			"tanggal_terima", 
			"asal_arsip", 
			"tanggal_arsip", 
			"kepada", 
			"subjek", 
			"sifat", 
			"id_arsip");
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				arsip.nomor_arsip LIKE ? OR 
				arsip.tanggal_terima LIKE ? OR 
				arsip.asal_arsip LIKE ? OR 
				arsip.tanggal_arsip LIKE ? OR 
				arsip.subjek LIKE ? OR 
				arsip.tembusan LIKE ? OR 
				arsip.sifat LIKE ? OR 
				arsip.id_arsip LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "arsip/search.php";
		}
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("arsip.id_arsip", ORDER_TYPE);
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
		$page_title = $this->view->page_title = "Index Arsip";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("arsip/list.php", $data); //render the full page
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
		$fields = array("id_arsip", 
			"nomor_arsip", 
			"tanggal_terima", 
			"asal_arsip", 
			"tanggal_arsip", 
			"kepada", 
			"tembusan", 
			"subjek", 
			"keterangan", 
			"lampiran", 
			"sifat");
		if($value){
			$db->where($rec_id, urldecode($value)); //select record based on field name
		}
		else{
			$db->where("arsip.id_arsip", $rec_id);; //select record based on primary key
		}
		$record = $db->getOne($tablename, $fields );
		if($record){
			$page_title = $this->view->page_title = "Index Arsip";
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
		return $this->render_view("arsip/view.php", $record);
	}
	/**
     * Insert new record to the database table
	 * @param $formdata array() from $_POST
     * @return BaseView
     */
	function add($formdata = null){
		if($formdata){
			$db = $this->GetModel();
			$tablename = $this->tablename;
			$request = $this->request;
			//fillable fields
			$fields = $this->fields = array("nomor_arsip","tanggal_terima","asal_arsip","tanggal_arsip","kepada","tembusan","subjek","keterangan","lampiran","sifat");
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'nomor_arsip' => 'required',
				'tanggal_terima' => 'required',
				'asal_arsip' => 'required',
				'kepada' => 'required',
				'subjek' => 'required',
				'keterangan' => 'required',
				'sifat' => 'required',
			);
			$this->sanitize_array = array(
				'nomor_arsip' => 'sanitize_string',
				'tanggal_terima' => 'sanitize_string',
				'asal_arsip' => 'sanitize_string',
				'tanggal_arsip' => 'sanitize_string',
				'kepada' => 'sanitize_string',
				'tembusan' => 'sanitize_string',
				'subjek' => 'sanitize_string',
				'lampiran' => 'sanitize_string',
				'sifat' => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
				$rec_id = $this->rec_id = $db->insert($tablename, $modeldata);
				if($rec_id){
					$this->set_flash_msg("Arsip berhasil ditambahkan", "success");
					return	$this->redirect("arsip");
				}
				else{
					$this->set_page_error();
				}
			}
		}
		$page_title = $this->view->page_title = "Tambah Arsip";
		$this->render_view("arsip/add.php");
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
		$fields = $this->fields = array("id_arsip","nomor_arsip","tanggal_terima","asal_arsip","tanggal_arsip","kepada","tembusan","subjek","keterangan","lampiran","sifat");
		if($formdata){
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'nomor_arsip' => 'required',
				'tanggal_terima' => 'required',
				'asal_arsip' => 'required',
				'kepada' => 'required',
				'subjek' => 'required',
				'keterangan' => 'required',
				'sifat' => 'required',
			);
			$this->sanitize_array = array(
				'nomor_arsip' => 'sanitize_string',
				'tanggal_terima' => 'sanitize_string',
				'asal_arsip' => 'sanitize_string',
				'tanggal_arsip' => 'sanitize_string',
				'kepada' => 'sanitize_string',
				'tembusan' => 'sanitize_string',
				'subjek' => 'sanitize_string',
				'lampiran' => 'sanitize_string',
				'sifat' => 'sanitize_string',
			);
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
				$db->where("arsip.id_arsip", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
				if($bool && $numRows){
					$this->set_flash_msg("Data berhasil diperbarui", "success");
					return $this->redirect("arsip");
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
						return	$this->redirect("arsip");
					}
				}
			}
		}
		$db->where("arsip.id_arsip", $rec_id);;
		$data = $db->getOne($tablename, $fields);
		$page_title = $this->view->page_title = "Edit  Arsip";
		if(!$data){
			$this->set_page_error();
		}
		return $this->render_view("arsip/edit.php", $data);
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
		$db->where("arsip.id_arsip", $arr_rec_id, "in");
		$bool = $db->delete($tablename);
		if($bool){
			$this->set_flash_msg("Data berhasil dihapus", "success");
		}
		elseif($db->getLastError()){
			$page_error = $db->getLastError();
			$this->set_flash_msg($page_error, "danger");
		}
		return	$this->redirect("arsip");
	}
	/**
     * List page records
     * @param $fieldname (filter record by a field) 
     * @param $fieldvalue (filter field value)
     * @return BaseView
     */
	function tb_arsip($fieldname = null , $fieldvalue = null){
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array("asal_arsip", 
			"subjek", 
			"sifat", 
			"tanggal_arsip", 
			"id_arsip");
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				arsip.asal_arsip LIKE ? OR 
				arsip.subjek LIKE ? OR 
				arsip.nomor_arsip LIKE ? OR 
				arsip.tanggal_terima LIKE ? OR 
				arsip.sifat LIKE ? OR 
				arsip.tanggal_arsip LIKE ? OR 
				arsip.tembusan LIKE ? OR 
				arsip.id_arsip LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "arsip/search.php";
		}
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("arsip.id_arsip", ORDER_TYPE);
		}
		$db->where("kepada OR tembusan LIKE '%".USER_NAME."%'");
		if($fieldname){
			$db->where($fieldname , $fieldvalue); //filter by a single field name
		}
		$tc = $db->withTotalCount();
		$records = $db->get($tablename, $pagination, $fields);
		$records_count = count($records);
		$total_records = intval($tc->totalCount);
		$page_limit = $pagination[1];
		$total_pages = ceil($total_records / $page_limit);
		if(	!empty($records)){
			foreach($records as &$record){
				$record['tanggal_arsip'] = relative_date($record['tanggal_arsip']);
			}
		}
		$data = new stdClass;
		$data->records = $records;
		$data->record_count = $records_count;
		$data->total_records = $total_records;
		$data->total_page = $total_pages;
		if($db->getLastError()){
			$this->set_page_error();
		}
		$page_title = $this->view->page_title = "Arsip";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("arsip/tb_arsip.php", $data); //render the full page
	}
	/**
     * List page records
     * @param $fieldname (filter record by a field) 
     * @param $fieldvalue (filter field value)
     * @return BaseView
     */
	function index_masuk($fieldname = null , $fieldvalue = null){
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array("nomor_arsip", 
			"asal_arsip", 
			"subjek", 
			"sifat", 
			"tanggal_arsip", 
			"id_arsip");
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				arsip.nomor_arsip LIKE ? OR 
				arsip.asal_arsip LIKE ? OR 
				arsip.subjek LIKE ? OR 
				arsip.sifat LIKE ? OR 
				arsip.tanggal_arsip LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "arsip/search.php";
		}
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("arsip.id_arsip", ORDER_TYPE);
		}
		$db->where("kepada OR tembusan LIKE '%".USER_NAME."%'");
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
		$page_title = $this->view->page_title = "Arsip Masuk";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("arsip/index_masuk.php", $data); //render the full page
	}
	/**
     * View record detail 
	 * @param $rec_id (select record by table primary key) 
     * @param $value value (select record by value of field name(rec_id))
     * @return BaseView
     */
	function masuk($rec_id = null, $value = null){
		$request = $this->request;
		$db = $this->GetModel();
		$rec_id = $this->rec_id = urldecode($rec_id);
		$tablename = $this->tablename;
		$fields = array("id_arsip", 
			"nomor_arsip", 
			"tanggal_terima", 
			"asal_arsip", 
			"tanggal_arsip", 
			"kepada", 
			"tembusan", 
			"subjek", 
			"keterangan", 
			"lampiran", 
			"sifat");
		if($value){
			$db->where($rec_id, urldecode($value)); //select record based on field name
		}
		else{
			$db->where("arsip.id_arsip", $rec_id);; //select record based on primary key
		}
		$record = $db->getOne($tablename, $fields );
		if($record){
			$page_title = $this->view->page_title = "Arsip Masuk";
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
		return $this->render_view("arsip/masuk.php", $record);
	}
}
