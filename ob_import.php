<?php
/*
Plugin Name: Excel Import Script
Plugin URI: http://Ekkachai.net
Description: Excel Import Script
Author: Ekkachai Danwancihakul
Version: 1.0
Author URI: http://Ekkachai.net
*/

$files = $_FILES;

register_activation_hook(__FILE__,'ob_import_install');

if(is_admin())
{
  add_action('admin_menu', 'add_menu');
}

function add_menu(){
  add_menu_page( 'Import', 'West Bred Seeds', 'update_plugins', 'obimport', 'obimport_options');
  add_submenu_page( 'obimport', 'Import', 'Import', 'update_plugins', 'obimport','obimport_options');
  add_submenu_page( 'obimport', 'Export', 'Export', 'update_plugins', 'obimport&action=export_now&noheader=true','obimport_options');
  add_submenu_page( 'obimport', 'Logs', 'Logs', 'update_plugins', 'obimport&action=import_logs','obimport_options');
  
}


function obimport_options(){
  $action = isset($_POST['action']) ? $_POST['action'] : false;
  if ($_GET['action'])  $action = $_GET['action'];
  $debug  = isset($_POST['debug']) ? $_POST['debug'] : false;
  
  $tmp_file_name  = isset($_POST['file_name']) ? $_POST['file_name'] : false;
  switch ($action) {
    case 'export_now':
       export_now();
      break;
    case 'import_logs':
        import_logs();
      break;
    case 'import_now':
      $str = truncate_exist_rows();
      $data = excel_reader($tmp_file_name);
      import_now($data, $tmp_file_name);
      if ($debug == "1") print $str."<hr />";
      break;
    case 'view_data':
      $file_name = upload_file($files);
      $data = excel_reader($file_name);
      view_data($data, $file_name);
      break;
    default:
      init();
      break;
  }
}


function init(){
  include 'admin/index.php';
}

function view_data($data, $file_name){
  include 'admin/view_data.php';
}

function upload_file($files){
  include 'admin/model/upload_file.php';
  return $file_name;
}

function export_now(){
  include 'export/index.php';
}

function import_now($data, $tmp_file_name){
  include 'admin/model/import_now.php'; 
}

function import_logs(){
  include 'admin/model/import_logs.php'; 
}

function truncate_exist_rows(){
  include 'admin/model/truncate_rows.php';
  return $out_str;
}

function excel_reader($in_file){
  require_once 'library/excel_reader2.php';
  // $file_name = ABSPATH . "assets/".$in_file;
  $data = new Spreadsheet_Excel_Reader($file_name);
  return $data;
}

 /**
 * Function: sanitize
 * Returns a sanitized string
 *
 * Parameters:
 *     $string - The string to sanitize.
 *     $force_lowercase - Force the string to lowercase?
 *     $anal - If set to *true*, will remove all non-alphanumeric characters.
 */
function sanitize($string, $force_lowercase = true, $anal = false) {
    $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
                   "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
                   "â€”", "â€“", ",", "<", ".", ">", "/", "?");
    $clean = trim(str_replace($strip, "", strip_tags($string)));
    $clean = preg_replace('/\s+/', "-", $clean);
    $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
    return ($force_lowercase) ?
        (function_exists('mb_strtolower')) ?
            mb_strtolower($clean, 'UTF-8') :
            strtolower($clean) :
        $clean;
}
