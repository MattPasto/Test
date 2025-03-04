
/**
 * Piccolo funzione che si occupa di pulire un payload prima di fare una chiamata
 * @param {object} record
 * @param {array} keysToSave 
 */
const CleanPayload = ({ record, keysToSave }) => {
    const cleanedRecord = {};

    Object.keys(record).forEach(key => {
        
        // Se la chiave non è presente nell'array keysToSave sarà distrutta
        if (
            (keysToSave.includes(key)) &&  
            record[key] !== undefined &&
            record[key] !== ""                                         
        ) {
            cleanedRecord[key] = record[key];
        }
    });

    return cleanedRecord;
};

/**
 * Ritorna una funzione per pulire un oggetto
 */
export default CleanPayload;

