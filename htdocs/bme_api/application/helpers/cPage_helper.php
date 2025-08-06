<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function isMenu($v){
	$CI =& get_instance();
	$menudata = $CI->db->where("pageid",$CI->pageid)->get("tbl_page")->row();
	switch(true){

		case ($menudata->pageid == $v['menu_0']):{
			
			break;
		}

		case ($menudata->pageid == $v['menu_1']):{
			
			break;
		}

		case ($menudata->pageid == $v['menu_2']):{
			
			break;
		}

		case ($menudata->pageid == $v['menu_0']):{
			
			break;
		}
	}

	return 0;
}

if ( ! function_exists('side_menu')){
    
    function side_menu($menu_array=array()){
		
        $html_menu="<div class='min-vh-100'>
                    <ul class='nav flex-column flex-nowrap ' style='height:100%;'>";
            $sub=0;
            foreach($menu_array as $k=>$v){
                
                    $alink=($v['pagename']==""?"#":$v['pagename']);
                    $attr="";
                  
                if(key_exists("submenu",$v)){
                       
                        $alink="#submenu".@$v["pageid"];
                        $aattr='data-toggle="collapse" aria-expanded="false" class="nav-link collapsed text-truncate dropdown-toggle"';
                    
                }else{
                        $alink=($v['pagename']==""?"#":base_url().$v['pagename']);
                        $aattr="class='nav-link' ";
                }
                  
                $html_menu.="<li class='nav-item '>"; 
                $html_menu.="<a ".$aattr." href='".$alink."'>";
                $html_menu.=(@$v["icon"]!="" ? "<i class='fas fa-". @$v["icon"]."'></i>" : "")."&nbsp;";
                $html_menu.=$v['pagetitle'];
                $html_menu.="</a>"; 
                            
                if(key_exists("submenu",$v)){
                    $html_menu.=submenu($v["submenu"],$v["pageid"]);  
                }   
                    
                    #$html_menu.="<a class='nav-link text-truncate' href='".$v['pagename']."'><i class='fa fa-home'></i> <span class='d-none d-sm-inline'>Overview</span></a>";
                    
                       
                $html_menu.="</li>";
                    

            }

        $html_menu.="</ul></div>";
            return $html_menu;
    }

    function submenu($menu_array=array(),$id = ""){
    
        $html_='<div class="collapse" id="submenu'.$id.'" aria-expanded="false">';
            $html_.="<ul class='flex-column pl-2 nav'>";
            
                foreach($menu_array as $k=>$v){
                
                     $alink=($v['pagename']==""?"#":$v['pagename']);
                     $attr="";
                
                    if( key_exists("submenu",$v) ){
                    
                        
                            $alink="#submenu".@$v["pageid"];
                            $aattr='data-toggle="collapse" aria-expanded="false" class="nav-link collapsed text-truncate dropdown-toggle"';
                    
                    }else{
                            $alink=($v['pagename']==""?"#":$v['pagename']);
                            $aattr="class='nav-link' ";
                    }
                    
                    
                    #$html_.="<li class=''>";
                    $html_.="<li class='nav-item'>"; 
                    $html_.="<a ".$aattr." href='".$alink."'>";
                    $html_.=(@$v["icon"]!="" ? "<i class='fas fa-". @$v["icon"]."'></i>" : "")."&nbsp;";
                    $html_.=$v['pagetitle'];
                    $html_.="</a>";
                    
                    if( key_exists("submenu",$v) ){
                    
                     $html_.=submenu($v["submenu"],$v["pageid"]);   
                    
                    }
                    
                    $html_.="</li>";
                
                }
            
            $html_.="</ul>";
        $html_.=" </div>";
    
        return $html_;
    }
    
}

if ( ! function_exists('loadjs')){
	function loadjs($js){
        
        if(strpos($js,'http') === false){
            echo '<script type="text/javascript" src="'.base_url($js).'"></script>'."\n";
        }
	    else{
            
            echo '<script type="text/javascript" src="'.$js.'"></script>'."\n";
        }
            
	}
}

if(! function_exists('loadcss')){
	function loadcss($css){
        if(strpos($css,'http') === false)
		    echo "\t<link rel=\"stylesheet\" href='".base_url($css)."' /> \n";
        else
            echo "\t<link rel=\"stylesheet\" href='".$css."' /> \n";
	}
}

if ( ! function_exists('sidemenu')){
	function sidemenu($menu=array()){
		
		$mymenu="";

		foreach($menu as $k=>$v){
			$alink=($v['pagename']==""?"#":$v['pagename']);
			$aattr="class='nav-link' ";
			#$mymenu.='<li class="nav-item">';
			
			#$mymenu.='</li>';
            
            $maysub="";
            
			if(key_exists("submenu",$v)){
                       
                        $alink="#submenu".@$v["pageid"];
                        $maysub='<i class="right fas fa-angle-left"></i>';
            }else{
                        $alink=($v['pagename']==""?"#":base_url().$v['pagename']);
                        $maysub='';
                       
            }
                  
                $mymenu.="<li class='nav-item'>"; 
                $mymenu.="<a ".$aattr." href='".$alink."'>";
                $mymenu.=(@$v["icon"]!="" ? "<i class='fas fa-". @$v["icon"]."'></i>" : "")."&nbsp;";
                $mymenu.="<p>".$v['pagetitle']." ".$maysub."</p>";
                $mymenu.="</a>"; 
                            
            if(key_exists("submenu",$v)){
                    $mymenu.=submenu_n($v["submenu"],$v["pageid"]);  
            }   
                    
                    #$html_menu.="<a class='nav-link text-truncate' href='".$v['pagename']."'><i class='fa fa-home'></i> <span class='d-none d-sm-inline'>Overview</span></a>";
                    
                       
            $mymenu.="</li>";

		}
        
        return $mymenu;

	}

	function submenu_n($menu_array=array(),$id = ""){
		
        $html_="<ul class='nav nav-treeview'>";
            
                foreach($menu_array as $k=>$v){
                
                     $alink=($v['pagename']==""?"#":$v['pagename']);
                     $aattr="class='nav-link' ";
                
                    if( key_exists("submenu",$v) ){
                    
                        
                            $alink="#submenu".@$v["pageid"];
                           
                    }else{
                            $alink=($v['pagename']==""?"#":base_url().$v['pagename']);
                            
                    }
                    
                    
                    #$html_.="<li class=''>";
                    $html_.="<li class='nav-item'>"; 
                    $html_.="<a ".$aattr." href='".$alink."'>";
                    $html_.=(@$v["icon"]!="" ? "<i class='fas fa-". @$v["icon"]."'></i>" : "")."&nbsp;";
                    $html_.="<p>".$v['pagetitle']."</p>";
                    $html_.="</a>";
                    
                    if( key_exists("submenu",$v) ){
                    
                     $html_.=submenu($v["submenu"],$v["pageid"]);   
                    
                    }
                    
                    $html_.="</li>";
                
                }
            
            $html_.="</ul>";
    
        return $html_;
    }
}

?>
