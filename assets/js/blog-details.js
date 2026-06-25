// Blog article page (blog-details.php)
// The post is fetched through the fetch_blogger.php proxy.

blogdetails();

const MONTHS = [
  "January", "February", "March", "April", "May", "June",
  "July", "August", "September", "October", "November", "December",
];

function strip_tags(input, allowed) {
  allowed = (
    ((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []
  ).join(""); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
  var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
    commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
  return input.replace(commentsAndPhpTags, "").replace(tags, function ($0, $1) {
    return allowed.indexOf("<" + $1.toLowerCase() + ">") > -1 ? $0 : "";
  });
}

function limit(string = "", limit = 0) {
  return string.substring(0, limit);
}

function formatDate(published) {
  var date = new Date(published);
  return date.getDate() + " " + MONTHS[date.getMonth()] + " " + date.getFullYear();
}

function getBlogView(postid) {
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      var viewshtml = document.getElementById("postidview-" + postid);
      if (!viewshtml) return;
      if (this.responseText == 0) {
        viewshtml.innerHTML = "";
      } else {
        viewshtml.innerHTML =
          '<i class="bi bi-bar-chart-fill"></i>' + this.responseText;
      }
    }
  };
  xmlhttp.open("GET", "admin/functions.php?postidviews=" + postid, true);
  xmlhttp.send();
}

function updateBlogView(postid) {
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.open("GET", "admin/functions.php?postidviewsupdate=" + postid, true);
  xmlhttp.send();
}

function blogdetails() {
  var postid = document.getElementById("hidid").innerText;
  updateBlogView(postid);

  const xhttp = new XMLHttpRequest();
  xhttp.open("GET", "fetch_blogger.php?postid=" + postid, true);

  xhttp.onload = function () {
    var data = JSON.parse(this.responseText);
    if (data.length === 0 || data.error) return;

    const article = document.getElementById("article");
    var shareUrl = window.location.href;

    var labelhtml = "";
    if (data.labels) {
      data.labels.forEach((label) => {
        labelhtml += '<i class="bi bi-tags"></i><li>' + label + "</li> ";
      });
    }

    var html =
      '<h2 class="title">' + data.title + "</h2>" +
      '<div class="meta-top">' +
      "  <ul>" +
      '    <li class="d-flex align-items-center"><i class="bi bi-clock"></i> ' + formatDate(data.published) + "</li>" +
      '    <li class="d-flex align-items-center" id="postidview-' + data.id + '"></li>' +
      "  </ul>" +
      "</div>" +
      '<div class="meta-top">' +
      '  <div class="social-links d-flex justify-content-center justify-content-lg-start order-first order-lg-last mb-3 mb-lg-0">' +
      '    <a href="mailto:?subject=Check Out This Article&body=' + shareUrl + '" class="instagram"><i class="bi bi-share-fill" style="color:var(--color-primary)"></i></a>' +
      '    <a href="https://www.facebook.com/sharer/sharer.php?u=' + shareUrl + '" class="facebook"><i class="bi bi-facebook" style="color:#4267B2"></i></a>' +
      '    <a href="https://twitter.com/share?text=Check Out This Article&url=' + shareUrl + '" class="twitter"><i class="fa-brands fa-x-twitter" style="color:#1DA1F2"></i></a>' +
      '    <a href="whatsapp://send?text=' + shareUrl + '" class="whatsapp"><i class="bi bi-whatsapp" style="color:#25D366"></i></a>' +
      "  </div>" +
      "</div>" +
      '<div class="content" id="content"><div class="row"></div></div>' +
      '<div class="meta-bottom">' +
      '  <ul class="tags">' + labelhtml + "</ul>" +
      "</div>";

    document.getElementById("blogbreadcrumbs").innerHTML = data.title;
    getBlogView(data.id);
    article.insertAdjacentHTML("beforeend", html);

    document.getElementById("content").innerHTML = data.content;
    document.getElementById("content").querySelectorAll("img").forEach((img) => {
      img.classList.add("img-fluid");
    });

    renderRelated(data.labels, data.id);
  };

  xhttp.send();
}

// ---- Related posts (matched by shared tag) -----------------------------

function renderRelated(labels, currentId) {
  var section = document.getElementById("related-posts");
  var list = document.getElementById("relatedlist");
  if (!section || !list || !labels || !labels.length) return;

  const xhttp = new XMLHttpRequest();
  xhttp.open("GET", "fetch_blogger.php?labels=" + encodeURIComponent(labels[0]), true);
  xhttp.onload = function () {
    var data = JSON.parse(this.responseText);
    if (!data || !data.items) return;

    var count = 0;
    for (var i = 0; i < data.items.length && count < 3; i++) {
      var item = data.items[i];
      if (String(item.id) === String(currentId)) continue;

      var img = item.images && item.images[0] ? item.images[0].url : "assets/img/default.jpg";
      list.insertAdjacentHTML(
        "beforeend",
        '<div class="col-md-4">' +
        '  <a class="related-card" href="blog-details.php?postid=' + item.id + '">' +
        '    <div class="related-img"><img src="' + img + '" alt="" loading="lazy"></div>' +
        '    <div class="related-body">' +
        "      <h4>" + item.title + "</h4>" +
        '      <time><i class="bi bi-clock"></i> ' + formatDate(item.published) + "</time>" +
        "    </div>" +
        "  </a>" +
        "</div>"
      );
      count++;
    }

    if (count > 0) section.style.display = "block";
  };
  xhttp.send();
}

// ---- Reading progress bar ----------------------------------------------

(function () {
  var bar = document.getElementById("reading-progress");
  if (!bar) return;
  var fill = bar.querySelector("span");

  window.addEventListener("scroll", function () {
    var docEl = document.documentElement;
    var scrollTop = window.pageYOffset || docEl.scrollTop;
    var max = docEl.scrollHeight - docEl.clientHeight;
    fill.style.width = (max > 0 ? (scrollTop / max) * 100 : 0) + "%";
  });
})();
