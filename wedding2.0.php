<!DOCTYPE html>
<html>
<head>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script>
	<!-- Used to style and give the aesthetics to the website -->
	<style type="text/css">
		body{
		  background-color:#328db8;}
		  font-family:Arial;}
		input[type=text] {
		  padding: 12px 20px;
		  margin: 8px 0;
		  box-sizing: border-box;
		  border: solid red;}
		table.table{
		  font-size:1.5em;
		  text-align:center;
		  width:60%;
		  margin-left:auto;
		  margin-right:auto;
		  border-style:outset;
		  border-width:0.3em;
		  border-color:#263369;}
		td.cell1{
		  border-style:inset;
		  border-width:0.15em;
		  border-color:#5f5f96;
		  background-color:#9999ff;}
		td.cell2{
		  border-style:inset;
		  border-width:0.15em;
		  border-color:#5f5f96;
		  background-color:#39d4ad;
		}
		th{
		  border-style:inset;
		  border-width:0.15em;
		  border-color:#5f5f96;
		  background-color:#9999ff;
		}
		td:hover{
		  background-color:#c2482d;}
	</style>
	<title> Plan your wedding with us </title> 
</head>
<script type="text/javascript">
	//executing and getting the results from the website
	function executeQuery(){
		
		//getting the email and checking if it is valid
		var email = $("#email").val();
		var check = false;
		if (email.includes("@")){
			check = true;
		}
		
		//getting the variables of the dates and party size as well as the catering grade
		var firstDate = $("#1date").val();
		var secondDate = $("#2date").val();
		var grade = $('#grade').val();
		var min = $("#min").val();
		var max = $("#max").val();
		var htm ="";
		
		//removing all the existing elements in order to get up to date data
		$('#res').empty();
		
		//validating that all user's input is correct
		if(firstDate != null && secondDate != null && max!=null && min!=null && check && firstDate <= secondDate && grade != null && grade>=1 && grade<=5){
			
			//printing a message showing to the user how to do it
			$("#validateEmail").text("An email will be sent soon to " + email);
			$("#weaving").text("Hello " + $("#name").val());
			
			//setting up the first date that we will check, the m variable is used to differiciate the tables with the results and help show the dates
			//the d variable keeps the dates examined and the m variable is used to print the tables and diferentiate the tables
			var currentD = firstDate;
			var day = 0;
			var d = [];
			var m = 0;
			var f = true;
			
			
			//ensuring that the the date we check is not outside of the period we were checking
			while(f){
			
			//it is used to get the correct date that is checked with the query
			d[day] = currentD;
			day = day + 1;
			if (currentD == secondDate){
				f = false;
			}
			
			//the sql query is assembled
			var sql = 'SELECT venue.venue_id, venue.name, venue.capacity, venue.weekend_price, venue.weekday_price, venue.licensed, catering.cost FROM `venue` LEFT JOIN `catering` ON venue.venue_id = catering.venue_id WHERE catering.grade = '+grade+' AND venue.venue_id NOT IN( SELECT `venue_id` FROM `venue_booking` WHERE `date_booked` = "'+ currentD +'" ) AND venue.capacity >= '+min+' AND venue.capacity <= '+max+' '
	
			//executing the query through the results.php on the wedding sub-directory
			$.post("results.php",{sql:sql},function(json, status){ 
					//if the query is successful then the table will be created
					htm = "";
					$('#res').append('<p>Date: ' + ("" + d[m]) +'</p>');
					if(json && json[0]){	
					
						//creating the table, the m variable is used to differiciate the tables with the results, also to show the dates that were examined
						$("#res").append('<table class="table" id="results'+ m +'" table>');
								
						$('#results'+ m +'').append("<tr><td class='cell1'>ID</td>" + 
						"<td class='cell1'>Name</td>" +
						"<td class='cell1'>Capacity</td>" +
						"<td class='cell1'>Weekend Price</td>" +
						"<td class='cell1'>Weekday Price</td>"+
						"<td class='cell1'>Licensed</td>" + 
						"<td class='cell1'>Cost</td>" +
						"</tr>");
						
						//creating the result part of the table
						for(i=0;i<json.length;i++){
							var license = "";
							if(json[i].licensed == 1){
								license= "Yes";
							}else{
								license="No";
							}
							
							$('#results'+ m +'').append("<tr>"+
							'<td class="cell2">'+ json[i].venue_id + '</td>' +
							'<td class="cell2">'+ json[i].name + '</td>'+
							'<td class="cell2">'+json[i].capacity +'</td>'+
							'<td class="cell2">'+ json[i].weekend_price + '</td>'+
							'<td class="cell2">'+ json[i].weekday_price + '</td>'+
							'<td class="cell2">'+ license + '</td>'+
							'<td class="cell2">'+ json[i].cost + '</td>'+
							"</tr>");
						}
						
						//finishing the table
						$('#results'+ m +'').append('</table></br>');
						m = m + 1;
						
						
					}else{
						//if the JSON file is not an array then a message is printed
						htm= "<h4>No venue is available for this date</h4></br>";
						m = m + 1;
					}
					
					//the message is presented to the user
					$("#res").append(htm);
			},'json');
			//getting the next date to be checked
			currentD = getDates(currentD);
			}
		}else{
			//in case that the inputs are incorrect then this message is printed
			$("#res").append("Some of the information you have inputted is incorrect");
		}
		
	}
	
	// the function is used to get the date of the date following the one inputted
	function getDates(fDate){
		//breakind up the date in the elements making it up
		var firstDate = fDate.split('-');
		
		//checking if it is the last date of the month, if so it changes the month
		if(parseInt(firstDate[2])==30 || parseInt(firstDate[2])==31 || (parseInt(firstDate[2])==28 && firstDate[1]=="02") || (parseInt(firstDate[2])==29 && firstDate[1]=="02")){
			if(firstDate[1]=="02"){
				firstDate[1] = "03";
				firstDate[2] = "01";
			} else if((firstDate[1]=="01" || firstDate[1]=="03" || firstDate[1]=="05" || firstDate[1]=="07" || firstDate[1]=="08" || firstDate[1]=="10" || firstDate[1]=="12") && parseInt(firstDate[2])==31){
				firstDate[1] = "0" + (parseInt(firstDate[1]) + 1);
				firstDate[2] = "01";
			} else if((firstDate[1]=="01" || firstDate[1]=="04" || firstDate[1]=="06" || firstDate[1]=="09" || firstDate[1]=="11") && parseInt(firstDate[2])==30){
				firstDate[1] = "0" + (parseInt(firstDate[1]) + 1);
				firstDate[2] = "01";
			}
        }else{
			if (parseInt(firstDate[2])<9) firstDate[2] = "0" + (parseInt(firstDate[2]) + 1);
			else firstDate[2] = "" + (parseInt(firstDate[2]) + 1);
        }
		//assembling the final string and returning it back
		var res = firstDate[0] + "-" + firstDate[1] + "-" + firstDate[2];
		return res;
	}
	
</script>
<body>

<h1 align="center"> Plan your dream wedding with us</h1>
</br>
</br>

 <form id="wedding">
	<!-- Used in order to input name, surname, email and other information of the people that book a wedding-->
	<label for="name" id="labelName">Name:</label>
	<input type="text" size="16" maxlength="16" name="name" id="name" ><br>
	</br>
	<label for="surname" id="labelSurname">Surname: </label>
	<input type="text" size="16" maxlength="16" name="surname" id="surname"> <br/>
	</br>
	<label for="email" id="labelEmail"> Email:</label>
	<input type="text" size="16" name="email" id="email"> <br/>
	</br>
	<label for="1date" id="label1date"> Date from:</label>
	<input type="date" name="1date" id="1date" size="16" maxlength="8">

	<label for="2date" id="label2date"> to:</label>
	<input type="date" name="2date" id="2date" size="16" maxlength="8"></br>
	</br>
	<label for="max" id="labelMax"> Max party size: </label>
	<input type="number" name="max" id="max" size="16" maxlength="8"></br>
	</br>
	<label for="min" id="labelMin"> Min party size: </label>
	<input type="number" name="min" id="min" size="16" maxlength="8"></br>
	</br>
	<label for="grade" id="lableGrade"> Catering Grade:</label>
	<input type="number" id="grade" name="grade" size="16" maxlength="8" min="1" max="5"></br>
	</br>
	<input type="button" id="btn" value="Submit information" onclick="executeQuery();">

 </form>
<!-- Used to show the reuslts of the search-->
<b id="weaving"></b>
</br>
<b id="validateEmail"></b>

<table class="table" id="costs"></table>
</br>
<div id="res"></div>
</body>
</html>