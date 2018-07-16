function clearCache() {
    navigator.camera.cleanup();
}

function onPhotoDataSuccess(imageData) {
	var smallImage 	= document.getElementById('smallImage');
	smallImage.style.display = 'block';
	smallImage.src 	= "data:image/jpeg;base64," + imageData;

}
function onPhotoURISuccess(imageURI) {
  var largeImage 	= document.getElementById('smallImage');
  largeImage.style.display = 'block';
  largeImage.src 	= imageURI;
}
function capturePhoto() {
    navigator.camera.getPicture(onPhotoDataSuccess, onFail, { quality: 100,
    //destinationType: destinationType.DATA_URL
    destinationType: destinationType.FILE_URI
    });
}
function getPhoto(source) {
    navigator.camera.getPicture(onPhotoURISuccess, onFail, { quality: 100,
    destinationType: destinationType.FILE_URI,
    sourceType: source });
}
function onFail(message) {
  alert('Failed because: ' + message);
}
function uploadPhoto() {
	
	var win = function (r) {
        clearCache();
        retries = 0;
        alert('Done!');
    }

    var fail = function (error) {
        if (retries == 0) {
            retries ++
            setTimeout(function() {
                onCapturePhoto(fileURI)
            }, 1000)
        } else {
            retries = 0;
            clearCache();
            alert('Ups. Something wrong happens!');
        }
		$.unblockUI();
    }
	var imageURI = document.getElementById('smallImage').getAttribute("src");
	if (!imageURI) {
		alert('Please select an image first.');
		return;
	}
	var options = new FileUploadOptions();
	options.fileKey = "file";
	options.fileName = imageURI.substr(imageURI.lastIndexOf('/')+1);
	options.mimeType = "image/jpeg";
	options.params = {"direction":"save_photo","nmrek":"Takur"};

	var ft = new FileTransfer();
	ft.upload(imageURI, encodeURI("http://sempoa.biz/discoin_api/profile/save_photo.php"), win, fail, options);
}

