import React, { useState } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { useNavigate } from 'react-router-dom';
import { login } from '../../store/slices/authSlice';

export default function LoginPage() {
  const dispatch = useDispatch();
  const navigate = useNavigate();
  const { status, error } = useSelector((s) => s.auth);
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');

  const onSubmit = async (e) => {
    e.preventDefault();
    const result = await dispatch(login({ email, password }));
    if (login.fulfilled.match(result)) {
      navigate('/');
    }
  };

  return (
    <div style={{
      minHeight: '100vh', display: 'grid', placeItems: 'center',
      background: 'linear-gradient(135deg, #072A66, #1E6FD9)',
    }}>
      <form onSubmit={onSubmit} style={{
        width: 'min(420px, 92vw)', background: '#fff', padding: '2rem',
        borderRadius: 12, boxShadow: '0 20px 50px rgba(0,0,0,0.25)',
      }}>
        <h1 style={{ marginTop: 0, color: '#0B3D91', fontSize: '1.4rem' }}>
          Airline Reservation &amp; Operations Platform
        </h1>
        <p style={{ color: '#5b6b7c' }}>Sign in to the operations console</p>
        <label style={{ display: 'block', marginBottom: 8 }}>Email</label>
        <input className="arop-input" style={{ width: '100%', marginBottom: 12 }} type="email" value={email} onChange={(e) => setEmail(e.target.value)} required />
        <label style={{ display: 'block', marginBottom: 8 }}>Password</label>
        <input className="arop-input" style={{ width: '100%', marginBottom: 16 }} type="password" value={password} onChange={(e) => setPassword(e.target.value)} required />
        {error && <p style={{ color: '#C8102E' }}>{error}</p>}
        <button className="arop-btn" style={{ width: '100%' }} type="submit" disabled={status === 'loading'}>
          {status === 'loading' ? 'Signing in…' : 'Sign in'}
        </button>
      </form>
    </div>
  );
}
