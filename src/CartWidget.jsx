import React, { useState } from 'react';

export default function CartWidget() {
  const [count, setCount] = useState(0);

  return (
    <div style={{ background: '#b39dfa', padding: '15px', color: 'white', borderRadius: '8px', margin: '20px 0' }}>
      <h5>React Interactive Cart Widget</h5>
      <p>Items added via React: <strong>{count}</strong></p>
      <button className="btn btn-light btn-sm me-2" onClick={() => setCount(count + 1)}>
        Add Quick Item
      </button>
      <button className="btn btn-outline-light btn-sm" onClick={() => setCount(0)}>
        Reset
      </button>
    </div>
  );
}