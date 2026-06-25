// Blog listing page (blog.php)
// Posts are fetched through the fetch_blogger.php proxy and rendered client-side.

var nextpagetoken = "";

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

// Blogger serves a small thumbnail by default; bump the size token so the
// large hero image isn't pixelated. Falls back to the original URL untouched.
function hiResImage(url, size) {
  if (!url) return url;
  size = size || 1600;
  return url
    .replace(/\/s\d+(-c)?\//, "/s" + size + "/")
    .replace(/\/w\d+-h\d+(-[a-z-]+)?\//, "/s" + size + "/")
    .replace(/=s\d+(-c)?\b/, "=s" + size)
    .replace(/=w\d+-h\d+[^&]*/, "=s" + size);
}

function getBlogView(postid) {
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      var viewshtml = document.getElementById("postidview-" + postid);
      if (viewshtml) {
        if (this.responseText == 0) {
          viewshtml.innerHTML = "";
        } else {
          viewshtml.innerHTML =
            '<i class="bi bi-bar-chart-fill"></i>' + this.responseText;
        }
      }
    }
  };
  xmlhttp.open("GET", "admin/functions.php?postidviews=" + postid, true);
  xmlhttp.send();
}

// ---- Loading skeletons -------------------------------------------------

function blogSkeletons(count) {
  var html = "";
  for (var i = 0; i < count; i++) {
    html +=
      '<div class="col-lg-6">' +
      '  <div class="skeleton-card">' +
      '    <div class="sk-img skeleton-block"></div>' +
      '    <div class="sk-line title skeleton-block"></div>' +
      '    <div class="sk-line skeleton-block"></div>' +
      '    <div class="sk-line skeleton-block"></div>' +
      '    <div class="sk-line short skeleton-block"></div>' +
      "  </div>" +
      "</div>";
  }
  return html;
}

function spinnerHTML() {
  return (
    '<div class="spinner-border text-primary mx-auto" role="status" id="spin2">' +
    '<span class="sr-only"></span>' +
    "</div>"
  );
}

// ---- Featured hero -----------------------------------------------------

function renderHero(item) {
  var hero = document.getElementById("featured-hero");
  if (!hero) return;

  hero.innerHTML =
    '<a class="hero-link" href="blog-details.php?postid=' + item.id + '">' +
    '  <img src="' + hiResImage(item.images[0].url, 1600) + '" alt="" loading="lazy">' +
    '  <div class="hero-overlay">' +
    '    <span class="hero-badge">Featured</span>' +
    '    <h2 class="hero-title">' + item.title + "</h2>" +
    '    <div class="hero-meta">' +
    '      <span><i class="bi bi-clock"></i>' + formatDate(item.published) + "</span>" +
    '      <span id="postidview-' + item.id + '"></span>' +
    "    </div>" +
    "  </div>" +
    "</a>";

  hero.style.display = "block";
  getBlogView(item.id);
}

// ---- Post card (shared by first load and infinite scroll) --------------

function appendPostCard(item) {
  var postlist = document.getElementById("postlist");
  var url = "https://kltheguide.com.my/blog-details.php?postid=" + item.id;

  var html =
    '<div class="col-lg-6">' +
    '  <article class="d-flex flex-column">' +
    '    <div class="post-img text-center">' +
    '      <img src="' + item.images[0].url + '" alt="" class="img-fluid" loading="lazy">' +
    "    </div>" +
    '    <h2 class="title"><a href="blog-details.php?postid=' + item.id + '">' + item.title + "</a></h2>" +
    '    <div class="meta-top">' +
    "      <ul>" +
    '        <li class="d-flex align-items-center"><i class="bi bi-clock"></i>' + formatDate(item.published) + "</li>" +
    '        <li class="d-flex align-items-center" id="postidview-' + item.id + '"></li>' +
    "      </ul>" +
    '      <ul id="tags3-' + item.id + '"></ul>' +
    "    </div>" +
    '    <div class="content" id="content' + item.id + '">...</div><br/>' +
    '    <div class="d-flex justify-content-between align-items-center mt-auto">' +
    '      <div class="social-links">' +
    '        <a href="mailto:?subject=Check Out This Article&body=' + url + '" class="instagram"><i class="bi bi-share-fill" style="color:var(--color-primary)"></i></a>' +
    '        <a href="https://www.facebook.com/sharer/sharer.php?u=' + url + '" class="facebook"><i class="bi bi-facebook" style="color:#4267B2"></i></a>' +
    '        <a href="https://twitter.com/share?text=Check Out This Article&url=' + url + '" class="twitter"><i class="fa-brands fa-x-twitter" style="color:#1DA1F2"></i></a>' +
    '        <a href="whatsapp://send?text=' + url + '" class="whatsapp"><i class="bi bi-whatsapp" style="color:#25D366"></i></a>' +
    "      </div>" +
    '      <div class="read-more"><a href="blog-details.php?postid=' + item.id + '">Read More</a></div>' +
    "    </div>" +
    "  </article>" +
    "</div>";

  postlist.insertAdjacentHTML("beforeend", html);

  // Trim the content to a short preview.
  var contentEl = document.getElementById("content" + item.id);
  contentEl.innerHTML = item.content;
  document.querySelectorAll(".separator").forEach(function (div) {
    div.remove();
  });
  var stripped = strip_tags(contentEl.innerHTML, "<p>");
  var str = stripped.split("<p></p>").join("");
  contentEl.innerHTML = limit(str, 250) + ".....";

  // Labels / tags.
  if (item.labels) {
    var labelhtml = "";
    item.labels.forEach(function (label) {
      labelhtml += "<li><i class='bi bi-tags'></i>" + label + "</li>";
    });
    var tagEl = document.getElementById("tags3-" + item.id);
    if (tagEl) tagEl.innerHTML = labelhtml;
  }

  getBlogView(item.id);

  // Structured data for the post.
  var schema = [{
    "@context": "https://schema.org",
    "@type": "BlogPosting",
    description: limit(item.title, 30),
    headline: limit(item.title, 30),
    image: item.images[0].url,
    datePublished: item.published,
    dateModified: item.published,
    author: [{ "@type": "Organization", name: "Bluedale", url: "https://bluedale.com.my/" }],
  }];
  var s = document.createElement("script");
  s.type = "application/ld+json";
  s.innerText = JSON.stringify(schema);
  document.head.appendChild(s);
}

// ---- Search ------------------------------------------------------------
// Search is done client-side so it matches any substring across the title,
// the post body (context) and the tags — e.g. "camp" matches "Basecamp",
// "hotel" matches every post that mentions a hotel anywhere in its text.

var currentSearch = "";
var allPostsCache = null; // full post list, fetched once and reused

function renderSearchResults(query, items) {
  var postlist = document.getElementById("postlist");
  postlist.innerHTML = "";

  var q = query.toLowerCase();
  var matches = items.filter(function (item) {
    var hay = (item.title || "");
    if (item.content) hay += " " + item.content;
    if (item.labels) hay += " " + item.labels.join(" ");
    // strip html tags so we match the visible text, not markup
    hay = hay.replace(/<[^>]*>/g, " ").toLowerCase();
    return hay.indexOf(q) !== -1;
  });

  if (matches.length) {
    for (var i = 0; i < matches.length; i++) {
      appendPostCard(matches[i]);
    }
  } else {
    postlist.innerHTML =
      '<div class="col-12 text-center py-5">' +
      '<i class="bi bi-search fs-1 text-muted d-block mb-3"></i>' +
      '<p>No posts found for <strong>"' + query + '"</strong></p>' +
      "</div>";
  }

  var label = document.getElementById("blog-search-label");
  if (label) {
    label.textContent =
      matches.length + ' result' + (matches.length === 1 ? "" : "s") +
      ' for "' + query + '"';
  }
}

function searchBlog(query) {
  query = (query || "").trim();
  currentSearch = query;

  var postlist = document.getElementById("postlist");
  var hero = document.getElementById("featured-hero");
  var status = document.getElementById("blog-search-status");

  if (!query) {
    if (status) status.classList.add("d-none");
    window.location.hash = "";
    window.addEventListener("scroll", handleInfiniteScroll);
    initblog3();
    return;
  }

  if (status) status.classList.remove("d-none");
  if (hero) { hero.style.display = "none"; hero.innerHTML = ""; }
  postlist.innerHTML = blogSkeletons(4);
  // Search shows the full matching set at once — no infinite scroll.
  window.removeEventListener("scroll", handleInfiniteScroll);

  // Use the cached post list if we already have it.
  if (allPostsCache) {
    renderSearchResults(query, allPostsCache);
    return;
  }

  const xhttp = new XMLHttpRequest();
  xhttp.onload = function () {
    var data;
    try { data = JSON.parse(this.responseText); } catch (e) { data = null; }

    if (data && data.items) {
      allPostsCache = data.items;
      // The user may have typed/cleared while this was loading.
      if (currentSearch) renderSearchResults(currentSearch, allPostsCache);
    } else {
      postlist.innerHTML =
        '<div class="col-12 text-center py-5"><p>Search is unavailable right now.</p></div>';
    }
  };
  xhttp.open("GET", "fetch_blogger.php?maxResults=500", true);
  xhttp.send();
}

function initSearchPanel() {
  var btn = document.getElementById("blog-search-btn");
  var input = document.getElementById("blog-search-input");
  var clear = document.getElementById("blog-search-clear");

  if (btn) {
    btn.addEventListener("click", function () {
      searchBlog(input.value);
    });
  }

  if (input) {
    input.addEventListener("keydown", function (e) {
      if (e.key === "Enter") searchBlog(input.value);
    });
  }

  if (clear) {
    clear.addEventListener("click", function () {
      input.value = "";
      searchBlog("");
    });
  }
}

// ---- Tag filter sidebar ------------------------------------------------

function gettags() {
  const xhttp = new XMLHttpRequest();
  xhttp.open("GET", "fetch_blogger.php?maxResults=500", true);
  xhttp.onload = function () {
    var data = JSON.parse(this.responseText);
    if (data.length !== 0) {
      var testlabes = [];
      const taghtml = document.getElementById("tagdiv");
      const taghtml2 = document.getElementById("tagdiv2");

      for (var i = 0; i < data.items.length; i++) {
        if (data.items[i].labels) {
          data.items[i].labels.forEach((div) => {
            testlabes.push(div);
          });
        }
      }

      var testlabes2 = [...new Set(testlabes)];
      testlabes2.forEach((div2) => {
        taghtml.insertAdjacentHTML(
          "beforeend",
          '<li><a href="#' + encodeURI(div2) + '" id="tags" onclick="activebutton(this)" role="button">' + div2 + "</a></li>"
        );
        taghtml2.insertAdjacentHTML(
          "beforeend",
          '<a href="#' + encodeURI(div2) + '" class="btn btn-primary mx-1 my-1" id="tags" onclick="activebutton(this)">' + div2 + "</a>"
        );
      });

      document.getElementById("tagspinner").classList.add("d-none");
    }
  };
  xhttp.send();
}

function activebutton(tage) {
  document.querySelectorAll("#tags-act").forEach((div) => {
    div.id = "tags";
    div.style.border = "1px solid rgba(var(--color-secondary-light-rgb), 0.8)";
  });
  if (tage.id == "tags") {
    tage.id = "tags-act";
    tage.style.border = "1px solid var(--color-primary)";
  }
  $("#exampleModal").modal("hide");
  initblog3();
}

// ---- Recent posts sidebar ----------------------------------------------

function recentpost() {
  const xhttp = new XMLHttpRequest();
  xhttp.open("GET", "fetch_blogger.php?type=recent&maxResults=4", true);
  xhttp.onload = function () {
    var data = JSON.parse(this.responseText);
    if (data.length !== 0) {
      nextpagetoken = data.nextPageToken;
      const recenthtml = document.getElementById("recentpost");
      recenthtml.innerHTML = "";

      for (var i = 0; i < data.items.length; i++) {
        var item = data.items[i];
        recenthtml.insertAdjacentHTML(
          "beforeend",
          '<div class="post-item mt-3">' +
          '  <img src="' + item.images[0].url + '" alt="" class="flex-shrink-0" loading="lazy">' +
          "  <div>" +
          '    <h4><a href="blog-details.php?postid=' + item.id + '">' + limit(item.title, 30) + "...</a></h4>" +
          "    <time>" + formatDate(item.published) + "</time>" +
          "  </div>" +
          "</div>"
        );
      }
    }
  };
  xhttp.send();
}

// ---- First load --------------------------------------------------------

function initblog3() {
  currentSearch = "";
  var status = document.getElementById("blog-search-status");
  var input = document.getElementById("blog-search-input");
  if (status) status.classList.add("d-none");
  if (input) input.value = "";

  const xhttp = new XMLHttpRequest();
  var hash = window.location.hash.slice(1);

  var string = "fetch_blogger.php?type=initblog3&maxResults=4";
  if (hash) {
    string += "&labels=" + encodeURIComponent(hash);
  }

  window.localStorage.setItem("nextpageToken", "");

  // Show skeleton placeholders while loading.
  var postlist = document.getElementById("postlist");
  postlist.innerHTML = blogSkeletons(4);

  var hero = document.getElementById("featured-hero");
  if (hero) {
    if (hash) {
      hero.style.display = "none";
      hero.innerHTML = "";
    } else {
      hero.innerHTML = '<div class="hero-skeleton skeleton-block"></div>';
      hero.style.display = "block";
    }
  }

  xhttp.onload = function () {
    var data = JSON.parse(this.responseText);
    if (data.length !== 0 && data.items) {
      nextpagetoken = data.nextPageToken;
      window.localStorage.setItem("nextpageToken", nextpagetoken);

      postlist.innerHTML = "";

      // On the unfiltered first page, the newest post becomes the hero.
      var startIndex = 0;
      if (!hash && data.items.length) {
        renderHero(data.items[0]);
        startIndex = 1;
      }

      for (var i = startIndex; i < data.items.length; i++) {
        appendPostCard(data.items[i]);
      }

      if (data.nextPageToken === undefined) {
        removeInfiniteScroll();
      } else {
        postlist.insertAdjacentHTML("beforeend", spinnerHTML());
      }
    }
  };

  xhttp.open("GET", string, true);
  xhttp.send();
}

// ---- Infinite scroll ---------------------------------------------------

function nextpageblog3() {
  const xhttp = new XMLHttpRequest();
  var spin2 = document.getElementById("spin2");
  if (spin2) spin2.remove();

  var params = "maxResults=4";
  var hash = window.location.hash.slice(1);
  if (hash) {
    params += "&labels=" + encodeURIComponent(hash);
  }
  var token = window.localStorage.getItem("nextpageToken");
  if (token) {
    params += "&pageToken=" + encodeURIComponent(token);
  }

  xhttp.onload = function () {
    var data = JSON.parse(this.responseText);
    if (data.length !== 0 && data.items) {
      nextpagetoken = data.nextPageToken;
      window.localStorage.setItem("nextpageToken", nextpagetoken);

      const postlist = document.getElementById("postlist");
      for (var i = 0; i < data.items.length; i++) {
        appendPostCard(data.items[i]);
      }

      if (data.nextPageToken === undefined) {
        removeInfiniteScroll();
      } else {
        postlist.insertAdjacentHTML("beforeend", spinnerHTML());
      }
    }
  };

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
  window.removeEventListener("scroll", handleInfiniteScroll);
};
