/*function init_timetable(){
	//path of the timetable controller is neede to specify the method
	var appPath = "http://localhost/dev/thutoschool/Intercom/";
	//prepare weekdays variables
	var day = new Date(),
	var d = day.getDay(),
	//var subject = ,
	//var teacher = ,
	started,
	categoryClass;

	var time_table = $('#timetableMainView').fullCalendar({
		lang:         "eng",//language
	schoolHours: {//prepares the availabilaty for the schedule
    // days of week. an array of zero-based day of week integers (0=Sunday)
    dow: [ 1, 2, 3, 4 ,5,6], // Monday - friday
    start: '00:00', //start time
    end: '16:00', //end time

    header:{//preapres the header
    	center:'title',
    },
    defaultView:'day',
    validRange:{//blocks class range
    	start:date
    },

    selectable:true,
    droppable:true,
    editable:true,
    eventRender:function(event,element){
    	element.find('td.box_content').text('Subject: '+event.name);
    	//element.find('');

    },
    //update the class timetable subject incase of drag and drop
    eventDrop:function(event,delta,revertFunc){
    	//ajax call to update the class timetable subject
    	ajaxUpdate(event,time_table,appPath);
    },

    select:function(start,end){
    	$('td.box_content').click();
    	started = start;
    	ended = end;
    	$('.addingClassTime').off().on('click',function(){
    		var subject = $('#subject').val();
    		if(end){
    			ended = end;
    		}
    		var view = time_table.fullCalendar('getView')
    		categoryClass = $('#event_type').val();
    		//checks view type to enable or not
    	}	
    	$.ajax({
    		type:'POST',
    		url:appPath+'addClassSubject',
    		data:{
    			subject:subject,
    			end:ended.format(),
    		},
    		success:function(response){
    			if(response){
    				time_table.fullCalendar('refetchEvents')
    			}else{
    			alert('ERROR:Event not saved')
    		}
    	}
    });
  }
  $('#subject').val('');
  time_table.fullCalendar('unselect');
  $('.antoclose').click();

  return false;
});
},

 eventMouseover: function(calEvent, jsEvent) {
       var tooltip = '<div class="tooltipevent container-fluid" style="background:#346EA5;position:absolute;z-index:10001;color:white">';
       tooltip +='<div style="width:100%;height:100%;"><h5>Description:</h5><p>' + calEvent.name + '</p></div></div>';
    	//get the view to only show the tooltip on the month view
      var view = calendar.fullCalendar( 'getView' );
      console.log(view.type);
      if (view.type=='listDay') {
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
    },*/

