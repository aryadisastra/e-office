<?php 
/**
 * Jt Page Controller
 * @category  Controller
 */
class JtController extends SecureController{
	function __construct(){
		parent::__construct();
		$this->tablename = "jt";
	}
	/**
     * Custom list page
     * @return BaseView
     */
	function index(){
		$request = $this->request;
		$db = $this->GetModel();
		$pagination = null;
		$sqltext = "SELECT SQL_CALC_FOUND_ROWS  * FROM index_surat";
		$queryparams = null;
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("id_surat", ORDER_TYPE);
		}
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); //Get sql limit from url if not set on the sql command text
		$tc = $db->withTotalCount();
		$records = $db->query($sqltext, $pagination, $queryparams);
		$records_count = count($records);
		$total_records = intval($tc->totalCount);
		$page_limit = (!empty($pagination) ? $pagination[1] : 1);
		$total_pages = ceil($total_records / $page_limit);
		if(	!empty($records)){
			foreach($records as &$record){
				$record['tanggal'] = relative_date($record['tanggal']);
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
		$page_title = $this->view->page_title = "Jt";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("jt/list.php", $data); //render the full page
	}
}
