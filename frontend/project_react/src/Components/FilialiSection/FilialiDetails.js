import PropTypes from 'prop-types';
import { useEffect, useState } from 'react';
import { Spinner } from 'reactstrap';
import {
    Button,
    Dialog,
    DialogTitle,
    DialogContent,
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableRow,
} from '@mui/material';
import toast from 'react-hot-toast';
import '../../Utility/styles.css';

/**
 * Costante con i messaggi ed il testo da mostrare
 */
const textAndMessages = {
    titolo: "Dettagli Filiale",
    titoloAutomezzi: "Automezzi disponibili",
    indirizzo: "Indirizzo",
    città: "Città",
    cap: "CAP",
    codice: "Codice",
    codiceAutomezzo: "Codice Automezzo",
    targa: "Targa",
    marca: "Marca",
    modello: "Modello",
    errorMessage: "Errore nella connessione al DB"
};

/**
 * Url per recuperare i dettagli
 */
const DETAILS_URL = "http://localhost:8081/api/filiale/details/";

/**
 * Props del componente
 */
FilialiDetails.propTypes = {
    id: PropTypes.number,
    onCloseFunction: PropTypes.func.isRequired,
};

export default function FilialiDetails({ id, onCloseFunction }) {

    const [loading, setLoading] = useState(false);
    const [filiale, setFiliale] = useState({});
    const [automezzi, setAutomezzi] = useState([]);

    useEffect(() => {

        fetchDetails();
    }, []);

    /**
     * Funzione che recupera i dettagli
     */
    const fetchDetails = async () => {

        setLoading(true);
        const response = await fetch(DETAILS_URL + id);
        const result = await response.json();
        if (response.status === 200) {

            setFiliale(result.data.filiale);
            setAutomezzi(result.data.automezzi);
        } else {
            toast.error(result.message ?? textAndMessages.errorMessage);
        }
        setLoading(false);
    };

    return <>
        <Dialog maxWidth={'100%'} open onClose={onCloseFunction}>
            <DialogTitle>
                <h4>{textAndMessages.titolo}</h4>
                <div style={{ position: 'absolute', top: '0', right: '0' }}>
                    <Button variant="text" onClick={() => onCloseFunction()}>
                        X
                    </Button>
                </div>
            </DialogTitle>
            <DialogContent>
                {
                    (loading) ?
                        (<div className='spinner-div'>
                            <Spinner />
                        </div>) : (
                            <div className="table-div">
                                {/** Tabella per la filiale */}
                                <Table>
                                    <TableHead>
                                        <TableRow>
                                            <TableCell>{textAndMessages.codice}</TableCell>
                                            <TableCell>{textAndMessages.indirizzo}</TableCell>
                                            <TableCell>{textAndMessages.città}</TableCell>
                                            <TableCell>{textAndMessages.cap}</TableCell>
                                        </TableRow>
                                    </TableHead>
                                    <TableBody>

                                        <TableRow key={filiale.id}>
                                            <TableCell>{filiale.codice}</TableCell>
                                            <TableCell>{filiale.indirizzo}</TableCell>
                                            <TableCell>{filiale.città}</TableCell>
                                            <TableCell>{filiale.cap}</TableCell>
                                        </TableRow>
                                    </TableBody>
                                </Table>
                                <h5>{textAndMessages.titoloAutomezzi}</h5>
                                {/** Tabella per gli automezzi disponibili */}
                                <Table>
                                    <TableHead>
                                        <TableRow>
                                            <TableCell>{textAndMessages.codiceAutomezzo}</TableCell>
                                            <TableCell>{textAndMessages.targa}</TableCell>
                                            <TableCell>{textAndMessages.marca}</TableCell>
                                            <TableCell>{textAndMessages.modello}</TableCell>
                                        </TableRow>
                                    </TableHead>
                                    <TableBody>
                                        {automezzi.map((automezzo) => (
                                            <TableRow key={automezzo.id}>
                                                <TableCell>{automezzo.codice}</TableCell>
                                                <TableCell>{automezzo.targa}</TableCell>
                                                <TableCell>{automezzo.marca}</TableCell>
                                                <TableCell>{automezzo.modello}</TableCell>
                                            </TableRow>
                                        ))}
                                    </TableBody>
                                </Table>
                            </div>
                        )
                }
            </DialogContent>
        </Dialog>
    </>

}
