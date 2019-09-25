/* CALENDAR */
 var currentMousePos = {
        x: -1,
        y: -1
     };
function  init_calendar() {
//path of the calendar controller, when called it´s neaded to specifie the methode
var appPath = window.location.href+'/';
//console.log(appPath);
//app_url = app_url.substr(0, app_url.lastIndexOf("/"));
//window.location.replace(app_url+'/App');
//prepare date variables
var date = new Date(),
d = date.getDate(),
m = date.getMonth(),
y = date.getFullYear(),
started,
categoryClass;
 
var calendar = $('#calendar').fullCalendar({
		lang:         "eng",//language
	businessHours: {//prepares the availabilaty for the schedule
    // days of week. an array of zero-based day of week integers (0=Sunday)
    dow: [ 1, 2, 3, 4 , 5 ,6], // Monday - friday

    start: '7:00', // a start time 
    end: '16:00', // an end time 
    allDayEvent:false,//all day

  },
//themeSystem: themeSystem,
header: {//prepares the header of the schedule
	left: 'myEventColor,prev,next,today',
	center: 'title',
	right: 'month,agendaWeek,agendaDay,listMonth'
  //ignoreTimezone:false
  },
defaultView: 'month',
    validRange: {//block events from a date prev to now
    	start: date
    },
    //forceEventDuration:true,
    selectable: true,
    droppable: true,
    selectHelper: true,
    editable:true,
    eventLimit:true,
    eventRender: function(event, element, view)
    {
      element.find('.fc-title').text('Title: ' + event.title);
      element.find('.fc-title').append("<br>Description: " + event.descr);
      element.find('.fc-title').parent().css('background',event.selColor);
      //element.find('.fc-title').parent().css('<br>Time:',event.selTime);
    },
     
    //to delete on drop
    eventDragStop: function(event,jsEvent,ui, view) {
      console.log(event);
    //variable to store the base coordinates of the div responsible to delete
    //console.log(isElementOverDiv());
    if(isElementOverDiv()){   
  var con = confirm('Are you sure to delete this event permanently?');
  if(con == true) {
    $.ajax({
      url: appPath+"eventDelete",
      data: 'type=remove&eventid='+event.id,
      type: 'POST',
      dataType: 'json',
      success: function(response){
        if(response.status == 'DELETED'){
          $('#calendar').load(window.location.href+'/manageCalendar',function(){
          $('.cal-eventPage').html('<div class="alert alert-sucess text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Event deleted successfully!</div></div>');
          $('#calendar').fullCalendar('removeEvents');
          });
        }
      },
      error: function(e){
        alert('Error processing your request: '+e.responseText);
      }
    });
  }
}

 /*cursor current position/coordinates*/    
    $(document).on("mousemove", function (event) {
      event.preventDefault();
      currentMousePos.x = event.pageX;
      currentMousePos.y = event.pageY;
    });
    /*cursor current position/coordinates end*/    

function isElementOverDiv() {
   var trashEl = $('#trashArea');
   //console.log(trashEl);
   var ofs = trashEl.offset();
   var x1 = ofs.left;
   var x2 = ofs.left + trashEl.outerWidth(true);
   var y1 = ofs.top;
   var y2 = ofs.top + trashEl.outerHeight(true);
   if (currentMousePos.x >= x1 &&
       currentMousePos.x <= x2 &&
       currentMousePos.y >= y1 &&
       currentMousePos.y <= y2) {     
      return true;    
        } 
        return false;
    }
 
},
    //updates the event on the db  in case of resize
    eventResize: function(event, delta, revertFunc) {
      console.log(event);
      //ajax call to update event in DB
      var start = $.fullCalendar.formatDate(event.start,"Y-MM-dd HH:mm:ss");
      var end = $.fullCalendar.formatDate(event.end,"Y-MM-dd HH:mm:ss");
      ajaxUpdate(event,calendar,appPath);
      
      },
    //updates the event on the db in case of event drag and drop
    eventDrop: function(event, delta, allDay, revertFunc) {
      //ajax call to update event in DB
      var start = $.fullCalendar.formatDate(event.start,"Y-MM-dd HH:mm:ss");
      var end = $.fullCalendar.formatDate(event.end,"Y-MM-dd HH:mm:ss");
      ajaxUpdate(event,calendar,appPath);
      
      },

      select: function(start, end, allDay) {
       $('#fc_create').click();

       started = start;
       ended = end;
       $(".antosubmit").off().on("click", function(e) {
        e.preventDefault();
        $('#calEventID').val();
        var title = $("#title").val();
        var descr = $("#descr").val();
        var colorID = $("#selColor").val();
              
        if (end) {
         ended = end;
       }
            //gets the view to see if its a allday event
            var view = calendar.fullCalendar('getView')
            categoryClass = $("#event_type").val();

            //checks view type to enable or not the alldayevent
            if (view.type == 'month') {
              allDayEvent = 1;
            }
            else{
              allDayEvent = 0;
            }
            if (title) {
			 /**
         * ajax call to store event in DB
         */ 
         $.ajax({
         	type: "POST",
         	url: appPath+"createEvent",
         	data: {
            
            selColor: colorID,
         		title: title,
         		descr: descr,
         		start: start.format(),
         		end: end.format(),
         		allDay: allDayEvent
         	},
         	success: function(response){
         		if(response){
              calendar.fullCalendar( 'refetchEvents' );
              $('.cal-eventAdd').html('<div class="alert alert-sucess text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Event added successfully!</div></div>');
            }else{
              alert('ERROR: Event not saved');
            }
          }
        });
       }

       $('#title').val('');
       $('#descr').val('');
       $('#selColor').val('');
       calendar.fullCalendar('unselect');
       $('.antoclose').click();

       return false;
     });
    },

     eventMouseover: function(calEvent, jsEvent) {
       var tooltip = '<div class="tooltipevent container-fluid" style="background:#346EA5;position:absolute;z-index:10001;color:white">';
       tooltip +='<div style="width:100%;height:100%;"><h5>Description:</h5><p>' + calEvent.descr + '</p></div></div>';
    	//get the view to only show the tooltip on the month view
      var view = calendar.fullCalendar( 'getView' );

      if (view.type=='listMonth') {
        var $tooltip = $(tooltip).appendTo('body');
        $(this).mouseover(function(e) {
          $(this).css('z-index', 10000);
          $tooltip.fadeIn('300');
          $tooltip.fadeTo('10', 1.9);
        }).mousemove(function(e) {
          $tooltip.css('top', e.pageY + 10);
          $tooltip.css('left', e.pageX + 20);
        });
      }
    },

    eventMouseout: function(calEvent, jsEvent) {
    	$(this).css('z-index', 8);
    	$('.tooltipevent').remove();
    },

    eventClick: function(calEvent, jsEvent, view) {
    	$('#fc_edit').click();
        //var start = calEvent.start;
        //var end = calEvent.end;
        $('#title2').val(calEvent.title);
        $('#descr2').val(calEvent.descr);
        $('#selColorEdt').val(calEvent.colorID).trigger('change');

        categoryClass = $("#event_type").val();
        $(".antosubmit2").off().on("click", function() {
          calEvent.title = $("#title2").val();
          calEvent.descr = $("#descr2").val();
          calEvent.selColorEdt = $("#selColorEdt").val();
          ajaxUpdate(calEvent,calendar,appPath);//ajax call to update event in DB
          $('.antoclose2').click();
          $('body').removeClass('modal-open');
          $('.modal-backdrop').remove();
          return false;
       });
        
        calendar.fullCalendar('unselect');

      },

      editable: true,
      eventSources: [
      {
            url: appPath+"getEvents", // use the `url` property
            timeFormat: 'H(:mm)',
            color: '',   // an option!
            textColor: '' // an option!
          }
          ],
        });
calendar.fullCalendar('option', 'aspectRatio', 2);
}// end #calendar
//converts the array of the date to a date string
function toString(value){
  var blkstr = $.map(value, function(val,index) {
    if(index==0){
     var str = val+'-';
     return str;
   }
   if(index==1){
    val= val+1;
    var str = val+'-';
    return str;
  }
  if(index==2){
   var str = val+' ';
   return str;
 }
 if(index==4){
   var str = val;
   return str;
 }
 if(index<5){
   var str = val+':';
   return str;
 }
}).join("");
  return blkstr;
}

function ajaxUpdate(calEvent,calendar,basepath){
        //console.log(calEvent);
       //corrects the data to be stored in db if came in array format
       if(jQuery.type(calEvent.start['_i']) === "array"){

         calEvent.start['_i']=toString(calEvent.start['_i']);
         calEvent.end['_i']=toString(calEvent.end['_i']);
       }
        /**
         * ajax call to store event in DB
         * alendar.fullCalendar( 'updateEvent', calEvent );
         **/
         $.ajax({
          type: "POST",
          url: basepath+"event_update",
          data: {

            id:calEvent.id,
            title: calEvent.title,
            start: calEvent.start['_i'],
            end: calEvent.end['_i'],
            descr:calEvent.descr,
            allDay: calEvent.allDay,
            selColor: calEvent.colorID,
          },

          success: function(response){
            if(response){
              $('#calendarSection').load(window.location.href+'/manageCalendar',function(){
                $('.cal-eventUpdt').html('<div class="alert alert-sucess text-center" alert-dismissable>\
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Event updated successfully!</div></div>');
                calendar.fullCalendar( 'updateEvent', calEvent );
              });
                          
           }else{
            alert('Error: No changes made');
            return false;
          }
        }
      });
    }
       $(document).ready(function() {
        init_calendar();
  });