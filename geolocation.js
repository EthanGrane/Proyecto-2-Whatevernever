//const apiIP = "https://api.ipify.org/?format=json";
//Obtener los datos de la localizacion del usuario, por defecto devuelve un array con la latitud i longitut

const apidatos = "https://ipwhois.app/json/";



async function obtenerUbicacion(opcion = "latlon") {
    try {
        const response = await fetch(apidatos);
        const data = await response.json();
        
        if (opcion === "ciudad") {
            return data.city;
        } else if (opcion === "pais") {
            return data.country;
        } else if (opcion === "latlon") {
            return { lat: data.latitude, lon: data.longitude };
        }

    } catch (error) {
        console.error('Error al obtener la geolocalización:', error);
        return null;
    }
}

/* Para que devuelva la latitud i la longitud
(async () => {
        const ubicacion = await obtenerUbicacion("latlon");
        console.log(ubicacion);
    })();
*/