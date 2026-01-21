function removeHttp(url) {
  return url.replace(/^https?:\/\//, "");
}
function newmodal(id) {
  // console.log("test");
  // console.log(id);
  var filename = document.getElementById("filename-" + id).innerText;
  var name = document.getElementById("name-" + id).innerText;
  var order = document.getElementById("order-" + id).innerText;

  // console.log(filename);
  // console.log(name);
  // console.log(order);
  var formname = document.getElementById("exampleFormControlTextarea1");
  var formorder = document.getElementById("exampleFormControlTextarea2");
  var formfilename = document.getElementById("exampleFormControlTextarea3");
  var formid = document.getElementById("exampleFormControlTextarea8");

  formid.value = id;
  formorder.value = order;
  formname.value = name;
  formfilename.value = filename;
  $("#exampleModal3").modal("show");
}

function editmodal(id, title) {
  // console.log("test");
  // console.log(id);
  // var filename = document.getElementById("filename-" + id).innerText;
  // var name = document.getElementById("name-" + id).innerText;
  // var order = document.getElementById("order-" + id).innerText;

  // console.log(filename);
  // console.log(name);
  // console.log(order);
  var ida = document.getElementById("hiddenid");
  // var formorder = document.getElementById("exampleFormControlTextarea2");
  var name = document.getElementById("recommendname");
  // var formid = document.getElementById("exampleFormControlTextarea8");
  name.value = title;
  ida.value = id;
  // formid.value = id;
  // formorder.value = order;
  // formname.value = name;
  // formfilename.value = filename;
  $("#editrecommend").modal("show");
}
$("#toast11").hide();

function toastupdate(body) {
  // console.log('test');

  const toastLiveExample = document.getElementById("liveToast");

  const toast = new bootstrap.Toast(toastLiveExample);
  var toastbody = document.getElementById("toast-body");

  toastbody.innerHTML = body;
  $(".toast").toast("show");
  $("#toast11").toast("show");

  toast.show();
}
$(document).ready(function () {
  $("#dataTable55").DataTable({
    ordering: false,
    lengthMenu: [5, 10, 25, 50, 75, 100],
  });
});

function editmodalebook(id) {
  //console.log("test");
  console.log(id);

  // var filename = document.getElementById("filename-" + id).innerText;
  var name = document.getElementById("name-" + id).innerText;
  var url = document.getElementById("url-" + id).innerText;
  var category = document.getElementById("category-" + id).innerText;
  var image = document.getElementById("image-" + id).innerText;
  var filename = document.getElementById("filename-" + id).innerText;
  // var location = document.getElementById("locationbkli-" + id).innerText;
  // var locationurl = document.getElementById("locationurlbkli-" + id).innerText;
  // var content = document.getElementById("contentbkli-" + id).innerText;
  // var imagename = document.getElementById("filenamebkli-" + id).innerText;
  // var hours = document.getElementById("hoursbkli-" + id).innerText;
  // var phone = document.getElementById("phonebkli-" + id).innerText;
  // var order = document.getElementById("orderbkli-" + id).innerText;

  // //console.log(filename);
  // //console.log(name);
  // //console.log(order);
  var formname = document.getElementById("ebook_name2");
  // var formlocationurl = document.getElementById("locationurlbkli");
  // var formcontent = document.getElementById("contentbkli");
  // var formorder = document.getElementById("orderbkli");
  var formfilename = document.getElementById("fileToUpload2a");
  var formimage = document.getElementById("fileToUpload3a");
  var formurl = document.getElementById("ebook_url");
  var formid = document.getElementById("hiddenid2");
  var formcategory = document.getElementById("ebook_category2");
  // var formimage = document.getElementById("imagenamebkli");
  // var formhours = document.getElementById("hoursbkli");
  // var formphone = document.getElementById("phonebkli");
  formid.value = id;
  formcategory.value = category;
  formfilename.value = filename;
  formimage.value = image;
  formurl.value = url;
  formname.value = name;
  console.log(formcategory.value);
  // formimage.value = imagename;
  // formlocationurl.value = locationurl;
  // formcontent.value = content;
  // formorder.value = order;
  // formhours.value = hours;
  // formphone.value = phone;
  // formname.value = name;
  $("#editebook2").modal("show");
}
