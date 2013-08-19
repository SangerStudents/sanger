<?php

//script for getting document statistics 

session_start();
include("../includes/header.php");
include("dblayer3.php"); //include my db layer --JR

//debugging
if ($_GET['verbose']) { //to enable debugging messages, add ?verbose=TRUE to the URL, after search.php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
}
?>

<h1>Statistics</h1>

<h2>Top 20 Mentioned Places</h2> 
<?php 
$placesQuery = "select name, count(name) c from mentioned_places group by name having c>0 order by c desc limit 20"; 
$placesResult = mysql_query($placesQuery); 

echo "<table>"; 
while ($row = mysql_fetch_array($placesResult)) { 
	extract($row); 
	echo "<tr><td>$name</td><td>$c</td></tr>"; 
} 
echo "</table>"; 
?>

<!-- Google Charts Stuff --> 
<!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
   
      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});
     
      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);


      // Callback that creates and populates a data table, 
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

      // Create the data table.
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Topping');
      data.addColumn('number', 'Slices');
      data.addRows([
        ['Mushrooms', 3],
        ['Onions', 1],
        ['Olives', 1], 
        ['Zucchini', 1],
        ['Pepperoni', 2]
      ]);

      // Set chart options
      var options = {'title':'How Much Pizza I Ate Last Night',
                     'width':400,
                     'height':300};

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }
    </script>
 
<!--Div that will hold the pie chart-->
    <div id="chart_div" style="width:400; height:300"></div>

<?php include("../includes/footer.php"); ?>
