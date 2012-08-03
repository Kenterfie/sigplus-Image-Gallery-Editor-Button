var path = [""];
var file = "";
var xmlHttpObject, xmlHttpObject2;
var imageLabel = "";
var imageDesc = "";

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
	{'form': 'thumb_count', 'config': 'maxcount', 'format': null},
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
	window.parent.SqueezeBox.close();
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
            //console.log(translateParam(param[1]));
			if(!translateParam(param[1])) {
				el.getParent("li").setStyle('display', 'none');
			}
		}
	});
}

function openConfig() {
	xmlHttpObject = new Request({
		method: 'get',
        url: joomla_base + '/administrator/index.php?option=com_sigpluseditorbutton&task=config&format=raw',
		onSuccess: function() {
			$('formHolder').innerHTML = xmlHttpObject.response.text;
			//buildParameters();
			hideParameters();
			$('pasteButton').setStyle('display', 'block');
		}
	}).send();
}

function openCaption() {
	xmlHttpObject = new Request({
		method: 'get',
        url: joomla_base+'/administrator/index.php?option=com_sigpluseditorbutton&task=caption&path=' + encodeURIComponent(path.join('/')) + '&file=' + encodeURIComponent(extractFilename(file)) + '&format=raw',
		onSuccess: function() {
			$('formHolder').innerHTML = xmlHttpObject.response.text;
		}
	}).send();
}

function extractFilename(f) {
	var parts = file.split('/');
	return parts[parts.length - 1];
}

function saveCaption() {
	browseFolder(path);
	xmlHttpObject = new Request({
		method: 'get',
        url: joomla_base + '/administrator/index.php?option=com_sigpluseditorbutton&task=setcaption&path=' + encodeURIComponent(path.join('/')) + '&file=' + encodeURIComponent(extractFilename(file)) + '&label=' + encodeURIComponent($('imageLabel').value) + '&desc=' + encodeURIComponent($('imageDesc').value) + '&format=raw',
		onSuccess: function() {
			// show return message
			$('messageHolder').innerHTML = xmlHttpObject.response.text;
		}
	}).send();
}

function setFile(f) {
	var text = '';
	if(path.length > 1)
		text = path.join('/').substring(1) + '/' + f;
	else
		text = f;
		
	file = text;
}

function back() {
	browseFolder(path);
}

function pasteTag() {
	var params = buildParameters();
	insertEditor('{gallery' + (params.length>0?' ' + params:'') + '}' + file + '{/gallery}');
	closeWindow();
}

function upload() {
    xmlHttpObject2 = new Request({
		method: 'get',
        url: joomla_base + '/administrator/index.php?option=com_sigpluseditorbutton&task=upload&format=raw',
		onSuccess: function() {
			$('formHolder').innerHTML = xmlHttpObject2.response.text;
            $j('.dropzone').filedrop({
                fallback_id: 'upload_button',    // an identifier of a standard file input element
                url: 'upload.php',              // upload handler, handles each file separately
                paramname: 'userfile',          // POST parameter name used on serverside to reference file
                data: {
                    param1: 'value1',           // send POST variables
                    param2: function(){
                        return 0; //calculated_data; // calculate data at time of upload
                    }
                },
                error: function(err, file) {
                    console.log(error);
                    switch(err) {
                        case 'BrowserNotSupported':
                            alert('browser does not support html5 drag and drop')
                            break;
                        case 'TooManyFiles':
                            // user uploaded more than 'maxfiles'
                            break;
                        case 'FileTooLarge':
                            // program encountered a file whose size is greater than 'maxfilesize'
                            // FileTooLarge also has access to the file which was too large
                            // use file.name to reference the filename of the culprit file
                            break;
                        default:
                            break;
                    }
                },
                maxfiles: 25,
                maxfilesize: 20,    // max file size in MBs
                dragOver: function() {
                    // user dragging files over #dropzone
                    $j('.dropzone').css('background-color', 'blue');
                },
                dragLeave: function() {
                    // user dragging files out of #dropzone
                },
                docOver: function() {
                    // user dragging files anywhere inside the browser document window
                    $j('.dropzone').css('background-color', 'blue');
                },
                docLeave: function() {
                    // user dragging files out of the browser document window
                },
                drop: function() {
                    // user drops file
                    console.log("drop");
                },
                uploadStarted: function(i, file, len){
                    // a file began uploading
                    // i = index => 0, 1, 2, 3, 4 etc
                    // file is the actual file of the index
                    // len = total files user dropped
                    console.log("upload start");
                },
                uploadFinished: function(i, file, response, time) {
                    // response is the data you got back from server in JSON format.
                    console.log("uploaded");
                },
                progressUpdated: function(i, file, progress) {
                    // this function is used for large files and updates intermittently
                    // progress is the integer value of file being uploaded percentage to completion
                    console.log("up " + i + " " + file + " " + progress);
                },
                speedUpdated: function(i, file, speed) {
                    // speed in kb/s
                    console.log("speed " + i + " " + file + " " + speed);
                },
                rename: function(name) {
                    // name in string format
                    // must return alternate name as string
                },
                beforeEach: function(file) {
                    // file is a file object
                    // return false to cancel upload
                },
                afterAll: function() {
                    // runs after all files have been uploaded or otherwise dealt with
                }
            });
		}
	}).send();
}

function loadFiles(p)
{
	xmlHttpObject2 = new Request({
		method: 'get',
        url: joomla_base + '/administrator/index.php?option=com_sigpluseditorbutton&task=browse&path=' + encodeURIComponent(p) + '&format=raw',
		onSuccess: function() {
			$('formHolder').innerHTML = xmlHttpObject2.response.text;
		}
	}).send();
}