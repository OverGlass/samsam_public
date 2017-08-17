/**
 * Created by antonincarlin on 20/01/2017.
 */
// Install the service worker.
self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open('v1').then(function(cache) {
            // The cache will fail if any of these resources can't be saved.
            return cache.addAll([
                // Path is relative to the origin, not the app directory.
                '/assets/',
                '/assets/css/app.css',
                '/assets/js/app.js',
                '/assets/js/OverFilter.js',
                '/assets/js/lodash.custom.min.js',
                '/assets/bower_components/jquery/dist/jquery.min.js',
                '/assets/bower_components/motion-ui/dist/motion-ui.min.js',
                '/assets/bower_components/foundation-sites/dist/js/foundation.min.js',
                '/assets/bower_components/foundation-sites/dist/css/foundation.min.css',
                '/assets/font/Ubuntu.ttf',
                '/assets/font/Ubuntu.woff',
                '/assets/font/Ubuntu.woff2',
                '/assets/font/Ubuntu-Bold.ttf',
                '/assets/font/Ubuntu-Bold.woff',
                '/assets/font/Ubuntu-Bold.woff2',
                '/assets/font/Ubuntu-Light.ttf',
                '/assets/font/Ubuntu-Light.woff',
                '/assets/font/Ubuntu-Light.woff2',
                '/assets/font/Ubuntu-Italic.ttf',
                '/assets/font/Ubuntu-Italic.woff',
                '/assets/font/Ubuntu-Italic.woff2',
                '/assets/img/photobiere.png',
                '/assets/img/logo_samsamcafe.svg',
                '/assets/img/down.svg',
                '/assets/ico/ico-search.svg',
                '/assets/ico/ico-beer.svg',
                '/assets/ico/ico-contact.svg',
                '/assets/ico/ico-jeu.svg',
                '/assets/ico/ico-news.svg',
                '/assets/img/back.svg',
                '/assets/img/bg.jpg',
                '/assets/img/jeux.jpg',
                '/assets/manifest.json'
            ])
                .then(function() {
                    console.log('Success! App is available offline!');
                })
        })
    );
});


// Define what happens when a resource is requested.
// For our app we do a Cache-first approach.
self.addEventListener('fetch', function(event) {
    event.respondWith(
        // Try the cache.
        caches.match(event.request)
            .then(function(response) {
                // Fallback to network if resource not stored in cache.
                return response || fetch(event.request);
            })
    );
});
