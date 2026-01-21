function removeHttp(url) {
  return url.replace(/^https?:\/\//, "");
}
function editmodalwtd(id) {
  // console.log("test");
  // console.log(id);

  // var filename = document.getElementById("filename-" + id).innerText;
  var name = document.getElementById("nameeklwtd-" + id).innerText;
  // var location = document.getElementById("locationeklwtd-" + id).innerText;
  var locationurl = document.getElementById("locationurleklwtd-" + id).innerText;
  var content = document.getElementById("contenteklwtd-" + id).innerText;
  var imagename = document.getElementById("filenameeklwtd-" + id).innerText;
  // var hours = document.getElementById("hourseklwtd-" + id).innerText;
  // var phone = document.getElementById("phoneeklwtd-" + id).innerText;
  var order = document.getElementById("ordereklwtd-" + id).innerText;

  // console.log(filename);
  // console.log(name);
  // console.log(order);
  var formname = document.getElementById("nameeklwtd");
  var formlocationurl = document.getElementById("locationurleklwtd");
  var formcontent = document.getElementById("contenteklwtd");
  var formorder = document.getElementById("ordereklwtd");
  var formid = document.getElementById("eklwtdid");
  var formimage = document.getElementById("imagenameeklwtd");
  // var formhours = document.getElementById("hourseklwtd");
  // var formphone = document.getElementById("phoneeklwtd");
  formid.value = id;
  formimage.value = imagename;  
  formlocationurl.value = locationurl;
  formcontent.value = content;
  formorder.value = order;
  // formhours.value = hours;
  // formphone.value = phone;
  formname.value = name;
  $("#editwtdmodal").modal("show");
}


function editmodaleklhs(id) {
  // console.log("test");
  // console.log(id);

  // var filename = document.getElementById("filename-" + id).innerText;
  var name = document.getElementById("nameeklhs-" + id).innerText;
  var location = document.getElementById("locationeklhs-" + id).innerText;
  var locationurl = document.getElementById("locationurleklhs-" + id).innerText;
  var content = document.getElementById("contenteklhs-" + id).innerText;
  var imagename = document.getElementById("filenameeklhs-" + id).innerText;
  var hours = document.getElementById("hourseklhs-" + id).innerText;
  var phone = document.getElementById("phoneeklhs-" + id).innerText;
  var order = document.getElementById("ordereklhs-" + id).innerText;

  // console.log(filename);
  // console.log(name);
  // console.log(order);
  var formname = document.getElementById("nameeklhs");
  var formlocation = document.getElementById("locationeklhs");
  var formlocationurl = document.getElementById("locationurleklhs");
  var formcontent = document.getElementById("contenteklhs");
  var formorder = document.getElementById("ordereklhs");
  var formid = document.getElementById("eklhsid");
  var formimage = document.getElementById("imagenameeklhs");
  var formhours = document.getElementById("hourseklhs");
  var formphone = document.getElementById("phoneeklhs");

  formid.value = id;
  formlocation.value = location;
  formimage.value = imagename;
  formlocationurl.value = locationurl;
  formcontent.value = content;
  formhours.value = hours;
  formphone.value = phone;
  formorder.value = order;
  formname.value = name;
  $("#editeklhsmodal").modal("show");
}

function editmodaleklkl4k(id) {
  // console.log("test");
  // console.log(id);

  // var filename = document.getElementById("filename-" + id).innerText;
  var name = document.getElementById("nameeklkl4k-" + id).innerText;
  var location = document.getElementById("locationeklkl4k-" + id).innerText;
  var locationurl = document.getElementById("locationurleklkl4k-" + id).innerText;
  var content = document.getElementById("contenteklkl4k-" + id).innerText;
  var imagename = document.getElementById("filenameeklkl4k-" + id).innerText;
  var hours = document.getElementById("hourseklkl4k-" + id).innerText;
  var phone = document.getElementById("phoneeklkl4k-" + id).innerText;
  var order = document.getElementById("ordereklkl4k-" + id).innerText;

  // console.log(filename);
  // console.log(name);
  // console.log(order);
  var formname = document.getElementById("nameeklkl4k");
  var formlocation = document.getElementById("locationeklkl4k");
  var formlocationurl = document.getElementById("locationurleklkl4k");
  var formcontent = document.getElementById("contenteklkl4k");
  var formorder = document.getElementById("ordereklkl4k");
  var formid = document.getElementById("eklkl4kid");
  var formimage = document.getElementById("imagenameeklkl4k");
  var formhours = document.getElementById("hourseklkl4k");
  var formphone = document.getElementById("phoneeklkl4k");

  formid.value = id;
  formlocation.value = location;
  formimage.value = imagename;
  formlocationurl.value = locationurl;
  formcontent.value = content;
  formhours.value = hours;
  formphone.value = phone;
  formorder.value = order;
  formname.value = name;
  $("#editeklkl4kmodal").modal("show");
}



function editmodaleklp(id) {
  // console.log("test");
  // console.log(id);

  // var filename = document.getElementById("filename-" + id).innerText;
  var name = document.getElementById("nameeklp-" + id).innerText;
  var location = document.getElementById("locationeklp-" + id).innerText;
  var locationurl = document.getElementById("locationurleklp-" + id).innerText;
  var content = document.getElementById("contenteklp-" + id).innerText;
  var imagename = document.getElementById("filenameeklp-" + id).innerText;
  var hours = document.getElementById("hourseklp-" + id).innerText;
  var phone = document.getElementById("phoneeklp-" + id).innerText;
  var order = document.getElementById("ordereklp-" + id).innerText;

  // console.log(filename);
  // console.log(name);
  // console.log(order);
  var formname = document.getElementById("nameeklp");
  var formlocation = document.getElementById("locationeklp");
  var formlocationurl = document.getElementById("locationurleklp");
  var formcontent = document.getElementById("contenteklp");
  var formorder = document.getElementById("ordereklp");
  var formid = document.getElementById("eklpid");
  var formimage = document.getElementById("imagenameeklp");
  var formhours = document.getElementById("hourseklp");
  var formphone = document.getElementById("phoneeklp");

  formid.value = id;
  formlocation.value = location;
  formimage.value = imagename;
  formlocationurl.value = locationurl;
  formcontent.value = content;
  formhours.value = hours;
  formphone.value = phone;
  formorder.value = order;
  formname.value = name;
  $("#editeklpmodal").modal("show");
}




function editmodaleklpwor(id) {
  // console.log("test");
  // console.log(id);

  // var filename = document.getElementById("filename-" + id).innerText;
  var name = document.getElementById("nameeklpwor-" + id).innerText;
  var category = document.getElementById("categoryeklpwor-" + id).innerText;
  var location = document.getElementById("locationeklpwor-" + id).innerText;
  var locationurl = document.getElementById("locationurleklpwor-" + id).innerText;
  var content = document.getElementById("contenteklpwor-" + id).innerText;
  var imagename = document.getElementById("filenameeklpwor-" + id).innerText;
  var hours = document.getElementById("hourseklpwor-" + id).innerText;
  var phone = document.getElementById("phoneeklpwor-" + id).innerText;
  var order = document.getElementById("ordereklpwor-" + id).innerText;

  // console.log(filename);
  // console.log(name);
  // console.log(order);
  var formname = document.getElementById("nameeklpwor");
  var formcategory = document.getElementById("categoryeklpwor");
  var formlocation = document.getElementById("locationeklpwor");
  var formlocationurl = document.getElementById("locationurleklpwor");
  var formcontent = document.getElementById("contenteklpwor");
  var formorder = document.getElementById("ordereklpwor");
  var formid = document.getElementById("eklpworid");
  var formimage = document.getElementById("imagenameeklpwor");
  var formhours = document.getElementById("hourseklpwor");
  var formphone = document.getElementById("phoneeklpwor");

  formid.value = id;
  formlocation.value = location;
  formimage.value = imagename;
  formlocationurl.value = locationurl;
  formcontent.value = content;
  formhours.value = hours;
  formphone.value = phone;
  formorder.value = order;
  formname.value = name;
  formcategory.value = category;
  $("#editeklpwormodal").modal("show");
}

function editmodaleklnl(id) {
  // console.log("test");
  // console.log(id);

  // var filename = document.getElementById("filename-" + id).innerText;
  var name = document.getElementById("nameeklnl-" + id).innerText;
  var category = document.getElementById("categoryeklnl-" + id).innerText;
  var location = document.getElementById("locationeklnl-" + id).innerText;
  var locationurl = document.getElementById("locationurleklnl-" + id).innerText;
  var content = document.getElementById("contenteklnl-" + id).innerText;
  var imagename = document.getElementById("filenameeklnl-" + id).innerText;
  var hours = document.getElementById("hourseklnl-" + id).innerText;
  var phone = document.getElementById("phoneeklnl-" + id).innerText;
  var order = document.getElementById("ordereklnl-" + id).innerText;

  // console.log(filename);
  // console.log(name);
  // console.log(order);
  var formname = document.getElementById("nameeklnl");
  var formcategory = document.getElementById("categoryeklnl");
  var formlocation = document.getElementById("locationeklnl");
  var formlocationurl = document.getElementById("locationurleklnl");
  var formcontent = document.getElementById("contenteklnl");
  var formorder = document.getElementById("ordereklnl");
  var formid = document.getElementById("eklnlid");
  var formimage = document.getElementById("imagenameeklnl");
  var formhours = document.getElementById("hourseklnl");
  var formphone = document.getElementById("phoneeklnl");

  formid.value = id;
  formlocation.value = location;
  formimage.value = imagename;
  formlocationurl.value = locationurl;
  formcontent.value = content;
  formhours.value = hours;
  formphone.value = phone;
  formorder.value = order;
  formname.value = name;
  formcategory.value = category;
  $("#editeklnlmodal").modal("show");
}

function editmodaleklss(id) {
  // console.log("test");
  // console.log(id);

  // var filename = document.getElementById("filename-" + id).innerText;
  var name = document.getElementById("nameeklss-" + id).innerText;
  var category = document.getElementById("categoryeklss-" + id).innerText;
  var location = document.getElementById("locationeklss-" + id).innerText;
  var locationurl = document.getElementById("locationurleklss-" + id).innerText;
  var content = document.getElementById("contenteklss-" + id).innerText;
  var imagename = document.getElementById("filenameeklss-" + id).innerText;
  var hours = document.getElementById("hourseklss-" + id).innerText;
  var phone = document.getElementById("phoneeklss-" + id).innerText;
  var order = document.getElementById("ordereklss-" + id).innerText;

  // console.log(filename);
  // console.log(name);
  // console.log(order);
  var formname = document.getElementById("nameeklss");
  var formcategory = document.getElementById("categoryeklss");
  var formlocation = document.getElementById("locationeklss");
  var formlocationurl = document.getElementById("locationurleklss");
  var formcontent = document.getElementById("contenteklss");
  var formorder = document.getElementById("ordereklss");
  var formid = document.getElementById("eklssid");
  var formimage = document.getElementById("imagenameeklss");
  var formhours = document.getElementById("hourseklss");
  var formphone = document.getElementById("phoneeklss");

  formid.value = id;
  formlocation.value = location;
  formimage.value = imagename;
  formlocationurl.value = locationurl;
  formcontent.value = content;
  formhours.value = hours;
  formphone.value = phone;
  formorder.value = order;
  formname.value = name;
  formcategory.value = category;
  $("#editeklssmodal").modal("show");
}



function editmodaleklwtesf(id) {
  // console.log("test");
  // console.log(id);

  // var filename = document.getElementById("filename-" + id).innerText;
  var name = document.getElementById("nameeklwtesf-" + id).innerText;
  var location = document.getElementById("locationeklwtesf-" + id).innerText;
  var locationurl = document.getElementById("locationurleklwtesf-" + id).innerText;
  var content = document.getElementById("contenteklwtesf-" + id).innerText;
  var website = document.getElementById("websiteeklwtesf-" + id).innerText;
  var imagename = document.getElementById("filenameeklwtesf-" + id).innerText;
  var hours = document.getElementById("hourseklwtesf-" + id).innerText;
  var phone = document.getElementById("phoneeklwtesf-" + id).innerText;
  var order = document.getElementById("ordereklwtesf-" + id).innerText;

  // console.log(filename);
  // console.log(name);
  // console.log(order);
  var formname = document.getElementById("nameeklwtesf");
  var formlocation = document.getElementById("locationeklwtesf");
  var formlocationurl = document.getElementById("locationurleklwtesf");
  var formcontent = document.getElementById("contenteklwtesf");
  var formwebsite = document.getElementById("websiteeklwtesf");
  var formorder = document.getElementById("ordereklwtesf");
  var formid = document.getElementById("eklwtesfid");
  var formimage = document.getElementById("imagenameeklwtesf");
  var formhours = document.getElementById("hourseklwtesf");
  var formphone = document.getElementById("phoneeklwtesf");

  formid.value = id;
  formlocation.value = location;
  formimage.value = imagename;
  formlocationurl.value = locationurl;
  formcontent.value = content;
  formwebsite.value = website;
  formhours.value = hours;
  formphone.value = phone;
  formorder.value = order;
  formname.value = name;
  $("#editeklwtesfmodal").modal("show");
}



function editmodaleklwtec(id) {
  // console.log("test");
  // console.log(id);

  // var filename = document.getElementById("filename-" + id).innerText;
  var name = document.getElementById("nameeklwtec-" + id).innerText;
  var location = document.getElementById("locationeklwtec-" + id).innerText;
  var locationurl = document.getElementById("locationurleklwtec-" + id).innerText;
  var content = document.getElementById("contenteklwtec-" + id).innerText;
  var website = document.getElementById("websiteeklwtec-" + id).innerText;
  var imagename = document.getElementById("filenameeklwtec-" + id).innerText;
  var hours = document.getElementById("hourseklwtec-" + id).innerText;
  var phone = document.getElementById("phoneeklwtec-" + id).innerText;
  var order = document.getElementById("ordereklwtec-" + id).innerText;

  // console.log(filename);
  // console.log(name);
  // console.log(order);
  var formname = document.getElementById("nameeklwtec");
  var formlocation = document.getElementById("locationeklwtec");
  var formlocationurl = document.getElementById("locationurleklwtec");
  var formcontent = document.getElementById("contenteklwtec");
  var formwebsite = document.getElementById("websiteeklwtec");
  var formorder = document.getElementById("ordereklwtec");
  var formid = document.getElementById("eklwtecid");
  var formimage = document.getElementById("imagenameeklwtec");
  var formhours = document.getElementById("hourseklwtec");
  var formphone = document.getElementById("phoneeklwtec");

  formid.value = id;
  formlocation.value = location;
  formimage.value = imagename;
  formlocationurl.value = locationurl;
  formcontent.value = content;
  formwebsite.value = website;
  formhours.value = hours;
  formphone.value = phone;
  formorder.value = order;
  formname.value = name;
  $("#editeklwtecmodal").modal("show");
}



function editmodaleklwter(id) {
  // console.log("test");
  // console.log(id);

  // var filename = document.getElementById("filename-" + id).innerText;
  var name = document.getElementById("nameeklwter-" + id).innerText;
  var location = document.getElementById("locationeklwter-" + id).innerText;
  var locationurl = document.getElementById("locationurleklwter-" + id).innerText;
  var content = document.getElementById("contenteklwter-" + id).innerText;
  var category = document.getElementById("categoryeklwter-" + id).innerText;
  var website = document.getElementById("websiteeklwter-" + id).innerText;
  var imagename = document.getElementById("filenameeklwter-" + id).innerText;
  var hours = document.getElementById("hourseklwter-" + id).innerText;
  var phone = document.getElementById("phoneeklwter-" + id).innerText;
  var order = document.getElementById("ordereklwter-" + id).innerText;

  // console.log(filename);
  // console.log(name);
  // console.log(order);
  var formname = document.getElementById("nameeklwter");
  var formlocation = document.getElementById("locationeklwter");
  var formlocationurl = document.getElementById("locationurleklwter");
  var formcontent = document.getElementById("contenteklwter");
  var formcategory = document.getElementById("categoryeklwter");
  var formwebsite = document.getElementById("websiteeklwter");
  var formorder = document.getElementById("ordereklwter");
  var formid = document.getElementById("eklwterid");
  var formimage = document.getElementById("imagenameeklwter");
  var formhours = document.getElementById("hourseklwter");
  var formphone = document.getElementById("phoneeklwter");

  formid.value = id;
  formlocation.value = location;
  formimage.value = imagename;
  formlocationurl.value = locationurl;
  formcontent.value = content;
  formcategory.value = category;
  formwebsite.value = website;
  formhours.value = hours;
  formphone.value = phone;
  formorder.value = order;
  formname.value = name;
  $("#editeklwtermodal").modal("show");
}

var toastbody = document.getElementById("toast-body");
$("#toast11").hide();
function toastupdate(body) {
  console.log("test");

  const toastLiveExample = document.getElementById("liveToast");

  const toast = new bootstrap.Toast(toastLiveExample);

  var toastbody = document.getElementById("toast-body");
  toastbody.innerHTML = body;
  $(".toast").toast("show");
  $("#toast11").toast("show");

  toast.show();
}


$(document).ready(function() {

  $('#dataTable1').DataTable({
    
    "ordering": false,
    "lengthMenu": [ 5,10, 25, 50, 75, 100 ],

  });
  $('#dataTable2').DataTable({
    
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
  $('#dataTable6').DataTable({
    
    "ordering": false,
    "lengthMenu": [ 5,10, 25, 50, 75, 100 ],

  });
  $('#dataTable7').DataTable({
    
    "ordering": false,
    "lengthMenu": [ 5,10, 25, 50, 75, 100 ],

  });
  $('#dataTable8').DataTable({
    
    "ordering": false,
    "lengthMenu": [ 5,10, 25, 50, 75, 100 ],

  });
  $('#dataTable9').DataTable({
    
    "ordering": false,
    "lengthMenu": [ 5,10, 25, 50, 75, 100 ],

  });
  $('#dataTable10').DataTable({
    
    "ordering": false,
    "lengthMenu": [ 5,10, 25, 50, 75, 100 ],

  });
});