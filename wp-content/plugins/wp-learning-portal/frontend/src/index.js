import React from 'react';
import { createRoot } from 'react-dom/client';

function Progress({ value = 60 }) {
  return (
    <div style={{ border: '1px solid #ddd', borderRadius: 8, padding: 6 }}>
      <div style={{ height: 10, background: '#e9eef3', borderRadius: 6 }}>
        <div
          style={{
            width: `${value}%`,
            height: '100%',
            background: '#0073aa',
            borderRadius: 6,
            transition: 'width 0.3s ease'
          }}
        />
      </div>
      <small>{value}% complete</small>
    </div>
  );
}

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.wplp-progress').forEach(el => {
    const val = Number(el.dataset.value || 60);
    createRoot(el).render(<Progress value={val} />);
  });
});
