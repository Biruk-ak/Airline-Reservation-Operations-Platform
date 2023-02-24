import { createSlice, createAsyncThunk } from '@reduxjs/toolkit';
import api from '../../services/api';

export const loadModuleList = createAsyncThunk(
  'ops/loadModuleList',
  async ({ moduleKey, params }) => {
    const { data } = await api.get(`/${moduleKey}`, { params });
    return { moduleKey, ...data };
  }
);

export const loadModuleStats = createAsyncThunk(
  'ops/loadModuleStats',
  async (moduleKey) => {
    const { data } = await api.get(`/${moduleKey}/statistics`);
    return { moduleKey, stats: data.data };
  }
);

const opsSlice = createSlice({
  name: 'ops',
  initialState: {
    lists: {},
    stats: {},
    selectedId: null,
    filters: {},
    status: 'idle',
    error: null,
  },
  reducers: {
    setFilter(state, action) {
      const { moduleKey, key, value } = action.payload;
      state.filters[moduleKey] = { ...(state.filters[moduleKey] || {}), [key]: value };
    },
    clearFilters(state, action) {
      state.filters[action.payload] = {};
    },
    selectEntity(state, action) {
      state.selectedId = action.payload;
    },
  },
  extraReducers: (builder) => {
    builder
      .addCase(loadModuleList.pending, (state) => { state.status = 'loading'; })
      .addCase(loadModuleList.fulfilled, (state, action) => {
        state.status = 'succeeded';
        state.lists[action.payload.moduleKey] = {
          data: action.payload.data,
          meta: action.payload.meta,
        };
      })
      .addCase(loadModuleList.rejected, (state, action) => {
        state.status = 'failed';
        state.error = action.error.message;
      })
      .addCase(loadModuleStats.fulfilled, (state, action) => {
        state.stats[action.payload.moduleKey] = action.payload.stats;
      });
  },
});

export const { setFilter, clearFilters, selectEntity } = opsSlice.actions;
export default opsSlice.reducer;
