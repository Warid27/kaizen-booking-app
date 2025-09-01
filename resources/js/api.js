// API Configuration and Client
import axios from 'axios';

// Create axios instance with base configuration
const apiClient = axios.create({
    baseURL: '/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    }
});

// Request interceptor to add auth token
apiClient.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('authToken');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Response interceptor for error handling
apiClient.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        if (error.response?.status === 401) {
            // Token expired or invalid
            localStorage.removeItem('authToken');
            localStorage.removeItem('mockAuth');
            localStorage.removeItem('mockRole');
            localStorage.removeItem('mockUserName');
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);

// Authentication API
export const authAPI = {
    async register(userData) {
        const response = await apiClient.post('/register', userData);
        return response.data;
    },

    async login(credentials) {
        const response = await apiClient.post('/login', credentials);
        if (response.data.token) {
            localStorage.setItem('authToken', response.data.token);
            localStorage.setItem('mockAuth', 'true');
            localStorage.setItem('mockRole', response.data.user.role || 'user');
            localStorage.setItem('mockUserName', response.data.user.name);
        }
        return response.data;
    },

    async logout() {
        try {
            await apiClient.post('/logout');
        } finally {
            localStorage.removeItem('authToken');
            localStorage.removeItem('mockAuth');
            localStorage.removeItem('mockRole');
            localStorage.removeItem('mockUserName');
        }
    },

    async getUser() {
        const response = await apiClient.get('/user');
        return response.data;
    }
};

// Rooms API
export const roomsAPI = {
    async getAll() {
        const response = await apiClient.get('/rooms');
        return response.data;
    },

    async getById(id) {
        const response = await apiClient.get(`/rooms/${id}`);
        return response.data;
    },

    async create(roomData) {
        const response = await apiClient.post('/rooms', roomData);
        return response.data;
    },

    async update(id, roomData) {
        const response = await apiClient.put(`/rooms/${id}`, roomData);
        return response.data;
    },

    async delete(id) {
        const response = await apiClient.delete(`/rooms/${id}`);
        return response.data;
    }
};

// Bookings API
export const bookingsAPI = {
    async getAll() {
        const response = await apiClient.get('/bookings');
        return response.data;
    },

    async getById(id) {
        const response = await apiClient.get(`/bookings/${id}`);
        return response.data;
    },

    async create(bookingData) {
        const response = await apiClient.post('/bookings', bookingData);
        return response.data;
    },

    async update(id, bookingData) {
        const response = await apiClient.put(`/bookings/${id}`, bookingData);
        return response.data;
    },

    async delete(id) {
        const response = await apiClient.delete(`/bookings/${id}`);
        return response.data;
    }
};

// Schedule API (public endpoint)
export const scheduleAPI = {
    async getSchedule() {
        const response = await apiClient.get('/schedule');
        return response.data;
    }
};

// Profile API
export const profileAPI = {
    async updateProfile(profileData) {
        const response = await apiClient.put('/profile', profileData);
        return response.data;
    },

    async changePassword(passwordData) {
        const response = await apiClient.put('/profile/password', passwordData);
        return response.data;
    }
};

// Settings API
export const settingsAPI = {
    async getSettings() {
        const response = await apiClient.get('/settings');
        return response.data;
    },

    async updateSettings(settingsData) {
        const response = await apiClient.put('/settings', settingsData);
        return response.data;
    }
};

// Error handling utility
export const handleApiError = (error, fallbackMessage = 'An error occurred') => {
    if (error.response?.data?.message) {
        return error.response.data.message;
    } else if (error.response?.data?.errors) {
        // Laravel validation errors
        const errors = error.response.data.errors;
        return Object.values(errors).flat().join(', ');
    } else if (error.message) {
        return error.message;
    }
    return fallbackMessage;
};

export default apiClient;
