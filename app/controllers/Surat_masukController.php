<?php 
/**
 * Surat_masuk Page Controller
 * @category  Controller
 */
class Surat_masukController extends SecureController{
	function __construct(){
		parent::__construct();
		$this->tablename = "surat_masuk";
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
			"id_surat", 
			"pengguna", 
			"subjek", 
			"sifat",  
			"dari", 
			"tanggal");
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				surat_masuk.nomor_surat LIKE ? OR 
				surat_masuk.pengguna LIKE ? OR 
				surat_masuk.subjek LIKE ? OR 
				surat_masuk.sifat LIKE ? OR 
				surat_masuk.tanggal LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "surat_masuk/search.php";
		}
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("surat_masuk.id_surat", ORDER_TYPE);
		}
		if(USER_BAGIAN != 6) $db->where("can_view LIKE '%".USER_NAME."%' AND status >= 4");
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
		$page_title = $this->view->page_title = "Surat Masuk";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("surat_masuk/list.php", $data); //render the full page
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
		$fields = array("nomor_surat", 
			"id_surat", 
			"status", 
			"tanggal", 
			"pengguna", 
			"kepada", 
			"tembusan", 
			"disposisi", 
			"subjek",    
			"lampiran",
			"dari", 
			"sifat");
		if($value){
			$db->where($rec_id, urldecode($value)); //select record based on field name
		}
		else{
			$db->where("surat_masuk.id_surat", $rec_id);; //select record based on primary key
		}
		$record = $db->getOne($tablename, $fields );
		if($record){
			$record['can_disposisi'] = 'no';
			$getval = $db->query("SELECT * FROM surat_masuk where id_surat = ".$rec_id." AND kepada LIKE '%".USER_NAME."%'");
			$getvaltwo = $db->query("SELECT * FROM disposisi where id_surat = ".$rec_id." AND (kepada = '".USER_NAME."' AND is_done = 1)");
			if($getval) $record['can_disposisi'] = 'yes';
			if($getvaltwo) $record['can_disposisi'] = 'no';
			$record['tanggal'] = relative_date($record['tanggal']);
			$page_title = $this->view->page_title = "Surat Masuk";
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
		return $this->render_view("surat_masuk/view.php", $record);
	}

	function add($formdata = null)
	{
		if ($formdata) {
			$db = $this->GetModel();
			$tablename = $this->tablename;
			$request = $this->request;
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'subjek' => 'required',
				'sifat' => 'required',
				'pengguna' => 'required',
				'nomor_surat' => 'required',
				'tanggal' => 'required',
				'dari' => 'required',
			);
			$this->sanitize_array = array(
				'tanggal' => 'sanitize_string',
				'pengguna' => 'sanitize_string',
				'tembusan' => 'sanitize_string',
				'subjek' => 'sanitize_string',
				'lampiran' => 'sanitize_string',
				'sifat' => 'sanitize_string',
				'dari' => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			$modeldata['kepada']	=	'kasubditbinkom';
			$getlast_id = $db->query("SELECT MAX(id_surat) AS last FROM surat_masuk");
			$fixedId = 1;
			if($getlast_id != null) $fixedId = (int) $getlast_id[0]['last'] + 1;
			$db->where("nomor_surat", $modeldata['nomor_surat']);
			if ($db->has($tablename)) {
				$this->view->page_error[] = $modeldata['nomor_surat'] . " Already exist!";
			}
			if ($this->validated()) {
				$can_view = ''.USER_NAME.', kasubditbinkom';
				$rec_id = $db->query("INSERT INTO surat_masuk (nomor_surat,dari,tanggal,pengguna,kepada,subjek,lampiran,sifat,can_view,status) 
									  VALUES
									  ('".$postdata['nomor_surat']."','".$postdata['dari']."','".$postdata['tanggal']."','".USER_NAMA."','kasubditbinkom','".$postdata['subjek']."','".$postdata['lampiran']."','".$postdata['sifat']."','".$can_view."','4') ");
				$log = $db->query("INSERT INTO log_surat (id_surat,pengguna,waktu,keterangan,lampiran,sumber,nomor_surat) VALUES ('".$fixedId."','".USER_NAMA."','".date("Y-m-d H:i",time())."','Di Input','".$postdata['lampiran']."',1,'".$postdata['nomor_surat']."')");
				$catatan = $db->query("INSERT INTO log_catatan_surat (id_surat,pengguna,catatan,waktu,keterangan,lampiran,sumber,nomor_surat) VALUES ('".$fixedId."','".USER_NAMA."','".$postdata['keterangan']."','".date("Y-m-d H:i",time())."','Di Input','".$postdata['lampiran']."',1,'".$postdata['nomor_surat']."')");
				if ($rec_id && $log && $catatan) {
					$this->set_flash_msg("Surat Berhasil Di Input", "success");
					return	$this->redirect("home");
				} else {
					$this->set_page_error();
				}
			}
		}
		$page_title = $this->view->page_title = "Buat Surat Masuk";
		$this->render_view("surat_masuk/add.php");
	}

	function disposisi($formdata = null){
		if($formdata){
			$postdata = $this->format_request_data($formdata);
			$db = $this->GetModel();
			$getData = $db->query("SELECT * FROM surat_masuk WHERE id_surat =".$formdata['id_surat']);
			$nomor_surat = $getData[0]['nomor_surat'];
			$fixStatus = (int) $getData[0]['status'] - 1;
			$updateView = $getData[0]['can_view'].', '.$postdata['kepada'].', '.$postdata['tembusan'] ;
			if(USER_BAGIAN == 3) $updateData = $db->query("UPDATE surat_masuk SET status = '".$fixStatus."', pengguna = '".USER_NAME."', kepada = '".$postdata['kepada']."', can_view = '".$updateView."' WHERE id_surat = '".$getData[0]['id_surat']."'");
			if(USER_BAGIAN == 4)$updateData = $db->query("UPDATE surat_masuk SET status = '".$fixStatus."', pengguna = '".USER_NAME."', kepada = '".$postdata['kepada']."', can_view = '".$updateView."' WHERE id_surat = '".$getData[0]['id_surat']."'");
			$log = $db->query("INSERT INTO log_surat (id_surat,pengguna,waktu,keterangan,sumber,nomor_surat) VALUES ('".$formdata['id_surat']."','".USER_NAMA."','".date("Y-m-d H:i",time())."','Di Disposisikan',1,'".$getData[0]['nomor_surat']."')");
			$catatan = $db->query("INSERT INTO log_catatan_surat (id_surat,pengguna,catatan,waktu,keterangan,sumber,nomor_surat) VALUES ('".$formdata['id_surat']."','".USER_NAMA."','".$formdata['keterangan']."','".date("Y-m-d H:i",time())."','Di Disposisikan',1,'".$getData[0]['nomor_surat']."')");
			$array_kepada = explode(",",$postdata['kepada']);
			$isi_disposisi = $formdata['disposisi'];
			$fix_disposisi = implode(", ",$isi_disposisi); 
			if(USER_BAGIAN != 3) $postdata['disposisi'];
			foreach($array_kepada as $dt)
			{
			$disposisi = $db->query("INSERT INTO disposisi_surat (id_surat,pengguna,isi_disposisi,waktu,nomor_surat,sumber)
									VALUES ('".$formdata['id_surat']."','".$dt."','".$fix_disposisi."','".date("Y-m-d H:i",time())."','".$nomor_surat."',1)");
			$surat_disposisi = $db->query("INSERT INTO disposisi (id_surat,kepada) VALUES ('".$formdata['id_surat']."','".$dt."')");
			}
			if(USER_BAGIAN == 3) $db->query("UPDATE disposisi SET is_done = 1 WHERE kepada = '".USER_NAME."' AND id_surat =".$formdata['id_surat']);
			if ($log && $catatan && $disposisi)  {
				$this->set_flash_msg("Surat Berhasil Diupdate", "success");
				return	$this->redirect("home");
			} else {
				$this->set_page_error();
			}
		}
		$page_title = $this->view->page_title = "Disposisikan";
		$this->render_view("surat_masuk/disposisi.php");
	}

	function distribusi($formdata = null){
		if($formdata){
			$postdata = $this->format_request_data($formdata);
			$db = $this->GetModel();
			$getData = $db->query("SELECT * FROM surat_masuk WHERE id_surat =".$formdata['id_surat']);
			$nomor_surat = $getData[0]['nomor_surat'];
			$fixStatus = (int) $getData[0]['status'] - 1;
			$updateView = $getData[0]['can_view'].', '.$postdata['kepada'].', '.$postdata['tembusan'] ;
			if(USER_BAGIAN == 3) $updateData = $db->query("UPDATE surat_masuk SET status = '".$fixStatus."', pengguna = '".USER_NAME."', kepada = '".$postdata['kepada']."', can_view = '".$updateView."' WHERE id_surat = '".$getData[0]['id_surat']."'");
			if(USER_BAGIAN == 4)$updateData = $db->query("UPDATE surat_masuk SET status = '".$fixStatus."', pengguna = '".USER_NAME."', kepada = '".$postdata['kepada']."', can_view = '".$updateView."' WHERE id_surat = '".$getData[0]['id_surat']."'");
			$log = $db->query("INSERT INTO log_surat (id_surat,pengguna,waktu,keterangan,sumber,nomor_surat) VALUES ('".$formdata['id_surat']."','".USER_NAMA."','".date("Y-m-d H:i",time())."','Di Disposisikan',1,'".$getData[0]['nomor_surat']."')");
			$catatan = $db->query("INSERT INTO log_catatan_surat (id_surat,pengguna,catatan,waktu,keterangan,sumber,nomor_surat) VALUES ('".$formdata['id_surat']."','".USER_NAMA."','".$formdata['keterangan']."','".date("Y-m-d H:i",time())."','Di Disposisikan',1,'".$getData[0]['nomor_surat']."')");
			$array_kepada = explode(",",$postdata['kepada']);
			$isi_disposisi = $formdata['disposisi'];
			$fix_disposisi = implode(", ",$isi_disposisi); 
			if(USER_BAGIAN != 3) $postdata['disposisi'];
			foreach($array_kepada as $dt)
			{
			$disposisi = $db->query("INSERT INTO disposisi_surat (id_surat,pengguna,isi_disposisi,waktu,nomor_surat,sumber)
									VALUES ('".$formdata['id_surat']."','".$dt."','".$fix_disposisi."','".date("Y-m-d H:i",time())."','".$nomor_surat."',1)");
			$surat_disposisi = $db->query("INSERT INTO disposisi (id_surat,kepada) VALUES ('".$formdata['id_surat']."','".$dt."')");
			}
			if(USER_BAGIAN == 3) $db->query("UPDATE disposisi SET is_done = 1 WHERE kepada = '".USER_NAME."' AND id_surat =".$formdata['id_surat']);
			if ($log && $catatan && $disposisi)  {
				$this->set_flash_msg("Surat Berhasil Diupdate", "success");
				return	$this->redirect("home");
			} else {
				$this->set_page_error();
			}
		}
		$page_title = $this->view->page_title = "Distribusikan";
		$this->render_view("surat_masuk/distribusi.php");
	}
}
