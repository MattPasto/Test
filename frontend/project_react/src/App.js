import React from 'react';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import { Toaster } from 'react-hot-toast';
import HomePage from './Pages/HomePage';
import FilialiPage from './Pages/FilialiPage';
import AutomezziPage from './Pages/AutomezziPage';

/**
 * Funzione con le rotte 
 */
function App() {
  return (
    <Router>
      <Routes>
        <Route path="/" element={<HomePage />} />
        <Route path="/filiali" element={<FilialiPage />} />
        <Route path="/automezzi" element={<AutomezziPage />} />
      </Routes>
      <Toaster/>
    </Router>
  
  );
}

export default App;
