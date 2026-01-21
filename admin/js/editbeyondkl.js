

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
function editmodali(id) {
  // //console.log("test");
  // //console.log(id);

  // var filename = document.getElementById("filename-" + id).innerText;
  var name = document.getElementById("namebkli-" + id).innerText;
  // var location = document.getElementById("locationbkli-" + id).innerText;
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
  $("#editimodal").modal("show");
}

function editmodalhs(id) {
  // //console.log("test");
  // //console.log(id);

  // var filename = document.getElementById("filename-" + id).innerText;
  var name = document.getElementById("namebklhs-" + id).innerText;
  // var location = document.getElementById("locationbklhs-" + id).innerText;
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
  $("#edithsmodal").modal("show");
}

function editmodalw(id) {
  // //console.log("test");
  // //console.log(id);

  // var filename = document.getElementById("filename-" + id).innerText;
  var name = document.getElementById("namebklw-" + id).innerText;
  // var location = document.getElementById("locationbklw-" + id).innerText;
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
  $("#editwmodal").modal("show");
}

function editmodalh(id) {
  // //console.log("test");
  // //console.log(id);

  // var filename = document.getElementById("filename-" + id).innerText;
  var name = document.getElementById("namebklh-" + id).innerText;
  // var location = document.getElementById("locationbklh-" + id).innerText;
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
  $("#edithmodal").modal("show");
}

function editmodales(id) {
  // //console.log("test");
  // //console.log(id);

  // var filename = document.getElementById("filename-" + id).innerText;
  var name = document.getElementById("namebkles-" + id).innerText;
  // var location = document.getElementById("locationbkles-" + id).innerText;
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
  $("#editesmodal").modal("show");
}

var toastbody = document.getElementById("toast-body");
$("#toast11").hide();
function toastupdate(body) {
  //console.log("test");

  const toastLiveExample = document.getElementById("liveToast");

  const toast = new bootstrap.Toast(toastLiveExample);

  var toastbody = document.getElementById("toast-body");
  toastbody.innerHTML = body;
  $(".toast").toast("show");
  $("#toast11").toast("show");

  toast.show();
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


    var table1collapse = window.localStorage.getItem("indextable1collapse"); // again choose one
    //console.log(table1collapse);
    if (table1collapse == "false") {
      document.getElementById("table1a").innerHTML =
        '<i class="fas fa-chevron-up"></i>';
      $("#table1").collapse("show");
    }
    if (table1collapse == "true") {
      document.getElementById("table1a").innerHTML =
        '<i class="fas fa-chevron-down"></i>';
      $("#table1").collapse("hide");
    }

    var table2collapse = window.localStorage.getItem("indextable2collapse"); // again choose one
    //console.log(table2collapse);
    if (table2collapse == "false") {
      document.getElementById("table2a").innerHTML =
        '<i class="fas fa-chevron-up"></i>';
      $("#table2").collapse("show");
    }
    if (table2collapse == "true") {
      document.getElementById("table2a").innerHTML =
        '<i class="fas fa-chevron-down"></i>';
      $("#table2").collapse("hide");
    }

    var table3collapse = window.localStorage.getItem("indextable3collapse"); // again choose one
    //console.log(table3collapse);
    if (table3collapse == "false") {
      document.getElementById("table3a").innerHTML =
        '<i class="fas fa-chevron-up"></i>';
      $("#table3").collapse("show");
    }
    if (table3collapse == "true") {
      document.getElementById("table3a").innerHTML =
        '<i class="fas fa-chevron-down"></i>';
      $("#table3").collapse("hide");
    }

    var table4collapse = window.localStorage.getItem("indextable4collapse"); // again choose one
    //console.log(table4collapse);
    if (table4collapse == "false") {
      document.getElementById("table4a").innerHTML =
        '<i class="fas fa-chevron-up"></i>';
      $("#table4").collapse("show");
    }
    if (table4collapse == "true") {
      document.getElementById("table4a").innerHTML =
        '<i class="fas fa-chevron-down"></i>';
      $("#table4").collapse("hide");
    }

    var table5collapse = window.localStorage.getItem("indextable5collapse"); // again choose one
    //console.log(table5collapse);
    if (table5collapse == "false") {
      document.getElementById("table5a").innerHTML =
        '<i class="fas fa-chevron-up"></i>';
      $("#table5").collapse("show");
    }
    if (table5collapse == "true") {
      document.getElementById("table5a").innerHTML =
        '<i class="fas fa-chevron-down"></i>';
      $("#table5").collapse("hide");
    }
    var table6collapse = window.localStorage.getItem("indextable6collapse"); // again choose one
    //console.log(table5collapse);
    if (table5collapse == "false") {
      document.getElementById("table6a").innerHTML =
        '<i class="fas fa-chevron-up"></i>';
      $("#table6").collapse("show");
    }
    if (table5collapse == "true") {
      document.getElementById("table6a").innerHTML =
        '<i class="fas fa-chevron-down"></i>';
      $("#table6").collapse("hide");
    }

  } else {
    // Sorry! No Storage support..
  }


});