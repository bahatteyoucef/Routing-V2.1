import axios    from 'axios'

export default class MobileClientIndexedDB {
    
    constructor() {

        this.openRequestPlanRTM             =   null

        // 

        this.route_import_db                =   null

        //

        this.object_route_import            =   []

        this.transaction_route_import       =   null
        this.store_route_import             =   null

        this.data_route_import              =   null

        //

        this.object_added_clients           =   []

        this.transaction_added_clients      =   null
        this.store_added_clients            =   null

        this.data_added_clients             =   null

        //

        this.object_updated_clients         =   []

        this.transaction_updated_clients    =   null
        this.store_updated_clients          =   null

        this.data_updated_clients           =   null

        //

        this.object_validated_clients       =   []

        this.transaction_validated_clients  =   null
        this.store_validated_clients        =   null

        this.data_validated_clients         =   null

        //

        this.object_deleted_clients         =   []

        this.transaction_deleted_clients    =   null
        this.store_deleted_clients          =   null

        this.data_deleted_clients           =   null

        //

        this.object_willayas                =   []

        this.transaction_willayas           =   null
        this.store_willayas                 =   null

        this.data_willayas                  =   null

        //

    }

    //

    async $indexedDB_intialiazeSetDATA() {

        this.indexedDB =   window.indexedDB    ||  window.mozIndexedDB ||  window.webkitIndexedDB  ||  window.msIndexedDB  ||  window.shimIndexedDB

        if (!this.indexedDB) {

        }

        else {
            await this.$indexedDB_setDATA()
        }
    }

    async $indexedDB_setDATA() {
        this.openRequestPlanRTM    =   this.indexedDB.open("route_import_db"   , 1);

        return new Promise((resolve, reject) => {

            // CASE 1 : DB New (not existe)
            this.openRequestPlanRTM.onupgradeneeded     =   (event)  =>  {

                // DB Config
                this.route_import_db            =   event.target.result

                // Create Objects
                this.object_route_import        =   this.route_import_db.createObjectStore("route_import"       ,   { keyPath: "id"         })
                this.object_updated_clients     =   this.route_import_db.createObjectStore("updated_clients"    ,   { keyPath: "id"         })
                this.object_added_clients       =   this.route_import_db.createObjectStore("added_clients"      ,   { keyPath: "id"         })
                this.object_validated_clients   =   this.route_import_db.createObjectStore("validated_clients"  ,   { keyPath: "id"         })
                this.object_deleted_clients     =   this.route_import_db.createObjectStore("deleted_clients"    ,   { keyPath: "id"         })

                this.object_willayas            =   this.route_import_db.createObjectStore("willayas"           ,   { keyPath: "DistrictNo" })

                resolve(true)
            };

            // CASE 2 : DB EXISTE 
            this.openRequestPlanRTM.onsuccess           =   async (event)  =>  {

                this.route_import_db            =   event.target.result

                await this.$sync()

                await this.$getListeRouteImportFromDB()
                await this.$getWillayasFromDB()
          
                resolve(true)
            };

            // CASE 3 : Error 
            this.openRequestPlanRTM.onerror             = async ()  =>  {

                resolve(false)
            };
        })
    }

    //

    // Fill DBB

    async $sync() {

        let updated_clients                     =   await this.$getUpdatedClients()
        let added_clients                       =   await this.$getAddedClients()
        let validated_clients                   =   await this.$getValidatedClients()
        let deleted_clients                     =   await this.$getDeletedClients()

        let formData = new FormData();

        formData.append("updated_clients"       ,   JSON.stringify(updated_clients))
        formData.append("added_clients"         ,   JSON.stringify(added_clients))
        formData.append("validated_clients"     ,   JSON.stringify(validated_clients))
        formData.append("deleted_clients"       ,   JSON.stringify(deleted_clients))

        const res   = await this.$callApi('post' ,   '/indexedDB/sync'  ,   formData)         
        
        if(res.status  ===  200) {

            this.$clearUpdatedClients()
            this.$clearAddedClients()
            this.$clearValidatedClients()
            this.$clearDeletedClients()
        }
    }

    async $getListeRouteImportFromDB() {

        if(window.navigator.onLine) {

            // Fill Liste Route Import
            axios.post("/route_import", null)
            .then((res)=> {

                // Add to indexedDB
                this.$setListeRouteImport(res.data)
            })
        }
    }

    async $getWillayasFromDB() {

        if(window.navigator.onLine) {

            let willayas    =   await this.$getWillayas()

            console.log(willayas.length)

            if(willayas.length  ==  0) {

                // Fill Liste Willayas
                axios.post("/rtm_willayas/rtm_cites/details", null)
                .then((res)=> {

                    console.log(res.data)

                    // Add to indexedDB
                    this.$setWillayas(res.data)
                })
            }
        }    
    }

    // Route Import

    $setListeRouteImport(liste_route_import) {

        // Set Reponse
        this.transaction_route_import               =   this.route_import_db.transaction("route_import", "readwrite")
        this.store_route_import                     =   this.transaction_route_import.objectStore("route_import")

        let clients                                 =   []

        for (let i = 0; i < liste_route_import.length; i++) {

            clients =   liste_route_import[i].clients.map((client) => ({ ...client }));

            this.store_route_import.put({ "id" : liste_route_import[i].id, "libelle" : liste_route_import[i].libelle, "owner" : liste_route_import[i].owner, "clients" : clients})
        }
        //
    }

    async $getListeRouteImport()  {

        let results                                 =   []

        this.transaction_route_import               =   this.route_import_db.transaction("route_import", "readwrite")
        this.store_route_import                     =   this.transaction_route_import.objectStore("route_import")

        let liste_route_import                      =   this.store_route_import.openCursor()

        results                                     =   await this.$fillListeRouteImport(liste_route_import)

        return new Promise((resolve, error) => {
            this.data_route_import                  =   results
            resolve(results)
        })     
    }

    async $fillListeRouteImport(liste_route_import) {

        let results =   []

        return new Promise((resolve, error) => {

            liste_route_import.onsuccess =    function (e)    {
                var cursor = e.target.result;

                if (cursor) {
                    results.push(cursor.value) 
                    cursor.continue();
                }

                else {
                    resolve(results)
                }
            };

        })
    }

    $clearListeRouteImport() {

        this.transaction_route_import               =   this.route_import_db.transaction("nouveaux_questionnaires", "readwrite")
        this.store_route_import                     =   this.transaction_route_import.objectStore("nouveaux_questionnaires")

        this.store_route_import.clear()
    }

    async $getRouteImport(id_route_import) {

        let result                                  =   []

        this.transaction_route_import               =   this.route_import_db.transaction("route_import", "readwrite")
        this.store_route_import                     =   this.transaction_route_import.objectStore("route_import")

        let id_route_import_number                  =   parseInt(id_route_import, 10)

        if(isNaN(id_route_import_number)) {

            resolve([])
        }

        let route_import                            =   this.store_route_import.get(id_route_import_number)

        result                                      =   await this.$findRouteImport(route_import)

        return new Promise((resolve, error) => {
            resolve(result)
        })     
    }

    async $findRouteImport(route_import) {

        return new Promise((resolve, error) => {

            route_import.onsuccess =    function (event)    {

                const data = event.target.result;

                if (data) {

                    // Data was found, you can now process it
                    resolve(data)
                } else {

                    // Data was not found for the specified key
                    resolve({})
                }
            };

        })

    }

    async $getClients(id_route_import)  {

        let result                                  =   []

        this.transaction_route_import               =   this.route_import_db.transaction("route_import", "readwrite")
        this.store_route_import                     =   this.transaction_route_import.objectStore("route_import")

        let id_route_import_number                  =   parseInt(id_route_import, 10)

        if(isNaN(id_route_import_number)) {

            resolve([])
        }

        let route_import                            =   this.store_route_import.get(id_route_import_number)

        result                                      =   await this.$findRouteImport(route_import)

        return new Promise((resolve, error) => {

            if(result   !=  {}) {

                resolve(result.clients)
            }

            else {

                resolve([])
            }
        })   
    }

    //

    // Added Clients

    async $setAddedClients(client, id_route_import) {

        // Set Reponse
        this.transaction_added_clients              =   this.route_import_db.transaction("added_clients", "readwrite")
        this.store_added_clients                    =   this.transaction_added_clients.objectStore("added_clients")

        this.store_added_clients.put({...client})

        console.log(client)

        // Set Client in Route Import
        this.transaction_route_import               =   this.route_import_db.transaction("route_import", "readwrite")
        this.store_route_import                     =   this.transaction_route_import.objectStore("route_import")

        let route_import                            =   await this.$getRouteImport(id_route_import)
        route_import.clients.push({...client})

        this.store_route_import.put(route_import)
    }

    async $getAddedClients()  {

        let results                                 =   []

        this.transaction_added_clients              =   this.route_import_db.transaction("added_clients", "readwrite")
        this.store_added_clients                    =   this.transaction_added_clients.objectStore("added_clients")

        let liste_added_clients                     =   this.store_added_clients.openCursor()

        results                                     =   await this.$fillListeAddedClients(liste_added_clients)

        return new Promise((resolve, error) => {
            this.data_added_clients                 =   results
            resolve(results)
        })     
    }

    async $fillListeAddedClients(liste_added_clients) {

        let results =   []

        return new Promise((resolve, error) => {

            liste_added_clients.onsuccess =    function (e)    {
                var cursor = e.target.result;

                if (cursor) {
                    results.push(cursor.value) 
                    cursor.continue();
                }

                else {
                    resolve(results)
                }
            };

        })
    }

    $clearAddedClients() {

        this.transaction_added_clients              =   this.route_import_db.transaction("added_clients", "readwrite")
        this.store_added_clients                    =   this.transaction_added_clients.objectStore("added_clients")

        this.store_added_clients.clear()
    }

    async $getAddedClient(id_client) {

        let result                                  =   []

        this.transaction_added_client               =   this.route_import_db.transaction("added_client", "readwrite")
        this.store_added_client                     =   this.transaction_added_client.objectStore("added_client")

        let added_client                            =   this.store_added_client.get(id_client)

        result                                      =   await this.$findAddedClient(added_client)

        return new Promise((resolve, error) => {
            resolve(result)
        })     
    }

    async $findAddedClient(added_client) {

        return new Promise((resolve, error) => {

            added_client.onsuccess =    function (event)    {

                const data = event.target.result;

                if (data) {

                    // Data was found, you can now process it
                    resolve(data)
                } else {

                    // Data was not found for the specified key
                    resolve({})
                }
            };

        })

    }

    async $getMaxAddedClients() {

        let result                                  =   null

        this.transaction_added_clients              =   this.route_import_db.transaction("added_clients", "readwrite")
        this.store_added_clients                    =   this.transaction_added_clients.objectStore("added_clients")

        let liste_added_clients                     =   this.store_added_clients.openCursor()

        result                                      =   await this.$fillMaxAddedClients(liste_added_clients)

        return new Promise((resolve, error) => {

            resolve(result)
        })
    }

    async $fillMaxAddedClients(liste_added_clients) {

        let maxRecord   =   null;
        let maxValue    =   -Infinity;

        return new Promise((resolve, error) => {

            liste_added_clients.onsuccess =    function (e)    {
                var cursor = e.target.result;

                if (cursor) {

                    const key   =   cursor.key

                    if (key > maxValue) {

                        maxRecord   =   cursor.value;
                        maxValue    =   key;
                    }

                    cursor.continue();

                } else {

                    if (maxRecord) {

                        const regex = /(\d+)$/; // Match one or more digits at the end of the string
                        const matches = maxRecord.id.match(regex);

                        if (matches && matches.length > 1) {

                            resolve(parseInt(matches[1])) // Parse the matched number to an integer
                        } else {

                            resolve(0)
                        }


                    } else {
    
                        resolve(0)
                    }
                }
            };

        })
    }

    //

    // Updated Clients

    async $setUpdatedClients(client, id_route_import) {

        // this is an old Client
        if(!isNaN(client.id)) {

            // Set Reponse
            this.transaction_updated_clients            =   this.route_import_db.transaction("updated_clients", "readwrite")
            this.store_updated_clients                  =   this.transaction_updated_clients.objectStore("updated_clients")

            this.store_updated_clients.put({...client})
        }

        // this is Added Client
        else {

            // Set Reponse
            this.transaction_added_clients              =   this.route_import_db.transaction("added_clients", "readwrite")
            this.store_added_clients                    =   this.transaction_added_clients.objectStore("added_clients")

            // let added_client                            =   this.$getAddedClient(client.id)

            // added_client                                =   {...client}

            this.store_added_clients.put({...client})
        }

        // Set Client in Route Import
        this.transaction_route_import               =   this.route_import_db.transaction("route_import", "readwrite")
        this.store_route_import                     =   this.transaction_route_import.objectStore("route_import")

        let route_import                            =   await this.$getRouteImport(id_route_import)

        for (let i = 0; i < route_import.clients.length; i++) {

            if(route_import.clients[i].id   ==  client.id) {

                route_import.clients[i]     =   {...client}
            }
        }

        this.store_route_import.put(route_import)
    }

    async $getUpdatedClients()  {

        let results                                 =   []

        this.transaction_updated_clients            =   this.route_import_db.transaction("updated_clients", "readwrite")
        this.store_updated_clients                  =   this.transaction_updated_clients.objectStore("updated_clients")

        let liste_updated_clients                   =   this.store_updated_clients.openCursor()

        results                                     =   await this.$fillListeUpdatedClients(liste_updated_clients)

        return new Promise((resolve, error) => {
            this.data_updated_clients                   =   results
            resolve(results)
        })     
    }

    async $fillListeUpdatedClients(liste_updated_clients) {

        let results =   []

        return new Promise((resolve, error) => {

            liste_updated_clients.onsuccess =    function (e)    {
                var cursor = e.target.result;

                if (cursor) {
                    results.push(cursor.value) 
                    cursor.continue();
                }

                else {
                    resolve(results)
                }
            };

        })
    }

    $clearUpdatedClients() {
        this.transaction_updated_clients            =   this.route_import_db.transaction("updated_clients", "readwrite")
        this.store_updated_clients                  =   this.transaction_updated_clients.objectStore("updated_clients")

        this.store_updated_clients.clear()
    }

    //

    // Deleted Clients

    async $setDeletedClients(client, id_route_import) {

        // this is an old Client
        if(!isNaN(client.id)) {

            // Set Reponse
            this.transaction_deleted_clients            =   this.route_import_db.transaction("deleted_clients", "readwrite")
            this.store_deleted_clients                  =   this.transaction_deleted_clients.objectStore("deleted_clients")

            this.store_deleted_clients.put({...client})

            // Verify if he exists in updated clients table
            this.transaction_updated_clients            =   this.route_import_db.transaction("updated_clients", "readwrite")
            this.store_updated_clients                  =   this.transaction_updated_clients.objectStore("updated_clients")

            this.store_updated_clients.delete(client.id)
        }

        // this is Added Client
        else {

            // Set Reponse
            this.transaction_added_clients              =   this.route_import_db.transaction("added_clients", "readwrite")
            this.store_added_clients                    =   this.transaction_added_clients.objectStore("added_clients")

            this.store_added_clients.delete(client.id)
        }

        // Set Client in Route Import
        this.transaction_route_import                   =   this.route_import_db.transaction("route_import", "readwrite")
        this.store_route_import                         =   this.transaction_route_import.objectStore("route_import")

        let route_import                                =   await this.$getRouteImport(id_route_import)

        for (let i = 0; i < route_import.clients.length; i++) {

            if(route_import.clients[i].id   ==  client.id) {

                route_import.clients.splice(1, i)
                break;
            }
        }

        this.store_route_import.put(route_import)
    }

    async $getDeletedClients()  {

        let results                                 =   []

        this.transaction_deleted_clients            =   this.route_import_db.transaction("deleted_clients", "readwrite")
        this.store_deleted_clients                  =   this.transaction_deleted_clients.objectStore("deleted_clients")

        let liste_deleted_clients                   =   this.store_deleted_clients.openCursor()

        results                                     =   await this.$fillListeDeletedClients(liste_deleted_clients)

        return new Promise((resolve, error) => {
            this.data_deleted_clients                   =   results
            resolve(results)
        })     
    }

    async $fillListeDeletedClients(liste_deleted_clients) {

        let results =   []

        return new Promise((resolve, error) => {

            liste_deleted_clients.onsuccess =    function (e)    {
                var cursor = e.target.result;

                if (cursor) {
                    results.push(cursor.value) 
                    cursor.continue();
                }

                else {
                    resolve(results)
                }
            };

        })
    }

    $clearDeletedClients() {

        this.transaction_deleted_clients            =   this.route_import_db.transaction("deleted_clients", "readwrite")
        this.store_deleted_clients                  =   this.transaction_deleted_clients.objectStore("deleted_clients")

        this.store_deleted_clients.clear()
    }

    //

    // Validated Clients

    async $setValidatedClients(client, id_route_import) {

        // this is an old Client
        if(!isNaN(client.id)) {

            // Set Reponse
            this.transaction_validated_clients          =   this.route_import_db.transaction("validated_clients", "readwrite")
            this.store_validated_clients                =   this.transaction_validated_clients.objectStore("validated_clients")

            this.store_validated_clients.put({...client})

            // Verify if he exists in updated clients table
            this.transaction_updated_clients            =   this.route_import_db.transaction("updated_clients", "readwrite")
            this.store_updated_clients                  =   this.transaction_updated_clients.objectStore("updated_clients")

            this.store_updated_clients.delete(client.id)
        }

        // this is Added Client
        else {

            // Set Reponse
            this.transaction_added_clients              =   this.route_import_db.transaction("added_clients", "readwrite")
            this.store_added_clients                    =   this.transaction_added_clients.objectStore("added_clients")

            client.status                                 =   "validated"

            this.store_added_clients.put({...client})
        }

        // Set Client in Route Import
        this.transaction_route_import                   =   this.route_import_db.transaction("route_import", "readwrite")
        this.store_route_import                         =   this.transaction_route_import.objectStore("route_import")

        let route_import                                =   await this.$getRouteImport(id_route_import)

        for (let i = 0; i < route_import.clients.length; i++) {

            if(route_import.clients[i].id   ==  client.id) {

                route_import.clients[i].status    =   "validated"
                break;
            }
        }

        this.store_route_import.put(route_import)
    }

    async $getValidatedClients()  {

        let results                                 =   []

        this.transaction_validated_clients          =   this.route_import_db.transaction("validated_clients", "readwrite")
        this.store_validated_clients                =   this.transaction_validated_clients.objectStore("validated_clients")

        let liste_validated_clients                 =   this.store_validated_clients.openCursor()

        results                                     =   await this.$fillListeValidatedClients(liste_validated_clients)

        return new Promise((resolve, error) => {
            this.data_validated_clients                   =   results
            resolve(results)
        })     
    }

    async $fillListeValidatedClients(liste_validated_clients) {

        let results =   []

        return new Promise((resolve, error) => {

            liste_validated_clients.onsuccess =    function (e)    {
                var cursor = e.target.result;

                if (cursor) {
                    results.push(cursor.value) 
                    cursor.continue();
                }

                else {
                    resolve(results)
                }
            };

        })
    }

    $clearValidatedClients() {    
        this.transaction_validated_clients          =   this.route_import_db.transaction("validated_clients", "readwrite")
        this.store_validated_clients                =   this.transaction_validated_clients.objectStore("validated_clients")

        this.store_validated_clients.clear()
    }

    //

    $setWillayas(willayas) {

        // Set Reponse
        this.transaction_willayas                   =   this.route_import_db.transaction("willayas", "readwrite")
        this.store_willayas                         =   this.transaction_willayas.objectStore("willayas")

        for (let i = 0; i < willayas.length; i++) {

            willayas[i].cites.map((cite) => ({ ...cite }));

            this.store_willayas.put(willayas[i])
        }
        //
    }

    async $getWillayas()  {

        let results                                 =   []

        this.transaction_willayas                   =   this.route_import_db.transaction("willayas", "readwrite")
        this.store_willayas                         =   this.transaction_willayas.objectStore("willayas")

        let wilayas                                 =   this.store_willayas.openCursor()

        results                                     =   await this.$fillWillayas(wilayas)

        return new Promise((resolve, error) => {
            this.data_willayas                  =   results
            resolve(results)
        })
    }

    async $fillWillayas(wilayas) {

        let results =   []

        return new Promise((resolve, error) => {

            wilayas.onsuccess =    function (e)    {
                var cursor = e.target.result;

                if (cursor) {
                    results.push(cursor.value) 
                    cursor.continue();
                }

                else {
                    resolve(results)
                }
            };

        })
    }

    $clearWillayas() {

        this.transaction_willayas               =   this.route_import_db.transaction("nouveaux_questionnaires", "readwrite")
        this.store_willayas                     =   this.transaction_willayas.objectStore("nouveaux_questionnaires")

        this.store_willayas.clear()
    }

    async $getWillaya(DistrictNo) {

        let result                                  =   []

        this.transaction_willayas                   =   this.route_import_db.transaction("willayas", "readwrite")
        this.store_willayas                         =   this.transaction_willayas.objectStore("willayas")

        let willayas                                =   this.store_willayas.get(DistrictNo)

        result                                      =   await this.$findWillaya(willayas)

        return new Promise((resolve, error) => {
            resolve(result)
        })     
    }

    async $findWillaya(route_import) {

        return new Promise((resolve, error) => {

            route_import.onsuccess =    function (event)    {

                const data = event.target.result;

                if (data) {

                    // Data was found, you can now process it
                    resolve(data)
                } else {

                    // Data was not found for the specified key
                    resolve({})
                }
            };

        })

    }

    //

    async $callApi(method, url, dataObj ){
        try {
            return await axios({
                method: method,
                url: url,
                data: dataObj
            });
        } catch (e) {
            return e.response
        }
    }

}