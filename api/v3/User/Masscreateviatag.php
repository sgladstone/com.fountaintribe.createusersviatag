<?php

/**
 * User.Masscreateviatag API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRM/API+Architecture+Standards
 */
function _civicrm_api3_user_Masscreateviatag_spec(&$spec) {
  $spec['tagname']['api.required'] = 1;
}

/**
 * User.Masscreateviatag API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_user_Masscreateviatag($params) {
  
 $message = "";
 $tmp_msg = ""; 
$tried_count = 0;  
$created_count = 0;
$skipped_count = 0; 

   $send_email_parm = "true";

if (array_key_exists('tagname', $params) && strlen( $params['tagname'] ) > 0 ) {

// Get tag id.
    $tag_result = civicrm_api3('Tag', 'get', array(
  'sequential' => 1,
  'name' =>  $params['tagname'],
));
    
if( $tag_result['count']  == 1){

   $tmp_tag_id = $tag_result['id'];

   $contacts_result = civicrm_api3('Contact', 'get', array(
  'sequential' => 1,
  'tag' => $tmp_tag_id,
  'contact_type' => 'Individual' 
));

   if(  $contacts_result['count'] > 0 ){
       $contacts_with_tag = $contacts_result['values'];


foreach( $contacts_with_tag as $cur){
 
  
  $cur_cid = $cur['id']; 

  $user_result = civicrm_api3('User', 'create', array(
  'sequential' => 1,
  'contact_id' => $cur_cid,
  'send_email_user' => $send_email_parm
));

 // print "<br> user result: ";
 // print_r( $user_result);
   $tmp_msg_arr = $user_result['values'][0];

    if( $user_result['is_error']  == 0 ){
          $tried_count = $tried_count + 1;
          if( isset( $tmp_msg_arr['created_count'] ) && $tmp_msg_arr['created_count'] <> 0   ){
               $created_count = $created_count + $tmp_msg_arr['created_count']; 
          }

          if( isset( $tmp_msg_arr['skipped_count']) && $tmp_msg_arr['skipped_count'] <> 0   ){
               $skipped_count = $skipped_count + $tmp_msg_arr['skipped_count']; 
          }


    }else{

    }
  
   $tmp_msg = $tmp_msg." ; ".$tmp_msg_arr['details']; 

     }

   $message = "Attempted to create ".$tried_count." users. Details: ".$tmp_msg;

    }else{
           $message = "Could not find any contacts tagged with '".$params['tagname']."'"; 
     }

   }else{
        throw new API_Exception(/*errorMessage*/ "Error: Could not find tag with the name '".$params['tagname']."'", /*errorCode*/ 1234);
   }   

    // $tmp_rtn_array['skipped_count'] =  $skipped_count;
    // $tmp_rtn_array['created_count'] =  $created_count;
    // $tmp_rtn_array['short_details'] =  $message;

    $returnValues = array( // OK, return several data rows
      12 => array('id' => 'skipped_count', 'name' =>  $skipped_count),
      34 => array('id' => 'created_count', 'name' =>  $created_count),
      56 => array('id' => 'details', 'name' => $message),
    ); 

 // $returnValues =  $tmp_rtn_array; // OK, success
    return civicrm_api3_create_success($returnValues, $params, 'NewEntity', 'NewAction');
  } else {
    throw new API_Exception(/*errorMessage*/ 'Everyone knows that the magicword is "sesame"', /*errorCode*/ 1234);
  }
}

