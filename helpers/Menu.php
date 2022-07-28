<?php

/**
 * Menu Items
 * All Project Menu
 * @category  Menu List
 */

class Menu extends BaseController
{


	public static $navbartopleft = array(
		array(
			'path' => 'home',
			'label' => 'BERANDA',
			'icon' => '<i class="fa fa-university "></i>'
		),

		array(
			'path' => 'surat_masuk',
			'label' => 'SURAT MASUK',
			'icon' => '<i class="fa fa-envelope "></i>'
		),

		array(
			'path' => 'index_surat/index_keluar',
			'label' => 'SURAT KELUAR',
			'icon' => '<i class="fa fa-send "></i>'
		),

		array(
			'path' 		=> 'setting',
			'label' 	=> 'PENGATURAN',
			'submenu' 	=>	[
								[
									'path'	=>	'Pengguna',
									'label'	=>	'Pengguna',
									'icon'	=>	'<i class="fa fa-group "></i>',
								],
								[
									'path'	=>	'Bagian',
									'label'	=>	'Bagian',
									'icon'	=>	'<i class="fa fa-sitemap "></i>',
								],
								[
									'path'	=>	'isiDisposisi',
									'label'	=>	'Isi Disposisi',
									'icon'	=>	'<i class="fa fa-share "></i>',
								],
							],
			'icon' 		=> '<i class="fa fa-gear "></i>'
		)
	);



	public static $role = array(
		array(
			"value" => "admin",
			"label" => "admin",
		),
		array(
			"value" => "user",
			"label" => "user",
		),
	);
}
