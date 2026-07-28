

$('#table1').on('show.bs.collapse', function () {
  document.getElementById("table1a").innerHTML =
  '<i class="fas fa-chevron-up"></i>';
  window.localStorage.setItem("bkltable1collapse", false); // saves with no expiration
})

$('#table1').on('hide.bs.collapse', function () {
  document.getElementById("table1a").innerHTML =
  '<i class="fas fa-chevron-down"></i>';
  window.localStorage.setItem("bkltable1collapse", true); // saves with no expiration
})

$('#table2').on('show.bs.collapse', function () {
  document.getElementById("table2a").innerHTML =
  '<i class="fas fa-chevron-up"></i>';
  window.localStorage.setItem("bkltable2collapse", false); // saves with no expiration
})

$('#table2').on('hide.bs.collapse', function () {
  document.getElementById("table2a").innerHTML =
  '<i class="fas fa-chevron-down"></i>';
  window.localStorage.setItem("bkltable2collapse", true); // saves with no expiration
})

$('#table3').on('show.bs.collapse', function () {
  document.getElementById("table3a").innerHTML =
  '<i class="fas fa-chevron-up"></i>';
  window.localStorage.setItem("bkltable3collapse", false); // saves with no expiration
})

$('#table3').on('hide.bs.collapse', function () {
  document.getElementById("table3a").innerHTML =
  '<i class="fas fa-chevron-down"></i>';
  window.localStorage.setItem("bkltable3collapse", true); // saves with no expiration
})

$('#table4').on('show.bs.collapse', function () {
  document.getElementById("table4a").innerHTML =
  '<i class="fas fa-chevron-up"></i>';
  window.localStorage.setItem("bkltable4collapse", false); // saves with no expiration
})

$('#table4').on('hide.bs.collapse', function () {
  document.getElementById("table4a").innerHTML =
  '<i class="fas fa-chevron-down"></i>';
  window.localStorage.setItem("bkltable4collapse", true); // saves with no expiration
})


$('#table5').on('show.bs.collapse', function () {
  document.getElementById("table5a").innerHTML =
  '<i class="fas fa-chevron-up"></i>';
  window.localStorage.setItem("bkltable5collapse", false); // saves with no expiration
})

$('#table5').on('hide.bs.collapse', function () {
  document.getElementById("table5a").innerHTML =
  '<i class="fas fa-chevron-down"></i>';
  window.localStorage.setItem("bkltable5collapse", true); // saves with no expiration
})


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
function editmodali(id) {
  // //console.log("test");
  // //console.log(id);

  // var filename = document.getElementById("filename-" + id).innerText;
  var name = document.getElementById("namebkli-" + id).innerText;
  var location = document.getElementById("locationbkli-" + id).innerText;
  var locationurl = document.getElementById("locationurlbkli-" + id).innerText;
  var content = document.getElementById("contentbkli-" + id).innerText;
  var imagename = document.getElementById("filenamebkli-" + id).innerText;
  // var hours = document.getElementById("hoursbkli-" + id).innerText;
  // var phone = document.getElementById("phonebkli-" + id).innerText;
  var order = document.getElementById("orderbkli-" + id).innerText;

  // //console.log(filename);
  // //console.log(name);
  // //console.log(order);
  var formname = document.getElementById("namebkli");
  var formlocation = document.getElementById("locationbkli");
  var formlocationurl = document.getElementById("locationurlbkli");
  var formcontent = document.getElementById("contentbkli");
  var formorder = document.getElementById("orderbkli");
  var formid = document.getElementById("bkliid");
  var formimage = document.getElementById("imagenamebkli");
  // var formhours = document.getElementById("hoursbkli");
  // var formphone = document.getElementById("phonebkli");
  formid.value = id;
  formimage.value = imagename;
  formlocationurl.value = locationurl;
  formcontent.value = content;
  formorder.value = order;
  // formhours.value = hours;
  // formphone.value = phone;
  formname.value = name;
  formlocation.value = location;
  setEditImagePreview("previewbkli", "../assets/img/beyondkl/i/", imagename, "fileToUploadbkliedit");
  setEditMapCoords("mapcoordsbkli", "mapcoordsbkli-" + id);
  $("#editimodal").modal("show");
}

function editmodalhs(id) {
  // //console.log("test");
  // //console.log(id);

  // var filename = document.getElementById("filename-" + id).innerText;
  var name = document.getElementById("namebklhs-" + id).innerText;
  var location = document.getElementById("locationbklhs-" + id).innerText;
  var locationurl = document.getElementById("locationurlbklhs-" + id).innerText;
  var content = document.getElementById("contentbklhs-" + id).innerText;
  var imagename = document.getElementById("filenamebklhs-" + id).innerText;
  // var hours = document.getElementById("hoursbklhs-" + id).innerText;
  // var phone = document.getElementById("phonebklhs-" + id).innerText;
  var order = document.getElementById("orderbklhs-" + id).innerText;

  // //console.log(filename);
  // //console.log(name);
  // //console.log(order);
  var formname = document.getElementById("namebklhs");
  var formlocation = document.getElementById("locationbklhs");
  var formlocationurl = document.getElementById("locationurlbklhs");
  var formcontent = document.getElementById("contentbklhs");
  var formorder = document.getElementById("orderbklhs");
  var formid = document.getElementById("bklhsid");
  var formimage = document.getElementById("imagenamebklhs");
  // var formhours = document.getElementById("hoursbklhs");
  // var formphone = document.getElementById("phonebklhs");
  formid.value = id;
  formimage.value = imagename;
  formlocationurl.value = locationurl;
  formcontent.value = content;
  formorder.value = order;
  // formhours.value = hours;
  // formphone.value = phone;
  formname.value = name;
  formlocation.value = location;
  setEditImagePreview("previewbklhs", "../assets/img/beyondkl/hs/", imagename, "fileToUploadbklhsedit");
  setEditMapCoords("mapcoordsbklhs", "mapcoordsbklhs-" + id);
  $("#edithsmodal").modal("show");
}

function editmodalw(id) {
  // //console.log("test");
  // //console.log(id);

  // var filename = document.getElementById("filename-" + id).innerText;
  var name = document.getElementById("namebklw-" + id).innerText;
  var location = document.getElementById("locationbklw-" + id).innerText;
  var locationurl = document.getElementById("locationurlbklw-" + id).innerText;
  var content = document.getElementById("contentbklw-" + id).innerText;
  var imagename = document.getElementById("filenamebklw-" + id).innerText;
  // var hours = document.getElementById("hoursbklw-" + id).innerText;
  // var phone = document.getElementById("phonebklw-" + id).innerText;
  var order = document.getElementById("orderbklw-" + id).innerText;

  // //console.log(filename);
  // //console.log(name);
  // //console.log(order);
  var formname = document.getElementById("namebklw");
  var formlocation = document.getElementById("locationbklw");
  var formlocationurl = document.getElementById("locationurlbklw");
  var formcontent = document.getElementById("contentbklw");
  var formorder = document.getElementById("orderbklw");
  var formid = document.getElementById("bklwid");
  var formimage = document.getElementById("imagenamebklw");
  // var formhours = document.getElementById("hoursbklw");
  // var formphone = document.getElementById("phonebklw");
  formid.value = id;
  formimage.value = imagename;
  formlocationurl.value = locationurl;
  formcontent.value = content;
  formorder.value = order;
  // formhours.value = hours;
  // formphone.value = phone;
  formname.value = name;
  formlocation.value = location;
  setEditImagePreview("previewbklw", "../assets/img/beyondkl/w/", imagename, "fileToUploadbklwedit");
  setEditMapCoords("mapcoordsbklw", "mapcoordsbklw-" + id);
  $("#editwmodal").modal("show");
}

function editmodalh(id) {
  // //console.log("test");
  // //console.log(id);

  // var filename = document.getElementById("filename-" + id).innerText;
  var name = document.getElementById("namebklh-" + id).innerText;
  var location = document.getElementById("locationbklh-" + id).innerText;
  var locationurl = document.getElementById("locationurlbklh-" + id).innerText;
  var content = document.getElementById("contentbklh-" + id).innerText;
  var imagename = document.getElementById("filenamebklh-" + id).innerText;
  // var hours = document.getElementById("hoursbklh-" + id).innerText;
  // var phone = document.getElementById("phonebklh-" + id).innerText;
  var order = document.getElementById("orderbklh-" + id).innerText;

  // //console.log(filename);
  // //console.log(name);
  // //console.log(order);
  var formname = document.getElementById("namebklh");
  var formlocation = document.getElementById("locationbklh");
  var formlocationurl = document.getElementById("locationurlbklh");
  var formcontent = document.getElementById("contentbklh");
  var formorder = document.getElementById("orderbklh");
  var formid = document.getElementById("bklhid");
  var formimage = document.getElementById("imagenamebklh");
  // var formhours = document.getElementById("hoursbklh");
  // var formphone = document.getElementById("phonebklh");
  formid.value = id;
  formimage.value = imagename;
  formlocationurl.value = locationurl;
  formcontent.value = content;
  formorder.value = order;
  // formhours.value = hours;
  // formphone.value = phone;
  formname.value = name;
  formlocation.value = location;
  setEditImagePreview("previewbklh", "../assets/img/beyondkl/h/", imagename, "fileToUploadbklhedit");
  setEditMapCoords("mapcoordsbklh", "mapcoordsbklh-" + id);
  $("#edithmodal").modal("show");
}

function editmodales(id) {
  // //console.log("test");
  // //console.log(id);

  // var filename = document.getElementById("filename-" + id).innerText;
  var name = document.getElementById("namebkles-" + id).innerText;
  var location = document.getElementById("locationbkles-" + id).innerText;
  var locationurl = document.getElementById("locationurlbkles-" + id).innerText;
  var content = document.getElementById("contentbkles-" + id).innerText;
  var imagename = document.getElementById("filenamebkles-" + id).innerText;
  // var hours = document.getElementById("hoursbkles-" + id).innerText;
  // var phone = document.getElementById("phonebkles-" + id).innerText;
  var order = document.getElementById("orderbkles-" + id).innerText;

  // //console.log(filename);
  // //console.log(name);
  // //console.log(order);
  var formname = document.getElementById("namebkles");
  var formlocation = document.getElementById("locationbkles");
  var formlocationurl = document.getElementById("locationurlbkles");
  var formcontent = document.getElementById("contentbkles");
  var formorder = document.getElementById("orderbkles");
  var formid = document.getElementById("bklesid");
  var formimage = document.getElementById("imagenamebkles");
  // var formhours = document.getElementById("hoursbkles");
  // var formphone = document.getElementById("phonebkles");
  formid.value = id;
  formimage.value = imagename;
  formlocationurl.value = locationurl;
  formcontent.value = content;
  formorder.value = order;
  // formhours.value = hours;
  // formphone.value = phone;
  formname.value = name;
  formlocation.value = location;
  setEditImagePreview("previewbkles", "../assets/img/beyondkl/es/", imagename, "fileToUploadbklesedit");
  setEditMapCoords("mapcoordsbkles", "mapcoordsbkles-" + id);
  $("#editesmodal").modal("show");
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
$(document).ready(function () {


  $('#dataTable1').DataTable({
    "ordering": false,

    "lengthMenu": [ 5,10, 25, 50, 75, 100 ],

  });
  $('#dataTable2a').DataTable({
    "ordering": false,

    "lengthMenu": [ 5,10, 25, 50, 75, 100 ],

  });
  $('#dataTable3').DataTable({
    "ordering": false,

    "lengthMenu": [ 5,10, 25, 50, 75, 100 ],

  });
  $('#dataTable4').DataTable({
    "ordering": false,

    "lengthMenu": [ 5,10, 25, 50, 75, 100 ],

  });
  $('#dataTable5').DataTable({
    "ordering": false,

    "lengthMenu": [ 5,10, 25, 50, 75, 100 ],

  });


  if (typeof Storage !== "undefined") {
    // Restore each card's collapsed state. Guarded: this page has five
    // tables, and the previous version reached for a sixth chevron that does
    // not exist here, throwing and aborting the rest of document.ready.
    for (var t = 1; t <= 5; t++) {
      var chevron = document.getElementById("table" + t + "a");
      var state = window.localStorage.getItem("bkltable" + t + "collapse");
      if (!chevron || (state !== "true" && state !== "false")) continue;
      var collapsed = state === "true";
      chevron.innerHTML = collapsed
        ? '<i class="fas fa-chevron-down"></i>'
        : '<i class="fas fa-chevron-up"></i>';
      $("#table" + t).collapse(collapsed ? "hide" : "show");
    }
  }



});