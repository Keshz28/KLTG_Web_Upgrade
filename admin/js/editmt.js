function removeHttp(url) {
  return url.replace(/^https?:\/\//, "");
}

/* Fill the "current image" thumbnail in an edit modal, and clear any file the
   user picked the last time the modal was open (a file input keeps its
   selection, which would otherwise re-upload it on the next save). */
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

/* Copy a row's map-coordinates cell into the modal, when the page has the field. */
function setEditMapCoords(fieldId, cellId) {
  var field = document.getElementById(fieldId);
  if (!field) return;
  var cell = document.getElementById(cellId);
  field.value = cell ? cell.innerText.trim() : "";
}

function editmodalhc(id) {
  // console.log("test");
  // console.log(id);

  // var filename = document.getElementById("filename-" + id).innerText;
  var name = document.getElementById("namehc-" + id).innerText;
  var location = document.getElementById("locationhc-" + id).innerText;
  var locationurl = document.getElementById("locationurlhc-" + id).innerText;
  var content = document.getElementById("contenthc-" + id).innerText;
  var imagename = document.getElementById("filenamehc-" + id).innerText;
  var hours = document.getElementById("hourshc-" + id).innerText;
  var phone = document.getElementById("phonehc-" + id).innerText;
  // Was missing: formorder.value = order threw "order is not defined", which
  // aborted this function before .modal("show") — the Healthcare edit button
  // simply did nothing.
  var order = document.getElementById("orderhc-" + id).innerText;
  // console.log(filename);
  // console.log(name);
  // console.log(order);
  var formname = document.getElementById("namehc");
  var formlocation = document.getElementById("locationhc");
  var formlocationurl = document.getElementById("locationurlhc");
  var formcontent = document.getElementById("contenthc");
  var formorder = document.getElementById("orderhc");
  var formid = document.getElementById("mthcid");
  var formimage = document.getElementById("imagenamemthc");
  var formhours = document.getElementById("hourshc");
  var formphone = document.getElementById("phonehc");

  formid.value = id;
  formlocation.value = location;
  formimage.value = imagename;
  formlocationurl.value = locationurl;
  formcontent.value = content;
  formorder.value = order;
  formhours.value = hours;
  formphone.value = phone;
  formname.value = name;
  setEditImagePreview("previewmthc", "../assets/img/medical_tourism/hc/", imagename, "fileToUploadhcedit");
  setEditMapCoords("mapcoordsmthc", "mapcoordshc-" + id);

  $("#edithcmodal").modal("show");
}


function editmodaldtl(id) {
  // console.log("test");
  // console.log(id);

  // var filename = document.getElementById("filename-" + id).innerText;
  var name = document.getElementById("namedtl-" + id).innerText;
  var location = document.getElementById("locationdtl-" + id).innerText;
  var locationurl = document.getElementById("locationurldtl-" + id).innerText;
  var content = document.getElementById("contentdtl-" + id).innerText;
  var imagename = document.getElementById("filenamedtl-" + id).innerText;
  var hours = document.getElementById("hoursdtl-" + id).innerText;
  var phone = document.getElementById("phonedtl-" + id).innerText;
  var order = document.getElementById("orderdtl-" + id).innerText;

  // console.log(filename);
  // console.log(name);
  // console.log(order);
  var formname = document.getElementById("namedtl");
  var formlocation = document.getElementById("locationdtl");
  var formlocationurl = document.getElementById("locationurldtl");
  var formcontent = document.getElementById("contentdtl");
  var formorder = document.getElementById("orderdtl");
  var formid = document.getElementById("mtdtlid");
  var formimage = document.getElementById("imagenamemtdtl");
  var formhours = document.getElementById("hoursdtl");
  var formphone = document.getElementById("phonedtl");

  formid.value = id;
  formlocation.value = location;
  formimage.value = imagename;
  formlocationurl.value = locationurl;
  formcontent.value = content;
  formhours.value = hours;
  formphone.value = phone;
  formorder.value = order;
  formname.value = name;
  setEditImagePreview("previewmtdtl", "../assets/img/medical_tourism/dtl/", imagename, "fileToUploaddtleditmt");
  setEditMapCoords("mapcoordsmtdtl", "mapcoordsdtl-" + id);
  $("#editdtlmodal").modal("show");
}


function editmodalder(id) {
  // console.log("test");
  // console.log(id);

  // var filename = document.getElementById("filename-" + id).innerText;
  var name = document.getElementById("nameder-" + id).innerText;
  var location = document.getElementById("locationder-" + id).innerText;
  var locationurl = document.getElementById("locationurlder-" + id).innerText;
  var content = document.getElementById("contentder-" + id).innerText;
  var imagename = document.getElementById("filenameder-" + id).innerText;
  var hours = document.getElementById("hoursder-" + id).innerText;
  var phone = document.getElementById("phoneder-" + id).innerText;
  var order = document.getElementById("orderder-" + id).innerText;

  // console.log(filename);
  // console.log(name);
  // console.log(order);
  var formname = document.getElementById("nameder");
  var formlocation = document.getElementById("locationder");
  var formlocationurl = document.getElementById("locationurlder");
  var formcontent = document.getElementById("contentder");
  var formorder = document.getElementById("orderder");
  var formid = document.getElementById("mtderid");
  var formimage = document.getElementById("imagenamemtder");
  var formhours = document.getElementById("hoursder");
  var formphone = document.getElementById("phoneder");

  formid.value = id;
  formlocation.value = location;
  formimage.value = imagename;
  formlocationurl.value = locationurl;
  formcontent.value = content;
  formhours.value = hours;
  formphone.value = phone;
  formorder.value = order;
  formname.value = name;
  setEditImagePreview("previewmtder", "../assets/img/medical_tourism/der/", imagename, "fileToUploadderedit");
  setEditMapCoords("mapcoordsmtder", "mapcoordsder-" + id);
  $("#editdermodal").modal("show");
}


function editmodaloph(id) {
  // console.log("test");
  // console.log(id);

  // var filename = document.getElementById("filename-" + id).innerText;
  var name = document.getElementById("nameoph-" + id).innerText;
  var location = document.getElementById("locationoph-" + id).innerText;
  var locationurl = document.getElementById("locationurloph-" + id).innerText;
  var content = document.getElementById("contentoph-" + id).innerText;
  var imagename = document.getElementById("filenameoph-" + id).innerText;
  var hours = document.getElementById("hoursoph-" + id).innerText;
  var phone = document.getElementById("phoneoph-" + id).innerText;
  var order = document.getElementById("orderoph-" + id).innerText;

  // console.log(filename);
  // console.log(name);
  // console.log(order);
  var formname = document.getElementById("nameoph");
  var formlocation = document.getElementById("locationoph");
  var formlocationurl = document.getElementById("locationurloph");
  var formcontent = document.getElementById("contentoph");
  var formorder = document.getElementById("orderoph");
  var formid = document.getElementById("mtophid");
  var formimage = document.getElementById("imagenamemtoph");
  var formhours = document.getElementById("hoursoph");
  var formphone = document.getElementById("phoneoph");

  formid.value = id;
  formlocation.value = location;
  formimage.value = imagename;
  formlocationurl.value = locationurl;
  formcontent.value = content;
  formhours.value = hours;
  formphone.value = phone;
  formorder.value = order;
  formname.value = name;
  setEditImagePreview("previewmtoph", "../assets/img/medical_tourism/oph/", imagename, "fileToUploadophedit");
  setEditMapCoords("mapcoordsmtoph", "mapcoordsoph-" + id);
  $("#editophmodal").modal("show");
}

function editmodalps(id) {
  // console.log("test");
  // console.log(id);

  // var filename = document.getElementById("filename-" + id).innerText;
  var name = document.getElementById("nameps-" + id).innerText;
  var location = document.getElementById("locationps-" + id).innerText;
  var locationurl = document.getElementById("locationurlps-" + id).innerText;
  var content = document.getElementById("contentps-" + id).innerText;
  var imagename = document.getElementById("filenameps-" + id).innerText;
  var hours = document.getElementById("hoursps-" + id).innerText;
  var phone = document.getElementById("phoneps-" + id).innerText;
  var order = document.getElementById("orderps-" + id).innerText;
  var website = document.getElementById("websiteps-" + id).innerText;
  var article = document.getElementById("articleps-" + id).innerText;

  // console.log(filename);
  // console.log(name);
  // console.log(order);
  var formname = document.getElementById("nameps");
  var formlocation = document.getElementById("locationps");
  var formlocationurl = document.getElementById("locationurlps");
  var formcontent = document.getElementById("contentps");
  var formorder = document.getElementById("orderps");
  var formid = document.getElementById("mtpsid");
  var formimage = document.getElementById("imagenamemtps");
  var formhours = document.getElementById("hoursps");
  var formphone = document.getElementById("phoneps");
  var formwebsite = document.getElementById("websiteps");
  var formarticle = document.getElementById("articleps");


  formid.value = id;
  formlocation.value = location;
  formimage.value = imagename;
  formlocationurl.value = locationurl;
  formcontent.value = content;
  formhours.value = hours;
  formphone.value = phone;
  formorder.value = order;
  formname.value = name;
  formwebsite.value = website;
  formarticle.value = article;
  setEditImagePreview("previewmtps", "../assets/img/medical_tourism/ps/", imagename, "fileToUploadpsedit");
  setEditMapCoords("mapcoordsmtps", "mapcoordsps-" + id);
  $("#editpsmodal").modal("show");
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
