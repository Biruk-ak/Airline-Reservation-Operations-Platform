/**
 * Utility helpers for WeatherIntegration module UI logic.
 */

export function normalizeWeatherIntegrationFilters(raw = {}) {
  const out = {};
  const keys = ['status', 'station_code', 'region', 'code', 'name', 'q', 'sort', 'dir', 'priority_min'];
  keys.forEach((k) => {
    if (raw[k] !== undefined && raw[k] !== null && raw[k] !== '') out[k] = raw[k];
  });
  if (raw.is_active === true || raw.is_active === false) out.is_active = raw.is_active;
  return out;
}

export function sortWeatherIntegrationRows(rows, sortKey = 'updated_at', dir = 'desc') {
  const sorted = [...rows].sort((a, b) => {
    const av = a[sortKey];
    const bv = b[sortKey];
    if (av === bv) return 0;
    if (av == null) return 1;
    if (bv == null) return -1;
    if (typeof av === 'number' && typeof bv === 'number') return av - bv;
    return String(av).localeCompare(String(bv));
  });
  return dir === 'asc' ? sorted : sorted.reverse();
}

export function groupWeatherIntegrationByStation(rows) {
  return rows.reduce((acc, row) => {
    const key = row.station_code || 'UNASSIGNED';
    if (!acc[key]) acc[key] = [];
    acc[key].push(row);
    return acc;
  }, {});
}

export function groupWeatherIntegrationByStatus(rows) {
  return rows.reduce((acc, row) => {
    const key = row.status || 'unknown';
    acc[key] = (acc[key] || 0) + 1;
    return acc;
  }, {});
}

export function computeWeatherIntegrationPriorityScore(row) {
  const base = Number(row.priority || 0);
  const activeBoost = row.is_active ? 2 : 0;
  const statusBoost = {
    in_progress: 3,
    pending_review: 2,
    scheduled: 1,
    draft: 0,
    completed: -1,
    cancelled: -2,
    archived: -3,
  }[row.status] || 0;
  return base + activeBoost + statusBoost;
}

export function filterWeatherIntegrationHighAttention(rows) {
  return rows
    .map((r) => ({ ...r, _score: computeWeatherIntegrationPriorityScore(r) }))
    .filter((r) => r._score >= 8 || r.status === 'pending_review')
    .sort((a, b) => b._score - a._score);
}

export function buildWeatherIntegrationExportFilename(prefix = 'weatherintegration') {
  const stamp = new Date().toISOString().replace(/[:.]/g, '-');
  return `${prefix}-export-${stamp}.json`;
}

export function validateWeatherIntegrationForm(form) {
  const errors = {};
  if (!form.name || String(form.name).trim().length < 2) {
    errors.name = 'Name must be at least 2 characters';
  }
  if (form.priority != null) {
    const p = Number(form.priority);
    if (Number.isNaN(p) || p < 0 || p > 10) errors.priority = 'Priority must be 0-10';
  }
  if (form.station_code && !/^[A-Z0-9]{3,4}$/i.test(form.station_code)) {
    errors.station_code = 'Station code must be 3-4 alphanumeric characters';
  }
  return errors;
}

export function mergeWeatherIntegrationMetadata(existing = {}, patch = {}) {
  return {
    ...existing,
    ...patch,
    _updated_at: new Date().toISOString(),
  };
}

export function describeWeatherIntegrationEntity(row) {
  if (!row) return 'No record selected';
  const parts = [row.code, row.name, row.station_code, row.status].filter(Boolean);
  return parts.join(' · ');
}
