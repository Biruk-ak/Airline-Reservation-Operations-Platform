import api from '../../services/api';

const BASE = '/crew';

export const CrewSchedulingApi = {
  list: (params) => api.get(BASE, { params }),
  get: (id) => api.get(`${BASE}/${id}`),
  create: (payload) => api.post(BASE, payload),
  update: (id, payload) => api.put(`${BASE}/${id}`, payload),
  remove: (id) => api.delete(`${BASE}/${id}`),
  activate: (id) => api.post(`${BASE}/${id}/activate`),
  deactivate: (id, reason) => api.post(`${BASE}/${id}/deactivate`, { reason }),
  archive: (id, reason) => api.post(`${BASE}/${id}/archive`, { reason }),
  bulk: (action, ids, payload = {}) => api.post(`${BASE}/bulk`, { action, ids, payload }),
  statistics: () => api.get(`${BASE}/statistics`),
  export: (params) => api.get(`${BASE}/export`, { params }),
  timeline: (id) => api.get(`${BASE}/${id}/timeline`),
  clone: (id, overrides = {}) => api.post(`${BASE}/${id}/clone`, { overrides }),
  syncExternal: (id, payload) => api.post(`${BASE}/${id}/sync-external`, payload),
};

export default CrewSchedulingApi;
