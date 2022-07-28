<?php

/**
 * Page Access Control
 * @category  RBAC Helper
 */
defined('ROOT') or exit('No direct script access allowed');
class ACL
{


	/**
	 * Array of user roles and page access 
	 * Use "*" to grant all access right to particular user role
	 * @var array
	 */
	public static $role_pages = array(
		'admin' =>
		array(
			'arsip' => array('list', 'view', 'add', 'edit', 'editfield', 'delete', 'tb_arsip', 'index_masuk', 'masuk', 'import_data'),
			'pengguna' => array('list','add_sign','post_sign', 'view', 'accountedit', 'accountview', 'add', 'edit', 'editfield', 'delete', 'import_data'),
			'sifat_surat' => array('list', 'view', 'add', 'edit', 'editfield', 'delete', 'import_data'),
			'balasan_surat' => array('list', 'view', 'add', 'edit', 'editfield', 'delete', 'import_data','kembalikan','buat_nomor'),
			'dis' => array('list', 'view'),
			'dis_fil' => array('list', 'view'),
			'index_surat' => array('list_keluar','list_disposisi','selesaikan_surat','log_surat_selesai','log_surat_ditolak','surat_ditolak','surat_selesai','buat_nomor','cek_signature','list', 'view', 'add', 'edit', 'editfield', 'delete', 'tb_masuk', 'tb_disposisi', 'index_masuk', 'index_keluar', 'index_disposisi', 'masuk', 'keluar', 'disposisi', 'import_data','cek_disposisi','log','cek_catatan','distribusi'),
			'nondis' => array('list', 'view'),
			'persetujuan_disposisi' => array('list', 'view', 'add', 'edit', 'editfield', 'delete', 'group_disposisi', 'import_data'),
			'sifat_persetujuan' => array('list', 'view', 'add', 'edit', 'editfield', 'delete', 'import_data'),
			'notnondis' => array('list', 'view'),
			'dis_final' => array('list', 'view'),
			'surat_masuk' => array('list','cek_signature', 'view','distribusi'),
			'report' => array('disposisi'),
		),

		'user' =>
		array(
			'arsip' => array('view', 'tb_arsip', 'index_masuk', 'masuk', 'import_data'),
			'balasan_surat' => array('list', 'add','kembalikan','buat_nomor','verifikasi'),
			'dis' => array('view'),
			'dis_fil' => array('view'),
			'index_surat' => array('list_print','list_keluar','list_disposisi','selesaikan_surat','log_surat_selesai','log_surat_ditolak','surat_ditolak','surat_selesai','buat_nomor','cek_signature','view', 'add', 'tb_masuk', 'tb_disposisi', 'index_masuk', 'index_keluar', 'index_disposisi', 'masuk', 'keluar', 'disposisi', 'import_data','cek_disposisi','log','cek_catatan','distribusi'),
			'nondis' => array('view'),
			'persetujuan_disposisi' => array('list', 'add'),
			'notnondis' => array('view'),
			'dis_final' => array('view'),
			'surat_masuk' => array('list','cek_signature', 'view','add','disposisi','distribusi'),
			'report' => array('disposisi'),
		),

		'kaur' =>
		array(
			'surat_masuk' => array('list_print','list','cek_signature', 'view','add','disposisi','distribusi','log'),
			'index_surat' => array('list_print','list_keluar','list_disposisi','selesaikan_surat','log_surat_selesai','log_surat_ditolak','surat_ditolak','surat_selesai','buat_nomor','cek_signature','view', 'add', 'tb_masuk', 'tb_disposisi', 'index_masuk', 'index_keluar', 'index_disposisi', 'masuk', 'keluar', 'disposisi', 'import_data','cek_disposisi','log','cek_catatan','distribusi'),
		),

		'superadmin' =>
		array(
			'arsip' => array('list', 'view','edit', 'editfield', 'delete', 'tb_arsip', 'index_masuk', 'masuk', 'import_data'),
			'pengguna' => array('list','post_sign', 'view', 'accountedit', 'accountview','edit', 'editfield', 'delete', 'import_data'),
			'sifat_surat' => array('list', 'view','edit', 'editfield', 'delete', 'import_data'),
			'balasan_surat' => array('list', 'view','edit', 'editfield', 'delete', 'import_data','kembalikan','buat_nomor'),
			'dis' => array('list', 'view'),
			'dis_fil' => array('list', 'view'),
			'index_surat' => array('list_keluar','list_disposisi','log_surat_selesai','log_surat_ditolak','surat_ditolak','surat_selesai','buat_nomor','cek_signature','list', 'view','edit', 'editfield', 'delete', 'tb_masuk', 'tb_disposisi', 'index_masuk', 'index_keluar', 'index_disposisi', 'masuk', 'keluar', 'disposisi', 'import_data','cek_disposisi','log','cek_catatan'),
			'nondis' => array('list', 'view'),
			'persetujuan_disposisi' => array('list', 'view','edit', 'editfield', 'delete', 'group_disposisi', 'import_data'),
			'sifat_persetujuan' => array('list', 'view','edit', 'editfield', 'delete', 'import_data'),
			'notnondis' => array('list', 'view'),
			'dis_final' => array('list', 'view'),
			'surat_masuk' => array('list','cek_signature', 'view'),
			'isi_disposisi' => array('list', 'view','add','edit','delete'),
			'setting' => array('list', 'view','add','edit','delete'),
			'report' => array('disposisi'),
		)
	);

	/**
	 * Current user role name
	 * @var string
	 */
	public static $user_role = null;

	/**
	 * pages to Exclude From Access Validation Check
	 * @var array
	 */
	public static $exclude_page_check = array("", "index", "home", "account", "info", "masterdetail", "bagian",'add','isidisposisi');

	/**
	 * Init page properties
	 */
	public function __construct()
	{
		if (!empty(USER_ROLE)) {
			self::$user_role = USER_ROLE;
		}
	}

	/**
	 * Check page path against user role permissions
	 * if user has access return AUTHORIZED
	 * if user has NO access return UNAUTHORIZED
	 * if user has NO role return NO_ROLE
	 * @return string
	 */
	public static function GetPageAccess($path)
	{
		$rp = self::$role_pages;
		if ($rp == "*") {
			return AUTHORIZED; // Grant access to any user
		} else {
			$path = strtolower(trim($path, '/'));

			$arr_path = explode("/", $path);
			$page = strtolower($arr_path[0]);

			//If user is accessing excluded access contrl pages
			if (in_array($page, self::$exclude_page_check)) {
				return AUTHORIZED;
			}

			$user_role = strtolower(USER_ROLE); // Get user defined role from session value
			if (array_key_exists($user_role, $rp)) {
				$action = (!empty($arr_path[1]) ? $arr_path[1] : "list");
				if ($action == "index") {
					$action = "list";
				}
				//Check if user have access to all pages or user have access to all page actions
				if ($rp[$user_role] == "*" || (!empty($rp[$user_role][$page]) && $rp[$user_role][$page] == "*")) {
					return AUTHORIZED;
				} else {
					if (!empty($rp[$user_role][$page]) && in_array($action, $rp[$user_role][$page])) {
						return AUTHORIZED;
					}
				}
				return FORBIDDEN;
			} else {
				//User does not have any role.
				return NOROLE;
			}
		}
	}

	/**
	 * Check if user role has access to a page
	 * @return Bool
	 */
	public static function is_allowed($path)
	{
		return (self::GetPageAccess($path) == AUTHORIZED);
	}
}
