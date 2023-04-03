import React, { useEffect, useMemo, useState, useCallback } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { loadModuleList, loadModuleStats, setFilter, clearFilters, selectEntity } from '../../store/slices/opsSlice';
import { pushToast } from '../../store/slices/uiSlice';
import api from '../../services/api';
import ModuleStatsBar from '../../components/ModuleStatsBar';
import EntityDetailDrawer from '../../components/EntityDetailDrawer';
import StatusBadge from '../../components/StatusBadge';
import useModuleKeyboardShortcuts from '../../hooks/useModuleKeyboardShortcuts';
import useDebouncedValue from '../../hooks/useDebouncedValue';
import { formatDistanceToNow } from 'date-fns';

const MODULE_KEY = 'baggage';
const PAGE_TITLE = 'Baggage Tracking';

const STATUS_OPTIONS = [
  '', 'draft', 'active', 'scheduled', 'in_progress', 'pending_review',
  'completed', 'cancelled', 'archived', 'inactive',
];

const SORT_OPTIONS = [
  { value: 'updated_at', label: 'Updated' },
  { value: 'created_at', label: 'Created' },
  { value: 'code', label: 'Code' },
  { value: 'name', label: 'Name' },
  { value: 'priority', label: 'Priority' },
  { value: 'status', label: 'Status' },
];

export default function BaggageTrackingPage() {
  const dispatch = useDispatch();
  const listState = useSelector((s) => s.ops.lists[MODULE_KEY]);
  const stats = useSelector((s) => s.ops.stats[MODULE_KEY]);
  const filters = useSelector((s) => s.ops.filters[MODULE_KEY] || {});
  const selectedId = useSelector((s) => s.ops.selectedId);
  const status = useSelector((s) => s.ops.status);

  const [q, setQ] = useState(filters.q || '');
  const [creating, setCreating] = useState(false);
  const [form, setForm] = useState({
    name: '', code: '', station_code: '', region: '', priority: 5, description: '',
  });
  const [selectedIds, setSelectedIds] = useState([]);
  const [detail, setDetail] = useState(null);
  const [busy, setBusy] = useState(false);

  const debouncedQ = useDebouncedValue(q, 350);

  const queryParams = useMemo(() => ({
    ...filters,
    q: debouncedQ || undefined,
    per_page: 25,
  }), [filters, debouncedQ]);

  const refresh = useCallback(() => {
    dispatch(loadModuleList({ moduleKey: MODULE_KEY, params: queryParams }));
    dispatch(loadModuleStats(MODULE_KEY));
  }, [dispatch, queryParams]);

  useEffect(() => { refresh(); }, [refresh]);

  useModuleKeyboardShortcuts({
    onRefresh: refresh,
    onClear: () => {
      setQ('');
      dispatch(clearFilters(MODULE_KEY));
    },
  });

  const rows = listState?.data || [];
  const meta = listState?.meta;

  const onFilter = (key, value) => {
    dispatch(setFilter({ moduleKey: MODULE_KEY, key, value: value || undefined }));
  };

  const toggleSelect = (id) => {
    setSelectedIds((prev) => prev.includes(id) ? prev.filter((x) => x !== id) : [...prev, id]);
  };

  const selectAll = () => {
    if (selectedIds.length === rows.length) setSelectedIds([]);
    else setSelectedIds(rows.map((r) => r.id));
  };

  const openDetail = async (id) => {
    dispatch(selectEntity(id));
    try {
      const { data } = await api.get(`/${MODULE_KEY}/${id}`);
      setDetail(data);
    } catch (e) {
      dispatch(pushToast({ type: 'error', message: 'Failed to load detail' }));
    }
  };

  const createEntity = async (e) => {
    e.preventDefault();
    setBusy(true);
    try {
      await api.post(`/${MODULE_KEY}`, form);
      dispatch(pushToast({ type: 'success', message: 'BaggageItem created' }));
      setCreating(false);
      setForm({ name: '', code: '', station_code: '', region: '', priority: 5, description: '' });
      refresh();
    } catch (err) {
      dispatch(pushToast({ type: 'error', message: err.response?.data?.message || 'Create failed' }));
    } finally {
      setBusy(false);
    }
  };

  const bulkAction = async (action) => {
    if (!selectedIds.length) return;
    setBusy(true);
    try {
      await api.post(`/${MODULE_KEY}/bulk`, { action, ids: selectedIds });
      dispatch(pushToast({ type: 'success', message: `Bulk ${action} completed` }));
      setSelectedIds([]);
      refresh();
    } catch (err) {
      dispatch(pushToast({ type: 'error', message: 'Bulk action failed' }));
    } finally {
      setBusy(false);
    }
  };

  const activate = async (id) => {
    await api.post(`/${MODULE_KEY}/${id}/activate`);
    refresh();
  };

  const deactivate = async (id) => {
    await api.post(`/${MODULE_KEY}/${id}/deactivate`, { reason: 'Deactivated from UI' });
    refresh();
  };

  const cloneRow = async (id) => {
    await api.post(`/${MODULE_KEY}/${id}/clone`);
    dispatch(pushToast({ type: 'success', message: 'Cloned successfully' }));
    refresh();
  };

  const exportRows = async () => {
    const { data } = await api.get(`/${MODULE_KEY}/export`, { params: queryParams });
    const blob = new Blob([JSON.stringify(data.data, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `baggage-export.json`;
    a.click();
    URL.revokeObjectURL(url);
  };

  return (
    <div className="baggagetracking-page">
      <h1 className="arop-page-title">{PAGE_TITLE}</h1>
      <p className="arop-page-sub">
        Manage baggage tracking records, operational status, priorities, and station-level workflows.
      </p>

      <ModuleStatsBar stats={stats} loading={status === 'loading'} />

      <div className="arop-toolbar">
        <input
          className="arop-input"
          placeholder="Search code, name, reference..."
          value={q}
          onChange={(e) => setQ(e.target.value)}
        />
        <select
          className="arop-select"
          value={filters.status || ''}
          onChange={(e) => onFilter('status', e.target.value)}
        >
          <option value="">All statuses</option>
          {STATUS_OPTIONS.filter(Boolean).map((s) => (
            <option key={s} value={s}>{s}</option>
          ))}
        </select>
        <input
          className="arop-input"
          placeholder="Station"
          value={filters.station_code || ''}
          onChange={(e) => onFilter('station_code', e.target.value.toUpperCase())}
        />
        <input
          className="arop-input"
          placeholder="Region"
          value={filters.region || ''}
          onChange={(e) => onFilter('region', e.target.value)}
        />
        <select
          className="arop-select"
          value={filters.sort || 'updated_at'}
          onChange={(e) => onFilter('sort', e.target.value)}
        >
          {SORT_OPTIONS.map((o) => (
            <option key={o.value} value={o.value}>{o.label}</option>
          ))}
        </select>
        <select
          className="arop-select"
          value={filters.dir || 'desc'}
          onChange={(e) => onFilter('dir', e.target.value)}
        >
          <option value="desc">Desc</option>
          <option value="asc">Asc</option>
        </select>
        <button className="arop-btn" type="button" onClick={refresh} disabled={busy}>Refresh</button>
        <button className="arop-btn secondary" type="button" onClick={() => setCreating((v) => !v)}>
          {creating ? 'Cancel' : 'New BaggageItem'}
        </button>
        <button className="arop-btn secondary" type="button" onClick={exportRows}>Export</button>
        <button className="arop-btn" type="button" disabled={!selectedIds.length || busy} onClick={() => bulkAction('activate')}>
          Activate selected
        </button>
        <button className="arop-btn secondary" type="button" disabled={!selectedIds.length || busy} onClick={() => bulkAction('deactivate')}>
          Deactivate selected
        </button>
        <button className="arop-btn danger" type="button" disabled={!selectedIds.length || busy} onClick={() => bulkAction('archive')}>
          Archive selected
        </button>
      </div>

      {creating && (
        <form onSubmit={createEntity} style={{ marginBottom: '1rem', display: 'grid', gap: '0.6rem', maxWidth: 720 }}>
          <input className="arop-input" required placeholder="Name" value={form.name} onChange={(e) => setForm({ ...form, name: e.target.value })} />
          <input className="arop-input" placeholder="Code (optional)" value={form.code} onChange={(e) => setForm({ ...form, code: e.target.value })} />
          <input className="arop-input" placeholder="Station code" value={form.station_code} onChange={(e) => setForm({ ...form, station_code: e.target.value.toUpperCase() })} />
          <input className="arop-input" placeholder="Region" value={form.region} onChange={(e) => setForm({ ...form, region: e.target.value })} />
          <input className="arop-input" type="number" min={0} max={10} value={form.priority} onChange={(e) => setForm({ ...form, priority: Number(e.target.value) })} />
          <textarea className="arop-input" rows={3} placeholder="Description" value={form.description} onChange={(e) => setForm({ ...form, description: e.target.value })} />
          <button className="arop-btn" type="submit" disabled={busy}>Create BaggageItem</button>
        </form>
      )}

      <div className="arop-table-wrap">
        <table className="arop-table">
          <thead>
            <tr>
              <th><input type="checkbox" checked={rows.length > 0 && selectedIds.length === rows.length} onChange={selectAll} /></th>
              <th>Code</th>
              <th>Name</th>
              <th>Status</th>
              <th>Station</th>
              <th>Region</th>
              <th>Priority</th>
              <th>Version</th>
              <th>Updated</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            {rows.length === 0 && (
              <tr><td colSpan={10}>No baggage tracking records found.</td></tr>
            )}
            {rows.map((row) => (
              <tr key={row.id} style={{ background: selectedId === row.id ? '#eef5ff' : undefined }}>
                <td><input type="checkbox" checked={selectedIds.includes(row.id)} onChange={() => toggleSelect(row.id)} /></td>
                <td><button type="button" className="arop-btn secondary" style={{ padding: '0.2rem 0.5rem' }} onClick={() => openDetail(row.id)}>{row.code}</button></td>
                <td>{row.name}</td>
                <td><StatusBadge status={row.status} active={row.is_active} /></td>
                <td>{row.station_code || '—'}</td>
                <td>{row.region || '—'}</td>
                <td>{row.priority}</td>
                <td>v{row.version}</td>
                <td>{row.effective_from ? formatDistanceToNow(new Date(row.effective_from), { addSuffix: true }) : '—'}</td>
                <td style={{ whiteSpace: 'nowrap' }}>
                  <button type="button" className="arop-btn" style={{ padding: '0.2rem 0.45rem', marginRight: 4 }} onClick={() => activate(row.id)}>On</button>
                  <button type="button" className="arop-btn secondary" style={{ padding: '0.2rem 0.45rem', marginRight: 4 }} onClick={() => deactivate(row.id)}>Off</button>
                  <button type="button" className="arop-btn secondary" style={{ padding: '0.2rem 0.45rem' }} onClick={() => cloneRow(row.id)}>Clone</button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      {meta && (
        <p style={{ marginTop: '0.75rem', color: '#5b6b7c' }}>
          Page {meta.current_page} of {meta.last_page} · {meta.total} total records
        </p>
      )}

      <EntityDetailDrawer
        open={Boolean(detail)}
        title={detail?.summary?.name || 'BaggageItem detail'}
        data={detail}
        onClose={() => setDetail(null)}
      />
    </div>
  );
}
