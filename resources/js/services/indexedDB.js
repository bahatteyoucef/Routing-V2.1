import axios from 'axios'

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
                this.object_route_import        =   this.route_import_db.createObjectStore("route_import"       ,   { keyPath: "id" })
                this.object_added_clients       =   this.route_import_db.createObjectStore("added_clients"      ,   { keyPath: "id" })
                this.object_updated_clients     =   this.route_import_db.createObjectStore("updated_clients"    ,   { keyPath: "id" })
            };

            // CASE 2 : DB EXISTE 
            this.openRequestPlanRTM.onsuccess           =   async (event)  =>  {

                this.route_import_db            =   event.target.result

                resolve(true)

            };

            // CASE 3 : Error 
            this.openRequestPlanRTM.onerror             = async ()  =>  {
                resolve(false)
            };

        })
    }

    //

    async $setListeRouteImport(liste_route_import) {

        // Set Reponse
        this.transaction_route_import               =   this.route_import_db.transaction("route_import", "readwrite")
        this.store_route_import                     =   this.transaction_route_import.objectStore("route_import")

        for (let i = 0; i < liste_route_import.length; i++) {

            this.store_route_import.put(liste_route_import[i])
        }
        //
    }

    async $getRouteImport()  {

        let results                                 =   []

        this.transaction_route_import               =   this.route_import_db.transaction("route_import", "readwrite")
        this.store_route_import                     =   this.transaction_route_import.objectStore("route_import")

        let liste_route_import                      =   this.store_route_import.openCursor()

        results                                     =   await this.fillListeRouteImport(liste_route_import)

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

    async $clearListeRouteImport() {

        this.transaction_route_import               =   this.route_import_db.transaction("nouveaux_questionnaires", "readwrite")
        this.store_route_import                     =   this.transaction_route_import.objectStore("nouveaux_questionnaires")

        this.store_route_import.clear()
    }

    //

    async $setAddedClients(liste_added_clients) {

        // Set Reponse
        this.transaction_added_clients              =   this.route_import_db.transaction("added_clients", "readwrite")
        this.store_added_clients                    =   this.transaction_added_clients.objectStore("added_clients")

        for (let i = 0; i < liste_added_clients.length; i++) {

            this.store_added_clients.put(liste_added_clients[i])
        }
        //
    }

    async $getAddedClients()  {

        let results                                 =   []

        this.transaction_added_clients              =   this.route_import_db.transaction("added_clients", "readwrite")
        this.store_added_clients                    =   this.transaction_added_clients.objectStore("added_clients")

        let liste_added_clients                     =   this.store_added_clients.openCursor()

        results                                     =   await this.fillListeUpdatedClients(liste_added_clients)

        return new Promise((resolve, error) => {
            this.data_added_clients                 =   results
            resolve(results)
        })     
    }

    async $fillListeUpdatedClients(liste_added_clients) {

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

    async $clearListeRouteImport() {

        this.transaction_added_clients              =   this.route_import_db.transaction("added_clients", "readwrite")
        this.store_added_clients                    =   this.transaction_added_clients.objectStore("added_clients")

        this.store_added_clients.clear()
    }

    //

    async $setUpdatedClients(liste_updated_clients) {

        // Set Reponse
        this.transaction_updated_clients                =   this.route_import_db.transaction("updated_clients", "readwrite")
        this.store_updated_clients                      =   this.transaction_updated_clients.objectStore("updated_clients")

        for (let i = 0; i < liste_updated_clients.length; i++) {

            this.store_updated_clients.put(liste_updated_clients[i])
        }
        //
    }

    async $getUpdatedClients()  {

        let results                                 =   []

        this.transaction_updated_clients            =   this.route_import_db.transaction("updated_clients", "readwrite")
        this.store_updated_clients                  =   this.transaction_updated_clients.objectStore("updated_clients")

        let liste_updated_clients                   =   this.store_updated_clients.openCursor()

        results                                     =   await this.fillListeUpdatedClients(liste_updated_clients)

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

    async $clearListeRouteImport() {
        this.transaction_updated_clients            =   this.route_import_db.transaction("updated_clients", "readwrite")
        this.store_updated_clients                  =   this.transaction_updated_clients.objectStore("updated_clients")

        this.store_updated_clients.clear()
    }

    //

}