function mfn_field_sliderbar(field, rwd = false) {
	let value = '';
	let classes = ['form-group', 'range-slider', 'pseudo'];
	let classes_input = ['mfn-slider-hidden-input'];
	let placeholder = '';
	let clean_value = '';
	let data_attr = '';

	let min = _.has(field, 'param') && _.has(field['param'], 'min') ? field['param']['min'] : 1;
	let max = _.has(field, 'param') && _.has(field['param'], 'max') ? field['param']['max'] : 100;
	let step = _.has(field, 'param') && _.has(field['param'], 'step') ? field['param']['step'] : 1;
	let unit = _.has(field, 'param') && _.has(field['param'], 'unit') ? field['param']['unit'] : '';

	if( _.has(field, 'after') ){
		classes.push('has-addons has-addons-append');
	}

	if( _.has(field, 'field_class') ){
		classes_input.push(field.field_class);
	}

	if( _.has(field, 'key') ) {
		data_attr = `data-key="${field.key}"`;
	}

	if( _.has(field, 'point_key') ) {
		data_attr = `data-pointobj="${field.point_key}"`;
	}

	if( _.has(edited_item['attr'], field.old_id) || _.has(edited_item['attr'], field.id) ) {
		// deprecated id with style
		if( _.has(edited_item['attr'], field.old_id) ){
			if( _.has(field, 'key') && typeof edited_item['attr'][field.old_id] == 'object' && _.has(edited_item['attr'][field.old_id], field.key) ){
				value = edited_item['attr'][field.old_id][field.key];
			}else if( _.has(edited_item['attr'], field.old_id) && typeof edited_item['attr'][field.old_id] == 'string' && !_.isEmpty(edited_item['attr'][field.old_id]) ){
				value = edited_item['attr'][field.old_id];
			}
		}else if(_.has(edited_item['attr'], field.id)){

			if( _.has(field, 'key') && _.has(edited_item['attr'][field.id], field.key) ) {

				value = edited_item['attr'][field.id][field.key];

			}else if( _.has(edited_item['attr'][field.id], 'val') ){
				
				if( typeof edited_item['attr'][field.id]['val'] == 'object' && _.has(edited_item['attr'][field.id]['val'], field.key) ){
					value = edited_item['attr'][field.id]['val'][field.key];
				}else if( typeof edited_item['attr'][field.id]['val'] == 'string' && !_.has(edited_item['attr'][field.id]['val'], field.key) ){
					value = edited_item['attr'][field.id]['val'];
				}
				
			}else{
				value = edited_item['attr'][field.id];
			}

		}
	}else if( (edited_item.jsclass == 'pageoption' || edited_item.jsclass == 'themeoption') && _.has(edited_item, field.id) && !_.isEmpty(edited_item[field.id]) ){
		// themeoption
		//value = edited_item[field.id];
		if( _.has(edited_item[field.id], 'val') ){
			value = edited_item[field.id]['val']
		}else{
			value = edited_item[field.id];
		}
	}

	

	// typography fix after _css update mess
	if( _.isEmpty(value) && _.has(field, 'keyrwd') && rwd && rwd != 'desktop' && _.has(edited_item, 'attr') && _.has(edited_item['attr'], field.id.replace('_'+rwd, '')) && _.has(edited_item['attr'][field.id.replace('_'+rwd, '')], field.key+'_'+rwd) ) {
		value = edited_item['attr'][field.id.replace('_'+rwd, '')][field.key+'_'+rwd];
	}


	clean_value = value;

	if( _.has(field, 'on_change') ){
		classes_input.push('field-to-object'); // object updater only
	}else{
		classes_input.push('mfn-field-value'); // all on change actions
	}

	if( _.has(field, 'units') ) {

		if( unit == '' ) unit = 'px';

		if( !_.isEmpty(value) ) {

			//clean_value = value.replace('px', '').replace('rem', '').replace('em', '').replace('%', '').replace('vw', '').replace('vh', '');

			if( value.includes('rem') ) {
				unit = 'rem';
			}else if( value.includes('em') ) {
				unit = 'em';
			}else if( value.includes('px') ) {
				unit = 'px';
			}else if( value.includes('vw') ) {
				unit = 'vw';
			}else if( value.includes('vh') ) {
				unit = 'vh';
			}else if( value.includes('%') ) {
				unit = '%';
			}else if( value.includes('ms') ) {
				unit = 'ms';
			}else if( value.includes('s') ) {
				unit = 's';
			}else if( value.includes('deg') ) {
				unit = 'deg';
			}

		}

		if( _.has(field.units, unit) ) {
			min = field.units[unit]['min'];
			max = field.units[unit]['max'];
			step = field.units[unit]['step'];
		}

	}

	if( _.isEmpty(value) && _.has(field, 'std') ){
		value = field.std;
	}

	if( _.isEmpty(value) && _.has(field, 'default_value') ){
		value = field.default_value;
	}

	if( _.has(field, 'value') ){
		value = field.value;
	}

	if( !_.isEmpty(value) ) {
		clean_value = value.replace('px', '').replace('rem', '').replace('em', '').replace('%', '').replace('vw', '').replace('vh', '').replace('deg', '').replace('ms', '').replace('s', '');
	}

	let html = `<div class="form-content"><div class="${classes.join(' ')}">
		${ _.has(field, 'units') ? `<ul class="mfn-slider-unit">
			${ _.map(field.units, function(un, u) {
				return `<li class="${unit == u ? 'active' : ''}" data-min="${un.min}" data-max="${un.max}" data-step="${un.step}"><a href="#">${u}</a></li>`;
			}).join('')}
		</ul>` : '' }

		<input type="hidden" ${data_attr} class="${classes_input.join(' ')}" name="${field.id}" value="${value ? value : ''}" autocomplete="off">

		<div class="form-control">
			<input ${data_attr} class="mfn-form-control mfn-form-input mfn-sliderbar-value" type="number" step="${step}" data-step="${step}" data-unit="${unit}" min="${min}" max="${max}" value="${clean_value ? clean_value : ''}" placeholder="${placeholder}" autocomplete="off"/>
		</div>

		${ _.has(field, 'after') ? `<div class="form-addon-append"><span class="label">${field.after}</span></div>` : '' }

		<div class="sliderbar"></div>

	</div></div>`;
	return html;

}