importScripts('https://storage.googleapis.com/workbox-cdn/releases/3.3.1/workbox-sw.js');

if (workbox) {
  console.log(`Workbox loaded`);
} else {
  console.log(`Workbox didn't load`);
}

//workbox.routing.registerNavigationRoute('/');

workbox.precaching.precacheAndRoute([

  '/css/font-awesome/css/font-awesome.min.css',
  '/css/font-awesome/fonts/fontawesome-webfont.woff2?v=4.7.0',
  '/css/bootstrap.min.css',
  '/css/mdb.min.css',
  '/font/roboto/Roboto-Light.woff2',
  '/font/roboto/Roboto-Regular.woff2',
  '/font/roboto/Roboto-Medium.woff2',
  '/font/roboto/Roboto-Bold.woff2',
  '/js/jquery-3.2.1.min.js',
  '/js/popper.min.js',
  '/js/bootstrap.min.js',
  '/js/mdb.min.js',
  '/index.php'

]);


workbox.routing.registerRoute(
  /\.(?:js|css)$/,
  workbox.strategies.staleWhileRevalidate({
    cacheName: 'static-resources',
  }),
);

workbox.routing.registerRoute(
  // Cache image files
  /.*\.(?:png|jpg|jpeg|svg|gif)/,
  // Use the cache if it's available
  workbox.strategies.cacheFirst({
    // Use a custom cache name
    cacheName: 'image-cache',
    plugins: [
      new workbox.expiration.Plugin({
        // Cache only 20 images
        maxEntries: 20,
        // Cache for a maximum of a week
        maxAgeSeconds: 7 * 24 * 60 * 60,
      })
    ],
  })
);