<?php
class Template {
                //ci instance
                private $CI;
                //template Data
                var $template_data = array();

                public function __construct() 
                {
                                $this->CI =& get_instance();
                }

                public function load_menu(){
                    
                    $mainmenu=$this->CI->db->order_by("menu_0 asc,menu_1 asc,menu_2 asc, menu_order asc")->get("tbl_page")->result();
                    #$submenu1=$this->CI->db->where("menu_0!='0'")->order_by("menu_0,menu_order asc")->get("tbl_page")->result();
                    $menu_array=array();
                    
                    foreach($mainmenu as $k=>$v){
                    
                       switch(true){
                        
                        case ($v->menu_2!=0):{
                            
                            $menu_array[$v->menu_0]["submenu1"][$v->menu_1]["submenu2"][$v->menu_order]=array(
                               "pageid"=>$v->pageid,
                               "pagetitle"=>$v->pagetitle,
                               "icon"=>$v->icon,
                               "pagename"=>$v->pagename
                            );
                            
                            break;
                        }
                        
                        case (($v->menu_0 != 0 )  && ($v->menu_1!=0) ) :{
                        
                            $menu_array[$v->menu_0]["submenu1"][$v->menu_order]=array(
                               "pageid"=>$v->pageid,
                               "pagetitle"=>$v->pagetitle,
                               "icon"=>$v->icon,
                               "pagename"=>$v->pagename
                           );
                            
                            break;
                        }
                        
                        case ($v->menu_0==0):{
                            
                            $menu_array[$v->menu_order]=array(
                               "pageid"=>$v->pageid,
                               "pagetitle"=>$v->pagetitle,
                               "icon"=>$v->icon,
                               "pagename"=>$v->pagename
                           );
                            
                            break;
                        }
                        
                        default:{
                            
                            break;
                        }
                        
                        }
                        
                    }
                    
                    
            
                    return $menu_array;
                    
                }
 
                function set($content_area, $value)
                {
                                $this->template_data[$content_area] = $value;
                }
 
                function load($template = '', $name ='', $view = 'blank' , $view_data = array(),$topPage="", $return = FALSE)
                {               
                                if($topPage!=""){
                                     $this->set("prePage" , $this->CI->load->view($topPage, $view_data, TRUE));
                                }
                                
                                $view=($view=='' ? 'blank':$view );
                                $this->set($name , $this->CI->load->view($view, $view_data, TRUE));
                               
                                
                               
                                $this->CI->load->view('layouts/'.$template, $this->template_data);
                }

				function newload($view = 'blank',$content=array() ,$view_data = array(),$topPage="", $return = FALSE)
                {              
                                $view_data=array_merge($this->template_data,$view_data);
								$this->set("header" , $this->CI->load->view('layouts/template/header', $view_data, TRUE));#header
								$this->set("topmenu" , $this->CI->load->view('layouts/template/topmenu', $view_data, TRUE));#topmenu
								$this->set("sidemenu" , $this->CI->load->view('layouts/template/sidemenu', $view_data, TRUE)); #sidemenu

								if(isset($content['top']))
                                	$this->set('top_content', $this->CI->load->view(@$content['top'], $view_data, TRUE));

                                $this->set('content', $this->CI->load->view($view, $view_data, TRUE));

								if(isset($content['lower']))
                                	$this->set('lower_content', $this->CI->load->view(@$content['lower'], $view_data, TRUE));

								$this->set("footer" , $this->CI->load->view('layouts/template/footer', $view_data, TRUE));#footer

                                $this->CI->load->view('layouts/template/layout', $this->template_data); #template
                }
                               
}
?>
