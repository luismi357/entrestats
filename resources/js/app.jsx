import React from 'react';
import ReactDOM from 'react-dom/client';
import FormularioDinamico from './components/FormularioDinamico';

const el = document.getElementById('app');

if (el) {
    ReactDOM.createRoot(el).render(<FormularioDinamico />);
}