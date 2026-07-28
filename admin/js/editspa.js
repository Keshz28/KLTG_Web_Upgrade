function removeHttp(url) {
  return url.replace(/^https?:\/\//, "");
}

/* Fill the "current image" thumbnail in an edit modal, and clear any file the
   user picked the last time it was open (a file input keeps its selection,
   which would otherwise silently re-upload it on the next save). */
function setEditImagePreview(previewId, folder, filename, fileInputId) {
  var img = document.getElementById(previewId);
  if (img) {
    var name = (filename || "").trim();
    if (name) {
      img.src = folder + name;
      img.classList.remove("d-none");
    } else {
      img.removeAttribute("src");
      img.classList.add("d-none");
    }
  }
  var fileInput = document.getElementById(fileInputId);
  if (fileInput) fileInput.value = "";
}

/* Copy a row's map-coordinates cell into the modal field. */
function setEditMapCoords(fieldId, cellId) {
  var field = document.getElementById(fieldId);
  if (!field) return;
  var cell = document.getElementById(cellId);
  field.value = cell ? cell.innerText.trim() : "";
}
function editmodalspa(id) {
  // console.log("test");
  // console.log(id);

  // var filename = document.getElementById("filename-" + id).innerText;
  var name = document.getElementById("name-" + id).innerText;
  var location = document.getElementById("location-" + id).innerText;
  var locationurl = document.getElementById("locationurl-" + id).innerText;
  var content = document.getElementById("content-" + id).innerText;
  var imagename = document.getElementById("filename-" + id).innerText;
  var hours = document.getElementById("hours-" + id).innerText;
  var phone = document.getElementById("phone-" + id).innerText;
  var order = document.getElementById("order-" + id).innerText;

  // console.log(filename);
  // console.log(name);
  // console.log(order);
  var formname = document.getElementById("namespa");
  var formlocation = document.getElementById("locationspa");
  var formlocationurl = document.getElementById("locationurlspa");
  var formcontent = document.getElementById("contentspa");
  var formorder = document.getElementById("orderspa");
  var formid = document.getElementById("spaid");
  var formimage = document.getElementById("imagenamespa");
  var formhours = document.getElementById("hoursspa");
  var formphone = document.getElementById("phonespa");
  formid.value = id;
  formlocation.value = location;
  formimage.value = imagename;
  formlocationurl.value = locationurl;
  formcontent.value = content;
  formorder.value = order;
  formhours.value = hours;
  formphone.value = phone;
  formname.value = name;
  setEditImagePreview("previewspa", "../assets/img/spa/", imagename, "fileToUploadspaedit");
  setEditMapCoords("mapcoordsspa", "mapcoords-" + id);
  $("#editmodal").modal("show");
}




/* Bootstrap 4 has no global `bootstrap` object (that is Bootstrap 5), so the old
   `new bootstrap.Toast(...)` threw a ReferenceError on every save and the admin
   never saw a success/failure toast. errors2.php calls this, so use the BS4
   jQuery plugin instead. */
function toastupdate(body) {
  var toastbody = document.getElementById("toast-body");
  if (toastbody) toastbody.innerHTML = body;
  $("#liveToast").toast("show");
}
