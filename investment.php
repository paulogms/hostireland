<?php
	
	function investment(){
		
		function numberType($val){ // this function is for gain/loss percentage negative or positive
			
			if($val>=0) 
				return "+".$val;
			else
				return $val;
			
		}
		
		if(isset($_POST["obj"])){
			
			$printTable = "<table id='printTable'>";    // making the print table
			$printTable.= "<th>Name of the rocket</th>";
			$printTable.= "<th>Launch status</th>";
			$printTable.= "<th>gain/loss percentage</th>";
			$printTable.= "<th>balance of your investment</th>";
			
			$investment=100000; // original investment
			$j=$fail=$previousFail=0;
			$models = array();
			$reuse = array();
			$obj = json_decode($_POST["obj"]);  // retrieve data form the api by post
			
			for($i=0;$i<count($obj);$i++){
				
				$percent=12;  // the normal percent after luanch success is 12%
				$gain_loss_percent=0;
				$rocket_model = $obj[$i]->rocket    
										->rocket_id; // getting rocket_id?
			    $rocket_name = $obj[$i]->rocket 
										->rocket_name; // getting rocket_name?
										
				$launch_success =  ($obj[$i]->launch_success == 1) ? 1 : 0;  // 1 for true , 0 for false
				$launch_success_state =  ($launch_success == 1) ? "success" : "fail";
				
				$reuse['cores'] =  $obj[$i]->rocket 
									 ->first_stage
									 ->cores[0]
									 ->reused; // getting cores reused
				$reuse['payloads'] =  $obj[$i]->rocket
									 ->second_stage
									 ->payloads[0]
									 ->reused; // getting payloads reused
				$reuse['core'] = $obj[$i]->reuse 
										 ->core; // getting core reused
				$reuse['side_core1'] = $obj[$i]->reuse
										 ->side_core1; // getting side_core1 reused
				$reuse['side_core2'] = $obj[$i]->reuse
										 ->side_core2; // getting side_core2 reused
				$reuse['fairings'] = $obj[$i]->reuse 
										 ->fairings; // getting fairings reused
				$reuse['capsule'] = $obj[$i]->reuse
										 ->capsule; // getting capsule reused
					

				// 1 for true , 0 for false
				
				$reuse['cores'] =  ($reuse['cores'] == 1) ? 1 : 0; 
				$reuse['payloads'] =  ($reuse['payloads'] == 1) ? 1 : 0;
				$reuse['core'] =  ($reuse['core'] == 1) ? 1 : 0;
				$reuse['side_core1'] =  ($reuse['side_core1'] == 1) ? 1 : 0;
				$reuse['side_core2'] =  ($reuse['side_core2'] == 1) ? 1 : 0;
				$reuse['fairings'] =  ($reuse['fairings'] == 1) ? 1 : 0;
				$reuse['capsule'] =  ($reuse['capsule'] == 1) ? 1 : 0;
				
				if(!(in_array($rocket_model,$models))){  // here iam testing if a new rocket model is invented if true investment will increase by 30%
				
					$models[]=$rocket_model;
					$j++;					
					$gain_loss_percent+=30;
				}
				
				if($launch_success==1){
					// here iam testing if a componeent are reused if true investment will increase by 4% of each component reused + 12%
					
					if($reuse['cores']==1) $percent+=4;
					if($reuse['payloads']==1) $percent+=4;
					if($reuse['core']==1) $percent+=4;
					if($reuse['side_core1']==1) $percent+=4;
					if($reuse['side_core2']==1) $percent+=4;
					if($reuse['fairings']==1) $percent+=4;
					if($reuse['capsule']==1) $percent+=4;
					$gain_loss_percent+=$percent;
					$fail=0;
					
				}else{ //here launch_success is fail so investment will decrease by 60%
				
					$gain_loss_percent-=60;
					
					if($fail==1){ // here we test if previous fail true investment will decrease by 3% and this condition stays true till launch_success is success
					$gain_loss_percent-=3;
					} 
					$fail=1; // fail variable is 1 mean  launch_success is failed
					
				}
				$investment = $investment + $investment * $gain_loss_percent/100;
				
				$printTable.= "<tr>";
				$printTable.= "<td>".$rocket_name."</td>";
				$printTable.= "<td>".$launch_success_state."</td>";
				$printTable.= "<td>".numberType($gain_loss_percent)."%</td>";
				$printTable.= "<td>".$investment."</td>";
				$printTable.= "</tr>";
			
		}
		$printTable.= "</table>";
		
		echo $printTable; // displaying the table
		}
	
	}
	echo investment();

?>