var RecordsManager={
    /**
     * @type {PopIn}
     */
    popIn:null,
    /**
     * show the pop in
     */
    open:function(){
        "use strict";
        if(!RecordsManager.popIn){
            RecordsManager.popIn=PopInManager.loadPopIn("RecordsManager","/ginette/admin?p=records&action=popIn")
        }
        RecordsManager.popIn.show();
    },
    /**
     * hide the pop in
     */
    close:function(){
        "use strict";
        RecordsManager.popIn.hide();
    },
    onSelectItem:function(recordId){
        "use strict";

    }

}

//-------------------events on an item----------------------------------


Jaquy.body.on("click","[records-manager-item]",function(e){
    "use strict";
    e.preventDefault;
    RecordsManager.onSelectItem($(this).attr("records-manager-item"));
})

//--------------------events outside the pop in--------------------------

/**
 * A button to import a record
 * [records-manager-action="import-image"]
 */
Jaquy.body.on("click",'[records-manager-action="import-record"]',function(e){
    "use strict";
    var field=GinetteFieldRecord.getParent($(this));

    RecordsManager.open();
    RecordsManager.onSelectItem=function(recordId){
        RecordsManager.close();
        alert("You just select the record "+recordId);
        field.setValue(recordId);
    }
})
