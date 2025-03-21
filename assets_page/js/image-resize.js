/*
-------------------------------
-------HANDLE FILE UPLOAD------
-------------------------------
*/

var x=document.getElementsByTagName("input");
for (var i=0;i<x.length;i++) {
    if (x[i].type == "file" && x[i].id.length > 0) {
        //console.log(x[i].id);
        var input = document.getElementById(x[i].id);
        //console.log(input);
        input.addEventListener('change', handleFiles);
        //document.write(x[i].value);
    }
}

function handleFiles(e) {
    //var input = document.getElementById(elementId);
    //console.log(elementId);
    //console.log(input.value);
    //console.log(e.target.files[0]);
    //console.log(e);
    if(e.target.files[0].size > 1048576){
        var img = new Image;
        img.src = URL.createObjectURL(e.target.files[0]);
        img.userfilename = e.target.files[0].name;
        img.targetElementId = e.target.dataset.refid;
        img.onload = function() {
            var base64String = resizeImg(img, Math.round(img.width / 6), Math.round(img.height / 6), 0); //HERE IS WHERE THE FUNCTION RESIZE IS CALLED!!!!
            //alert(base64String);
            //document.getElementsByName(e.target.id).value=base64String;
            //document.getElementById(e.target.id).value=base64String;
            //return base64String;
        }
    }
    else{
        let file = e.target.files[0];
        //console.log(file);
        let list = new DataTransfer();
        list.items.add(file);
        //console.log(list);
        let fileInput = document.getElementById(e.target.dataset.refid);
        //console.log(fileInput.files);
        fileInput.files = list.files;
    }
}



/*
-------------------------------
-------RESIZING FUNCTION-------
-------------------------------
*/


function resizeImg(img, maxWidth, maxHeight, degrees) {
    //console.log(img);
    var imgWidth = img.width,
        imgHeight = img.height;
    //console.log("Original Name :" +img.userfilename);
    //console.log("Target ElementId :" +img.targetElementId);
    //console.log("Original Width :" +imgWidth);
    //console.log("Original Height :" +imgHeight);

    //console.log("Maximum Width :" +maxWidth);
    //console.log("Maximum Height :" +maxHeight);

    var ratio = 1,
        ratio1 = 1,
        ratio2 = 1;
    ratio1 = maxWidth / imgWidth;
    ratio2 = maxHeight / imgHeight;

    //console.log("Ratio 1 :" +ratio1);
    //console.log("Ratio 2 :" +ratio2);

    // Use the smallest ratio that the image best fit into the maxWidth x maxHeight box.
    if (ratio1 < ratio2) {
        ratio = ratio1;
    } else {
        ratio = ratio2;
    }
    var canvas = document.createElement("canvas");
    var canvasContext = canvas.getContext("2d");
    var canvasCopy = document.createElement("canvas");
    var copyContext = canvasCopy.getContext("2d");
    var canvasCopy2 = document.createElement("canvas");
    var copyContext2 = canvasCopy2.getContext("2d");
    canvasCopy.width = imgWidth;
    canvasCopy.height = imgHeight;
    copyContext.drawImage(img, 0, 0);

    // init
    canvasCopy2.width = imgWidth;
    canvasCopy2.height = imgHeight;
    copyContext2.drawImage(canvasCopy, 0, 0, canvasCopy.width, canvasCopy.height, 0, 0, canvasCopy2.width, canvasCopy2.height);


    var rounds = 1;
    var roundRatio = ratio * rounds;
    for (var i = 1; i <= rounds; i++) {


        // tmp
        canvasCopy.width = imgWidth * roundRatio / i;
        canvasCopy.height = imgHeight * roundRatio / i;

        copyContext.drawImage(canvasCopy2, 0, 0, canvasCopy2.width, canvasCopy2.height, 0, 0, canvasCopy.width, canvasCopy.height);

        // copy back
        canvasCopy2.width = imgWidth * roundRatio / i;
        canvasCopy2.height = imgHeight * roundRatio / i;
        copyContext2.drawImage(canvasCopy, 0, 0, canvasCopy.width, canvasCopy.height, 0, 0, canvasCopy2.width, canvasCopy2.height);

    } // end for

    canvas.width = imgWidth * roundRatio / rounds;
    canvas.height = imgHeight * roundRatio / rounds;
    canvasContext.drawImage(canvasCopy2, 0, 0, canvasCopy2.width, canvasCopy2.height, 0, 0, canvas.width, canvas.height);


    if (degrees == 90 || degrees == 270) {
        canvas.width = canvasCopy2.height;
        canvas.height = canvasCopy2.width;
    } else {
        canvas.width = canvasCopy2.width;
        canvas.height = canvasCopy2.height;
    }

    canvasContext.clearRect(0, 0, canvas.width, canvas.height);
    if (degrees == 90 || degrees == 270) {
        canvasContext.translate(canvasCopy2.height / 2, canvasCopy2.width / 2);
    } else {
        canvasContext.translate(canvasCopy2.width / 2, canvasCopy2.height / 2);
    }
    canvasContext.rotate(degrees * Math.PI / 180);
    canvasContext.drawImage(canvasCopy2, -canvasCopy2.width / 2, -canvasCopy2.height / 2);


    //var dataURL = canvas.toDataURL("image/jpg");
    canvas.toBlob((blob) => {
        const newImg = document.createElement("img");
        const url = URL.createObjectURL(blob);

        newImg.onload = () => {
            // no longer need to read the blob so it's revoked
            URL.revokeObjectURL(url);
        };

        let file = new File([blob], img.userfilename);
        //console.log(file);
        let list = new DataTransfer();
        list.items.add(file);
        //console.log(list);
        let fileInput = document.getElementById(img.targetElementId);
        //console.log(fileInput.files);
        fileInput.files = list.files;
        //console.log(fileInput.files);
        //console.log(url);
        //newImg.src = url;
        //document.body.appendChild(newImg);
    });

    //console.log(dataURL);
    //var file = canvas.mozGetAsFile("foo.png");
    //return dataURL;
}
