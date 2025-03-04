import React from 'react';
import { Link } from 'react-router-dom';
import { Button, Box } from '@mui/material';

/**
 * Costante con i messaggi ed il testo da mostrare
 */
const textAndMessages = {
  select: 'Seleziona la sezione',
  manFiliali: 'Gestione Filiali',
  manAutomezzi: 'Gestione Automezzi',
};

/**
 * Funzione che ritorna il contenuto della HomePage
 */
export default function HomePage() {
  return (
    <div style={{ textAlign: 'center', padding: '3%' }}>
      <h1>{textAndMessages.select}</h1>
      <Box display="flex" justifyContent="center" gap={2}>
        <Link to="/filiali">
          <Button variant="contained" color="primary" size="large">
          {textAndMessages.manFiliali}
          </Button>
        </Link>
        <Link to="/automezzi">
          <Button variant="contained" color="primary" size="large">
          {textAndMessages.manAutomezzi}
          </Button>
        </Link>
      </Box>
    </div>
  );
};