        </div>
    </section>

        <footer class="main-footer">
            <strong>Copyright &copy; <?php echo date("Y"); ?> <a href="<?php echo base_url();?>">Newlyweds foods Philippines</a>. </strong>All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
            <!--<b>Version</b> 3.1.0-->
            </div>
        </footer>
        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
                    <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

        <?php
            #Javascript	
            $myjs=array(
                'assets/plugins/jquery/jquery.min.js',
                'assets/plugins/jquery-ui/jquery-ui.min.js',
                'assets/plugins/bootstrap/js/bootstrap.bundle.min.js',
                'assets/plugins/chart.js/Chart.min.js',
                'assets/plugins/sparklines/sparkline.js',
                'assets/plugins/jqvmap/jquery.vmap.min.js',
                'assets/plugins/jqvmap/maps/jquery.vmap.usa.js',
                'assets/plugins/jquery-knob/jquery.knob.min.js',
                'assets/plugins/moment/moment.min.js',
                'assets/plugins/daterangepicker/daterangepicker.js',
                'assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js',
                'assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js',
                'assets/js/adminlte.min.js',
                'assets/plugins/datatables/jquery.dataTables.min.js',
                'assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js',
                'assets/plugins/datatables-responsive/js/dataTables.responsive.min.js',
                'assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js',
                'assets/plugins/datatables-buttons/js/dataTables.buttons.min.js',
                'assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js',
                'assets/plugins/datatables-buttons/js/buttons.html5.min.js',
                'assets/plugins/datatables-buttons/js/buttons.print.min.js',
                'assets/plugins/datatables-buttons/js/buttons.colVis.min.js',
                'assets/plugins/select2/js/select2.full.min.js',
                'assets/plugins/sweetalert2/sweetalert2.all.min.js',
                'assets/plugins/number-format/jquery.number.min.js',
                'assets/plugins/jquery-validation/jquery.validate.min.js',
                'assets/plugins/jquery-validation/additional-methods.min.js',
                'assets/plugins/inputmask/jquery.inputmask.min.js',
                'assets/js/chartjs.plugins.js',
				
                'assets/js/template.js',
                'assets/js/jAlert.min.js',
                'assets/js/bootbox.min.js',

                'assets/js/apps.js',
                'assets/js/ui.js',
                'assets/js/form.js',
                'assets/js/appdatatables.js',
            );
            
            foreach($myjs as $k=>$v){
                loadjs($v);
            }
            
        ?>
        
        <script type='text/javascript'>
            <?php echo $this->addJS."\n"; ?>
            <?php echo $this->frmJS."\n"; ?>
            (function(){
            
                <?php 
                    echo @$this->tblJS;
                ?>
                
            })()
            
        </script>
        <?php
            
            foreach($this->includeJS as $k=>$v){
                loadjs($v);
            }

        ?>
    </body>
</html>
