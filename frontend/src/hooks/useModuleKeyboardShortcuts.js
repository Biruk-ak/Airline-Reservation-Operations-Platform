import { useEffect } from 'react';

export default function useModuleKeyboardShortcuts({ onRefresh, onClear }) {
  useEffect(() => {
    const handler = (e) => {
      if (e.target && ['INPUT', 'TEXTAREA', 'SELECT'].includes(e.target.tagName)) return;
      if (e.key === 'r' && !e.metaKey && !e.ctrlKey) {
        e.preventDefault();
        onRefresh?.();
      }
      if (e.key === 'c' && !e.metaKey && !e.ctrlKey) {
        e.preventDefault();
        onClear?.();
      }
    };
    window.addEventListener('keydown', handler);
    return () => window.removeEventListener('keydown', handler);
  }, [onRefresh, onClear]);
}
