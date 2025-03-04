import { toast } from 'react-hot-toast';
import { Spinner } from 'reactstrap';
import React, { useState, useEffect } from 'react';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableRow,
  Button
} from '@mui/material';
import { Menu, MenuItem, IconButton } from '@mui/material';
import MoreVertIcon from '@mui/icons-material/MoreVert';
import Confirm from '../../Utility/Confirm';
import AutomezziForm from './AutomezziForm';
import AutomezziDetails from './AutomezziDetails';
import '../../Utility/styles.css'

/**
 * Costante con i messaggi ed il testo da mostrare
 */
const textAndMessages = {
  titolo: "Lista Automezzi",
  codice: "Codice",
  codiceFiliale: "Codice Filiale",
  targa: "Targa",
  marca: "Marca",
  modello: "Modello",
  insert: "Inserisci Automezzo",
  errorMessage: "Errore nel recupero delle informazioni",
  titleAlert: "ATTENZIONE",
  messageConfirm: "Sei sicuro di voler cancellare questo elemento?",
  details: "Dettaglio",
  update: "Aggiorna",
  delete: "Elimina",
  yes: "Conferma",
  no: "Annulla"
}

// Url per gli endpoint necessari
const LIST_URL = "http://localhost:8081/api/automezzo/list";
const DELETE_URL = "http://localhost:8081/api/automezzo/delete";

/**
 * @returns Componente per visualizzare la lista degli Automezzi
 */
export default function FilialiList() {
  const [data, setData] = useState([]);
  const [loading, setLoading] = useState(true);
  const [getDataTable, setGetDataTable] = useState(false);
  const [anchorEl, setAnchorEl] = useState(null);
  const [selectedAutomezzo, setSelectedAutomezzo] = useState(null);
  const [confirm, setConfirm] = useState(undefined);
  const [form, setForm] = useState(undefined);

  useEffect(() => {
    fetchData();
  }, [!getDataTable]);

  /**
   * Funzione che fa la chiamata per riempire la tabella
   */
  const fetchData = async () => {
    try {
      const response = await fetch(LIST_URL);
      const result = await response.json();

      if (response.status !== 200) {
        throw new Error(await result.message ?? textAndMessages.errorMessage);
      }

      setData(result.data);
      setLoading(false);
      setGetDataTable(true);
    } catch (err) {
      toast.error(err.message);
      setLoading(false);
      setGetDataTable(true);
    }
  };

  /**
   * Funzione per la cancellazione di un record
   * @param {int} id 
   */
  const deleteData = async (id) => {
    try {
      const response = await fetch(DELETE_URL, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          'id': id
        })
      });

      const result = await response.json();

      if (response.status !== 200) {
        throw new Error(await result.message ?? textAndMessages.errorMessage);
      }

      toast.success(result.message);
      setGetDataTable(false);
    } catch (err) {
      toast.error(err.message);
      setGetDataTable(false);
    }
  };

  /**
   * Funzione che gestisce l'evento di selezione del menu
   * @param {object} event 
   * @param {object} automezzo 
   */
  const handleClick = (event, automezzo) => {
    setAnchorEl(event.currentTarget);
    setSelectedAutomezzo(automezzo);
  };

  /**
   * Gestisce la chiusura del menu
   */
  const handleClose = () => {
    setAnchorEl(null);
    setSelectedAutomezzo(null);
  };

  /**
   * Funzioni per le opzioni del menu
   */
  const handleDettaglio = () => {
    setForm(
      <AutomezziDetails
        id={selectedAutomezzo.id}
        onCloseFunction={() => {
          setGetDataTable(false)
          setForm(undefined)
        }}
      />)
    handleClose();
  };

  /**
   * Apre il form di aggiornamento dell'elemento selezioanto
   */
  const handleAggiorna = () => {
    setForm(
      <AutomezziForm
        automezzo={selectedAutomezzo}
        onCloseFunction={() => {
          setGetDataTable(false)
          setForm(undefined)
        }}
      />)
    handleClose();
  };

  /**
   * Apre Dialog di conferma per la cancellazione del record selezionato
   */
  const handleElimina = () => {
    setConfirm(
      <Confirm
        title={textAndMessages.titleAlert}
        body={
          <>
            {textAndMessages.messageConfirm}
          </>
        }
        confirm={{
          callback: async () => {
            setLoading(true)

            return deleteData(selectedAutomezzo.id)

          },
          text: textAndMessages.yes
        }}
        cancel={{
          text: textAndMessages.no
        }}
        closeFunction={() => setConfirm(false)}
      />
    )
    handleClose();
  };

  return <>
    {confirm}
    {form}
    {
      (loading) ?
        (<div className='spinner-div'>
          <Spinner />
        </div>) : (
          <div className="table-div">
            <h1>{textAndMessages.titolo}</h1>
            {/** Tabella con i dati da mostrare */}
            <Table>
              <TableHead>
                <TableRow>
                  <TableCell>{textAndMessages.codice}</TableCell>
                  <TableCell>{textAndMessages.codiceFiliale}</TableCell>
                  <TableCell>{textAndMessages.targa}</TableCell>
                  <TableCell>{textAndMessages.marca}</TableCell>
                  <TableCell>{textAndMessages.modello}</TableCell>
                  <TableCell></TableCell>
                </TableRow>
              </TableHead>
              <TableBody>
                {data.map((automezzo) => (
                  <TableRow key={automezzo.id}>
                    <TableCell>{automezzo.codice}</TableCell>
                    <TableCell>{automezzo.filiale_codice}</TableCell>
                    <TableCell>{automezzo.targa}</TableCell>
                    <TableCell>{automezzo.marca}</TableCell>
                    <TableCell>{automezzo.modello}</TableCell>
                    <TableCell>
                      <IconButton onClick={(event) => handleClick(event, automezzo)}>
                        <MoreVertIcon />
                      </IconButton>
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
            {/** Bottone per il form d'inserimento */}
            <Button
              color="primary"
              variant="contained"
              style={{
                marginTop: '1%',
              }}
              onClick={() => setForm(
                <AutomezziForm
                  onCloseFunction={() => {
                    setGetDataTable(false)
                    setForm(undefined)
                  }}
                />
              )}
            >
              {textAndMessages.insert}
            </Button>
            {/** Eventi dell'IconButton */}
            <Menu
              anchorEl={anchorEl}
              open={Boolean(anchorEl)}
              onClose={handleClose}
            >
              {/** Elementi IconButton */}
              <MenuItem onClick={handleDettaglio}>{textAndMessages.details}</MenuItem>
              <MenuItem onClick={handleAggiorna}>{textAndMessages.update}</MenuItem>
              <MenuItem onClick={handleElimina}>{textAndMessages.delete}</MenuItem>
            </Menu>
          </div>
        )
    }
  </>
};
