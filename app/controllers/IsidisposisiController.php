<?php

/**
 * Pengguna Page Controller
 * @category  Controller
 */
class IsidisposisiController extends SecureController
{
    function __construct()
    {
        parent::__construct();
        $this->tablename = "isi_disposisi";
    }

    function index($fieldname = null, $fieldvalue = null)
	{
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array(
			"id",
			"isi_disposisi",
			"bagian",
			"status",
		);
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if (!empty($request->search)) {
			$text = trim($request->search);
			$search_condition = "(
				isi_disposisi.isi_disposisi LIKE ?
			)";
			$search_params = array("%$text%");
			//setting search conditions
			$db->where($search_condition, $search_params);
			//template to use when ajax search
			$this->view->search_template = "isi_disposisi/search.php";
		}
		$db->orderBy("isi_disposisi.id", ORDER_TYPE);
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
		$page_title = $this->view->page_title = "Master Isi Disposisi";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$dbs = $this->GetModel();
		$sqltext = "SELECT * FROM bagian";
		$queryparams = null;
		$arr = $dbs->rawQuery($sqltext, $queryparams);
		foreach ($arr as $a) {
			$data->bagianAdd[] = ['id' => $a['id'],'bagian' => $a['bagian']];
		}
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
		$this->render_view("isi_disposisi/list.php", $data); //render the full page
	}

    function addIsi($formdata = null)
    {
        if ($formdata) {
			$formdata['status']	= 1;
            $db = $this->GetModel();
            $tablename = $this->tablename;
            $request = $this->request;
            //fillable fields
            $fields = $this->fields = array('isi_disposisi','bagian','status');
            $postdata = $this->format_request_data($formdata);

            $this->rules_array = array(
                'bagian' => 'required',
                'isi_disposisi' => 'required',
            );
            $this->sanitize_array = array(
                'isi_disposisi' => 'sanitize_string',
            );
            $this->filter_vals = true; //set whether to remove empty fields
            $modeldata = $this->modeldata = $this->validate_form($postdata);
            if ($this->validated()) {
                $rec_id = $this->rec_id = $db->insert($tablename, $modeldata);
                if ($rec_id) {
                    $this->set_flash_msg("Data berhasil ditambahkan", "success");
                    return    $this->redirect("isidisposisi");
                } else {
                    $this->set_page_error();
                }
            } else {
                $this->set_flash_msg("Data Gagal ditambahkan", "danger");
                return    $this->redirect("isidisposisi");
            }
        }

        $page_title = $this->view->page_title = "Pengguna";
        $this->redirect("isidisposisi");
    }

	function view($rec_id = null, $value = null){
		$request = $this->request;
		$db = $this->GetModel();
		$rec_id = $this->rec_id = urldecode($rec_id);
		$tablename = $this->tablename;
		$fields = array("id", 
			"isi_disposisi", 
			"bagian",
			"status"
		);
		if($value){
			$db->where($rec_id, urldecode($value)); //select record based on field name
		}
		else{
			$db->where("isi_disposisi.id", $rec_id);; //select record based on primary key
		}
		$record = $db->getOne($tablename, $fields );
		if($record){
			$page_title = $this->view->page_title = "View  Isi Disposisi";
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
		$dbs = $this->GetModel();
		$sqltext = "SELECT bagian FROM bagian WHERE id = " . $record['bagian'];
		$queryparams = null;
		if (isset($record['bagian'])) {
			$arr = $dbs->rawQuery($sqltext, $queryparams);
			foreach ($arr as $a) {
				$res = $a['bagian'];
				$record['bagian'] = $res;
			}
		}
		return $this->render_view("isi_disposisi/view.php", $record);
	}

	function edit($rec_id = null, $formdata = null){
		$request = $this->request;
		$db = $this->GetModel();
		$this->rec_id = $rec_id;
		$tablename = $this->tablename;
		 //editable fields
		$fields = $this->fields = array("id","isi_disposisi","bagian","status");
		if($formdata){
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'isi_disposisi' => 'required',
				'bagian' => 'required',
				'status' => 'required',
			);
			$this->sanitize_array = array(
				'isi_disposisi' => 'sanitize_string'
			);
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
				$db->where("isi_disposisi.id", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
				if($bool && $numRows){
					$this->set_flash_msg("Data berhasil diperbarui", "success");
					return $this->redirect("isidisposisi");
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
						return	$this->redirect("isidisposisi");
					}
				}
			}
		}
		$db->where("isi_disposisi.id", $rec_id);;
		$data = $db->getOne($tablename, $fields);
		$page_title = $this->view->page_title = "Edit  Isi Disposisi";
		if(!$data){
			$this->set_page_error();
		}

		$data['bagianOption'] = $db->get('bagian');
		if (!$data['bagianOption']) {
			$this->set_page_error();
		}
		return $this->render_view("isi_disposisi/edit.php", $data);
	}

	function delete($rec_id = null)
	{
		Csrf::cross_check();
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$this->rec_id = $rec_id;
		//form multiple delete, split record id separated by comma into array
		$arr_rec_id = array_map('trim', explode(",", $rec_id));
		$db->where("isi_disposisi.id", $arr_rec_id, "in");
		$bool = $db->delete($tablename);
		if ($bool) {
			$this->set_flash_msg("Data berhasil dihapus", "success");
		} elseif ($db->getLastError()) {
			$page_error = $db->getLastError();
			$this->set_flash_msg($page_error, "danger");
		}
		return	$this->redirect("pengguna");
	}
}
