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
    titolo: "Dettagli Automezzo",
    codice: "Codice",
    codiceFiliale: "Codice Filiale",
    targa: "Targa",
    marca: "Marca",
    modello: "Modello",
    indirizzo: "Indirizzo Filiale",
    città: "Città",
    cap: "CAP",
    errorMessage: "Errore nella connessione al DB"
};

/**
 * Url per recuperare i dettagli
 */
const DETAILS_URL = "http://localhost:8081/api/automezzo/details/";

/**
 * Props del componente
 */
AutomezziDetails.propTypes = {
    id: PropTypes.number,
    onCloseFunction: PropTypes.func.isRequired,
};

/**
 * @returns Ritorna un accordion con i dettagli dell'automezzo ricevuto con il parametro id
 */
export default function AutomezziDetails({ id, onCloseFunction }) {

    const [loading, setLoading] = useState(false);
    const [details, setDetails] = useState([]);

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
            setDetails(result.data);
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
                                {/** Tabella con i dettagli */}
                                <Table>
                                    <TableHead>
                                        <TableRow>
                                            <TableCell>{textAndMessages.codice}</TableCell>
                                            <TableCell>{textAndMessages.codiceFiliale}</TableCell>
                                            <TableCell>{textAndMessages.targa}</TableCell>
                                            <TableCell>{textAndMessages.marca}</TableCell>
                                            <TableCell>{textAndMessages.modello}</TableCell>
                                            <TableCell>{textAndMessages.indirizzo}</TableCell>
                                            <TableCell>{textAndMessages.città}</TableCell>
                                            <TableCell>{textAndMessages.cap}</TableCell>
                                        </TableRow>
                                    </TableHead>
                                    <TableBody>
                                        {details.map((detail) => (
                                            <TableRow key={detail.id}>
                                                <TableCell>{detail.codice}</TableCell>
                                                <TableCell>{detail.filiale_codice}</TableCell>
                                                <TableCell>{detail.targa}</TableCell>
                                                <TableCell>{detail.marca}</TableCell>
                                                <TableCell>{detail.modello}</TableCell>
                                                <TableCell>{detail.indirizzo}</TableCell>
                                                <TableCell>{detail.città}</TableCell>
                                                <TableCell>{detail.cap}</TableCell>
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
