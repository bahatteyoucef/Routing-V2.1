// axios
import axios        from    "axios"

// store
import store        from    "../store/store"

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

        console.log(123)

        this.indexedDB      =   window.indexedDB    ||  window.mozIndexedDB ||  window.webkitIndexedDB  ||  window.msIndexedDB  ||  window.shimIndexedDB

        if (!this.indexedDB) {

        }

        else {
            await this.$indexedDB_setDATA()
        }
    }

    async $indexedDB_setDATA() {
        this.openRequestPlanRTM    =   this.indexedDB.open("route_import_db"   , 1);

        console.log(456)

        return new Promise((resolve, reject) => {

            // CASE 1 : DB New (not existe)
            this.openRequestPlanRTM.onupgradeneeded     =   async (event)   =>  {

                // DB Config
                this.route_import_db            =   event.target.result

                // Create Objects
                this.object_route_import        =   this.route_import_db.createObjectStore("route_import"       ,   { keyPath: "id"         })
                this.object_updated_clients     =   this.route_import_db.createObjectStore("updated_clients"    ,   { keyPath: "id"         })
                this.object_added_clients       =   this.route_import_db.createObjectStore("added_clients"      ,   { keyPath: "id"         })
                this.object_deleted_clients     =   this.route_import_db.createObjectStore("deleted_clients"    ,   { keyPath: "id"         })

                this.object_willayas            =   this.route_import_db.createObjectStore("willayas"           ,   { keyPath: "DistrictNo" })

                resolve(true)
            };

            // CASE 2 : DB EXISTE 
            this.openRequestPlanRTM.onsuccess           =   async (event)   =>  {

                this.route_import_db            =   event.target.result
          
                await this.$sync()

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

        if(window.navigator.onLine) {

            let updated_clients                     =   await this.$getUpdatedClients()
            let added_clients                       =   await this.$getAddedClients()
            let deleted_clients                     =   await this.$getDeletedClients()

            let formData = new FormData();

            formData.append("updated_clients"       ,   JSON.stringify(updated_clients))
            formData.append("added_clients"         ,   JSON.stringify(added_clients))
            formData.append("deleted_clients"       ,   JSON.stringify(deleted_clients))

            const res   = await this.$callApi('post'    ,   '/indexedDB/sync'   ,   formData)         
            console.log(res)

            if(res.status  ==  200) {

                //
                this.$clearListeRouteImport()
                // this.$clearWillayas()

                //
                this.$clearUpdatedClients()
                this.$clearAddedClients()
                this.$clearDeletedClients()

                //
                await this.$getListeRouteImportFromDB()
                await this.$getWillayasFromDB()

                return 200
            }
        }
    }

    async $getListeRouteImportFromDB() {

        console.log(store.getters[`authentification/getUser`].id_route_import)

        // Fill Liste Route Import
        axios.post("/route_import/"+store.getters[`authentification/getUser`].id_route_import+"/indexedDB/show",    null)
        .then((res)=> {

            // Add to indexedDB
            this.$setListeRouteImport([res.data])
        })
    }

    async $getWillayasFromDB() {

        if(window.navigator.onLine) {

            let willayas    =   await this.$getWillayas()

            if(willayas.length  ==  0) {

                // Fill Liste Willayas
                axios.post("/rtm_willayas/rtm_cites/details/indexedDB", null)
                .then((res)=> {

                    console.log(res)

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

        return new Promise(async (resolve, error) => {

            let results                                 =   []

            this.transaction_route_import               =   this.route_import_db.transaction("route_import", "readwrite")
            this.store_route_import                     =   this.transaction_route_import.objectStore("route_import")

            let liste_route_import                      =   this.store_route_import.openCursor()

            results                                     =   await this.$fillListeRouteImport(liste_route_import)

            console.log(results)

            this.data_route_import                      =   results
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

        this.transaction_route_import               =   this.route_import_db.transaction("route_import", "readwrite")
        this.store_route_import                     =   this.transaction_route_import.objectStore("route_import")

        this.store_route_import.clear()
    }

    async $getRouteImport(id_route_import) {

        return new Promise(async (resolve, error) => {

            let result                                  =   []

            this.transaction_route_import               =   this.route_import_db.transaction("route_import", "readwrite")
            this.store_route_import                     =   this.transaction_route_import.objectStore("route_import")

            let id_route_import_number                  =   parseInt(id_route_import, 10)

            if(isNaN(id_route_import_number)) {

                resolve([])
            }

            let route_import                            =   this.store_route_import.get(id_route_import_number)

            result                                      =   await this.$findRouteImport(route_import)

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

        return new Promise(async (resolve, error) => {

            let result                                  =   []

            this.transaction_route_import               =   this.route_import_db.transaction("route_import", "readwrite")
            this.store_route_import                     =   this.transaction_route_import.objectStore("route_import")

            let id_route_import_number                  =   parseInt(id_route_import, 10)

            if(isNaN(id_route_import_number)) {

                resolve([])
            }

            let route_import                            =   this.store_route_import.get(id_route_import_number)

            result                                      =   await this.$findRouteImport(route_import)


            if(result   !=  {}) {

                resolve(result.clients)
            }

            else {

                resolve([])
            }
        })   
    }

    async $getClientsByStatus(id_route_import, filter_status) {

        return new Promise(async (resolve, error) => {

            let result                                  =   []

            this.transaction_route_import               =   this.route_import_db.transaction("route_import", "readwrite")
            this.store_route_import                     =   this.transaction_route_import.objectStore("route_import")

            let id_route_import_number                  =   parseInt(id_route_import, 10)

            if(isNaN(id_route_import_number)) {

                resolve([])
            }

            let route_import                            =   this.store_route_import.get(id_route_import_number)

            result                                      =   await this.$findRouteImport(route_import)

            if(result   !=  {}) {

                let clients         =   []

                for (let index = 0; index < result.clients.length; index++) {

                    if(result.clients[index].status     ==  filter_status) {

                        clients.push(result.clients[index])
                    }
                }

                resolve(clients)
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

        // Set Client in Route Import
        this.transaction_route_import               =   this.route_import_db.transaction("route_import", "readwrite")
        this.store_route_import                     =   this.transaction_route_import.objectStore("route_import")

        let route_import                            =   await this.$getRouteImport(id_route_import)

        route_import.clients.push({...client})

        this.store_route_import.put(route_import)
    }

    async $getAddedClients()  {

        return new Promise(async (resolve, error) => {

            let results                                 =   []

            this.transaction_added_clients              =   this.route_import_db.transaction("added_clients", "readwrite")
            this.store_added_clients                    =   this.transaction_added_clients.objectStore("added_clients")

            let liste_added_clients                     =   this.store_added_clients.openCursor()

            results                                     =   await this.$fillListeAddedClients(liste_added_clients)

            console.log(results)

            this.data_added_clients                     =   results
            resolve(results)
        })     
    }

    async $fillListeAddedClients(liste_added_clients) {

        return new Promise((resolve, error) => {

            let results =   []

            liste_added_clients.onsuccess =    function (e)    {
                var cursor = e.target.result;

                if (cursor) {
                    results.push(cursor.value) 
                    cursor.continue();

                    console.log(cursor.value)
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

        return new Promise(async (resolve, error) => {

            let result                                  =   []

            this.transaction_added_client               =   this.route_import_db.transaction("added_client", "readwrite")
            this.store_added_client                     =   this.transaction_added_client.objectStore("added_client")

            let added_client                            =   this.store_added_client.get(id_client)

            result                                      =   await this.$findAddedClient(added_client)

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

        return new Promise(async (resolve, error) => {

            let results     =   await this.$getAddedClients()
            console.log(results)

            resolve(parseInt(results[results.length - 1].id.match(/(\d+)$/)[1]))
        })
    }

    async $fillMaxAddedClients(liste_added_clients) {

        let maxRecord   =   null;
        let maxValue    =   -1000;

        return new Promise((resolve, error) => {

            liste_added_clients.onsuccess =    function (e)    {
                var cursor = e.target.result;

                console.log(e.target)

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

                        console.log(matches)

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

        return new Promise(async (resolve, error) => {

            let results                                 =   []

            this.transaction_updated_clients            =   this.route_import_db.transaction("updated_clients", "readwrite")
            this.store_updated_clients                  =   this.transaction_updated_clients.objectStore("updated_clients")

            let liste_updated_clients                   =   this.store_updated_clients.openCursor()

            results                                     =   await this.$fillListeUpdatedClients(liste_updated_clients)

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

        console.log(route_import)

        for (let i = 0; i < route_import.clients.length; i++) {

            if(route_import.clients[i].id   ==  client.id) {

                console.log(route_import.clients[i])
                console.log(client)

                route_import.clients.splice(1, i)
                break;
            }
        }

        this.store_route_import.put(route_import)
    }

    async $getDeletedClients()  {

        return new Promise(async (resolve, error) => {

            let results                                 =   []

            this.transaction_deleted_clients            =   this.route_import_db.transaction("deleted_clients", "readwrite")
            this.store_deleted_clients                  =   this.transaction_deleted_clients.objectStore("deleted_clients")

            let liste_deleted_clients                   =   this.store_deleted_clients.openCursor()

            results                                     =   await this.$fillListeDeletedClients(liste_deleted_clients)

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

        return new Promise(async (resolve, error) => {

            let results                                 =   []

            this.transaction_willayas                   =   this.route_import_db.transaction("willayas", "readwrite")
            this.store_willayas                         =   this.transaction_willayas.objectStore("willayas")

            let wilayas                                 =   this.store_willayas.openCursor()

            results                                     =   await this.$fillWillayas(wilayas)

            let data_willayas                           =   results

            this.data_willayas                          =   data_willayas.sort(this.$sortWillayasByDistrictNo)

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

        this.transaction_willayas                   =   this.route_import_db.transaction("willayas", "readwrite")
        this.store_willayas                         =   this.transaction_willayas.objectStore("willayas")

        this.store_willayas.clear()
    }

    async $getWillaya(DistrictNo) {

        return new Promise(async (resolve, error) => {

            let result                                  =   []

            this.transaction_willayas                   =   this.route_import_db.transaction("willayas", "readwrite")
            this.store_willayas                         =   this.transaction_willayas.objectStore("willayas")

            let willayas                                =   this.store_willayas.get(DistrictNo)

            result                                      =   await this.$findWillaya(willayas)

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

    // Sorting function with clear comments
    $sortWillayasByDistrictNo(a, b) {

        // Safely handle cases where DistrictNo is non-numeric
        const districtNoA   =   parseInt(a.DistrictNo, 10) || Number.MAX_SAFE_INTEGER; // Infinity for non-numeric values
        const districtNoB   =   parseInt(b.DistrictNo, 10) || Number.MAX_SAFE_INTEGER; // Infinity for non-numeric values

        // Sort in ascending order, with non-numeric values appearing at the end
        if (districtNoA     <   districtNoB) return     -1;
        if (districtNoA     >   districtNoB) return     1;
     
        return 0;
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