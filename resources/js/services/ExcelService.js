import * as XLSX from "xlsx";

// Centralized Column List
export const REQUIRED_COLUMNS = [
    'start_adding_time', 'adding_duration', 'comment', 'NewCustomer', 'OpenCustomer',
    'CustomerCode', 'CustomerNameE', 'CustomerNameA', 'Latitude', 'Longitude',
    'Address', 'RvrsGeoAddress', 'Neighborhood', 'Landmark', 'DistrictNo',
    'DistrictNameE', 'CityNo', 'CityNameE', 'Tel', 'tel_status', 'tel_comment',
    'CustomerType', 'JPlan', 'Journee', 'BrandAvailability', 'BrandSourcePurchase',
    'status', 'nonvalidated_details', 'created_at', 'owner', 'CustomerIdentifier',
    'AvailableBrands', 'Frequency', 'SuperficieMagasin', 'NbrAutomaticCheckouts'
];

export const processExcelFile = (file) => {
    return new Promise((resolve, reject) => {
        const fileReader = new FileReader();

        fileReader.onload = (e) => {
            try {
                const data = new Uint8Array(e.target.result);
                // Optimization: Read directly from ArrayBuffer (No for-loop needed)
                const workbook = XLSX.read(data, { type: "array" });
                const firstSheet = workbook.SheetNames[0];
                const worksheet = workbook.Sheets[firstSheet];
                
                // Get Raw Data
                const jsonData = XLSX.utils.sheet_to_json(worksheet, { raw: true });
                resolve(jsonData);
            } catch (err) {
                reject(err);
            }
        };

        fileReader.onerror = (err) => reject(err);
        fileReader.readAsArrayBuffer(file);
    });
};

export const normalizeClientsData = (clients) => {
    // create default object once
    const defaults = REQUIRED_COLUMNS.reduce((acc, col) => {
        acc[col] = (col === 'Latitude' || col === 'Longitude') ? 0 : '';
        return acc;
    }, {});

    return clients.map(client => {
        // 1. Merge defaults
        const newClient = { ...defaults, ...client };

        // 2. Fix GPS (Merged your setGPSStandard logic here)
        ['Latitude', 'Longitude'].forEach(coord => {
            if (newClient[coord]) {
                let val = newClient[coord].toString().replace(",", ".");
                newClient[coord] = isNaN(val) ? 0 : val;
            } else {
                newClient[coord] = 0;
            }
        });

        return newClient;
    });
};

export const validateHeaders = (firstRow) => {
    if (!firstRow) return ["File is empty"];
    
    const fileColumns = Object.keys(firstRow);
    const missing = REQUIRED_COLUMNS.filter(col => !fileColumns.includes(col));
    
    return missing.length > 0 
        ? missing.map(col => `Your file doesn't contain the column '${col}'`) 
        : [];
};