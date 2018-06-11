if ('serviceWorker' in navigator) { // Comprobando si el servicio esta disponible en el navegador

            navigator.serviceWorker.register('/js/site/sw.js').then(function (registration) {
        }).catch(function (err) {
            console.log('ServiceWorker registration failed: ', err);
        });
}
