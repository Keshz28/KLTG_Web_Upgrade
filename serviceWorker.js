const static = "KLTG";
const assets = [
  "/",
  "/index.php",
  "/footer.php",
  "/header.php",
  "/assets/css/main.css",
  "/assets/css/variables.css",
  "/assets/js/main.js",
  "/assets/js/blog.js",
  "/assets/js/blog-details.js",
]
const offlineFallbackPage = "inner-page.html";

self.addEventListener("install", installEvent => {
  installEvent.waitUntil(
    caches.open(static).then(cache => {
      cache.addAll(assets)
    })
  )
})
self.addEventListener('notificationclick', function (event) {
  event.notification.close();

// console.log(event.notification)
  event.waitUntil(
    self.clients.openWindow('https://kltheguide.com.my/')
  );
});
// (A) INSTANT WORKER ACTIVATION
// self.addEventListener("install", evt => self.skipWaiting());

// (B) CLAIM CONTROL INSTANTLY
self.addEventListener("activate", evt => self.clients.claim());

// (C) LISTEN TO PUSH
self.addEventListener("push", evt => {
  const data = evt.data.json();
  console.log("Push", data);
  self.registration.showNotification(data.title, {
    body: data.body,
    icon: data.icon,
    image: data.image,
    badge:data.badge,
    data:data,

  });
  
});

