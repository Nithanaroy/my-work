<html>
<head>
<script type="text/javascript">
	var dropbox;
	window.onload = function() {
		dropbox = document.getElementById("dropbox");
		dropbox.addEventListener("dragenter", dragenter, false);
		dropbox.addEventListener("dragover", dragover, false);
		dropbox.addEventListener("drop", drop, false);
	}
	function dragenter(e) {
		e.stopPropagation();
		e.preventDefault();
	}
	function dragover(e) {
		e.stopPropagation();
		e.preventDefault();
	}

	function drop(e) {
		e.stopPropagation();
		e.preventDefault();

		var dt = e.dataTransfer;
		var files = dt.files;

		handleFiles(files);
	}

	function upload_file() {
		var selected_files = document.getElementById('input').files;
		handleFiles(selected_files);
	}

	function handleFiles(files) {
		var preview = document.getElementById("output");
		for ( var i = 0; i < files.length; i++) {
			// atleast 20% of image should be in the blob. Yet to verify 
			var file = files[i].slice(0, parseInt(files[i].size * 0.2), files[i].type);
// 			var file = files[i] //.slice(0, files[i].size, files[i].type);
			var imageType = /image.*/;

			if (!file.type.match(imageType)) {
				continue;
			}

			var img = document.createElement("img");
			img.classList.add("obj");
			img.file = file;
			preview.appendChild(img);

			var reader = new FileReader();
			reader.addEventListener("loadend", function(e) {
				   // reader.result contains the contents of blob as a typed array
				img.src = reader.result;
				});
// 			reader.onload = (function(aImg) {
// 				return function(e) {
// 					aImg.src = e.target.result;
// 				};
// 			})(img);
			reader.readAsDataURL(file);
// 			reader.readAsArrayBuffer(file);
		}
	}
	function sendFiles() {
		var imgs = document.querySelectorAll(".obj");

		for ( var i = 0; i < imgs.length; i++) {
			new FileUpload(imgs[i], imgs[i].file);
		}
	}
	function FileUpload(img, file) {
		var reader = new FileReader();
		// 		this.ctrl = createThrobber(img);
		var xhr = new XMLHttpRequest();
		this.xhr = xhr;

		var self = this;

		/* this.xhr.upload.addEventListener("progress", function(e) {
			if (e.lengthComputable) {
				var percentage = Math.round((e.loaded * 100) / e.total);
				self.ctrl.update(percentage);
			}
		}, false);

		xhr.upload.addEventListener("load", function(e) {
			self.ctrl.update(100);
			var canvas = self.ctrl.ctx.canvas;
			canvas.parentNode.removeChild(canvas);
		}, false); */

		xhr.open("POST", "upload.php", true);
		xhr.setRequestHeader("Cache-Control", "no-cache");
		xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
		xhr.setRequestHeader("X-File-Name", 'blob.jpg');
		xhr.setRequestHeader("X-File-Size", file.size);
// 		xhr.onreadystatechange = function() {
//             if (xhr.readyState == 4 && xhr.status == 200) {
// //                 alert(xhr.responseText); // handle response.
// 				document.getElementById("response").innerHTML = xhr.responseText;
//             }
//         };
        xhr.onload = function (oEvent) {
        	if (xhr.readyState == 4 && xhr.status == 200) {
//              alert(xhr.responseText); // handle response.
				document.getElementById("response").innerHTML = xhr.response;
         }
		};
// 		xhr.overrideMimeType('text/plain; charset=x-user-defined-binary');
// 		oReq.responseType = "'text/plain'";
// 		reader.onload = function(evt) {
// // 			xhr.sendAsBinary(evt.target.result);
// 			xhr.send(evt.target.result);
// 		};
// 		reader.readAsBinaryString(file);
		xhr.send(file);
	}
</script>
</head>
<body>
	
	<p>Uploaded Image</p>
	<img alt="Blob" src="blob.jpg" />
	<br />
	
	<input type="file" id="input" onchange="upload_file()" />
	<div id="dropbox"
		style="width: 300px; height: 100px; border: solid 1px #000; text-align: center">
		<p>Drop your files here</p>
	</div>
	
	<p>Preview</p>
	<div id="output"></div>
	
	<input type="button" id="upload_image_btn" value="Upload image"
		onclick="sendFiles()" />
	
	<p>Server response</p>
	<div id="response"></div>
</body>
</html>