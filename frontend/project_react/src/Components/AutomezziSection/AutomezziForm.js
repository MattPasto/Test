import PropTypes from 'prop-types';
import { useEffect, useState } from 'react';
import { Row, Col } from 'reactstrap';
import {
    Button,
    TextField,
    Dialog,
    DialogTitle,
    DialogContent,
    DialogActions,
    MenuItem,
    Select,
    InputLabel,
    FormControl
} from '@mui/material';
import { LoadingButton } from '@mui/lab';
import toast from 'react-hot-toast';
import CleanPayload from '../../Utility/CleanPayload';
import '../../Utility/styles.css';

/**
 * Costante con i messaggi ed il testo da mostrare
 */
const textAndMessages = {
    save: 'Salva',
    newAutomezzo: 'INSERISCI NUOVO AUTOMEZZO',
    uptAutomezzo: 'MODIFICA AUTOMEZZO',
    codice: 'Codice',
    targa: "Targa",
    marca: "Marca",
    modello: "Modello",
    selectLabel: "Seleziona Filiale",
    errorValidation: "Controlla i campi d'inserimento",
    errorMessage: "Errore nella connessione al DB"
};

/**
 * Scheletro di un record Filiale
 */
const defaultRecord = {
    id: undefined,
    filiale_id: undefined,
    codice: "",
    targa: "",
    marca: "",
    modello: "",
};

/**
 * Array con le chiavi da tenere nella fare di pulizia del payload
 */
const keysPayload = ["id", "filiale_id", "codice", "targa", "marca", "modello"];

// Url per gli endpoint necessari
const CREATE_URL = "http://localhost:8081/api/automezzo/create";
const UPDATE_URL = "http://localhost:8081/api/automezzo/update";
const FILIALI_URL = "http://localhost:8081/api/filiale/list";

/**
 * Props del componente
 */
AutomezziForm.propTypes = {
    automezzo: PropTypes.shape({
        id: PropTypes.number,
        codice: PropTypes.string,
        targa: PropTypes.string,
        marca: PropTypes.string,
        modello: PropTypes.string,
    }),
    onCloseFunction: PropTypes.func.isRequired,
};

/**
 * @returns Ritorna il form per aggiungere o aggiornare un automezzo
 */
export default function AutomezziForm({ automezzo, onCloseFunction }) {

    const [record, setRecord] = useState(() => {

        // Se viene passato un oggetto, lo uso per popolare i campi
        return automezzo !== undefined ? automezzo : defaultRecord;
    });

    const [loading, setLoading] = useState(false);
    const [filiali, setFiliali] = useState([]);

    useEffect(() => {

        fetchFiliali();
    }, []);

    /**
     * Funzione per il recupero delle Filiali
     */
    const fetchFiliali = async () => {
        const response = await fetch(FILIALI_URL);
        const result = await response.json();
        if (response.status === 200) {
            setFiliali(result.data);
        } else {
            toast.error(textAndMessages.errorMessage);
        }
    };

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

    return <>
        <Dialog maxWidth={'100%'} open onClose={onCloseFunction}>
            <DialogTitle>
                <h4>
                    {record.id === undefined ? textAndMessages.newAutomezzo : textAndMessages.uptAutomezzo}
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
                            label={textAndMessages.targa}
                            type="text"
                            value={record.targa ?? ''}
                            onChange={(e) => {
                                setRecord({ ...record, targa: e.target.value });
                            }}
                        />
                    </Col>
                </Row>
                <Row className='row'>
                    <Col>
                        <TextField
                            fullWidth
                            label={textAndMessages.marca}
                            type="text"
                            value={record.marca ?? ''}
                            onChange={(e) => {
                                setRecord({ ...record, marca: e.target.value });
                            }}
                        />
                    </Col>
                    <Col>
                        <TextField
                            fullWidth
                            label={textAndMessages.modello}
                            type="text"
                            value={record.modello ?? ''}
                            onChange={(e) => {
                                setRecord({ ...record, modello: e.target.value });
                            }}
                        />
                    </Col>
                </Row>

                {/** Select per le Filiali */}
                <Row className='row'>
                    <Col>
                        <FormControl fullWidth>
                            <InputLabel>{textAndMessages.selectLabel}</InputLabel>
                            <Select
                                value={record.filiale_id ?? ''}
                                label={textAndMessages.selectLabel}
                                onChange={(e) => {
                                    setRecord({ ...record, filiale_id: e.target.value });
                                }}
                            >
                                {/** Mappo le opzioni della select */}
                                {filiali.map((filiale) => (
                                    <MenuItem key={filiale.id} value={filiale.id}>
                                        {filiale.codice}
                                    </MenuItem>
                                ))
                                }
                            </Select>
                        </FormControl>
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
    </>
}
