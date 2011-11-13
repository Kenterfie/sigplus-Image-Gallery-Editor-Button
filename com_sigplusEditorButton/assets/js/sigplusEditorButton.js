var path = [""];
var file = "";
var xmlHttpObject;

/*
	sigplus parameter translation table
	form: form id
	config: gallery tag
	format: set the output format
*/
var sigplus_ptt = [
	{'form': 'layout', 'config': 'layout', 'format': null},
	{'form': 'thumb_width', 'config': 'width', 'format': null},
	{'form': 'thumb_height', 'config': 'height', 'format': null},
	{'form': 'cols', 'config': 'cols', 'format': null},
	{'form': 'rows', 'config': 'rows', 'format': null},
	{'form': 'thumb_crop', 'config': 'crop', 'format': null},
	{'form': 'slider_orientation', 'config': 'orientation', 'format': null},
	{'form': 'slider_navigation', 'config': 'navigation', 'format': null},
	{'form': 'slider_buttons', 'config': 'buttons', 'format': null},
	{'form': 'slider_links', 'config': 'links', 'format': null},
	{'form': 'slider_overlay', 'config': 'overlay', 'format': null},
];

// build gallery paramter string (only modified from default)
function buildParameters() {
	var parameters = "";
	
	$$('input, select, textarea', true).each(function(el) {
		if(!el.name || el.disabled || el.type == 'submit' || el.type == 'reset' || el == 'file') return;
		
		var value = (el.tagName.toLowerCase() == 'select') ? $(el).getElements('option').filter(function(option){
			return option.selected;
		}).map(function(opt) { return opt.value }) :
		((el.type == 'radio' || el.type == 'checkbox') && !el.checked) ? 'undefined' : el.value;
		
		if(value != 'undefined') {
			var param = el.name.match(/params\[([a-z_]+)\]/i);
			var sigplus_param = param[1];
			//console.log(sigplus_param);
			if(param.length > 0 && typeof sigplus_parameter[sigplus_param] != 'undefined') {
				var default_value = (sigplus_parameter[sigplus_param] == null?'':sigplus_parameter[sigplus_param]);
				if(default_value == value) {
					console.log("default" + sigplus_param + " has " + value);
					
				} else {
					console.log("changed " + sigplus_param + " from " + default_value + " to " + value);
					var p = translateParam(sigplus_param);
					if(p) { // translation table record found
						console.log(p.config + '=' + value);
						parameters += p.config + '=' + value + ' ';
					}
				}
			}
		}
	});
	
	return parameters;
}
		
function insertEditor(text) {
	var result = text;
	window.parent.insertEditorClickCallback( result );
}

function closeWindow() {
	window.parent.document.getElementById('sbox-window').close();
}
	
function browseFolder(p) {
	if(p == null && path.length > 1)
		path.pop();
	else
		path.push(p);
	loadFiles(path.join('/'));
}

function translateParam(param) {
	for(var p in sigplus_ptt) {
		if(sigplus_ptt[p].form == param)
			return sigplus_ptt[p];
	}
	return false;
}

// hide parameter which are not customizable
function hideParameters() {
	$$('input, select, textarea', true).each(function(el) {
		var param = el.name.match(/params\[([a-z_]+)\]/i);
		if(param.length > 0) {
			if(!translateParam(param[1])) {
				el.getParent().getParent().setStyle('display', 'none');
			}
		}
	});
}

function openConfig() {
	xmlHttpObject = new XHR({
		method: 'get',
		onSuccess: function() {
			$('formHolder').innerHTML = xmlHttpObject.response.text;
			buildParameters();
			hideParameters();
		}
	}).send(joomla_base+'/administrator/index.php?option=com_sigpluseditorbutton&task=config&format=raw');
}

function setFile(f) {
	var text = '';
	if(path.length > 1)
		text = path.join('/').substring(1) + '/' + f;
	else
		text = f;
		
	file = text;
}

function pasteTag() {
	var params = buildParameters();
	insertEditor('{gallery' + (params.length>0?' ' + params:'') + '}' + file + '{/gallery}');
	closeWindow();
}

function loadFiles(p)
{
	xmlHttpObject = new XHR({
		method: 'get',
		onSuccess: function() {
			$('formHolder').innerHTML = xmlHttpObject.response.text;
		}
	}).send(joomla_base+'/administrator/index.php?option=com_sigpluseditorbutton&task=browse&path=' + p + '&format=raw');
}