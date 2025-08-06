<?php 
   Class Auth_model extends CI_Model {
	
      Public function __construct() { 
         parent::__construct(); 
      } 
		
   
      public function UserLogin($user="",$pass=""){

        $userinfo= $this->db->where("uname",$user)
                  ->where("pword",$pass)
                  ->get('tbl_user')->row();

         return $userinfo;

      }

      public function LDAPUserLogin($user="",$pass=""){
         
      

         $adServer = "ldap://NWFTH.COM";
         $ldap = ldap_connect($adServer)  or  die("Couldn't connect to AD!");
         
         $ldaprdn = 'NWFTH' . "\\" . $user;
         ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
         ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
        
         $bind = @ldap_bind($ldap, $ldaprdn, $pass);

         ldap_unbind($ldap);

         $userinfo = null;

         if($bind)
            $userinfo= $this->db->where("uname",$user)
                              ->get('tbl_user')->row();


         return $userinfo;
         
       }
      

   
   } 

?>