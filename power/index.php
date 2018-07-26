<?
require_once("globals.php");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <p>&nbsp;</p>
    <div class="container" id="loading" align="center">
      <div class="jumbotron">
        <i class="fa fa-cog fa-spin fa-5x fa-fw"></i>
        <span class="sr-only">Loading...</span>
        <h1>Initializing Connection</h1>
      </div>
    </div>
    <div class="container" id="error" align="center" style="display:none;">
      <div class="jumbotron">
        <i class="fa fa-cog fa-spin fa-5x fa-fw"></i>
        <span class="sr-only">ERROR</span>
        <h1>NETWORK ERROR</h1>
        <p>Unable to communicate with device at <?=SWITCH_IP?>.  Please verify connections.  <br />This page will automatically reload when a connection is restored.</p>
      </div>
    </div>
    <div class="container" id="power-panel" style="display:none;">
      <div class="row">
        <div class="col-xs-6 col-md-3">
          <div class="panel panel-success" id="outlet-1-panel">
            <div class="panel-heading">
              <h3 class="panel-title"><span class="outlet-1-name">OUTLET 1</span></h3>
            </div>
            <div class="panel-body" align="center">
              <button type="button" class="btn btn-default btn-lg outlet-btn" data-outlet="1">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span> POWER <span class="outlet-1-button-state">OFF</span>
              </button>
            </div>
          </div>
        </div>
        <div class="col-xs-6 col-md-3">
          <div class="panel panel-success" id="outlet-2-panel">
            <div class="panel-heading">
              <h3 class="panel-title"><span class="outlet-2-name">OUTLET 2</span></h3>
            </div>
            <div class="panel-body" align="center">
              <button type="button" class="btn btn-default btn-lg outlet-btn" data-outlet="2">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span> POWER <span class="outlet-2-button-state">OFF</span>
              </button>
            </div>
          </div>
        </div>
        <div class="col-xs-6 col-md-3">
          <div class="panel panel-success" id="outlet-3-panel">
            <div class="panel-heading">
              <h3 class="panel-title"><span class="outlet-3-name">OUTLET 3</span></h3>
            </div>
            <div class="panel-body" align="center">
              <button type="button" class="btn btn-default btn-lg outlet-btn" data-outlet="3">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span> POWER <span class="outlet-3-button-state">OFF</span>
              </button>
            </div>
          </div>
        </div>
        <div class="col-xs-6 col-md-3">
          <div class="panel panel-success" id="outlet-4-panel">
            <div class="panel-heading">
              <h3 class="panel-title"><span class="outlet-4-name">OUTLET 4</span></h3>
            </div>
            <div class="panel-body" align="center">
              <button type="button" class="btn btn-default btn-lg outlet-btn" data-outlet="4">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span> POWER <span class="outlet-4-button-state">OFF</span>
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-6 col-md-3">
          <div class="panel panel-success" id="outlet-5-panel">
            <div class="panel-heading">
              <h3 class="panel-title"><span class="outlet-5-name">OUTLET 5</span></h3>
            </div>
            <div class="panel-body" align="center">
              <button type="button" class="btn btn-default btn-lg outlet-btn" data-outlet="5">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span> POWER <span class="outlet-5-button-state">OFF</span>
              </button>
            </div>
          </div>
        </div>
        <div class="col-xs-6 col-md-3">
          <div class="panel panel-success" id="outlet-6-panel">
            <div class="panel-heading">
              <h3 class="panel-title"><span class="outlet-6-name">OUTLET 6</span></h3>
            </div>
            <div class="panel-body" align="center">
              <button type="button" class="btn btn-default btn-lg outlet-btn" data-outlet="6">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span> POWER <span class="outlet-6-button-state">OFF</span>
              </button>
            </div>
          </div>
        </div>
        <div class="col-xs-6 col-md-3">
          <div class="panel panel-success" id="outlet-7-panel">
            <div class="panel-heading">
              <h3 class="panel-title"><span class="outlet-7-name">OUTLET 7</span></h3>
            </div>
            <div class="panel-body" align="center">
              <button type="button" class="btn btn-default btn-lg outlet-btn" data-outlet="7">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span> POWER <span class="outlet-7-button-state">OFF</span>
              </button>
            </div>
          </div>
        </div>
        <div class="col-xs-6 col-md-3">
          <div class="panel panel-success" id="outlet-8-panel">
            <div class="panel-heading">
              <h3 class="panel-title"><span class="outlet-8-name">OUTLET 8</span></h3>
            </div>
            <div class="panel-body" align="center">
              <button type="button" class="btn btn-default btn-lg outlet-btn" data-outlet="8">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span> POWER <span class="outlet-8-button-state">OFF</span>
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="panel panel-danger" id="power-off-panel">
            <div class="panel-heading">
              <h3 class="panel-title">ALL OUTLETS</h3>
            </div>
            <div class="panel-body" align="center">
              <button type="button" class="btn btn-default btn-lg all-outlet-off-btn" data-outlet="all">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span> POWER OFF
              </button>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="panel panel-success" id="power-on-panel">
            <div class="panel-heading">
              <h3 class="panel-title">ALL OUTLETS</h3>
            </div>
            <div class="panel-body" align="center">
              <button type="button" class="btn btn-default btn-lg all-outlet-on-btn" data-outlet="all">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span> POWER ON
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function(){
        $('.all-outlet-on-btn').click(function(){
          var button  = $(this);
          // DISABLE BRIEFLY
          $(this).prop("disabled",true);
          $.ajax({
              url: "proc.api.php?function=0",
              data: {},
              dataType: "json",
              success: function(data){
                updateOutletStates();
              },
              complete: function(data){
                $(button).prop("disabled",false);
              }
            });
        })
        $('.all-outlet-off-btn').click(function(){
          var button  = $(this);
          // DISABLE BRIEFLY
          $(this).prop("disabled",true);
          $.ajax({
              url: "proc.api.php?function=1",
              data: {},
              dataType: "json",
              success: function(data){
                updateOutletStates();
              },
              complete: function(data){
                $(button).prop("disabled",false);
              }
            });
        })
        $('.outlet-btn').click(function(){
          // GET OUTLET ID
          var outlet  = $(this).data('outlet');
          // DEFINE BUTTON
          var button  = $(this);
          // DISABLE BRIEFLY
          $(this).prop("disabled",true);
          if($('.outlet-'+outlet+'-button-state').html() == "OFF"){
            // OUTLET IS ON, TURN IT OFF
            // API CALL
            $.ajax({
              url: "proc.api.php?function=5&outlet="+outlet,
              data: {},
              dataType: "json",
              success: function(data){
                updateOutletStates();
              },
              complete: function(data){
                $(button).prop("disabled",false);
              }
            });
          }else{
            // OUTLET IS OFF, TURN IT ON
            // API CALL
            $.ajax({
              url: "proc.api.php?function=4&outlet="+outlet,
              data: {},
              dataType: "json",
              success: function(data){
                updateOutletStates();
              },
              complete: function(data){
                $(button).prop("disabled",false);
              }
            });
          }
        })

        // Call functions on page load
        updateOutletNames();
        updateOutletStates();

        // CREATE INTERVAL TO CONSTANTLY UPDATE NAMES AND STATES
        var interval = setInterval(function(){
          updateOutletNames();
          updateOutletStates();
          console.log("OUTLET NAMES AND STATES UPDATED")
        },2500);
        
        var networkError = false;

        function updateOutletNames(){
          // UPDATE OUTLET NAMES
          $.ajax({
            url: "proc.api.php?function=2",
            data: {},
            dataType: "json",
            timeout: "<?=NETWORK_TIMEOUTE?>",
            error: function(result){
              $("#error").fadeIn()
              $("#power-panel").hide();
              networkError = true;
              console.log('timeout/error');
            },
            success: function(data){
              // Detecting network error recovery -- reload page
              if(networkError){
                location.reload();
              }
              $("#error").hide()
              $("#power-panel").fadeIn();
              if( $("#loading").is(":visible") ){
                // Hide loading and bring in the power panel
                $("#loading").hide();
                $("#power-panel").fadeIn();
              }
              var outlet = 1;
              $.each(JSON.parse(data.result), function(k,v){
                $('.outlet-'+outlet+'-name').html(v);
                outlet++;
              })
            }
          });
        }

        function updateOutletStates(){
          // UPDATE OUTLET STATES
          $.ajax({
            url: "proc.api.php?function=3",
            data: {},
            dataType: "json",
            timeout: "<?=NETWORK_TIMEOUTE?>",
            error: function(result){
              $("#error").fadeIn()
              $("#power-panel").hide();
              networkError = true;
              console.log('timeout/error');
            },
            success: function(data){
              // Detecting network error recovery -- reload page
              if(networkError){
                location.reload();
              }
              $("#error").hide()
              $("#power-panel").fadeIn();
              var outlet = 1;
              $.each(JSON.parse(data.result), function(k,v){
                if(v){
                  // OUTLET IS ON
                  // UPDATE PANEL CLASS TO SUCCESS
                  $("#outlet-"+outlet+"-panel").removeClass("panel-danger").addClass("panel-success");
                  // SET BUTTON LABEL TO POWER OFF
                  $('.outlet-'+outlet+'-button-state').html("OFF");
                }else{
                  // OUTLET IS OFF
                  // UPDATE PANEL CLASS TO DANGER
                  $("#outlet-"+outlet+"-panel").removeClass("panel-success").addClass("panel-danger");
                  // SET BUTTON LABEL TO POWER OFF
                  $('.outlet-'+outlet+'-button-state').html("ON");
                }
                //$('.outlet-'+outlet+'-name').html(v);
                outlet++;
              })
            }
          });
        }


      })
    </script>
  </body>
</html>