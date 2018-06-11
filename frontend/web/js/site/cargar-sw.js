if ('serviceWorker' in navigator) { // Comprobando si el servicio esta disponible en el navegador
    window.addEventListener('load', function (){
        // EL ALCANCE DEL SERVICE WORKER DEPENDE DE LA UBICACION DEL ARCHIVO DE PROCESO (EN ESTE CASO sw.js)
        navigator.serviceWorker.register('/js/site/sw.js').then(function (registration) {
            // Se ha registrado con exito
            console.log('ServiceWorker registration successful with scope: ', registration.scope);
        }).catch(function (err) {
            // No se ha podido registrar
            console.log('ServiceWorker registration failed: ', err);
        });
    });
}
