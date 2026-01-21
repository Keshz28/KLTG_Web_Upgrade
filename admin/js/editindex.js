initblog();


$(document).ready(function () {
  $("#dataTable1").DataTable({
    ordering: false,
  });
  $("#dataTable2").DataTable({
    ordering: false,
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
    //console.log(table6collapse);
    if (table6collapse == "false") {
      document.getElementById("table6a").innerHTML =
        '<i class="fas fa-chevron-up"></i>';
      $("#table6").collapse("show");
    }
    if (table6collapse == "true") {
      document.getElementById("table6a").innerHTML =
        '<i class="fas fa-chevron-down"></i>';
      $("#table6").collapse("hide");
    }

  } else {
    // Sorry! No Storage support..
  }
});

function removeHttp(url) {
  return url.replace(/^https?:\/\//, "");
}
function newmodal(id) {
  // //console.log("test");
  // //console.log(id);
  var filename = document.getElementById("filename-" + id).innerText;
  var filename2 = document.getElementById("filename2-" + id).innerText;
  var name = document.getElementById("name-" + id).innerText;
  var order = document.getElementById("order-" + id).innerText;
  var url = document.getElementById("url-" + id).innerText;

  // //console.log(filename);
  // //console.log(name);
  // //console.log(order);
  var formname = document.getElementById("exampleFormControlTextarea1");
  var formorder = document.getElementById("exampleFormControlTextarea2");
  var formfilename = document.getElementById("exampleFormControlTextarea3");
  var formfilename2 = document.getElementById("exampleFormControlTextarea99");
  var formid = document.getElementById("exampleFormControlTextarea8");
  var formurl= document.getElementById("exampleFormControlTextarea10");

  formid.value = id;
  formorder.value = order;
  formname.value = name;
  formurl.value = url;
  formfilename.value = filename;
  formfilename2.value = filename2;
  $("#exampleModal3").modal("show");
}

function editmodal(id, title) {
  // //console.log("test");
  // //console.log(id);
  // var filename = document.getElementById("filename-" + id).innerText;
  // var name = document.getElementById("name-" + id).innerText;
  // var order = document.getElementById("order-" + id).innerText;

  // //console.log(filename);
  // //console.log(name);
  // //console.log(order);
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

function initblog() {
  const xhttp = new XMLHttpRequest();
  var string =
    "https://www.googleapis.com/blogger/v3/blogs/1732826187557117921/posts?key=AIzaSyC7NA9vDhkVtk4lWisJxGW--fYXLIeM__w&fetchImages=true&maxResults=500";

  // //console.log(string);
  xhttp.open("GET", string, true);

  xhttp.onload = function () {
    var data = JSON.parse(this.responseText);
    if (data.length !== 0) {
      // const blogposthtml = document.getElementById("postlist");

      // blogposthtml.innerHTML = "";
      const table = document.getElementById("recommendationtable");

      for (var i = 0; i < data.items.length; i++) {
        var date = new Date(data.items[i].published);

        var day = date.getDate();
        var year = date.getFullYear();
        const months = [
          "January",
          "February",
          "March",
          "April",
          "May",
          "June",
          "July",
          "August",
          "September",
          "October",
          "November",
          "December",
        ];
        var longmonth = months[date.getMonth()];
        // //console.log(data.items[i].images[0].url);
        // var content2 = strip_tags(data.items[i].content, "<p>");
        var addbutton =
          "" +
          '<form action="edit-index.php?addrecommend#recommendcard" method="post" enctype="multipart/form-data">' +
          '    <input   name="name" hidden value="' +
          data.items[i].title +
          '"></input>' +
          '    <input   name="image2" hidden value="' +
          removeHttp(data.items[i].images[0].url) +
          '"></input>' +
          '    <input   name="postid" hidden value="' +
          data.items[i].id +
          '"></input>' +
          '    <button class="btn btn-primary" type="submit" name="saverecommendation"><i class=\'fas fa-plus\'></i></button>' +
          "</form>" +
          "";
        var tablerow =
          "<tr>" +
          "<th> " +
          i +
          "</th>" +
          "<th> " +
          data.items[i].title +
          "</th>" +
          "<th> " +
          data.items[i].id +
          "</th>" +
          "<th> " +
          addbutton +
          "</th>" +
          "</tr";
        table.insertAdjacentHTML("beforeend", tablerow);
        // $("#newrecommendation").modal("show");
      }
      // //console.log(data);
      $(document).ready(function () {
        $("#dataTable3").DataTable({
          ordering: false,
        });
      });
    }
  };
  xhttp.send();

}
var toastbody = document.getElementById("toast-body");
$("#toast11").hide();
function toastupdate(body) {

  //console.log('test');
  
  const toastLiveExample = document.getElementById('liveToast')

  const toast = new bootstrap.Toast(toastLiveExample)


  var toastbody = document.getElementById("toast-body");
  toastbody.innerHTML = body;
  $(".toast").toast("show");
  $("#toast11").toast("show");

  toast.show()

}


$('#table1').on('show.bs.collapse', function () {
  document.getElementById("table1a").innerHTML =
  '<i class="fas fa-chevron-up"></i>';
  window.localStorage.setItem("indextable1collapse", false); // saves with no expiration
})

$('#table1').on('hide.bs.collapse', function () {
  document.getElementById("table1a").innerHTML =
  '<i class="fas fa-chevron-down"></i>';
  window.localStorage.setItem("indextable1collapse", true); // saves with no expiration
})

$('#table2').on('show.bs.collapse', function () {
  document.getElementById("table2a").innerHTML =
  '<i class="fas fa-chevron-up"></i>';
  window.localStorage.setItem("indextable2collapse", false); // saves with no expiration
})

$('#table2').on('hide.bs.collapse', function () {
  document.getElementById("table2a").innerHTML =
  '<i class="fas fa-chevron-down"></i>';
  window.localStorage.setItem("indextable2collapse", true); // saves with no expiration
})

$('#table3').on('show.bs.collapse', function () {
  document.getElementById("table3a").innerHTML =
  '<i class="fas fa-chevron-up"></i>';
  window.localStorage.setItem("indextable3collapse", false); // saves with no expiration
})

$('#table3').on('hide.bs.collapse', function () {
  document.getElementById("table3a").innerHTML =
  '<i class="fas fa-chevron-down"></i>';
  window.localStorage.setItem("indextable3collapse", true); // saves with no expiration
})

$('#table4').on('show.bs.collapse', function () {
  document.getElementById("table4a").innerHTML =
  '<i class="fas fa-chevron-up"></i>';
  window.localStorage.setItem("indextable4collapse", false); // saves with no expiration
})

$('#table4').on('hide.bs.collapse', function () {
  document.getElementById("table4a").innerHTML =
  '<i class="fas fa-chevron-down"></i>';
  window.localStorage.setItem("indextable4collapse", true); // saves with no expiration
})


$('#table5').on('show.bs.collapse', function () {
  document.getElementById("table5a").innerHTML =
  '<i class="fas fa-chevron-up"></i>';
  window.localStorage.setItem("indextable5collapse", false); // saves with no expiration
})

$('#table5').on('hide.bs.collapse', function () {
  document.getElementById("table5a").innerHTML =
  '<i class="fas fa-chevron-down"></i>';
  window.localStorage.setItem("indextable5collapse", true); // saves with no expiration
})

$('#table6').on('show.bs.collapse', function () {
  document.getElementById("table6a").innerHTML =
  '<i class="fas fa-chevron-up"></i>';
  window.localStorage.setItem("indextable6collapse", false); // saves with no expiration
})

$('#table6').on('hide.bs.collapse', function () {
  document.getElementById("table6a").innerHTML =
  '<i class="fas fa-chevron-down"></i>';
  window.localStorage.setItem("indextable6collapse", true); // saves with no expiration
})