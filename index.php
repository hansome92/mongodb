<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>Blog Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->   

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<script src="js/jquery.min.js"></script>
  <script type="text/javascript">

    var url="http://localhost/local/mongo/save.php";
    window.pos = 0;

    function getCode() {
      
      $.post(url, {code : window.codes[window.pos]}).done(function(data) {   
      //alert(data);
        try{
            var jd = JSON.parse(data);
        }catch(e){
            $(window.td2).append("error<br>");
            $("#page").val("1");
            $("#country").val("");
            $("#state").val("");  
            window.pos ++;
            setTimeout(getCode, 50);
             // return;
         }
        
          $(window.td2).append('<span style="color:red" id="prog' + window.pos + '">' + jd.country + '</span>');

          if (window.pos == window.codes.length)
            return;
          $("#code").val(window.codes[window.pos]);   
          $("#country").val(jd.country);
          $("#state").val(jd.state);    

          setTimeout(savePlan, 50);
          
      });
    }

    function savePlan() {
      
      $.post(url, $("#mainform").serialize()).done(function(data) {        

          $(window.td2).append(data);
          if (data == "next") {
            var page = $("#page").val();page++;
            $("#page").val(page);
            setTimeout(savePlan, 50);
          }
          else {
             $(window.td2).append("<br>");
            $("#page").val("1");
            $("#country").val("");
            $("#state").val("");  
            window.pos ++;
            setTimeout(getCode, 50);
          }
          
      });
    }

    $(document).ready(function() {

        var text = $(".table").find("tr:nth-child(2)").find("td:nth-child(1)").html();
        window.td2 = $(".table").find("tr:nth-child(2)").find("td:nth-child(2)");
        var attr = text.split("<br>");
        var codes = [];

        for (var i = 0; i < attr.length; i++) {      
          //var param = attr[i].split(":");
          //if (param.length > 1)
            codes.push(attr[i].trim());  
        }
        window.codes = codes;

        $("#scenario").change(function() {
          
          switch ($(this).val()) {
            case "1":
              $("#tobacco").val("Non-Smoker");
              $("#gender").val("Male");
              break;
            case "2":
              $("#tobacco").val("Smoker");
              $("#gender").val("Male");
              break;
            case "3":
              $("#tobacco").val("Smoker");
              $("#gender").val("Female");
              break;
            case "4":
              $("#tobacco").val("Non-Smoker");
              $("#gender").val("Female");
              break;
          }
        });

       $("#start").click(function() {
         $(window.td2).html();
         var timeid = setTimeout(getCode, 100);
       });
    });
    
  </script>
  <style type="text/css">
    .page {
      margin:40px auto;
      width:800px;

    }

    .page h1 {
      text-align: center;
    }
  </style>
  </head>

  <body>
      <div class="page">
      <h1>Save Data To Mongodb</h1>
   
      <div class="row">
     
        <form id="mainform" action="" method="post">
          <div style="display:none;">
            <input type="text" id="code" name="code" value="" /> <br/>
            <input type="text" id="country" name="country" value="" /> <br/>
            <input type="text" id="state" name="state" value="" > <br/>
            <label>Page</label> <br> <input type="text" id="page" name="page" value="1" ></br>
          </div>
          <div >
            <div class="col-xs-6 col-sm-3 placeholder">

          <label>Gender</label> <br> <input type="text" id="gender" name="gender" value="Male" ></br>
            <label>Tobacco</label> <br> <input type="text" id="tobacco" name="tobacco" value="Non-Smoker" ></br>
            <label>Age</label> <br> <input type="text" id="age" name="age" value="19" ></br>
            </div>
            <div class="col-xs-6 col-sm-3 placeholder">
           <label>Scenario</label> <br> 
          <select id="scenario">
          <option value="1">Scenario 1: Male Non Smoker </option>
          <option value="2">Scenario 2: Male Smoker  </option>
          <option value="3">Scenario 3: Female Smoker   </option>
          <option value="4">Scenario 4: Female Non Smoker  </option>
        </select> <br/> <br/>
        <input type="button" id="start" class="btn" value="START"></input></div>
      </div>
        </form>
        
        
      </div>
      <div class=" row table-responsive"><br/>
	<table class="table table-striped">
		<tr>
			<th>Country	:ZIPCODE	
			</th>
			<th>
        Process
      </th>
		</tr>

		<tr>
      <td>
94501<br>
96120<br>
95629<br>
95222<br>
95912<br>
94505<br>
95531<br>
95614<br>
93210<br>
95920<br>
92227<br>
92328<br>
93203<br>
96056<br>
90001<br>
93601<br>
94901<br>
93623<br>
95410<br>
93620<br>
96006<br>
93512<br>
93426<br>
94503<br>
95602<br>
95923<br>
91752<br>
95608<br>
95023<br>
91701<br>
91901<br>
94101<br>
95202<br>
93401<br>
94002<br>
93013<br>
94022<br>
96001<br>
95568<br>
94510<br>
94922<br>
95230<br>
95645<br>
96021<br>
93207<br>
95310<br>
91320<br>
95605<br>
95692<br>
      </td>
      <td>

      </td>
    
		</tr>
	</table> </div><!-- table -->
   </div>
  </body>
</html>
