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
import FilialiForm from './FilialiForm';
import FilialiDetails from './FilialiDetails';
import '../../Utility/styles.css'

/**
 * Costante con i messaggi ed il testo da mostrare
 */
const textAndMessages = {
  titolo: "Lista Filiali",
  codice: "Codice",
  indirizzo: "Indirizzo",
  città: "Città",
  cap: "Cap",
  automezziTot: "TOT. AUTOMEZZI",
  insert: "Inserisci Filiale",
  errorMessage: "Errore nel recupero delle informazioni",
  titleAlert: "ATTENZIONE",
  messageConfirm: "Ricorda che per cancellare una filiale, essa deve essere priva di automezzi",
  details: "Dettaglio",
  update: "Aggiorna",
  delete: "Elimina",
  yes: "Conferma",
  no: "Annulla"
}

// Url per gli endpoint necessari
const LIST_URL = "http://localhost:8081/api/filiale/list";
const DELETE_URL = "http://localhost:8081/api/filiale/delete";

/**
 * @returns Componente per visualizzare la lista degli Automezzi
 */
export default function FilialiList() {
  const [data, setData] = useState([]);
  const [loading, setLoading] = useState(true);
  const [getDataTable, setGetDataTable] = useState(false);
  const [anchorEl, setAnchorEl] = useState(null);
  const [selectedFiliale, setSelectedFiliale] = useState(null);
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
   * @param {object} filiale 
   */
  const handleClick = (event, filiale) => {
    setAnchorEl(event.currentTarget);
    setSelectedFiliale(filiale);
  };

  /**
   * Gestisce la chiusura del menu
   */
  const handleClose = () => {
    setAnchorEl(null);
    setSelectedFiliale(null);
  };

  /**
   * Funzioni per le opzioni del menu
   */
  const handleDettaglio = () => {
    setForm(
      <FilialiDetails
        id={selectedFiliale.id}
        onCloseFunction={() => {
          setGetDataTable(false)
          setForm(undefined)
        }}
      />)
    handleClose();
  };

  /**
  * Apre il form di aggiornamento dell'elemento selezionato
  */
  const handleAggiorna = () => {
    setForm(
      <FilialiForm
        filiale={selectedFiliale}
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

            return deleteData(selectedFiliale.id)

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
                  <TableCell>{textAndMessages.indirizzo}</TableCell>
                  <TableCell>{textAndMessages.città}</TableCell>
                  <TableCell>{textAndMessages.cap}</TableCell>
                  <TableCell>{textAndMessages.automezziTot}</TableCell>
                  <TableCell></TableCell>
                </TableRow>
              </TableHead>
              <TableBody>
                {data.map((filiale) => (
                  <TableRow key={filiale.id}>
                    <TableCell>{filiale.codice}</TableCell>
                    <TableCell>{filiale.indirizzo}</TableCell>
                    <TableCell>{filiale.città}</TableCell>
                    <TableCell>{filiale.cap}</TableCell>
                    <TableCell>{filiale.automezzi_tot}</TableCell>
                    <TableCell>
                      <IconButton onClick={(event) => handleClick(event, filiale)}>
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
                <FilialiForm
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
