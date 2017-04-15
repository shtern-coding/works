$(document).ready(function(){
	$('.tag-btn').click(function(){
		var status = $('.tags-all').css('display');
		if (status == 'none') {
			$('.tags-all').show();
		} else {
			$('.tags-all').hide();
		}
	});
});
