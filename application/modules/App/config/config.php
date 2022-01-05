<?php defined('BASEPATH') || exit('No direct script access allowed');

$config['module_config'] = array(
	'description'	=> 'Your module description',
	'name'		    => 'App',
     /*
      * Replace the 'name' entry above with this entry and create the entry in
      * the application_lang file for localization/translation support in the
      * menu
      */
	'version'		=> '0.0.1',
	'author'		=> 'debarshi',
	'users'			=> array(
		'meta' 	=> array(
    		'phone' => array(
    			'name'			=> 'phone',
    			'type' 			=> 'text',
    			'label' 		=> 'Phone',
    			'attributes' 	=> array(
					'name'          => 'meta[phone]',
					'id'            => 'inputPhone',
					'value'         => '',
					'maxlength'     => 14,
    				'data-minlength' => 10,
					//'size'          => '',
					//'style'         => '',
    				'placeholder' 	=> "Enter phone number",
    				//'required' 		=> true,
    				'class'			=> 'form-control',
				),
    			'help_text' 	=> '14 digit phone number',  
    			'hide_on_load'	=> false,			
    		),
    		'location' => array(
          'name'      => 'location',
          'type'      => 'text',
          'label'     => 'Location',
          'attributes'  => array(
          'name'          => 'meta[location]',
          'id'            => 'inputLocation',
          //'value'         => '',
          //'maxlength'     => '',
            //'data-minlength' => '',
          //'size'          => '',
          //'style'         => '',
            'placeholder'   => "Enter location",
            //'required'    => '',
            'class'     => 'form-control',
        ),
          'help_text'   => '',  
        'hide_on_load'  => true,
        )
    	)
    )
);

