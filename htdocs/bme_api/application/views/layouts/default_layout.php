<?php
        
        $CI= &get_instance();
        $mymenu = @$CI->load_menu();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>TAX CERT - <?php echo $this->titlepage;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="overview &amp; stats" />

	<!-- bootstrap & fontawesome -->
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/all.min.css" />
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/datatables.css" />
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/w3.css" />
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/fontawesome.min.css" />
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery.alerts.min.css" />
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/chartjs.min.css" />
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/template.css" />
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/matex.css" />
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jsuites.css" />
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jexcel.css" />
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/toggle-switch.css" />
    
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/Chart.min.css" />

    
	<!-- page specific plugin styles -->
	<!-- basic scripts -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.alerts.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/popper.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/bootbox.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/datatables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pdfmake.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/vfs_fonts.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/typeahead.bundle.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/chartjs.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/template.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/jsuites.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/jexcel.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/Chart.bundle.min.js"></script>

    <style>

        .nav-link{
            color:black;
        }

    </style>

</head>
 
<body class="no-skin" >

    <div id="loader-wrapper">
    <div id="loader"></div>
 
    <div class="loader-section section-left"></div>
    <div class="loader-section section-right"></div>
 
    </div>

 <nav class="navbar navbar-expand-sm bg-success" id="topmenu">
    <a class="navbar-brand mr-auto" href="#" style='font-size: 1.25rem;color: black!important;font-weight:bold;'>NWFP - TAX CERT </a>
       <!-- <ul class="navbar-nav">
            <li class="nav-item">
            <a class="nav-link" href="#">Link 1</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="#">Link 2</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="#">Link 3</a>
            </li>
        </ul>
        <ul class="nav justify-content-end">
            <li class="nav-item">
             <a class="nav-link" href="#">Link 3</a>
            </li>
        </ul>-->
    <div>
        
    </div>
 </nav>
<!--
 <div class="navbar_">
  <a href="#home">Home</a>
  <a href="#news">News</a>
  <div class="dropdown_">
    <button class="dropbtn_">Dropdown 
      <i class="fa fa-caret-down"></i>
    </button>
    <div class="dropdown-content_">
      <a href="#">Link 1</a>
      <a href="#">Link 2</a>
      <a href="#">Link 3</a>
    </div>
  </div> 
</div>
-->
<div class="">
  

<div class="w3-row" style='height: 100vh;' >
    <div class="w3-col" style="height: 100vh;width:250px;background-color:white" >

        <!-- SIDE MENU 
        <ul class="nav flex-column" style='height: 100%;'>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-user"></i> <?php echo @$this->session->userdata["Fname"]." ".@$this->session->userdata["Lname"] ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"> <i class="fas fa-chart-line"></i> Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-clipboard-list"></i> Product Pricing Catalog</a>
            </li>
           
            <li class="nav-item">
                <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fas fa-shopping-cart"></i> Purchasing</a>
                
                <ul class="collapse list-unstyled " id="homeSubmenu" style=" padding-left: 20px!important">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Purchase Request</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Purchase Request Approval</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Purchase Order</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Purchase Order Approval</a>
                    </li>
                </ul>

            </li>

            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-file-invoice-dollar"></i> Sales</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-file-contract"></i> Report</a>
            </li>
            
            <li class="nav-item">
                <a href="#adminSubmenu" data-toggle="collapse" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fas fa-tools"></i> Admin Tools</a>
                
                <ul class="collapse list-unstyled " id="adminSubmenu" style=" padding-left: 20px!important">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url();?>UserAccount">User Management</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url();?>UserLevel">User Level</a>
                    </li>
                </ul>

            </li>

            
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url();?>Auth/logout"><i class="fas fa-sign-out-alt"></i> Log-out</a>
            </li>


        </ul>
  <!-- END SIDE MENU-->
      
       <?php
            
           
            #$mymenu=$this->load_menu();
           echo side_menu($mymenu);
        ?>

    </div>
    <div class="w3-rest " style='overflow:scroll;height: 96vh;'>
    <nav class="navbar navbar-expand-xs " style="background-color:white">
        <span class="h6 mr-auto" style="font-weight:bold">&nbsp; <?php echo $this->title;?></span>
        <div>
            <?php
            # $my=$this->models->UserInfo();
            #    echo "<center style='font-weight:bold'><a class='btn btn-outline-light'style='border-clor:#a0a1a2;' ><img style='border: solid 1px #c5c5c5;' src=\"".base_url()."assets\img/emp/10094.png\" alt=\"Avatar\" class=\"avatar\">&nbsp;&nbsp;".$my->firstname." ".$my->surname."</a></center>";
            
            ?>
        </div>
    </nav>
    <div class="w3-row"  style="padding-left:2px">
    <?php
        
        echo @$prePage;
    
    ?>
    </div>
    <div class="w3-row"  style="padding-left:2px">
        
              <!---Tables-->
              <?php
              if($this->datatables->table !=""){
               

                if(@$this->subtable!=""){
                  echo '<div class="w3-half" >';
                  
                  if($this->permission[$this->pageclass]->add)
                    echo "<p><a onclick='btnAdd_".$this->datatables->table."()' class='btn btn-outline-success'><i class='fas fa-plus-circle'></i>&nbsp; ADD</a></p>";
                   
                    $this->datatables->genTable($this->datatables->table);
                  
                  if($this->permission[$this->pageclass]->add)
                    echo "<p><a onclick='btnAdd_".$this->datatables->table."()' class='btn btn-outline-success'><i class='fas fa-plus-circle'></i>&nbsp; ADD</a></p>";
                 
                echo '</div>';
                  echo '<div class="w3-half" >';

                  if($this->permission[$this->pageclass]->add)
                    echo "<p><a onclick='btnAdd_".$this->subdb->table."()' class='btn btn-outline-success'><i class='fas fa-plus-circle'></i>&nbsp; ADD</a></p>";
                    
                    $this->subdb->genTable();
                
                if($this->permission[$this->pageclass]->add)
                    echo "<p><a onclick='btnAdd_".$this->subdb->table."()' class='btn btn-outline-success'><i class='fas fa-plus-circle'></i>&nbsp; ADD</a></p>";
                  echo "</div>";
                }else{
                  echo '<div class="w3-col" style="    padding-left: 20px;">';
             
              if($this->permission[$this->pageclass]->add)    
                  echo "<p><a onclick='btnAdd_".$this->datatables->table."()' class='btn btn-outline-success'><i class='fas fa-plus-circle'></i>&nbsp; ADD</a></p>";
                 
                  $this->datatables->genTable($this->datatables->table);
                
              if($this->permission[$this->pageclass]->add)                  
                  echo "<p><a onclick='btnAdd_".$this->datatables->table."()' class='btn btn-outline-success'><i class='fas fa-plus-circle'></i>&nbsp; ADD</a></p>";
                 
                  echo "</div>";

                }

                

                
              }
              #print_r(frminput($this->datatables->column));
          ?>
              <!---end Table-->
              <!--Table CRUD-->
              <script type="text/javascript">

                    <?php
                        if($this->datatables->table !=""){
                        $this->datatables->genJS($this->datatables->table);
                           
                    ?>
                                
                            var vdata="";
                            var xref=<?php echo ( count(@$this->inp_form['xref'])>0 ? json_encode(@$this->inp_form['xref']) :"''" ); ?>;
                            var in_add_<?php echo $this->datatables->table; ?>=<?php echo ( count(@$this->inp_form['inp_add'])>0 ? json_encode($this->inp_form['inp_add']) :"''" ); ?>;
                            <?php 
                             if($this->permission[$this->pageclass]->add)  
                                echo $this->datatables->genJSAdd();
                            if($this->permission[$this->pageclass]->edit)  
                                echo $this->datatables->genJSEdit();
                            if($this->permission[$this->pageclass]->delete)  
                                echo $this->datatables->genJSDelete();
                                
                                if(@$this->subtable!=""){

                                echo $this->subdb->genJSSub();

                                $subinput=frminput($this->subdb->column);
                                echo $this->subdb->genJSAddSub();
                                echo $this->subdb->genJSEditSub();
                                echo $this->subdb->genJSDeleteSub();
                                ?>

                                var xref_<?php echo $this->subdb->table; ?>=<?php echo ( count($subinput['xref'])>0 ? json_encode($subinput['xref']) :"''" ); ?>;
                                var in_add_<?php echo $this->subdb->table; ?>=<?php echo ( count($subinput['inp_add'])>0 ? json_encode($subinput['inp_add']) :"''" ); ?>;
                                
                                $.each(xref_<?php echo $this->subdb->table; ?>,function(k,v){
                                    
                                    //console.log(k);
                                    xref[k]=v;

                                });
                                
                                function subdata(e){
                                    xdx=e;
                                   
                                    vdata=$(xdx).attr('data-value');
                                    dtble_sub.ajax.url("<?php echo base_url().$this->router->fetch_class()."/loadSDataSub" ?>/?id="+vdata).load();
                                    //tbl_leave.ajax.type="GET";
                                    //tbl_leave.ajax.reload(null, false);
                                }

                                $('#auto-data-<?php echo $this->datatables->table;?>').on( 'click', 'tr', function () {
                                        if ( $(this).hasClass('myhigh') ) {
                                            $(this).removeClass('myhigh');
                                        }
                                        else {
                                        subdata($(this).children("td").html());
                                        dtble.$('tr.myhigh').removeClass('myhigh');
                                            $(this).addClass('myhigh');
                                        }
                                    } );


                                <?php

                                }

                            ?>

                            

                        
                    <?php
                        }
                    ?>

                    </script>


             
             
              <!-- PAGE CONTENT BEGINS -->
                                        <?php echo $contents;?>
                        <!-- PAGE CONTENT ENDS -->
        
    </div>
	</div><!-- /.col -->
</div><!-- /.row -->

</div>
</div><!-- /.main-content -->

<div class="footer">
<div class="footer-inner">
<div class="footer-content">
<span class="bigger-120">
	<span style='float:left'>&copy<?php echo date('Y');?> Newly Weds Foods Philippines</span>
   
</div>
</div>
</div>


</div><!-- /.main-container -->
 

 </div>

 <script type="text/javascript">
    
    $("#loader-wrapper").hide();

</script>
</body>
</html>