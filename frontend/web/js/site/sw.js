var CACHE_VERSION = '1';                    // PARA CONTROLAR LOS CAMBIOS DE ALGUN FICHERO CACHEADO Y LO DETECTE
var CACHE_NAME    = 'sktrap-sw';
var urlsToCache   = [
    '/css/site/site.css',
    '/fonts/RobotoSlab-Bold.ttf',
    '/fonts/RobotoSlab-Light.ttf',
    '/fonts/RobotoSlab-Thin.ttf',
    '/fonts/RobotoSlab-Regular.ttf',
    '/fonts/supercell-magic.ttf',
    '/images/clash-royale-logo.png',
    '/images/battle.png',
    '/images/perfil.png',
    '/images/logo.png',
    '/images/loading.gif',
    '/images/equipo.png',
    '/images/trophy.png',
    '/images/versus.png',
    '/android-icon-36x36.png',
    '/android-icon-48x48.png',
    '/android-icon-72x72.png',
    '/android-icon-96x96.png',
    '/android-icon-144x144.png',
    '/android-icon-192x192.png',
    '/apple-icon-57x57.png',
    '/apple-icon-60x60.png',
    '/apple-icon-72x72.png',
    '/apple-icon-76x76.png',
    '/apple-icon-114x114.png',
    '/apple-icon-120x120.png',
    '/apple-icon-144x144.png',
    '/apple-icon-180x180.png',
    '/apple-icon.png',
    '/browserconfig.xml',
    '/favicon-16x16.png',
    '/favicon-32x32.png',
    '/favicon-96x96.png',
    '/favicon.ico',
    '/manifest.json',
    '/ms-icon-70x70.png',
    '/ms-icon-144x144.png',
    '/ms-icon-150x150.png',
    '/ms-icon-310x310.png',
];


self.addEventListener('install', function (event) {
    // event.waitUntil -> TOMA UNA PROMESA Y LA USA PARA SABER CUANTO TIEMPO TARDA LA
    //                    INSTALACION Y SI SE REALIZO CORRECTAMENTE
    event.waitUntil(
        caches.open(CACHE_NAME)
        .then(function (cache) {
            console.log('Abriendo cache. Version: ' + CACHE_VERSION);
            return cache.addAll(urlsToCache)        // Anadiendo archivos
                   .then(() => self.skipWaiting()); // Saltar cuando se encuentre actualizaciones
        })
    );
});
