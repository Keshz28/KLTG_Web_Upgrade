function removeHttp(url) {
    return url.replace(/^https?:\/\//, "");
  }
  
/*   function editmodalev(event_id) {
    // Populate the modal fields with the data from the table
    var name = decodeURIComponent($('#nameev-' + event_id).text());
    var location = decodeURIComponent($('#locationev-' + event_id).text());
    var locationurl = decodeURIComponent($('#locationurlev-' + event_id).text());
    var content = decodeURIComponent($('#contentev-' + event_id).text());
    var hours = decodeURIComponent($('#hoursev-' + event_id).text());
    var phone = decodeURIComponent($('#phoneev-' + event_id).text());
    var day = decodeURIComponent($('#dayev-' + event_id).text());
    var month = decodeURIComponent($('#monthev-' + event_id).text());
    var year = decodeURIComponent($('#yearev-' + event_id).text());
    var category = decodeURIComponent($('#categoryev-' + event_id).text());
    var website = decodeURIComponent($('#websiteev-' + event_id).text());
    var imagename = decodeURIComponent($('#filenameev-' + event_id).text());
    var order = decodeURIComponent($('#orderev-' + event_id).text());

    // Populate form fields with the retrieved data
    document.getElementById("event_id").value = event_id;
    document.getElementById("titleev").value = title;
    document.getElementById("locationev").value = location;
    document.getElementById("locationurlev").value = locationurl;
    document.getElementById("contentev").value = content;
    document.getElementById("orderev").value = order;
    document.getElementById("imageev").value = image;
    document.getElementById("hoursev").value = hours;
    document.getElementById("phoneev").value = phone;
    document.getElementById("dayev").value = day;
    document.getElementById("monthev").value = month;
    document.getElementById("yearev").value = year;
    document.getElementById("websiteev").value = website;
    document.getElementById("categoryev").value = category;

    // Show the modal
    $("#editevmodal").modal("show");
}
 */

  
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

function editmodalev(event_id) {
  // Get the modal element
  var modal = $('#editevmodal');

  // Populate the modal fields with the data from the table
  var name = $('#nameev-' + event_id).text();
  var location = $('#locationev-' + event_id).text();
  var locationurl = $('#locationurlev-' + event_id).text();
  var content = $('#contentev-' + event_id).text();
  var content2 = $('#content2ev-' + event_id).text();
  var hours = $('#hoursev-' + event_id).text();
  var phone = $('#phoneev-' + event_id).text();
  var day = $('#dayev-' + event_id).text();
  var month = $('#monthev-' + event_id).text();
  var year = $('#yearev-' + event_id).text();
  var category = $('#categoryev-' + event_id).text();
  var website = $('#websiteev-' + event_id).text();
  var imagename = $('#filenameev-' + event_id).text(); 
  var order = $('#orderev-' + event_id).text(); 
  var facebook = $('#facebookev-' + event_id).text();
  var instagram = $('#instagramev-' + event_id).text();
  var tiktok = $('#tiktokev-' + event_id).text();
  var youtube = $('#youtubeev-' + event_id).text();
  var twitter = $('#twitterev-' + event_id).text(); 

  // Set the values in the modal form fields
  modal.find('#titleev').val(name);
  modal.find('#locationev').val(location);
  modal.find('#locationurlev').val(locationurl);
  modal.find('#contentev').val(content);
  modal.find('#content2ev').val(content2);
  modal.find('#hoursev').val(hours);
  modal.find('#phoneev').val(phone);
  modal.find('#dayev').val(day);
  modal.find('#monthev').val(month);
  modal.find('#yearev').val(year);
  modal.find('#categoryev').val(category);
  modal.find('#websiteev').val(website);
  modal.find('#event_id').val(event_id);
  modal.find('#imagenameev').val(imagename);
  modal.find('#orderev').val(order);
  modal.find('#facebookev').val(facebook); 
  modal.find('#instagramev').val(instagram); 
  modal.find('#tiktokev').val(tiktok); 
  modal.find('#youtubeev').val(youtube); 
  modal.find('#twitterev').val(twitter); 
  // Show the modal
  modal.modal('show');
}