function mfn_field_hotspot(field) {
	let html = '';
	let value = [];

	if( _.has(edited_item['attr'], 'hotspots') ){
		value = edited_item['attr']['hotspots'];
	}


	if( value.length ){
		_.map( value, (obj, i) => html += mfn_field_hotspot_render(obj, i) ).join('');
	}
	

    html += '<a href="#" class="hotspot_add_new mfn-btn">Add new</a>';
	
	return html;
}


function mfn_field_hotspot_render(obj, i) {

	let html = '';

	let used_fields = [

		{
			'id': 'type',
			'attr_id': 'hotspots'+'_type'+obj.hash,
			'on_change': 'object',
			'type': 'switch',
			're_render': true,
			'title': 'Type',
			'std': '',
			'value': _.has(obj, 'type') ? obj['type'] : '',
			'options': {
				'': 'Default',
				'icon': 'Icon',
				'text': 'Text',
			}
		},

		{
			'id': 'icon',
			'condition': {
				id: 'hotspots'+'_type'+obj.hash,
				opt: "is",
				val: "icon"
			},
			'on_change': 'object',
			'type': 'icon',
			'title': 'Icon',
			'std': 'icon-plus',
			'value': _.has(obj, 'icon') ? obj['icon'] : 'icon-plus',
		},

		{
			'id': 'text',
			'condition': {
				id: 'hotspots'+'_type'+obj.hash,
				opt: "is",
				val: "text"
			},
			'on_change': 'object',
			'type': 'text',
			'title': 'Text',
			'std': 'Point text',
			'value': _.has(obj, 'text') ? obj['text'] : 'Point text',
		},


		{
			'id': 'hotspots_yaxis_'+obj.hash,
			'attr_id': 'hotspots_yaxis_'+obj.hash,
			'on_change': 'object',
			're_render': true,
			'class': 'hotspot-y-axis-switcher',
			'type': 'switch',
			'title': 'Y axis',
			'std': '',
			'value': _.has(obj, 'hotspots_yaxis_'+obj.hash) ? obj['hotspots_yaxis_'+obj.hash] : '',
			'options': {
				'': 'Top',
				'bottom': 'Bottom',
			}
		},

		{
			'id': 'css_y_'+obj.hash+'_top',
			'css_path': '.mcb-section .mcb-wrap .mcb-item-mfnuidelement .hotspot-point-'+obj.hash,
			'css_style': 'top',
			'condition': {
				id: 'hotspots_yaxis_'+obj.hash,
				opt: "is",
				val: ""
			},
			'responsive': true,
			'type': 'sliderbar',
			'class': 'hotspot-y-axis-field',
			'point_key': 'y',
			'param': {
				'min': '0',
				'max': '100',
				'step': '1',
				'unit': '%',
			},
			'title': 'Top',
			'hash': obj.hash,
			'value': 'find',
		},

		{
			'id': 'css_y_'+obj.hash+'_bottom',
			'css_path': '.mcb-section .mcb-wrap .mcb-item-mfnuidelement .hotspot-point-'+obj.hash,
			'css_style': 'bottom',
			'condition': {
				id: 'hotspots_yaxis_'+obj.hash,
				opt: "is",
				val: "bottom"
			},
			'responsive': true,
			'class': 'hotspot-y-axis-field',
			'type': 'sliderbar',
			'point_key': 'y',
			'param': {
				'min': '0',
				'max': '100',
				'step': '1', 
				'unit': '%',
			},
			'title': 'Bottom',
			'hash': obj.hash,
			'value': 'find',
		},

		{
			'id': 'hotspots_xaxis_'+obj.hash,
			'attr_id': 'hotspots_xaxis_'+obj.hash,
			'type': 'switch',
			'class': 'hotspot-x-axis-switcher',
			're_render': true,
			'title': 'X axis',
			'std': '',
			'value': _.has(obj, 'hotspots_xaxis_'+obj.hash) ? obj['hotspots_xaxis_'+obj.hash] : '',
			'options': {
				'': 'Left',
				'right': 'Right',
			}
		},

		{
			'id': 'css_x_'+obj.hash+'_left',
			'css_path': '.mcb-section .mcb-wrap .mcb-item-mfnuidelement .hotspot-point-'+obj.hash,
			'css_style': 'left',
			'condition': {
				id: 'hotspots_xaxis_'+obj.hash,
				opt: "is",
				val: ""
			},
			'responsive': true,
			'point_key': 'x',
			'class': 'hotspot-x-axis-field',
			'type': 'sliderbar',
			'param': {
				'min': '0',
				'max': '100',
				'step': '1',
				'unit': '%',
			},
			'title': 'Left',
			'hash': obj.hash,
			'value': 'find',
		},

		{
			'id': 'css_x_'+obj.hash+'_right',
			'css_path': '.mcb-section .mcb-wrap .mcb-item-mfnuidelement .hotspot-point-'+obj.hash,
			'css_style': 'right',
			'condition': {
				id: 'hotspots_xaxis_'+obj.hash,
				opt: "is",
				val: "right"
			},
			'responsive': true,
			'class': 'hotspot-x-axis-field',
			'point_key': 'x',
			'type': 'sliderbar',
			'param': {
				'min': '0',
				'max': '100',
				'step': '1',
				'unit': '%',
			},
			'title': 'Right',
			'hash': obj.hash,
			'value': 'find',
		},






		{
			'id': 'css_marker_'+obj.hash+'_top',
			'css_path': '.mcb-section .mcb-wrap .mcb-item-mfnuidelement .mfn-hotspot-style-line .hotspot-point-'+obj.hash+' .hotspot-marker',
			'css_style': 'top',
			'condition': {
				id: 'style',
				opt: "is",
				val: "line"
			},
			'type': 'sliderbar',
			'point_key': 'y_offset',
			'responsive': true,
			'param': {
				'min': '-1000',
				'max': '1000',
				'step': '1',
				'unit': 'px',
			},
			'std': 0,
			'title': 'Line offset top',
			'hash': obj.hash,
			'value': 'find',
		},

		
		{
			'id': 'css_marker_'+obj.hash+'_left',
			'css_path': '.mcb-section .mcb-wrap .mcb-item-mfnuidelement .mfn-hotspot-style-line .hotspot-point-'+obj.hash+' .hotspot-marker',
			'css_style': 'left',
			'condition': {
				id: 'style',
				opt: "is",
				val: "line"
			},
			'type': 'sliderbar',
			'point_key': 'x_offset',
			'responsive': true,
			'param': {
				'min': '-1000',
				'max': '1000',
				'step': '1',
				'unit': 'px',
			},
			'std': 0,
			'title': 'Line offset left',
			'hash': obj.hash,
			'value': 'find',
		},

		{
			'id': 'content_position',
			'condition': {
				id: 'style',
				opt: "isnt",
				val: "line"
			},
			'on_change': 'object',
			'type': 'switch',
			'responsive': true,
			'title': 'Content position',
			'std': 'top',
			'value': _.has(obj, 'content_position') ? obj['content_position'] : 'top',
			'options': {
				'top': 'Top',
				'left': 'Left',
				'bottom': 'Bottom',
				'right': 'Right',
			}
		},

		{
			'id': 'link',
			'attr_id': 'hotspots'+'_link'+obj.hash,
			'field_class': 'hotspots'+'_text',
			'on_change': 'object',
			'type': 'text',
			'title': 'Link',
			'dynamic_data': 'permalink',
			'value': _.has(obj, 'link') ? obj['link'] : '',
		},

		{
			'id': 'link_target',
			'condition': {
				id: 'hotspots'+'_link'+obj.hash,
				opt: "isnt",
				val: ""
			},
			'on_change': 'object',
			'type': 'select',
			'title': 'Link target',
			'value': _.has(obj, 'link_target') ? obj['link_target'] : '',
			'options': {
				'': "Default",
				'_blank': "New window"
			}
		},

		{
			'id': 'link_title',
			'condition': {
				id: 'hotspots'+'_link'+obj.hash,
				opt: "isnt",
				val: ""
			},
			'on_change': 'object',
			'type': 'text',
			'dynamic_data': 'title',
			'title': 'Link title',
			'value': _.has(obj, 'link_title') ? obj['link_title'] : '',
		},

		{
			'id': 'content',
			'condition': {
				id: 'style',
				opt: "isnt",
				val: "line"
			},
			'on_change': 'object',
			're_render': true,
			'type': 'textarea',
			'dynamic_data': 'content',
			'title': 'Content',
			'value': _.has(obj, 'content') ? obj['content'] : '',
		},

	];

	html += '<div id="'+obj.hash+'" class="mfn-hotspot-point mfn-hotspot-form-'+obj.hash+'">';
	html += '<div class="mfn-hotspot-point-tab-header mfn-hotspot-point-header-'+obj.hash+'">';
	html += `<div class="mfn-hotspot-point-header-left"><a class="mfn-option-btn mfn-option-blank mfn-tab-toggle mfn-tab-toggle" href="#"><span class="mfn-icon mfn-icon-arrow-down"></span></a>
			<h6>Point ${i+1}</h6>
			</div>
			<div class="mfn-hotspot-point-header-right">
			<a class="mfn-option-btn mfn-option-blue mfn-tab-delete" href="#"><span class="mfn-icon mfn-icon-delete"></span></a>
			</div>`;
	html += '</div>';
	html += '<div class="mfn-hotspot-form-wrapper">';
	const mfn_form_hotspot = new MfnForm( used_fields );
    html += mfn_form_hotspot.render();
    html += '</div>';
    html += '</div>';

	return html;

}