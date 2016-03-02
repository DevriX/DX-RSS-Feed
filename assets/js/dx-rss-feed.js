jQuery(function( $ ){

	$( 'li.feed-item' ).first().addClass( 'active' );
	setInterval(function(){
   		  var active = $(".active").removeClass('active');
          if ( active.next() && active.next().length ){
                active.next().addClass( 'active' );
		    } else {
		      active.siblings(":first").addClass('active');
		    }
	},10000);

});