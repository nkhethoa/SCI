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
        .done(function(data) {
            window.location.reload(true);                               
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
        .done(function(data) {
                if(data == 'yes')
                {
                    window.location.reload(true); 
                }                         
        })
        .fail(function() {
            alert("error");
        })

    }
});
//askDelete Subject
/// 
$(document).on('click','.askDeleteSubject',function(e){
    e.preventDefault();
   
    var deleteSubject = $(this).data('subid'); 
    $('.askDeleteSubject-yes').val(deleteSubject);
     $('.modal-title').text('Delete Subject');
     $('.modal-msg').text('Are you sure you want to delete this Subject?')
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
            url: 'users',
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
        .done(function(data) {
            window.location.reload(true);  

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
        .done(function(data) {
                if(data == 'yes')
                {
                     window.location.reload(true);  
                }                         
        })
        .fail(function() {
            alert("error");
        })

    }
});

/// EDIT a Quarter on the system when the modal is clicked
/// 
$(document).on('click','.askDeleteQuarter',function(e){
    e.preventDefault();
    var deleteQuarterId = $(this).data('qid');
    $('.askDelete-yes').val(deleteQuarterId);
     $('.modal-title').text('Delete Quarter');
     $('.modal-msg').text('Are you sure you want to delete this Quarter?')
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
        .done(function(data) {
            window.location.reload(true);  

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
        .done(function(data) {
                if(data == 'yes')
                {
                    
                     window.location.reload(true);  
                }else {
                    alert("There's something wrong with your code buddy")
                }                        
        })
        .fail(function() {
            alert("error");
        })

    }
});

///End of EDIT a level on the system when the modal is clicked


//askDelete Level
/// 
$(document).on('click','.askDeleteLevel',function(e){
    e.preventDefault();
    var deleteLevelId = $(this).data('lid');
    $('.askDeleteLevel-yes').val(deleteLevelId);
     $('.modal-title').text('Delete Level');
     $('.modal-msg').text('Are you sure you want to delete this Level?')
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
        .done(function(data) {
            window.location.reload(true);  

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

        .done(function(data) {
                if(data == 'yes')
                {
                    
                     window.location.reload(true);  
                }else {
                    alert("There's something wrong with your code buddy")
                }                        
        })
        .fail(function() {
            alert("error");
        })

    }
});

///End of EDIT a Assessment Type on the system when the modal is clicked

//askDelete Assessment Type
/// 
$(document).on('click','.askDeleteAssess',function(e){
    e.preventDefault();
    var deleteAssessId = $(this).data('lid');
    $('.askDeleteAssess-yes').val(deleteAssessId);
     $('.modal-title').text('Delete Assessment Type');
     $('.modal-msg').text('Are you sure you want to delete this Assessment Type?')
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
        .done(function(data) { 
            window.location.reload(true);                               
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

        .done(function(data) {
                if(data == 'yes')
                {
                    
                     window.location.reload(true);  
                }else {
                    alert("There's something wrong with your code buddy")
                }                        
        })
        .fail(function() {
            alert("error");
        })

    }
});


//askDelete Assessment Type
/// 
$(document).on('click','.askDeleteGroup',function(e){
    e.preventDefault();
    var deleteGroupId = $(this).data('gid');
    $('.askDeleteGroup-yes').val(deleteGroupId);
     $('.modal-title').text('Delete Class Group');
     $('.modal-msg').text('Are you sure you want to delete this Class Group?')
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
    $('.askDeleteTeacher-yes').val(deleteTeacher);
     $('.modal-title').text('Delete Teacher');
     $('.modal-msg').text('Are you sure you want to delete this Teacher?')
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
            url: 'users',
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
    $('.askDeleteGuardian-yes').val(deleteParent);
     $('.modal-title').text('Delete Parent/Guardian');
     $('.modal-msg').text('Are you sure you want to delete this Parent/Guardian?')
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
            url: 'users',
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

    $(document).on('change','#role',function(e){
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
            data: {'levelId': levelId}
            //dataType:'json',
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
/*$(document).on('change','.group_id',function(e){
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
    $('.askDeleteLearner-yes').val(deleteLearnerID);
     $('.modal-title').text('Delete Learner');
     $('.modal-msg').text('Are you sure you want to delete this Learner?')
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
            url: 'users',
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

$(document).on('click','.Send-Message',function(e){
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
                $('.win-alert').html('<div class="alert alert-success text-center">Message sent successfully!</div>');
            }else if (msg == "NO")
                $('.comment-alert').html('<div class="alert alert-danger text-center">Error! Message not sent, please try again.</div>');
            else
                $('.comment-alert').html('<div class="alert alert-danger">' + msg + '</div>');
        })

});// End Send Dierect Message To Parent!!

//Send Dierect Message To Teacher

$(document).on('click','.Send-Message',function(e){
    e.preventDefault();

    var form = $('.messageForm')[0];
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
                $('.win-alert').html('<div class="alert alert-success text-center">Message sent successfully!</div>');
            }else if (msg == "NO")
                $('.comment-alert').html('<div class="alert alert-danger text-center">Error! Message not sent, please try again.</div>');
            else
                $('.comment-alert').html('<div class="alert alert-danger">' + msg + '</div>');
        })


});// End Send Dierect Message To Teacher!!

//Send Direct Message To Learner

$(document).on('click','.Send-Messages',function(e){
    e.preventDefault();

    var form = $('.messageForm')[0];
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
                $('.win-alert').html('<div class="alert alert-success text-center">Message sent successfully!</div>');
            }else if (msg == "NO")
                $('.comment-alert').html('<div class="alert alert-danger text-center">Error! Message not sent, please try again.</div>');
            else
                $('.comment-alert').html('<div class="alert alert-danger">' + msg + '</div>');
        })


});// End Send Dierect Message To Learner!!




//Form Wizard Script
"use strict";
function scroll_to_class(element_class, removed_height) {
    var scroll_to = $(element_class).offset().top - removed_height;
    if($(window).scrollTop() != scroll_to) {
        $('.form-wizard').stop().animate({scrollTop: scroll_to}, 0);
    }
}

function bar_progress(progress_line_object, direction) {
    var number_of_steps = progress_line_object.data('number-of-steps');
    var now_value = progress_line_object.data('now-value');
    var new_value = 0;
    if(direction == 'right') {
        new_value = now_value + ( 100 / number_of_steps );
    }
    else if(direction == 'left') {
        new_value = now_value - ( 100 / number_of_steps );
    }
    progress_line_object.attr('style', 'width: ' + new_value + '%;').data('now-value', new_value);
}

jQuery(document).ready(function() {
    
    /*
        Form
    */
    $('.form-wizard fieldset:first').fadeIn('slow');
    
    $('.form-wizard .required').on('focus', function() {
        $(this).removeClass('input-error');
    });
    
    // next step
    $('.form-wizard .btn-next').on('click', function() {
        var parent_fieldset = $(this).parents('fieldset');
        var next_step = true;
        // navigation steps / progress steps
        var current_active_step = $(this).parents('.form-wizard').find('.form-wizard-step.active');
        var progress_line = $(this).parents('.form-wizard').find('.form-wizard-progress-line');
        
        // fields validation
        parent_fieldset.find('.required').each(function() {
            if( $(this).val() == "" ) {
                $(this).addClass('input-error');
                next_step = false;
            }
            else {
                $(this).removeClass('input-error');
            }
        });
        // fields validation
        
        if( next_step ) {
            parent_fieldset.fadeOut(400, function() {
                // change icons
                current_active_step.removeClass('active').addClass('activated').next().addClass('active');
                // progress bar
                bar_progress(progress_line, 'right');
                // show next step
                $(this).next().fadeIn();
                // scroll window to beginning of the form
                scroll_to_class( $('.form-wizard'), 20 );
            });
        }
        
    });
    
    // previous step
    $('.form-wizard .btn-previous').on('click', function() {
        // navigation steps / progress steps
        var current_active_step = $(this).parents('.form-wizard').find('.form-wizard-step.active');
        var progress_line = $(this).parents('.form-wizard').find('.form-wizard-progress-line');
        
        $(this).parents('fieldset').fadeOut(400, function() {
            // change icons
            current_active_step.removeClass('active').prev().removeClass('activated').addClass('active');
            // progress bar
            bar_progress(progress_line, 'left');
            // show previous step
            $(this).prev().fadeIn();
            // scroll window to beginning of the form
            scroll_to_class( $('.form-wizard'), 20 );
        });
    });
    
    // submit
    $('.form-wizard').on('submit', function(e) {
        
        // fields validation
        $(this).find('.required').each(function() {
            if( $(this).val() == "" ) {
                e.preventDefault();
                $(this).addClass('input-error');
            }
            else {
                $(this).removeClass('input-error');
            }
        });
        // fields validation
        
    });
    
    
});

// image uploader scripts 

var $dropzone = $('.image_picker'),
    $droptarget = $('.drop_target'),
    $dropinput = $('#inputFile'),
    $dropimg = $('.image_preview'),
    $remover = $('[data-action="remove_current_image"]');

$dropzone.on('dragover', function() {
  $droptarget.addClass('dropping');
  return false;
});

$dropzone.on('dragend dragleave', function() {
  $droptarget.removeClass('dropping');
  return false;
});

$dropzone.on('drop', function(e) {
  $droptarget.removeClass('dropping');
  $droptarget.addClass('dropped');
  $remover.removeClass('disabled');
  e.preventDefault();
  
  var file = e.originalEvent.dataTransfer.files[0],
      reader = new FileReader();

  reader.onload = function(event) {
    $dropimg.css('background-image', 'url(' + event.target.result + ')');
  };
  
  reader.readAsDataURL(file);

  return false;
});

$dropinput.change(function(e) {
  $droptarget.addClass('dropped');
  $remover.removeClass('disabled');
  $('.image_title input').val('');
  
  var file = $dropinput.get(0).files[0],
      reader = new FileReader();
  
  reader.onload = function(event) {
    $dropimg.css('background-image', 'url(' + event.target.result + ')');
  }
  
  reader.readAsDataURL(file);
});

$remover.on('click', function() {
  $dropimg.css('background-image', '');
  $droptarget.removeClass('dropped');
  $remover.addClass('disabled');
  $('.image_title input').val('');
});

$('.image_title input').blur(function() {
  if ($(this).val() != '') {
    $droptarget.removeClass('dropped');
  }
});
$('#radioBtn a').on('click', function(){
    var sel = $(this).data('title');
    var tog = $(this).data('toggle');
    $('#'+tog).prop('value', sel);
    
    $('a[data-toggle="'+tog+'"]').not('[data-title="'+sel+'"]').removeClass('active').addClass('notActive');
    $('a[data-toggle="'+tog+'"][data-title="'+sel+'"]').removeClass('notActive').addClass('active');
})

// image uploader scripts

//add learner  script
  /*$(document).ready(function () {
        $('#memployboxes-0').change(function () {
            $('#motherdiv').fadeIn();
        });
        $('#memployboxes-1').change(function () {
            $('#motherdiv').fadeOut();
        });
        
    });
  //add learner  script
  $(document).ready(function () {
        $('#father-0').change(function () {
            $('#fatherDiv').fadeIn();
        });
        $('#father-1').change(function () {
            $('#fatherDiv').fadeOut();
        });
        
    });*/
//End of  Form Wizard Script

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
                $('#updateProfile').hide('fast', function() {
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                });
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

                $('#ale-msg').html('<div class="alert alert-danger text-center">Error Updating your Profile! Please try again later.</div>');
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

});  
 /*****End of script for Assign Class to Teacher*****/
/************WIZARD JS**********************/
 //compose modal
   $(document).on('click','.ttt',function(e){
         e.preventDefault();
    var form_learner = {
        firstName: $('#firstName').val(),
        middleName: $('#middleName').val(),
        lastName: $('#lastName').val(),
        email: $('#email').val(),
        phone:$('#phone').val(),
        address:$('#address').val(),
        lZip:$('#lZip').val(),
        lCity:$('#lCity').val(),
        lProvince:$('#lProvince').val(),
        doeLearnNo:$('#doeLearnNo').val(),
        cglID:$('#cglID').val(),
        learner_id:$('#learner_id').val(),
        //input fields ids and names that are gonna be passed in the data variable 
    };

    $.ajax({
        url: "addLearnerOnWizard",
        type: 'POST',
        data: form_learner,
        success: function(msg) {
            if (msg == "YES"){        
                $('#alert-lPage').html('<div class="alert alert-success text-center">Learnerhas been sent added successfully!</div>');
            //$('#compose').hide();
            }else if (msg == "NO"){
                $('.alert-lmsgw').html('<div class="alert alert-danger text-center">Error adding  learner! Please try again later.</div>');
            }   
            else
               $('.alert-lmsgw').html('<div class="alert alert-danger">' + msg + '</div>');
        }
    });
});//end compose message

$(document).on('click','.addCheck',function(e){
         e.preventDefault();
    var form_guardian = {
        firstName: $('#firstName').val(),
        middleName: $('#middleName').val(),
        lastName: $('#lastName').val(),
        email: $('#email').val(),
        phone:$('#phone').val(),
        address:$('#address').val(),
        gZip:$('#gZip').val(),
        gCity:$('#gCity').val(),
        gProvince:$('#gProvince').val(),
               
        //input fields ids and names that are gonna be passed in the data variable 
    };

    $.ajax({
        url: "addGuardianOnWizard",
        type: 'POST',
        data: form_guardian,
        success: function(msg) {
            if (msg == "YES"){        
                $('#alert-lPage').html('<div class="alert alert-success text-center">Learnerhas been sent added successfully!</div>');
            //$('#compose').hide();
            }else if (msg == "NO"){
                $('.alert-lmsgw').html('<div class="alert alert-danger text-center">Error adding  learner! Please try again later.</div>');
            }   
            else
               $('.alert-lmsgw').html('<div class="alert alert-danger">' + msg + '</div>');
        }
    });
    
});



})  ;//End of Document.ready
 