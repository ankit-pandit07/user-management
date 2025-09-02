<?php
require_once 'assets/php/admin-header.php';
require_once 'assets/php/admin-db.php';
$count= new Admin();
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card-deck mt-3 text-light text-center font-weight-bold">
            <div class="card bg-primary">
                <div class="card-header">Total Users</div>
                <div class="card-body">
                    <div class="display-4">
                        <?= $count->totalCount('users'); ?>
                    </div>
                </div>
            </div>
            <div class="card bg-warning">
                <div class="card-header">Verified Users</div>
                <div class="card-body">
                    <div class="display-4">8</div>
                </div>
            </div>
            <div class="card bg-success">
                <div class="card-header">Unverified Users</div>
                <div class="card-body">
                    <div class="display-4">4</div>
                </div>
            </div>
            <div class="card bg-danger">
                <div class="card-header">Website Hits</div>
                <div class="card-body">
                    <h1 class="display-4">
                        <?php $data=$count->site_hits(); echo $data['hits']; ?>
                    </h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card-deck mt-3 text-light text-center font-weight-bold">

                <div class="card bg-danger">
                    <div class="card-header">Total Notes</div>
                    <div class="card-body">
                        <h1 class="display-4">
                            <?= $count->totalCount('notes');?>
                        </h1>
                    </div>
                </div>
                <div class="card bg-success">
                    <div class="card-header">Total Feedback</div>
                    <div class="card-body">
                        <h1 class="display-4">
                             <?= $count->totalCount('feedback');?>
                        </h1>
                    </div>
                </div>
           
            <div class="card bg-info">
                <div class="card-header">Total Notification</div>
                <div class="card-body">
                    <h1 class="display-4">
                         <?= $count->totalCount('notification');?>
                    </h1>
                </div>
            </div>
             </div>
            </div>
        </div>
    </div>
</div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card-deck my-3">
            <div class="card border-success">
                <div class="card-header bg-success text-center text-white lead">
                    Male/Female User's Percentage
                </div>
                <div id="chartOne" style="width:99%; height:400px;">
                </div>
                </div>
            <div class="card border-info">
                <div class="card-header bg-info text-center text-white lead">
                    Verified/Unverified User's Percentage
                </div>
                <div id="chartTwo" style="width: 99%; height: 400px;">
                </div>
           
            </div>
        </div>
    </div>

<!-- Footer Area -->
</div>
</div>
</div>

  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

         //Check Notification
   checkNotification();
    function checkNotification(){
        $.ajax({
            url:'assets/php/admin-action.php',
            method:'post',
            data:{action:'checkNotification'},
            success:function(response){
                $("#checkNotification").html(response);
            }
        })
    }
        
          // Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(pieChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function pieChart() {

        // Create the data table.
        var data = new google.visualization.arrayToDataTable([
            ['Gender','Number'],
            <?php
            $gender=$count->genderPer();
            foreach($gender as $row){
                echo '["'.$row['gender'].'",'.$row['number'].'],';
            }
            ?>
        ]);

        // Set chart options
        var options = {
            is3D:false
        }

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chartOne'));
        chart.draw(data, options);
      }
    </script>
</body>
</html>