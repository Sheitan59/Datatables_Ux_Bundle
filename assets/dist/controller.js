import { Controller } from '@hotwired/stimulus';
import DataTable from 'datatables.net-dt';

let isDataTableInitialized = false;
class DataTableController extends Controller {
    
    static targets =  ['viewValue'];


    connect() {
        if (isDataTableInitialized) {
            document.addEventListener("turbo:before-cache", ()=> {
                this.table.destroy()
        })
        isDataTableInitialized = false;
        } else {
 
        const payload = this.viewValue;

        this.table = new DataTable(this.element, payload);

        isDataTableInitialized = true;
        }

    }
}


DataTableController.values = {
    view: Object,
};
export { DataTableController as default };