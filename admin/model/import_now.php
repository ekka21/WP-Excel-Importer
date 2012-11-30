<?php   
/****************************************************************
* Insert (title, description) to wp_posts and return new id
* Insert new id(post_id) 
* 
* Insert new id(object_id) to wp_term_relationships 
*  - get state ids from wp_terms
*  - term_taxonomy_id 

  Array
        (
            [1] => Title
            [2] => Description
            [3] => PDF Linnk
            [4] => Class
            [5] => Restricted Use Licence
            [6] => Plant variety Protection
            [7] => PVP#
            [8] => State
        )
****************************************************************
*/

global $wpdb;
$raw_data = $data->dumptoarray(); 
$table  = $wpdb->prefix."posts";
$cnt = 1;
foreach($raw_data as $r)
{
  if ($cnt > 1)
  {
    $title         = trim($r[1]);
    $content       = trim($r[2]);
    $pdf_link      = trim($r[3]);
    $class         = trim($r[4]);
    $rul           = trim($r[5]);
    $pvp           = trim($r[6]);
    $pvp_number    = trim($r[7]);
    $patent        = trim($r[8]);
    $patent_number = trim($r[9]);
    $patent_number_link = trim($r[10]);
    $states        = trim($r[11]);
    $arr           = array(
                       'post_name'     =>  sanitize($title),
                       'post_title'    =>  $title,
                       'post_content'  =>  $content,
                       'post_type'     =>  'product',
                       'post_author'   =>  '1',
                       'post_status'   =>  'publish',
                       'post_date'     =>   date('Y-m-d g:i:s'),
                       'post_date_gmt' =>   date('Y-m-d g:i:s'),                 
                    );  
    //instert post data of products                             
    $wpdb->insert($table, $arr);
    $id = $wpdb->insert_id;
    //insert post meta of states
    insert_wp_term_relationships($wpdb, $id, $states);
    
    //insert the less of post meta
    $postmeta = array(
                  'class'           => $class,
                  'rul'             => $rul,
                  'pvp'             => $pvp,
                  'pvp_number'      => $pvp_number,
                  'pdf_link'        => $pdf_link,
                  'patent'          => $patent,
                  'patent_number'   => $patent_number,
                  'patent_number_link'   => $patent_number_link,
                );
    insert_wp_postmeta($wpdb, $id, $postmeta);
  }

  $cnt++;
}
add_ob_import_logs($wpdb, $tmp_file_name);
$wpdb->print_error();
$wpdb->flush();



/**
* Add states to associated post id
* @params db, id, array
* @return Null
*/
function insert_wp_term_relationships($wpdb, $id, $in_args){
  $args = explode(',' , $in_args);
  $table = $wpdb->prefix."terms";
  foreach($args as $a)
  {
    $q = "SELECT term_id FROM ".$table." WHERE name = '".strtoupper($a)."'";
    $terms = $wpdb->get_results($wpdb->prepare($q));
    foreach($terms as $t)
    {
      $arr = array(
              'object_id'         => $id,
              'term_taxonomy_id'  =>  $t->term_id,
      );
      $wpdb->insert($wpdb->prefix.'term_relationships', $arr);
    }
  }
}

/**
* Add meta_value to associated post id
* @params db, id, postmeta_array
* @return Null
*/
function insert_wp_postmeta($wpdb, $id, $postmeta){
  $table = $wpdb->prefix."postmeta";
  $class = get_id_post_type($wpdb, 'class', $postmeta['class']);
  $rul   = get_id_post_type($wpdb, 'restricteduselicence', $postmeta['rul']);
  $pvp   = get_id_post_type($wpdb, 'plantvariety', $postmeta['pvp']);
  $patent   = get_id_post_type($wpdb, 'patent', $postmeta['patent']);
  $patent_number   = get_id_post_type($wpdb, 'patentnumber', $postmeta['patent_number']);
  $patent_number_link   = get_id_post_type($wpdb, 'patentnumberlink', $postmeta['patent_number_link']);
    
  add_post_meta($id, 'class', $class[0]->id);
  add_post_meta($id, 'restricted_use_licence', $rul[0]->id);
  add_post_meta($id, 'plant_variety_protection', $pvp[0]->id);
  add_post_meta($id, 'pvp_number', $postmeta['pvp_number']);
  add_post_meta($id, 'pdf_link', $postmeta['pdf_link']);
  add_post_meta($id, 'patent', $postmeta['patent']);
  add_post_meta($id, 'patent_number', $postmeta['patent_number']);
  add_post_meta($id, 'patent_number_link', $postmeta['patent_number_link']);  
}

/**
* Get value of post type
* @params db, post_type, str
* @return $id
*/
function get_id_post_type($wpdb, $post_type, $title){
  $table = $wpdb->prefix."posts";
  $q = "SELECT ID as id FROM ".$table." WHERE post_type = '".$post_type."' && post_title = '".$title."'";
  $row = $wpdb->get_results($wpdb->prepare($q));

  return $row;
}

/**
* Add import to logs table
* @param file_name
* @return Null
*/
function add_ob_import_logs($wpdb, $file_name){
      $table = $wpdb->prefix."ob_import_logs";
      $wpdb->insert($table, array('file_name' => $file_name));
}