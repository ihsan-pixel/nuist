import React from 'react';
import ReactDOM from 'react-dom/client';
import PresensiApp from './PresensiApp';

document.addEventListener('DOMContentLoaded', function() {
    const rootElement = document.getElementById('presensi-react-app');
    if (rootElement) {
        const root = ReactDOM.createRoot(rootElement);
        root.render(React.createElement(PresensiApp));
    }
});
