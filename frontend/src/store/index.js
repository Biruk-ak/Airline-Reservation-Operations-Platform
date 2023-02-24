import { configureStore } from '@reduxjs/toolkit';
import authReducer from './slices/authSlice';
import opsReducer from './slices/opsSlice';
import uiReducer from './slices/uiSlice';

export const store = configureStore({
  reducer: {
    auth: authReducer,
    ops: opsReducer,
    ui: uiReducer,
  },
  middleware: (getDefault) => getDefault({
    serializableCheck: false,
  }),
});
