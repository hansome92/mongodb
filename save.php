<?php 
	
	require_once('config.php');
    require_once('Plan.php');

	function getcontent($filename, $arg) {
		try {
			$myfile = fopen($filename, "r") or die("Unable to open file!");
			$str =  fread($myfile, filesize("request.xml"));

            foreach ($arg as $key => $a) {
                if (isset($a)) {
                    $str = str_replace("#" . $key . "#", $a, $str);
                }
            }
			return $str;
		}	
		catch(Exception  $e){
            echo 'Message: ' .$e->getMessage();die("Error");
    	}
		return null;
		
	}
	
	function send_requst($url, $xmlRequest) {
		try{
            $ch = curl_init();

            $headers = array(
			    "Content-type:application/xml"			     
			 );
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);

            // send xml request to a server

            curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);

            curl_setopt($ch, CURLOPT_POSTFIELDS,  $xmlRequest);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $data = curl_exec($ch);
            curl_close($ch);
            //convert the XML result into array
            if($data === false){
                $error = curl_error($ch);
                echo $error; 
                die('error occured');
            }else{           
                
                $data = json_decode(json_encode(simplexml_load_string($data)), true);  
                return $data;
            }
            

        }catch(Exception  $e){
            echo 'Message: ' .$e->getMessage();die("Error");
    	}

    	return null;
	}

    $postcode = isset($_POST["code"]) ? $_POST["code"] : "";
    $country = isset($_POST["country"]) ? $_POST["country"] : "";
    $state = isset($_POST["state"]) ? $_POST["state"] : "";
    $age = isset($_POST["age"]) ? $_POST["age"] : "";
    $gender = isset($_POST["gender"]) ? $_POST["gender"] : "";
    $tobacco = isset($_POST["tobacco"]) ? $_POST["tobacco"] : "";
    $page = isset($_POST["page"]) ? $_POST["page"] : 1;

    $age = (int)$age;
    if ($age >= 19 ||  $age <= 64) {
        
    }
    else {
        die();
    }

    $year =  (int)date("Y") - $age;


    if ($postcode == "") {

    }
    else {
        if ($country == "") {
         	$xml = getcontent('zip.xml', array('ZIP' => $postcode));            
            if ($xml != null) {
                $data = send_requst(API_URL, $xml);                    
                if (!empty($data)) {
                    $country = $data["CountiesForPostalCodeResponse"]["Counties"]["County"][0]["CountyName"];
                    $state = $data["CountiesForPostalCodeResponse"]["Counties"]["County"][0]["StateCode"];
                    echo json_encode(
                        array("country" => $country,
                        "state" => $state)
                    );
                    die();
                }
            }   
        }
        else {

                $xml = getcontent('request.xml', array('COUNTRY' => $country, 'STATE' => $state, 'ZIP' => $postcode, 
                    'YEAR' => $year, 'GENDER' => $gender, 'TOBACCO' => $tobacco, "PAGE" => $page));
                $data = send_requst(API_URL, $xml);   
                $country = "";  
                $state = "";
                $plans = null;
                $total = $data["PlansForIndividualOrFamilyResponse"]["TotalEligiblePlansQuanity"];

                if (!empty($data["PlansForIndividualOrFamilyResponse"]["Plans"]["Plan"])) {
                    foreach ($data["PlansForIndividualOrFamilyResponse"]["Plans"]["Plan"] as $p) {
                        
                        $plans[] = array(
                            'PlanID' => $p["PlanID"], 'PlanNameText' => $p["PlanNameText"],
                            'ProductID' => $p["ProductID"], 'ProductNameText' => $p["ProductNameText"],
                            'IssuerID' => $p["IssuerID"], 'IssuerNameText' => $p["IssuerNameText"],
                            'IssuerStateCode' => $p["IssuerStateCode"]
                            );
                    }    
                }

                if ($plans != null) {
                    
                   // die;

                    $db = new Plan();
                    $db->addPlan(array('gender' => $gender,
                        'tobacco_user' => $tobacco, 'code' => $postcode, 
                        'age'=>$age,  'pagenum' =>  $page,
                         $postcode => $plans));
                    echo "next";
                }
                else {
                    echo "ok";
                }
                die();

        }

    }
?>
