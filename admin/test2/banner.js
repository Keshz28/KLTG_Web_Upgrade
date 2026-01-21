function newmodal(id) {
  console.log("test");
  console.log(id);
  var filename = document.getElementById("filename-" + id).innerText;
  var name = document.getElementById("name-" + id).innerText;
  var order = document.getElementById("order-" + id).innerText;

  console.log(filename);
  console.log(name);
  console.log(order);
  var formname = document.getElementById("exampleFormControlTextarea1");
  var formorder = document.getElementById("exampleFormControlTextarea2");
  var formfilename = document.getElementById("exampleFormControlTextarea3");
  var formid = document.getElementById("exampleFormControlTextarea8");

  formid.value = id;
  formorder.value = order;
  formname.value = name;
  formfilename.value = filename;
  $('#exampleModal2').modal('show');

}
