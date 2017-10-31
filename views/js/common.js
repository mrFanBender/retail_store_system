$(document).ready(function(){
	//функция для работы табов
	$(".nav-tabs > .nav-item > a.nav-link").click(function(e){
		e.preventDefault();
		$(this).tab('show');
	});
	$(".nav-pills > a.nav-item.nav-link").each(function(){
		if($(location).attr('href').match($(this).attr('href')) ||
			$(location).attr('pathname').match($(this).attr('href'))){
			target = $(this).attr('target_tab');
			$(target).tab('show');
			$(this).addClass('active');
		}
	});

	//временная функция обработки ajax
	$('[name=button]').bind('click', function(){
		var params = $(this).parent('form').serialize();
		var url = $(this).parent('form').attr('action');
		ajax_form(params, url);
	});

	function ajax_form(params, url){
		params = params+'&json=true';
		$.ajax({
			type: 'post',
			url: url,
			json: true,
			data: params,
			success: function(data){
				var j_data = jQuery.parseJSON(data);
				if(j_data.errors){
					$(j_data.errors).each(function(ind, error){
						if($('input[name='+error.target+']').length>0){
							$('input[name='+error.target+']').after('<label class='+error.type+'>'+error.message+'</label>');
						}else{
							alert(error.message);
						}
					});
				}else if(j_data.data){
					alert('есть данные');
				}
				if(j_data.statusMessages){
					$(j_data.statusMessages).each(function(ind, statusMessage){
						if($('input[name='+statusMessage.target+']').length>0){
							$('input[name='+statusMessage.target+']').after('<label class='+statusMessage.type+'>'+statusMessage.message+'</label>');
						}else{
							alert(statusMessage.message);
						}
					});
				}
				if(j_data.redirect){
					alert('типа делаем редирект');
					location.replace(j_data.redirect);
				}

				/*if(j_data.statusMessages.length>0){
					$(j_data.statusMessages).each(function(ind, statusMessage){
						if($('input[name='+statusMessage.target+']')){
							$('input[name='+statusMessage.target+']').after('<label class='+statusMessage.type+'>'+statusMessage.message+'</label>')
						}else{
							//иначе вызываем диалоговое окно и показываем сообщение
							alert(statusMessage.message);
						}
					});
				}else{
					alert(j_data.data);
				}*/
			},
			error: function(xhr, str){
				alert('Произошла ошибка:'+xhr.responseCode);
			} 
		})
		
		//var form_inputs = $(obj).serialize();
		
		/*$.ajax({

		});*/

	};
});


