 
 <?php 
 const n = 10 ; 
 { 
  	 for( $i = 1 ; $i <= n ; $i++ ) 
  	 { 
  	  	 $a = 1 ; 
  	  	 for( $j = 1 ; $j <= $i ; $j++ ) 
  	  	 { 
  	  	  	 $a = $a * $j ; 
  	  	  	} 
  	  	 if( $a > 8 ) 
  	  	 { 
  	  	  	 echo  $a ; 
  	  	  	 echo '<br />'; 
  	  	  	} 
  	  	} 
  	}