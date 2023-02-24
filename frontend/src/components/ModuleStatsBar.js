import React from 'react';

export default function ModuleStatsBar({ stats, loading }) {
  if (loading && !stats) {
    return <div style={{ marginBottom: '1rem', color: '#5b6b7c' }}>Loading statistics…</div>;
  }
  if (!stats) return null;

  const cards = [
    { label: 'Total', value: stats.total },
    { label: 'Active', value: stats.active },
    { label: 'Inactive', value: stats.inactive },
    { label: 'Archived', value: stats.archived },
    { label: 'High Priority', value: stats.high_priority },
    { label: 'Pending Review', value: stats.pending_review },
    { label: 'Avg Priority', value: stats.avg_priority },
  ];

  return (
    <div style={{ display: 'flex', gap: '0.75rem', flexWrap: 'wrap', marginBottom: '1rem' }}>
      {cards.map((c) => (
        <div key={c.label} style={{
          minWidth: 120,
          padding: '0.75rem 1rem',
          background: 'rgba(255,255,255,0.9)',
          border: '1px solid #d7e0ec',
          borderRadius: 8,
        }}>
          <div style={{ fontSize: 12, color: '#5b6b7c' }}>{c.label}</div>
          <div style={{ fontSize: 22, fontWeight: 700, color: '#0B3D91' }}>{c.value ?? '—'}</div>
        </div>
      ))}
    </div>
  );
}
