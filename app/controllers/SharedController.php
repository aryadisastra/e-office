<?php

/**
 * SharedController Controller
 * @category  Controller / Model
 */
class SharedController extends BaseController
{

	/**
	 * arsip_kepada_option_list Model Action
	 * @return array
	 */
	function arsip_kepada_option_list()
	{
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT username AS value,username AS label FROM pengguna WHERE bagian != '' ORDER BY id ASC";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
	 * arsip_tembusan_option_list Model Action
	 * @return array
	 */
	function arsip_tembusan_option_list()
	{
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT username AS value,username AS label FROM pengguna WHERE bagian != '' ORDER BY id ASC";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
	 * arsip_sifat_option_list Model Action
	 * @return array
	 */
	function arsip_sifat_option_list()
	{
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT sifat_surat AS value,sifat_surat AS label FROM sifat_surat ORDER BY id";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
	 * pengguna_username_value_exist Model Action
	 * @return array
	 */
	function pengguna_username_value_exist($val)
	{
		$db = $this->GetModel();
		$db->where("username", $val);
		$exist = $db->has("pengguna");
		return $exist;
	}

	function bagian_exist($val)
	{
		$db = $this->GetModel();
		$db->where("bagian", $val);
		$exist = $db->has("bagian");
		return $exist;
	}

	/**
	 * pengguna_email_value_exist Model Action
	 * @return array
	 */
	function pengguna_email_value_exist($val)
	{
		$db = $this->GetModel();
		$db->where("email", $val);
		$exist = $db->has("pengguna");
		return $exist;
	}

	/**
	 * index_surat_nomor_surat_value_exist Model Action
	 * @return array
	 */
	function index_surat_nomor_surat_value_exist($val)
	{
		$db = $this->GetModel();
		$db->where("nomor_surat", $val);
		$exist = $db->has("index_surat");
		return $exist;
	}

	/**
	 * index_surat_kepada_option_list Model Action
	 * @return array
	 */
	function index_surat_kepada_option_list()
	{
		$db = $this->GetModel();
		$ownBagian = (int) USER_BAGIAN - 1;
		$ownID = (int) USER_ID;
		$sqltext = "SELECT  DISTINCT username AS value ,username AS label FROM pengguna WHERE parent_id = '".USER_ID."' ORDER BY id DESC";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	function index_surat_kepada_kasi_option_list()
	{
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT username AS value ,username AS label FROM pengguna WHERE bagian = 2 ORDER BY id DESC";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	function index_surat_kepada_kaur_option_list()
	{
		$db = $this->GetModel();
		$ownBagian = (int) USER_BAGIAN - 1;
		$ownID = (int) USER_ID;
		$sqltext = "SELECT  DISTINCT username AS value ,username AS label FROM pengguna WHERE bagian = 0 AND username != 'OPERATOR' ORDER BY id DESC";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	function index_surat_kepada_tahap1_option_list()
	{
		$db = $this->GetModel();
		$ownBagian = (int) USER_BAGIAN - 1;
		$ownID = (int) USER_ID;
		$sqltext = "SELECT  DISTINCT username AS value ,username AS label FROM pengguna WHERE id = '".USER_PARENT."' ORDER BY id DESC";
		if (USER_BAGIAN == 1) $sqltext = "SELECT  DISTINCT username AS value ,username AS label FROM pengguna WHERE bagian = 2 ORDER BY id DESC";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	function index_surat_tembusan_new_option_list()
	{
		$db = $this->GetModel();
		$ownBagian = (int) USER_BAGIAN - 1;
		$ownID = (int) USER_ID;
		$getNextBag = (int) USER_BAGIAN + 1;
		$sqltext = "SELECT  DISTINCT username AS value ,username AS label FROM pengguna WHERE id != '".USER_PARENT."' AND bagian = '".$getNextBag."' ORDER BY id DESC";
		if (USER_BAGIAN == 1) $sqltext = "SELECT  DISTINCT username AS value ,username AS label FROM pengguna WHERE bagian = 2 ORDER BY id DESC";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	function index_surat_tembusan_new_disposisi_option_list()
	{
		$db = $this->GetModel();
		$ownBagian = (int) USER_BAGIAN - 1;
		$ownID = (int) USER_ID;
		$getNextBag = (int) USER_BAGIAN - 1;
		$sqltext = "SELECT  DISTINCT username AS value ,username AS label FROM pengguna WHERE bagian = '".$getNextBag."' ORDER BY id DESC";
		if (USER_BAGIAN == 1) $sqltext = "SELECT  DISTINCT username AS value ,username AS label FROM pengguna WHERE bagian = 2 ORDER BY id DESC";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
	 * index_surat_tembusan_option_list Model Action
	 * @return array
	 */
	function index_surat_tembusan_option_list()
	{
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT username AS value,username AS label FROM pengguna where role != 'admin' ORDER BY id DESC";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
	 * index_surat_disposisi_option_list Model Action
	 * @return array
	 */
	function index_surat_disposisi_option_list()
	{
		$db = $this->GetModel();
		$ownBagian = (int) USER_BAGIAN - 1;
		$sqltext = 'SELECT  DISTINCT username AS value,username AS label FROM pengguna where bagian = "'.$ownBagian.'" ORDER BY id DESC';
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	function index_surat_dircab_option_list()
	{
		$db = $this->GetModel();
		$sqltext = 'SELECT  DISTINCT username AS value,username AS label FROM pengguna where bagian = 5 OR bagian = 4 ORDER BY id DESC';
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
	 * index_surat_sifat_option_list Model Action
	 * @return array
	 */
	function index_surat_sifat_option_list()
	{
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT sifat_surat AS value,sifat_surat AS label FROM sifat_surat ORDER BY id";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
	 * persetujuan_disposisi_persetujuan_option_list Model Action
	 * @return array
	 */
	function persetujuan_disposisi_persetujuan_option_list()
	{
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT persetujuan AS value,persetujuan AS label FROM sifat_persetujuan ORDER BY id";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
	 * getcount_suratmasuk Model Action
	 * @return Value
	 */
	function getcount_suratmasuk()
	{
		$db = $this->GetModel();
		$sqltext = "SELECT COUNT(*) AS num FROM surat_masuk where can_view LIKE '%".USER_NAME."%' AND status >= 4";
		if(USER_BAGIAN == 6) $sqltext = "SELECT COUNT(*) AS num FROM surat_masuk ";
		$queryparams = null;
		$val = $db->rawQueryValue($sqltext, $queryparams);

		if (is_array($val)) {
			return $val[0];
		}
		return $val;
	}

	function getcount_suratprinted()
	{
		$db = $this->GetModel();
		$sqltext = "SELECT COUNT(*) AS num FROM index_surat where can_view LIKE '%".USER_NAME."%' AND status = 6 AND is_printed = 0";
		if(USER_BAGIAN == 6) $sqltext = "SELECT COUNT(*) AS num FROM surat_masuk ";
		$queryparams = null;
		$val = $db->rawQueryValue($sqltext, $queryparams);

		if (is_array($val)) {
			return $val[0];
		}
		return $val;
	}

	/**
	 * getcount_suratkeluar Model Action
	 * @return Value
	 */
	function getcount_suratkeluar()
	{
		$db = $this->GetModel();
		$bagian = USER_BAGIAN;
		$sqltext = "SELECT COUNT(*) AS num FROM index_surat where can_view LIKE '%".USER_NAME."%' AND status = ".$bagian." AND status != 404";
		if(USER_BAGIAN == 6) $sqltext = "SELECT COUNT(*) AS num FROM index_surat";
		$queryparams = null;
		$val = $db->rawQueryValue($sqltext, $queryparams);

		if (is_array($val)) {
			return $val[0];
		}
		return $val;
	}

	/**
	 * getcount_disposisi Model Action
	 * @return Value
	 */
	function getcount_disposisi()
	{
		$db = $this->GetModel();
		$role = USER_BAGIAN;
		if($role != null) $sqltext = "SELECT COUNT(*) AS num FROM surat_masuk WHERE status = ".USER_BAGIAN."  AND can_view LIKE '%".USER_NAME."%'";
		if($role == null) $sqltext = "SELECT COUNT(*) AS num FROM surat_masuk ";
		if(USER_BAGIAN == 6) $sqltext = "SELECT COUNT(*) AS num FROM surat_masuk ";
		$queryparams = null;
		$val = $db->rawQueryValue($sqltext, $queryparams);

		if (is_array($val)) {
			return $val[0];
		}
		return $val;
	}

	/**
	 * getcount_arsip Model Action
	 * @return Value
	 */
	function getcount_arsip()
	{
		$db = $this->GetModel();
		$sqltext = "SELECT COUNT(*) AS num FROM arsip";
		if(USER_BAGIAN != 6) $sqltext = "SELECT COUNT(*) AS num FROM arsip WHERE kepada OR tembusan LIKE '%" . USER_NAME . "%'";
		$queryparams = null;
		$val = $db->rawQueryValue($sqltext, $queryparams);

		if (is_array($val)) {
			return $val[0];
		}
		return $val;
	}
	
	function getIsiDisposisi()
	{
		$db = $this->GetModel();
		$sqltext = "SELECT * FROM isi_disposisi WHERE bagian = ".USER_BAGIAN;
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	function parentUser()
	{
		$db = $this->GetModel();
		$sqltext = "SELECT * FROM pengguna";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	function tableDisposisi($id)
	{
		$db = $this->GetModel();
		$sqltext = "SELECT * FROM disposisi_surat WHERE id_surat = ".$id;
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	function tableSignature($id)
	{
		$db = $this->GetModel();
		$sqltext = "SELECT * FROM signature WHERE id_surat = ".$id;
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	function getSignature($id, $user)
	{
		$db = $this->GetModel();
		$sqltext = "SELECT * FROM signature WHERE pengguna = ".$user." AND id_surat = ".$id;
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	function getUserData($nama)
	{
		$db = $this->GetModel();
		$sqltext = "SELECT * FROM pengguna WHERE nama = '$nama'";
		// return var_dump($sqltext);
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}
}
