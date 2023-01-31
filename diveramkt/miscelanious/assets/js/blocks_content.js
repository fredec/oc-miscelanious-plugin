$(document).on('click','figure[data-block-content]',function(e){
	e.preventDefault();
	requestAjax(this);
});

// <figure class="fr-draggable" data-block-content="true" data-inspector-css-class="hero" data-name="Bloco1" data-snippet="bloco1" data-ui-block="true" draggable="true" tabindex="0">&nbsp;</figure>

var label_default_blockcontent='Selecionar Bloco';

function requestAjax(target){
	$(target)
	.addClass('loading')
	.addClass('fr-draggable')
	.attr({
		'data-inspector-css-class': 'hero',
		'data-name': 'Loading...',
		'data-ui-block': 'true',
		'draggable': 'true',
		'tabindex': '0'
	})
	.html('&nbsp;');
	$(target).request('onGetBlocksContent', {
		// data: {
		// 	codes: snippetCodes
		// }
		loading: $.oc.stripeLoadIndicator,
	}).done(function(data) {
		$(target)
		.removeAttr('data-name')
		.removeClass('loading');

		if(!data.count){
			alert('Nenhum bloco encontrado ou cadastrado!');
			$(target).attr('data-name',label_default_blockcontent);
			return;
		}

		content='';
		content+='<div class="popover-head">';
		content+='<h3>Blocos de conteúdo</h3>';
		content+='<button type="button" class="close" data-dismiss="popover">&times;</button>';
		content+='</div>';
		// content+='<div class="popover-body">';
		// content+='<select>';
		// content+='<option>Teste</option>';
		// content+='</select>';
		// content+='</div>';

		content+='<form autocomplete="off" onsubmit="return false">';
		content+='<div data-surface-container="">';
		content+='<div data-disposable="">';
		content+='<table class="inspector-fields">';
		content+='<tbody>';
		content+='<tr data-property="blocks" data-property-path="blocks" class="property" data-group-level="0">';
		content+='<th>';
		content+='<div style="margin-left: 0px;">';
		content+='<div>';
		content+='<span class="title-element" title="Blocos">Blocos</span>';
		// content+='<span title="" class="info wn-icon-info with-tooltip" data-original-title="The company to show the data">';
		// content+='</span>';
		content+='</div>';
		content+='</div>';
		content+='</th>';
		content+='<td class="dropdown">';
		content+='<select class="custom-select select2 select_block_content_active" tabindex="-1" aria-hidden="true">';
		content+='<option value="">'+label_default_blockcontent+'</option>';
		for (block in data.blocks) {
			content+='<option value="'+block+'">'+data.blocks[block]+'</option>';
		}
		content+='</select>';
		content+='</td>';
		content+='</tr>';
		content+='</tbody>';
		content+='</table>';
		content+='</div>';
		content+='</div>';
		content+='</form>';

		$(target).ocPopover({
			content: content,
			placement: 'bottom',
			width: '400px',
		});
		$("body").append('<div class="popover-overlay in"></div>');
		$(target).on('hide.oc.popover', function(e, popover) {
			$(".popover-overlay").remove();
		})

		if($(target).attr('data-snippet') && $('.select_block_content_active').find('option[value="'+$(target).attr('data-snippet')+'"]').length) $('.select_block_content_active').val($(target).attr('data-snippet'));
		else $(target).attr('data-name',label_default_blockcontent);
		$('.select_block_content_active').change(function(){
			$(target).attr("data-snippet",$(this).val());
			$(target).attr("data-name",$(this).find("option:selected").text());
		});

	});

}