/**
 * User Location Service
 *
 * A reusable service for getting the user's geolocation.
 * This can be used by any component that needs the user's location.
 */
export default class UserLocationService {
    /**
     * Get the user's current location
     *
     * @param {Function} onSuccess - Callback function when location is successfully retrieved
     * @param {Function} onError - Callback function when location retrieval fails
     * @param {Object} options - Geolocation options
     * @returns {Promise} - A promise that resolves with the position or rejects with an error
     */
    static getCurrentPosition(onSuccess = null, onError = null, options = {}) {
        // Default options
        const defaultOptions = {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0
        };

        // Merge provided options with defaults
        const geolocationOptions = { ...defaultOptions, ...options };

        // Check if geolocation is available in the browser
        if (!navigator.geolocation) {
            const error = new Error('Geolocation is not supported by your browser');
            if (onError) onError(error);
            return Promise.reject(error);
        }

        // Return a promise that resolves with the position or rejects with an error
        return new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(
                // Success callback
                (position) => {
                    // Store the geolocation in the backend
                    this.storeGeolocation(position.coords.latitude, position.coords.longitude)
                        .catch(error => console.error('Failed to store geolocation:', error));

                    if (onSuccess) onSuccess(position);
                    resolve(position);
                },
                // Error callback
                (error) => {
                    let errorMessage = 'Unable to retrieve your location';

                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage = 'User denied the request for Geolocation';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage = 'Location information is unavailable';
                            break;
                        case error.TIMEOUT:
                            errorMessage = 'The request to get user location timed out';
                            break;
                        case error.UNKNOWN_ERROR:
                            errorMessage = 'An unknown error occurred';
                            break;
                    }

                    console.log(errorMessage);

                    if (onError) onError(error);
                    reject(error);
                },
                // Options
                geolocationOptions
            );
        });
    }

    /**
     * Store the user's geolocation in the backend
     * 
     * @param {number} latitude - The user's latitude
     * @param {number} longitude - The user's longitude
     * @returns {Promise} - A promise that resolves when the geolocation is stored
     */
    static storeGeolocation(latitude, longitude) {
        return fetch('/api/geolocation', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                latitude,
                longitude
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to store geolocation');
            }
            return response.json();
        });
    }
}
