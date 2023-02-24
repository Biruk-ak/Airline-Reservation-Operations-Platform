import React from 'react';

export default function StatusBadge({ status, active }) {
  let cls = 'badge inactive';
  if (active || status === 'active' || status === 'completed') cls = 'badge active';
  else if (status === 'pending_review' || status === 'in_progress' || status === 'scheduled') cls = 'badge warning';
  else if (status === 'cancelled') cls = 'badge danger';
  return <span className={cls}>{status || 'unknown'}</span>;
}
