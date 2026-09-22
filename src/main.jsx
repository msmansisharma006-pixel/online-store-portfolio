import React from 'react';
import ReactDOM from 'react-dom/client';
import CartWidget from './CartWidget';

const cartContainer = document.getElementById('react-cart-root');

if (cartContainer) {
  ReactDOM.createRoot(cartContainer).render(
    <React.StrictMode>
      <CartWidget />
    </React.StrictMode>
  );
}