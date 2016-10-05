<?php
session_start();

$include_path = 'includes/';
define('DEVELOPER_STATUS', true);
define('DEFAULT_PAGE_LENGTH', 10);

$root = "/hoved_p2/nco_cms/";

if(DEVELOPER_STATUS){
    error_reporting(E_ALL);
}else{
    error_reporting(0);
}

include $include_path.'classes/db_conf.php';
include $include_path.'classes/data_handler.php';
include $include_path.'classes/login_class.php';
include $include_path.'classes/events.php';

$DB=new DB();
$userClass=new User();
$user=$userClass->getUser();
$loggedIn=(count($user) > 0 ? true : false);
$Event=new Events();
$dataHandle=new DataHandler();


require 'lang/da_DK.php';
require $include_path . 'functions.php';
if($loggedIn)
    isAdminUser();

$timeStamp=FORMATTED_TIME;

// Array with icons used in CMS
$icons =
[
	'caret-down'	=> '<i class="fa fa-caret-down fa-fw" aria-hidden="true"></i>',
	'check'			=> '<i class="fa fa-check fa-fw" aria-hidden="true"></i>',
	'create'		=> '<i class="fa fa-plus fa-fw" aria-hidden="true"></i>',
	'edit'			=> '<i class="fa fa-pencil fa-fw" aria-hidden="true"></i>',
	'external-link'	=> '<i class="fa fa-external-link fa-fw" aria-hidden="true"></i>',
	'delete'		=> '<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>',
	'save'			=> '<i class="fa fa-floppy-o fa-fw" aria-hidden="true"></i>',
	'search'		=> '<i class="fa fa-search fa-fw" aria-hidden="true"></i>',
	'preview'		=> '<i class="fa fa-eye fa-fw" aria-hidden="true"></i>',
	'times'			=> '<i class="fa fa-times fa-fw" aria-hidden="true"></i>',
	'dashboard'		=> '<i class="fa fa-dashboard fa-fw" aria-hidden="true"></i>',
	'warning'		=> '<i class="fa fa-exclamation-triangle fa-fw" aria-hidden="true"></i>',
	'users'			=> '<i class="fa fa fa-users fa-fw" aria-hidden="true"></i>',
	'files'			=> '<i class="fa fa-files-o fa-fw" aria-hidden="true"></i>',
	'file-text'		=> '<i class="fa fa-file-text-o fa-fw" aria-hidden="true"></i>',
	'puzzle'		=> '<i class="fa fa-puzzle-piece fa-fw" aria-hidden="true"></i>',
	'sitemap'		=> '<i class="fa fa-sitemap fa-fw" aria-hidden="true"></i>',
	'comment'		=> '<i class="fa fa-comment fa-fw" aria-hidden="true"></i>',
	'comments'		=> '<i class="fa fa-comments-o fa-fw" aria-hidden="true"></i>',
	'sign-in'		=> '<i class="fa fa-sign-in fa-fw" aria-hidden="true"></i>',
	'sign-out'		=> '<i class="fa fa-sign-out fa-fw" aria-hidden="true"></i>',
	'sort'			=> '<i class="fa fa-sort fa-fw sortable-handle" aria-hidden="true"></i>',
	'sort-asc'		=> '<i class="fa fa-sort-amount-asc fa-fw" aria-hidden="true"></i>',
	'sort-desc'		=> '<i class="fa fa-sort-amount-desc fa-fw" aria-hidden="true"></i>',
	'user'			=> '<i class="fa fa-user fa-fw" aria-hidden="true"></i>',
	'previous'		=> '<i class="fa fa-angle-left fa-fw" aria-hidden="true"></i>',
	'next'			=> '<i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>',
	'history'		=> '<i class="fa fa-history fa-fw" aria-hidden="true"></i>',
	'settings'		=> '<i class="fa fa-cog fa-fw" aria-hidden="true"></i>',
	'link'			=> '<i class="fa fa-link fa-fw" aria-hidden="true"></i>'
];

// Array with buttons used in CMS
$buttons =
[
	'save'		=> 'btn btn-success',
	'create'	=> 'btn btn-success',
	'edit'		=> 'btn btn-warning btn-xs',
	'delete'	=> 'btn btn-danger btn-xs',
	'primary'	=> 'btn btn-primary',
	'default'	=> 'btn btn-default'
];

// Array with options for page length
$show_pages_length=[
    '1'=>1,
    '2'=>2,
    '3'=>3,
    '10'=>10,
    '25'=>25,
    '50'=>50,
    '100'=>100,
];

// Array with files for dynamic include (key: file names from view folder, value: Array with details to each file)
$view_files =
[
	'index'	=>
	[
		'icon'	=> $icons['dashboard'],
		'title' => DASHBOARD,
		'nav'	=> true,
        'acl'   => 10
	],

	'error' =>
	[
		'icon'	=> $icons['warning'],
		'title' => isset($_GET['status']) ? 'HTTP ' . $_GET['status'] : ERROR,
		'nav'	=> false,
        'acl'   => 10
	],

	'settings' =>
	[
		'icon'	=> $icons['settings'],
		'title'	=> SETTINGS,
		'nav'	=> true,
        'acl'   => 100
	],

	'users' =>
	[
		'icon'	=> $icons['users'],
		'title'	=> USERS,
		'nav'	=> true,
        'acl'   => 100
	],

	'user-create' =>
	[
		'icon'	=> $icons['users'],
		'title'	=> USERS,
		'nav'	=> false,
        'acl'   => 100
	],

	'user-edit' =>
	[
		'icon'	=> $icons['users'],
		'title'	=> USERS,
		'nav'	=> false,
        'acl'   => 100
	],

	'pages' =>
	[
		'icon'	=> $icons['files'],
		'title'	=> PAGES,
		'nav'	=> true,
        'acl'   => 100
	],

		'page-create' =>
		[
			'icon'	=> $icons['files'],
			'title'	=> PAGES,
			'nav'	=> false,
            'acl'   => 100
		],

		'page-edit' =>
		[
			'icon'	=> $icons['files'],
			'title'	=> PAGES,
			'nav'	=> false,
            'acl'   => 100
		],

		'page-content' =>
		[
			'icon'	=> $icons['file-text'],
			'title'	=> PAGE_CONTENT,
			'nav'	=> false,
            'acl'   => 100
		],

			'page-content-create' =>
			[
				'icon'	=> $icons['file-text'],
				'title'	=> PAGE_CONTENT,
				'nav'	=> false,
                'acl'   => 100
			],

			'page-content-edit' =>
			[
				'icon'	=> $icons['file-text'],
				'title'	=> PAGE_CONTENT,
				'nav'	=> false,
                'acl'   => 100
			],

	'page-functions' =>
	[
		'icon'	=> $icons['puzzle'],
		'title'	=> PAGE_FUNCTIONS,
		'nav'	=> true,
        'acl'   => 1000
	],

	'menus' =>
	[
		'icon'	=> $icons['sitemap'],
		'title'	=> MENUS,
		'nav'	=> true,
        'acl'   => 100
	],

		'menu-links' =>
		[
			'icon'	=> $icons['link'],
			'title'	=> LINKS,
			'nav'	=> false,
            'acl'   => 100
		],

			'menu-link-create' =>
			[
				'icon'	=> $icons['link'],
				'title'	=> LINKS,
				'nav'	=> false,
                'acl'   => 100
			],

			'menu-link-edit' =>
			[
				'icon'	=> $icons['link'],
				'title'	=> LINKS,
				'nav'	=> false,
                'acl'   => 100
			],

	'posts' =>
	[
		'icon'	=> $icons['comment'],
		'title'	=> BLOG_POSTS,
		'nav'	=> true,
        'acl'   => 10
	],

		'post-create' =>
		[
			'icon'	=> $icons['comment'],
			'title'	=> BLOG_POSTS,
			'nav'	=> false,
            'acl'   => 10
		],

		'post-edit' =>
		[
			'icon'	=> $icons['comment'],
			'title'	=> BLOG_POSTS,
			'nav'	=> false,
            'acl'   => 10
		],

		'comments' =>
		[
			'icon'	=> $icons['comments'],
			'title'	=> COMMENTS,
			'nav'	=> false,
            'acl'   => 10
		],

			'comment-create' =>
			[
				'icon'	=> $icons['comments'],
				'title'	=> COMMENTS,
				'nav'	=> false,
                'acl'   => 10
			],

			'comment-edit' =>
			[
				'icon'	=> $icons['comments'],
				'title'	=> COMMENTS,
				'nav'	=> false,
                'acl'   => 10
			],

	'events' =>
	[
		'icon'	=> $icons['history'],
		'title'	=> LOGBOOK,
		'nav'	=> true,
        'acl'   => 100
	]
];

if(isset($_GET['page']) && isset($view_files[$_GET['page']]))
    pageAccess($_GET['page']);