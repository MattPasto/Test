import { useState } from "react"
import PropTypes from 'prop-types';
import {
    Dialog,
    DialogTitle,
    DialogContent,
    DialogContentText,
    DialogActions,
} from '@mui/material';
import { LoadingButton } from '@mui/lab';

/**
 * Props del componente
 */
Confirm.propTypes = {
    title: PropTypes.string.isRequired,
    body:  PropTypes.any.isRequired,
    confirm: PropTypes.shape({
        callback: PropTypes.func,
        text: PropTypes.string,
        closeOnClick: PropTypes.bool,
    }),
    cancel: PropTypes.shape({
        callback: PropTypes.func,
        text: PropTypes.string,
        closeOnClick: PropTypes.bool,
    }),
    closeFunction: PropTypes.func.isRequired,
};

/**
 * @returns Ritorna un dialog di conferma per un operazione 
 */
export default function Confirm({title, body, confirm, cancel, closeFunction}){
    const [loading, setLoading] = useState(false)

    return (
            <Dialog
                open
                onClose={closeFunction}
            >
                <DialogTitle>{title}</DialogTitle>
                <DialogContent>
                    <DialogContentText>
                        {body}
                    </DialogContentText>
                </DialogContent>
                <DialogActions>
                    {/** Bottone per annullare */}
                    <LoadingButton
                        onClick={async ()=>{
                            if(cancel.callback instanceof Function){
                                setLoading(true)
                                await cancel.callback()
                                setLoading(false)
                            }

                            if(cancel.closeOnClick ?? true){
                                closeFunction(undefined)
                            }
                        }}
                        color="error"
                        variant="contained"
                        loading={loading}
                    >
                        {cancel.text ?? 'No'}
                    </LoadingButton>
                    {/** Bottone per confermare */}
                    <LoadingButton
                        onClick={async ()=>{
                            if(confirm.callback instanceof Function){
                                setLoading(true)
                                await confirm.callback()
                                setLoading(false)
                            }

                            if(confirm.closeOnClick ?? true){
                                closeFunction(undefined)
                            }
                        }}
                        color="primary"
                        variant="contained"
                        loading={loading}
                    >
                        {confirm.text ?? 'Si'}
                    </LoadingButton>
                </DialogActions>
            </Dialog>
    );
}