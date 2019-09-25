/**
 * Created by Kupletsky Sergey on 17.10.14.
 *
 * Material Sidebar (Profile menu)
 * Tested on Win8.1 with browsers: Chrome 37, Firefox 32, Opera 25, IE 11, Safari 5.1.7
 * You can use this sidebar in Bootstrap (v3) projects. HTML-markup like Navbar bootstrap component will make your work easier.
 * Dropdown menu and sidebar toggle button works with JQuery and Bootstrap.min.js
 */


// Sidebar toggle
//
// -------------------
$(document).ready(function() {
    //$('#msgMainView').load("intercom/manageMsg");



    var overlay = $('.sidebar-overlay');

    $('.sidebar-toggle').on('click', function() {
        var sidebar = $('#sidebar');
        sidebar.toggleClass('open');
        if ((sidebar.hasClass('sidebar-fixed-left') || sidebar.hasClass('sidebar-fixed-right')) && sidebar.hasClass('open')) {
            overlay.addClass('active');
        } else {
            overlay.removeClass('active');
        }
    });

    overlay.on('click', function() {
        $(this).removeClass('active');
        $('#sidebar').removeClass('open');
    });

});

// Sidebar constructor
//
// -------------------
$(document).ready(function() {

    var sidebar = $('#sidebar');
    var sidebarHeader = $('#sidebar .sidebar-header');
    var sidebarImg = sidebarHeader.css('background-image');
    var toggleButtons = $('.sidebar-toggle');

    // Hide toggle buttons on default position
    toggleButtons.css('display', 'none');
    $('body').css('display', 'table');


    // Sidebar position
    $('#sidebar-position').change(function() {
        var value = $( this ).val();
        sidebar.removeClass('sidebar-fixed-left sidebar-fixed-right sidebar-stacked').addClass(value).addClass('open');
        if (value == 'sidebar-fixed-left' || value == 'sidebar-fixed-right') {
            $('.sidebar-overlay').addClass('active');
        }
        // Show toggle buttons
        if (value != '') {
            toggleButtons.css('display', 'initial');
            $('body').css('display', 'initial');
        } else {
            // Hide toggle buttons
            toggleButtons.css('display', 'none');
            $('body').css('display', 'table');
        }
    });

    // Sidebar theme
    $('#sidebar-theme').change(function() {
        var value = $( this ).val();
        sidebar.removeClass('sidebar-default sidebar-inverse sidebar-colored sidebar-colored-inverse').addClass(value)
    });

    // Header cover
    $('#sidebar-header').change(function() {
        var value = $(this).val();

        $('.sidebar-header').removeClass('header-cover').addClass(value);

        if (value == 'header-cover') {
            sidebarHeader.css('background-image', sidebarImg)
        } else {
            sidebarHeader.css('background-image', '')
        }
    });
});

/**
 *
 * Add JQuery animation to bootstrap dropdown elements.
 */

(function($) {
    var dropdown = $('.dropdown');

    // Add slidedown animation to dropdown
    dropdown.on('show.bs.dropdown', function(e){
        $(this).find('.dropdown-menu').first().stop(true, true).slideDown();
    });

    // Add slideup animation to dropdown
    dropdown.on('hide.bs.dropdown', function(e){
        $(this).find('.dropdown-menu').first().stop(true, true).slideUp();
    });
})(jQuery);



(function(removeClass) {

    jQuery.fn.removeClass = function( value ) {
        if ( value && typeof value.test === "function" ) {
            for ( var i = 0, l = this.length; i < l; i++ ) {
                var elem = this[i];
                if ( elem.nodeType === 1 && elem.className ) {
                    var classNames = elem.className.split( /\s+/ );

                    for ( var n = classNames.length; n--; ) {
                        if ( value.test(classNames[n]) ) {
                            classNames.splice(n, 1);
                        }
                    }
                    elem.className = jQuery.trim( classNames.join(" ") );
                }
            }
        } else {
            removeClass.call(this, value);
        }
        return this;
    }
   

})(jQuery.fn.removeClass);

 /*****new script for Guardian modal messages*****/
  
$(document).ready(function(){

$(document).on('click','.gcontact',function(){
$('#gname').val($(this).data('lname'));
$('#gemail').val($(this).data('email'));
$('#tName').val($(this).data('lname'));
$('#tEmail').val($(this).data('email'));
$('#userGuardID').val($(this).data('guardid'));
$('#userTeachID').val($(this).data('teachid'));

});  
 /*****End of script for Guardian modal messages*****/
$(document).on('click','.learncontact',function(){
$('#userlearnerID').val($(this).data('learnid'));
}); 
  /*Teacher and Parent List*/
    $(function() {    
        $('#input-search').on('keyup', function() {
          var rex = new RegExp($(this).val(), 'i');
            $('.searchable-container .items').hide();
            $('.searchable-container .items').filter(function() {
                return rex.test($(this).text());
            }).show();
        });
    });
/*Teacher List*/

  


 /*dropdown js
$(function() {
   var selectOptions;
   if(localStorage.getItem("selectOptions")) {
       selectOptions = JSON.parse(localStorage.getItem("selectOptions"));
       Object.keys(selectOptions).forEach(function(select) {
         $("select[name="+select+"]").val(selectOptions[select]);
       });
  } else {
     selectOptions = {};
  }
  $("select").change(function() {
       var $this =  $(this),
           selectName = $this.attr("name");
      selectOptions[selectName] = $this.val();
      localStorage.setItem("selectOptions", JSON.stringify(selectOptions));
    });

});*/

///*Subjects table js*/

$(document).ready(function() {
    $('#example').DataTable();
} );

///*end of subjects table js*/

///*Add subject modal Script js*/
 //Add a subject on the system when the modal is clicked
$(document).on('click','.addSubject',function(e){
    e.preventDefault();
    var addSub = $('#newSubject').val();
    if (addSub != '') {
        $.ajax({
            url: 'addSubject',
            type: 'POST',
            data: {'subjectName': addSub},
            //dataType:'json',
        })
        .done(function(msg) {
            var str = msg.substr(0,3);
           if(str == 'yes')
                {
                    $('#myModal1').hide('800', function() {
                      $('body').removeClass('modal-open'); 
                       //need to remove div with modal-backdrop class
                       $('.modal-backdrop').remove(); 
                        
                    });
                    /*window.location.reload(true); */
                    $('.ale-sub').html('<div class="alert alert-success text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    School Subject Added Successfully!</div>');
                }else if(str == 'no'){
                     $('.ale-subAdd').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Error Adding School Subject!</div>');
            }
            else
                $('.ale-subAdd').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+msg+'</div>');                            
        })
        .fail(function() {
            alert("error");
        })

    }
});
///*Add subject modal Script js*/
///
///
/// //EDIT a subject on the system when the modal is clicked
/// 
$(document).on('click','.subjectEdit',function(e){
    e.preventDefault();
    var editSub = $(this).data('subname');
    var editSubid = $(this).data('subid');
    $('#editSubject').val(editSub);
    $('#subid').val(editSubid);

    });

$(document).on('click','.updateSubject',function(e){
    e.preventDefault();
    var editSub =$('#editSubject').val();
    var editSubid =$('#subid').val();
    if (editSub != '') {
        $.ajax({
            url: 'editSubject',
            type: 'POST',
            data: {'subjectName': editSub,
                   'subjectId': editSubid},
            //dataType:'json',
        })
        .done(function(msg) {
            var str = msg.substr(0,3);
                if(str == 'yes')
                {
                    $('#myModal').hide('800', function() {
                      $('body').removeClass('modal-open'); 
                       //need to remove div with modal-backdrop class
                       $('.modal-backdrop').remove(); 
                        
                    });
                    /*window.location.reload(true); */
                    $('.ale-sub').html('<div class="alert alert-success text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Subject Updated Successfully!</div>');
                }else if(str == 'no'){
                     $('.ale-Editerror').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Error Updating Subject!</div>');
            }
            else
                $('.ale-Editerror').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+msg+'</div>');

        })
       

    }
});
//askDelete Subject
/// 
$(document).on('click','.askDeleteSubject',function(e){
    e.preventDefault();
    var deleteSubject = $(this).data('subid'); 
    var subjectNam = $(this).data('subname'); 
    $('.askDeleteSubject-yes').val(deleteSubject);
     $('.modal-title').text('Delete Subject');
     $('.modal-msg').text('Are you sure you want to delete ' + subjectNam.toUpperCase() + ' ?');
     $('#askDeleteSubject').show();

    });
$(document).on('click','.askDeleteSubject-no',function(e){
    e.preventDefault();
     $('#askDeleteSubject').hide('800', function() {
       $('body').removeClass('modal-open'); 
        //need to remove div with modal-backdrop class
        $('.modal-backdrop').remove(); 
         
     });

    });

//Delete
$(document).on('click','.askDeleteSubject-yes',function(e){
    e.preventDefault();
    var deleteSubject =$(this).val();
    if (deleteSubject != '') {

        $.ajax({
            url: '../Admin/users',
            type: 'POST',
            data: {'subid': deleteSubject}
            //dataType:'json',
        })
        .done(function(msg) {
            var str = msg.substr(0,3);
            if(str== 'yes')
                //if(msg == 'yes')
                {
                   $('#askDeleteSubject').hide('800', function() {
                     $('body').removeClass('modal-open'); 
                 //need to remove div with modal-backdrop class
                 $('.modal-backdrop').remove(); 
                    
                  });
                    $('.modal-title').text('Delete Success');
                    $('.modal-msg').text('You have successfully deleted the Subject');
                     $('#askDeleteSubject-success').show();
                }                       
        })
        .fail(function() {
            alert("error");
        })

    }
});

$(document).on('click','.askDeleteSubject-ok',function(e){
    e.preventDefault();
     $('#askDeleteSubject-success').hide('800', function() {
         
       $('body').removeClass('modal-open'); 
        //need to remove div with modal-backdrop class
        $('.modal-backdrop').remove(); 
          window.location.reload();
     });

    });//end of ask delete 
///*Add subject modal Script js*/
 //Add a subject on the system when the modal is clicked
$(document).on('click','.addQuarter',function(e){
    e.preventDefault();
    var addQuarter = $('#newQuarter').val();
    if (addQuarter != '') {
        $.ajax({
            url: 'addQuarter',
            type: 'POST',
            data: {'quarterName': addQuarter},
            //dataType:'json',
        })
        .done(function(msg) {
            if(msg == 'yes')
                {
                    $('#myModal1').hide('800', function() {
                      $('body').removeClass('modal-open'); 
                       //need to remove div with modal-backdrop class
                       $('.modal-backdrop').remove(); 
                        
                    });
                    /*window.location.reload(true); */
                    $('.ale-quarter').html('<div class="alert alert-success text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    School Quarter Added Successfully!</div>');
                }else if(msg == 'no'){
                     $('.ale-quarterAdd').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Error Adding School Quarter!</div>');
            }
            else
                $('.ale-quarterAdd').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+msg+'</div>'); 

        })
        .fail(function() {
            alert("error");
        })

    }
});
///*Add subject modal Script js*/


/// EDIT a Quarter on the system when the modal is clicked
/// 
$(document).on('click','.quarterEdit',function(e){
    e.preventDefault();
    var editQuarter = $(this).data('qname');
    var editQuarterId = $(this).data('qid');
    $('#editQuarter').val(editQuarter);
    $('#Quartid').val(editQuarterId);

    });

$(document).on('click','.updateQuarter',function(e){
    e.preventDefault();
    var editQuarter =$('#editQuarter').val();
    var editQuarterId =$('#Quartid').val();
    if (editQuarter != '') {
        $.ajax({
            url: 'editQuarter',
            type: 'POST',
            data: {'quarterName': editQuarter,
                   'Quartid': editQuarterId},
            //dataType:'json',
        })
        .done(function(msg) {
            var str = msg.substr(0,3);
                if(str == 'yes')
                {
                    $('#myModal').hide('800', function() {
                      $('body').removeClass('modal-open'); 
                       //need to remove div with modal-backdrop class
                       $('.modal-backdrop').remove(); 
                        
                    });
                    /*window.location.reload(true); */
                    $('.ale-quarter').html('<div class="alert alert-success text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    School Quarter Updated Successfully!</div>');
                }else if(str == 'no'){
                     $('.ale-errorQ').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Error Updating School Quarter !</div>');
            }
            else
                $('.ale-errorQ').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+msg+'</div>');                         
        })
    }
});

/// EDIT a Quarter on the system when the modal is clicked
/// 
$(document).on('click','.askDeleteQuarter',function(e){
    e.preventDefault();
    var deleteQuarterId = $(this).data('qid');
    var quarterNam = $(this).data('qname');
    $('.askDelete-yes').val(deleteQuarterId);
     $('.modal-title').text('Delete Quarter');
     $('.modal-msg').text('Are you sure you want to delete ' + quarterNam.toUpperCase() + ' ?')
     $('#askDelete').show();

    });
$(document).on('click','.askDelete-no',function(e){
    e.preventDefault();
     $('#askDelete').hide('800', function() {
       $('body').removeClass('modal-open'); 
        //need to remove div with modal-backdrop class
        $('.modal-backdrop').remove(); 
         
     });

    });
//Delete
$(document).on('click','.askDelete-yes',function(e){
    e.preventDefault();
    var deleteQuarter =$(this).val();
    if (deleteQuarter != '') {
        $.ajax({
            url: 'users',
            type: 'POST',
            data: {'quarterId': deleteQuarter}
            //dataType:'json',
        })
        .done(function(msg) {
           
            var str = msg.substr(0,3);
            if(str== 'yes')
                //if(msg == 'yes')
                {
                   $('#askDelete').hide('800', function() {
                     $('body').removeClass('modal-open'); 
                 //need to remove div with modal-backdrop class
                 $('.modal-backdrop').remove(); 
                    
                  });
                    $('.modal-title').text('Delete Success');
                    $('.modal-msg').text('You have successfully deleted the Quarter');
                     $('#askDelete-success').show();
                }                       
        })
        .fail(function() {
            alert("error");
        })

    }
});

$(document).on('click','.askDelete-ok',function(e){
    e.preventDefault();
     $('#askDelete-success').hide('800', function() {
         
       $('body').removeClass('modal-open'); 
        //need to remove div with modal-backdrop class
        $('.modal-backdrop').remove(); 
          window.location.reload();
     });

    });



//Add a Level on the system when the modal is clicked
$(document).on('click','.addLevel',function(e){
    e.preventDefault();
    var addLevels = $('#newLevel').val();
    if (addLevels != '') {
        $.ajax({
            url: 'addLevel',
            type: 'POST',
            data: {'levelName': addLevels},
            //dataType:'json',
        })
        .done(function(msg) {
            if(msg == 'yes')
                {
                    $('#myModal1').hide('800', function() {
                      $('body').removeClass('modal-open'); 
                       //need to remove div with modal-backdrop class
                       $('.modal-backdrop').remove(); 
                        
                    });
                    /*window.location.reload(true); */
                    $('.ale-level').html('<div class="alert alert-success text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Class Level Added Successfully!</div>');
                }else if(msg == 'no'){
                     $('.ale-levelError').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Error Adding Class Group!</div>');
            }
            else
                $('.ale-levelError').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+msg+'</div>');       

        })
        .fail(function() {
            alert("error");
        })

    }
});
///*End of Add level modal Script js*/

/// EDIT a Level on the system when the modal is clicked
/// 
$(document).on('click','.levelEdit',function(e){
    e.preventDefault();
    var editLevel = $(this).data('lvlname');
    var editLevelId = $(this).data('lid');
    $('#editLevels').val(editLevel);
    $('#levID').val(editLevelId);

    });

$(document).on('click','.updateLevel',function(e){
    e.preventDefault();
    var editLevel =$('#editLevels').val();
    var editLevelId =$('#levID').val();
    if (editLevel != '') {
        $.ajax({
            url: 'editLevel',
            type: 'POST',
            data: {'levelName': editLevel,
                   'levID': editLevelId},
            //dataType:'json',
        })
        .done(function(msg) {
              var str = msg.substr(0,3);
                if(str == 'yes')
                {
                    $('#myModal').hide('800', function() {
                      $('body').removeClass('modal-open'); 
                       //need to remove div with modal-backdrop class
                       $('.modal-backdrop').remove(); 
                        
                    });
                    /*window.location.reload(true); */
                    $('.ale-level').html('<div class="alert alert-success text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Class Level Updated Successfully!</div>');
                }else if(str == 'no'){
                     $('.editL-error').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Error Updating Class Level!</div>');
            }
            else
                $('.editL-error').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+msg+'</div>');
                       
        })

    }
});

///End of EDIT a level on the system when the modal is clicked


//askDelete Level
/// 
$(document).on('click','.askDeleteLevel',function(e){
    e.preventDefault();
    var deleteLevelId = $(this).data('lid');
    var levelNam = $(this).data('lvlname');
    $('.askDeleteLevel-yes').val(deleteLevelId);
     $('.modal-title').text('Delete Level');
     $('.modal-msg').text('Are you sure you want to delete ' + levelNam.toUpperCase() + '?');
     $('#askDeleteLevel').show();

    });
$(document).on('click','.askDeleteLevel-no',function(e){
    e.preventDefault();
     $('#askDeleteLevel').hide('800', function() {
       $('body').removeClass('modal-open'); 
        //need to remove div with modal-backdrop class
        $('.modal-backdrop').remove(); 
         
     });

    });
//Delete
$(document).on('click','.askDeleteLevel-yes',function(e){
    e.preventDefault();
    var deleteLevel =$(this).val();
    if (deleteLevel != '') {
        $.ajax({
            url: 'users',
            type: 'POST',
            data: {'levID': deleteLevel}
            //dataType:'json',
        })
        .done(function(msg) {
           
            var str = msg.substr(0,3);
            if(str== 'yes')
                //if(msg == 'yes')
                {
                   $('#askDeleteLevel').hide('800', function() {
                     $('body').removeClass('modal-open'); 
                 //need to remove div with modal-backdrop class
                 $('.modal-backdrop').remove(); 
                    
                  });
                    $('.modal-title').text('Delete Success');
                    $('.modal-msg').text('You have successfully deleted the level');
                     $('#askDeleteLevel-success').show();
                }                       
        })
        .fail(function() {
            alert("error");
        })

    }
});

$(document).on('click','.askDeleteLevel-ok',function(e){
    e.preventDefault();
     $('#askDeleteLevel-success').hide('800', function() {
         
       $('body').removeClass('modal-open'); 
        //need to remove div with modal-backdrop class
        $('.modal-backdrop').remove(); 
          window.location.reload();
     });

    });//end of ask delete 

//Add a Assessment Type on the system when the modal is clicked
$(document).on('click','.addAsseType',function(e){
    e.preventDefault();
    var addAssessments = $('#newType').val();
    if (addAssessments != '') {
        $.ajax({
            url: 'addAssessType',
            type: 'POST',
            data: {'assessName': addAssessments},
            //dataType:'json',
        })
        .done(function(msg) {
            if(msg == 'yes')
                {
                    $('#myModal1').hide('800', function() {
                      $('body').removeClass('modal-open'); 
                       //need to remove div with modal-backdrop class
                       $('.modal-backdrop').remove(); 
                        
                    });
                    /*window.location.reload(true); */
                    $('.ale-asses').html('<div class="alert alert-success text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Assessment Type Added Successfully!</div>');
                }else if(msg == 'no'){
                     $('.ale-levelError').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Error Adding Assessment Type!</div>');
            }
            else
                $('.ale-assesAdd').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+msg+'</div>'); 

        })
        .fail(function() {
            alert("error");
        })

    }
});
///*End of Add Assessment Type modal Script js*/


/// EDIT a Assesssment type on the system when the modal is clicked
/// 
$(document).on('click','.assessEdit',function(e){
    e.preventDefault();
    var editAssessType = $(this).data('assessname');
    var editAssessId = $(this).data('assessid');
    $('#editAssessment').val(editAssessType);
    $('#assessID').val(editAssessId);

    });

$(document).on('click','.updateAssessment',function(e){
    e.preventDefault();
    var editAssessType =$('#editAssessment').val();
    var editAssessId =$('#assessID').val();
    if (editAssessType != '') {
        $.ajax({
            url: 'editAssessType',
            type: 'POST',
            data: {'assessName': editAssessType,
                   'assessID': editAssessId},
            //dataType:'json',
        })

        .done(function(msg) {
              var str = msg.substr(0,3);
                if(str == 'yes')
                {
                    $('#myModal').hide('800', function() {
                      $('body').removeClass('modal-open'); 
                       //need to remove div with modal-backdrop class
                       $('.modal-backdrop').remove(); 
                        
                    });
                    /*window.location.reload(true); */
                    $('.ale-asses').html('<div class="alert alert-success text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Assessment Type Updated Successfully!</div>');
                }else if(str == 'no'){
                     $('.ale-assesError').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Error Updating Assessment Type!</div>');
            }
            else
                $('.ale-assesError').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+msg+'</div>');                      
        })
       

    }
});

///End of EDIT a Assessment Type on the system when the modal is clicked

//askDelete Assessment Type
/// 
$(document).on('click','.askDeleteAssess',function(e){
    e.preventDefault();
    var deleteAssessId = $(this).data('lid');
    var assessNam = $(this).data('assessname');
    $('.askDeleteAssess-yes').val(deleteAssessId);
     $('.modal-title').text('Delete Assessment Type');
     $('.modal-msg').text('Are you sure you want to delete ' + assessNam.toUpperCase() + ' ?');
     $('#askDeleteAssess').show();

    });
$(document).on('click','.askDeleteAssess-no',function(e){
    e.preventDefault();
     $('#askDeleteAssess').hide('800', function() {
       $('body').removeClass('modal-open'); 
        //need to remove div with modal-backdrop class
        $('.modal-backdrop').remove(); 
         
     });

    });

//Delete
$(document).on('click','.askDeleteAssess-yes',function(e){
    e.preventDefault();
    var deleteAssessType =$(this).val();
    if (deleteAssessType != '') {
        $.ajax({
            url: 'users',
            type: 'POST',
            data: {'assessID': deleteAssessType}
            //dataType:'json',
        })
        .done(function(msg) {
           
            var str = msg.substr(0,3);
            if(str== 'yes')
                //if(msg == 'yes')
                {
                   $('#askDeleteAssess').hide('800', function() {
                     $('body').removeClass('modal-open'); 
                 //need to remove div with modal-backdrop class
                 $('.modal-backdrop').remove(); 
                    
                  });
                    $('.modal-title').text('Delete Success');
                    $('.modal-msg').text('You have successfully deleted the Assessment Type');
                     $('#askDeleteAssess-success').show();
                }                       
        })
        .fail(function() {
            alert("error");
        })

    }
});

$(document).on('click','.askDeleteAssess-ok',function(e){
    e.preventDefault();
     $('#askDeleteAssess-success').hide('800', function() {
         
       $('body').removeClass('modal-open'); 
        //need to remove div with modal-backdrop class
        $('.modal-backdrop').remove(); 
          window.location.reload();
     });

    });//end of ask delete 

    //Add a Class Group on the system when the modal is clicked
$(document).on('click','.addGroup',function(e){
    e.preventDefault();
    var addClassGroup = $('#newGroup').val();
    if (addClassGroup != '') {
        $.ajax({
            url: 'addClassGroup',
            type: 'POST',
            data: {'classGroupName': addClassGroup},
            //dataType:'json',
        })
        .done(function(msg) { 
            var str = msg.substr(0,3);
            if(str == 'yes')
                {
                    $('#myModal1').hide('800', function() {
                      $('body').removeClass('modal-open'); 
                       //need to remove div with modal-backdrop class
                       $('.modal-backdrop').remove(); 
                        
                    });
                    /*window.location.reload(true); */
                    $('.aler-group').html('<div class="alert alert-success text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Class Group Added Successfully!</div>');
                }else if(str == 'no'){
                     $('.aler-groupAdd').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Error Adding Class Group!</div>');
            }
            else
                $('.aler-groupAdd').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+msg+'</div>');                               
        })
        .fail(function() {
            alert("error");
        })

    }
});
//end of add class group script

//edit class group script
$(document).on('click','.groupEdit',function(e){
    e.preventDefault();
    var editClassGroups = $(this).data('gname');
    var editClassGroupId = $(this).data('gid');
    $('#editGroups').val(editClassGroups);
    $('#groupID').val(editClassGroupId);

    });
$(document).on('click','.updateGroup',function(e){
    e.preventDefault();
    var editClassGroups =$('#editGroups').val();
    var editClassGroupId =$('#groupID').val();
    if (editClassGroups != '') {
        $.ajax({
            url: 'editClassGroup',
            type: 'POST',
            data: {'classGroupName': editClassGroups,
                   'groupID': editClassGroupId},
            //dataType:'json',
        })

        .done(function(msg) {
              var str = msg.substr(0,3);
                if(str == 'yes')
                {
                    $('#myModal').hide('800', function() {
                      $('body').removeClass('modal-open'); 
                       //need to remove div with modal-backdrop class
                       $('.modal-backdrop').remove(); 
                        
                    });
                    /*window.location.reload(true); */
                    $('.aler-group').html('<div class="alert alert-success text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Class Group Updated Successfully!</div>');
                }else if(str == 'no'){
                     $('.aler-groupError').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Error Updating Class Group!</div>');
            }
            else
                $('.aler-groupError').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+msg+'</div>');                       
        })
    }
});


//askDelete Assessment Type
/// 
$(document).on('click','.askDeleteGroup',function(e){
    e.preventDefault();
    var deleteGroupId = $(this).data('gid');
    var classGroupNam = $(this).data('gcname');
    $('.askDeleteGroup-yes').val(deleteGroupId);
     $('.modal-title').text('Delete Class Group');
     $('.modal-msg').text('Are you sure you want to delete ' + classGroupNam.toUpperCase() + ' ?');
     $('#askDeleteGroup').show();

    });
$(document).on('click','.askDeleteGroup-no',function(e){
    e.preventDefault();
     $('#askDeleteGroup').hide('800', function() {
       $('body').removeClass('modal-open'); 
        //need to remove div with modal-backdrop class
        $('.modal-backdrop').remove(); 
         
     });

    });

//Delete
$(document).on('click','.askDeleteGroup-yes',function(e){
    e.preventDefault();
    var deleteClassGroup =$(this).val();
    if (deleteClassGroup != '') {
        $.ajax({
            url: 'users',
            type: 'POST',
            data: {'groupID': deleteClassGroup}
            //dataType:'json',
        })
        .done(function(msg) {
           
            var str = msg.substr(0,3);
            if(str== 'yes')
                //if(msg == 'yes')
                {
                   $('#askDeleteGroup').hide('800', function() {
                     $('body').removeClass('modal-open'); 
                 //need to remove div with modal-backdrop class
                 $('.modal-backdrop').remove(); 
                    
                  });
                    $('.modal-title').text('Delete Success');
                    $('.modal-msg').text('You have successfully deleted the Class Group');
                     $('#askDeleteGroup-success').show();
                }                       
        })
        .fail(function() {
            alert("error");
        })

    }
});

$(document).on('click','.askDeleteGroup-ok',function(e){
    e.preventDefault();
     $('#askDeleteGroup-success').hide('800', function() {
         
       $('body').removeClass('modal-open'); 
        //need to remove div with modal-backdrop class
        $('.modal-backdrop').remove(); 
          window.location.reload();
     });

    });//end of ask delete 

//askDelete Teacher
/// 
$(document).on('click','.askDeleteTeacher',function(e){
    e.preventDefault();
    var deleteTeacher = $(this).data('techid');
    var teacherName = $(this).data('tname');
    $('.askDeleteTeacher-yes').val(deleteTeacher);
     $('.modal-title').text('Delete Teacher');
     $('.modal-msg').text('Are you sure you want to delete ' + teacherName.toUpperCase() + ' ?');
     $('#askDeleteTeacher').show();

    });
$(document).on('click','.askDeleteTeacher-no',function(e){
    e.preventDefault();
     $('#askDeleteTeacher').hide('800', function() {
       $('body').removeClass('modal-open'); 
        //need to remove div with modal-backdrop class
        $('.modal-backdrop').remove(); 
         
     });

    });

//Delete
$(document).on('click','.askDeleteTeacher-yes',function(e){
    e.preventDefault();
    var deleteTeacher =$(this).val();
    if (deleteTeacher != '') {
        $.ajax({
            url: '../Admin/users',
            type: 'POST',
            data: {'id_userTeacher': deleteTeacher}
            //dataType:'json',
        })
        .done(function(msg) {
           
            var str = msg.substr(0,3);
            if(str== 'yes')
                //if(msg == 'yes')
                {
                   $('#askDeleteTeacher').hide('800', function() {
                     $('body').removeClass('modal-open'); 
                 //need to remove div with modal-backdrop class
                 $('.modal-backdrop').remove(); 
                    
                  });
                    $('.modal-title').text('Delete Success');
                    $('.modal-msg').text('You have successfully deleted the Teacher');
                     $('#askDeleteTeacher-success').show();
                }                       
        })
        .fail(function() {
            alert("error");
        })

    }
});

$(document).on('click','.askDeleteTeacher-ok',function(e){
    e.preventDefault();
     $('#askDeleteTeacher-success').hide('800', function() {
         
       $('body').removeClass('modal-open'); 
        //need to remove div with modal-backdrop class
        $('.modal-backdrop').remove(); 
          window.location.reload();
     });

    });//end of ask delete 



//askDelete Parent/Guardian
/// 
$(document).on('click','.askDeleteGuardian',function(e){
    e.preventDefault();
    var deleteParent = $(this).data('guardid');
    var parentName = $(this).data('gname');
    $('.askDeleteGuardian-yes').val(deleteParent);
     $('.modal-title').text('Delete Guardian');
     $('.modal-msg').text('Are you sure you want to delete '+ parentName.toUpperCase() + ' ?');
     $('#askDeleteGuardian').show();

    });
$(document).on('click','.askDeleteGuardian-no',function(e){
    e.preventDefault();
     $('#askDeleteGuardian').hide('800', function() {
       $('body').removeClass('modal-open'); 
        //need to remove div with modal-backdrop class
        $('.modal-backdrop').remove(); 
         
     });

    });

//Delete
$(document).on('click','.askDeleteGuardian-yes',function(e){
    e.preventDefault();
    var deleteParent =$(this).val();
    if (deleteParent != '') {
        $.ajax({
            url: '../Admin/users',
            type: 'POST',
            data: {'id_userGuardian': deleteParent}
            //dataType:'json',
        })
        .done(function(msg) {
           
            var str = msg.substr(0,3);
            if(str== 'yes')
                //if(msg == 'yes')
                {
                   $('#askDeleteGuardian').hide('800', function() {
                     $('body').removeClass('modal-open'); 
                 //need to remove div with modal-backdrop class
                 $('.modal-backdrop').remove(); 
                    
                  });
                    $('.modal-title').text('Delete Success');
                    $('.modal-msg').text('You have successfully deleted the Parent/Guardian');
                     $('#askDeleteGuardian-success').show();
                }                       
        })
        .fail(function() {
            alert("error");
        })

    }
});

$(document).on('click','.askDeleteGuardian-ok',function(e){
    e.preventDefault();
     $('#askDeleteGuardian-success').hide('800', function() {
         
       $('body').removeClass('modal-open'); 
        //need to remove div with modal-backdrop class
        $('.modal-backdrop').remove(); 
          window.location.reload();
     });

    });//end of ask delete 

/***Delete Admin from the system****/

$(document).on('click','.askDeleteAdmin',function(e){
    e.preventDefault();
    var deleteAdmin = $(this).data('adminid');
    var adminName = $(this).data('aname');
    $('.askDeleteAdmin-yes').val(deleteAdmin);
     $('.modal-title').text('Delete Admin');
     $('.modal-msg').text('Are you sure you want to delete ' + adminName.toUpperCase() + ' ?');
     $('#askDeleteAdmin').show();

    });
$(document).on('click','.askDeleteAdmin-no',function(e){
    e.preventDefault();
     $('#askDeleteAdmin').hide('800', function() {
       $('body').removeClass('modal-open'); 
        //need to remove div with modal-backdrop class
        $('.modal-backdrop').remove(); 
         
     });

    });
//Delete
$(document).on('click','.askDeleteAdmin-yes',function(e){
    e.preventDefault();
    var deleteAdmin =$(this).val();
    if (deleteAdmin != '') {
        $.ajax({
            url: 'removeAdmin',
            type: 'POST',
            data: {'id_admin': deleteAdmin}
            //dataType:'json',
        })
        .done(function(msg) {
           
            var str = msg.substr(0,3);
            if(str== 'yes')
                //if(msg == 'yes')
                {
                   $('#askDeleteAdmin').hide('800', function() {
                     $('body').removeClass('modal-open'); 
                 //need to remove div with modal-backdrop class
                 $('.modal-backdrop').remove(); 
                    
                  });
                    $('.modal-title').text('Delete Success');
                    $('.modal-msg').text('You have successfully deleted the Adminnistrator');
                     $('#askDeleteAdmin-success').show();
                }                       
        })
        .fail(function() {
            alert("error");
        })

    }
});

$(document).on('click','.askDeleteAdmin-ok',function(e){
    e.preventDefault();
     $('#askDeleteAdmin-success').hide('800', function() {
         
       $('body').removeClass('modal-open'); 
        //need to remove div with modal-backdrop class
        $('.modal-backdrop').remove(); 
          window.location.reload();
     });

    });//end

/***End of Delete Admin from the system****/
//todo list script
$(document).ready(function(){
        
        function addListItem(){
            var text = $('#input').val();
            $('#result').append('<p>' + text + '&nbsp;<button class="goAway">Delete</button></p>').css('display','block');
            $('#input').val('');    
      $('.goAway').click(function(){
                $(this).parent().fadeOut();
            });
        }
        
        $('#button').on('click', addListItem);

});

//end of todo list script

//add learner  script
$(document).ready(function(){

/*$(document).on('change','#role',function(e){
    e.preventDefault();
  if ($(this).val()== 'learner')
  {
    $(".level_id").show();
  }
  else
  {
    $(".level_id").hide();
    $(".group_id").hide();
  }
});

//when admin selects level
$(document).on('change','.level_id',function(e){
    e.preventDefault();
    var levelId = $(this).val();
    $(".group_id").show();
    if (levelId != 0) {
        $.ajax({
            url: 'getLevel',
            type: 'POST',
            data: {'levelId': levelId},
            dataType:'json',
        })
        .done(function(data) {
        $(".group_id").html(data);
                      
        })
        .fail(function() {
            alert("error");
        })

    }
});

//when admin selects level
$(document).on('change','.group_id',function(e){
    e.preventDefault();
    var groupId = $(this).val();
    $(".group_id").show();
    if (groupId != 0) {
        $.ajax({
            url: 'getLevel',
            type: 'POST',
            data: {'groupId': groupId}
            //dataType:'json',
        })
        .done(function(data) {
            console.log(data);
        $(".cglID").val(data[0].['cglID']);
                      
        })
        .fail(function() {
            alert("error");
        })

    }
});*/


});



//end of add learner script

//send learner group message

$(document).on('click','.learncontact',function(e){
    e.preventDefault();
    var sendlearnmessage = $(this).data('learnname');
    var sendlearnerEmail = $(this).data('learnemail');
    $('#learnName').val(sendlearnmessage);
    $('#learnEmail').val(sendlearnerEmail);

    });

//askDelete Learner
/// 
$(document).on('click','.askDeleteLearner',function(e){
    e.preventDefault();
    var deleteLearnerID = $(this).data('learnid');
    var learnerName = $(this).data('learnname');
    $('.askDeleteLearner-yes').val(deleteLearnerID);
     $('.modal-title').text('Delete Learner');
     $('.modal-msg').text('Are you sure you want to delete ' + learnerName.toUpperCase() + ' ?');
     $('#askDeleteLearner').show();

    });
$(document).on('click','.askDeleteLearner-no',function(e){
    e.preventDefault();
     $('#askDeleteLearner').hide('800', function() {
       $('body').removeClass('modal-open'); 
        //need to remove div with modal-backdrop class
        $('.modal-backdrop').remove(); 
         
     });

    });
//Delete
$(document).on('click','.askDeleteLearner-yes',function(e){
    e.preventDefault();
    var deleteLearners =$(this).val();
    if (deleteLearners != '') {
        $.ajax({
            url: '../Admin/users',
            type: 'POST',
            data: {'learnid': deleteLearners}
            //dataType:'json',
        })
        .done(function(msg) {
           
            var str = msg.substr(0,3);
            if(str== 'yes')
                //if(msg == 'yes')
                {
                   $('#askDeleteLearner').hide('800', function() {
                     $('body').removeClass('modal-open'); 
                 //need to remove div with modal-backdrop class
                 $('.modal-backdrop').remove(); 
                    
                  });
                    $('.modal-title').text('Delete Success');
                    $('.modal-msg').text('You have successfully deleted the Learner');
                     $('#askDeleteLearner-success').show();
                }                       
        })
        .fail(function() {
            alert("error");
        })

    }
});

$(document).on('click','.askDeleteLearner-ok',function(e){
    e.preventDefault();
     $('#askDeleteLearner-success').hide('800', function() {
         
       $('body').removeClass('modal-open'); 
        //need to remove div with modal-backdrop class
        $('.modal-backdrop').remove(); 
          window.location.reload();
     });

    });//end of ask delete 


/**this triggers when the user click on the save(submit) button**/

//Send Dierect Message To Parent

$(document).on('click','.g_send-Message',function(e){
    e.preventDefault();

    var form = $('.messageForm')[0];
    var form_data = new FormData(form);
    $.ajax({
        url: 'sendParentsMessage',
        type: 'POST',
        cache:false,
        data: form_data, //send this to php
        contentType: false,
        processData: false,
    })
    .done(function(msg){  
           if (msg == "Added"){
                $('#myModal').hide('fast', function() {
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                });
                $('.win-alert').html('<div class="alert alert-success text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Message sent Successfully!</div>')
            }else if (msg == "NO")
            $('.comment-alert').html('<div class="alert alert-success text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Error! Message not sent!</div>')
            else
                $('.comment-alert').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+msg+'</div>');
        })

});// End Send Dierect Message To Parent!!

//Send Dierect Message To Teacher

$(document).on('click','.t_send-Message',function(e){
    e.preventDefault();

    var form = $('.t_messageForm')[0];
    var form_data = new FormData(form);
    $.ajax({
        url: 'sendTeacherMessage',
        type: 'POST',
        cache:false,
        data: form_data, //send this to php
        contentType: false,
        processData: false,
    })
    .done(function(msg){  
           if (msg == "Added"){
                $('#myModal').hide('fast', function() {
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                });
                $('.win-alert').html('<div class="alert alert-success text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Message sent Successfully!</div>')
            }else if (msg == "NO")
                   $('.comment-alert').html('<div class="alert alert-success text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Error! Message not sent!</div>')
            else
                $('.comment-alert').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+msg+'</div>');
        })


});// End Send Dierect Message To Teacher!!

//Send Direct Message To Learner

$(document).on('click','.Send-Messages',function(e){
    e.preventDefault();

    var form = $('.l_messageForm')[0];
    var form_data = new FormData(form);
    $.ajax({
        url: 'sendLearnerMessage',
        type: 'POST',
        cache:false,
        data: form_data, //send this to php
        contentType: false,
        processData: false,
    })
    .done(function(msg){  
           if (msg == "Added"){
                $('#myModal').hide('fast', function() {
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                });
                $('#learn-alert').html('<div class="alert alert-success text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Message sent Successfully!</div>')
            }else if (msg == "NO")
             $('#lea-alert').html('<div class="alert alert-success text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\
                    Error! Message not sent!</div>')
            else
                $('#lea-alert').html('<div class="alert alert-danger text-center" alert-dismissable>\
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+msg+'</div>');
        })


});// End Send Dierect Message To Learner!!

/***Send Admin message***/

$(document).on('click','.acontact',function(e){
    e.preventDefault();
    var sendadminMessage = $(this).data('aname');
    var sendadminEmail = $(this).data('adminemail');
    var adminid = $(this).data('adminid');
    $('#aName').val(sendadminMessage);
    $('#aEmail').val(sendadminEmail);
    $('#userAdminID').val(adminid);

    });


//Send Direct Message To Learner

$(document).on('click','.Send-aMessage',function(e){
    e.preventDefault();

    var form = $('.a_messageForm')[0];
    var form_data = new FormData(form);
    $.ajax({
        url: 'sendAdminMessage',
        type: 'POST',
        cache:false,
        data: form_data, //send this to php
        contentType: false,
        processData: false,
    })
    .done(function(msg){  
           if (msg == "Added"){
                $('#textAdmin').hide('fast', function() {
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                });
                $('.yes-alert').html('<div class="alert alert-success text-center">Message sent successfully!</div>');
            }else if (msg == "NO")
                $('.a-alert').html('<div class="alert alert-danger text-center">Error! Message not sent, please try again.</div>');
            else
                $('.a-alert').html('<div class="alert alert-danger">' + msg + '</div>');
        })


});// End Send Dierect Message To Learner!!
/***End of Send Admin message***/


$('#radioBtn a').on('click', function(){
    var sel = $(this).data('title');
    var tog = $(this).data('toggle');
    $('#'+tog).prop('value', sel);
    
    $('a[data-toggle="'+tog+'"]').not('[data-title="'+sel+'"]').removeClass('active').addClass('notActive');
    $('a[data-toggle="'+tog+'"][data-title="'+sel+'"]').removeClass('notActive').addClass('active');
})

// image uploader scripts
//End of  Form Wizard Script
/*Image upload Script*/
$("#profileImage").click(function(e) {
    $("#imageUpload").click();
});

function fasterPreview( uploader ) {
    if ( uploader.files && uploader.files[0] ){
          $('#profileImage').attr('src', 
             window.URL.createObjectURL(uploader.files[0]) );
    }
}

$("#imageUpload").change(function(){
    fasterPreview( this );
});
//update profile error messages script
$(document).on('click','#saveImage',function(e){
    e.preventDefault();
    //grab the data from the form to write it after on the table
    var form_data2 = {
        fileID: $('#fileID').val(),
        firstName: $('#firstName').val(),
        middleName: $('#middleName').val(),
        lastName: $('#lastName').val(),
        email: $('#email').val(),
        phone: $('#phone').val(),
        address: $('#address').val(),
        bio: $('#bio').val(),

}
 //end grab the data from
  var form = $('.frmEditProfile')[0];
    var form_data = new FormData(form);
$.ajax({
        url: "profile/editProfile",
        type: 'POST',
        data: form_data,
        enctype: 'multipart/form-data',
        processData: false,
         contentType: false,
        success: function(msg) {
            
            if (msg == "YES"){
         
              /* $('#filePath').bind('change', function() { // jQuery on change form
                 $("#profile_container").html('<img src="'.base_url($_SESSION['filePath']).'">');
                $('#proForm').ajaxForm({ // AJAX form plugin to upload a single image
              url: 'assets/files', // Call this file to update database and send back the correct new image and URL
            dataType: 'json', // JSON farmat
            success: function(data){
            $('#profile_container').html(data.text); // We display text in the div resultImageProfile tank
            $(".profile_pic ").load(function() { // We break the cache and force the browser to check for the image again
                $(".profile_pic").attr( 'src', data.imgURL + '?dt=' + (+new Date()) );
              }); 
        }
    }).submit();
});*/

                $('#updateProfile').hide('fast', function() {
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                });
                 //$('#proPic').attr('src', $('#proPic').attr('src') + '?' + Math.random());
                //window.location.reload(true); 
                $('#ale-AnnPage').html('<div class="alert alert-success text-center">Profile Updated successfully!</div>');
                //write the data assigned on the form in the correct places
                $('#label_1').text(form_data2['firstName']);
                $('#label_2').text(form_data2['middleName']);
                $('#label_3').text(form_data2['lastName']);
                $('#label_4').text(form_data2['email']);
                $('#label_5').text(form_data2['phone']);
                $('#label_6').text(form_data2['address']);
                $('#label_7').text(form_data2['bio']);
                $('#label_8').text(form_data2['firstName']);
                $('#label_9').text(form_data2['middleName']);
                $('#label_10').text(form_data2['lastName']);
                //end write data in the table

            }
            else if (msg == "NO"){
            
                $('#ale-msg').html('<div class="alert alert-danger text-center">Error Updating your Profile!.</div>');
            }
            else
                $('#ale-msg').html('<div class="alert alert-danger">' + msg + '</div>');
        }
    });

//$("#proPic").attr("src", "src='<?= base_url($userProfile->filePath);?>'");

});//end update profile error messages script

/*****new script for Assign Class to Teacher*****/


$(document).on('click','.Class_Assign',function(){
    $('#teach').val($(this).data('lname'));
    $('#userTedachID').val($(this).data('teahersid'));
    $('#mt_allocate_subjects').attr('disabled', 'true');
});  
 /*****End of script for Assign Class to Teacher*****/
/************WIZARD JS**********************/



/************////////////Start todo list/////////*****************/

    //add new item on the list
    $(document).on("click", "#submit_todo", function(e) {
        e.preventDefault();
        var sTaskDescription = $('#sTaskDescriptioen').val();
        if (sTaskDescription != '') {
            $.ajax({
                url: "admin/addTodoList",
                type: 'POST',
                data: {'sTaskDescription':sTaskDescription},
            })
            .done(function(msg) {
                if (msg=='yes') {
                    //clear textbox
                    $('#sTaskDescriptioen').val("");
                    //refresh list
                    printTodoView();
                }else {
                    alert('Error: Please try again.');
                }
            });//done
        }else{
            alert('You cannot add EMPTY todo item.');
        }
    });//onCLiCK


    //delete tasks which are marked completed
    $(document).on("click", ".todoDelete", function() {
        var todoDel = $(this).attr('id');
        $.ajax({
            url: "admin/deleteTodo",
            type: 'POST',
            data: {'todoDel':todoDel},
        })
        .done(function(msg) {
            if(msg=='YES')
            {
                //refresh the list
                printTodoView();
            }
       
        }) 
    });



        //delete tasks  from history
    $(document).on("click", ".remHistory", function() {
        var todoDelHistory = $(this).data('hid');
        console.log(todoDelHistory);
        $.ajax({
            url: "admin/deleteTodoHistory",
            type: 'POST',
            data: {'todoDelHistory':todoDelHistory},
        })
        .done(function(msg) {
            if(msg=='YES')
            {
                $('.todo_History_alert').html('<div class="alert alert-success text-center">Todo has been removed from history Successfully</div>');
            }
       
        }) 
    });

 //select all checkboxes
    $(document).on('change','#select_all_cc',function(){
        alert('all select');
        if($(this).is(":checked",true)){
            $(".checkBoxClass").prop('checked',true);
        }
        else{
            $(".checkBoxClass").prop('checked',false);
        }
        //select all checked checkboxes count
        $("#select_count").html($("input.value:checked").length+" Select");
    });
    //end selected checkboxes 

// //delete selected records
    $(document).on('click','.buttonDelete',function(){
        //delete selected records
        var messaging = [];
        $('.checkBoxClass:checked').each(function(){
            messaging.push($(this).val());
        });
        //console.log(messaging);
        if(messaging.length <= 0){
            alert('Please select atleast one Message.');
        }
        else{

            WRN_MESSAGE_DELETE = 'Are you sure you want to Delete this '+(messaging.length>1?"these":"this")+" ";
            var checked = confirm(WRN_MESSAGE_DELETE);
            if(checked == true){
                var selected_valuess = messaging.join(",");
                //console.log(selected_values);
                $.ajax({
                    url: 'Admin/wipeTodoHistory',
                    type: 'POST',
                    data: {'trashHistory':selected_valuess},
                    //dataType: 'json',
                    success: function(response){
                  
                    }
              });
           }
        }
       });//end delete selected messages

//end of mark all
//mark all completed tasks
$(document).on("click", ".complete", function() {
    var todofinish = $(this).attr('id');
    $.ajax({
        url: "admin/finishTodo",
        type: 'POST',
        data: {'todofinish':todofinish},
    })
    .done(function(msg) {
        if(msg=='YES')
        {
        //refresh list
         printTodoView();
        }
   
    })
    
});

//triggers the modal
$(document).on("click", ".history", function() {
    $.ajax({
        url: "admin/getTodoHistory",
        type: 'POST',
        dataType:'json'
    })
    .done(function(data) {
        
        //refresh list
         $("#preview").html(data);
    })
    
});


//refresh list of todo after each user activity 
function printTodoView() {
    $.ajax({
        url: "admin/getTodolist",
        dataType:'json',
    })
    .done(function(data) {
        //print to the view
        $(".buildTodo").html(data);
        
    })  
 } 
/************////////////END todo list/////////*****************/

/*Assign Class Teacher*/
function createExpr(arr) {
   var index = 0;
   var expr = [":containsiAND('" + arr[0] + "')"];
   for (var i = 1; i < arr.length; i++) {
      if (arr[i] === 'AND') {
         expr[index] += ":containsiAND('" + arr[i + 1] + "')";
         i++;
      } else if (arr[i] === 'OR') {
         index++;
         expr[index] = ":containsiOR('" + arr[i + 1] + "')";
         i++;
      }
   }
   return expr;
}

$(document).ready(function() {

   $(".searchKey").keyup(function() {
      var searchTerm = $(".searchKey").val().replace(/["']/g, "");
      // var listItem = $('.results tbody').children('tr');
      var arr = searchTerm.split(/(AND|OR)/);
      var exprs = createExpr(arr);

      var searchSplit = searchTerm.replace(/AND/g, "'):containsiAND('").replace(/OR/g, "'):containsiOR('");

      $.extend($.expr[':'], {
         'containsiAND': function(element, i, match, array) {
            return (element.textContent || element.innerText || '').toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
         }
      });
      $.extend($.expr[':'], {
         'containsiOR': function(element, i, match, array) {
            return (element.textContent || element.innerText || '').toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
         }
      });
      $('.results tbody tr').attr('visible', 'false');
      for (var expr in exprs) {
         $(".results tbody tr" + exprs[expr]).each(function(e) {
            $(this).attr('visible', 'true');
         });
      }
      /**
      $(".results tbody tr").not(":containsiAND('" + searchSplit + "')").each(function(e) {
         $(this).attr('visible', 'false');
      });
      $(".results tbody tr:containsiAND('" + searchSplit + "')").each(function(e) {
         $(this).attr('visible', 'true');
      });
      $(".results tbody tr:containsiOR('" + searchSplit + "')").each(function(e) {
         $(this).attr('visible', 'true');
      });
      **/
      var searchCount = $('.results tbody tr[visible="true"]').length;
      $('.searchCount').text(searchCount + ' item');
      if (searchCount == '0') {
         $('.no-result').show();
      } else {
         $('.no-result').hide();
      }
      if ($('.searchKey').val().length == 0) {
         $('.searchCount').hide();
      } else {
         $('.searchCount').show();
      }
   });
});

$(document).on('click','.assignClass',function(){
$('#assignClasses').val($(this).data('lname'));
$('#teacherID').val($(this).data('teachid'));
$('#teacher_userID').val($(this).data('teacher_userid'));
});

$(document).on('click','#assign',function(e){
         e.preventDefault();
    var assignTeacherClass = {
        date: $('#date').val(),
        teacherID: $('#teacherID').val(),
        level: $('#teacherLevel').val(),
        group: $('#teacherGroup').val(),
        //input fields ids and names that are gonna be passed in the data variable 
    };
    $.ajax({
        url: "assignClass",
        type: 'POST',
        data: assignTeacherClass,
        success: function(msg) {
            var str = msg.substr(0,3);
            if (str == "YES"){
                var level= $('#teacherLevel option:selected').text();
                var group= $('#teacherGroup option:selected').text();
                var tID = $('#teacherID').val();
                var tuID = $('#teacher_userID').val();
                $('.'+tID).text(level);
                $('.'+tuID).text(group);
                $('#assignClass').modal('hide');
                $('.alert-assignPage').html('<div class="alert alert-success text-center">Teacher has been Assigned a Class Successfully</div>');
            //$('#compose').hide();
            }else if (msg == "NO"){
                $('.alert-assignError').html('<div class="alert alert-danger text-center">Error Assigning Teacher a class! Please try again later.</div>');
            }   
            else
               $('.alert-assignError').html('<div class="alert alert-danger">' + msg + '</div>');
        }
    });
    
});
//remove teacher from class as class teacher

 //delete tasks which are marked completed
    $(document).on("click", ".RemoveClassTeacher", function(e) {
        e.preventDefault();
        var removeTeacherClass = $(this).data('teahersid');
        if(removeTeacherClass != '') {
        $.ajax({
            url: "../admin/removeClassTeacher",
            type: 'POST',
            data: {'removeTeacherClass':removeTeacherClass},
        })
        .done(function(msg) {
             if (msg == "YES"){
                //$('#assignClass').append(data); 
                //$('#assignClass').modal('hide');
                console.log(msg);         
                $('.class_Teacher_alert').html('<div class="alert alert-success text-center">Teacher has been removed as a class Teacher Successfully</div>');
            //$('#compose').hide();
           
            }else if (msg == "NO"){
                $('.alert-assignError').html('<div class="alert alert-danger text-center">Error removing Teacher as a class Teacher! Please try again later.</div>');
            }   
            else
               $('.alert-assignError').html('<div class="alert alert-danger">' + msg + '</div>');
       
        }) 
    }
    });
/*End ofAssign Class Teacher*/

/*Pagination Script*/
    pageSize = 4;

    var pageCount =  $(".items").length / pageSize;
    
     for(var i = 0 ; i<pageCount;i++){
        
       var pagination =$("#pagin").append('<li><a href="#">'+(i+1)+'</a></li> ');
       console.log($(".items").length );
     }
        $("#pagin li").first().find("a").addClass("current")
    showPage = function(page) {
        $(".items").hide();
        $(".items").each(function(n) {
            if (n >= pageSize * (page - 1) && n < pageSize * page)
                $(this).show();
        });        
    }
    
    showPage(1);

    $("#pagin li a").click(function(e) {
          e.preventDefault();
        $("#pagin li a").removeClass("current");
        $(this).addClass("current");
        showPage(parseInt($(this).text())) 
    });

    /*End of pagination*/
    
    /******Teacherlist Script *******/
    $(document).on('click','.viewDetails',function(e){
    e.preventDefault();
    var teachId = $(this).data('teachid');
    //$(".group_id").show();
    if (teachId  != 0) {
        $.ajax({
            url: 'getTeacherSubjects',
            type: 'POST',
            data: {'teachId':teachId},
            dataType:'json',
        })
        .done(function(data) {
            console.log (data);
        $("#mySubjects").html(data);
                      
        })
        .fail(function() {
            alert("error");
        })

    }
});

  /******Teacherlist Script *******/

    /******class group level Script *******/
    $(document).on('click','.addclassglevel',function(e){
             e.preventDefault();
        var addClassGroupLevel = {
            levelID: $('#cgl_level').val(),
            groupID: $('#cgl_group').val(),
            limit: $('#cgl_limit').val(),
            cglid: $('#cglID').val(),
            //input fields ids and names that are gonna be passed in the data variable 
        };
        $.ajax({
            url: "add_class_group_level",
            type: 'POST',
            data: addClassGroupLevel,
            success: function(msg) {
                var str = msg.substr(0,3);
                console.log(str);
                if (str == "Add"){
                    $('#add_class_group_level').modal('hide');
                    $('.alert-addcgl').html('<div class="alert alert-success text-center">Class group level added Successfully</div>');
                //$('#compose').hide();
                }else if (str == "Edi"){
                    $('#add_class_group_level').modal('hide');
                    $('.alert-addcgl').html('<div class="alert alert-success text-center">Class group level Edited Successfully</div>');
                //$('#compose').hide();
                }else if (str == "NO"){
                    $('.alert-cglError').html('<div class="alert alert-danger text-center">Error adding Class group level! Please try again.</div>');
                }   
                else
                   $('.alert-cglError').html('<div class="alert alert-danger">' + msg + '</div>');
            }
        });
        
    });
$(document).on('click','.cglEdit',function(e){
    e.preventDefault();
    var editcglimit = $(this).data('cgl_limit');
    var editcglevel = $(this).data('cgl_level');
    var editcgroup = $(this).data('cgl_group');
    var cglid = $(this).data('cglid');
    $('#cgl_limit').val(editcglimit);
    $('#cgl_level').val(editcglevel);
    $('#cgl_group').val(editcgroup);
    $('#cglID').val(cglid);
    $('.addclassglevel').text('Update');
    $('#lineModalLabel').text('Update Class Group Level');
    });

   $(document).on('click','.add_new_cgl',function(e){
    e.preventDefault();
    $('#cgl_limit').val('');
    $('#cgl_level').val('');
    $('#cgl_group').val('');
    $('#cglID').val('');
    $('.addclassglevel').text('Add');
    $('#lineModalLabel').text('Add Class Group Level');
    });

   //askDelete class group level
/// 
$(document).on('click','.askDeleteCgLevel',function(e){
    e.preventDefault();
    var deletecgl = $(this).data('cglid'); 
    var deletelimit = $(this).data('cgl_limit'); 
    var deletelevel = $(this).data('cgl_level'); 
    var cglname = $(this).data('cglname'); 
    $('.askDeleteCgLevel-yes').val(deletecgl);
     $('.modal-title').text('Delete Class Group Level');
     $('.modal-msg').text('Are you sure you want to delete ' +cglname.toUpperCase() + ' ' + deletelevel + ' Limit' + ' ' + deletelimit +' ?');
     $('#askDeleteCgLevel').show();

    });
$(document).on('click','.askDeleteCgLevel-no',function(e){
    e.preventDefault();
     $('#askDeleteCgLevel').hide('800', function() {
       $('body').removeClass('modal-open'); 
        //need to remove div with modal-backdrop class
        $('.modal-backdrop').remove(); 
         
     });

    });
//Delete
$(document).on('click','.askDeleteCgLevel-yes',function(e){
    e.preventDefault();
    var deletecgl =$(this).val();
    if (deletecgl != '') {

        $.ajax({
            url: 'removeclass_group_level',
            type: 'POST',
            data: {'cglid': deletecgl}
            //dataType:'json',
        })
        .done(function(msg) {
            var str = msg.substr(0,3);
            if(str== 'yes')
                //if(msg == 'yes')
                {
                   $('#askDeleteCgLevel').hide('800', function() {
                     $('body').removeClass('modal-open'); 
                 //need to remove div with modal-backdrop class
                 $('.modal-backdrop').remove(); 
                    
                  });
                    $('.modal-title').text('Delete Success');
                    $('.modal-msg').text('You have successfully deleted the Class Group Level');
                     $('#askDeleteCgLevel-success').show();
                }                       
        })
        .fail(function() {
            alert("error");
        })

    }
});

$(document).on('click','.askDeleteCgLevel-ok',function(e){
    e.preventDefault();
     $('#askDeleteCgLevel-success').hide('800', function() {
         
       $('body').removeClass('modal-open'); 
        //need to remove div with modal-backdrop class
        $('.modal-backdrop').remove(); 
          window.location.reload();
     });

    });//end of ask delete 
    /******class group level Script *******/





/***********************Search Script******************************/

$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#preview *").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});

/************************End of search script*****************************/


/************************Ask Delete User*****************************/

    //askDelete Subject
/// 
$(document).on('click','.askDeleteUser',function(e){
    e.preventDefault();
    var deleteUserID = $(this).data('usernameid'); 
    var userNam = $(this).data('username'); 
    $('.askDeleteUser-yes').val(deleteUserID);
     $('.modal-title').text(' Delete User');
     $('.modal-msg').text('Are you sure you want to delete ' + userNam.toUpperCase() + ' ?');
     $('#askDeleteUser').show();

    });
$(document).on('click','.askDeleteUser-no',function(e){
    e.preventDefault();
     $('#askDeleteUser').hide('800', function() {
       $('body').removeClass('modal-open'); 
        //need to remove div with modal-backdrop class
        $('.modal-backdrop').remove(); 
         
     });

    });

//Delete
$(document).on('click','.askDeleteUser-yes',function(e){
    e.preventDefault();
    var deleteUserID =$(this).val();
    if (deleteUserID != '') {

        $.ajax({
            url: '../Admin/users',
            type: 'POST',
            data: {'id_user': deleteUserID}
            //dataType:'json',
        })
        .done(function(msg) {
            var str = msg.substr(0,3);
            if(str== 'yes')
                //if(msg == 'yes')
                {
                   $('#askDeleteUser').hide('800', function() {
                     $('body').removeClass('modal-open'); 
                 //need to remove div with modal-backdrop class
                 $('.modal-backdrop').remove(); 
                    
                  });
                    $('.modal-title').text('Delete Success');
                    $('.modal-msg').text('You have successfully deleted the User');
                     $('#askDeleteUser-success').show();
                }                       
        })
        .fail(function() {
            alert("error");
        })

    }
});

$(document).on('click','.askDeleteUser-ok',function(e){
    e.preventDefault();
     $('#askDeleteUser-success').hide('800', function() {
         
       $('body').removeClass('modal-open'); 
        //need to remove div with modal-backdrop class
        $('.modal-backdrop').remove(); 
          window.location.reload();
     });

    });//end of ask delete 
/************************End of Ask Delete User*****************************/

$(document).on('click','.viewp',function(e){
    e.preventDefault();
    var cID = $(this).data('cid'); 
    var cNam = $(this).data('cname'); 
    var cgNam = $(this).data('cgnam'); 
    var mabitso = $(this).data('mabitso'); 
    var bio = $(this).data('bio'); 
    var pic = $(this).data('pic'); 
     $('#profile_pic').attr('src',pic);
     $('.modal-msg2').text('Full Names : ' + mabitso);
     $('.modal-msg').text('Currently in:  ' + cgNam + '  ' + cNam);
     $('.modal-msg3').text('Biography:  ' + bio);
     console.log(bio);
     $('#viewProfile').show();

    });
$(document).on('click','.viewProfile-no',function(e){
    e.preventDefault();
     $('#viewProfile').hide('800', function() {
       $('body').removeClass('modal-open'); 
        //need to remove div with modal-backdrop class
        $('.modal-backdrop').remove(); 
         
     });

    });
    /***** Modal Script ******
    $(document).on('shown.bs.modal','.modal',function(e){
    e.preventDefault();
    alert('modal');
    //$('#myModal').on('shown.bs.modal', function () {
    $(this).find('.modal-dialog').css({width:'auto',
                               height:'auto', 
                              'max-height':'100%'});
});*/
    /******End of Modal   Script *******/

    /*Pagination Script
    pageSizes = 4;

    var pageCounts =  $(".contents").length / pageSizes;
    
     for(var i = 0 ; i<pageCounts;i++){
        
       $("#paginations").append('<li><a href="#">'+(i+1)+'</a></li> ');
     }
        $("#paginations li").first().find("a").addClass("current")
    showPage = function(page) {
        $(".contents").hide();
        $(".contents").each(function(n) {
            if (n >= pageSizes * (page - 1) && n < pageSizes * page)
                $(this).show();
        });        
    }
    
    showPage(1);

    $("#paginations li a").click(function(e) {
          e.preventDefault();
        $("#paginations li a").removeClass("current");
        $(this).addClass("current");
        showPage(parseInt($(this).text())) 
    });*/

    /*End of pagination*/

/***************Accordian Script***********************/
 $(document).ready(function(){
         $('[data-toggle="tooltip"]').tooltip(); 
         });
         
         
        
      
         
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].onclick = function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight){
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  }
}



/***************End of Accordian Script***********************/




    
})  ;//End of Document.ready
 