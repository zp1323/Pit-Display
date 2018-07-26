function notificationChecker(){
    console.log("Checking for notifications...");
    $.getJSON("/controller/NotificationsController.php", function(data){
        if(data.active == "false"){
            // Keep the modal body empty
            $("#notification-modal-body").empty();
            // Keep the modal hidden
            $('#notification-modal').modal('hide')
        }else{
            // Log the data to console for the debugs...
           //console.log(data);
           // Update Modal Body with current alerts...
            // Empty the modal body container
            $("#notification-modal-body").empty();
            // For each alert, append it to the body.  We trust the order is correct from the controller JSON request.
            $.each( data, function( k, v ) {
              $("#notification-modal-body").append("<div class='alert alert-warning' data-alertid='"+k+"' data-notificationid='"+v.id+"'><span class='alert-username'>"+v.user_name+":</span> <span class='alert-text'>"+v.text+"</span> <span class='badge pull-right'>"+k+"</div></div>")
            });


           // Ensure the modal is being shown (if already shown, the alerts will be updated)
           //console.log("Launching modal...");
           $('#notification-modal').modal('show')
           
        }
    });
}


function clearNotification(tag){
    // Select element
    var id = $("[data-alertid='"+tag+"']").data("notificationid")
    console.log("Removing alert id: " +tag+ " // notification id: " + id)
    $.ajax({
      type: "POST",
      url: "/controller/NotificationsController.php",
      data: {"do":"ack", "id":id},
      success: function(msg){
        console.log(msg)
      },
      error: function(e){
        console.log(e)
      },
      dataType: "JSON"
    });
}

function clearAllNotifications(tag){
    // Select element
    console.log("Acknowledging all notifications!")
    $.ajax({
      type: "POST",
      url: "/controller/NotificationsController.php",
      data: {"do":"ack-all"},
      success: function(msg){
        console.log(msg)
      },
      error: function(e){
        console.log(e)
      },
      dataType: "JSON"
    });
}

function pitPage(tag){
    // Select element
    console.log("Paging for help via Slack!")
    $.ajax({
      type: "POST",
      url: "/controller/NotificationsController.php",
      data: {"do":"pit-page", "id": tag},
      success: function(msg){
        console.log(msg)
      },
      error: function(e){
        console.log(e)
      },
      dataType: "JSON"
    });
}



var $rankingWrapper = $(".rankingswrapper");
var $list = $rankingWrapper.find("ul.list");
var $clonedList = $list.clone();
var listWidth = 10;

$list.find("li").each(function (i) {
			listWidth += $(this, i).outerWidth(true);
});

var endPos = $rankingWrapper.width() - listWidth;

$list.add($clonedList).css({
	"width" : listWidth + "px"
});

$clonedList.addClass("cloned").appendTo($rankingWrapper);

//TimelineMax
var infinite = new TimelineMax({repeat: -1, paused: true});
var time = 110;

infinite
  .fromTo($list, time, {rotation:0.01,x:0}, {force3D:true, x: -listWidth, ease: Linear.easeNone}, 0)
  .fromTo($clonedList, time, {rotation:0.01, x:listWidth}, {force3D:true, x:0, ease: Linear.easeNone}, 0)
  .set($list, {force3D:true, rotation:0.01, x: listWidth})
  .to($clonedList, time, {force3D:true, rotation:0.01, x: -listWidth, ease: Linear.easeNone}, time)
  .to($list, time, {force3D:true, rotation:0.01, x: 0, ease: Linear.easeNone}, time)
  .progress(1).progress(0)
  .play();

//Pause/Play		
$rankingWrapper.on("mouseenter", function(){
	infinite.pause();
}).on("mouseleave", function(){
	infinite.play();
});