<?php

    class DataTables {

        protected $CI;
        #private $database;
        var $table="";
        var $column=array();
        var $pkey="";
        var $fkey="";
        var $where="";
        var $sql="";
        var $request;
        var $opt=array();
        var $permissioon=array();
        var $subkey="";
        var $xwhere="";
        var $xorderby="";
        var $xorderby_dir="";
       

        public function __construct($db = NULL)
        {
            $this->CI =& get_instance();
            $this->request=$this->CI->input->get();

        }

        private function setColumn(){
            $mycol_array=array();
            $mycol=$this->CI->db
                        ->query("Select COLUMN_NAME from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME ='".$this->table."'")
                        ->result();
            
            
            foreach($mycol as $k=>$v){
                
                $mycol_array[]=array(
                    "db"=>$v->COLUMN_NAME,
                    "dt"=>$k,
                    "th"=>$v->COLUMN_NAME
                );

            }
            
            $this->column=$mycol_array;

        }

        private function data_output (  $data )
        {
            #print_r($data);
            #die();
            $columns=$this->column;
            $out = array();

            for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
                $row = array();

                for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
                    $column = $columns[$j];

                    // Is there a formatter?
                    if ( isset( $column['formatter'] ) ) {
                        $row[ $column['dt']] = "<span data-value='".@$data[$i][ $this->subkey ] ."'>".$column['formatter']( $data[$i][ $column['db'] ], $data[$i] )."</span>";
                    }
                    else {
                        $row[ $column['dt']] = "<span data-value='".@$data[$i][ $this->subkey ] ."'>".$data[$i][ $columns[$j]['db'] ]."</span>";
                    }
                }

                $out[] = $row;
            }

            return $out;
        }

        private function limit ( )
        {
            $request=$this->request;
            $columns=$this->column;
            $limit = '';

            if ( isset($request['start']) && $request['length'] != -1 ) {
                $limit = "LIMIT ".intval($request['start']).", ".intval($request['length']);
            }

            return $limit;
        }

        private function order ( )
        {
            $request=$this->request;
            $columns=$this->column;
            $order = '';

            if ( isset($request['order']) && count($request['order']) ) {
                $orderBy = array();
                $dtColumns = self::pluck( $columns, 'dt' );

                for ( $i=0, $ien=count($request['order']) ; $i<$ien ; $i++ ) {
                    // Convert the column index into the column data property
                    $columnIdx = intval($request['order'][$i]['column']);
                    $xcolumnIdx = intval($request['order'][$i]['column']);
                    $requestColumn = $request['columns'][$columnIdx];

                    $columnIdx = array_search( $requestColumn['data'], $dtColumns );
                    $column = $columns[ $columnIdx ];

                    if ( $requestColumn['orderable'] == 'true' ) {
                        $dir = $request['order'][$i]['dir'] === 'asc' ?
                            'ASC' :
                            'DESC';
                        
                        // customize
               
                        if($xcolumnIdx==0 && $this->xorderby!=""){
                            $orderBy[] = $this->xorderby;
                        }else{
                            $orderBy[] = '`'.$column['db'].'` '.$dir;
                        }
                        
                        
                    }
                }
                
              
               
                
               // die();
                
                if ( count( $orderBy ) ) {
                    
                    
                        $order = 'ORDER BY '.implode(', ', $orderBy);
                    
                
                    
                }
            }
            
            

            return $order;
        }

        function pluck ( $a, $prop )
        {
            $out = array();

            for ( $i=0, $len=count($a) ; $i<$len ; $i++ ) {
                $out[] = $a[$i][$prop];
            }

            return $out;
        }

        private function filter (&$bindings )
        {
            $request=$this->request;
            $columns=$this->column;
            
            $globalSearch = array();
            $columnSearch = array();
            $dtColumns = self::pluck( $columns, 'dt' );

            if ( isset($request['search']) && $request['search']['value'] != '' ) {
                $str = $request['search']['value'];

                for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
                    $requestColumn = $request['columns'][$i];
                    $columnIdx = array_search( $requestColumn['data'], $dtColumns );
                    $column = $columns[ $columnIdx ];

                    if ( $requestColumn['searchable'] == 'true' ) {
                        #$binding = self::bind( $bindings, '%'.$str.'%', PDO::PARAM_STR );
                        $globalSearch[] = "`".$column['db']."` LIKE '%".$str."%'";
                    }
                }
            }

            // Individual column filtering
            if ( isset( $request['columns'] ) ) {
                for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
                    $requestColumn = $request['columns'][$i];
                    $columnIdx = array_search( $requestColumn['data'], $dtColumns );
                    $column = $columns[ $columnIdx ];

                    $str = $requestColumn['search']['value'];

                    if ( $requestColumn['searchable'] == 'true' &&
                    $str != '' ) {
                        #$binding = self::bind( $bindings, '%'.$str.'%', PDO::PARAM_STR );
                        $columnSearch[] = "`".$column['db']."` LIKE '%".$str."%'";
                    }
                }
            }

            // Combine the filters into a single string
            $where = '';

            if ( count( $globalSearch ) ) {
                $where = '('.implode(' OR ', $globalSearch).')';
            }

            if ( count( $columnSearch ) ) {
                $where = $where === '' ?
                    implode(' AND ', $columnSearch) :
                    $where .' AND '. implode(' AND ', $columnSearch);
            }

            if ( $where !== '' ) {
                $where = 'WHERE '.$where;
            }

            return $where;
        }

        public function simple ( $xwhere=""  )
        {

            if($this->column=="")
            $this->setColumn();

            $request=$this->request;
            $columns=$this->column;
            $table=$this->table;
            $primaryKey=$this->pkey;

            

            $bindings = array();
           # $db = self::db( $conn );

            // Build the SQL query string from the request
            $limit = self::limit(  );
            $order = self::order();
            
            
            
            $where = self::filter($bindings );
            
            if($this->xwhere!=""){
                $where.=($where!=""? " and ":" where " ).$this->xwhere;
            }
            
            if($xwhere!=""){
                $where.=($where!=""? " and ":" where " ).$xwhere;
            }
           
           
            // Main query to actually get the data
             $data = self::sql_exec(
                "SELECT `".implode("`, `", self::pluck($columns, 'db'))."`
                FROM `$table`
                 $where
                $order
                $limit"
            );

            // Data set length after filtering
            $resFilterLength = self::sql_exec( 
                "SELECT COUNT(`{$primaryKey}`)
                FROM   `$table`
                 $where"
            );
           
            $recordsFiltered = $resFilterLength[0]["COUNT(`".$this->pkey."`)"];

            // Total data set length
            $resTotalLength = self::sql_exec( 
                "SELECT COUNT(`{$primaryKey}`)
                FROM   `$table`"
            );
            #print_r($resTotalLength[0]);
            $recordsTotal = $resTotalLength[0]["COUNT(`".$this->pkey."`)"];

            /*
            * Output
            */
            return array(
                "draw"            => isset ( $request['draw'] ) ?
                    intval( $request['draw'] ) :
                    0,
                "recordsTotal"    => intval( $recordsTotal ),
                "recordsFiltered" => intval( $recordsFiltered ),
                "data"            => self::data_output(  $data )
            );
        }


        private function sql_exec ( $sql=null )
        {
            
            if($this->sql!=""){
                $sql=$this->sql;
            }
            
            // Execute
            try {
               
                $this->CI->load->database();
                $sqlrestult=$this->CI->db
                                        ->query($sql)
                                        ->result_array();
               

            }
            catch (PDOException $e) {
                self::fatal( "An SQL error occurred: ".$e->getMessage() );
            }

            // Return all
            return $sqlrestult;
        }

        static function fatal ( $msg )
        {
            echo json_encode( array( 
                "error" => $msg
            ) );

            exit(0);
        }

        public function complex ( $whereResult=null, $whereAll=null )
        {
            if($this->column=="")
            $this->setColumn();
            $request=$this->request;
            $columns=$this->column;
            $table=$this->table;
            $primaryKey=$this->pkey;

            $bindings = array();
           # $db = self::db( $conn );
            $localWhereResult = array();
            $localWhereAll = array();
            $whereAllSql = '';

            // Build the SQL query string from the request
            $limit = self::limit(  );
            $order = self::order( );
            $where = self::filter( $bindings );

            $whereResult = self::_flatten( $whereResult );
            $whereAll = self::_flatten( $whereAll );

            if ( $whereResult ) {
                $where = $where ?
                    $where .' AND '.$whereResult :
                    'WHERE '.$whereResult;
            }

            if ( $whereAll ) {
                $where = $where ?
                    $where .' AND '.$whereAll :
                    'WHERE '.$whereAll;

                $whereAllSql = 'WHERE '.$whereAll;
            }

            // Main query to actually get the data
            $data = self::sql_exec( 
                "SELECT `".implode("`, `", self::pluck($columns, 'db'))."`
                FROM `$table`
                $where
                $order
                $limit"
            );

            // Data set length after filtering
            $resFilterLength = self::sql_exec( 
                "SELECT COUNT(`{$primaryKey}`)
                FROM   `$table`
                $where"
            );
            $recordsFiltered = $resFilterLength[0][0];

            // Total data set length
            $resTotalLength = self::sql_exec( 
                "SELECT COUNT(`{$primaryKey}`)
                FROM   `$table` ".
                $whereAllSql
            );
            $recordsTotal = $resTotalLength[0][0];

            /*
            * Output
            */
            return array(
                "draw"            => isset ( $request['draw'] ) ?
                    intval( $request['draw'] ) :
                    0,
                "recordsTotal"    => intval( $recordsTotal ),
                "recordsFiltered" => intval( $recordsFiltered ),
                "data"            => self::data_output(  $data )
            );
        }

        static function _flatten ( $a, $join = ' AND ' )
        {
            if ( ! $a ) {
                return '';
            }
            else if ( $a && is_array($a) ) {
                return implode( $join, $a );
            }
            return $a;
        }
    
        public function genTable(){
           
            if($this->column=="")
                $this->column= $this->setColumn();
            
            $html="<table valign='top' class='table table-sm table-bordered table-hover table-striped nowrap' id='auto-data-".$this->table."' >";
            $html.="<thead>";
            foreach($this->column as $k=>$v){
                $html.="<th class='text-center'>".$v['th']."</th>";
            }

            $html.="</thead>";
            $html.="</table>";

            echo $html;
        }

        public function genJS($type=""){
            $url=base_url()."index.php/".$this->CI->uri->segment(1)."/loadSData";
            
            $mleft=array();
            $mright=array();
            $mcenter=array();
            
            foreach($this->column as $k=>$v){
                
                switch(strtoupper(@$v["align"])){
                    
                    case "R":{
                            $mright[]=@$v['dt'];
                        break;
                    }
                    
                    case "L":{
                            $mcenter[]=@$v['dt'];
                        break;
                    }
                    
                    default:{
                            $mcenter[]=@$v['dt'];
                        break;
                    }
                    
                }
            }
            
            
                $myalign='
                    ,"columnDefs": [
                        {"className": "text-left", "targets": ['.implode(",",$mleft).']},
                        {"className": "text-right", "targets": ['.implode(",",$mright).']},
                        {"className": "text-center", "targets": ['.implode(",",$mcenter).']},
                    ]
                ';
            
            
            
            $js='
                    var dtble;
                    $(document).ready(function() {
                        dtble=$("#auto-data-'.$this->table.'").DataTable( {
                            "processing": true,
                            "serverSide": true,
                            "responsive": true,
                            "ajax": "'.$url.'"'.$myalign.'
                        } );

                        
                    } );
                ';
            echo $js;
        }

        public function genJSSub($type=""){
            $url=base_url()."index.php/".$this->CI->uri->segment(1)."/loadSData";
            $js='  
                    var dtble_sub;
                    dtble_sub=$("#auto-data-'.$this->table.'").DataTable( { } );
                ';
            echo $js;
        }

        public function genJSAdd(){
            $url=base_url().$this->CI->router->fetch_class()."/addData";
            $x="
            function btnAdd_".$this->table."(){

                var msg=\"<form id='frmAdd'><table class='table table-bordered table-sm'>\";
                    $.each(in_add_".$this->table.",function(k){
                        
                        if(in_add_".$this->table."[k].type!=\"hidden\" && in_add_".$this->table."[k].type!=\"na\")
                        msg+=\"<tr><td>\"+input(in_add_".$this->table."[k])+\"</td></tr>\";
                        else
                        msg+=input(in_add_".$this->table."[k]);
                    });
                    msg+=\"</table></form>\";
                bootbox.dialog({
                    'title':'Add',
                    'message':msg,
                    buttons: {
                                Submit: {
                                    label: \"<i class='fas fa-save'></i> &nbsp;Save\",
                                    className: 'btn-outline-success',
                                    callback: function(){
                                        var myret=false;
                                        var formData = new FormData($('#frmAdd')[0]);
                                        $.ajax({
                                                    type: \"POST\",
                                                    url: '".$url."',
                                                    data: formData,
                                                    //use contentType, processData for sure.
                                                    contentType: false,
                                                    processData: false,
                                                    beforeSend: function() {
                                                        
                                                    },
                                                    success: function(msg) {
                                                        var mmsg=$.parseJSON(msg);
                                                        
                                                        if(mmsg.msg==undefined){
                                                            jAlert(\"Successfully Added\",'',function(){
                                                                dtble.ajax.reload(null, false);
                                                            });
                                                            bootbox.hideAll();
                                                        }else{
                                                              jAlert(mmsg.msg,'',function(){
                                                                dtble.ajax.reload(null, false);
                                                                
                                                              if(mmsg.xreturn==undefined || mmsg.xreturn==true){
                                                                 bootbox.hideAll();
                                                              }
                                                            });
                                                        }
                                                        
                                                        
                                                    },
                                                    error: function() {
                                                      
                                                    }
                                                });
                                         return false;       
                                    }
                                },
                                Cancel: {
                                    label: \"<i class='fas fa-window-close'></i> &nbsp;Cancel\",
                                    className: 'btn-outline-danger ',
                                    callback: function(){
                                        
                                    }
                                },
                            }
                })

            }";
            #".base_url().$this->router->fetch_class()."\addData"

          

            return $x;
        }
        
        public function genJSAddSub(){
            $url=base_url().$this->CI->router->fetch_class()."/addDataSub";
            $x="
            function btnAdd_".$this->table."(){

                var msg=\"<form id='frmAdd'><table class='table table-bordered table-sm'>\";
                    $.each(in_add_".$this->table.",function(k){
                        if(in_add_".$this->table."[k].type!=\"hidden\" && in_add_".$this->table."[k].type!=\"na\")
                        msg+=\"<tr><td>\"+input(in_add_".$this->table."[k])+\"</td></tr>\";
                        else
                        msg+=input(in_add_".$this->table."[k]);
                    });
                    msg+=\"</table></form>\";
                bootbox.dialog({
                    'title':'Add',
                    'message':msg,
                    buttons: {
                                Submit: {
                                    label: \"<i class='fas fa-save'></i> &nbsp;Save\",
                                    className: 'btn-outline-success',
                                    callback: function(){
                                        var formData = new FormData($('#frmAdd')[0]);
                                            formData.append('".@$this->CI->subdb->fkey."',vdata);
                                        $.ajax({
                                                    type: \"POST\",
                                                    url: '".$url."',
                                                    data: formData,
                                                    //use contentType, processData for sure.
                                                    contentType: false,
                                                    processData: false,
                                                    beforeSend: function() {
                                                        
                                                    },
                                                    success: function(msg) {
                                                        jAlert(\"Successfully Added\",'',function(){
                                                          dtble_sub.ajax.reload(null, false);
                                                        });
                                                    },
                                                    error: function() {
                                                      
                                                    }
                                                });
                                    }
                                },
                                Cancel: {
                                    label: \"<i class='fas fa-window-close'></i> &nbsp;Cancel\",
                                    className: 'btn-outline-danger ',
                                    callback: function(){
                                        
                                    }
                                },
                            }
                })

            }";

            #".base_url().$this->router->fetch_class()."\addData"
            return $x;
        }

        public function genJSEdit(){
           # $url=base_url().$this->CI->router->fetch_class()."\updateData";
            $x="
            function btnUpdate_".$this->table."(e){
                            $.post('".base_url().$this->CI->router->fetch_class()."/getInfo',{id:e},function(xres){
                                            var msg=\"<form id='frmEdit'><table class='table table-bordered table-sm'>\";
                                                $.each(in_add_".$this->table.",function(k){
                                                    if(in_add_".$this->table."[k].type!=\"hidden\" && in_add_".$this->table."[k].type!=\"na\")
                                                    msg+=\"<tr><td>\"+input(in_add_".$this->table."[k],xres[k])+\"</td></tr>\";
                                                    else
                                                    msg+=input(in_add_".$this->table."[k],xres[k]);
                                                    
                                                });
                                                msg+=\"</table></form>\";
                                                    bootbox.dialog({
                                                        'title':'Edit',
                                                        'message':msg,
                                                        buttons: {
                                                                    Submit: {
                                                                        label: \"<i class='fas fa-save'></i> &nbsp;Save\",
                                                                        className: 'btn-outline-success',
                                                                        callback: function(){
                                                                            var formData = new FormData($('#frmEdit')[0]);
                                                                            $.ajax({
                                                                                        type: \"POST\",
                                                                                        url: '".base_url().$this->CI->router->fetch_class()."/updateData',
                                                                                        data: formData,
                                                                                        //use contentType, processData for sure.
                                                                                        contentType: false,
                                                                                        processData: false,
                                                                                        beforeSend: function() {
                                                                                            
                                                                                        },
                                                                                        success: function(msg) {
                                                                                            jAlert(\"Successfully Updated\",'',function(){
                                                                                            dtble.ajax.reload(null, false);
                                                                                            });
                                                                                        },
                                                                                        error: function() {
                                                                                        
                                                                                        }
                                                                                    });
                                                                        }
                                                                    },
                                                                    Cancel: {
                                                                        label: \"<i class='fas fa-window-close'></i> &nbsp;Cancel\",
                                                                        className: 'btn-outline-danger ',
                                                                        callback: function(){
                                                                            
                                                                        }
                                                                    },
                                                                }
                                                    })
                            },\"json\");
                }
                ";
                
              
                return $x;

        }
        
        public function genJSEditSub(){

            $x="
            function btnUpdate_".$this->table."(e){
                            $.post('".base_url().$this->CI->router->fetch_class()."/getInfoSub',{id:e},function(xres){
                                            var msg=\"<form id='frmEdit'><table class='table table-bordered table-sm'>\";
                                                $.each(in_add_".$this->table.",function(k){
                                                    if(in_add_".$this->table."[k].type!=\"hidden\")
                                                    msg+=\"<tr><td>\"+input(in_add_".$this->table."[k],xres[k])+\"</td></tr>\";
                                                    else
                                                    msg+=input(in_add_".$this->table."[k],xres[k]);
                                                    
                                                });
                                                msg+=\"</table></form>\";
                                                    bootbox.dialog({
                                                        'title':'Edit',
                                                        'message':msg,
                                                        buttons: {
                                                                    Submit: {
                                                                        label: \"<i class='fas fa-save'></i> &nbsp;Save\",
                                                                        className: 'btn-outline-success',
                                                                        callback: function(){
                                                                            var formData = new FormData($('#frmEdit')[0]);
                                                                            $.ajax({
                                                                                        type: \"POST\",
                                                                                        url: '".base_url().$this->CI->router->fetch_class()."/updateDataSub',
                                                                                        data: formData,
                                                                                        //use contentType, processData for sure.
                                                                                        contentType: false,
                                                                                        processData: false,
                                                                                        beforeSend: function() {
                                                                                            
                                                                                        },
                                                                                        success: function(msg) {
                                                                                            jAlert(\"Successfully Updated\",'',function(){
                                                                                            dtble_sub.ajax.reload(null, false);
                                                                                            });
                                                                                        },
                                                                                        error: function() {
                                                                                        
                                                                                        }
                                                                                    });
                                                                        }
                                                                    },
                                                                    Cancel: {
                                                                        label: \"<i class='fas fa-window-close'></i> &nbsp;Cancel\",
                                                                        className: 'btn-outline-danger ',
                                                                        callback: function(){
                                                                            
                                                                        }
                                                                    },
                                                                }
                                                    })
                            },\"json\");
                }
                ";

              
                return $x;
    

        }
        
        public function genJSDelete(){
              
              $x="
               function btnDelete_".$this->table."(e){
                            jConfirm('Are you sure to remove the record?', 'Remove Record', function(r) {
                                
                                if(r==true){
                                    $.post('".base_url().$this->CI->router->fetch_class()."/deleteData',{id:e},function(xres){
                                        jAlert(\"Successfully Removed\",\"Remove Record \",function(){
                                             dtble.ajax.reload(null, false);
                                        });
                                    });
                                }

                            });
                        }
                ";

                return $x;
        }
        
        public function genJSDeleteSub(){
              
              $x="
               function btnDelete_".$this->table."(e){
                            jConfirm('Are you sure to remove the record?', 'Remove Record', function(r) {
                                
                                if(r==true){
                                    $.post('".base_url().$this->CI->router->fetch_class()."/deleteData',{id:e},function(xres){
                                        jAlert(\"Successfully Removed\",\"Remove Record \",function(){
                                             dtble_sub.ajax.reload(null, false);
                                        });
                                    });
                                }

                            });
                        }
                ";

               
                return $x;
        }
    
    }

?>