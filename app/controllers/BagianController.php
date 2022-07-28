<?php

/**
 * Pengguna Page Controller
 * @category  Controller
 */
class BagianController extends SecureController
{
    function __construct()
    {
        parent::__construct();
        $this->tablename = "bagian";
    }

    //  * List page records
    //  * @param $fieldname (filter record by a field) 
    //  * @param $fieldvalue (filter field value)
    //  * @return BaseView

    public function index() {
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array(
			"id",
			"bagian"
		);
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if (!empty($request->search)) {
			$text = trim($request->search);
			$search_condition = "(
				bagian.id LIKE ? OR 
				bagian.bagian LIKE ?
			)";
			$search_params = array(
				"%$text%", "%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			//template to use when ajax search
			$this->view->search_template = "bagian/search.php";
		}
		if (!empty($request->orderby)) {
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		} else {
			$db->orderBy("bagian.id", ORDER_TYPE);
		}
		// if ($fieldname) {
		// 	$db->where($fieldname, $fieldvalue); //filter by a single field name
		// }
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
		$page_title = $this->view->page_title = "Bagian";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("bagian/index.php", $data); //render the full page
    }

    function edit($rec_id = null, $formdata = null)
	{
		$request = $this->request;
		$db = $this->GetModel();
		$this->rec_id = $rec_id;
		$tablename = $this->tablename;
		//editable fields
		$fields = $this->fields = array("id","bagian");
		if ($formdata) {
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'bagian' => 'required',
			);
			$this->sanitize_array = array(
				'bagian' => 'sanitize_string',
			);
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			//Check if Duplicate Record Already Exit In The Database
			if (isset($modeldata['username'])) {
				$db->where("bagian", $modeldata['bagian'])->where("id", $rec_id, "!=");
				if ($db->has($tablename)) {
					$this->view->page_error[] = $modeldata['bagian'] . " Already exist!";
				}
			}
			if ($this->validated()) {
				$db->where("bagian.id", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
				if ($bool && $numRows) {
					$this->set_flash_msg("Data berhasil diperbarui", "success");
					return $this->redirect("bagian");
				} else {
					if ($db->getLastError()) {
						$this->set_page_error();
					} elseif (!$numRows) {
						//not an error, but no record was updated
						$page_error = "No record updated";
						$this->set_page_error($page_error);
						$this->set_flash_msg($page_error, "warning");
						return	$this->redirect("bagian");
					}
				}
			}
		}
		$db->where("bagian.id", $rec_id);;
		$data = $db->getOne($tablename, $fields);
		$page_title = $this->view->page_title = "Edit  Bagian";
		if (!$data) {
			$this->set_page_error();
		}

		$data['bagianOption'] = $db->get('bagian');
		if (!$data['bagianOption']) {
			$this->set_page_error();
		}
		return $this->render_view("bagian/edit.php", $data);
	}
    function addBagian($formdata = null)
    {
        if ($formdata) {
            $db = $this->GetModel();
            $tablename = $this->tablename;
            $request = $this->request;
            //fillable fields
            $fields = $this->fields = array("bagian");
            $postdata = $this->format_request_data($formdata);

            $this->rules_array = array(
                'bagian' => 'required',
            );
            $this->sanitize_array = array(
                'bagian' => 'sanitize_string',
            );
            $this->filter_vals = true; //set whether to remove empty fields
            $modeldata = $this->modeldata = $this->validate_form($postdata);
            $db->where("bagian", $modeldata['bagian']);
            if ($db->has($tablename)) {
                $this->view->page_error[] = $modeldata['Bagian'] . " Bagian Sudah Ada!";
            }
            if ($this->validated()) {
                $rec_id = $this->rec_id = $db->insert($tablename, $modeldata);
                if ($rec_id) {
                    $this->set_flash_msg("Data berhasil ditambahkan", "success");
                    return    $this->redirect("pengguna");
                } else {
                    $this->set_page_error();
                }
            } else {
                $this->set_flash_msg("Data Gagal ditambahkan", "danger");
                return    $this->redirect("pengguna");
            }
        }

        $page_title = $this->view->page_title = "Pengguna";
        $this->redirect("pengguna");
    }
}
