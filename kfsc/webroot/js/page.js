$page = {
	data: {
		base: null,
		name: null
	},
	file: false,
	selected: null
};

$.starts.push(function(){
	$('#pageForms').hide();
	
	$('#PagesFilesForm').submit(function(e){
		e.preventDefault();
		
		$page.data.base = $('#PagesBase',this).val();
		
		$.post(
			$(this).attr('action'),
			$(this).serializeArray(),
			function (data) {
				$('#dirs').empty();
				$('#files').empty();
				
				for (i in data.dirs) {
					$('<li />').html('<a href="#">'+data.dirs[i]+'/</a>').appendTo('#dirs');
				}
				for (i in data.files) {
					$('<li />').html('<a href="#">'+data.files[i]+'</a>').appendTo('#files');
				}
			},
			'json'
		);
	});
	
	$('#createDir').click(function(){
		$('<li />').html('<a href="#">新しいディクトリ</a>').appendTo('#dirs');
	});
	$('#createFile').click(function(){
		$('<li />').html('<a href="#">新しいページ</a>').appendTo('#files');
	});
	
	$('#files a').live('click',function(){
		$page.data.name = $(this).text();
		$page.file = true;
		$page.selected = $(this);
		
		$('#PagesSource').show();
		$('#PagesNewName').val($page.data.name);
		$('#pageForms').show();
		
		$.post(
			'/admin/pages/source',
			$page.data,
			function(data) {
				$('#PagesSource').show().text(data.source);
			},
			'json'
		);
    
	});
	$('#dirs a').live('click',function(){
		$page.data.name = $(this).text();
		$page.file = false;
		$page.selected = $(this);
		
		$('#PagesSource').hide();
		$('#PagesNewName').val($page.data.name);
		$('#pageForms').show();
	});
	
	$('#PagesEditForm').submit(function(e){
		e.preventDefault();
		
		$('#PagesBase',this).val($page.data.base);
		$('#PagesName',this).val($page.data.name);
		
		$.post(
			$(this).attr('action') + ($page.file ? '/1' : ''),
			$(this).serializeArray(),
			function() {
      	$page.data.name = $('#PagesNewName').val();
				$page.selected.text($page.data.name);
				$('<div />').addClass('message').text('更新しました')
				.prependTo('#PagesEditForm').delay(1000).fadeOut(500,function(){
					$(this).remove();
				});
			}
		);
	});
	$('#PagesDeleteForm').submit(function(e){
		e.preventDefault();
		
		$('#PagesBase',this).val($page.data.base);
		$('#PagesName',this).val($page.data.name);
		
		$.post(
			$(this).attr('action') + ($page.file ? '/1' : ''),
			$(this).serializeArray(),
			function() {
				$page.selected.remove();
				$('#pageForms').hide();
			}
		);
	})
});