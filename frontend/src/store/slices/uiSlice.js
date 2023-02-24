import { createSlice } from '@reduxjs/toolkit';

const uiSlice = createSlice({
  name: 'ui',
  initialState: {
    sidebarCollapsed: false,
    toasts: [],
    modal: null,
    theme: 'light',
  },
  reducers: {
    toggleSidebar(state) {
      state.sidebarCollapsed = !state.sidebarCollapsed;
    },
    pushToast(state, action) {
      state.toasts.push({ id: Date.now(), ...action.payload });
    },
    dismissToast(state, action) {
      state.toasts = state.toasts.filter((t) => t.id !== action.payload);
    },
    openModal(state, action) {
      state.modal = action.payload;
    },
    closeModal(state) {
      state.modal = null;
    },
  },
});

export const { toggleSidebar, pushToast, dismissToast, openModal, closeModal } = uiSlice.actions;
export default uiSlice.reducer;
