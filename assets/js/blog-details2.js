blogdetails();
recentpost();
function wrap(el, wrapper) {
  el.parentNode.insertBefore(wrapper, el);
  wrapper.appendChild(el);
}

function escapeHtml(text) {
  var map = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': "&quot;",
    "'": "&#039;",
  };

  return text.replace(/[&<>"']/g, function (m) {
    return map[m];
  });
}

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

function getBlogView(postid) {
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      // console.log(this.responseText);
      var viewshtml = document.getElementById("postidview-" + postid);
      if (this.responseText == 0) {
        viewshtml.innerHTML = "";
      } else {
        viewshtml.innerHTML =
          '<i class="bi bi-bar-chart-fill"></i>' + this.responseText;
        // var eachviews2 = this.responseText;
      }
    }
  };
  xmlhttp.open("GET", "admin/functions.php?postidviews=" + postid, true);
  xmlhttp.send();
}

function updateBlogView(postid){
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      // console.log(this.responseText);
      // var viewshtml = document.getElementById("postidview-" + postid);
      // if (this.responseText == -1) {
      //   viewshtml.innerHTML = "";
      // } else {
      //   viewshtml.innerHTML =
      //     '<i class="bi bi-bar-chart-fill"></i>' + this.responseText;
      // }
      //console.log(this.responseText);
    }
  };
  xmlhttp.open("GET", "admin/functions.php?postidviewsupdate=" + postid, true);
  xmlhttp.send();
}

function blogdetails() {
  var postid = document.getElementById("hidid").innerText;
  updateBlogView(postid);

  // console.log(postid);
  var postidstring = postid;
  // console.log(postidstring);
  const xhttp = new XMLHttpRequest();
  xhttp.open(
    "GET",
    "https://www.googleapis.com/blogger/v3/blogs/1732826187557117921/posts/" +
      postidstring +
      "?key=AIzaSyC7NA9vDhkVtk4lWisJxGW--fYXLIeM__w&fetchImages=true",
    true
  );

  //   console.log(key);

  xhttp.onload = function () {
    var data = JSON.parse(this.responseText);
    if (data.length !== 0) {
      //console.log(data);
      //console.log(imageurlblog)

      var date = new Date(data.published);

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
      // var content = data.items[i].content;

      // const greeting = limit('Hello Marcus', 6)
      // var content3 = limit(content2, 120);
      console.log(data);

      const blogposthtml = document.getElementById("article");
      // var html =
      //   '<div class="col-lg-6">' +
      //   '  <article class="d-flex flex-column">' +
      //   '    <div class="post-img text-center">' +
      //   '      <img src="' +
      //   data.items[i].images[0].url +
      //   '" alt="" class="img-fluid ">' +
      //   "    </div>" +
      //   '    <h2 class="title">' +
      //   '      <a href="' +
      //   data.items[i].url +
      //   '">' +
      //   data.items[i].title +
      //   "        </a>" +
      //   "    </h2>" +
      //   '    <div class="meta-top">' +
      //   "      <ul>" +
      //   '        <li class="d-flex align-items-center"><i class="bi bi-clock"></i>' +
      //   day +
      //   " " +
      //   longmonth +
      //   ", " +
      //   year +
      //   "</li>" +
      //   "      </ul>" +
      //   "    </div>" +
      //   '    <div class="content" id="content' +
      //   i +
      //   '">' +
      //   "" +
      //   "........</div><br/>" +
      //   '    <div class="read-more mt-auto align-self-end">' +
      //   '      <a href="blog-details.php?key=' +
      //   data.items[i].id +
      //   '">Read More</a>' +
      //   "    </div>" +
      //   "  </article>" +
      //   "</div>";
      var labelhtml = "";
      if (data.labels) {
        data.labels.forEach((label) => {
          labelhtml = labelhtml + "<li>" + label + "</li>";
        });
      }
      // console.log(labelhtml);

      var html =
        "" +
        // '<meta itemprop="description" content="'+ data.title+'">'+
        // '<meta itemprop="image" content="'+ imageurlblog+'">'+
        // '<meta itemprop="image_url" content="'+ imageurlblog+'">'+
        // '<meta itemprop="thumbnailUrl" content="'+ imageurlblog+'">'+
        // '<meta itemprop="url" content="'+document.location + '">'+
        '              <h2 class="title" itemprop="name">' +
        data.title +
        "</h2>" +
        '              <div class="meta-top">' +
        "                <ul>" +
        '                  <li class="d-flex align-items-center"><i class="bi bi-clock"></i> ' +
        day +
        " " +
        longmonth +
        " " +
        year +
        "</li>" +
        '<li class="d-flex align-items-center" id="postidview-' +
        data.id +
        '">Number</li>' +
        "                </ul>" +
        "              </div><!-- End meta top -->" +
        '              <div class="content" id="content" itemprop="articleBody"><div class="row">' +
        "" +
        "              </div></div><!-- End post content -->" +
        '              <div class="meta-bottom">' +
        '                <i class="bi bi-tags"></i>' +
        '                <ul class="tags">' +
        labelhtml +
        "                </ul>" +
        "              </div><!-- End meta bottom -->";
        
        // document.title = "KL The Guide - " + data.title;
        // document.querySelector('meta[name="description"]').setAttribute("content", data.title);
        document.getElementById("blogbreadcrumbs").innerHTML = data.title;
        getBlogView(data.id);
      blogposthtml.insertAdjacentHTML("beforeend", html);
      var newDescription = limit(strip_tags(data.content,''),150);

      // var pubtimemeta = document.createElement('meta');  pubtimemeta.setAttribute('property', 'article:published_time');  pubtimemeta.content = data.published;  document.getElementsByTagName('head')[0].appendChild(pubtimemeta);

      // var descmeta = document.createElement('meta');  descmeta.setAttribute('property', 'og:description');  descmeta.content = data.title;  document.getElementsByTagName('head')[0].appendChild(descmeta);

      // var titlemeta = document.createElement('meta');  titlemeta.setAttribute('property', 'og:title');  titlemeta.content = data.title;  document.getElementsByTagName('head')[0].appendChild(titlemeta);
      // var typemeta = document.createElement('meta'); typemeta.setAttribute('property', 'og:type');  typemeta.content = "article";  document.getElementsByTagName('head')[0].appendChild(typemeta);

      // var imageurl = document.createElement('meta');  imageurl.setAttribute('property', 'og:image');  imageurl.content = imageurlblog;  document.getElementsByTagName('head')[0].appendChild(imageurl);

      // var link = document.createElement('meta');  link.setAttribute('property', 'og:url');  link.content = document.location;  document.getElementsByTagName('head')[0].appendChild(link);
      // console.log(i);
      // console.log(escapeHtml(data.items[i].content));

      //   var divremove = document.getElementsByTagName("img");
      //   console.log(divremove.innerHTML);
      document.getElementById("content").innerHTML = data.content;
      const divaddclass = document
        .getElementById("content")
        .querySelectorAll("img");
      divaddclass.forEach((div) => {
        div.classList.add("img-fluid");
      });
    }
  };

  xhttp.send();
}
function recentpost() {
  const xhttp = new XMLHttpRequest();
  var string =
    "https://www.googleapis.com/blogger/v3/blogs/1732826187557117921/posts?key=AIzaSyC7NA9vDhkVtk4lWisJxGW--fYXLIeM__wz&fetchImages=true&maxResults=5";

  xhttp.open("GET", string, true);

  xhttp.onload = function () {
    var data = JSON.parse(this.responseText);
    if (data.length !== 0) {
      nextpagetoken = data.nextPageToken;
      const recenthtml = document.getElementById("recentpost");

      recenthtml.innerHTML = "";

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


        var html =
          ' <div class="post-item mt-3">' +
          '                    <img src="'+ data.items[i].images[0].url+'" alt="" class="flex-shrink-0" loading="lazy">' +
          "                    <div>" +
          '                      <h4><a href="blog-details.php?postid='+data.items[i].id +'">'+limit(data.items[i].title,30) + '...</a></h4>' +
          '                      <time >'+ day+ ' '+ longmonth+ ' '+ year +'</time>' +
          "                    </div>" +
          "                  </div>";

        recenthtml.insertAdjacentHTML("beforeend", html);

      }
    }
  };
  xhttp.send();
}
