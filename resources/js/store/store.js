import { reactive } from 'vue';


const store = reactive({
    // data of user
    user: {},
    userName: 'Accedi',
    // is logged user boolean
    is_logged: false,
    have_apartment: false,

    // boolean for dropdown menu of header
    is_open: false,
    // all apartment visible
    allApartments: [],
    // apartment of singol user
    userApartment: [],

    filtredApartment:[],
    inApartmentPage: false,
    isFilterClose: false,
    // center: [],
    // // filtred apartment near of the center of map
    // filtredApartment: [],

    // payment variable
    isLoading: true,
      paymentError: false,
      successPayment: false,
})

const checkAdress = (adress) =>{
// trasform adress in array
let stringRequest = adress.trim().split(" ").join('%')

let urlRequest = `https://api.tomtom.com/search/2/geocode/${encodeURIComponent(stringRequest)}.json?countrySet=IT&limit=3&storeResult=false&topLeft=47.25145,2.35341&btmRight=36.08512,-6.29537&language=it-IT&extendedPostalCodesFor=str&view=Unified&key=qNjsW3gGJOBNhFoXhBzsGRJAk5RJMJhI`;



return urlRequest
//   array
}

// find a square point zone in the center of map
const isCloser = (latCenter, lonCenter, latLocation, lonLocation, maxDistance) =>{
    Number(latCenter)
    Number(lonCenter)
    Number(latLocation)
    Number(lonLocation)

    const distance = distancePoint(latCenter, lonCenter, latLocation, lonLocation);


    // Confronta la distanza calcolata con la distanza massima specificata dall'utente
    if (distance <= maxDistance) {
        console.log(distance, maxDistance)
        return true;
    } else {
        return false;
    }


}
const distanceOfCenter = (latCenter, lonCenter, latLocation, lonLocation) =>{
    Number(latCenter)
    Number(lonCenter)
    Number(latLocation)
    Number(lonLocation)

    const distance = distancePoint(latCenter, lonCenter, latLocation, lonLocation);
    return distance


}
function distancePoint(lat1, lon1, lat2, lon2) {
    const R = 6371; // Raggio della Terra in km

    // Converti le latitudini e longitudini da gradi a radianti
    const toRadians = (degree) => degree * (Math.PI / 180);

    lat1 = toRadians(lat1);
    lon1 = toRadians(lon1);
    lat2 = toRadians(lat2);
    lon2 = toRadians(lon2);

    // Differenze delle coordinate
    const dlat = lat2 - lat1;
    const dlon = lon2 - lon1;

    // Formula dell'Haversine
    const a = Math.sin(dlat / 2) ** 2 +
              Math.cos(lat1) * Math.cos(lat2) * Math.sin(dlon / 2) ** 2;
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

    // Distanza in km
    const distance = R * c;
    return distance;
}





export {store, checkAdress, isCloser, distanceOfCenter};
