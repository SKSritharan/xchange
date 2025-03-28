import axios from 'axios';

// Load the API base URL from environment variables
const baseURL = import.meta.env.VITE_API_URL || '/api/v1';

const apiClient = axios.create({
    baseURL: baseURL,
    headers: {
        Authorization: `Bearer ${localStorage.getItem('xchange_token')}`,
        Accept: 'application/json',
        ContentType: "application/json"
    },
});

export default apiClient;
