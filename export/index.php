<?php
require "php-export-data.class.php";

$excel = new ExportDataExcel('browser');
$excel->filename = "westbredseed_".date('Y-m-d').".xls";

$tmp_data = get_db_data();
$excel->initialize();
$header = array(
              'Title',
              'Description',
              'PDF Link',
              'Class',
              'Restricted Use Licence',
              'Plant variety Protection',
              'PVP#',
              'Patent',
              'Patent Number',
              'Patent Number Link',
              'State',
            );
$excel->addRow($header);            
for($i=0;$i<count($tmp_data);$i++)
{
  $row = array(
        $tmp_data[$i]['title'],
        $tmp_data[$i]['desc'],
        $tmp_data[$i]['pdf'],
        $tmp_data[$i]['class'],
        $tmp_data[$i]['rul'],
        $tmp_data[$i]['pvp'],
        $tmp_data[$i]['pvp_number'],
        $tmp_data[$i]['patent'],
        $tmp_data[$i]['patent_number'],
        $tmp_data[$i]['patent_number_link'],
        $tmp_data[$i]['states'],
        );
  $excel->addRow($row);
}

$excel->finalize();
exit;

function get_db_data(){
    $args = array( 
        'post_type'      => 'product',
        // 'post_status'    => 'publish',
        'posts_per_page' => '-1',
        'orderby'        => 'title',
     );
    $the_query = new WP_Query( $args );

    // The Loop
    if ( $the_query->have_posts() ) :
    $i = 0;
    while ( $the_query->have_posts() ) : $the_query->the_post();
    
      $pdf_link      = get_post_meta( get_the_ID(), 'pdf_link');
      $class         = go_get_post_title( get_post_meta( get_the_ID() , 'class') , 'class');
      $rul           = go_get_post_title( get_post_meta( get_the_ID() , 'restricted_use_licence') , 'restricteduselicence');
      $pvp           = go_get_post_title( get_post_meta( get_the_ID() , 'plant_variety_protection') , 'plantvariety');
      $pvp_number    = get_post_meta( get_the_ID() , 'pvp_number');
      $patent        = get_post_meta( get_the_ID() , 'patent');
      $patent_number = get_post_meta( get_the_ID() , 'patent_number');
      $patent_number_link = get_post_meta( get_the_ID() , 'patent_number_link');
      $states        = go_get_terms( get_the_ID() );
      $data[$i]      = array(
                          'title'           => get_the_title(),
                          'desc'            => get_the_content(),
                          'class'           => $class,
                          'pdf'             => $pdf_link[0],
                          'rul'             => $rul,
                          'pvp'             => $pvp,
                          'pvp_number'      => $pvp_number[0],
                          'patent'          => $patent[0],
                          'patent_number'   => $patent_number[0],
                          'patent_number_link'   => $patent_number_link[0],
                          'states'          => $states,
                        );
    $i++;  
    endwhile;
    endif;

    // Reset Post Data
    wp_reset_postdata();
  return $data;
}

function go_get_terms($id){
  global $wpdb;
  $q     = 'SELECT * from wp_terms t LEFT JOIN wp_term_relationships r on r.term_taxonomy_id = t.term_id WHERE object_id = '.$id;
  $rows  = $wpdb->get_results($wpdb->prepare($q));
  $out   = ""; 
  foreach($rows as $row)
  {
    $out_str .= $row->name.',';
  }
  return substr($out_str,0,-1);
}

function go_get_post_title($id, $post_type){
  if ($id[0] != "")
  {
     global $wpdb;
     $table  = "wp_posts";
     $q      = 'SELECT post_title from '.$table.' WHERE post_type = "'.$post_type.'" && ID = '.$id[0];
     $row    = $wpdb->get_results($wpdb->prepare($q));
     $title  = $row[0]->post_title;
  }
  else
  {
    $title = "";
  }
  return $title;
}