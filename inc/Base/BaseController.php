<?php


 namespace respek_nature\Inc\Base;

  /**
*  sort out plugin path headache!!!!
*/

 class BaseController{

    public $plugin_path;
    public $plugin_url;
    // public array $manager;

    public function __construct(){
        $this->plugin_path = plugin_dir_path( dirname( __FILE__, 2 ) );
        $this->plugin_url = plugin_dir_url( dirname( __FILE__, 2 ) );

        // $this->$manager =[
        //     'text_ex',
        //     'collections_manager',
        //     'collections_matching_manager',
        //     'collections_on_us_manager',
        //     'collections_text_manager '
        // ];
    }
 }