import './bootstrap';
import { 
  authAPI,
  roomsAPI,
  bookingsAPI,
  profileAPI,
  settingsAPI,
  scheduleAPI,
  handleApiError
} from './api';

// Expose APIs globally for use in Blade templates
window.authAPI = authAPI;
window.roomsAPI = roomsAPI;
window.bookingsAPI = bookingsAPI;
window.profileAPI = profileAPI;
window.settingsAPI = settingsAPI;
window.scheduleAPI = scheduleAPI;

// Also expose handleApiError if needed
window.handleApiError = handleApiError;
