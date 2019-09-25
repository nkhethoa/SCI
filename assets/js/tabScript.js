$(document).ready(function() {
	//this will only work on mobile mode
	$(document).on('click', 'div.mainTab-tab-menu>div.list-group-mobile>a', function(e){
        e.preventDefault();
        $(this).siblings('a.active').removeClass("active");
        $(this).addClass("active");
        var index = $(this).index();
        $("div.mainTab-tab>div.mainTab-tab-content").removeClass("active");
        $("div.mainTab-tab>div.mainTab-tab-content").eq(index).addClass("active");
    });
    //this is the script for the mainTabs on the left side
    $(document).on('click', 'div.mainTab-tab-menu>div.list-group>a', function(e){
        e.preventDefault();
        $(this).siblings('a.active').removeClass("active");
        $(this).addClass("active");
        var index = $(this).index();
        $("div.mainTab-tab>div.mainTab-tab-content").removeClass("active");
        $("div.mainTab-tab>div.mainTab-tab-content").eq(index).addClass("active");
    });
//This works with the accordion which is inside academy discussion
$(document).on('click', '.panel-heading .clickable', function(e){
    var $this = $(this);
	if(!$this.hasClass('panel-collapsed')) {
		$this.parents('.panel').find('.panel-body').slideUp();
		$this.addClass('panel-collapsed');
		//$this.find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
		$this.find('i').removeClass('glyphicon-plus-sign').addClass('glyphicon-minus-sign');
	} else {
		$this.parents('.panel').find('.panel-body').slideDown();
		$this.removeClass('panel-collapsed');
		$('#accordion').collapse({ parent: true, toggle: true }); 
		//$this.find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
		$this.find('i').removeClass('glyphicon-minus-sign').addClass('glyphicon-plus-sign');
	}
})

});
