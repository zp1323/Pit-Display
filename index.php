<?php
date_default_timezone_set('America/Los_Angeles');

$team_id    = $_GET['team'];
$event_id   = $_GET['event'];
$comp_level  = array("qm" => "Q", "ef" => "EF", "qf" => "QF", "sf" => "SF", "f" => "F"); // For translating TBA API values

function time_lapse ($time, $until = null)
{
  if($until == null){
    $end_time   = time();
  }else{
    $end_time   = $until;
  }

    $time = $end_time - $time; // to get the time since that moment

    if( $time < 0 )
    {
      $time   = abs ( $time );
      $prefix =   "About";
      $suffix =   "";
    }
    else
    {
      $prefix = "";
      $suffix =   "AGO";
    }

    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'min',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $prefix.' '.$numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'') . ' ' . $suffix; //add s for plural
    }

}

function searchRankings($id, $array) {
   foreach ($array as $key => $val) {
       if ($val['team_key'] == $id) {
           return $key + 1;
       }
   }
   return "??";
}


try
{
    function getDirContents($dir, &$results = array()){
        $files = scandir($dir);

        foreach($files as $key => $value){
            $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
            if(!is_dir($path)) {
                $fileinfo = new SplFileInfo($path);
                if($fileinfo->getExtension() == "php")
                {
                    $results[] = $path;
                    include $path;
                }
            } else if($value != "." && $value != "..") {
                getDirContents($path, $results);
            }
        }

        return $results;
    }

    getDirContents("app");
    $ba = new \Providers\BAServiceProvider;
    $notification = new \Providers\NotificationProvider;
    
    $status              =   $ba->getTeamStatus($team_id, $event_id);
    $schedule            =   $ba->getTeamSchedule($team_id, $event_id);
    $rankings            =   $ba->getRankings($event_id);
    $event_matches       =   $ba->getEventSchedule($event_id);
    $event_last_match    =   $ba->getEventLastMatch($event_id);
    $event_insights      =   $ba->getEventInsights($event_id);
    $timeseries           =   $ba->getEventTimeseries($event_id, false);
    //$predictions =   $ba->getPredictions($event_id);

    // Calculate Next Match
    if( $status['next_match_key'] == "")
    {
      $next_match['number']   = "N/A";
      $next_match['time']     = "EVENT COMPLETE";
    }
    else
    {
      // Get Next Match Data
      $next_match_data        = $ba->getMatch($status['next_match_key']);
      if( $next_match_data['comp_level'] !== "qm" )
      {
        $set_number   = $next_match_data['set_number'] . "-";
      }
      else
      {
        $set_number   = "";
      }
      
       // Determine alliance color
          if ( in_array($team_id, $next_match_data['alliances']['blue']['team_keys']) ):
            $alliance   = "blue";
          else:
            $alliance   = "red";
          endif;
          
          
      $next_match['number']   = $comp_level[$next_match_data['comp_level']] . ' ' . $set_number . $next_match_data['match_number'] ;
      $next_match['time']     = time_lapse($next_match_data['predicted_time']);
      $next_match['alliance'] = $alliance;
    }

    // Sort Schedule Array
    usort($schedule, function($a, $b) {
        return $a['match_number'] - $b['match_number'];
    });

    // Event Last Match
    $event_last_match['number']   = $comp_level[$event_last_match['comp_level']] . ' ' .$event_last_match['set_number'] .'-'. $event_last_match['match_number'] ;
    $event_last_match['time']     = ($event_last_match['actual_time'] == 0 ? "NOT STARTED" : time_lapse($event_last_match['actual_time']));


$matchCount = count($schedule);

    /*
  echo "<h2>SCHEDULE</h2>";
    print("<pre>");
    print_r( $schedule );
    print("</pre>"); 
    print("<pre>");
    print_r( $status );
    print("</pre>");
    echo "<h2>SCHEDULE</h2>";
    print("<pre>");
    print_r( $schedule );
    print("</pre>"); 
    echo "<h2>RANKINGS</h2>";
    print("<pre>");
    print_r( $rankings );
    print("</pre>"); 
    /*
    echo "<h2>PREDICTIONS</h2>";
    print("<pre>");
    print_r( $predictions );
    print("</pre>"); 

    
    echo "<h2>INSIGHTS</h2>";
    print("<pre>");
    print_r( $event_insights );
    print("</pre>"); 
 
    echo $timeseries;
    
     echo "<h2>LAST MATCH</h2>";
    print("<pre>");
    print_r( $event_last_match );
    print("</pre>"); 
    
    */

	
      

    
}
catch (Exception $e)
{
    echo $e;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Birdie Pit Display</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
    body{
      background: #000;
    }
      .match-row{
        background: #ccc;
        font-size: 24px;
        text-align: center;
        vertical-align: middle;
        color: #3f51b5;
      }

      .header-row{
        background: #000;
        font-size: 48px;
        text-align: center;
        vertical-align: middle;
        color: #fff;
        padding:15px;
      }

      .red-bg{
        background: #fee;
        padding:10px;
      }

      .blue-bg{
        background: #eef;
        padding:10px;
      }

      .red-score{
        background: #fdd;
        padding:10px;
      }

      .blue-score{
        background: #ddf;
        padding:10px;
      }
      
      .red{
      	color: #ff0000;
      }
      
      .blue{
      	color: #ddf;
      }

      .rank, .sub{
        font-size: 14px;
        display: block;
        text-transform: uppercase;
      }

      .match-info{
        line-height: 54px;
      }

      .win{
        font-weight: bold;
        /* text-decoration: underline; */
      }

      .predicted-red{
        padding: 10px;
        color: #ffeeee;
      }

      .predicted-blue{
        padding: 10px;
        color: #eeeeff;
      }

      .line-break{
        line-height: 1px;
        display: block;
        background: #dedede;
      }

      .underline-this{
        text-decoration: underline;
      }
      
      #schedule{
      	height: 750px;
      	overflow: hidden;
      }
      
      .alert-username{
      	font-weight:bold;
      	font-size48px;
      }
      
      .alert-text{
      	font-size:48px;
      	font-weight:normal;
      }
      
      .rankingswrapper {
	  /* the outer div */
	  
	  position: fixed;
	  bottom: 00px;
	  left:0%;
	  border: 1px solid #444;
	  background: #fff;
	  width: 99.9%;
	  height: 80px;
	  overflow: hidden;
	  cursor: pointer;
	  font-size:18px;
	}
	
	ul.list {
	  position: relative;
	  display: inline-block;
	  list-style: none;
	  padding:0;
	  margin:0;
	}
	
	ul.list.cloned {
	  position: absolute;
	  top: 0px;
	  left: 0px;
	}
	
	ul.list li {
	  float: left;
	  padding-left: 20px;
	}
	
    </style>
  </head>
  <body>
    <div class="container-fluid">
      <div class="row header-row">
        <div class="col-md-4"><span class="sub">LAST FIELD MATCH</span><?=$event_last_match['number']?><span class="sub"><?=$event_last_match['time']?></span></div>
        <div class="col-md-4"><span class="sub">CURRENT RANK</span><?=$status['qual']['ranking']['rank']?><span class="sub"><?=$status['qual']['ranking']['record']['wins'] . '-' . $status['qual']['ranking']['record']['losses'] . '-' .$status['qual']['ranking']['record']['ties']?></span></div>
        <div class="col-md-4 <?=$next_match['alliance']?>"><span class="sub">NEXT TEAM MATCH</span><?=$next_match['number']?><span class="sub"><?=$next_match['time']?></span></div>
      </div>

	<div id="schedule">
      <?
      $ba->sortMatches($schedule, true);
      foreach ($schedule as $match)
      {
        echo '<div class="row match-row">';
          // Determine alliance color
          if ( in_array($team_id, $match['alliances']['blue']['team_keys']) ):
            $alliance   = "blue";
          else:
            $alliance   = "red";
          endif;

          if ( $match['actual_time'] == "" )
          {
            // Match not completed
            $match_completed  = false;
            $match_time       = $match['predicted_time'];
            $score_type       = "predicted";

            $red_score        = "";
            $blue_score       = "";
            $red_rp           = "";
            $blue_rp          = "";

          }
          else
          {
            // Match Completed
            $match_completed  = true;
            $match_time       = $match['actual_time'];
            $red_score        = $match['alliances']['red']['score'];
            $blue_score       = $match['alliances']['blue']['score'];
            $red_rp           = $match['score_breakdown']['red']['rp'];
            $blue_rp          = $match['score_breakdown']['blue']['rp'];

            if($red_score > $blue_score)
            {
              // RED WINS!
              $red_match_status   = "win";
              $blue_match_status  = "loss";
            }
            else if( $red_score == $blue_score)
            {
              // TIE MATCH!
              $red_match_status   = "win";
              $blue_match_status  = "win";
            }
            else
            {
              // BLUE WINS!
              $red_match_status   = "loss";
              $blue_match_status  = "win";
            }
          }

          if( $match['comp_level'] !== "qm" )
          {
            $set_number   = $match['set_number'] . "-";
          }
          else
          {
            $set_number   = "";
          }
          
          $match['number']   = $comp_level[$match['comp_level']] . ' ' . $set_number . $match['match_number'] ;

          echo '<div class="col-md-1 '.$alliance.'-bg match-info">'.$match['number'].'</div>';
          echo '<div class="col-md-1 '.$alliance.'-bg">'.date("g:i a", $match_time).'<span class="rank">'.($match_completed ? 'ACTUAL' : 'PREDICTED').'</span></div>';
          echo '<div class="col-md-1 red-bg">'.($match['alliances']['red']['team_keys'][0] == $team_id ? '<u>'.str_replace("frc", "", $match['alliances']['red']['team_keys'][0]).'</u>' : str_replace("frc", "", $match['alliances']['red']['team_keys'][0])).'<span class="rank">RANK '.searchRankings($match['alliances']['red']['team_keys'][0], $rankings['rankings']).'</span></div>';
          echo '<div class="col-md-1 red-bg">'.($match['alliances']['red']['team_keys'][1] == $team_id ? '<u>'.str_replace("frc", "", $match['alliances']['red']['team_keys'][1]).'</u>' : str_replace("frc", "", $match['alliances']['red']['team_keys'][1])).'<span class="rank">RANK '.searchRankings($match['alliances']['red']['team_keys'][1], $rankings['rankings']).'</span></div>';
          echo '<div class="col-md-1 red-bg">'.($match['alliances']['red']['team_keys'][2] == $team_id ? '<u>'.str_replace("frc", "", $match['alliances']['red']['team_keys'][2]).'</u>' : str_replace("frc", "", $match['alliances']['red']['team_keys'][2])).'<span class="rank">RANK '.searchRankings($match['alliances']['red']['team_keys'][2], $rankings['rankings']).'</span></div>';
          echo '<div class="col-md-1 blue-bg">'.($match['alliances']['blue']['team_keys'][0] == $team_id ? '<u>'.str_replace("frc", "", $match['alliances']['blue']['team_keys'][0]).'</u>' : str_replace("frc", "", $match['alliances']['blue']['team_keys'][0])).'<span class="rank">RANK '.searchRankings($match['alliances']['blue']['team_keys'][0], $rankings['rankings']).'</span></div>';
          echo '<div class="col-md-1 blue-bg">'.($match['alliances']['blue']['team_keys'][1] == $team_id ? '<u>'.str_replace("frc", "", $match['alliances']['blue']['team_keys'][1]).'</u>' : str_replace("frc", "", $match['alliances']['blue']['team_keys'][1])).'<span class="rank">RANK '.searchRankings($match['alliances']['blue']['team_keys'][1], $rankings['rankings']).'</span></div>';
          echo '<div class="col-md-1 blue-bg">'.($match['alliances']['blue']['team_keys'][2] == $team_id ? '<u>'.str_replace("frc", "", $match['alliances']['blue']['team_keys'][2]).'</u>' : str_replace("frc", "", $match['alliances']['blue']['team_keys'][2])).'<span class="rank">RANK '.searchRankings($match['alliances']['blue']['team_keys'][2], $rankings['rankings']).'</span></div>';
          if( $match_completed )
          {
            echo '<div class="col-md-2 red-score '.$red_match_status.' '.($alliance == "red" ? 'underline-this' : '').'">'.$red_score.' <span class="rank">'.$red_rp.' RP</span></div>';
            echo '<div class="col-md-2 blue-score '.$blue_match_status.' '.($alliance == "blue" ? 'underline-this' : '').' ">'.$blue_score.' <span class="rank">'.$blue_rp.' RP</span></div>';
          }
          else
          {
            echo '<div class="col-md-2 red-predicted">&nbsp; <span class="rank">&nbsp;</span></div>';
            echo '<div class="col-md-2 blue-predicted">&nbsp; <span class="rank">&nbsp;</span></div>';
          }
          

        echo '</div>';
      }
      ?>
      

	</div>
    </div>
    
    <div class="rankingswrapper">
    <ul class='list'>
      <?
      foreach($rankings['rankings'] as $v)
      {
      	echo '<li class="listitem"><span>'.$v['rank'].') '.str_replace("frc", "", $v['team_key']).'<br>('.$v['record']['wins'].'-'.$v['record']['losses'].'-'.$v['record']['ties'].')<BR>'.$v['sort_orders'][0].'</span></li>';
      }
      ?>
      
  </ul>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="notification-modal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><img src="/img/slack_icon.png" width="24px" height="24px" /> SLACK NOTIFICATION!</h4>
          </div>
          <div class="modal-body" id="notification-modal-body">
            <p>One fine body&hellip;</p>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js" type="text/javascript"></script>
    <script src="/js/custom.js"></script>

    <script type="text/javascript">
      $(document).ready(function(){
      	console.log("Match count: <?=$matchCount?>");
      	
      	setTimeout(function(){
      		location.reload()
      	},120000)
      	
        setInterval(function(){
          notificationChecker()
        },1000)

        // Monitor KeyPress
        $(document).on('keypress', function(e) {
            var tag = e.which 
            console.log( tag )
            if(tag > 47 && tag < 58)
            {
              // Number key pressed, clear that specific alert...sorry on the most recent 10 can be cleared individually!
              if(modalVisible)
              {
                // Modal is shown - clear notifications
                clearNotification(tag-48); // 48 is the offset from the numeric key pressed
              }
              else
              {
                // Modal is hidden - send notifications
                pitPage(tag-48);
              }
            }
            else if(tag == 13)
            {
              // Enter key was pressed, clear all notifications!
              clearAllNotifications();
            }

        });
        var modalVisible = false;
        $('#notification-modal').on('shown.bs.modal', function () {
          console.log("Notification modal launched...")
          modalVisible = true;
        })
        $('#notification-modal').on('hidden.bs.modal', function () {
          console.log("Notification modal closed...")
          modalVisible = false;
        })

      	<? if (isset($_GET['scroll']) || $matchCount > 10): ?>
	      	var top=0;
	      	var par = document.getElementById('schedule')
	      	var scroll = function() {
	      	  top++;
	      	  if( top>=par.firstElementChild.offsetHeight )
	      	  {
	      	    //first element is out of sight, so move to the end of the list
	      	    top=0;
	      	    par.firstElementChild.style.marginTop='';//reset to -
	      	    par.appendChild(par.firstElementChild);
	      	  }
	      	  else
	      	  {
	      	     par.firstElementChild.style.marginTop='-'+top+'px';
	      	  }
	      	  setTimeout(scroll, 40)
	      	}
	      	scroll();
	<? endif; ?>
      })
    </script>
  </body>
</html>