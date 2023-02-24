import React from 'react';

export default function EntityDetailDrawer({ open, title, data, onClose }) {
  if (!open) return null;
  return (
    <div style={{
      position: 'fixed', inset: 0, background: 'rgba(7,42,102,0.35)',
      display: 'flex', justifyContent: 'flex-end', zIndex: 50,
    }} onClick={onClose}>
      <aside
        style={{ width: 'min(480px, 100%)', background: '#fff', height: '100%', padding: '1.25rem', overflow: 'auto' }}
        onClick={(e) => e.stopPropagation()}
      >
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
          <h2 style={{ margin: 0, color: '#0B3D91' }}>{title}</h2>
          <button type="button" className="arop-btn secondary" onClick={onClose}>Close</button>
        </div>
        <pre style={{
          marginTop: '1rem', background: '#F5F8FC', padding: '1rem',
          borderRadius: 8, overflow: 'auto', fontSize: 12,
        }}>
          {JSON.stringify(data, null, 2)}
        </pre>
      </aside>
    </div>
  );
}
