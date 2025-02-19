(function () {
    var input = document.getElementById("images"), 
    formdata = false;

    function showUploadedItem (source) {
        var list = document.getElementById("image-list"),
        li   = document.createElement("li"),
        img  = document.createElement("span");
        img.id = "image-list-value";
        img.innerHTML = source;
        li.appendChild(img);
        list.appendChild(li);
    }   

    if (window.FormData) {
        formdata = new FormData();
        document.getElementById("btn").style.display = "none";
    }
	
    input.addEventListener("change", function (evt) {
        document.getElementById("response").innerHTML = "Cargando . . ."
        var i = 0, len = this.files.length, img, reader, file;
	
        for ( ; i < len; i++ ) {
            file = this.files[i];
	
            if (!file.type.match('doc.*')) {
                if ( window.FileReader ) {
                    reader = new FileReader();
                    reader.onloadend = function (e) { 
//                        showUploadedItem(e.target.result, file.fileName);
                        showUploadedItem(file.name);
                    };
                    reader.readAsDataURL(file);
                }
                if (formdata) {
                    formdata.append("images[]", file);
                }
            }	
        }
	
        if (formdata) {
            $.ajax({
                url: "../../lib/upload-zonas/upload.php",
                type: "POST",
                data: formdata,
                processData: false,
                contentType: false,
                success: function (res) {
                    document.getElementById("response").innerHTML = res; 
                }
            });
        }
    }, false);
}());
