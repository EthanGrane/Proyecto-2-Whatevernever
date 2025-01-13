let lat = 0, lon = 0;

mapboxgl.accessToken = 'pk.eyJ1IjoiZXRoYW5ncmFuZSIsImEiOiJjbTVyMWNsZDAwNmNsMnFxdTl5enQ2dXAxIn0.gCn0a-Ef8cuqw1pEozCo0Q';
window.onload = () => {
    start();
};

async function start() {
    await GetCoordinates();
    ShowMap();
}

function GetCoordinates() {
    return new Promise((resolve, reject) => {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                lat = position.coords.latitude;
                lon = position.coords.longitude;
                resolve(); // Resolvemos la promesa cuando las coordenadas se obtienen
            },
            (error) => {
                console.error("Error al obtener la ubicación:", error);
                reject(error); // Rechazamos la promesa si ocurre un error
            }
        );
    });
}

function ShowMap() {
    const map = (window.map = new mapboxgl.Map({
        container: 'map',
        zoom: 16,
        center: [lon, lat], // Ahora lat y lon tendrán los valores correctos
        pitch: 0,
        bearing: 0,
        hash: true,
        style: 'mapbox://styles/ethangrane/cm5r25hne00ka01plf02k59lw',
        projection: 'globe'
    }));

    const resetRotation = () => {
        map.rotateTo(0, { duration: 0 });
    };

    resetRotation();

    // map.touchZoomRotate.disableRotation();
    // map.dragRotate.disable();
}
