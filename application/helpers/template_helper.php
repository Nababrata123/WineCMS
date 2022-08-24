<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//Get user name from header
if(!function_exists('get_username')){
    function get_username($role,$id)
    {
        
        $ci = &get_instance();
        if($role=='agency')
        {
            $sql = "SELECT meta_value FROM user_meta WHERE user_id=$id AND meta_key='agency_name'"; 
        
            $query = $ci->db->query($sql);
            $result=$query->row();
            if($result)
                $name=$result->meta_value;
            else
                $name='';
        }
        else
        {
            $sql = "SELECT first_name FROM users WHERE id=$id"; 
        
            $query = $ci->db->query($sql);
            $result=$query->row();
            $name=$result->first_name;
        }
        return $name;
        
    }
}
//Dynamically add Javascript files to header page
if(!function_exists('add_js')){
    function add_js($file='')
    {
        $str = '';
        $ci = &get_instance();
        $header_js  = $ci->config->item('header_js');

        if(empty($file)){
            return;
        }

        if(is_array($file)){
            if(!is_array($file) && count($file) <= 0){
                return;
            }
            foreach($file AS $item){
                $header_js[] = $item;
            }
            $ci->config->set_item('header_js',$header_js);
        }else{
            $str = $file;
            $header_js[] = $str;
            $ci->config->set_item('header_js',$header_js);
        }
    }
}

//Dynamically add CSS files to header page
if(!function_exists('add_css')){
    function add_css($file='')
    {
        $str = '';
        $ci = &get_instance();
        $header_css = $ci->config->item('header_css');

        if(empty($file)){
            return;
        }

        if(is_array($file)){
            if(!is_array($file) && count($file) <= 0){
                return;
            }
            foreach($file AS $item){
                $header_css[] = $item;
            }
            $ci->config->set_item('header_css',$header_css);
        }else{
            $str = $file;
            $header_css[] = $str;
            $ci->config->set_item('header_css',$header_css);
        }
    }
}

if(!function_exists('put_headers')){
    function put_headers()
    {
        $str = '';
        $ci = &get_instance();

        $header_css = $ci->config->item('header_css');
        $header_js  = $ci->config->item('header_js');
        
          

        foreach($header_css AS $item){

        	$file = "";
        	if (file_exists(DIR_CSS.$item)) {
        		$file = HTTP_CSS_PATH.$item;
        	}
        	if (file_exists(DIR_THEME.$item)) {
        		$file = HTTP_THEME_PATH.$item;
        	}
        	if (file_exists($item)) {
        		$file = BASE_URL.$item;
        	}

        	if ($file <> "") {
	            $str .= '<link rel="stylesheet" href="'.$file.'" type="text/css" />'."\n";
        	}
        }

        foreach($header_js AS $item){

        	$file = "";
        	if (file_exists(DIR_JS.$item)) {
        		$file = HTTP_JS_PATH.$item;
        	}
        	if (file_exists($item)) {
        		$file = BASE_URL.$item;
        	}

        	if ($file <> "") {
	            $str .= '<script type="text/javascript" src="'.$file.'"></script>'."\n";
        	}
        }

        return $str;
    }
}

if(!function_exists('render_field')){
    function render_field($data = array(), $value = '', $extra = '')
    {
    	if (empty($data)) {
    		return;
    	}

    	if ($value <> "") {
	    	$data['attributes']['value'] = $value;
    	}

    	$output = '<div class="form-group '.($data['hide_on_load']?'hide':'').'">
		  	<label for="'.$data['attributes']['id'].'" class="col-sm-3 control-label">'.$data['label'].'</label>
		  	<div class="col-sm-7">';
    	$output .= form_input($data['attributes']);
		$output .= '<div class="help-block with-errors">'.$data['help_text'].'</div>
			</div>
		</div>';
    	return $output;
    }
}

if(!function_exists('render_action')){
    function render_action($action = array(), $value = '', $ref_url = '')
    {

        //echo "<pre>";
       // print_r($action);
        $ci = &get_instance();
        $page_data = $ci->session->userdata('page_data');
        
        $url = $page_data['url'];
        $permissions = $page_data['permissions'];

        $output = '';

    	if (empty($action)) {
    		return $output;
    	}

        $output .= " ";
        if (in_array('images', $action) && checkActionPermission($permissions['images'])) {
            $output .= anchor($url.'/images/'.$value, '<span class="glyphicon glyphicon-picture"></span> Images', array('title' => 'Images', 'class' => 'btn btn-warning btn-xs'));
        }

        if (in_array('reset_pass', $action) && checkActionPermission($permissions['reset_pass'])) {
            $output .= anchor($url.'/reset_pass/'.$value, '<span class="glyphicon glyphicon-lock"></span> Reset Password', array('title' => 'Reset Password', 'class' => 'btn btn-info btn-xs'));
        }

        $output .= " ";
        if (in_array('edit', $action) && checkActionPermission($permissions['edit'])) {
            $output .= anchor($url.'/edit/'.$value.'?ref='.$ref_url, '<span class="glyphicon glyphicon-edit"></span> Edit', array('title' => 'Edit', 'class' => 'btn btn-primary btn-xs'));
        }

        $output .= " ";
        if (in_array('delete', $action) && checkActionPermission($permissions['delete'])) {
            $output .= anchor($url.'/delete/'.$value.'?ref='.$ref_url, '<span class="glyphicon glyphicon-trash"></span> Delete', array('title' => 'Delete', 'class' => 'btn btn-danger btn-xs', 'onclick' => 'return confirm(\'Are you sure you want to delete this?\')'));
        }

        $output .= " ";
        if (in_array('details', $action) && checkActionPermission($permissions['details'])) {
            $output .= anchor($url.'/details/'.$value, '<span class="glyphicon glyphicon-info-sign"></span> Details', array('title' => 'Details', 'class' => 'btn btn-default btn-xs'));
        }
    	return $output;
    }
}

if(!function_exists('render_link')){
    function render_link($action = '', $label = '')
    {
        $ci = &get_instance();
        $page_data = $ci->session->userdata('page_data');

        $url = $page_data['url'];
        $permissions = $page_data['permissions'];

        $valid_actions = array('add','delete','update_status','reset_pass','index');

        $output = '';

        if (empty($action)) {
    		return $output;
    	}

        if (!in_array($action, $valid_actions)) {
            return $output;
        }

        if (('add' == $action) && checkActionPermission($permissions[$action])) {
            $output .= anchor($url.'/add', $label, array('title' => strip_tags($label), 'class' => ''));
        }

        if (('index' == $action) && checkActionPermission($permissions[$action])) {
            $output .= anchor($url, $label, array('title' => strip_tags($label), 'class' => ''));
        }

    	return $output;
    }
}


if(!function_exists('render_buttons')){
    function render_buttons($action = array())
    {
        $ci = &get_instance();
        $page_data = $ci->session->userdata('page_data');

        $url = $page_data['url'];
        $permissions = $page_data['permissions'];

        $has_output = false;
        $output = '';

    	if (empty($action)) {
    		return $output;
    	}

        if (in_array('update_status', $action) && checkActionPermission($permissions['update_status'])) {
            $output .= form_button(array(
                    'name' => 'operation',
                    'id' => 'btn_status_active',
                    'value' => 'active',
                    'type' => 'submit',
                    'content' => '<span class="glyphicon glyphicon-ok-circle"></span> Activate',
                    'class' => 'btn btn-sm btn-success'
                )
            );
            $output .= "&nbsp;";
            $output .= form_button(array(
                    'name' => 'operation',
                    'id' => 'btn_status_deactive',
                    'value' => 'inactive',
                    'type' => 'submit',
                    'content' => '<span class="glyphicon glyphicon-ban-circle"></span> Deactivate',
                    'class' => 'btn btn-sm btn-warning'
                )
            );

            $has_output = true;
        }

        $output .= " ";
        if (in_array('delete', $action) && checkActionPermission($permissions['delete'])) {
            $output .= form_button(array(
                    'name' => 'operation',
                    'id' => 'btn_status_delete',
                    'value' => 'delete',
                    'type' => 'submit',
                    'content' => '<span class="glyphicon glyphicon-trash"></span> Delete',
                    'class' => 'btn btn-sm btn-danger',
                    'onclick' => 'return confirm(\'Are you sure you want to delete the selected item(s)?\');'
                )
            );
            $has_output = true;
        }
        //For export CSV
        $output .= " ";
        if (in_array('export', $action)) {
            $output .= form_button(array(
                    'name' => 'operation',
                    'id' => 'export',
                    'value' => 'export',
                    'type' => 'submit',
                    'content' => 'Export to csv',
                    'class' => 'btn btn-sm btn-info',
                    //'onclick' => 'return confirm("Are you sure you want to export the selected item(s)?")'
                )
            );
            $has_output = true;
        }
        if ($has_output) {
            $output = "With selected ".$output;
        }
    	return $output;
    }
}

function checkActionPermission($method) {
    $ci = &get_instance();
    $permissions = $ci->session->userdata('permissions');

    if (!in_array($method, $permissions)) {
        return false;
    } else {
        return true;
    }
}

if(!function_exists('categoryTree')){
    function categoryTree($parent_id = 0, $sub_mark = '',$category_id=NULL)
    {
        //echo "sdvs";die;
        $ci = &get_instance();
        //echo "<pre>";
        //print_r($ci);die;
        $ci->load->database(); 

        $sql = "SELECT * FROM category WHERE parent_id = $parent_id AND status='active' AND is_deleted='0' ORDER BY name ASC"; 
        
        $query = $ci->db->query($sql);
        $result=$query->result_array();
        //echo $category_id;exit;

        if($query->num_rows() > 0){
            
        foreach($result as $row)
        {
            if(!empty($category_id) && $category_id == $row['id'])
                echo '<option value="'.$row['id'].'" selected >'.$sub_mark.$row['name'].'</option>';
            else    
                echo '<option value="'.$row['id'].'" >'.$sub_mark.$row['name'].'</option>';
            categoryTree($row['id'], $sub_mark.'---');
        }
    }
    }
}

if(!function_exists('get_user_role'))
{
    function get_user_role($table_name,$user_id)
    {
        $ci = &get_instance();
        $ci->load->database(); 
        $ci->db->select('role_id');
        $ci->db->from($table_name);
        $ci->db->where('users.id',$user_id);
        $user_role=$ci->db->get()->row('role_id');
        return $user_role;
    }
}
if(!function_exists('get_user_type'))
{
    function get_user_type($table_name,$user_id)
    {
        $ci = &get_instance();
        $ci->load->database();
        $ci->db->select('user_type,created_by');
        $ci->db->from($table_name);
        $ci->db->where('users.id',$user_id);
        $user_role=$ci->db->get()->row();
  
        return $user_role->user_type;
    }
}
if(!function_exists('get_agency_name'))
{
    function get_agency_name($table_name,$user_id)
    {
        $ci = &get_instance();
        $ci->load->database();
        $ci->db->select('meta_value');
        $ci->db->from($table_name);
        $ci->db->where('user_id',$user_id);
        $ci->db->where('meta_key','agency_name');
        $agency_name=$ci->db->get()->row('meta_value');
        return $agency_name;
    }
}
if(!function_exists('get_signature_image'))
{
    function get_signature_image($job_id)
    {
        $ci = &get_instance();
        $ci->load->database();    
        $ci->db->select('signature_img');
        $ci->db->from('manager_verification_details');
        $ci->db->where('job_id',$job_id);
        $value=$ci->db->get()->row();
        if($value)
          $img=$value->signature_img;
        else
          $img='';
        return $img;
    }
}
if(!function_exists('get_receipt_images'))
{
    function get_receipt_images($job_id)
    {
        $ci = &get_instance();
        $ci->load->database();
        $ci->db->select('id');
        $ci->db->from('expense_details');
        $ci->db->where('job_id',$job_id);
        $value=$ci->db->get()->row();
        if($value)
          $id=$value->id;
        else
          $id='';
        if($id!='')
        {
            $ci->db->select('images');
            $ci->db->from('expense_details_images');
            $ci->db->where('expense_id',$id);
            $value_img=$ci->db->get()->result_array();
            return $value_img;
        }
        else
        {
            return '';
        }
    }
}
if(!function_exists('get_taster_rate_per_hour'))
{
    function get_taster_rate_per_hour($taster_id)
    {
        $ci = &get_instance();
        $ci->load->database();
        //Get taster created type
        $ci->db->select('created_by');
        $ci->db->from('users');
        $ci->db->where('users.id',$taster_id);
        $value=$ci->db->get()->row();
        $created_by=$value->created_by;
        if($created_by==7)
        {
            $meta_key='rate_per_hour';
        }
        else
        {
            $meta_key='tasters_rate';
        }
        $ci->db->select('meta_value');
        $ci->db->from('user_meta');
        $ci->db->where('meta_key',$meta_key);
        $ci->db->where('user_id',$taster_id);
        $user_meta=$ci->db->get()->row();
        if($user_meta)
            $rate_per_hr=$user_meta->meta_value;
        else
            $rate_per_hr=0;
        return $rate_per_hr;
    }
}

//Get taster meta information

if(!function_exists('get_user_meta'))
{
    function get_user_meta($taster_id)
    {
        $ci = &get_instance();
        $ci->load->database();
        $ci->db->select('meta_value');
        $ci->db->from('user_meta');
        $ci->db->where('meta_key','zone');
        $ci->db->where('user_id',$taster_id);
        $user_meta=$ci->db->get()->row();
        if($user_meta)
            $zone_id=$user_meta->meta_value;
        else
            $zone_id=0;
        
        $zone_id_array=explode(",",$zone_id);
                //print_r($zone_id_array);die;
        $zone='';
        foreach($zone_id_array as $id)
        {
            $ci->db->select('name');
            $ci->db->from('zone');

            $ci->db->where('zone.id',$id);
            $value = $ci->db->get();
            //echo $this->db->last_query();die;
            $result=$value->row();
            if($result)
                $name= $result->name;
            else
                $name='';
            
            $zone.=$name.",";
        }
        $zone=rtrim($zone,",");
        return $zone;
        
    }
}

if(!function_exists('get_zone_list'))
{
    function get_zone_list()
    {
        $ci = &get_instance();
        $ci->load->database();
        $ci->db->select('*');
        $ci->db->from('zone');
        $ci->db->where('zone.status','active');
        $ci->db->where('zone.is_deleted',0);
        $zone = $ci->db->get();
        return $zone->result();
        
    }
}
if(!function_exists('get_zone_list'))
{
    function get_zone_list()
    {
        $ci = &get_instance();
        $ci->load->database();
        $ci->db->select('*');
        $ci->db->from('zone');
        $ci->db->where('zone.status','active');
        $ci->db->where('zone.is_deleted',0);
        $zone = $ci->db->get();
        return $zone->result();
        
    }
}
if(!function_exists('get_wine_names'))
{
    function get_wine_names($wine_id_array)
    {
        $ci = &get_instance();
        $ci->load->database();
        $ci->db->select("wine.name");
        $ci->db->from('wine');
        $ci->db->where_in('wine.id',$wine_id_array);
        $result=$ci->db->get();
        $names=$result->result_array();
        $str='';
        foreach($names as $val)
        {
            $str.=$val['name'].",";
        }
        $str=rtrim($str,",");
        $str = implode(',',array_unique(explode(',', $str)));
        
        return $str;
    }
}   
if(!function_exists('get_agency_and_taster'))
{
    function get_agency_and_taster($id)
    {
        $ci = &get_instance();
        $ci->load->database();
        $ci->db->select("users.first_name,users.last_name");
        $ci->db->from('users');
        $ci->db->where_in('users.id',$id);
        $result=$ci->db->get();
        $names=$result->result_array();
        $str='';
        foreach($names as $val)
        {
            $str.=$val['first_name']." ".$val['last_name'].",";
        }
        $str=rtrim($str,",");
        
        
        return $str;
    }
} 
if(!function_exists('check_is_deleted'))
{
    function check_is_deleted($tablename,$id)
    {
        $ci = &get_instance();
        $ci->load->database();
        $ci->db->select("is_deleted");
        $ci->db->from($tablename);
        $ci->db->where('id',$id);
        $result=$ci->db->get();
        $value=$result->row();
        if($result->num_rows()==0)
        {
            return false;
        }
        else
        {
            $deleted=$value->is_deleted;
            if($deleted==1)
            {
                return false;
            }
            else
            {
                return true;
            }
        }
    }
}