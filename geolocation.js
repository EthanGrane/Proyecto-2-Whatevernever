//const apiIP = "https://api.ipify.org/?format=json";
//Obtener los datos de la localizacion del usuario, por defecto devuelve un array con la latitud i longitut

const apidatos = "https://ipwhois.app/json/";

async function obtenerUbicacion(opcion = "latlon") {
    fetch('https://ipwhois.app/json/')
    .then(response => response.json())
    .then(data => {
        if (opcion == "ciudad") {
            return data.city;
        } else if (opcion == "pais") {
            return data.country;
        } else if (opcion == "latlon") {
            return {"lat":data.latitude, "lon":data.longitude};
        }
    })
    .catch(error => console.error('Error al obtener la geolocalización:', error));

}