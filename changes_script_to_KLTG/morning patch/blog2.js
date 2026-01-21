var prevpagetoken = "";
var prevpagetokenarray = [];
var prevpageelement;
var nextpagetoken = "";
var nextpageelement;
var nextPageToken2;
var paginationnum = 1;
var newtoken;

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
      if (viewshtml) {
        if (this.responseText == 0) {
          viewshtml.innerHTML = "";
        } else {
          viewshtml.innerHTML =
            '<i class="bi bi-bar-chart-fill"></i>' + this.responseText;
          // var eachviews2 = this.responseText;
        }
      }
    }
  };
  xmlhttp.open("GET", "admin/functions.php?postidviews=" + postid, true);
  xmlhttp.send();
}

function blogpost() {
  const xhttp = new XMLHttpRequest();
  var labels = [];
  var tags = document.querySelectorAll("#tags");

  tags.forEach((tag) => {
    labels.push(tag.innerHTML);
  });

  console.log(labels);

  // Use proxy instead of hardcoded Google API
  xhttp.open(
    "GET",
    "fetch_blogger.php?fetchImages=true&maxResults=4&labels=" + encodeURIComponent(labels.join(",")),
    true
  );

  xhttp.onload = function () {
    // Handle response here if needed
    var data = JSON.parse(this.responseText);
    console.log(data);
  };

  xhttp.send();


  xhttp.onload = function () {
    var data = JSON.parse(this.responseText);
    if (data.length !== 0) {
      nextpagetoken = data.nextPageToken;

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
        // var content = data.items[i].content;
        var content2 = strip_tags(data.items[i].content, "<p>");

        const blogposthtml = document.getElementById("postlist");
        var html =
          '<div class="col-lg-6">' +
          '  <article class="d-flex flex-column">' +
          '    <div class="post-img text-center">' +
          '      <img src="' +
          data.items[i].images[0].url +
          '" alt="" class="img-fluid " loading="lazy">' +
          "    </div>" +
          '    <h2 class="title">' +
          '      <a href="blog-details.php?postid=' +
          data.items[i].id +
          '">' +
          data.items[i].title +
          "        </a>" +
          "    </h2>" +
          '    <div class="meta-top">' +
          "      <ul>" +
          '        <li class="d-flex align-items-center"><i class="bi bi-clock"></i>' +
          day +
          " " +
          longmonth +
          ", " +
          year +
          "</li>" +
          "      </ul>" +
          "    </div>" +
          '    <div class="content" id="content' +
          i +
          '">' +
          "" +
          "........</div><br/>" +
          '    <div class="read-more mt-auto align-self-end">' +
          '      <a href="blog-details.php?postid=' +
          data.items[i].id +
          '">Read More</a>' +
          "    </div>" +
          "  </article>" +
          "</div>";

        blogposthtml.insertAdjacentHTML("beforeend", html);

        document.getElementById("content" + i).innerHTML =
          data.items[i].content;

        const divremove = document.querySelectorAll(".separator");
        divremove.forEach((div) => {
          div.remove();
        });

        var string = document.getElementById("content" + i).innerHTML;

        var stripped = strip_tags(string, "<p>");
        var str = stripped.split("<p>​</p>").join("");
        console.log(str);

        document.getElementById("content" + i).innerHTML = limit(str, 350);
      }

      var prevpageelement = document.getElementById("prev");
      var nextpageelement = document.getElementById("next");

      prevpageelement.style.display = "none";
      nextpageelement.style.display = "block";

      prevpagetokenarray.push("");
    }
  };
  xhttp.send();
}

function activebutton(tage) {
    
  //200126 
  document.getElementById("search_blog").value = '';
  const divremove = document.querySelectorAll("#tags-act");
  divremove.forEach((div) => {
    div.id = "tags";
    div.style.border = "1px solid rgba(var(--color-secondary-light-rgb), 0.8)";
  });
  if (tage.id == "tags") {
    tage.id = "tags-act";
    tage.style.border = "1px solid var(--color-primary)";
    // tage.classList.toggle="test-toggle";
  }
  // document.getElementById("exampleModal").modal("hide");
  $("#exampleModal").modal("hide");
  console.log("done");
  paginationnum = 1;


  initblog3();
}

function gettags() {
  const xhttp = new XMLHttpRequest();

  // Use your proxy instead of hitting Google directly
  var string = "fetch_blogger.php?maxResults=500";

  xhttp.open("GET", string, true);
  // console.log(labels);
  xhttp.onload = function () {
    var data = JSON.parse(this.responseText);
    if (data.length !== 0) {
      var testlabes = [];

      const taghtml = document.getElementById("tagdiv");
      const taghtml2 = document.getElementById("tagdiv2");

      for (var i = 0; i < data.items.length; i++) {
        if (data.items[i].labels) {
          const labelstest = data.items[i].labels;

          labelstest.forEach((div) => {
            testlabes.push(div);
          });
        }
      }

      var elementtagadd;
      var elementtagadd2;

      var testlabes2 = [...new Set(testlabes)];
      testlabes2.forEach((div2) => {
        elementtagadd =
          '<li><a href="#' +
          encodeURI(div2) +
          '" id="tags" onclick="activebutton(this)" role="button">' +
          div2 +
          "</a></li>";
        elementtagadd2 =
          '<a href="#' +
          encodeURI(div2) +
          '" class="btn btn-primary mx-1 my-1" id="tags" onclick="activebutton(this)">' +
          div2 +
          "</a>";
        taghtml.insertAdjacentHTML("beforeend", elementtagadd);
        taghtml2.insertAdjacentHTML("beforeend", elementtagadd2);
      });

      var tagspinner = document.getElementById("tagspinner");
      tagspinner.classList.add("d-none");
    }
  };
  xhttp.send();
}


function initblog() {
  const xhttp = new XMLHttpRequest();

  // Start with proxy instead of Blogger API
  var string = "fetch_blogger.php?fetchImages=true&maxResults=4";

  if (nextpagetoken) {
    console.log(nextpagetoken);
    var tokenfind = "&pageToken=" + encodeURIComponent(nextpagetoken);
    string = string + tokenfind;
  }

  var labels = [];
  var tags = document.querySelectorAll("#tags-act");
  if (typeof tags != "undefined" && tags != null) {
    tags.forEach((tag) => {
      labels.push(tag.innerHTML);
    });
    if (labels.length > 0) {
      var labelsfind = "&labels=" + encodeURIComponent(labels.join(","));
      string = string + labelsfind;
    }
  }

  // console.log(string);
  xhttp.open("GET", string, true);

  xhttp.onload = function () {
    var data = JSON.parse(this.responseText);
    if (data.length !== 0) {
      nextpagetoken = data.nextPageToken;
      const blogposthtml = document.getElementById("postlist");

      blogposthtml.innerHTML = "";

      for (var i = 0; i < data.items.length; i++) {
        var date = new Date(data.items[i].published);

        var day = date.getDate();
        var year = date.getFullYear();
        const months = [
          "January","February","March","April","May","June",
          "July","August","September","October","November","December",
        ];
        var longmonth = months[date.getMonth()];
        var content2 = strip_tags(data.items[i].content, "<p>");

        var html =
          '<div class="col-lg-6">' +
          '  <article class="d-flex flex-column">' +
          '    <div class="post-img text-center">' +
          '      <img src="' +
          data.items[i].images[0].url +
          '" alt="" class="img-fluid " loading="lazy">' +
          "    </div>" +
          '    <h2 class="title">' +
          '      <a href="blog-details.php?postid=' +
          data.items[i].id +
          '">' +
          data.items[i].title +
          "</a>" +
          "    </h2>" +
          '    <div class="meta-top">' +
          "      <ul>" +
          '        <li class="d-flex align-items-center"><i class="bi bi-clock"></i>' +
          day +
          " " +
          longmonth +
          " " +
          year +
          "</li>" +
          '<li class="d-flex align-items-center" id="postidview-' +
          data.items[i].id +
          '"></li>' +
          "      </ul>" +
          "    </div>" +
          '    <div class="content" id="content' +
          i +
          '">........</div><br/>' +
          '    <div class="read-more mt-auto align-self-end">' +
          '      <a href="blog-details.php?postid=' +
          data.items[i].id +
          '">Read More</a>' +
          "    </div>" +
          "  </article>" +
          "</div>";

        blogposthtml.insertAdjacentHTML("beforeend", html);

        document.getElementById("content" + i).innerHTML =
          data.items[i].content;

        const divremove = document.querySelectorAll(".separator");
        divremove.forEach((div) => {
          div.remove();
        });

        var string = document.getElementById("content" + i).innerHTML;
        var stripped = strip_tags(string, "<p>");
        var str = stripped.split("<p></p>").join("");
        document.getElementById("content" + i).innerHTML =
          limit(str, 250) + ".....";

        getBlogView(data.items[i].id);

        var richresult1 = [
          {
            "@context": "https://schema.org",
            "@type": "BlogPosting",
            description: limit(data.items[i].title, 30),
            headline: limit(data.items[i].title, 30),
            image: data.items[i].images[0].url,
            datePublished: data.items[i].published,
            dateModified: data.items[i].published,
            author: [
              {
                "@type": "Organization",
                name: "Bluedale",
                url: "https://bluedale.com.my/",
              },
            ],
          },
        ];

        let usersToString = JSON.stringify(richresult1);
        var richresult2 = document.createElement("script");
        richresult2.type = "application/ld+json";
        richresult2.innerText = usersToString;
        document.head.appendChild(richresult2);
      }

      var prevpageelement = document.getElementById("prev");
      var nextpageelement = document.getElementById("next");
      if (paginationnum == 1) {
        prevpageelement.style.display = "none";
        nextpageelement.style.display = "block";
        prevpagetokenarray.push("");
        if (!data.nextPageToken) {
          nextpageelement.style.display = "none";
          prevpageelement = [];
          document.getElementById("pagination").innerHTML = paginationnum;
        }
      }
    }
  };
  xhttp.send();
}


function nextpageblog2() {
  initblog();

  var prevpageelement = document.getElementById("prev");
  var nextpageelement = document.getElementById("next");
  console.log(paginationnum);

  paginationnum++;
  console.log(paginationnum);

  prevpagetoken = nextpagetoken;
  prevpagetokenarray.push(prevpagetoken);
  nextpagetoken = nextPageToken2;

  prevpageelement.style.display = "block";
  nextpageelement.style.display = "block";
  document.getElementById("pagination").innerHTML = paginationnum;

  console.log(prevpagetokenarray);
}
function prevpageblog2() {
  prevpagetokenarray.splice(-1);

  var prevpageelement = document.getElementById("prev");
  var nextpageelement = document.getElementById("next");
  paginationnum = paginationnum - 1;

  if (paginationnum == 1) {
    prevpageelement.style.display = "none";
    nextpageelement.style.display = "block";
  } else {
    prevpageelement.style.display = "block";
    nextpageelement.style.display = "block";
  }

  nextpagetoken = prevpagetokenarray[prevpagetokenarray.length - 1];
  document.getElementById("pagination").innerHTML = paginationnum;

  initblog();
}

function recentpost() {
  const xhttp = new XMLHttpRequest();

  // Instead of calling Google API directly, call your PHP proxy
  const string = "fetch_blogger.php?type=recent&maxResults=4";

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
          "January","February","March","April","May","June",
          "July","August","September","October","November","December",
        ];
        var longmonth = months[date.getMonth()];

        var html =
          ' <div class="post-item mt-3">' +
          '    <img src="' +
          data.items[i].images[0].url +
          '" alt="" class="flex-shrink-0" loading="lazy">' +
          "    <div>" +
          '      <h4><a href="blog-details.php?postid=' +
          data.items[i].id +
          '">' +
          limit(data.items[i].title, 30) +
          "...</a></h4>" +
          "      <time>" +
          day +
          " " +
          longmonth +
          " " +
          year +
          "</time>" +
          "    </div>" +
          "  </div>";

        recenthtml.insertAdjacentHTML("beforeend", html);
      }
    }
  };
  xhttp.send();
}


function init (pagination, tags) {
  var xmlhttp = new XMLHttpRequest();

  xmlhttp.onload = function () {
    var data = JSON.parse(this.responseText);

    console.log("Pages Total : " + Math.ceil(data[0].length / 4));
    console.log("Pages Num : " + pagination);
    console.log("Pages Left : ");
    console.log(Math.ceil(data[0].length / 4) - pagination);

    const blogpaginationid = document.getElementById("blog-paginationid");
    blogpaginationid.innerHTML = "";

    if (pagination == 1) {
      var nextpage = pagination + 1;
      if (tags) {
        var urltag = "&tags=" + tags;
      } else {
        var urltag = "";
      }
      var pagepagination =
        '<li  class="d-block active"><a href="blog2.php?page=' +
        pagination +
        urltag +
        '">' +
        pagination +
        "</a></li>";

      if (Math.ceil(data[0].length / 4) - 3 > 3) {
        for ($i = pagination + 1; $i < pagination + 3; $i++) {
          pagepagination +=
            '<li  class="d-block"><a href="blog2.php?page=' +
            $i +
            urltag +
            '">' +
            $i +
            "</a></li>";
        }
        pagepagination += "<li> </li>";
        pagepagination += "<li> </li>";

        if (pagination + 2 < Math.ceil(data[0].length / 4)) {
          pagepagination +=
            '<li  class="d-block"><a href="blog2.php?page=' +
            Math.ceil(data[0].length / 4) +
            urltag +
            '" >' +
            Math.ceil(data[0].length / 4) +
            "</a></li>";
        }
      }
      if (pagination + 1 <= Math.ceil(data[0].length / 4)) {
        pagepagination +=
          '<li id="next"><a href="blog2.php?page=' +
          nextpage +
          urltag +
          ' "><i class="bi bi-chevron-right"></i></a></li>';
      }

      blogpaginationid.insertAdjacentHTML("beforeend", pagepagination);
    } else {
      var nextpage = pagination + 1;
      var prevpage = pagination - 1;
      if (tags) {
        var urltag = "&tags=" + tags;
      } else {
        var urltag = "";
      }
      var pagepagination =
        '<li id="next"><a href="blog2.php?page=' +
        prevpage +
        urltag +
        ' "><i class="bi bi-chevron-left"></i></a></li>';

      pagepagination +=
        '<li  class="d-block active"><a href="blog2.php?page=' +
        pagination +
        urltag +
        '">' +
        pagination +
        "</a></li>";

      if (Math.ceil(data[0].length / 4) - 3 > 3) {
        for ($i = pagination + 1; $i < pagination + 3; $i++) {
          pagepagination +=
            '<li  class="d-block"><a href="blog2.php?page=' +
            $i +
            urltag +
            '">' +
            $i +
            "</a></li>";
        }
        pagepagination += "<li> </li>";
        pagepagination += "<li> </li>";

        if (pagination + 2 < Math.ceil(data[0].length / 4)) {
          pagepagination +=
            '<li  class="d-block"><a href="blog2.php?page=' +
            Math.ceil(data[0].length / 4) +
            urltag +
            '" >' +
            Math.ceil(data[0].length / 4) +
            "</a></li>";
        }
      }
      if (pagination + 1 <= Math.ceil(data[0].length / 4)) {
        pagepagination +=
          '<li id="next"><a href="blog2.php?page=' +
          nextpage +
          urltag +
          ' "><i class="bi bi-chevron-right"></i></a></li>';
      }

      blogpaginationid.insertAdjacentHTML("beforeend", pagepagination);
    }

    if (Math.ceil(data[0].length / 4) <= pagination) {
      if (data[0].length % 4) {
        // console.log("remainder " + (data[0].length % 4));
        for (
          let x = 4 * (pagination - 1);
          x <= 4 * (pagination - 1) - 1 + (data[0].length % 4);
          x++
        ) {
          startblog(data, x);
        }
      } else {
        for (
          let x = 4 * (pagination - 1);
          x <= 4 * (pagination - 1) - 1 + 4;
          x++
        ) {
          startblog(data, x);
        }
      }
    } else {
      // console.log("test");
      for (
        let x = 4 * (pagination - 1);
        x <= 4 * (pagination - 1) - 1 + 4;
        x++
      ) {
        startblog(data, x);
      }
    }
  };
  xmlhttp.open(
    "GET",
    "admin/functions.php?initblog=1&page=" + pagination + "&tags=" + tags,
    true
  );
  xmlhttp.send();
}

function getFirstImageFromPost(content) {
  if (!content) return null;

  // Create a temporary DOM element
  const tempDiv = document.createElement('div');
  tempDiv.innerHTML = content;

  // Find the first <img> inside
  const firstImg = tempDiv.querySelector('img');
  return firstImg ? firstImg.src : null;
}



//200126        
function searchbyText(text='') {

  const xhttp = new XMLHttpRequest();

  // Call your PHP proxy instead of Google API
  var string = "fetch_blogger_search.php?type=initblog3&maxResults=4";

  var hash = window.location.hash.slice(1);

  if (hash) {
    string += "&labels=" + encodeURIComponent(hash);
  }
  string += '&q=' +text;

  
  xhttp.onload = function () {
    var data = JSON.parse(this.responseText);
    
    document.getElementById('search_text_progress').parentNode.removeChild(document.getElementById('search_text_progress'));
    
    if (data.length !== 0) {

      const blogposthtml = document.getElementById("postlist");
      blogposthtml.innerHTML = "";
      for (var i = 0; i < data.items.length; i++) {

        const getFirstImage = getFirstImageFromPost(data.items[i]['content']);
        console.log(getFirstImage)
        
        var date = new Date(data.items[i].published);

        var day = date.getDate();
        var year = date.getFullYear();
        const months = [
          "January", "February", "March", "April", "May", "June",
          "July", "August", "September", "October", "November", "December",
        ];
        var longmonth = months[date.getMonth()];


        var html =
          '<div class="col-lg-6">' +
          '  <article class="d-flex flex-column">' +
          '    <div class="post-img text-center">' +
          '      <img src="' + getFirstImage + '" alt="" class="img-fluid" loading="lazy">' +
          "    </div>" +
          '    <h2 class="title">' +
          '      <a href="blog-details.php?postid=' + data.items[i].id + '">' + data.items[i].title + "</a>" +
          "    </h2>" +
          '    <div class="meta-top">' +
          "      <ul>" +
          '        <li class="d-flex align-items-center"><i class="bi bi-clock"></i>' +
          day + " " + longmonth + " " + year +
          "</li>" +
          '<li class="d-flex align-items-center" id="postidview-' + data.items[i].id + '"></li>' +
          "      </ul>" +
          '<ul id="tags3-' + data.items[i].id + '"></ul>' +
          "    </div>" +
          '    <div class="content" id="content' + data.items[i].id + '">...</div><br/>' +
          '    <div class="d-flex justify-content-between align-items-center mt-auto">' +
          '      <div class="social-links">' +
          '        <a href="mailto:?subject=Check Out This Article&body=https://kltheguide.com.my/blog-details.php?postid=' + data.items[i].id + '" class="instagram">' +
          '          <i class="bi bi-share-fill" style="color:var(--color-primary)"></i>' +
          "        </a>" +
          '        <a href="https://www.facebook.com/sharer/sharer.php?u=https://kltheguide.com.my/blog-details.php?postid=' + data.items[i].id + '" class="facebook">' +
          '          <i class="bi bi-facebook" style="color:#4267B2"></i>' +
          "        </a>" +
          '        <a href="https://twitter.com/share?text=Check Out This Article&url=https://kltheguide.com.my/blog-details.php?postid=' + data.items[i].id + '" class="twitter">' +
          '          <i class="fa-brands fa-x-twitter" style="color:#1DA1F2"></i>' +
          "        </a>" +
          '        <a href="whatsapp://send?text=https://kltheguide.com.my/blog-details.php?postid=' + data.items[i].id + '" class="whatsapp">' +
          '          <i class="bi bi-whatsapp" style="color:#25D366"></i>' +
          "        </a>" +
          "      </div>" +
          '      <div class="read-more">' +
          '        <a href="blog-details.php?postid=' + data.items[i].id + '">Read More</a>' +
          "      </div>" +
          "    </div>" +
          "  </article>" +
          "</div>";

        blogposthtml.insertAdjacentHTML("beforeend", html);

        document.getElementById("content" + data.items[i].id).innerHTML =
          data.items[i].content;

        const divremove = document.querySelectorAll(".separator");
        divremove.forEach((div) => div.remove());

        var labelhtml = "";
        if (data.items[i].labels) {
          data.items[i].labels.forEach((label) => {
            labelhtml += "<li><i class='bi bi-tags'></i>" + label + "</li>";
          });
          document.getElementById("tags3-" + data.items[i].id).innerHTML = labelhtml;
        }

        var string = document.getElementById("content" + data.items[i].id).innerHTML;
        var stripped = strip_tags(string, "<p>");
        var str = stripped.split("<p></p>").join("");

        document.getElementById("content" + data.items[i].id).innerHTML =
          limit(str, 250) + ".....";

        getBlogView(data.items[i].id);

        var richresult1 = [{
          "@context": "https://schema.org",
          "@type": "BlogPosting",
          description: limit(data.items[i].title, 30),
          headline: limit(data.items[i].title, 30),
          image: getFirstImage,
          datePublished: data.items[i].published,
          dateModified: data.items[i].published,
          author: [{ "@type": "Organization", name: "Bluedale", url: "https://bluedale.com.my/" }]
        }];

        let usersToString = JSON.stringify(richresult1);
        var richresult2 = document.createElement("script");
        richresult2.type = "application/ld+json";
        richresult2.innerText = usersToString;
        document.head.appendChild(richresult2);
      }


      document.getElementById('search_text_progress').parentNode.removeChild(document.getElementById('search_text_progress'));
    }

  };

  xhttp.open("GET", string, true);
  xhttp.send();

}

function initblog3() {
  const xhttp = new XMLHttpRequest();

  // Call your PHP proxy instead of Google API
  var string = "fetch_blogger.php?type=initblog3&maxResults=4";

  var hash = window.location.hash.slice(1);

  if (hash) {
    string += "&labels=" + encodeURIComponent(hash);
  }

  window.localStorage.setItem("nextpageToken", "");

  xhttp.onload = function () {
    var data = JSON.parse(this.responseText);
    if (data.length !== 0) {
      nextpagetoken = data.nextPageToken;
      window.localStorage.setItem("nextpageToken", nextpagetoken);

      const blogposthtml = document.getElementById("postlist");
      blogposthtml.innerHTML = "";

      for (var i = 0; i < data.items.length; i++) {
        var date = new Date(data.items[i].published);

        var day = date.getDate();
        var year = date.getFullYear();
        const months = [
          "January", "February", "March", "April", "May", "June",
          "July", "August", "September", "October", "November", "December",
        ];
        var longmonth = months[date.getMonth()];

        var html =
          '<div class="col-lg-6">' +
          '  <article class="d-flex flex-column">' +
          '    <div class="post-img text-center">' +
          '      <img src="' + data.items[i].images[0].url + '" alt="" class="img-fluid" loading="lazy">' +
          "    </div>" +
          '    <h2 class="title">' +
          '      <a href="blog-details.php?postid=' + data.items[i].id + '">' + data.items[i].title + "</a>" +
          "    </h2>" +
          '    <div class="meta-top">' +
          "      <ul>" +
          '        <li class="d-flex align-items-center"><i class="bi bi-clock"></i>' +
          day + " " + longmonth + " " + year +
          "</li>" +
          '<li class="d-flex align-items-center" id="postidview-' + data.items[i].id + '"></li>' +
          "      </ul>" +
          '<ul id="tags3-' + data.items[i].id + '"></ul>' +
          "    </div>" +
          '    <div class="content" id="content' + data.items[i].id + '">...</div><br/>' +
          '    <div class="d-flex justify-content-between align-items-center mt-auto">' +
          '      <div class="social-links">' +
          '        <a href="mailto:?subject=Check Out This Article&body=https://kltheguide.com.my/blog-details.php?postid=' + data.items[i].id + '" class="instagram">' +
          '          <i class="bi bi-share-fill" style="color:var(--color-primary)"></i>' +
          "        </a>" +
          '        <a href="https://www.facebook.com/sharer/sharer.php?u=https://kltheguide.com.my/blog-details.php?postid=' + data.items[i].id + '" class="facebook">' +
          '          <i class="bi bi-facebook" style="color:#4267B2"></i>' +
          "        </a>" +
          '        <a href="https://twitter.com/share?text=Check Out This Article&url=https://kltheguide.com.my/blog-details.php?postid=' + data.items[i].id + '" class="twitter">' +
          '          <i class="fa-brands fa-x-twitter" style="color:#1DA1F2"></i>' +
          "        </a>" +
          '        <a href="whatsapp://send?text=https://kltheguide.com.my/blog-details.php?postid=' + data.items[i].id + '" class="whatsapp">' +
          '          <i class="bi bi-whatsapp" style="color:#25D366"></i>' +
          "        </a>" +
          "      </div>" +
          '      <div class="read-more">' +
          '        <a href="blog-details.php?postid=' + data.items[i].id + '">Read More</a>' +
          "      </div>" +
          "    </div>" +
          "  </article>" +
          "</div>";

        blogposthtml.insertAdjacentHTML("beforeend", html);

        document.getElementById("content" + data.items[i].id).innerHTML =
          data.items[i].content;

        const divremove = document.querySelectorAll(".separator");
        divremove.forEach((div) => div.remove());

        var labelhtml = "";
        if (data.items[i].labels) {
          data.items[i].labels.forEach((label) => {
            labelhtml += "<li><i class='bi bi-tags'></i>" + label + "</li>";
          });
          document.getElementById("tags3-" + data.items[i].id).innerHTML = labelhtml;
        }

        var string = document.getElementById("content" + data.items[i].id).innerHTML;
        var stripped = strip_tags(string, "<p>");
        var str = stripped.split("<p></p>").join("");

        document.getElementById("content" + data.items[i].id).innerHTML =
          limit(str, 250) + ".....";

        getBlogView(data.items[i].id);

        var richresult1 = [{
          "@context": "https://schema.org",
          "@type": "BlogPosting",
          description: limit(data.items[i].title, 30),
          headline: limit(data.items[i].title, 30),
          image: data.items[i].images[0].url,
          datePublished: data.items[i].published,
          dateModified: data.items[i].published,
          author: [{ "@type": "Organization", name: "Bluedale", url: "https://bluedale.com.my/" }]
        }];

        let usersToString = JSON.stringify(richresult1);
        var richresult2 = document.createElement("script");
        richresult2.type = "application/ld+json";
        richresult2.innerText = usersToString;
        document.head.appendChild(richresult2);
      }

      if (data.nextPageToken === undefined) {
        removeInfiniteScroll();
      } else {
        var html5 =
          '<div class="spinner-border text-primary mx-auto" role="status" id="spin2">' +
          '<span class="sr-only"></span>' +
          "</div>";
        blogposthtml.insertAdjacentHTML("beforeend", html5);
      }
    }
  };

  xhttp.open("GET", string, true);
  xhttp.send();
}


function nextpageblog3() {
  const xhttp = new XMLHttpRequest();
  var spin2 = document.getElementById("spin2");
  if (spin2) {
    spin2.remove();
  }

  // Start building query params
  var params = "maxResults=4";

  var hash = window.location.hash.slice(1);
  if (hash) {
    params += "&labels=" + encodeURIComponent(hash);
  }

  var nextpageToken = window.localStorage.getItem("nextpageToken");
  if (nextpageToken) {
    params += "&pageToken=" + encodeURIComponent(nextpageToken);
  }

  xhttp.onload = function () {
    var data = JSON.parse(this.responseText);
    if (data.length !== 0) {
      nextpagetoken = data.nextPageToken;
      window.localStorage.setItem("nextpageToken", nextpagetoken);

      const blogposthtml = document.getElementById("postlist");

      for (var i = 0; i < data.items.length; i++) {
        var date = new Date(data.items[i].published);
        var day = date.getDate();
        var year = date.getFullYear();
        const months = [
          "January","February","March","April","May","June",
          "July","August","September","October","November","December"
        ];
        var longmonth = months[date.getMonth()];
        var content2 = strip_tags(data.items[i].content, "<p>");

        var labelhtml = "";
        if (data.items[i].labels) {
          data.items[i].labels.forEach((label) => {
            labelhtml += label;
          });
        }

        var html =
          '<div class="col-lg-6">' +
          '  <article class="d-flex flex-column">' +
          '    <div class="post-img text-center">' +
          '      <img src="' + data.items[i].images[0].url + '" alt="" class="img-fluid" loading="lazy">' +
          "    </div>" +
          '    <h2 class="title">' +
          '      <a href="blog-details.php?postid=' + data.items[i].id + '">' + data.items[i].title + "</a>" +
          "    </h2>" +
          '    <div class="meta-top">' +
          "      <ul>" +
          '        <li class="d-flex align-items-center"><i class="bi bi-clock"></i>' +
          day + " " + longmonth + " " + year +
          "</li>" +
          '<li class="d-flex align-items-center" id="postidview-' + data.items[i].id + '"></li>' +
          "      </ul>" +
          '<ul id="tags3-' + data.items[i].id + '"></ul>' +
          "    </div>" +
          '    <div class="content" id="content' + data.items[i].id + '">...</div><br/>' +

          // Flex container for social-links and read-more button
          '    <div class="d-flex justify-content-between align-items-center mt-auto">' +
          '      <div class="social-links">' +
          '        <a href="mailto:?subject=Check Out This Article&body=https://kltheguide.com.my/blog-details.php?postid=' + data.items[i].id + '" class="instagram">' +
          '          <i class="bi bi-share-fill" style="color:var(--color-primary)"></i>' +
          "        </a>" +
          '        <a href="https://www.facebook.com/sharer/sharer.php?u=https://kltheguide.com.my/blog-details.php?postid=' + data.items[i].id + '" class="facebook">' +
          '          <i class="bi bi-facebook" style="color:#4267B2"></i>' +
          "        </a>" +
          '        <a href="https://twitter.com/share?text=Check Out This Article&url=https://kltheguide.com.my/blog-details.php?postid=' + data.items[i].id + '" class="twitter">' +
          '          <i class="fa-brands fa-x-twitter" style="color:#1DA1F2"></i>' +
          "        </a>" +
          '        <a href="whatsapp://send?text=https://kltheguide.com.my/blog-details.php?postid=' + data.items[i].id + '" class="whatsapp">' +
          '          <i class="bi bi-whatsapp" style="color:#25D366"></i>' +
          "        </a>" +
          "      </div>" +
          '      <div class="read-more">' +
          '        <a href="blog-details.php?postid=' + data.items[i].id + '">Read More</a>' +
          "      </div>" +
          "    </div>" +
          "  </article>" +
          "</div>";

        blogposthtml.insertAdjacentHTML("beforeend", html);

        var labelhtml = "";
        if (data.items[i].labels) {
          data.items[i].labels.forEach((label) => {
            labelhtml += "<li><i class='bi bi-tags'></i>" + label + "</li>";
          });
          document.getElementById("tags3-" + data.items[i].id).innerHTML =
            labelhtml;
        }

        document.getElementById("content" + data.items[i].id).innerHTML =
          data.items[i].content;

        const divremove = document.querySelectorAll(".separator");
        divremove.forEach((div) => div.remove());

        var string = document.getElementById("content" + data.items[i].id).innerHTML;
        var stripped = strip_tags(string, "<p>");
        var str = stripped.split("<p></p>").join("");

        document.getElementById("content" + data.items[i].id).innerHTML =
          limit(str, 250) + ".....";

        getBlogView(data.items[i].id);
      }

      if (data.nextPageToken === undefined) {
        removeInfiniteScroll();
      } else {
        var html5 =
          '<div class="spinner-border text-primary mx-auto" role="status" id="spin2">' +
          '<span class="sr-only"></span>' +
          "</div>";
        blogposthtml.insertAdjacentHTML("beforeend", html5);
      }
    }
  };

  // 🔹 Call proxy instead of Blogger API
  xhttp.open("GET", "fetch_blogger.php?" + params, true);
  xhttp.send();
}


var throttleTimer;
const throttle = (callback, time) => {
  if (throttleTimer) return;
  throttleTimer = true;
  setTimeout(() => {
    callback();
    throttleTimer = false;
  }, time);
};
const removeInfiniteScroll = () => {
  // loader.remove();
  window.removeEventListener("scroll", handleInfiniteScroll);
};
