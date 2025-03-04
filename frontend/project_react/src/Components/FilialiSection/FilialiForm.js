import PropTypes from 'prop-types';
import { useState } from 'react';
import { Row, Col } from 'reactstrap';
import {
    Button,
    TextField,
    Dialog,
    DialogTitle,
    DialogContent,
    DialogActions,
} from '@mui/material';
import { LoadingButton } from '@mui/lab';
import toast from 'react-hot-toast';
import CleanPayload from '../../Utility/CleanPayload';
import '../../Utility/styles.css'

/**
 * Costante con i messaggi ed il testo da mostrare
 */
const textAndMessages = {
    save: 'Salva',
    newFiliale: 'INSERISCI NUOVA FILIALE',
    uptFiliale: 'MODIFICA FILIALE',
    codice: 'Codice',
    indirizzo: "Indirizzo",
    città: "Città",
    cap: "Cap",
    errorValidation: "Controlla i campi d'inserimento",
    errorMessage: "Errore nella connessione al DB"
};

/**
 * Scheletro di un record Filiale
 */
const defaultRecord = {
    id: undefined,
    codice: "",
    indirizzo: "",
    città: "",
    cap: "",
};

/**
 * Array con le chiavi da tenere nella fare di pulizia del payload
 */
const keysPayload = ["id", "codice", "indirizzo", "città", "cap"];

// Url per gli endpoint necessari
const CREATE_URL = "http://localhost:8081/api/filiale/create";
const UPDATE_URL = "http://localhost:8081/api/filiale/update";

/**
 * Props del componente
 */
FilialiForm.propTypes = {
    filiale: PropTypes.shape({
        id: PropTypes.number,
        codice: PropTypes.string,
        indirizzo: PropTypes.string,
        città: PropTypes.string,
        cap: PropTypes.string,
    }),
    onCloseFunction: PropTypes.func.isRequired,
};

/**
 * @returns Ritorna il form per aggiungere o aggiornare una filiale
 */
export default function FilialiForm({ filiale, onCloseFunction }) {
    const [record, setRecord] = useState(() => {

        // Se viene passato un oggetto lo uso per popolare i campi
        return filiale !== undefined ? filiale : defaultRecord;
    });

    const [loading, setLoading] = useState(false);

    /**
    * Funzione che si occupa di fare una chiamata di aggiornamento o creazione in base ai parametri che riceve
    */
    const fetchCall = async (url, method) => {

        // Pulisco il payload
        const cleanRecord = CleanPayload({ record: record, keysToSave: keysPayload });

        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(cleanRecord),
        });

        const result = await response.json();

        if (response.status <= 400) {
            // Stampo un toast di successo e chiudo il componente
            toast.success(result.message);
            onCloseFunction();
        } else if (response.status === 422) {
            // Errore nelle validazioni
            toast.error(textAndMessages.errorValidation);
        } else {
            // Se l'errore non dipende dalle validazioni
            toast.error(result.message ?? textAndMessages.errorMessage);
        }
    };

    return (
        <Dialog maxWidth={'100%'} open onClose={onCloseFunction}>
            <DialogTitle>
                <h4>
                    {record.id === undefined ? textAndMessages.newFiliale : textAndMessages.uptFiliale}
                </h4>
                <div style={{ position: 'absolute', top: '0', right: '0' }}>
                    <Button variant="text" onClick={() => onCloseFunction()}>
                        X
                    </Button>
                </div>
            </DialogTitle>
            <DialogContent>
                {/** Gruppo di TexField per modificare i parametri */}
                <Row className='row'>
                    <Col>
                        <TextField
                            fullWidth
                            label={textAndMessages.codice}
                            type="text"
                            value={record.codice ?? ''}
                            onChange={(e) => {
                                setRecord({ ...record, codice: e.target.value });
                            }}
                        />
                    </Col>
                    <Col>
                        <TextField
                            fullWidth
                            label={textAndMessages.indirizzo}
                            type="text"
                            value={record.indirizzo ?? ''}
                            onChange={(e) => {
                                setRecord({ ...record, indirizzo: e.target.value });
                            }}
                        />
                    </Col>
                </Row>
                <Row className='row'>
                    <Col>
                        <TextField
                            fullWidth
                            label={textAndMessages.città}
                            type="text"
                            value={record.città ?? ''}
                            onChange={(e) => {
                                setRecord({ ...record, città: e.target.value });
                            }}
                        />
                    </Col>
                    <Col>
                        <TextField
                            fullWidth
                            label={textAndMessages.cap}
                            type="text"
                            value={record.cap ?? ''}
                            onChange={(e) => {
                                setRecord({ ...record, cap: e.target.value });
                            }}
                        />
                    </Col>
                </Row>
            </DialogContent>
            <DialogActions>
                {/** Bottone di conferma */}
                <LoadingButton
                    onClick={async () => {
                        setLoading(true);
                        
                        let url = record && record.id !== undefined ? UPDATE_URL : CREATE_URL;
                        let method = record && record.id !== undefined ? 'PUT' : 'POST';

                        await fetchCall(url, method);

                        setLoading(false);
                    }}
                    color="primary"
                    variant="contained"
                    loading={loading}
                >
                    {textAndMessages.save}
                </LoadingButton>
            </DialogActions>
        </Dialog>
    );
}
